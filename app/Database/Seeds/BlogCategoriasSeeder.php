<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BlogCategoriasSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            ['nombre' => 'Guías de sellado', 'slug' => 'guias-sellado', 'descripcion' => 'Cómo elegir y usar liners sensitivos o de inducción.', 'orden' => 1],
            ['nombre' => 'Materiales y compatibilidad', 'slug' => 'materiales-compatibilidad', 'descripcion' => 'PE, PP, PS, PVC, PET, vidrio, EPE y poliestireno.', 'orden' => 2],
            ['nombre' => 'Seguridad e inocuidad', 'slug' => 'seguridad-inocuidad', 'descripcion' => 'Hermeticidad, inviolabilidad, contaminación y conservación.', 'orden' => 3],
            ['nombre' => 'Sostenibilidad', 'slug' => 'sostenibilidad', 'descripcion' => 'Materiales, reducción de desperdicio y alternativas Eco-sensitive.', 'orden' => 4],
            ['nombre' => 'Aplicaciones por industria', 'slug' => 'aplicaciones-industria', 'descripcion' => 'Alimentos, farmacéutica, cosmética, veterinaria y química.', 'orden' => 5],
        ];

        $now = date('Y-m-d H:i:s');

        foreach ($categorias as $categoria) {
            $existe = $this->db->table('blog_categorias')->where('slug', $categoria['slug'])->countAllResults();

            if ($existe > 0) {
                continue;
            }

            $this->db->table('blog_categorias')->insert(array_merge($categoria, [
                'activo'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
