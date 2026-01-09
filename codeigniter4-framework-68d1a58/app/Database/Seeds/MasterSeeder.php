<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserRoleModel;
use CodeIgniter\Shield\Models\UserModel;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // Produits
        $this->call(ProductSeeder::class);

        // Utilisateurs (avec rôles automatiques)
        $this->call(UserSeeder::class);
    }
}
