<?php

namespace App\Libraries\OrderState\States;

use App\Libraries\OrderState\AbstractOrderState;
use App\Libraries\OrderState\OrderContext;

/**
 * État "Payée" - État initial après le paiement
 * 
 * Cet état représente une commande qui vient d'être payée
 * et qui attend d'être mise en préparation.
 */
class PayeeState extends AbstractOrderState
{
    /**
     * {@inheritdoc}
     */
    public function getCode(): string
    {
        return 'PAYEE';
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return 'Payée';
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
        return 'fa-check-circle';
    }

    /**
     * {@inheritdoc}
     */
    public function getNextPossibleStatuses(): array
    {
        return ['EN_PREPARATION', 'ANNULEE'];
    }

    /**
     * {@inheritdoc}
     */
    public function onEnter(OrderContext $context): void
    {
        $this->log($context, 'Commande payée et confirmée');
        
        // Ici, on pourrait :
        // - Envoyer un email de confirmation au client
        // - Décrémenter le stock (si pas déjà fait)
        // - Notifier l'équipe logistique
    }

    /**
     * {@inheritdoc}
     */
    public function onExit(OrderContext $context): void
    {
        $this->log($context, 'Transition depuis l\'état PAYEE');
    }
}
