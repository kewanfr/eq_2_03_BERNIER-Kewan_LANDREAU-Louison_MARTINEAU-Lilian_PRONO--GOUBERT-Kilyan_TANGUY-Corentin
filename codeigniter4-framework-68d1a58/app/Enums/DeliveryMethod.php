<?php

namespace App\Enums;

/**
 * Énumération des méthodes de livraison
 * 
 * Gère les différents modes de livraison avec leurs
 * coûts et descriptions.
 */
enum DeliveryMethod: string
{
    case PICKUP = 'pickup';
    case LOCAL_DELIVERY = 'local_delivery';
    case CARRIER_DELIVERY = 'carrier_delivery';

    /**
     * Label humain pour l'affichage
     */
    public function label(): string
    {
        return match ($this) {
            self::PICKUP => 'Retrait à la cidrerie',
            self::LOCAL_DELIVERY => 'Livraison locale',
            self::CARRIER_DELIVERY => 'Livraison transporteur',
        };
    }

    /**
     * Description détaillée
     */
    public function description(): string
    {
        return match ($this) {
            self::PICKUP => 'Venez récupérer votre commande directement à notre cidrerie',
            self::LOCAL_DELIVERY => 'Livraison dans un rayon de 30km autour de la cidrerie',
            self::CARRIER_DELIVERY => 'Expédition par transporteur partout en France',
        };
    }

    /**
     * Icône FontAwesome
     */
    public function icon(): string
    {
        return match ($this) {
            self::PICKUP => 'fa-store',
            self::LOCAL_DELIVERY => 'fa-bicycle',
            self::CARRIER_DELIVERY => 'fa-truck',
        };
    }

    /**
     * Coût de base en euros
     */
    public function baseCost(): float
    {
        return match ($this) {
            self::PICKUP => 0.00,
            self::LOCAL_DELIVERY => 5.00,
            self::CARRIER_DELIVERY => 9.90,
        };
    }

    /**
     * Calcule le coût de livraison selon le total du panier
     * Livraison gratuite au-delà d'un certain montant
     */
    public function calculateCost(float $cartTotal): float
    {
        // Livraison gratuite à partir de 50€
        if ($cartTotal >= 50.00 && $this !== self::CARRIER_DELIVERY) {
            return 0.00;
        }
        
        // Livraison gratuite à partir de 100€ pour transporteur
        if ($cartTotal >= 100.00) {
            return 0.00;
        }
        
        return $this->baseCost();
    }

    /**
     * Délai de livraison estimé
     */
    public function estimatedDelay(): string
    {
        return match ($this) {
            self::PICKUP => 'Disponible sous 24h',
            self::LOCAL_DELIVERY => '1-2 jours ouvrés',
            self::CARRIER_DELIVERY => '3-5 jours ouvrés',
        };
    }

    /**
     * Indique si une adresse de livraison est requise
     */
    public function requiresAddress(): bool
    {
        return match ($this) {
            self::PICKUP => false,
            self::LOCAL_DELIVERY, self::CARRIER_DELIVERY => true,
        };
    }

    /**
     * Retourne toutes les méthodes
     * 
     * @return DeliveryMethod[]
     */
    public static function all(): array
    {
        return self::cases();
    }

    /**
     * Crée depuis une chaîne avec valeur par défaut
     */
    public static function fromString(string $value): DeliveryMethod
    {
        return self::tryFrom($value) ?? self::PICKUP;
    }
}
