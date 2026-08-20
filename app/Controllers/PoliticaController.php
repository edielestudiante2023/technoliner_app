<?php

namespace App\Controllers;

class PoliticaController extends BaseController
{
    public function index()
    {
        return view('politica/tratamiento_datos', [
            'empresa'     => $this->datosEmpresa(),
            'titulo'      => 'Política de Tratamiento de Datos Personales — Technoliner SAS',
            'descripcion' => 'Política de tratamiento de datos personales de Technoliner SAS, versión 1.0.',
        ]);
    }
}
