<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Controllers\RegisterController;

class Register extends RegisterController {
    
    // Ajoute automatiquement le rôle client après inscription
    public function registerAction(): RedirectResponse
    {
        $response = parent::registerAction();
        
        // Si l'inscription réussit, ajoute le rôle client et les infos supplémentaires
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
            
            // Récupère les données du formulaire
            $customerType = $this->request->getPost('customer_type') ?? 'particulier';
            $companyName = $this->request->getPost('company_name');
            $siret = $this->request->getPost('siret');
            $tvaNumber = $this->request->getPost('tva_number');
            $phone = $this->request->getPost('phone');
            $address = $this->request->getPost('address');
            
            // Met à jour les informations du client
            $updateData = ['customer_type' => $customerType];
            
            // Ajoute les infos de contact si présentes
            if (!empty($phone)) {
                $updateData['phone'] = $phone;
            }
            if (!empty($address)) {
                $updateData['address'] = $address;
            }
            
            if ($customerType === 'professionnel') {
                $updateData['company_name'] = $companyName;
                $updateData['siret'] = $siret;
                $updateData['tva_number'] = $tvaNumber;
            }
            
            $db->table('users')->where('id', $userId)->update($updateData);
        }
        
        return $response;
    }
}