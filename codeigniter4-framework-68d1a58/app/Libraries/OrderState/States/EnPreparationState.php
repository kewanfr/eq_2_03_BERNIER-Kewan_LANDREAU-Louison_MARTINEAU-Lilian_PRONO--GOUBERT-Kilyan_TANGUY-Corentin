<?php

namespace App\Libraries\OrderState\States;

use App\Libraries\OrderState\AbstractOrderState;
use App\Libraries\OrderState\OrderContext;

/**
 * État "En préparation" - La commande est en cours de préparation
 */
class EnPreparationState extends AbstractOrderState
{
    /**
     * {@inheritdoc}
     */
    public function getCode(): string
    {
        return 'EN_PREPARATION';
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return 'En préparation';
    }

    /**
     * {@inheritdoc}
     */
    public function getBadgeClass(): string
    {
        return 'warning';
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon(): string
    {
        return 'fa-cog';
    }

    /**
     * {@inheritdoc}
     */
    public function getNextPossibleStatuses(): array
    {
        return ['PRETE', 'ANNULEE'];
    }

    /**
     * {@inheritdoc}
     */
    public function onEnter(OrderContext $context): void
    {
        $this->log($context, 'Commande mise en préparation');
        
        // Ici, on pourrait :
        // - Notifier l'équipe de préparation
        // - Ajouter la commande à la file d'attente de préparation
        // - Envoyer une notification au client
    }

    /**
     * {@inheritdoc}
     */
    public function onExit(OrderContext $context): void
    {
        $this->log($context, 'Préparation terminée');
    }
}
