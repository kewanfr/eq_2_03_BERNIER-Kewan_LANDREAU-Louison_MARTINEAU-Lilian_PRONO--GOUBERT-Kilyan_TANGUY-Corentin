<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Users;

class ProfileController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new Users();
    }

    /**
     * Affiche le profil de l'utilisateur
     */
    public function index()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->id();
        $user = $this->userModel->find($userId);
        
        // Récupère l'email depuis auth_identities
        $db = \Config\Database::connect();
        $identity = $db->table('auth_identities')
            ->where('user_id', $userId)
            ->where('type', 'email_password')
            ->get()
            ->getRow();
        
        $data = [
            'user' => $user,
            'email' => $identity->secret ?? ''
        ];

        return view('profile/index', $data);
    }

    /**
     * Met à jour le profil
     */
    public function update()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->id();
        $db = \Config\Database::connect();

        // Récupère les données du formulaire
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $address = $this->request->getPost('address');
        $customerType = $this->request->getPost('customer_type');
        $companyName = $this->request->getPost('company_name');
        $siret = $this->request->getPost('siret');
        $tvaNumber = $this->request->getPost('tva_number');

        // Met à jour les informations utilisateur
        $updateData = [
            'username' => $username,
            'phone' => $phone,
            'address' => $address,
            'customer_type' => $customerType
        ];

        if ($customerType === 'professionnel') {
            $updateData['company_name'] = $companyName;
            $updateData['siret'] = $siret;
            $updateData['tva_number'] = $tvaNumber;
        } else {
            // Efface les données pro si on passe en particulier
            $updateData['company_name'] = null;
            $updateData['siret'] = null;
            $updateData['tva_number'] = null;
        }

        $this->userModel->update($userId, $updateData);

        // Met à jour l'email si changé
        $currentIdentity = $db->table('auth_identities')
            ->where('user_id', $userId)
            ->where('type', 'email_password')
            ->get()
            ->getRow();

        if ($currentIdentity && $currentIdentity->secret !== $email) {
            // Vérifie si le nouvel email n'est pas déjà utilisé
            $existingEmail = $db->table('auth_identities')
                ->where('secret', $email)
                ->where('type', 'email_password')
                ->where('user_id !=', $userId)
                ->get()
                ->getRow();

            if ($existingEmail) {
                return redirect()->back()->with('error', 'Cet email est déjà utilisé par un autre compte');
            }

            $db->table('auth_identities')
                ->where('user_id', $userId)
                ->where('type', 'email_password')
                ->update(['secret' => $email]);
        }

        return redirect()->to('/profile')->with('success', 'Profil mis à jour avec succès');
    }

    /**
     * Change le mot de passe
     */
    public function changePassword()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->id();
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Vérifie que les nouveaux mots de passe correspondent
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Les nouveaux mots de passe ne correspondent pas');
        }

        // Vérifie le mot de passe actuel
        $user = auth()->user();
        if (!password_verify($currentPassword, $user->password_hash)) {
            return redirect()->back()->with('error', 'Mot de passe actuel incorrect');
        }

        // Change le mot de passe
        $user->password = $newPassword;
        $this->userModel->save($user);

        return redirect()->to('/profile')->with('success', 'Mot de passe modifié avec succès');
    }
}
