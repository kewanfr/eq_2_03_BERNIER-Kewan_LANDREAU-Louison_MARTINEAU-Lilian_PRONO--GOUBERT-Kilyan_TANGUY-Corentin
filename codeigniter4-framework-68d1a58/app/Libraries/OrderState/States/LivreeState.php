<?php

namespace App\Libraries\OrderState\States;

use App\Libraries\OrderState\AbstractOrderState;
use App\Libraries\OrderState\OrderContext;

/**
 * État "Livrée" - État terminal, la commande a été livrée au client
 */
class LivreeState extends AbstractOrderState
{
    /**
     * {@inheritdoc}
     */
    public function getCode(): string
    {
        return 'LIVREE';
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return 'Livrée';
    }

    /**
     * {@inheritdoc}
     */
    public function getBadgeClass(): string
    {
        return 'success';
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon(): string
    {
        return 'fa-check-double';
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
        // Une commande livrée ne peut pas être annulée
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function onEnter(OrderContext $context): void
    {
        $this->log($context, 'Commande livrée avec succès');
        
        // Ici, on pourrait :
        // - Demander au client de laisser un avis
        // - Envoyer un email de satisfaction
        // - Mettre à jour les statistiques de livraison
        // - Déclencher un programme de fidélité
    }
}
