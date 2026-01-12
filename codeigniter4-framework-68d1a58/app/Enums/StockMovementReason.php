<?php

namespace App\Enums;

/**
 * Énumération des raisons de mouvement de stock
 * 
 * Encapsule la logique métier liée au signe de la quantité
 * et aux notes par défaut.
 */
enum StockMovementReason: string
{
    case ORDER = 'ORDER';
    case CANCELLATION = 'CANCELLATION';
    case MANUAL = 'MANUAL';

    /**
     * Label humain pour l'affichage
     */
    public function label(): string
    {
        return match ($this) {
            self::ORDER => 'Commande',
            self::CANCELLATION => 'Annulation',
            self::MANUAL => 'Ajustement manuel',
        };
    }

    /**
     * Classe CSS pour le badge
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::ORDER => 'danger',
            self::CANCELLATION => 'success',
            self::MANUAL => 'warning',
        };
    }

    /**
     * Icône FontAwesome
     */
    public function icon(): string
    {
        return match ($this) {
            self::ORDER => 'fa-shopping-cart',
            self::CANCELLATION => 'fa-undo',
            self::MANUAL => 'fa-hand-paper',
        };
    }

    /**
     * Applique le signe correct à la quantité selon le type de mouvement
     * 
     * - ORDER: toujours négatif (décrémente le stock)
     * - CANCELLATION: toujours positif (restaure le stock)
     * - MANUAL: conserve le signe tel quel
     */
    public function applySign(int $quantity): int
    {
        return match ($this) {
            self::ORDER => -abs($quantity),
            self::CANCELLATION => abs($quantity),
            self::MANUAL => $quantity,
        };
    }

    /**
     * Note par défaut pour ce type de mouvement
     */
    public function defaultNote(?int $referenceId = null): string
    {
        $ref = $referenceId ? " #{$referenceId}" : '';
        
        return match ($this) {
            self::ORDER => "Décrémentation via commande{$ref}",
            self::CANCELLATION => "Restauration de stock après annulation{$ref}",
            self::MANUAL => 'Ajustement manuel de stock',
        };
    }

    /**
     * Indique si une référence (order_id) est requise
     */
    public function requiresReference(): bool
    {
        return match ($this) {
            self::ORDER, self::CANCELLATION => true,
            self::MANUAL => false,
        };
    }

    /**
     * Retourne tous les types
     * 
     * @return StockMovementReason[]
     */
    public static function all(): array
    {
        return self::cases();
    }

    /**
     * Crée depuis une chaîne avec valeur par défaut
     */
    public static function fromString(string $value, StockMovementReason $default = self::MANUAL): StockMovementReason
    {
        return self::tryFrom($value) ?? $default;
    }
}
