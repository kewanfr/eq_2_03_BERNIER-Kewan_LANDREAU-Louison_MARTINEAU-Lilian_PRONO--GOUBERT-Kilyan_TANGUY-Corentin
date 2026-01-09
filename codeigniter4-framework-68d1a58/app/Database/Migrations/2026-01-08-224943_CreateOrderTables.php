<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderTables extends Migration
{
    public function up()
    {
        // Table des commandes
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'order_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'default' => 'PAYEE',
            ],
            'total_ht' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'total_ttc' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('orders');

        // Table des lignes de commande
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'order_id' => [
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
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('order_items');
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
        $this->forge->dropTable('orders');
    }
}
