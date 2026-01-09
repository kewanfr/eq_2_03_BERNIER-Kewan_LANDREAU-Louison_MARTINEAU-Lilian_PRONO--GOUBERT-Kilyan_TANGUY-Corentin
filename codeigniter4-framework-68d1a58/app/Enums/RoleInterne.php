<?php

namespace App\Enums;

// Les différents rôles du diagramme UML
class RoleInterne
{
    const ADMIN = 'admin';
    const COMMERCIAL = 'commercial';
    const PREPARATION = 'preparation';
    const PRODUCTION = 'production';
    const SAISONNIER = 'saisonnier';
    const CLIENT = 'client';

    // Tous les rôles dispo
    public static function getAll(): array
    {
        return [
            self::ADMIN,
            self::COMMERCIAL,
            self::PREPARATION,
            self::PRODUCTION,
            self::SAISONNIER,
            self::CLIENT
        ];
    }

    // Rôles employés
    public static function getInternalRoles(): array
    {
        return [
            self::ADMIN,
            self::COMMERCIAL,
            self::PREPARATION,
            self::PRODUCTION,
            self::SAISONNIER
        ];
    }

    // Vérifie si un rôle existe
    public static function isValid(string $role): bool
    {
        return in_array($role, self::getAll());
    }

    // Rôle interne ?
    public static function isInternal(string $role): bool
    {
        return in_array($role, self::getInternalRoles());
    }

    // Permissions par rôle
    public static function getPermissions(string $role): array
    {
        $permissions = [
            self::ADMIN => [
                'access_admin',
                'manage_users',
                'manage_products',
                'manage_orders',
                'view_statistics',
                'manage_settings',
                'manage_stock'
            ],
            self::COMMERCIAL => [
                'access_admin',
                'view_orders',
                'manage_orders',
                'view_statistics',
                'manage_products'
            ],
            self::PREPARATION => [
                'access_admin',
                'view_orders',
                'manage_orders',
                'update_order_status'
            ],
            self::PRODUCTION => [
                'access_admin',
                'view_orders',
                'manage_orders',
                'manage_stock',
                'view_products'
            ],
            self::SAISONNIER => [
                'access_admin',
                'view_orders'
            ],
            self::CLIENT => [
                'view_products',
                'manage_cart',
                'place_order'
            ]
        ];

        return $permissions[$role] ?? [];
    }

    // Vérifie permission
    public static function hasPermission(string $role, string $permission): bool
    {
        $permissions = self::getPermissions($role);
        return in_array($permission, $permissions);
    }
}
