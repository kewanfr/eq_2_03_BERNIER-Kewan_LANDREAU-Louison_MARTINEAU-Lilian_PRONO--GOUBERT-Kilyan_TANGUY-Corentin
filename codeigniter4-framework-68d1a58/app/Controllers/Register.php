<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Controllers\RegisterController;

class Register extends RegisterController {
    
    // Ajoute automatiquement le rôle client après inscription
    public function registerAction(): RedirectResponse
    {
        $response = parent::registerAction();
        
        // Si l'inscription réussit, ajoute le rôle client
        if (auth()->loggedIn()) {
            $userId = auth()->id();
            $db = \Config\Database::connect();
            
            // Vérifie si le user a déjà un rôle
            $existing = $db->table('user_roles')->where('user_id', $userId)->get()->getRow();
            
            if (!$existing) {
                $db->table('user_roles')->insert([
                    'user_id' => $userId,
                    'role' => 'client'
                ]);
            }
        }
        
        return $response;
    }
}