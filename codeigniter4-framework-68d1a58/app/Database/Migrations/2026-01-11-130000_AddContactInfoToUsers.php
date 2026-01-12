<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddContactInfoToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];
        
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['phone', 'address']);
    }
}
