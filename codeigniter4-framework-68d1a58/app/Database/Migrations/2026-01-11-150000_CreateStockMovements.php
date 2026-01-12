<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockMovements extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'quantity_change' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'reason' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'reference_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'user_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('stock_movements');
    }

    public function down()
    {
        $this->forge->dropTable('stock_movements');
    }
}
