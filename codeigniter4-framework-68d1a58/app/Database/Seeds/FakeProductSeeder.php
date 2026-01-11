<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder pour générer des faux articles de test (scroll infini)
 */
class FakeProductSeeder extends Seeder
{
    public function run()
    {
        // Récupère les catégories existantes depuis la base
        $existingCategories = $this->db->table('products')
            ->select('category')
            ->distinct()
            ->where('category IS NOT NULL')
            ->where('category !=', '')
            ->get()
            ->getResultArray();
        
        $categories = array_column($existingCategories, 'category');
        
        // Fallback si aucune catégorie n'existe
        if (empty($categories)) {
            $categories = ['Cidres', 'Jus', 'Vinaigres', 'Spiritueux', 'Confitures', 'Coffrets'];
        }
        
        // Récupère tous les tags existants
        $existingProducts = $this->db->table('products')
            ->select('tags')
            ->where('tags IS NOT NULL')
            ->where('tags !=', '')
            ->get()
            ->getResultArray();
        
        $allTags = [];
        foreach ($existingProducts as $product) {
            if (!empty($product['tags'])) {
                $tags = explode(',', $product['tags']);
                foreach ($tags as $tag) {
                    $tag = trim($tag);
                    if (!empty($tag) && $tag !== 'fake-article') {
                        $allTags[] = $tag;
                    }
                }
            }
        }
        
        // Déduplique
        $allTags = array_unique($allTags);
        
        // Fallback si aucun tag
        if (empty($allTags)) {
            $allTags = ['bio', 'artisanal', 'premium', 'traditionnel', 'fruité', 'sec', 'doux'];
        }
        
        $formats = ['25cl', '33cl', '50cl', '75cl', '1L', '1.5L', '3L'];
        
        $data = [];
        
        // Génère 200 faux articles
        for ($i = 1; $i <= 200; $i++) {
            $category = $categories[array_rand($categories)];
            $format = $formats[array_rand($formats)];
            $price = round(mt_rand(200, 5000) / 100, 2); // Prix entre 2.00€ et 50.00€
            $quantity = mt_rand(0, 200);
            $tvaRate = 20.0;
            
            // Génère des tags : fake-article + 1 à 3 tags aléatoires
            $numTags = mt_rand(1, 3);
            $selectedTags = [];
            for ($j = 0; $j < $numTags; $j++) {
                $selectedTags[] = $allTags[array_rand($allTags)];
            }
            $selectedTags = array_unique($selectedTags);
            $tags = 'fake-article,' . implode(',', $selectedTags);
            
            $data[] = [
                'name' => "Article Test #{$i}",
                'desc' => "Ceci est un article de test numéro {$i} pour tester le scroll infini et la pagination. Catégorie: {$category}, Format: {$format}.",
                'img_src' => '/assets/img/missing_product.jpg',
                'price' => $price,
                'tva_rate' => $tvaRate,
                'quantity' => $quantity,
                'format' => $format,
                'category' => $category,
                'tags' => $tags,
                'is_active' => 1
            ];
        }
        
        // Insertion en base
        $this->db->table('products')->insertBatch($data);
        
        echo "200 faux articles créés avec succès !\n";
    }
}
