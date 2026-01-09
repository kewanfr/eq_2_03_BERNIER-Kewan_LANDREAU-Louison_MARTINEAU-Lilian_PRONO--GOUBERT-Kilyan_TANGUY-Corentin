<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Utilisation du Design Pattern Repository
 */

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'desc', 'img_src', 'price', 'quantity', 'category', 'tags'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    /**
     * Constructeur
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    /**
     * Retourne tous les produits de la table
     * 
     * @return array All products
     */
    public function getAllProducts()
    {
        return $this->findAll();
    }

    /**
     * Retourne un produit par son nom
     * 
     * @param string $name (le nom du produit que l'on cherche)
     * @return array|null (Retourne le produit si il existe, sinon on renvoie null)
     */
    public function getProductByName(string $name)
    {
        return $this->where('name', $name)->first();
    }

    /**
     * Retourne un produit par son identifiant (id)
     *
     * @param string $name (l'ID que l'on recherche)
     * @return array|null (Retourne le produit si il existe, sinon on renvoie null)
     */
    public function getProductById(int $id)
    {
        return $this->where('id', $id)->first();
    }

    /**
     * Retourne un produit qui contient un mot-clé spécifique dans dans sa description
     * 
     * @param string $keyword (le mot-clé que (l'on cherche dans la description)
     * @return array (le produit qui contient le mot-clé)
     */
    public function getProductsByKeyword(string $keyword)
    {
        return $this->like('desc', $keyword)->findAll();
    }

    // Recherche et filtre les produits
    public function searchAndFilter($search = null, $category = null, $tag = null)
    {
        $builder = $this->builder();
        
        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('desc', $search)
                ->groupEnd();
        }
        
        if ($category) {
            $builder->where('category', $category);
        }
        
        if ($tag) {
            $builder->like('tags', $tag);
        }
        
        return $builder->get()->getResultArray();
    }

    // Récupère toutes les catégories
    public function getCategories()
    {
        return $this->select('category')
            ->distinct()
            ->where('category IS NOT NULL')
            ->findAll();
    }

    // Récupère tous les tags
    public function getAllTags()
    {
        $products = $this->select('tags')->where('tags IS NOT NULL')->findAll();
        $allTags = [];
        
        foreach ($products as $product) {
            if (!empty($product['tags'])) {
                $tags = explode(',', $product['tags']);
                $allTags = array_merge($allTags, $tags);
            }
        }
        
        return array_unique($allTags);
    }

    /**
     * Retourne le lien de l'image d'un produit par son identifiant (ID)
     * 
     * @param int $id (l'identifiant du produit)
     * @return string|null (retoune l'image si elle existe, sinon null)
     */
    public function getImageById(int $id)
    {
        $product = $this->select('img_src')->where('id', $id)->first();
        return $product ? $product['img_src'] : null;
    }

    /**
     * Ajoute un nouveau produit dans la base de donnée
     * 
     * @param string $name (le nom du produit)
     * @param string $desc (la description du produit)
     * @param string $img_src (l'image du produit)
     * @param float $price (le prix du produit)
     * @param int $quantity (la quantité du produit)
     * @return bool (renvoie True si l'assertion réussie, sinon False)
     */
    public function addProduct(string $name, string $desc, string $img_src, float $price, int $quantity)
    {
        $data = [
            'name' => $name,
            'desc' => $desc,
            'img_src' => $img_src,
            'price' => $price,
            'quantity' => $quantity
        ];

        return $this->insert($data);
    }

    /**
     * Supprime un produit par son identifiant (ID)
     * 
     * @param int $id (l'ID du produit à supprimer)
     * @return bool (renvoie True si l'assertion réussie, sinon False)
     */
    public function deleteProduct(int $id)
    {
        return $this->delete($id);
    }

    /**
     * Vérifie si un produit existe par son identifiant (ID)
     * 
     * @param int $id (l'identifiant du produit à vérifier)
     * @return bool (renvoie True si le produit existe, sinon False)
     */
    public function productExists(int $id)
    {
        return $this->where('id', $id)->countAllResults() > 0;
    }

    /**
     * Retourne le nombre de produits dans la base de donnée
     * 
     * @return int Number of products
     */
    public function getProductCount()
    {
        return $this->countAll();
    }
}