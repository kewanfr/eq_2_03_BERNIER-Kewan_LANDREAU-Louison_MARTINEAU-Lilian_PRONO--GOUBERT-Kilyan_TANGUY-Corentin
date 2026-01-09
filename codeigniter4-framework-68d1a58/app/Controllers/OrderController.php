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

        // Crée la commande
        $orderId = $this->orderModel->createFromCart($userId, $cartId);

        if (!$orderId) {
            return redirect()->to('/cart')->with('error', 'Erreur lors de la création de la commande');
        }

        return redirect()->to('/orders/' . $orderId)->with('success', 'Commande passée avec succès !');
    }
}
