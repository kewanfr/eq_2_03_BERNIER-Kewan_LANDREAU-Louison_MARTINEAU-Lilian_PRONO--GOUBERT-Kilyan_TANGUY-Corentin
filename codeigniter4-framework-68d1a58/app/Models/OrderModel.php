<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Gestion des commandes
 */
class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'order_date', 'status', 'total_ht', 'total_ttc', 'delivery_address_id', 'billing_address_id', 'delivery_method', 'delivery_cost'];
    protected $useTimestamps = false;

    // Statuts possibles
    const STATUS_PAYEE = 'PAYEE';
    const STATUS_EN_PREPARATION = 'EN_PREPARATION';
    const STATUS_PRETE = 'PRETE';
    const STATUS_EXPEDIEE = 'EXPEDIEE';
    const STATUS_LIVREE = 'LIVREE';
    const STATUS_ANNULEE = 'ANNULEE';

    // Crée une commande depuis le panier
    public function createFromCart(int $userId, int $cartId, string $deliveryMethod = 'pickup', float $deliveryCost = 0.0)
    {
        $cartModel = new CartModel();
        $items = $cartModel->getCartItems($cartId);
        
        if (empty($items)) {
            return false;
        }

        $total = $cartModel->getTotal($cartId);
        $tva = 0.20; // 20% TVA
        $totalHT = $total / (1 + $tva);
        $totalTTC = $total + $deliveryCost; // Ajoute les frais de livraison

        // Crée la commande
        $orderData = [
            'user_id' => $userId,
            'order_date' => date('Y-m-d H:i:s'),
            'status' => self::STATUS_PAYEE,
            'total_ht' => $totalHT,
            'total_ttc' => $totalTTC,
            'delivery_method' => $deliveryMethod,
            'delivery_cost' => $deliveryCost
        ];

        if (!$this->insert($orderData)) {
            return false;
        }

        $orderId = $this->insertID();

        // Crée les lignes de commande
        $db = \Config\Database::connect();
        $builder = $db->table('order_items');

        $movementModel = new StockMovementModel();
        foreach ($items as $item) {
            $builder->insert([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price']
            ]);

            // Met à jour le stock
            $productModel = new ProductModel();
            $product = $productModel->getProductById($item['product_id']);
            $newQuantity = $product['quantity'] - $item['quantity'];
            $productModel->update($item['product_id'], ['quantity' => $newQuantity]);

            // Journalise le mouvement de stock (commande)
            $movementModel->logMovement(
                (int)$item['product_id'],
                -(int)$item['quantity'],
                'ORDER',
                $userId,
                $orderId,
                'Décrémentation via commande'
            );
        }

        // Vide le panier
        $cartModel->clearCart($cartId);

        return $orderId;
    }

    // Commandes d'un utilisateur
    public function getUserOrders(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('order_date', 'DESC')
            ->findAll();
    }

    // Toutes les commandes (pour admin)
    public function getAllOrders(?string $status = null): array
    {
        $builder = $this->builder();
        $builder->select('orders.*, users.username, users.customer_type, users.company_name, users.phone, users.address, auth_identities.secret as email')
            ->join('users', 'users.id = orders.user_id')
            ->join('auth_identities', 'auth_identities.user_id = users.id', 'left');
        
        if ($status) {
            $builder->where('orders.status', $status);
        }
        
        return $builder->orderBy('orders.order_date', 'DESC')->get()->getResultArray();
    }

    // Détails commande
    public function getOrderDetails(int $orderId): ?array
    {
        $db = \Config\Database::connect();
        
        // Récupère la commande avec infos utilisateur
        $order = $db->table('orders')
            ->select('orders.*, users.username, users.customer_type, users.company_name, users.siret, users.tva_number, users.phone, users.address, auth_identities.secret as email')
            ->join('users', 'users.id = orders.user_id')
            ->join('auth_identities', 'auth_identities.user_id = users.id', 'left')
            ->where('orders.id', $orderId)
            ->get()
            ->getRowArray();
        
        if (!$order) {
            return null;
        }

        // Récupère les items
        $items = $db->table('order_items')
            ->select('order_items.*, products.name, products.img_src')
            ->join('products', 'products.id = order_items.product_id')
            ->where('order_items.order_id', $orderId)
            ->get()
            ->getResultArray();

        $order['items'] = $items;
        return $order;
    }

    // Change le statut
    public function changeStatus(int $orderId, string $newStatus): bool
    {
        $validStatuses = [
            self::STATUS_PAYEE,
            self::STATUS_EN_PREPARATION,
            self::STATUS_PRETE,
            self::STATUS_EXPEDIEE,
            self::STATUS_LIVREE,
            self::STATUS_ANNULEE
        ];

        if (!in_array($newStatus, $validStatuses)) {
            return false;
        }

        return $this->update($orderId, ['status' => $newStatus]);
    }

    // Annule une commande
    public function cancelOrder(int $orderId): bool
    {
        $order = $this->find($orderId);
        
        if (!$order || $order['status'] === self::STATUS_LIVREE) {
            log_message('error', 'Cannot cancel order #' . $orderId . ' - Order not found or already delivered');
            return false;
        }

        log_message('info', 'Cancelling order #' . $orderId . ' - Current status: ' . $order['status']);

        // Restaure le stock
        $db = \Config\Database::connect();
        $items = $db->table('order_items')
            ->where('order_id', $orderId)
            ->get()
            ->getResultArray();

        $productModel = new ProductModel();
        $movementModel = new StockMovementModel();
        foreach ($items as $item) {
            $product = $productModel->getProductById($item['product_id']);
            $newQuantity = $product['quantity'] + $item['quantity'];
            $productModel->update($item['product_id'], ['quantity' => $newQuantity]);
            log_message('info', 'Restored stock for product #' . $item['product_id'] . ': ' . $product['quantity'] . ' -> ' . $newQuantity);

            // Journalise le mouvement de stock (annulation)
            $movementModel->logMovement(
                (int)$item['product_id'],
                (int)$item['quantity'],
                'CANCELLATION',
                (int)$order['user_id'] ?? null,
                $orderId,
                'Restauration de stock après annulation'
            );
        }

        // Changement de statut
        $result = $this->update($orderId, ['status' => self::STATUS_ANNULEE]);
        log_message('info', 'Status update result for order #' . $orderId . ': ' . ($result ? 'SUCCESS' : 'FAILED'));
        
        // Vérification
        $updatedOrder = $this->find($orderId);
        log_message('info', 'Order #' . $orderId . ' new status: ' . $updatedOrder['status']);
        
        return $updatedOrder['status'] === self::STATUS_ANNULEE;
    }

    // Stats pour dashboard
    public function getStatistics(): array
    {
        $db = \Config\Database::connect();
        
        $totalOrders = $this->countAll();
        $totalRevenue = $db->table('orders')
            ->selectSum('total_ttc')
            ->get()
            ->getRowArray()['total_ttc'] ?? 0;

        $ordersByStatus = $db->table('orders')
            ->select('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->getResultArray();

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'by_status' => $ordersByStatus
        ];
    }
}
