<?php

namespace App\Controllers;

use App\Models\Users;
use CodeIgniter\Controller;

/**
 * Controlleur pour permettre à l'utilisateur de se connecter à son compte déjà créé
 */
class Login extends Controller {
    /**
     * Fonction de connexion principale
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function login() {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $remember = $this->request->getPost('remember') === '1';

        $auth = service('auth');
        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        $result = $auth
            ->remember($remember)
            ->attempt($credentials);

        if (! $result->isOk()) {
            return redirect()->to('/login?error=2');
        }

        return redirect("/");
    }
}