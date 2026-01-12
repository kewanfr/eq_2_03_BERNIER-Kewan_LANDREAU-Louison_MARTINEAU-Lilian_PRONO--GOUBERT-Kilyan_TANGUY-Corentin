<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeliveryToOrders extends Migration
{
    public function up()
    {
        $fields = [
            'delivery_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'default' => 'pickup',
                'comment' => 'pickup, local_delivery, carrier_delivery',
            ],
            'delivery_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00,
            ],
        ];
        
        $this->forge->addColumn('orders', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', ['delivery_method', 'delivery_cost']);
    }
}
