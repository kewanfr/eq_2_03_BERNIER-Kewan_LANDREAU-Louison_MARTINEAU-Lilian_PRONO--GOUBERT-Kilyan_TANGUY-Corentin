<?php

namespace App\Controllers;

/**
 * Controlleur pour afficher la page d'accueil
 */
class Home extends BaseController
{
    public function index(): string
    {
        return view('layout/main');
    }
}
