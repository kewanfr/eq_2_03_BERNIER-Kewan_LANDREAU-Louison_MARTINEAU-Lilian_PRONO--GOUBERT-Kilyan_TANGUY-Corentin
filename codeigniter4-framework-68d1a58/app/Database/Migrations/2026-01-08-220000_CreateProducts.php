<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProducts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'desc' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'img_src' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'price' => [
                'type' => 'REAL',
                'null' => false,
            ],
            'quantity' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
