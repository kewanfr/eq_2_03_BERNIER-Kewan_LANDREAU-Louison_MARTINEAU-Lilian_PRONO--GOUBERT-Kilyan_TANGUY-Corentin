<?php

namespace App\Controllers;

use CodeIgniter\Shield\Controllers\LoginController;
use CodeIgniter\HTTP\RedirectResponse;

class Login extends LoginController
{
    //retourne la vue pour permettre à l'utilisateur de se connecter.
    public function loginView()
    {
        return parent::loginView();
    }

    /**
     * Fonction de connexion principale
     * Accepte username ou email
     *
     * @return RedirectResponse
     */
    public function loginAction(): RedirectResponse {
        // Règles de validation personnalisées (pas de validation email stricte)
        $rules = [
            'email' => [
                'label' => 'Auth.email',
                'rules' => 'required',
            ],
            'password' => [
                'label' => 'Auth.password',
                'rules' => 'required',
            ],
        ];
            
        if (!$this->validateData($this->request->getPost(), $rules, [], config('Auth')->DBGroup)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $loginValue = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        // Détermine si c'est un email ou un username
        $credentials = [];
        
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            // C'est un email
            $credentials = [
                'email' => $loginValue,
                'password' => $password
            ];
        } else {
            // C'est un username - trouve l'email associé
            $db = \Config\Database::connect();
            $user = $db->table('users')
                ->where('username', $loginValue)
                ->get()
                ->getRowArray();
            
            if (!$user) {
                return redirect()->route('login')->withInput()->with('error', 'Identifiants invalides');
            }
            
            // Récupère l'email depuis auth_identities
            $identity = $db->table('auth_identities')
                ->where('user_id', $user['id'])
                ->where('type', 'email_password')
                ->get()
                ->getRowArray();
            
            if (!$identity) {
                return redirect()->route('login')->withInput()->with('error', 'Identifiants invalides');
            }
            
            $credentials = [
                'email' => $identity['secret'],
                'password' => $password
            ];
        }

        $authenticator = auth('session')->getAuthenticator();
        $result = $authenticator->remember($this->request->getPost('remember') ?? false)->attempt($credentials);

        if (!$result->isOK()) {
            return redirect()->route('login')->withInput()->with('error', 'Identifiants invalides');
        }

        return redirect()->to(config('Auth')->loginRedirect());
    }

    public function logoutAction(): RedirectResponse
    {
        return parent::logoutAction();
    }
}