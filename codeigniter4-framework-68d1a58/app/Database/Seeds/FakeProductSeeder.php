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
        
        // Récupère tous les tags existants (table tags)
        $existingTags = $this->db->table('tags')->select('name')->get()->getResultArray();
        $allTags = array_column($existingTags, 'name');
        if (empty($allTags)) {
            $allTags = ['bio', 'artisanal', 'premium', 'fruité', 'sec', 'doux'];
        }
        
        $formats = ['25cl', '33cl', '50cl', '75cl', '1L', '1.5L', '3L'];
        
        // Génère et insère 200 faux articles
        for ($i = 1; $i <= 200; $i++) {
            $category = $categories[array_rand($categories)];
            $format = $formats[array_rand($formats)];
            $price = round(mt_rand(200, 5000) / 100, 2); // Prix entre 2.00€ et 50.00€
            $quantity = mt_rand(0, 200);
            $tvaRate = 20.0;
            
            // Génère des tags : fake-article + 0 à 1 tag aléatoire
            $numTags = mt_rand(0, 1);
            $selectedTags = [];
            for ($j = 0; $j < $numTags; $j++) {
                $selectedTags[] = $allTags[array_rand($allTags)];
            }
            $selectedTags = array_unique($selectedTags);
            
            $productData = [
                'name' => "Article Test #{$i}",
                'desc' => "Ceci est un article de test numéro {$i} pour tester le scroll infini et la pagination. Catégorie: {$category}, Format: {$format}.",
                'img_src' => '/assets/img/missing_product.jpg',
                'price' => $price,
                'tva_rate' => $tvaRate,
                'quantity' => $quantity,
                'format' => $format,
                'category' => $category,
                'is_active' => 1
            ];

            // Insère le produit
            $this->db->table('products')->insert($productData);
            $productId = $this->db->insertID();

            // Associer tags: toujours 'fake-article' + éventuels sélectionnés
            $tagNames = array_merge(['fake-article'], $selectedTags);
            foreach ($tagNames as $tagName) {
                $existing = $this->db->table('tags')->where('name', $tagName)->get()->getRowArray();
                if (!$existing) {
                    $this->db->table('tags')->insert(['name' => $tagName]);
                    $tagId = $this->db->insertID();
                } else {
                    $tagId = $existing['id'];
                }
                $existsLink = $this->db->table('product_tags')->where(['product_id' => $productId, 'tag_id' => $tagId])->countAllResults();
                if ($existsLink == 0) {
                    $this->db->table('product_tags')->insert(['product_id' => $productId, 'tag_id' => $tagId]);
                }
            }
        }
        
        echo "200 faux articles créés avec tags normalisés !\n";
    }
}
