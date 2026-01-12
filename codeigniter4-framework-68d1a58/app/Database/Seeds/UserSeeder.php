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
            [
                'username' => 'client_pro1',
                'email'    => 'clientpro1@example.com',
                'password' => 'motdepasse',
                'role'     => 'client',
                'customer_type' => 'professionnel',
                'company_name' => 'Entreprise Alpha',
                'siret' => '12345678901234',
                'tva_number' => 'FR12345678901',
                'phone' => '01 23 45 67 89',
                'address' => '123 Rue de la République, 75001 Paris',
            ],
            [
                'username' => 'client_pro2',
                'email'    => 'clientpro2@example.com',
                'password' => 'motdepasse',
                'role'     => 'client',
                'customer_type' => 'professionnel',
                'company_name' => 'Société Beta',
                'siret' => '98765432109876',
                'tva_number' => 'FR98765432109',
                'phone' => '01 98 76 54 32',
                'address' => '456 Avenue des Champs, 75008 Paris',
            ],
            [
                'username' => 'client_part1',
                'email'    => 'clientpart1@example.com',
                'password' => 'motdepasse',
                'role'     => 'client',
                'customer_type' => 'particulier',
                'phone' => '06 12 34 56 78',
                'address' => '789 Boulevard Saint-Michel, 75005 Paris',
            ],
            [
                'username' => 'client_part2',
                'email'    => 'clientpart2@example.com',
                'password' => 'motdepasse',
                'role'     => 'client',
                'customer_type' => 'particulier',
                'phone' => '06 87 65 43 21',
                'address' => '321 Place de la Concorde, 75008 Paris',
            ],
            [
                'username' => 'client_part3',
                'email'    => 'clientpart3@example.com',
                'password' => 'motdepasse',
                'role'     => 'client',
                'customer_type' => 'particulier',
                'phone' => '07 11 22 33 44',
                'address' => '654 Rue du Faubourg, 75009 Paris',
            ],
            [
                'username' => 'client_part4',
                'email'    => 'clientpart4@example.com',
                'password' => 'motdepasse',
                'role'     => 'client',
                'customer_type' => 'particulier',
                // Pas de phone ni address pour ce client
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
            $userData = [
                'username'       => $data['username'],
                'status'         => 'active',
                'active'         => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];

            // Ajouter les champs spécifiques aux clients si présents
            if (isset($data['customer_type'])) {
                $userData['customer_type'] = $data['customer_type'];
            }
            if (isset($data['company_name'])) {
                $userData['company_name'] = $data['company_name'];
            }
            if (isset($data['siret'])) {
                $userData['siret'] = $data['siret'];
            }
            if (isset($data['tva_number'])) {
                $userData['tva_number'] = $data['tva_number'];
            }
            if (isset($data['phone'])) {
                $userData['phone'] = $data['phone'];
            }
            if (isset($data['address'])) {
                $userData['address'] = $data['address'];
            }

            $this->db->table('users')->insert($userData);
            $userId = (int) $this->db->insertID();

            // Identité email+password (Shield: email dans secret, hash dans secret2)
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $this->db->table('auth_identities')->insert([
                'user_id'     => $userId,
                'type'        => 'email_password',
                'secret'      => $data['email'],
                'secret2'     => $hash,
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
