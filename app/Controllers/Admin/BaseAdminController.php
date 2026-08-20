<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

abstract class BaseAdminController extends BaseController
{
    protected function usuarioActual(): ?array
    {
        $session = session();

        if (! $session->get('admin_usuario_id')) {
            return null;
        }

        return [
            'id'     => $session->get('admin_usuario_id'),
            'nombre' => $session->get('admin_nombre'),
            'email'  => $session->get('admin_email'),
        ];
    }
}
