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
            'empresa'   => $this->datosEmpresa(),
            'productos' => $productos,
        ]);
    }
}
