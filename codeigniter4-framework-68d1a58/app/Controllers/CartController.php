<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\ProductModel;
use CodeIgniter\Controller;

/**
 * pour gérer le panier de l'utilisateur
 */
class CartController extends Controller
{
    protected $cartModel;
    protected $productModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->productModel = new ProductModel();
    }

    /**
     * Affiche le panier de l'utilisateur
     */
    public function index()
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté pour accéder au panier');
        }

        $userId = auth()->id();
        $cartId = $this->cartModel->getOrCreateCart($userId);
        
        $data = [
            'items' => $this->cartModel->getCartItems($cartId),
            'total' => $this->cartModel->getTotal($cartId),
            'itemCount' => $this->cartModel->getItemCount($cartId)
        ];

        return view('cart/index', $data);
    }

    /**
     * Ajoute un produit au panier
     */
    public function add()
    {
        // Vérifie si l'utilisateur est connecté
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Vous devez être connecté pour ajouter au panier',
                'redirect' => '/login'
            ]);
        }

        // Accepte les données en POST ou JSON
        $productId = null;
        $quantity = 1;
        
        // Vérifie d'abord le Content-Type pour éviter les erreurs de parsing
        $contentType = $this->request->getHeaderLine('Content-Type');
        
        if (strpos($contentType, 'application/json') !== false) {
            // Si c'est du JSON, on le parse
            try {
                $json = $this->request->getJSON();
                if ($json) {
                    $productId = $json->product_id ?? null;
                    $quantity = $json->quantity ?? 1;
                }
            } catch (\Exception $e) {
                // Si le parsing JSON échoue, on continue avec POST
                log_message('debug', 'JSON parsing failed, fallback to POST: ' . $e->getMessage());
            }
        }
        
        // Si pas de données JSON, on essaie POST
        if ($productId === null) {
            $productId = $this->request->getPost('product_id');
            $quantity = $this->request->getPost('quantity') ?? 1;
        }

        // Debug log
        log_message('debug', 'Cart add - Product ID: ' . $productId . ', Quantity: ' . $quantity);

        // Valide les données
        if (!$productId || $quantity < 1) {
            log_message('error', 'Cart add - Invalid data: productId=' . var_export($productId, true) . ', quantity=' . var_export($quantity, true));
            return $this->response->setJSON(['success' => false, 'message' => 'Données invalides (ID: ' . $productId . ', Qté: ' . $quantity . ')']);
        }

        // Vérifie que le produit existe
        $product = $this->productModel->getProductById($productId);
        if (!$product) {
            return $this->response->setJSON(['success' => false, 'message' => 'Produit introuvable']);
        }

        // Vérifie le stock
        // Calcul de la quantité cumulée déjà présente dans le panier
        $userId = auth()->id();
        $cartId = $this->cartModel->getOrCreateCart($userId);
        $existingItem = $this->cartModel->getItem($cartId, (int)$productId);
        $currentQty = $existingItem ? (int)$existingItem['quantity'] : 0;

        if ((int)$product['quantity'] < ($currentQty + (int)$quantity)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Stock insuffisant']);
        }


        if ($this->cartModel->addItem($cartId, $productId, $quantity)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Produit ajouté']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Erreur']);
    }

    /**
     * Retire un produit du panier
     */
    public function remove($productId)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->id();
        $cartId = $this->cartModel->getOrCreateCart($userId);

        if ($this->cartModel->removeItem($cartId, $productId)) {
            return redirect()->to('/cart')->with('success', 'Produit retiré du panier');
        }

        return redirect()->to('/cart')->with('error', 'Erreur lors de la suppression');
    }

    /**
     * Met à jour la quantité d'un produit dans le panier
     */
    public function update()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity');

        if (!$productId || $quantity < 0) {
            return redirect()->back()->with('error', 'Données invalides');
        }

        $userId = auth()->id();
        $cartId = $this->cartModel->getOrCreateCart($userId);

        if ($this->cartModel->updateQuantity($cartId, $productId, $quantity)) {
            return redirect()->to('/cart')->with('success', 'Quantité mise à jour');
        }

        return redirect()->to('/cart')->with('error', 'Erreur lors de la mise à jour');
    }

    /**
     * Vide le panier
     */
    public function clear()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $userId = auth()->id();
        $cartId = $this->cartModel->getOrCreateCart($userId);

        if ($this->cartModel->clearCart($cartId)) {
            return redirect()->to('/cart')->with('success', 'Panier vidé');
        }

        return redirect()->to('/cart')->with('error', 'Erreur lors du vidage du panier');
    }

    /**
     * Nombre d'articles dans le panier (pour affichage dans header)
     */
    public function count()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['count' => 0]);
        }

        $userId = auth()->id();
        $cartId = $this->cartModel->getOrCreateCart($userId);
        $count = $this->cartModel->getItemCount($cartId);

        return $this->response->setJSON(['count' => $count]);
    }
}
