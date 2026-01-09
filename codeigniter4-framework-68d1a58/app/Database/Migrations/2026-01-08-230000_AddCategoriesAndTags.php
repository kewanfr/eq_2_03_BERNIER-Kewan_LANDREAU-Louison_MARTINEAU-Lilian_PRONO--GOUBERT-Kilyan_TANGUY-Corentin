<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoriesAndTags extends Migration
{
    public function up()
    {
        // Ajoute catégorie et tags aux produits
        $fields = [
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'tags' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];
        
        $this->forge->addColumn('products', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('products', ['category', 'tags']);
    }
}
