<?php

namespace App\Models;

use CodeIgniter\Model;

// Gestion des rôles utilisateurs
class UserRoleModel extends Model
{
    protected $table = 'user_roles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'role'];
    protected $useTimestamps = false;

    // Donne un rôle à un user
    public function assignRole(int $userId, string $role): bool
    {
        // Vérifie si l'utilisateur a déjà ce rôle
        $existing = $this->where(['user_id' => $userId, 'role' => $role])->first();
        
        if ($existing) {
            return true;
        }

        return $this->insert([
            'user_id' => $userId,
            'role' => $role
        ]);
    }

    // Retire un rôle
    public function removeRole(int $userId, string $role): bool
    {
        return $this->where(['user_id' => $userId, 'role' => $role])->delete();
    }

    // Récupère les rôles d'un user
    public function getUserRoles(int $userId): array
    {
        $roles = $this->where('user_id', $userId)->findAll();
        return array_column($roles, 'role');
    }

    // Vérifie si un user a un rôle
    public function hasRole(int $userId, string $role): bool
    {
        return $this->where(['user_id' => $userId, 'role' => $role])->first() !== null;
    }

    // Rôle principal
    public function getPrimaryRole(int $userId): string
    {
        $role = $this->where('user_id', $userId)->first();
        return $role ? $role['role'] : 'client';
    }

    // Change tous les rôles
    public function setUserRoles(int $userId, array $roles): bool
    {
        // Supprime tous les rôles existants
        $this->where('user_id', $userId)->delete();

        // Ajoute les nouveaux rôles
        foreach ($roles as $role) {
            $this->insert([
                'user_id' => $userId,
                'role' => $role
            ]);
        }

        return true;
    }
}
