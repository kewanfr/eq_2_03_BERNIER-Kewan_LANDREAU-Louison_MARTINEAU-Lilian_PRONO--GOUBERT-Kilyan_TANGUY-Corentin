<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCartTables extends Migration
{
    public function up()
    {
        // Table pour les paniers
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('cart');

        // Table pour les items du panier
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'cart_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'product_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'quantity' => [
                'type' => 'INTEGER',
                'null' => false,
                'default' => 1,
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('cart_items');
    }

    public function down()
    {
        $this->forge->dropTable('cart_items');
        $this->forge->dropTable('cart');
    }
}
