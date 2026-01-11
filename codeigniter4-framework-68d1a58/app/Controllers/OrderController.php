<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\CartModel;
use CodeIgniter\Controller;

// Gère les commandes côté client
class OrderController extends Controller
{
    protected $orderModel;
    protected $cartModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->cartModel = new CartModel();
    }

    // Historique des commandes
    public function index()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté');
        }

        $userId = auth()->id();
        $orders = $this->orderModel->getUserOrders($userId);

        $data = ['orders' => $orders];
        return view('orders/index', $data);
    }

    // Détails d'une commande
    public function details($id)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $order = $this->orderModel->getOrderDetails($id);

        if (!$order || $order['user_id'] != auth()->id()) {
            return redirect()->to('/orders')->with('error', 'Commande introuvable');
        }

        $data = ['order' => $order];
        return view('orders/details', $data);
    }

    // Page de validation avant commande
    public function checkout()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté pour commander');
        }

        $userId = auth()->id();
        $cartId = $this->cartModel->getOrCreateCart($userId);
        $items = $this->cartModel->getCartItems($cartId);

        if (empty($items)) {
            return redirect()->to('/cart')->with('error', 'Votre panier est vide');
        }

        $data = [
            'items' => $items,
            'total' => $this->cartModel->getTotal($cartId)
        ];

        return view('orders/checkout', $data);
    }

    // Valide et crée la commande
    public function place()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->id();
        $cartId = $this->cartModel->getOrCreateCart($userId);
        
        // Récupère les infos de livraison
        $deliveryMethod = $this->request->getPost('delivery_method') ?? 'pickup';
        $deliveryCost = (float) ($this->request->getPost('delivery_cost') ?? 0);

        // Crée la commande avec les infos de livraison
        $orderId = $this->orderModel->createFromCart($userId, $cartId, $deliveryMethod, $deliveryCost);

        if (!$orderId) {
            return redirect()->to('/cart')->with('error', 'Erreur lors de la création de la commande');
        }

        return redirect()->to('/orders/' . $orderId)->with('success', 'Commande passée avec succès !');
    }

    // Annule une commande (si pas encore en préparation)
    public function cancel($id)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté');
        }

        $order = $this->orderModel->find($id);
        if (!$order || (int)$order['user_id'] !== (int)auth()->id()) {
            return redirect()->to('/orders')->with('error', 'Commande introuvable');
        }

        // Annulation seulement si la commande n'est pas encore en préparation
        if ($order['status'] !== OrderModel::STATUS_PAYEE) {
            return redirect()->to('/orders/' . $id)->with('error', "La commande ne peut plus être annulée car elle est déjà en préparation ou au-delà.");
        }

        $ok = $this->orderModel->cancelOrder((int)$id);
        if ($ok) {
            return redirect()->to('/orders/' . $id)->with('success', 'Commande annulée avec succès.');
        }

        return redirect()->to('/orders/' . $id)->with('error', "Impossible d'annuler la commande.");
    }
}
