<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Enums\StockMovementReason;

class StockMovementModel extends Model
{
    protected $table = 'stock_movements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id', 'quantity_change', 'reason', 'reference_id', 'user_id', 'note', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    /**
     * Journalise un mouvement de stock
     * 
     * @param int $productId ID du produit
     * @param int $change Quantité (le signe sera appliqué automatiquement selon la raison)
     * @param StockMovementReason|string $reason Raison du mouvement
     * @param int|null $userId ID de l'utilisateur
     * @param int|null $referenceId ID de référence (commande)
     * @param string|null $note Note optionnelle
     */
    public function logMovement(
        int $productId, 
        int $change, 
        StockMovementReason|string $reason, 
        ?int $userId = null, 
        ?int $referenceId = null, 
        ?string $note = null
    ): bool {
        // Convertir string en enum si nécessaire
        $reasonEnum = $reason instanceof StockMovementReason 
            ? $reason 
            : StockMovementReason::fromString($reason);

        // Appliquer le signe correct selon le type de mouvement
        $quantityChange = $reasonEnum->applySign($change);

        $data = [
            'product_id' => $productId,
            'quantity_change' => $quantityChange,
            'reason' => $reasonEnum->value,
            'reference_id' => $referenceId,
            'user_id' => $userId,
            'note' => $note ?? $reasonEnum->defaultNote($referenceId),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return (bool) $this->insert($data);
    }

    public function getHistory(int $limit = 200): array
    {
        $builder = $this->builder();
        return $builder
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
