<?php

namespace App\Controllers;

use App\Models\ClienteModel;

class ClienteController extends BaseController
{
    public function index()
    {
        return view('clientes/index', [
            'empresa'     => $this->datosEmpresa(),
            'clientes'    => (new ClienteModel())->activos(),
            'titulo'      => 'Clientes que confían en Technoliner | Technoliner',
            'descripcion' => 'Empresas de las industrias alimentaria, farmacéutica, industrial y cosmética que confían en las soluciones de sellado de Technoliner.',
            'canonical'   => site_url('clientes'),
            'robots'      => 'index, follow',
        ]);
    }
}
