<?php

namespace App\Controllers\Admin;

class DashboardController extends BaseAdminController
{
    public function index()
    {
        $contenido = view('admin/dashboard', ['usuario' => $this->usuarioActual()]);

        return view('admin/layout', [
            'titulo'    => 'Inicio',
            'usuario'   => $this->usuarioActual(),
            'contenido' => $contenido,
        ]);
    }
}
