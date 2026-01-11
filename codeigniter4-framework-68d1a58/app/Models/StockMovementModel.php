<?php

namespace App\Models;

use CodeIgniter\Model;

class StockMovementModel extends Model
{
    protected $table = 'stock_movements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id', 'quantity_change', 'reason', 'reference_id', 'user_id', 'note', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function logMovement(int $productId, int $change, string $reason, ?int $userId = null, ?int $referenceId = null, ?string $note = null): bool
    {
        $data = [
            'product_id' => $productId,
            'quantity_change' => $change,
            'reason' => $reason,
            'reference_id' => $referenceId,
            'user_id' => $userId,
            'note' => $note,
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
