<?php

namespace App\Controllers;

use App\Models\ProductModel;

/**
 * Contrôleur pour la page produits uniquement
 */
class Products extends BaseController
{
    const PRODUCTS_PER_PAGE = 20;

    public function index(): string
    {
        $model = new ProductModel();
        
        // Récupère les paramètres de recherche et filtres
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $tag = $this->request->getGet('tag');
        $minPrice = $this->request->getGet('min_price');
        $maxPrice = $this->request->getGet('max_price');
        
        // Premier chargement: 20 produits
        if ($search || $category || $tag || $minPrice || $maxPrice) {
            $products = $model->searchAndFilter($search, $category, $tag, $minPrice, $maxPrice, self::PRODUCTS_PER_PAGE, 0);
            $total = $model->countFiltered($search, $category, $tag, $minPrice, $maxPrice);
        } else {
            $products = $model->getAllActiveProducts(self::PRODUCTS_PER_PAGE, 0);
            $total = $model->countActiveProducts();
        }
        
        $data = [
            "products" => $products,
            "categories" => $model->getCategories(),
            "tags" => $model->getAllTags(),
            "currentSearch" => $search,
            "currentCategory" => $category,
            "currentTag" => $tag,
            "currentMinPrice" => $minPrice,
            "currentMaxPrice" => $maxPrice,
            "totalProducts" => $total,
            "perPage" => self::PRODUCTS_PER_PAGE
        ];

        return view('products_page', $data);
    }

    /**
     * API JSON pour le scroll infini
     */
    public function loadMore()
    {
        $model = new ProductModel();
        
        $offset = (int) $this->request->getGet('offset');
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $tag = $this->request->getGet('tag');
        $minPrice = $this->request->getGet('min_price');
        $maxPrice = $this->request->getGet('max_price');
        
        if ($search || $category || $tag || $minPrice || $maxPrice) {
            $products = $model->searchAndFilter($search, $category, $tag, $minPrice, $maxPrice, self::PRODUCTS_PER_PAGE, $offset);
            $total = $model->countFiltered($search, $category, $tag, $minPrice, $maxPrice);
        } else {
            $products = $model->getAllActiveProducts(self::PRODUCTS_PER_PAGE, $offset);
            $total = $model->countActiveProducts();
        }
        
        // Génère le HTML pour chaque produit
        $html = '';
        foreach ($products as $product) {
            $html .= view('products', $product);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'html' => $html,
            'count' => count($products),
            'total' => $total,
            'hasMore' => ($offset + count($products)) < $total
        ]);
    }

    public function detail($id): string
    {
        $model = new ProductModel();
        $product = $model->find($id);
        
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $data = [
            'product' => $product
        ];
        
        return view('product_detail', $data);
    }
}
