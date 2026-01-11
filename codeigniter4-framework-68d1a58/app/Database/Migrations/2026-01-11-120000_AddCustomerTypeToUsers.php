<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomerTypeToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'customer_type' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'default' => 'particulier',
                'comment' => 'Type de client: particulier ou professionnel',
                'after' => 'active'
            ],
            'company_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Nom de l\'entreprise pour les professionnels',
                'after' => 'customer_type'
            ],
            'siret' => [
                'type' => 'VARCHAR',
                'constraint' => 14,
                'null' => true,
                'comment' => 'Numéro SIRET pour les professionnels',
                'after' => 'company_name'
            ],
            'tva_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'comment' => 'Numéro de TVA intracommunautaire',
                'after' => 'siret'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['customer_type', 'company_name', 'siret', 'tva_number']);
    }
}
