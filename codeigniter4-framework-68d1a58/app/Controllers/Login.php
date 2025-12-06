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
     *
     * @return RedirectResponse
     */
    public function loginAction(): RedirectResponse {
        return parent::loginAction();
    }

    public function logoutAction(): RedirectResponse
    {
        return parent::logoutAction();
    }
}