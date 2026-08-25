<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticuloBlogModel extends Model
{
    protected $table          = 'articulos_blog';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $allowedFields = [
        'categoria_id',
        'autor_id',
        'titulo',
        'slug',
        'extracto',
        'contenido_html',
        'imagen_portada',
        'imagen_alt',
        'publicado',
        'destacado',
        'seo_titulo',
        'seo_descripcion',
        'publicado_at',
        'vistas',
    ];

    protected $validationRules = [
        'titulo'         => 'required|max_length[255]',
        'slug'           => 'required|max_length[255]|is_unique[articulos_blog.slug,id,{id}]',
        'contenido_html' => 'required',
    ];

    public function publicados()
    {
        return $this->where('publicado', 1)
            ->where('publicado_at <=', date('Y-m-d H:i:s'))
            ->orderBy('publicado_at', 'DESC');
    }

    public function encontrarPublicadoPorSlug(string $slug): ?array
    {
        return $this->publicados()->where('slug', $slug)->first();
    }

    public function registrarVista(int $id): void
    {
        $this->builder()->set('vistas', 'vistas + 1', false)->where('id', $id)->update();
    }
}
