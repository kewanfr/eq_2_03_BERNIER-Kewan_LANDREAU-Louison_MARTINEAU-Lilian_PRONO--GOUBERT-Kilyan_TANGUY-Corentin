<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Users;
use CodeIgniter\Shield\Controllers\RegisterController;
use CodeIgniter\Shield\Models\UserModel;

/**
 * Controlleur permettant à l'utilisateur de se créer un nouveau compte
 */
class Register extends RegisterController
{
    /**
     * Fonction d'enregistrement principale.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function register() {
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $passwordconf = $this->request->getPost('passwordconf');

        if ($password != $passwordconf) {
            redirect("/register?error=1");
        }

        $users = new UserModel();

        $user = $users->createNewUser([
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ]);

        $users->save($user);

        if ($this->request->getPost('rememberme') != null) {
            //TODO: add tokens and cookies and allat
        }

        return redirect("/");
    }
}