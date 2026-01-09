<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        // Ajoute le rôle admin au premier user
        // Change l'ID si besoin
        $data = [
            'user_id' => 1,
            'role' => 'admin'
        ];
        
        $this->db->table('user_roles')->insert($data);
        
        echo "Rôle admin ajouté au user 1\n";
    }
}
