<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserRoles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'default' => 'client',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('user_roles');
    }

    public function down()
    {
        $this->forge->dropTable('user_roles');
    }
}
