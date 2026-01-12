<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTagsTables extends Migration
{
    public function up()
    {
        // tags
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('name', false, true); // unique
        $this->forge->createTable('tags');

        // product_tags
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'product_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'tag_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['product_id', 'tag_id'], false, true); // unique pair
        // FKs (si supportés)
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tag_id', 'tags', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_tags');
    }

    public function down()
    {
        $this->forge->dropTable('product_tags');
        $this->forge->dropTable('tags');
    }
}
