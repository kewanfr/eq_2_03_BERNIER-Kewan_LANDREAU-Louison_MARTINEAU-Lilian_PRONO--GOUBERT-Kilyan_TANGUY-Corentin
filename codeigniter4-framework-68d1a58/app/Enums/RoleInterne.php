<?php

namespace App\Enums;

/**
 * Énumération des rôles internes de l'application
 * 
 * Gère les différents rôles avec leurs permissions associées.
 */
enum RoleInterne: string
{
    case ADMIN = 'admin';
    case COMMERCIAL = 'commercial';
    case PREPARATION = 'preparation';
    case PRODUCTION = 'production';
    case SAISONNIER = 'saisonnier';
    case CLIENT = 'client';

    /**
     * Label humain pour l'affichage
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrateur',
            self::COMMERCIAL => 'Commercial',
            self::PREPARATION => 'Préparation',
            self::PRODUCTION => 'Production',
            self::SAISONNIER => 'Saisonnier',
            self::CLIENT => 'Client',
        };
    }

    /**
     * Classe CSS pour le badge
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::ADMIN => 'danger',
            self::COMMERCIAL => 'primary',
            self::PREPARATION => 'info',
            self::PRODUCTION => 'warning',
            self::SAISONNIER => 'secondary',
            self::CLIENT => 'success',
        };
    }

    /**
     * Icône FontAwesome
     */
    public function icon(): string
    {
        return match ($this) {
            self::ADMIN => 'fa-user-shield',
            self::COMMERCIAL => 'fa-briefcase',
            self::PREPARATION => 'fa-box-open',
            self::PRODUCTION => 'fa-industry',
            self::SAISONNIER => 'fa-calendar-alt',
            self::CLIENT => 'fa-user',
        };
    }

    /**
     * Permissions associées à ce rôle
     * 
     * @return string[]
     */
    public function permissions(): array
    {
        return match ($this) {
            self::ADMIN => [
                'access_admin',
                'manage_users',
                'manage_products',
                'view_orders',
                'manage_orders',
                'update_order_status',
                'view_statistics',
                'manage_settings',
                'manage_stock'
            ],
            self::COMMERCIAL => [
                'access_admin',
                'view_orders',
                'manage_orders',
                'update_order_status',
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
                'update_order_status',
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
            ],
        };
    }

    /**
     * Vérifie si ce rôle a une permission donnée
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions(), true);
    }

    /**
     * Indique si c'est un rôle interne (employé)
     */
    public function isInternal(): bool
    {
        return match ($this) {
            self::ADMIN, self::COMMERCIAL, self::PREPARATION, self::PRODUCTION, self::SAISONNIER => true,
            self::CLIENT => false,
        };
    }

    /**
     * Indique si ce rôle peut accéder à l'administration
     */
    public function canAccessAdmin(): bool
    {
        return $this->hasPermission('access_admin');
    }

    /**
     * Retourne tous les rôles
     * 
     * @return RoleInterne[]
     */
    public static function all(): array
    {
        return self::cases();
    }

    /**
     * Retourne les rôles internes (employés)
     * 
     * @return RoleInterne[]
     */
    public static function internalRoles(): array
    {
        return array_filter(self::cases(), fn($r) => $r->isInternal());
    }

    /**
     * Crée depuis une chaîne avec valeur par défaut
     */
    public static function fromString(string $value): RoleInterne
    {
        return self::tryFrom($value) ?? self::CLIENT;
    }

    /**
     * Vérifie si une chaîne correspond à un rôle valide
     */
    public static function isValid(string $value): bool
    {
        return self::tryFrom($value) !== null;
    }

    // ========== MÉTHODES STATIQUES DE COMPATIBILITÉ ==========
    // Ces méthodes maintiennent la compatibilité avec l'ancien code

    /**
     * @deprecated Utiliser RoleInterne::cases() ou all()
     */
    public static function getAll(): array
    {
        return array_map(fn($r) => $r->value, self::cases());
    }

    /**
     * @deprecated Utiliser RoleInterne::internalRoles()
     */
    public static function getInternalRoles(): array
    {
        return array_map(fn($r) => $r->value, self::internalRoles());
    }

    /**
     * @deprecated Utiliser $role->permissions() directement
     */
    public static function getPermissions(string $role): array
    {
        $roleEnum = self::tryFrom($role);
        return $roleEnum?->permissions() ?? [];
    }

    /**
     * @deprecated Utiliser $role->hasPermission() directement
     */
    public static function hasPermissionStatic(string $role, string $permission): bool
    {
        $roleEnum = self::tryFrom($role);
        return $roleEnum?->hasPermission($permission) ?? false;
    }
}
