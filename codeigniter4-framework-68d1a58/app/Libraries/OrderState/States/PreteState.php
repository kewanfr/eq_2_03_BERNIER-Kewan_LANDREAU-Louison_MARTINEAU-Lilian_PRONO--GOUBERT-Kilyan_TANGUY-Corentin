<?php

namespace App\Libraries\OrderState\States;

use App\Libraries\OrderState\AbstractOrderState;
use App\Libraries\OrderState\OrderContext;

/**
 * État "Prête" - La commande est prête à être expédiée ou retirée
 */
class PreteState extends AbstractOrderState
{
    /**
     * {@inheritdoc}
     */
    public function getCode(): string
    {
        return 'PRETE';
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return 'Prête';
    }

    /**
     * {@inheritdoc}
     */
    public function getBadgeClass(): string
    {
        return 'info';
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon(): string
    {
        return 'fa-box';
    }

    /**
     * {@inheritdoc}
     */
    public function getNextPossibleStatuses(): array
    {
        // LIVREE directement si retrait sur place
        // EXPEDIEE si livraison par transporteur
        return ['EXPEDIEE', 'LIVREE', 'ANNULEE'];
    }

    /**
     * {@inheritdoc}
     */
    public function onEnter(OrderContext $context): void
    {
        $this->log($context, 'Commande prête');
        
        // Ici, on pourrait :
        // - Notifier le client que sa commande est prête
        // - Envoyer un email avec les instructions de retrait/livraison
        // - Préparer les documents d'expédition si applicable
    }

    /**
     * {@inheritdoc}
     */
    public function onExit(OrderContext $context): void
    {
        $this->log($context, 'Commande quitte l\'état PRETE');
    }
}
