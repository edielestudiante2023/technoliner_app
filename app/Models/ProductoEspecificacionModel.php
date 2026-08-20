<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoEspecificacionModel extends Model
{
    protected $table         = 'producto_especificaciones';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'producto_id',
        'nombre',
        'valor',
        'unidad',
        'orden',
    ];

    public function porProducto(int $productoId): array
    {
        return $this->where('producto_id', $productoId)->orderBy('orden', 'ASC')->findAll();
    }
}
