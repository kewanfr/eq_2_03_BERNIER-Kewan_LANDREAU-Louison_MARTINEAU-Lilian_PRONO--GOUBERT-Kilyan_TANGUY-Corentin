<?php

namespace App\Libraries\OrderState;

/**
 * Classe abstraite de base pour les états de commande
 * 
 * Implémente les comportements communs à tous les états.
 * Les classes concrètes n'ont qu'à définir leurs spécificités.
 * 
 * @see https://refactoring.guru/design-patterns/state
 */
abstract class AbstractOrderState implements OrderStateInterface
{
    /**
     * {@inheritdoc}
     */
    public function canTransitionTo(string $newStatusCode): bool
    {
        return in_array($newStatusCode, $this->getNextPossibleStatuses(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function isTerminal(): bool
    {
        return empty($this->getNextPossibleStatuses());
    }

    /**
     * {@inheritdoc}
     */
    public function canBeCancelled(): bool
    {
        // Par défaut, les états non terminaux peuvent être annulés
        return !$this->isTerminal() && in_array('ANNULEE', $this->getNextPossibleStatuses(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function onEnter(OrderContext $context): void
    {
        // Comportement par défaut : ne rien faire
        // Les états concrets peuvent surcharger cette méthode
    }

    /**
     * {@inheritdoc}
     */
    public function onExit(OrderContext $context): void
    {
        // Comportement par défaut : ne rien faire
        // Les états concrets peuvent surcharger cette méthode
    }

    /**
     * {@inheritdoc}
     */
    public function transitionTo(OrderContext $context, string $newStatusCode): bool
    {
        // Vérifier si la transition est valide
        if (!$this->canTransitionTo($newStatusCode)) {
            return false;
        }

        // Exécuter l'action de sortie
        $this->onExit($context);

        // Le contexte va changer l'état et appeler onEnter sur le nouvel état
        return true;
    }

    /**
     * Méthode utilitaire pour logger des événements
     */
    protected function log(OrderContext $context, string $message): void
    {
        log_message('info', sprintf(
            'Order #%d - %s: %s',
            $context->getOrderId(),
            $this->getCode(),
            $message
        ));
    }
}
