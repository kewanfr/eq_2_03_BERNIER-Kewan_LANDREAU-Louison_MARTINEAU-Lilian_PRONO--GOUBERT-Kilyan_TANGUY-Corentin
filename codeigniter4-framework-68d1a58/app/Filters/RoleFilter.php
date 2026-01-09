<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserRoleModel;
use App\Enums\RoleInterne;

// Vérifie si l'utilisateur a le bon rôle
class RoleFilter implements FilterInterface
{
    // Vérifie avant d'accéder à la route
    public function before(RequestInterface $request, $arguments = null)
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté');
        }

        // Si aucun rôle requis, laisse passer
        if (empty($arguments)) {
            return;
        }

        $userId = auth()->id();
        $userRoleModel = new UserRoleModel();
        $userRoles = $userRoleModel->getUserRoles($userId);

        // Vérifie si l'utilisateur a l'un des rôles requis
        foreach ($arguments as $requiredRole) {
            if (in_array($requiredRole, $userRoles)) {
                return;
            }
        }

        // Pas les permissions nécessaires
        return redirect()->to('/')->with('error', 'Accès non autorisé');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après
    }
}
