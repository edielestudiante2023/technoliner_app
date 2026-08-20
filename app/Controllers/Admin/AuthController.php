<?php

namespace App\Controllers\Admin;

use App\Libraries\ResendMailer;
use App\Models\TokenRecuperacionModel;
use App\Models\UsuarioModel;
use Config\Services;

class AuthController extends BaseAdminController
{
    private const MAX_INTENTOS       = 5;
    private const BLOQUEO_MINUTOS    = 15;
    private const RECUPERACION_MIN   = 60;

    public function login()
    {
        if (session()->get('admin_usuario_id')) {
            return redirect()->to('/admin');
        }

        return view('admin/auth/login');
    }

    public function attemptLogin()
    {
        $throttler = Services::throttler();

        if (! $throttler->check('login-' . md5($this->request->getIPAddress()), 10, MINUTE)) {
            return redirect()->back()->withInput()->with('error', 'Demasiados intentos. Espera un minuto e inténtalo de nuevo.');
        }

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->findByEmail($email);

        $mensajeGenerico = 'Correo o contraseña incorrectos.';

        if (! $usuario) {
            return redirect()->back()->withInput()->with('error', $mensajeGenerico);
        }

        if (! empty($usuario['bloqueado_hasta']) && strtotime($usuario['bloqueado_hasta']) > time()) {
            return redirect()->back()->withInput()->with('error', 'Cuenta bloqueada temporalmente por demasiados intentos fallidos. Intenta más tarde.');
        }

        if ((int) $usuario['activo'] !== 1) {
            return redirect()->back()->withInput()->with('error', $mensajeGenerico);
        }

        if (! password_verify($password, $usuario['password_hash'])) {
            $intentos = (int) $usuario['intentos_fallidos'] + 1;
            $data     = ['intentos_fallidos' => $intentos];

            if ($intentos >= self::MAX_INTENTOS) {
                $data['bloqueado_hasta']  = date('Y-m-d H:i:s', time() + self::BLOQUEO_MINUTOS * 60);
                $data['intentos_fallidos'] = 0;
            }

            $usuarioModel->update($usuario['id'], $data);

            return redirect()->back()->withInput()->with('error', $mensajeGenerico);
        }

        $usuarioModel->update($usuario['id'], [
            'intentos_fallidos' => 0,
            'bloqueado_hasta'   => null,
            'ultimo_login_at'   => date('Y-m-d H:i:s'),
        ]);

        $session = session();
        $session->regenerate(true);
        $session->set([
            'admin_usuario_id' => $usuario['id'],
            'admin_nombre'     => $usuario['nombre'],
            'admin_email'      => $usuario['email'],
        ]);

        return redirect()->to('/admin')->with('mensaje', 'Bienvenido, ' . $usuario['nombre'] . '.');
    }

    public function logout()
    {
        $session = session();
        $session->remove(['admin_usuario_id', 'admin_nombre', 'admin_email']);
        $session->regenerate(true);

        return redirect()->to('/admin/login')->with('mensaje', 'Sesión cerrada correctamente.');
    }

    public function recuperar()
    {
        return view('admin/auth/recuperar');
    }

    public function attemptRecuperar()
    {
        $throttler = Services::throttler();

        if (! $throttler->check('recuperar-' . md5($this->request->getIPAddress()), 5, MINUTE)) {
            return redirect()->back()->withInput()->with('error', 'Demasiadas solicitudes. Espera un minuto e inténtalo de nuevo.');
        }

        if (! $this->validate(['email' => 'required|valid_email'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email        = $this->request->getPost('email');
        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->findByEmail($email);

        // Mensaje idéntico exista o no la cuenta, para no revelar qué correos están registrados.
        $mensaje = 'Si el correo está registrado, enviaremos instrucciones para restablecer la contraseña.';

        if ($usuario && (int) $usuario['activo'] === 1) {
            $tokenModel = new TokenRecuperacionModel();
            $token      = $tokenModel->crearParaUsuario((int) $usuario['id'], $this->request->getIPAddress(), self::RECUPERACION_MIN);

            $enlace = site_url('admin/restablecer/' . $token);
            $cuerpo = "Se solicitó restablecer tu contraseña de Technoliner.\n\nUsa este enlace (vigente por " . self::RECUPERACION_MIN . " minutos):\n{$enlace}\n\nSi no lo solicitaste, ignora este correo.";

            $resultado = (new ResendMailer())->send($usuario['email'], 'Restablecer contraseña - Technoliner', $cuerpo);

            if (! $resultado['success']) {
                log_message('warning', 'No se pudo enviar el correo de recuperación a {email}: {error}. Enlace: {enlace}', [
                    'email'   => $usuario['email'],
                    'error'   => $resultado['error'],
                    'enlace'  => $enlace,
                ]);
            }
        }

        return redirect()->to('/admin/login')->with('mensaje', $mensaje);
    }

    public function restablecer(string $token)
    {
        $tokenModel = new TokenRecuperacionModel();
        $registro   = $tokenModel->encontrarVigentePorToken($token);

        if (! $registro) {
            return redirect()->to('/admin/recuperar')->with('error', 'El enlace es inválido o ya expiró.');
        }

        return view('admin/auth/restablecer', ['token' => $token]);
    }

    public function attemptRestablecer(string $token)
    {
        $tokenModel = new TokenRecuperacionModel();
        $registro   = $tokenModel->encontrarVigentePorToken($token);

        if (! $registro) {
            return redirect()->to('/admin/recuperar')->with('error', 'El enlace es inválido o ya expiró.');
        }

        $rules = [
            'password'          => 'required|min_length[12]',
            'password_confirm'  => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $usuarioModel = new UsuarioModel();
        $usuarioModel->update($registro['usuario_id'], [
            'password_hash'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'password_changed_at' => date('Y-m-d H:i:s'),
            'intentos_fallidos'   => 0,
            'bloqueado_hasta'     => null,
        ]);

        $tokenModel->marcarUsado($registro['id']);

        return redirect()->to('/admin/login')->with('mensaje', 'Contraseña actualizada. Ya puedes iniciar sesión.');
    }
}
