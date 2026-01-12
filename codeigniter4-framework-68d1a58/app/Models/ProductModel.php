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
    protected $allowedFields = ['name', 'desc', 'img_src', 'price', 'tva_rate', 'quantity', 'format', 'category', 'is_active'];
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

    // Produits actifs (catalogue public) avec pagination optionnelle
    public function getAllActiveProducts(?int $limit = null, int $offset = 0)
    {
        $builder = $this->builder();
        $builder->select('products.*, GROUP_CONCAT(tags.name) AS tags')
            ->join('product_tags', 'product_tags.product_id = products.id', 'left')
            ->join('tags', 'tags.id = product_tags.tag_id', 'left')
            ->where('is_active', 1)
            ->groupBy('products.id');

        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }
        return $builder->get()->getResultArray();
    }

    // Compte le nombre de produits actifs
    public function countActiveProducts(): int
    {
        return $this->where('is_active', 1)->countAllResults();
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

    // Recherche et filtre les produits avec pagination
    public function searchAndFilter($search = null, $category = null, $tag = null, $minPrice = null, $maxPrice = null, ?int $limit = null, int $offset = 0)
    {
        $builder = $this->builder();
        $builder->select('products.*, GROUP_CONCAT(tags.name) AS tags')
            ->join('product_tags', 'product_tags.product_id = products.id', 'left')
            ->join('tags', 'tags.id = product_tags.tag_id', 'left');

        if ($search) {
            $builder->groupStart()
                ->like('products.name', $search)
                ->orLike('products.desc', $search)
                ->groupEnd();
        }

        if ($category) {
            $builder->where('products.category', $category);
        }

        if ($tag) {
            $builder->where('tags.name', $tag);
        }

        if ($minPrice !== null && $minPrice !== '') {
            $builder->where('products.price >=', (float)$minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $builder->where('products.price <=', (float)$maxPrice);
        }

        $builder->where('products.is_active', 1)
                ->groupBy('products.id');

        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    // Compte les résultats filtrés (pour pagination)
    public function countFiltered($search = null, $category = null, $tag = null, $minPrice = null, $maxPrice = null): int
    {
        $builder = $this->builder();
        $builder->select('products.id')
            ->join('product_tags', 'product_tags.product_id = products.id', 'left')
            ->join('tags', 'tags.id = product_tags.tag_id', 'left');

        if ($search) {
            $builder->groupStart()
                ->like('products.name', $search)
                ->orLike('products.desc', $search)
                ->groupEnd();
        }

        if ($category) {
            $builder->where('products.category', $category);
        }

        if ($tag) {
            $builder->where('tags.name', $tag);
        }

        if ($minPrice !== null && $minPrice !== '') {
            $builder->where('products.price >=', (float)$minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $builder->where('products.price <=', (float)$maxPrice);
        }

        $builder->where('products.is_active', 1)
                ->groupBy('products.id');

        return $builder->get()->getNumRows();
    }

    // Récupère toutes les catégories
    public function getCategories()
    {
        return $this->select('category')
            ->distinct()
            ->where('category IS NOT NULL AND category != ""')
            ->findAll();
    }

    // Récupère tous les tags avec plus de 2 articles
    public function getAllTags()
    {
        $builder = $this->db->table('tags');
        $builder->select('tags.name, COUNT(product_tags.tag_id) AS cnt')
            ->join('product_tags', 'product_tags.tag_id = tags.id', 'inner')
            ->groupBy('tags.name')
            ->having('cnt >=', 2)
            ->orderBy('tags.name', 'ASC');
        $rows = $builder->get()->getResultArray();
        return array_column($rows, 'name');
    }
    public function getProductWithTagsById(int $id)
    {
        $builder = $this->builder();
        $builder->select('products.*, GROUP_CONCAT(tags.name) AS tags')
            ->join('product_tags', 'product_tags.product_id = products.id', 'left')
            ->join('tags', 'tags.id = product_tags.tag_id', 'left')
            ->where('products.id', $id)
            ->groupBy('products.id');
        return $builder->get()->getRowArray();
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