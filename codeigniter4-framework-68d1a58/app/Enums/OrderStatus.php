<?php

namespace App\Enums;

use App\Libraries\OrderState\OrderStateFactory;
use App\Libraries\OrderState\OrderStateInterface;

/**
 * Énumération des statuts de commande
 * 
 * Cette énumération sert de façade/adaptateur au pattern State implémenté
 * dans App\Libraries\OrderState. Elle délègue les comportements complexes
 * aux classes State concrètes tout en gardant une API simple.
 * 
 * @see App\Libraries\OrderState\OrderStateInterface
 * @see https://refactoring.guru/design-patterns/state
 */
enum OrderStatus: string
{
    case PAYEE = 'PAYEE';
    case EN_PREPARATION = 'EN_PREPARATION';
    case PRETE = 'PRETE';
    case EXPEDIEE = 'EXPEDIEE';
    case LIVREE = 'LIVREE';
    case ANNULEE = 'ANNULEE';

    /**
     * Retourne l'objet State correspondant à ce statut
     * 
     * Cette méthode crée un lien entre l'enum et le pattern State.
     */
    private function getState(): OrderStateInterface
    {
        return OrderStateFactory::createFromCode($this->value);
    }

    /**
     * Retourne l'objet State correspondant à ce statut
     * 
     * Cette méthode crée un lien entre l'enum et le pattern State.
     */
    private function getState(): OrderStateInterface
    {
        return OrderStateFactory::createFromCode($this->value);
    }

    /**
     * Label humain pour l'affichage
     * 
     * Délègue au pattern State.
     */
    public function label(): string
    {
        return $this->getState()->getLabel();
    }

    /**
     * Classe CSS Bootstrap pour le badge
     * 
     * Délègue au pattern State.
     */
    public function badgeClass(): string
    {
        return $this->getState()->getBadgeClass();
    }

    /**
     * Icône FontAwesome
     * 
     * Délègue au pattern State.
     */
    public function icon(): string
    {
        return $this->getState()->getIcon();
    }

    /**
     * Transitions possibles depuis ce statut
     * 
     * Délègue au pattern State.
     * 
     * @return OrderStatus[]
     */
    public function nextPossibleStatuses(): array
    {
        $statusCodes = $this->getState()->getNextPossibleStatuses();
        
        // Convertit les codes en instances de l'enum
        return array_map(
            fn($code) => self::from($code),
            $statusCodes
        );
    }

    /**
     * Vérifie si la transition vers un nouveau statut est valide
     * 
     * Délègue au pattern State.
     */
    public function canTransitionTo(OrderStatus $newStatus): bool
    {
        return $this->getState()->canTransitionTo($newStatus->value);
    }

    /**
     * Indique si la commande peut être annulée
     * 
     * Délègue au pattern State.
     */
    public function canBeCancelled(): bool
    {
        return $this->getState()->canBeCancelled();
    }

    /**
     * Indique si c'est un statut terminal (pas de transition possible)
     * 
     * Délègue au pattern State.
     */
    public function isTerminal(): bool
    {
        return $this->getState()->isTerminal();
    }

    /**
     * Retourne tous les statuts
     * 
     * @return OrderStatus[]
     */
    public static function all(): array
    {
        return self::cases();
    }

    /**
     * Retourne les statuts actifs (non terminaux)
     * 
     * @return OrderStatus[]
     */
    public static function active(): array
    {
        return array_filter(self::cases(), fn($s) => !$s->isTerminal());
    }

    /**
     * Crée un statut depuis une chaîne, avec valeur par défaut
     */
    public static function fromString(string $value, OrderStatus $default = self::PAYEE): OrderStatus
    {
        return self::tryFrom($value) ?? $default;
    }
}
