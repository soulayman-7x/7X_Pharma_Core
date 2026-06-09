<?php
class Client extends Model {
    protected $table = 'clients';
    // 1. function designed to record a payment and reduce the debt
    public function makePayment($clientId, $amount, $userId, $method = 'cash', $note = null) {
        // Guard: prevent paying more than the actual debt
        $client = $this->getById($clientId);
        if (!$client) {
            return false;
        }

        $amount = min($amount, floatval($client['credit_balance']));
        if ($amount <= 0) {
            return false; // Nothing to pay
        }

        $sql1 = "UPDATE {$this->table} SET credit_balance = credit_balance - ? WHERE id = ?";
        $stmt1 = $this->query($sql1, [$amount, $clientId]);

        if ($stmt1->rowCount() > 0) {
            $sql2 = "INSERT INTO client_payments (client_id, amount, payment_method, note, user_id, payment_date) VALUES (?, ?, ?, ?, ?, NOW())";
            $this->query($sql2, [$clientId, $amount, $method, $note, $userId]);
            return true;
        }

        return false;
    }

    // Calculating total outstanding debts
    public function getTotalUnpaidCredit() {
        $sql = "SELECT COALESCE(SUM(credit_balance), 0) as total_unpaid
                FROM {$this->table}";
        $stmt = $this->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total_unpaid'];
    }

    public function addDebt($clientId, $amount) {
        $sql = "UPDATE {$this->table} SET credit_balance = credit_balance + ? WHERE id = ?";
        $stmt = $this->query($sql, [$amount, $clientId]);
        
        return $stmt->rowCount() > 0;
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->query($sql);
        
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return []; 
    }

    // Retrieve customer payment history
    public function getClientTransactions($clientId) {
        $sql = "SELECT * FROM client_payments WHERE client_id = ? ORDER BY payment_date DESC";
        $stmt = $this->query($sql, [$clientId]);
        
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
}