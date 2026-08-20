<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table          = 'productos';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $allowedFields = [
        'categoria_id',
        'creado_por',
        'nombre',
        'slug',
        'sku',
        'resumen',
        'descripcion_html',
        'activo',
        'destacado',
        'orden',
        'seo_titulo',
        'seo_descripcion',
    ];

    protected $validationRules = [
        'categoria_id' => 'required|is_natural_no_zero',
        'nombre'       => 'required|max_length[180]',
        'slug'         => 'required|max_length[200]|is_unique[productos.slug,id,{id}]',
    ];

    protected $beforeInsert = ['normalizarSku'];
    protected $beforeUpdate = ['normalizarSku'];

    protected function normalizarSku(array $data): array
    {
        if (isset($data['data']['sku']) && trim((string) $data['data']['sku']) === '') {
            $data['data']['sku'] = null;
        }

        return $data;
    }

    public function publicosQuery()
    {
        return $this
            ->select('productos.*, producto_categorias.nombre as categoria_nombre, producto_categorias.slug as categoria_slug,
                imagen_principal.ruta as imagen_principal_ruta, imagen_principal.alt_text as imagen_principal_alt')
            ->join('producto_categorias', 'producto_categorias.id = productos.categoria_id')
            ->join('producto_imagenes imagen_principal', 'imagen_principal.producto_id = productos.id AND imagen_principal.es_principal = 1', 'left')
            ->where('productos.activo', 1)
            ->where('producto_categorias.activo', 1);
    }

    public function encontrarPublicoPorSlug(string $slug): ?array
    {
        return $this->publicosQuery()->where('productos.slug', $slug)->first();
    }
}
