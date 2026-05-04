<?php

class Batch extends Model {
    protected $table = 'batches';

    // kayjib products li b9at lihom 90day t9riban bax ikhsro
    public function getExpiringSoon($days = 90) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY) 
                AND quantity > 0 
                ORDER BY expiry_date ASC";
        $stmt = $this->query($sql, [$days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to deduct quantity after sale
    public function decreaseQuantity($batchId, $qtyToDeduct) {
        $sql = "UPDATE {$this->table} SET quantity = quantity - ? WHERE id = ? AND quantity >= ?";
        $stmt = $this->query($sql, [$qtyToDeduct, $batchId, $qtyToDeduct]);
        return $stmt->rowCount() > 0;
    }
}