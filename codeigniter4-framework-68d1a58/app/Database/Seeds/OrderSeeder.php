<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Récupérer les IDs des utilisateurs clients
        $users = $db->table('users')->get()->getResultArray();
        $userIds = array_column($users, 'id');
        
        // Récupérer les IDs des produits
        $products = $db->table('products')->get()->getResultArray();
        $productIds = array_column($products, 'id');
        
        if (empty($userIds) || empty($productIds)) {
            echo "Erreur: Aucun utilisateur ou produit trouvé. Exécutez d'abord UserSeeder et ProductSeeder.\n";
            return;
        }
        
        $statuses = ['PAYEE', 'EN_PREPARATION', 'PRETE', 'EXPEDIEE', 'LIVREE', 'ANNULEE'];
        $orders = [];
        
        // Générer 50 commandes
        for ($i = 0; $i < 50; $i++) {
            // Date aléatoire dans les 60 derniers jours
            $daysAgo = rand(0, 60);
            $orderDate = date('Y-m-d H:i:s', strtotime("-$daysAgo days"));
            
            // Utilisateur aléatoire
            $userId = $userIds[array_rand($userIds)];
            
            // Statut aléatoire (plus de commandes en cours que terminées)
            $statusWeights = [
                'PAYEE' => 15,
                'EN_PREPARATION' => 20,
                'PRETE' => 15,
                'EXPEDIEE' => 20,
                'LIVREE' => 25,
                'ANNULEE' => 5
            ];
            $status = $this->getWeightedRandom($statusWeights);
            
            // Calculer le total
            $numItems = rand(1, 5); // 1 à 5 produits par commande
            $totalHT = 0;
            $orderItems = [];
            
            for ($j = 0; $j < $numItems; $j++) {
                $product = $products[array_rand($products)];
                $quantity = rand(1, 3);
                $priceUnit = $product['price'];
                
                $orderItems[] = [
                    'product_id' => $product['id'],
                    'quantity' => $quantity,
                    'unit_price' => $priceUnit
                ];
                
                $totalHT += $priceUnit * $quantity;
            }
            
            $totalTTC = $totalHT * 1.20; // TVA 20%
            
            $orders[] = [
                'user_id' => $userId,
                'order_date' => $orderDate,
                'status' => $status,
                'total_ht' => $totalHT,
                'total_ttc' => $totalTTC,
                'items' => $orderItems
            ];
        }
        
        // Insérer les commandes
        foreach ($orders as $order) {
            $items = $order['items'];
            unset($order['items']);
            
            // Insérer la commande
            $db->table('orders')->insert($order);
            $orderId = $db->insertID();
            
            // Insérer les articles de la commande
            foreach ($items as $item) {
                $item['order_id'] = $orderId;
                $db->table('order_items')->insert($item);
            }
        }
        
        echo "50 commandes créées avec succès!\n";
    }
    
    /**
     * Sélectionne un élément aléatoire selon des poids
     */
    private function getWeightedRandom(array $weights): string
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($weights as $value => $weight) {
            $currentWeight += $weight;
            if ($random <= $currentWeight) {
                return $value;
            }
        }
        
        return array_key_first($weights);
    }
}
