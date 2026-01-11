<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveToProducts extends Migration
{
    public function up()
    {
        $fields = [
            'is_active' => [
                'type' => 'INTEGER',
                'null' => false,
                'default' => 1,
            ],
        ];

        $this->forge->addColumn('products', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'is_active');
    }
}
