<?php

namespace App\Controllers\Admin;

use App\Models\ContactoModel;

class ContactosController extends BaseAdminController
{
    public function index()
    {
        $contactoModel = new ContactoModel();

        $contactos = $contactoModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $contenido = view('admin/contactos/index', [
            'contactos' => $contactos,
        ]);

        return view('admin/layout', [
            'titulo'       => 'Contactos',
            'usuario'      => $this->usuarioActual(),
            'contenido'    => $contenido,
            'extraHead'    => '<link rel="stylesheet" href="' . base_url('assets/vendor/datatables/dataTables.dataTables.min.css') . '">',
            'extraScripts' => '<script src="' . base_url('assets/vendor/datatables/jquery-3.7.1.min.js') . '"></script>'
                . '<script src="' . base_url('assets/vendor/datatables/dataTables.min.js') . '"></script>'
                . '<script src="' . base_url('assets/js/admin-contactos.js') . '"></script>',
        ]);
    }
}
