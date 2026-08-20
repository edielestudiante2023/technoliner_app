<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $exists = $this->db->table('roles')->where('codigo', 'administrador')->countAllResults();

        if ($exists > 0) {
            return;
        }

        $now = date('Y-m-d H:i:s');

        $this->db->table('roles')->insert([
            'codigo'      => 'administrador',
            'nombre'      => 'Administrador',
            'descripcion' => 'Acceso completo al panel administrativo.',
            'activo'      => 1,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);
    }
}
