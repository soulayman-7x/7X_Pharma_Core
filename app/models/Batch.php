<?php

class Batch extends Model {
    protected $table = 'batches';

    // Bring all the payments for a specific drug
    public function getBatchesByMedicineId($medicine_id) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE medicine_id = ? 
                ORDER BY expiry_date ASC";
        $stmt = $this->query($sql, [$medicine_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Adding a new batch (new stock of an existing drug)
    public function addStock($medicine_id, $batch_number, $expiry_date, $quantity) {
        return $this->insert([
            'medicine_id' => $medicine_id,
            'batch_number' => $batch_number,
            'expiry_date' => $expiry_date,
            'current_quantity' => $quantity,
            'initial_quantity' => $quantity
        ]);
    }

}