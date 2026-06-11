<?php
class InventoryMovement extends Model {
    protected $table = 'inventory_movements';

    public function recordMovement($batch_id, $movement_type, $quantity, $user_id, $reference_id = null) {
        return $this->insert([
            'batch_id' => $batch_id,
            'movement_type' => $movement_type,
            'quantity' => $quantity,
            'user_id' => $user_id,
            'reference_id' => $reference_id
        ]);
    }
}
?>
