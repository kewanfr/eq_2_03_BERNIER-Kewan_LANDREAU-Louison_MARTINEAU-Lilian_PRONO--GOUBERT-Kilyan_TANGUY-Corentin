<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * Controlleur permettant la gestion de la permission de l'utilisation des cookies par l'utilisateur.
 */
class Cookies extends BaseController
{
    /**
     * Fonction pour enregistrer l'acceptation des cookies par l'utilisateur
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function accept() {
        helper('cookie');

        set_cookie(
            "acceptcookies",
            "1",
            60 * 60 * 24 * 365, //Le cookie dure 1 an
        );

        return $this->response->setJson(["status" => "ok"]);
    }

    /**
     * Fonction pour enregistrer le refus des cookies par l'utilisateur.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function decline() {
        helper('cookie');

        set_cookie(
            "acceptcookies",
            "0",
            60 * 60 * 24 * 365,
        );

        return $this->response->setJson(["status" => "ok"]);
    }
}