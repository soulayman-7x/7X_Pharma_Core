<?php
class Sale extends Model {
    protected $table = 'sales';
    
    // 1. Today Revenue
    public function getTodayRevenue() {
        $sql ="SELECT COALESCE(SUM(total_amount), 0) as daily_revenue 
                FROM {$this->table} 
                WHERE DATE(created_at) = CURDATE() AND deleted_at IS NULL";
        $stmt = $this->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['daily_revenue'];
    }

    // 2. Today Transactions
    public function getTodayTransactionsCount()  {
        // How many invoices were created today
        $sql = "SELECT COUNT(id) as trans_count
                FROM {$this->table} 
                WHERE DATE(created_at) = CURDATE() AND deleted_at IS NULL";
        $stmt = $this->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['trans_count'];
    }

    // 3. Recent Sales
    public function getRecentSales($limit = 5) {

        $limit = (int) $limit;
        // CONCAT('RX-', LPAD(sales.id, 6, '0')) = RX-000014
        $sql = "SELECT 
                    sales.id,
                    CONCAT('RX-', LPAD(sales.id, 6, '0')) as receipt_number,  
                    sales.created_at,
                    COALESCE(clients.name, 'Walk-in Customer') as client_name,
                    (SELECT COALESCE(SUM(quantity), 0) FROM sale_items WHERE sale_id = sales.id) as items_count,
                    sales.payment_method,
                    sales.total_amount,
                    CASE 
                        WHEN sales.payment_method = 'credit' THEN 'Pending'
                        ELSE 'Paid'
                    END as status
                FROM sales
                LEFT JOIN clients ON sales.client_id = clients.id
                WHERE sales.deleted_at IS NULL
                ORDER BY sales.created_at DESC
                LIMIT {$limit}";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>