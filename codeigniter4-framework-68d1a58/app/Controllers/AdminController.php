<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\UserRoleModel;
use App\Models\Users;
use App\Enums\RoleInterne;
use CodeIgniter\Controller;

/**
 * back office (administration)
 * Accessible uniquement aux utilisateurs avec rôles internes
 */
class AdminController extends Controller
{
    protected $productModel;
    protected $orderModel;
    protected $userRoleModel;
    protected $userModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
        $this->userRoleModel = new UserRoleModel();
        $this->userModel = new Users();
    }

    /**
     * Vérifie les permissions
     */
    private function checkPermission(string $permission): bool
    {
        if (!auth()->loggedIn()) {
            return false;
        }

        $userId = auth()->id();
        $userRoles = $this->userRoleModel->getUserRoles($userId);
        
        // Stocke les rôles en session pour les vues
        session()->set('user_roles', $userRoles);
        
        // Vérifie si AU MOINS UN des rôles a la permission
        foreach ($userRoles as $role) {
            if (RoleInterne::hasPermission($role, $permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Redirige si pas de permission
     */
    private function requirePermission(string $permission)
    {
        if (!$this->checkPermission($permission)) {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }
        return null;
    }

    /**
     * Dashboard principal du back office
     */
    public function index()
    {
        if ($redirect = $this->requirePermission('access_admin')) {
            return $redirect;
        }

        // Stocke les rôles de l'utilisateur en session pour les vues
        $userId = auth()->id();
        $userRoles = $this->userRoleModel->getUserRoles($userId);
        session()->set('user_roles', $userRoles);

        $data = [
            'statistics' => $this->orderModel->getStatistics(),
            'recentOrders' => $this->orderModel->getAllOrders(),
            'userRole' => $this->userRoleModel->getPrimaryRole($userId)
        ];

        return view('admin/dashboard', $data);
    }

    // ========== GESTION DES PRODUITS ==========

    /**
     * Liste tous les produits
     */
    public function products()
    {
        if ($redirect = $this->requirePermission('manage_products')) {
            return $redirect;
        }

        $data = [
            'products' => $this->productModel->getAllProducts()
        ];

        return view('admin/products/index', $data);
    }

    /**
     * Formulaire d'ajout de produit
     */
    public function createProduct()
    {
        if ($redirect = $this->requirePermission('manage_products')) {
            return $redirect;
        }

        return view('admin/products/create');
    }

    /**
     * Formulaire d'édition de produit
     */
    public function editProduct($id)
    {
        if ($redirect = $this->requirePermission('manage_products')) {
            return $redirect;
        }

        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produit introuvable');
        }

        $data = ['product' => $product];
        return view('admin/products/edit', $data);
    }

    /**
     * Met à jour un produit
     */
    public function updateProduct($id)
    {
        if ($redirect = $this->requirePermission('manage_products')) {
            return $redirect;
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'desc' => $this->request->getPost('desc'),
            'category' => $this->request->getPost('category'),
            'tags' => $this->request->getPost('tags'),
            'price' => $this->request->getPost('price'),
            'quantity' => $this->request->getPost('quantity')
        ];

        // Gestion de l'upload d'image
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Validation du fichier
            $validationRule = [
                'image' => [
                    'uploaded[image]',
                    'mime_in[image,image/jpg,image/jpeg,image/png,image/gif]',
                    'max_size[image,2048]', // 2MB max
                ],
            ];

            if ($this->validate($validationRule)) {
                // Génère un nom unique pour l'image
                $newName = $image->getRandomName();
                
                // Déplace l'image vers le dossier uploads/products
                $uploadPath = FCPATH . 'uploads/products/';
                
                // Crée le dossier s'il n'existe pas
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                if ($image->move($uploadPath, $newName)) {
                    // Supprime l'ancienne image si elle existe et n'est pas l'image par défaut
                    $oldProduct = $this->productModel->getProductById($id);
                    if (!empty($oldProduct['img_src']) && 
                        strpos($oldProduct['img_src'], '/uploads/products/') !== false) {
                        $oldImagePath = FCPATH . ltrim($oldProduct['img_src'], '/');
                        if (file_exists($oldImagePath)) {
                            @unlink($oldImagePath);
                        }
                    }
                    
                    // Ajoute le chemin de la nouvelle image aux données
                    $data['img_src'] = '/uploads/products/' . $newName;
                }
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Erreur lors de l\'upload de l\'image : ' . implode(', ', $this->validator->getErrors()));
            }
        }

        if ($this->productModel->update($id, $data)) {
            return redirect()->to('/admin/products')->with('success', 'Produit mis à jour');
        }

        return redirect()->back()->with('error', 'Erreur lors de la mise à jour');
    }

    /**
     * Supprime un produit
     */
    public function deleteProduct($id)
    {
        if ($redirect = $this->requirePermission('manage_products')) {
            return $redirect;
        }

        if ($this->productModel->delete($id)) {
            return redirect()->to('/admin/products')->with('success', 'Produit supprimé');
        }

        return redirect()->back()->with('error', 'Erreur lors de la suppression');
    }

    // ========== GESTION DES COMMANDES ==========

    /**
     * Liste toutes les commandes
     */
    public function orders()
    {
        if ($redirect = $this->requirePermission('view_orders')) {
            return $redirect;
        }

        // Stocke les rôles en session
        $userId = auth()->id();
        $userRoles = $this->userRoleModel->getUserRoles($userId);
        session()->set('user_roles', $userRoles);

        $status = $this->request->getGet('status');
        
        $data = [
            'orders' => $this->orderModel->getAllOrders($status),
            'statuses' => [
                OrderModel::STATUS_PAYEE,
                OrderModel::STATUS_EN_PREPARATION,
                OrderModel::STATUS_PRETE,
                OrderModel::STATUS_EXPEDIEE,
                OrderModel::STATUS_LIVREE,
                OrderModel::STATUS_ANNULEE
            ]
        ];

        return view('admin/orders/index', $data);
    }

    /**
     * Détails d'une commande
     */
    public function orderDetails($id)
    {
        if ($redirect = $this->requirePermission('view_orders')) {
            return $redirect;
        }

        // Stocke les rôles en session
        $userId = auth()->id();
        $userRoles = $this->userRoleModel->getUserRoles($userId);
        session()->set('user_roles', $userRoles);

        $order = $this->orderModel->getOrderDetails($id);
        
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Commande introuvable');
        }

        $data = ['order' => $order];
        return view('admin/orders/details', $data);
    }

    /**
     * Change le statut d'une commande
     */
    public function updateOrderStatus($id)
    {
        if ($redirect = $this->requirePermission('update_order_status')) {
            return $redirect;
        }

        $newStatus = $this->request->getPost('status');

        if ($this->orderModel->changeStatus($id, $newStatus)) {
            return redirect()->to('/admin/orders/' . $id)->with('success', 'Statut mis à jour');
        }

        return redirect()->back()->with('error', 'Erreur lors de la mise à jour du statut');
    }

    /**
     * Annule une commande
     */
    public function cancelOrder($id)
    {
        if ($redirect = $this->requirePermission('manage_orders')) {
            return $redirect;
        }

        // Accepte GET et POST
        if ($this->request->getMethod() === 'post' || $this->request->getGet('confirm') === '1') {
            if ($this->orderModel->cancelOrder($id)) {
                return redirect()->to('/admin/orders')->with('success', 'Commande annulée');
            }
            return redirect()->back()->with('error', 'Impossible d\'annuler cette commande');
        }
        
        // Si GET sans confirmation, redirige vers détails
        return redirect()->to('/admin/orders/' . $id);
    }

    // ========== GESTION DES UTILISATEURS ==========

    /**
     * Liste tous les utilisateurs
     */
    public function users()
    {
        if ($redirect = $this->requirePermission('manage_users')) {
            return $redirect;
        }

        $usersObjects = $this->userModel->findAll();
        $users = [];
        
        // Convertit les objets User en tableaux et ajoute les rôles
        foreach ($usersObjects as $userObj) {
            $userData = [
                'id' => $userObj->id,
                'username' => $userObj->username,
                'active' => $userObj->active,
                'created_at' => $userObj->created_at
            ];
            $userData['roles'] = $this->userRoleModel->getUserRoles($userObj->id);
            $users[] = $userData;
        }

        $data = ['users' => $users];
        return view('admin/users/index', $data);
    }

    /**
     * Édite les rôles d'un utilisateur
     */
    public function editUserRoles($id)
    {
        if ($redirect = $this->requirePermission('manage_users')) {
            return $redirect;
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Utilisateur introuvable');
        }

        $userRoles = $this->userRoleModel->getUserRoles($id);

        $data = [
            'user' => $user,
            'userRoles' => $userRoles
        ];

        return view('admin/users/edit', $data);
    }

    /**
     * Met à jour les rôles d'un utilisateur
     */
    public function updateUserRoles($id)
    {
        if ($redirect = $this->requirePermission('manage_users')) {
            return $redirect;
        }

        $roles = $this->request->getPost('roles') ?? [];

        if ($this->userRoleModel->setUserRoles($id, $roles)) {
            return redirect()->to('/admin/users')->with('success', 'Rôles mis à jour');
        }

        return redirect()->back()->with('error', 'Erreur lors de la mise à jour des rôles');
    }

    // ========== GESTION DES STOCKS ==========

    /**
     * Gestion des stocks
     */
    public function stock()
    {
        if ($redirect = $this->requirePermission('manage_stock')) {
            return $redirect;
        }

        $products = $this->productModel->getAllProducts();
        
        // Marque les produits avec stock faible
        foreach ($products as &$product) {
            $product['low_stock'] = $product['quantity'] < 10;
        }

        $data = ['products' => $products];
        return view('admin/stock/index', $data);
    }

    /**
     * Ajuste le stock d'un produit
     */
    public function adjustStock($id)
    {
        if ($redirect = $this->requirePermission('manage_stock')) {
            return $redirect;
        }

        $adjustment = $this->request->getPost('adjustment');
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Produit introuvable');
        }

        $newQuantity = $product['quantity'] + $adjustment;

        if ($newQuantity < 0) {
            return redirect()->back()->with('error', 'Stock ne peut pas être négatif');
        }

        if ($this->productModel->update($id, ['quantity' => $newQuantity])) {
            return redirect()->to('/admin/stock')->with('success', 'Stock ajusté');
        }

        return redirect()->back()->with('error', 'Erreur lors de l\'ajustement du stock');
    }
}
