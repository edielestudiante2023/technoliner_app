<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVistasToArticulosBlog extends Migration
{
    public function up()
    {
        $this->forge->addColumn('articulos_blog', [
            'vistas' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'publicado_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('articulos_blog', 'vistas');
    }
}
