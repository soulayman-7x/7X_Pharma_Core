<?php
class Notification extends Model {
    protected $table = 'batches';

    public function getLowStockAlerts() {
        $sql = "SELECT b.id AS batch_id, b.batch_number, b.current_quantity, m.name AS medicine_name
                FROM batches b
                JOIN medicines m ON b.medicine_id = m.id
                WHERE b.current_quantity <= 10 AND b.current_quantity > 0
                AND m.deleted_at IS NULL
                ORDER BY b.current_quantity ASC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExpiringAlerts() {
        $sql = "SELECT b.id AS batch_id, b.batch_number, b.expiry_date, b.current_quantity, m.name AS medicine_name
                FROM batches b
                JOIN medicines m ON b.medicine_id = m.id
                WHERE b.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 60 DAY) 
                AND b.current_quantity > 0
                AND m.deleted_at IS NULL
                ORDER BY b.expiry_date ASC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
