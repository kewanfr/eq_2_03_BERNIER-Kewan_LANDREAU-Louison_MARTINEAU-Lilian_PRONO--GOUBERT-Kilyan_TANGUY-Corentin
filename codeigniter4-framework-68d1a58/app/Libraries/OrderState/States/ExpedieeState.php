<?php

namespace App\Libraries\OrderState\States;

use App\Libraries\OrderState\AbstractOrderState;
use App\Libraries\OrderState\OrderContext;

/**
 * État "Expédiée" - La commande a été expédiée au client
 */
class ExpedieeState extends AbstractOrderState
{
    /**
     * {@inheritdoc}
     */
    public function getCode(): string
    {
        return 'EXPEDIEE';
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return 'Expédiée';
    }

    /**
     * {@inheritdoc}
     */
    public function getBadgeClass(): string
    {
        return 'primary';
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon(): string
    {
        return 'fa-truck';
    }

    /**
     * {@inheritdoc}
     */
    public function getNextPossibleStatuses(): array
    {
        return ['LIVREE'];
    }

    /**
     * {@inheritdoc}
     */
    public function canBeCancelled(): bool
    {
        // Une commande expédiée ne peut plus être annulée
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function onEnter(OrderContext $context): void
    {
        $this->log($context, 'Commande expédiée');
        
        // Ici, on pourrait :
        // - Envoyer un email avec le numéro de suivi
        // - Intégrer avec l'API du transporteur
        // - Mettre à jour le statut de tracking
    }

    /**
     * {@inheritdoc}
     */
    public function onExit(OrderContext $context): void
    {
        $this->log($context, 'Commande en cours de livraison');
    }
}
