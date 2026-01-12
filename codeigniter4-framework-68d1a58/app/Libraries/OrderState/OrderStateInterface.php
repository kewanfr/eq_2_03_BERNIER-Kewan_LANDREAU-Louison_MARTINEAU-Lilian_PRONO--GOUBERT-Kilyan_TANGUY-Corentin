<?php

namespace App\Libraries\OrderState;

/**
 * Interface pour le pattern State appliqué aux commandes
 * 
 * Selon les bonnes pratiques du pattern State, cette interface définit
 * les méthodes que tous les états concrets doivent implémenter.
 * 
 * @see https://refactoring.guru/design-patterns/state
 */
interface OrderStateInterface
{
    /**
     * Retourne le code du statut
     */
    public function getCode(): string;

    /**
     * Retourne le label humain pour l'affichage
     */
    public function getLabel(): string;

    /**
     * Retourne la classe CSS Bootstrap pour le badge
     */
    public function getBadgeClass(): string;

    /**
     * Retourne l'icône FontAwesome
     */
    public function getIcon(): string;

    /**
     * Retourne les codes des statuts suivants possibles
     * 
     * @return string[]
     */
    public function getNextPossibleStatuses(): array;

    /**
     * Vérifie si la transition vers un nouveau statut est valide
     */
    public function canTransitionTo(string $newStatusCode): bool;

    /**
     * Indique si la commande peut être annulée dans cet état
     */
    public function canBeCancelled(): bool;

    /**
     * Indique si c'est un statut terminal (pas de transition possible)
     */
    public function isTerminal(): bool;

    /**
     * Action exécutée lors de l'entrée dans cet état
     * 
     * @param OrderContext $context Le contexte de la commande
     */
    public function onEnter(OrderContext $context): void;

    /**
     * Action exécutée lors de la sortie de cet état
     * 
     * @param OrderContext $context Le contexte de la commande
     */
    public function onExit(OrderContext $context): void;

    /**
     * Traite la transition vers un nouvel état
     * 
     * @param OrderContext $context Le contexte de la commande
     * @param string $newStatusCode Le code du nouveau statut
     * @return bool True si la transition a réussi
     */
    public function transitionTo(OrderContext $context, string $newStatusCode): bool;
}
