<?php

namespace App\Libraries\OrderState\States;

use App\Libraries\OrderState\AbstractOrderState;
use App\Libraries\OrderState\OrderContext;

/**
 * État "Annulée" - État terminal, la commande a été annulée
 */
class AnnuleeState extends AbstractOrderState
{
    /**
     * {@inheritdoc}
     */
    public function getCode(): string
    {
        return 'ANNULEE';
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return 'Annulée';
    }

    /**
     * {@inheritdoc}
     */
    public function getBadgeClass(): string
    {
        return 'danger';
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon(): string
    {
        return 'fa-times-circle';
    }

    /**
     * {@inheritdoc}
     */
    public function getNextPossibleStatuses(): array
    {
        // État terminal : aucune transition possible
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function canBeCancelled(): bool
    {
        // Une commande déjà annulée ne peut pas être annulée à nouveau
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function onEnter(OrderContext $context): void
    {
        $this->log($context, 'Commande annulée');
        
        // Ici, on pourrait :
        // - Restaurer le stock des produits
        // - Déclencher le processus de remboursement
        // - Notifier le client de l'annulation
        // - Annuler les réservations ou allocations liées
    }
}
