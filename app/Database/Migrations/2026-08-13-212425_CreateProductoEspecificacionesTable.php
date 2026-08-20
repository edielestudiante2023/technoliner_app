<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductoEspecificacionesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'producto_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
            ],
            'valor' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'unidad' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'orden' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'created_at' => ['type' => 'DATETIME'],
            'updated_at' => ['type' => 'DATETIME'],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['producto_id', 'nombre']);
        $this->forge->addKey(['producto_id', 'orden']);
        $this->forge->addForeignKey('producto_id', 'productos', 'id', '', 'CASCADE');
        $this->forge->createTable('producto_especificaciones', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('producto_especificaciones', true);
    }
}
