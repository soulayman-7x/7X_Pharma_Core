<?php
class Medicine extends Model {
    protected $table = 'medicines';
    
    // 1. search and filter
    public function searchAndFilter($searchQuery = '', $category  = 'all') {
        // ربط جدول الأدوية بالدفعات لحساب الكمية الإجمالية
        $sql = "SELECT medicines.*, 
                COALESCE(SUM(batches.current_quantity), 0) as current_quantity
                FROM {$this->table}
                LEFT JOIN batches ON medicines.id = batches.medicine_id
                WHERE medicines.deleted_at IS NULL";
        
        $params = [];

        if (!empty($searchQuery)) {
            $sql .= " AND (medicines.name LIKE ? OR medicines.barcode LIKE ?)";
            $params[] = "%{$searchQuery}%";
            $params[] = "%{$searchQuery}%";
        }

        // ida khtar chi 7aja mn 4ir "all" 
        if ($category !== 'all') {
            $sql .= " AND LOWER(medicines.category) = LOWER(?)";
            $params[] = trim($category);
        }

        $sql .= " GROUP BY medicines.id";

        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Deduct Stock function (FIFO)
    public function deductStock($id, $qtyToDeduct) {
        $sql = "SELECT id, current_quantity FROM batches 
                WHERE medicine_id = ? AND current_quantity > 0 
                ORDER BY expiry_date ASC";
                
        $stmt = $this->query($sql, [$id]);
        $availableBatches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $remainingToDeduct = $qtyToDeduct;

        foreach ($availableBatches as $batch) {
            if ($remainingToDeduct <= 0) break; 

            if ($batch['current_quantity'] >= $remainingToDeduct) {
                $updateSql = "UPDATE batches SET current_quantity = current_quantity - ? WHERE id = ?";
                $this->query($updateSql, [$remainingToDeduct, $batch['id']]);
                $remainingToDeduct = 0;
            } else {
                $updateSql = "UPDATE batches SET current_quantity = 0 WHERE id = ?";
                $this->query($updateSql, [$batch['id']]);
                $remainingToDeduct -= $batch['current_quantity'];
            }
        }

        return $remainingToDeduct == 0;
    }

    // جلب دواء واحد مع كميته الإجمالية
    public function getById($id) {
        $sql = "SELECT medicines.*, 
                COALESCE(SUM(batches.current_quantity), 0) as current_quantity
                FROM {$this->table}
                LEFT JOIN batches ON medicines.id = batches.medicine_id
                WHERE medicines.id = ? AND medicines.deleted_at IS NULL
                GROUP BY medicines.id";
                
        $stmt = $this->query($sql, [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>