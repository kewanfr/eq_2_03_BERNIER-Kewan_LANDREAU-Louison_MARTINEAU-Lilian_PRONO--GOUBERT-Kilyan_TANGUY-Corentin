<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel;

/**
 * Vue permettant de faciliter l'accès au données liées aux utilisateurs
 */
class Users extends UserModel
{
    /**
     * Fonction permettant de donner un utilisateur en fonction d'un email donné
     *
     * @param $email
     * @return array|object|null
     */
    public function getUserByEmail($email) {
        return $this->select('users.*')
            ->where('users.email', $email)
            ->first();
    }

    /**
     * Fonction permettant de donner un utilisateur à partir d'un nom d'utilisateur donné
     *
     * @param $username
     * @return array|object|null
     */
    public function getUserByUsername($username) {
        return $this->select('users.*')
            ->where('users.username', $username)
            ->first();
    }

    /**
     * Fonction permettant de donner un utilisateur à partir d'un identifiant utilisateur donné.
     *
     * @param $id
     * @return array|object|null
     */
    public function getUserById($id) {
        return $this->select('users.*')
            ->where('users.id', $id)
            ->first();
    }
}