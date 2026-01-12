<?php

namespace App\Libraries\OrderState;

/**
 * Contexte pour le pattern State appliqué aux commandes
 * 
 * Le contexte maintient une référence à l'état actuel et délègue
 * tout le travail spécifique à l'état à cet objet.
 * 
 * @see https://refactoring.guru/design-patterns/state
 */
class OrderContext
{
    /**
     * L'état actuel de la commande
     */
    private OrderStateInterface $currentState;

    /**
     * L'ID de la commande
     */
    private int $orderId;

    /**
     * Données supplémentaires de la commande
     */
    private array $orderData;

    /**
     * @param int $orderId L'ID de la commande
     * @param array $orderData Les données de la commande
     * @param OrderStateInterface|null $initialState L'état initial (si null, sera PAYEE par défaut)
     */
    public function __construct(int $orderId, array $orderData = [], ?OrderStateInterface $initialState = null)
    {
        $this->orderId = $orderId;
        $this->orderData = $orderData;
        
        if ($initialState === null) {
            // État par défaut
            $this->currentState = OrderStateFactory::createFromCode('PAYEE');
        } else {
            $this->currentState = $initialState;
        }
    }

    /**
     * Retourne l'ID de la commande
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * Retourne les données de la commande
     */
    public function getOrderData(): array
    {
        return $this->orderData;
    }

    /**
     * Met à jour les données de la commande
     */
    public function setOrderData(array $data): void
    {
        $this->orderData = $data;
    }

    /**
     * Retourne une donnée spécifique de la commande
     */
    public function getOrderDataValue(string $key, mixed $default = null): mixed
    {
        return $this->orderData[$key] ?? $default;
    }

    /**
     * Retourne l'état actuel
     */
    public function getState(): OrderStateInterface
    {
        return $this->currentState;
    }

    /**
     * Change l'état de la commande
     * 
     * Cette méthode est utilisée pour effectuer une transition d'état.
     * Elle est généralement appelée par l'état lui-même.
     */
    public function setState(OrderStateInterface $state): void
    {
        $this->currentState = $state;
        $state->onEnter($this);
    }

    /**
     * Effectue une transition vers un nouveau statut
     * 
     * @param string $newStatusCode Le code du nouveau statut
     * @return bool True si la transition a réussi
     */
    public function transitionTo(string $newStatusCode): bool
    {
        // Vérifier si la transition est possible
        if (!$this->currentState->canTransitionTo($newStatusCode)) {
            log_message('warning', sprintf(
                'Invalid transition from %s to %s for order #%d',
                $this->currentState->getCode(),
                $newStatusCode,
                $this->orderId
            ));
            return false;
        }

        // Effectuer la transition
        if (!$this->currentState->transitionTo($this, $newStatusCode)) {
            return false;
        }

        // Créer et définir le nouvel état
        $newState = OrderStateFactory::createFromCode($newStatusCode);
        $this->setState($newState);

        log_message('info', sprintf(
            'Order #%d transitioned from %s to %s',
            $this->orderId,
            $this->currentState->getCode(),
            $newStatusCode
        ));

        return true;
    }

    /**
     * Retourne le code du statut actuel
     */
    public function getCurrentStatusCode(): string
    {
        return $this->currentState->getCode();
    }

    /**
     * Retourne le label du statut actuel
     */
    public function getCurrentStatusLabel(): string
    {
        return $this->currentState->getLabel();
    }

    /**
     * Retourne la classe CSS du badge pour le statut actuel
     */
    public function getCurrentBadgeClass(): string
    {
        return $this->currentState->getBadgeClass();
    }

    /**
     * Retourne l'icône du statut actuel
     */
    public function getCurrentIcon(): string
    {
        return $this->currentState->getIcon();
    }

    /**
     * Retourne les statuts suivants possibles
     */
    public function getNextPossibleStatuses(): array
    {
        return $this->currentState->getNextPossibleStatuses();
    }

    /**
     * Vérifie si la commande peut être annulée
     */
    public function canBeCancelled(): bool
    {
        return $this->currentState->canBeCancelled();
    }

    /**
     * Vérifie si le statut actuel est terminal
     */
    public function isTerminal(): bool
    {
        return $this->currentState->isTerminal();
    }

    /**
     * Annule la commande
     */
    public function cancel(): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        return $this->transitionTo('ANNULEE');
    }
}
