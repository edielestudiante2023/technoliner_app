<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(BlogCategoriasSeeder::class);
        $this->call(ProductoCategoriasSeeder::class);
        $this->call(ProductosSeeder::class);
    }
}
