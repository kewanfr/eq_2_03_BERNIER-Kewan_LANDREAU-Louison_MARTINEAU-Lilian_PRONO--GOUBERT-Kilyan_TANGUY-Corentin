<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $defaults = [
            [
                'username' => 'admin',
                'email'    => 'admin@example.com',
                'password' => 'motdepasse',
                'role'     => 'admin',
            ],
            [
                'username' => 'commercial',
                'email'    => 'commercial@example.com',
                'password' => 'motdepasse',
                'role'     => 'commercial',
            ],
            [
                'username' => 'preparation',
                'email'    => 'preparation@example.com',
                'password' => 'motdepasse',
                'role'     => 'preparation',
            ],
            [
                'username' => 'production',
                'email'    => 'production@example.com',
                'password' => 'motdepasse',
                'role'     => 'production',
            ],
            [
                'username' => 'saisonnier',
                'email'    => 'saisonnier@example.com',
                'password' => 'motdepasse',
                'role'     => 'saisonnier',
            ],
            [
                'username' => 'client',
                'email'    => 'client@example.com',
                'password' => 'motdepasse',
                'role'     => 'client',
            ],
        ];

        $now = date('Y-m-d H:i:s');

        foreach ($defaults as $data) {
            // Vérifie si l'utilisateur existe déjà dans la table users
            $existing = $this->db->table('users')->where('username', $data['username'])->get()->getRowArray();
            if ($existing) {
                echo "Utilisateur {$data['username']} déjà présent, skip." . PHP_EOL;
                continue;
            }

            // Insère l'utilisateur
            $this->db->table('users')->insert([
                'username'       => $data['username'],
                'status'         => 'active',
                'active'         => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
            $userId = (int) $this->db->insertID();

            // Identité email+password combinée (type = 'email_password')
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $this->db->table('auth_identities')->insert([
                'user_id'     => $userId,
                'type'        => 'email_password',
                'name'        => $data['email'],
                'secret'      => $hash,
                'force_reset' => 0,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            // Attribution du rôle dans user_roles
            if (isset($data['role'])) {
                $this->db->table('user_roles')->insert([
                    'user_id' => $userId,
                    'role'    => $data['role'],
                ]);
                echo "Utilisateur {$data['username']} créé avec le rôle '{$data['role']}'." . PHP_EOL;
            } else {
                echo "Utilisateur {$data['username']} créé." . PHP_EOL;
            }
        }
    }
}
