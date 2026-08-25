<?php

namespace App\Controllers;

use App\Libraries\ResendMailer;
use App\Models\ContactoModel;
use Config\Notifications;
use Config\Services;

class ContactoController extends BaseController
{
    private const TIEMPO_MINIMO_SEGUNDOS = 3;

    public function guardar()
    {
        $throttler = Services::throttler();

        if (! $throttler->check('contacto-' . md5($this->request->getIPAddress()), 5, MINUTE)) {
            return redirect()->to('/#contacto')->with('contacto_error', 'Demasiadas solicitudes. Espera un minuto e inténtalo de nuevo.');
        }

        $renderizadoEn = (int) $this->request->getPost('form_rendered_at');

        if ($renderizadoEn > 0 && (time() - $renderizadoEn) < self::TIEMPO_MINIMO_SEGUNDOS) {
            return redirect()->to('/#contacto')->with('contacto_error', 'No se pudo procesar tu solicitud. Inténtalo de nuevo.');
        }

        if ($this->contieneEscrituraNoLatina((string) $this->request->getPost('nombre') . ' ' . (string) $this->request->getPost('empresa') . ' ' . (string) $this->request->getPost('mensaje'))) {
            log_message('warning', 'Contacto spam bloqueado (escritura no latina). IP: {ip}', ['ip' => $this->request->getIPAddress()]);

            return redirect()->to('/#contacto')->with('contacto_mensaje', 'Gracias, recibimos tu solicitud. Te contactaremos pronto.');
        }

        $rules = [
            'nombre'    => 'required|max_length[120]',
            'correo'    => 'required|valid_email|max_length[190]',
            'mensaje'   => 'required',
            'politica'  => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/#contacto')->withInput()->with('contacto_errors', $this->validator->getErrors());
        }

        $model = new ContactoModel();
        $config = config(Notifications::class);

        $id = $model->insert([
            'producto_id'             => $this->request->getPost('producto_id') ?: null,
            'nombre'                  => $this->request->getPost('nombre'),
            'email'                   => $this->request->getPost('correo'),
            'telefono'                => $this->request->getPost('telefono'),
            'empresa'                 => $this->request->getPost('empresa'),
            'sector'                  => $this->request->getPost('sector'),
            'producto_interes'        => $this->request->getPost('producto'),
            'mensaje'                 => $this->request->getPost('mensaje'),
            'consentimiento_datos_at' => date('Y-m-d H:i:s'),
            'version_politica'        => $config->politicaVersionVigente,
            'origen_url'              => $this->request->getPost('origen_url') ?: site_url('/'),
            'ip_address'              => $this->request->getIPAddress(),
            'user_agent'              => (string) $this->request->getUserAgent(),
            'created_at'              => date('Y-m-d H:i:s'),
        ], true);

        if (! $id) {
            return redirect()->to('/#contacto')->withInput()->with('contacto_errors', $model->errors());
        }

        $this->enviarAvisoInmediato($id, $model);

        return redirect()->to('/#contacto')->with('contacto_mensaje', 'Gracias, recibimos tu solicitud. Te contactaremos pronto.');
    }

    private function enviarAvisoInmediato(int $contactoId, ContactoModel $model): void
    {
        $contacto = $model->find($contactoId);
        $config   = config(Notifications::class);

        $cuerpo = "Se recibió una nueva solicitud desde el sitio web.\n\n"
            . 'Nombre: ' . $contacto['nombre'] . "\n"
            . 'Email: ' . $contacto['email'] . "\n"
            . 'Teléfono: ' . ($contacto['telefono'] ?: '—') . "\n"
            . 'Empresa: ' . ($contacto['empresa'] ?: '—') . "\n"
            . 'Sector: ' . ($contacto['sector'] ?: '—') . "\n"
            . 'Producto de interés: ' . ($contacto['producto_interes'] ?: '—') . "\n"
            . 'Mensaje: ' . $contacto['mensaje'] . "\n"
            . 'Fecha: ' . $this->fechaColombia($contacto['created_at']);

        $resultado = (new ResendMailer())->send(
            $config->contactRecipient,
            'Te contactaron desde la web de Technoliner',
            $cuerpo,
            $contacto['email'],
        );

        if ($resultado['success']) {
            $model->marcarNotificado($contactoId);

            return;
        }

        $model->marcarErrorEnvio($contactoId, 'No se pudo enviar el aviso por correo.');

        log_message('error', 'Aviso de contacto #{id} no se pudo enviar: {error}', [
            'id'    => $contactoId,
            'error' => $resultado['error'],
        ]);
    }

    private function contieneEscrituraNoLatina(string $texto): bool
    {
        return (bool) preg_match('/[\p{Cyrillic}\p{Han}\p{Arabic}\p{Hebrew}\p{Thai}\p{Greek}]/u', $texto);
    }

    private function fechaColombia(string $fechaLocal): string
    {
        return (new \DateTime($fechaLocal))->format('d/m/Y H:i') . ' (hora Colombia)';
    }
}
