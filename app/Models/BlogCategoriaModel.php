<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogCategoriaModel extends Model
{
    protected $table         = 'blog_categorias';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['nombre', 'slug', 'descripcion', 'activo', 'orden'];

    protected $validationRules = [
        'nombre' => 'required|max_length[100]',
        'slug'   => 'required|max_length[120]|is_unique[blog_categorias.slug,id,{id}]',
    ];

    public function activas()
    {
        return $this->where('activo', 1)->orderBy('orden', 'ASC')->findAll();
    }
}
