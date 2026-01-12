<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $products = $db->table('products')->select('id')->get()->getResultArray();
        
        if (empty($products)) {
            echo "Aucun produit trouvé. Lancez d'abord ProductSeeder.\n";
            return;
        }

        $data = [];
        $reasons = ['ORDER', 'CANCELLATION', 'MANUAL'];
        $notes = [
            'ORDER' => [
                'Commande client effectuée',
                'Vente directe',
                'Déstockage suite à commande',
            ],
            'CANCELLATION' => [
                'Annulation de commande',
                'Retour client',
                'Remboursement suite à défaut',
            ],
            'MANUAL' => [
                'Inventaire corrigé',
                'Produit endommagé',
                'Perte lors du transport',
                'Réapprovisionnnement',
                'Ajustement de stock',
            ],
        ];

        // Génère des mouvements pour les 7 derniers jours
        $baseDate = new \DateTime('now', new \DateTimeZone('UTC'));
        $baseDate->modify('-7 days');

        foreach ($products as $product) {
            $productId = $product['id'];
            
            // Génère 0-1 mouvement aléatoire par produit
            $movementCount = mt_rand(0, 1);
            
            for ($i = 0; $i < $movementCount; $i++) {
                $daysAgo = mt_rand(0, 7);
                $movementDate = clone $baseDate;
                $movementDate->modify("+$daysAgo days");
                $movementDate->modify('+' . mt_rand(0, 1439) . ' minutes');
                
                $reason = $reasons[array_rand($reasons)];
                $quantity = match($reason) {
                    'ORDER' => -mt_rand(1, 5),
                    'CANCELLATION' => mt_rand(1, 3),
                    'MANUAL' => mt_rand(-3, 5),
                };
                
                $note = $notes[$reason][array_rand($notes[$reason])];
                
                $data[] = [
                    'product_id' => $productId,
                    'quantity_change' => $quantity,
                    'reason' => $reason,
                    'reference_id' => null,
                    'user_id' => mt_rand(1, 3),
                    'note' => $note,
                    'created_at' => $movementDate->format('Y-m-d H:i:s'),
                ];
            }
        }

        // Insère par batch de 100 pour éviter les requêtes trop longues
        foreach (array_chunk($data, 100) as $batch) {
            $this->db->table('stock_movements')->insertBatch($batch);
        }

        echo count($data) . " mouvements de stock créés.\n";
    }
}
