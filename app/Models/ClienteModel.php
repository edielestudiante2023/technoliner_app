<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table         = 'clientes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'nombre',
        'logo',
        'logo_alt',
        'orden',
        'activo',
    ];

    protected $validationRules = [
        'nombre' => 'required|max_length[120]',
        'logo'   => 'required|max_length[255]',
    ];

    public function activos()
    {
        return $this->where('activo', 1)->orderBy('orden', 'ASC')->orderBy('nombre', 'ASC')->findAll();
    }
}
