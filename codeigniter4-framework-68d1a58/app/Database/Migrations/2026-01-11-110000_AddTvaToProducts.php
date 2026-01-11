<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTvaToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'tva_rate' => [
                'type' => 'REAL',
                'null' => false,
                'default' => 20.0,
                'comment' => 'Taux de TVA en pourcentage',
                'after' => 'price'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'tva_rate');
    }
}
