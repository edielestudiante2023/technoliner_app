<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductoCategoriasTable extends Migration
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
            'parent_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'activo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'orden' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'seo_titulo' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
                'null'       => true,
            ],
            'seo_descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => 170,
                'null'       => true,
            ],
            'created_at' => ['type' => 'DATETIME'],
            'updated_at' => ['type' => 'DATETIME'],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('parent_id');
        $this->forge->addKey(['activo', 'orden']);
        $this->forge->addForeignKey('parent_id', 'producto_categorias', 'id', '', 'SET NULL');
        $this->forge->createTable('producto_categorias', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('producto_categorias', true);
    }
}
