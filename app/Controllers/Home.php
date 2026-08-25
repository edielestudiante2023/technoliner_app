<?php

namespace App\Controllers;

use App\Models\ProductoModel;

class Home extends BaseController
{
    public function index(): string
    {
        $productos = (new ProductoModel())
            ->publicosQuery()
            ->orderBy('productos.destacado', 'DESC')
            ->orderBy('productos.orden', 'ASC')
            ->findAll(6);

        return view('home/index', [
            'empresa'     => $this->datosEmpresa(),
            'productos'   => $productos,
            'titulo'      => 'Liners para sellado de envases en Colombia | Technoliner',
            'descripcion' => 'Liners sensitivos, espumados y de inducción para envases PE, PP, PET, PVC y vidrio, con asesoría técnica y entregas confiables.',
            'canonical'   => site_url('/'),
            'robots'      => 'index, follow',
        ]);
    }
}
