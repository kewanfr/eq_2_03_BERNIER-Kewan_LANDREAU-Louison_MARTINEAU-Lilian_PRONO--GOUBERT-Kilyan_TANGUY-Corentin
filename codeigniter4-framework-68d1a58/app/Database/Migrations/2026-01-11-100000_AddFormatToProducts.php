<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFormatToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'format' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'quantity'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'format');
    }
}
