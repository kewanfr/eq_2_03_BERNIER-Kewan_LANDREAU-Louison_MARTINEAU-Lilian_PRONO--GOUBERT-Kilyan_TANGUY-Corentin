<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Gestion du panier
 */
class CartModel extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'created_at'];
    protected $useTimestamps = false;

    // Récupère le panier de l'utilisateur ou en crée un nouveau
    public function getOrCreateCart(int $userId): int
    {
        $cart = $this->where('user_id', $userId)->first();
        
        if (!$cart) {
            $data = [
                'user_id' => $userId,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->insert($data);
            return $this->insertID();
        }
        
        return $cart['id'];
    }

    // Ajoute un produit au panier
    public function addItem(int $cartId, int $productId, int $quantity = 1): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table('cart_items');
        
        // Vérifie si le produit est déjà dans le panier
        $existing = $builder->where([
            'cart_id' => $cartId,
            'product_id' => $productId
        ])->get()->getRowArray();
        
        if ($existing) {
            // Récupère le prix et le stock du produit
            $productModel = new ProductModel();
            $product = $productModel->getProductById($productId);
            if (!$product) {
                return false;
            }

            // Additionne la quantité existante avec la quantité demandée
            $newQuantity = (int)$existing['quantity'] + (int)$quantity;

            // Vérifie le stock disponible par rapport à la quantité cumulée
            if ((int)$product['quantity'] < $newQuantity) {
                return false;
            }

            // Met à jour la quantité cumulée
            return $builder->where([
                'cart_id' => $cartId,
                'product_id' => $productId
            ])->update(['quantity' => $newQuantity]);
        } else {
            // Récupère le prix du produit
            $productModel = new ProductModel();
            $product = $productModel->getProductById($productId);
            
            if (!$product) {
                return false;
            }
            
            // Vérifie le stock pour une première insertion
            if ((int)$product['quantity'] < (int)$quantity) {
                return false;
            }

            // Ajoute une nouvelle ligne
            return $builder->insert([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $product['price']
            ]);
        }
    }

    // Retire un produit
    public function removeItem(int $cartId, int $productId): bool
    {
        $db = \Config\Database::connect();
        return $db->table('cart_items')->where([
            'cart_id' => $cartId,
            'product_id' => $productId
        ])->delete();
    }

    // Change la quantité
    public function updateQuantity(int $cartId, int $productId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->removeItem($cartId, $productId);
        }
        
        $db = \Config\Database::connect();
        return $db->table('cart_items')->where([
            'cart_id' => $cartId,
            'product_id' => $productId
        ])->update(['quantity' => $quantity]);
    }

    // Récupère tous les articles
    public function getCartItems(int $cartId): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('cart_items');
        
        return $builder
            ->select('cart_items.*, products.name, products.desc, products.img_src, products.price')
            ->join('products', 'products.id = cart_items.product_id')
            ->where('cart_items.cart_id', $cartId)
            ->get()
            ->getResultArray();
    }

    // Récupère un article spécifique du panier
    public function getItem(int $cartId, int $productId): ?array
    {
        $db = \Config\Database::connect();
        $row = $db->table('cart_items')->where([
            'cart_id' => $cartId,
            'product_id' => $productId
        ])->get()->getRowArray();

        return $row ?: null;
    }

    // Calcule le total
    public function getTotal(int $cartId): float
    {
        $items = $this->getCartItems($cartId);
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item['unit_price'] * $item['quantity'];
        }
        
        return $total;
    }

    // Vide le panier
    public function clearCart(int $cartId): bool
    {
        $db = \Config\Database::connect();
        return $db->table('cart_items')->where('cart_id', $cartId)->delete();
    }

    // Compte les articles
    public function getItemCount(int $cartId): int
    {
        $db = \Config\Database::connect();
        $result = $db->table('cart_items')
            ->selectSum('quantity')
            ->where('cart_id', $cartId)
            ->get()
            ->getRowArray();
        
        return (int)($result['quantity'] ?? 0);
    }
}
