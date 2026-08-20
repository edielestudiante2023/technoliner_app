<?php

namespace App\Controllers\Admin;

use App\Models\RolModel;
use App\Models\UsuarioModel;

class UsuariosController extends BaseAdminController
{
    public function index()
    {
        $usuarioModel = new UsuarioModel();

        $usuarios = $usuarioModel
            ->select('usuarios.*, roles.nombre as rol_nombre')
            ->join('roles', 'roles.id = usuarios.rol_id')
            ->orderBy('usuarios.nombre', 'ASC')
            ->findAll();

        $contenido = view('admin/usuarios/index', [
            'usuarios'      => $usuarios,
            'usuarioActual' => $this->usuarioActual(),
        ]);

        return view('admin/layout', [
            'titulo'    => 'Usuarios',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function nuevo()
    {
        $rolModel = new RolModel();

        $contenido = view('admin/usuarios/form', [
            'roles'  => $rolModel->where('activo', 1)->findAll(),
            'modo'   => 'crear',
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);

        return view('admin/layout', [
            'titulo'    => 'Nuevo usuario',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function crear()
    {
        $rules = [
            'nombre'   => 'required|max_length[120]',
            'email'    => 'required|valid_email|max_length[190]|is_unique[usuarios.email]',
            'rol_id'   => 'required|is_natural_no_zero',
            'password' => 'required|min_length[12]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $usuarioModel = new UsuarioModel();
        $usuarioModel->insert([
            'nombre'               => $this->request->getPost('nombre'),
            'email'                => $this->request->getPost('email'),
            'rol_id'               => (int) $this->request->getPost('rol_id'),
            'password_hash'        => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'activo'               => 1,
            'password_changed_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/usuarios')->with('mensaje', 'Usuario creado correctamente.');
    }

    public function editar(int $id)
    {
        $usuarioModel = new UsuarioModel();
        $rolModel     = new RolModel();

        $usuarioEditar = $usuarioModel->find($id);

        if (! $usuarioEditar) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuario no encontrado.');
        }

        $contenido = view('admin/usuarios/form', [
            'roles'         => $rolModel->where('activo', 1)->findAll(),
            'modo'          => 'editar',
            'usuarioEditar' => $usuarioEditar,
            'errors'        => session()->getFlashdata('errors') ?? [],
        ]);

        return view('admin/layout', [
            'titulo'    => 'Editar usuario',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }

    public function actualizar(int $id)
    {
        $usuarioModel  = new UsuarioModel();
        $usuarioEditar = $usuarioModel->find($id);

        if (! $usuarioEditar) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuario no encontrado.');
        }

        $rules = [
            'nombre' => 'required|max_length[120]',
            'email'  => "required|valid_email|max_length[190]|is_unique[usuarios.email,id,{$id}]",
            'rol_id' => 'required|is_natural_no_zero',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'email'  => $this->request->getPost('email'),
            'rol_id' => (int) $this->request->getPost('rol_id'),
        ];

        $password = $this->request->getPost('password');

        if (! empty($password)) {
            if (mb_strlen($password) < 12) {
                return redirect()->back()->withInput()->with('errors', ['password' => 'La contraseña debe tener al menos 12 caracteres.']);
            }

            $data['password_hash']       = password_hash($password, PASSWORD_DEFAULT);
            $data['password_changed_at'] = date('Y-m-d H:i:s');
        }

        $usuarioModel->update($id, $data);

        return redirect()->to('/admin/usuarios')->with('mensaje', 'Usuario actualizado correctamente.');
    }

    public function cambiarEstado(int $id)
    {
        $usuarioModel  = new UsuarioModel();
        $usuarioEditar = $usuarioModel->find($id);

        if (! $usuarioEditar) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuario no encontrado.');
        }

        $actual = $this->usuarioActual();

        if ((int) $usuarioEditar['id'] === (int) $actual['id']) {
            return redirect()->to('/admin/usuarios')->with('error', 'No puedes inactivar tu propia cuenta.');
        }

        $nuevoEstado = (int) $usuarioEditar['activo'] === 1 ? 0 : 1;

        if ($nuevoEstado === 0 && $usuarioModel->countAdministradoresActivos() <= 1) {
            return redirect()->to('/admin/usuarios')->with('error', 'Debe existir al menos un administrador activo.');
        }

        $usuarioModel->update($id, ['activo' => $nuevoEstado]);

        $mensaje = $nuevoEstado === 1 ? 'Usuario activado.' : 'Usuario inactivado.';

        return redirect()->to('/admin/usuarios')->with('mensaje', $mensaje);
    }
}
