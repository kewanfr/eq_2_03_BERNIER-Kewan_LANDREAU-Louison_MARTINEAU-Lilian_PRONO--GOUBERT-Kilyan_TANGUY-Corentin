<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\UserRoleModel;
use App\Models\Users;
use App\Enums\RoleInterne;
use App\Enums\OrderStatus;
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
            $roleEnum = RoleInterne::tryFrom($role);
            if ($roleEnum && $roleEnum->hasPermission($permission)) {
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
                    'mime_in[image,image/jpg,image/jpeg,image/png,image/gif,image/webp]',
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

    /**
     * Active/Désactive un produit
     */
    public function toggleProduct($id)
    {
        if ($redirect = $this->requirePermission('manage_products')) {
            return $redirect;
        }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produit introuvable');
        }

        $new = (int) (empty($product['is_active']) ? 1 : 0);
        if ($this->productModel->update($id, ['is_active' => $new])) {
            return redirect()->to('/admin/products')->with('success', $new ? 'Produit activé' : 'Produit désactivé');
        }

        return redirect()->to('/admin/products')->with('error', 'Impossible de changer le statut');
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
            'statuses' => OrderStatus::cases()
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

        // Vérifier si c'est une demande d'annulation
        $action = $this->request->getPost('action');
        if ($action === 'cancel') {
            log_message('info', 'Action CANCEL détectée pour commande #' . $id);
            
            // Vérifier permission manage_orders pour annulation
            if (!$this->checkPermission('manage_orders')) {
                return redirect()->back()->with('error', 'Accès non autorisé');
            }
            
            if ($this->orderModel->cancelOrder($id)) {
                return redirect()->to('/admin/orders/' . $id)->with('success', 'Commande annulée avec succès');
            }
            return redirect()->back()->with('error', 'Impossible d\'annuler cette commande');
        }

        // Sinon, mise à jour normale du statut
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
        log_message('info', '=== DEBUT cancelOrder() - ID: ' . $id . ' - Method: ' . $this->request->getMethod() . ' ===');
        
        if ($redirect = $this->requirePermission('manage_orders')) {
            log_message('warning', 'Permission denied for user ' . auth()->id());
            return $redirect;
        }

        // Vérifier uniquement POST
        if ($this->request->getMethod() === 'post') {
            log_message('info', 'Tentative d\'annulation de la commande #' . $id);
            
            if ($this->orderModel->cancelOrder($id)) {
                log_message('info', 'Commande #' . $id . ' annulée avec succès');
                return redirect()->to(base_url('admin/orders'))->with('success', 'Commande annulée avec succès');
            }
            
            log_message('error', 'Échec de l\'annulation de la commande #' . $id);
            return redirect()->back()->with('error', 'Impossible d\'annuler cette commande');
        }
        
        log_message('info', 'Request was not POST, redirecting to details');
        // Si pas POST, redirige vers détails
        return redirect()->to(base_url('admin/orders/' . $id));
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
                'created_at' => $userObj->created_at,
                'customer_type' => $userObj->customer_type ?? 'particulier',
                'company_name' => $userObj->company_name ?? null,
                'phone' => $userObj->phone ?? null,
                'address' => $userObj->address ?? null,
                'siret' => $userObj->siret ?? null,
                'tva_number' => $userObj->tva_number ?? null
            ];
            
            // Récupère l'email depuis auth_identities
            $db = \Config\Database::connect();
            $identity = $db->table('auth_identities')
                ->where('user_id', $userObj->id)
                ->where('type', 'email_password')
                ->get()
                ->getRow();
            $userData['email'] = $identity->secret ?? null;
            
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
        $phone = $this->request->getPost('phone');
        $address = $this->request->getPost('address');

        // Met à jour les rôles
        $rolesUpdated = $this->userRoleModel->setUserRoles($id, $roles);
        
        // Met à jour l'adresse et le téléphone
        $db = \Config\Database::connect();
        $userDataUpdated = $db->table('users')->where('id', $id)->update([
            'phone' => $phone,
            'address' => $address
        ]);

        if ($rolesUpdated && $userDataUpdated !== false) {
            return redirect()->to('/admin/users')->with('success', 'Informations utilisateur mises à jour');
        }

        return redirect()->back()->with('error', 'Erreur lors de la mise à jour');
    }

    /**
     * Formulaire de création d'utilisateur
     */
    public function createUser()
    {
        if ($redirect = $this->requirePermission('manage_users')) {
            return $redirect;
        }

        return view('admin/users/create');
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function storeUser()
    {
        if ($redirect = $this->requirePermission('manage_users')) {
            return $redirect;
        }

        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $customerType = $this->request->getPost('customer_type') ?? 'particulier';
        $phone = $this->request->getPost('phone');
        $address = $this->request->getPost('address');
        $roles = $this->request->getPost('roles') ?? [];

        // Validation basique
        if (empty($username) || empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Nom d\'utilisateur, email et mot de passe requis');
        }

        // Vérifie si l'email existe déjà
        $db = \Config\Database::connect();
        $existingIdentity = $db->table('auth_identities')
            ->where('secret', $email)
            ->where('type', 'email_password')
            ->get()
            ->getRow();

        if ($existingIdentity) {
            return redirect()->back()->with('error', 'Cet email est déjà utilisé');
        }

        // Crée l'utilisateur avec Shield
        $users = auth()->getProvider();
        
        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ]);

        $users->save($user);
        $userId = $users->getInsertID();

        // Met à jour les informations supplémentaires
        $updateData = [
            'customer_type' => $customerType,
            'phone' => $phone,
            'address' => $address
        ];

        if ($customerType === 'professionnel') {
            $updateData['company_name'] = $this->request->getPost('company_name');
            $updateData['siret'] = $this->request->getPost('siret');
            $updateData['tva_number'] = $this->request->getPost('tva_number');
        }

        $db->table('users')->where('id', $userId)->update($updateData);

        // Ajoute les rôles
        if (!empty($roles)) {
            $this->userRoleModel->setUserRoles($userId, $roles);
        }

        return redirect()->to('/admin/users')->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Supprime un utilisateur
     */
    public function deleteUser($id)
    {
        if ($redirect = $this->requirePermission('manage_users')) {
            return $redirect;
        }

        // Empêche la suppression de son propre compte
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        $db = \Config\Database::connect();
        
        // Supprime les rôles
        $db->table('user_roles')->where('user_id', $id)->delete();
        
        // Supprime les identités (email/password)
        $db->table('auth_identities')->where('user_id', $id)->delete();
        
        // Supprime l'utilisateur
        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'Utilisateur supprimé');
        }

        return redirect()->back()->with('error', 'Erreur lors de la suppression');
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
        $note = trim((string)($this->request->getPost('note') ?? ''));
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Produit introuvable');
        }

        $newQuantity = $product['quantity'] + $adjustment;

        if ($newQuantity < 0) {
            return redirect()->back()->with('error', 'Stock ne peut pas être négatif');
        }

        if ($this->productModel->update($id, ['quantity' => $newQuantity])) {
            // Journalise le mouvement de stock (ajustement manuel)
            $movementModel = new \App\Models\StockMovementModel();
            $movementModel->logMovement((int)$id, (int)$adjustment, 'MANUAL', auth()->id(), null, $note ?: 'Ajustement manuel');

            return redirect()->to('/admin/stock')->with('success', 'Stock ajusté');
        }

        return redirect()->back()->with('error', 'Erreur lors de l\'ajustement du stock');
    }

    /**
     * Historique des mouvements de stock
     */
    public function stockHistory()
    {
        if ($redirect = $this->requirePermission('manage_stock')) {
            return $redirect;
        }

        $movementModel = new \App\Models\StockMovementModel();
        $db = \Config\Database::connect();

        // Jointure pour enrichir l'historique
        $rows = $db->table('stock_movements')
            ->select('stock_movements.*, products.name as product_name, users.username as actor_username')
            ->join('products', 'products.id = stock_movements.product_id', 'left')
            ->join('users', 'users.id = stock_movements.user_id', 'left')
            ->orderBy('stock_movements.created_at', 'DESC')
            ->limit(500)
            ->get()
            ->getResultArray();

        $data = ['movements' => $rows];
        return view('admin/stock/history', $data);
    }
}
