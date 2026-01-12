<?php

namespace App\Controllers;

use App\Models\ProductModel;

/**
 * Controlleur pour afficher la page d'accueil
 */
class Home extends BaseController
{
    public function index(): string
    {
        $model = new ProductModel();
        
        // Récupère les paramètres de recherche et filtres
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $tag = $this->request->getGet('tag');
        
        // Filtre les produits
        if ($search || $category || $tag) {
            $products = $model->searchAndFilter($search, $category, $tag, null, null, 10);
        } else {
            // Page d'accueil: afficher seulement 10 produits actifs
            $products = $model->getAllActiveProducts(10);
        }
        
        $data = [
            "products" => $products,
            "categories" => $model->getCategories(),
            "tags" => $model->getAllTags(),
            "currentSearch" => $search,
            "currentCategory" => $category,
            "currentTag" => $tag
        ];

        return view('layout/main', $data);
    }
}
