<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoImagenModel extends Model
{
    protected $table         = 'producto_imagenes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'producto_id',
        'ruta',
        'nombre_original',
        'mime_type',
        'tamano_bytes',
        'alt_text',
        'es_principal',
        'orden',
        'created_at',
    ];

    public function marcarComoPrincipal(int $imagenId, int $productoId): void
    {
        $this->where('producto_id', $productoId)->set(['es_principal' => 0])->update();
        $this->update($imagenId, ['es_principal' => 1]);
    }

    public function porProducto(int $productoId): array
    {
        return $this->where('producto_id', $productoId)->orderBy('orden', 'ASC')->findAll();
    }
}
