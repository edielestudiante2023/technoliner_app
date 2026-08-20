<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductoCategoriasSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $principalId = $this->obtenerOCrear([
            'parent_id'   => null,
            'nombre'      => 'Liners y sellos',
            'slug'        => 'liners-y-sellos',
            'descripcion' => 'Agrupa todo el catálogo inicial de liners y sellos para envases.',
            'orden'       => 1,
        ], $now);

        $subcategorias = [
            ['nombre' => 'Sellos sensibles a presión', 'slug' => 'sellos-sensibles-presion', 'orden' => 1],
            ['nombre' => 'Liners espumados', 'slug' => 'liners-espumados', 'orden' => 2],
            ['nombre' => 'Sellos por inducción', 'slug' => 'sellos-induccion', 'orden' => 3],
        ];

        foreach ($subcategorias as $subcategoria) {
            $this->obtenerOCrear(array_merge($subcategoria, ['parent_id' => $principalId]), $now);
        }
    }

    private function obtenerOCrear(array $datos, string $now): int
    {
        $existente = $this->db->table('producto_categorias')->where('slug', $datos['slug'])->get()->getRowArray();

        if ($existente) {
            return (int) $existente['id'];
        }

        $this->db->table('producto_categorias')->insert(array_merge($datos, [
            'activo'     => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]));

        return (int) $this->db->insertID();
    }
}
