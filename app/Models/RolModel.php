<?php

namespace App\Models;

use CodeIgniter\Model;

class RolModel extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['codigo', 'nombre', 'descripcion', 'activo'];

    protected $validationRules = [
        'codigo' => 'required|max_length[50]|is_unique[roles.codigo,id,{id}]',
        'nombre' => 'required|max_length[100]',
    ];
}
