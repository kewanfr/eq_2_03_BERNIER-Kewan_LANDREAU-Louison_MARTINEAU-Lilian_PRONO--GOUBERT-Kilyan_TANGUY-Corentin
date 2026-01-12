<?php

namespace App\Libraries\OrderState\Examples;

use App\Libraries\OrderState\OrderContext;
use App\Libraries\OrderState\OrderStateFactory;
use App\Enums\OrderStatus;

/**
 * Exemples d'utilisation du pattern State pour les commandes
 * 
 * Ce fichier contient des exemples pratiques d'utilisation.
 * Il n'est pas destiné à être exécuté en production.
 */
class OrderStateExamples
{
    /**
     * Exemple 1 : Cycle de vie complet d'une commande
     */
    public static function example1_CompleteOrderLifecycle()
    {
        echo "=== Exemple 1 : Cycle de vie complet d'une commande ===\n\n";
        
        // Créer un contexte pour une nouvelle commande
        $order = new OrderContext(
            orderId: 123,
            orderData: [
                'customer_id' => 456,
                'customer_email' => 'client@example.com',
                'total' => 99.99,
                'items' => [
                    ['product_id' => 1, 'quantity' => 2],
                    ['product_id' => 2, 'quantity' => 1],
                ]
            ]
        );
        
        // État initial
        echo "État initial : {$order->getCurrentStatusLabel()}\n";
        echo "Badge class : {$order->getCurrentBadgeClass()}\n";
        echo "Icon : {$order->getCurrentIcon()}\n";
        echo "Peut être annulée ? " . ($order->canBeCancelled() ? 'Oui' : 'Non') . "\n";
        echo "Transitions possibles : " . implode(', ', $order->getNextPossibleStatuses()) . "\n\n";
        
        // Transition vers EN_PREPARATION
        echo ">>> Mise en préparation...\n";
        if ($order->transitionTo('EN_PREPARATION')) {
            echo "✓ Transition réussie vers {$order->getCurrentStatusLabel()}\n";
        }
        echo "Transitions possibles : " . implode(', ', $order->getNextPossibleStatuses()) . "\n\n";
        
        // Transition vers PRETE
        echo ">>> Préparation terminée...\n";
        if ($order->transitionTo('PRETE')) {
            echo "✓ Transition réussie vers {$order->getCurrentStatusLabel()}\n";
        }
        echo "Transitions possibles : " . implode(', ', $order->getNextPossibleStatuses()) . "\n\n";
        
        // Transition vers EXPEDIEE
        echo ">>> Expédition...\n";
        if ($order->transitionTo('EXPEDIEE')) {
            echo "✓ Transition réussie vers {$order->getCurrentStatusLabel()}\n";
        }
        echo "Peut être annulée ? " . ($order->canBeCancelled() ? 'Oui' : 'Non') . "\n";
        echo "Transitions possibles : " . implode(', ', $order->getNextPossibleStatuses()) . "\n\n";
        
        // Transition vers LIVREE
        echo ">>> Livraison...\n";
        if ($order->transitionTo('LIVREE')) {
            echo "✓ Transition réussie vers {$order->getCurrentStatusLabel()}\n";
        }
        echo "Est terminal ? " . ($order->isTerminal() ? 'Oui' : 'Non') . "\n";
        echo "Transitions possibles : " . implode(', ', $order->getNextPossibleStatuses()) . "\n\n";
    }
    
    /**
     * Exemple 2 : Annulation d'une commande
     */
    public static function example2_CancelOrder()
    {
        echo "=== Exemple 2 : Annulation d'une commande ===\n\n";
        
        $order = new OrderContext(123, []);
        
        echo "État initial : {$order->getCurrentStatusLabel()}\n";
        
        // Passer en préparation
        $order->transitionTo('EN_PREPARATION');
        echo "État actuel : {$order->getCurrentStatusLabel()}\n";
        echo "Peut être annulée ? " . ($order->canBeCancelled() ? 'Oui' : 'Non') . "\n";
        
        // Annuler
        echo "\n>>> Annulation de la commande...\n";
        if ($order->cancel()) {
            echo "✓ Commande annulée avec succès\n";
            echo "État final : {$order->getCurrentStatusLabel()}\n";
            echo "Est terminal ? " . ($order->isTerminal() ? 'Oui' : 'Non') . "\n";
        }
        echo "\n";
    }
    
    /**
     * Exemple 3 : Tentative de transition invalide
     */
    public static function example3_InvalidTransition()
    {
        echo "=== Exemple 3 : Tentative de transition invalide ===\n\n";
        
        $order = new OrderContext(123, []);
        
        echo "État initial : {$order->getCurrentStatusLabel()}\n";
        echo "Transitions possibles : " . implode(', ', $order->getNextPossibleStatuses()) . "\n\n";
        
        // Tenter une transition invalide
        echo ">>> Tentative de passer directement à LIVREE...\n";
        if ($order->transitionTo('LIVREE')) {
            echo "✓ Transition réussie\n";
        } else {
            echo "✗ Transition refusée (invalide depuis {$order->getCurrentStatusLabel()})\n";
        }
        
        echo "État toujours : {$order->getCurrentStatusLabel()}\n\n";
    }
    
    /**
     * Exemple 4 : Utilisation avec l'enum OrderStatus
     */
    public static function example4_UsingEnum()
    {
        echo "=== Exemple 4 : Utilisation avec l'enum OrderStatus ===\n\n";
        
        // L'enum sert de façade au pattern State
        $status = OrderStatus::PRETE;
        
        echo "Statut : {$status->value}\n";
        echo "Label : {$status->label()}\n";
        echo "Badge : {$status->badgeClass()}\n";
        echo "Icon : {$status->icon()}\n";
        echo "Est terminal ? " . ($status->isTerminal() ? 'Oui' : 'Non') . "\n";
        echo "Peut être annulée ? " . ($status->canBeCancelled() ? 'Oui' : 'Non') . "\n";
        
        echo "\nTransitions possibles :\n";
        foreach ($status->nextPossibleStatuses() as $nextStatus) {
            echo "  - {$nextStatus->value} ({$nextStatus->label()})\n";
        }
        
        // Vérifier une transition
        $newStatus = OrderStatus::EXPEDIEE;
        if ($status->canTransitionTo($newStatus)) {
            echo "\n✓ Transition vers {$newStatus->label()} est valide\n";
        }
        
        echo "\n";
    }
    
    /**
     * Exemple 5 : Utilisation de la factory
     */
    public static function example5_UsingFactory()
    {
        echo "=== Exemple 5 : Utilisation de la factory ===\n\n";
        
        // Lister tous les statuts disponibles
        echo "Tous les statuts disponibles :\n";
        foreach (OrderStateFactory::getAllStatusCodes() as $code) {
            $state = OrderStateFactory::createFromCode($code);
            echo sprintf(
                "  - %s : %s (terminal: %s)\n",
                $code,
                $state->getLabel(),
                $state->isTerminal() ? 'oui' : 'non'
            );
        }
        
        // Vérifier la validité d'un code
        echo "\nVérification de codes :\n";
        echo "  'PAYEE' est valide ? " . (OrderStateFactory::isValidStatusCode('PAYEE') ? 'Oui' : 'Non') . "\n";
        echo "  'INVALID' est valide ? " . (OrderStateFactory::isValidStatusCode('INVALID') ? 'Oui' : 'Non') . "\n";
        
        echo "\n";
    }
    
    /**
     * Exemple 6 : Intégration avec un modèle
     */
    public static function example6_ModelIntegration()
    {
        echo "=== Exemple 6 : Intégration avec un modèle (pseudo-code) ===\n\n";
        
        echo <<<'PHP'
// Dans OrderModel.php ou un Controller
class OrderModel extends Model
{
    public function updateStatus(int $orderId, string $newStatus): bool
    {
        // Charger la commande
        $orderData = $this->find($orderId);
        if (!$orderData) {
            return false;
        }
        
        // Créer le contexte avec l'état actuel
        $context = new OrderContext(
            orderId: $orderId,
            orderData: $orderData,
            initialState: OrderStateFactory::createFromCode($orderData['status'])
        );
        
        // Tenter la transition
        if (!$context->transitionTo($newStatus)) {
            log_message('warning', "Invalid transition from {$orderData['status']} to {$newStatus}");
            return false;
        }
        
        // Sauvegarder le nouveau statut
        $this->update($orderId, ['status' => $newStatus]);
        
        // Les actions onEnter() et onExit() ont déjà été exécutées
        // (emails envoyés, stocks mis à jour, etc.)
        
        return true;
    }
    
    public function canCancelOrder(int $orderId): bool
    {
        $orderData = $this->find($orderId);
        if (!$orderData) {
            return false;
        }
        
        $context = new OrderContext(
            orderId: $orderId,
            orderData: $orderData,
            initialState: OrderStateFactory::createFromCode($orderData['status'])
        );
        
        return $context->canBeCancelled();
    }
}
PHP;
        echo "\n\n";
    }
    
    /**
     * Exécute tous les exemples
     */
    public static function runAll()
    {
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║  Exemples d'utilisation du Pattern State - Commandes      ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        echo "\n";
        
        self::example1_CompleteOrderLifecycle();
        echo str_repeat("─", 60) . "\n\n";
        
        self::example2_CancelOrder();
        echo str_repeat("─", 60) . "\n\n";
        
        self::example3_InvalidTransition();
        echo str_repeat("─", 60) . "\n\n";
        
        self::example4_UsingEnum();
        echo str_repeat("─", 60) . "\n\n";
        
        self::example5_UsingFactory();
        echo str_repeat("─", 60) . "\n\n";
        
        self::example6_ModelIntegration();
        
        echo "\n";
        echo "✓ Tous les exemples ont été exécutés\n";
        echo "\n";
    }
}

// Pour exécuter les exemples :
// OrderStateExamples::runAll();
