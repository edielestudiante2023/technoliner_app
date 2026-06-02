<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'empresa' => [
                'nombre'    => 'Technoliner SAS',
                'eslogan'   => 'Protege lo esencial, preserva la calidad.',
                'descripcion' => 'Soluciones de empaque seguras y sostenibles para tu industria.',
                'nit'       => '901.404.716-8',
                'direccion' => 'Cl. 28 Sur # 51 A - 79, Barrio Alcalá, Bogotá D.C.',
                'telefono'  => '+57 312 430 4623',
                'whatsapp'  => '+57 312 430 4623',
                'whatsapp_link' => '573124304623',
                'correo'    => 'gerencia.technoliner@gmail.com',
            ],
        ];

        return view('home/index', $data);
    }
}
