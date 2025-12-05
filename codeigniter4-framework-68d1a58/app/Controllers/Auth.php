<?php

namespace App\Controllers;

/**
 * Controlleur permettant l'affichage des vues d'authentification.
 */
class Auth extends BaseController
{
    /**
     * Affichage de la vue de connexion
     *
     * @return string
     */
    public function login() {
        return view('auth/login');
    }

    /**
     * Affichage de la vue de création de compte
     *
     * @return string
     */
    public function register() {
        return view('auth/register');
    }
}