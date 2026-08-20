<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoCategoriaModel extends Model
{
    protected $table         = 'producto_categorias';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'parent_id',
        'nombre',
        'slug',
        'descripcion',
        'activo',
        'orden',
        'seo_titulo',
        'seo_descripcion',
    ];

    protected $validationRules = [
        'nombre' => 'required|max_length[120]',
        'slug'   => 'required|max_length[150]|is_unique[producto_categorias.slug,id,{id}]',
    ];

    public function activas()
    {
        return $this->where('activo', 1)->orderBy('orden', 'ASC')->findAll();
    }

    public function principales()
    {
        return $this->where('parent_id', null)->orderBy('orden', 'ASC')->findAll();
    }
}
