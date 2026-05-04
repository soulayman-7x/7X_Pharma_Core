<?php
class Client extends Model {
    // function designed to record a payment and reduce the debt from the customer account
    public function makePayment($clientId, $amount) {
        $sql = 'UPDATE {$this->table} SET balance = balance - ? WHERE id = ?';
        $stmt = $this->query($sql, [$amount, $clientId]);

        return $stmt->rowCount() > 0;
    }
}