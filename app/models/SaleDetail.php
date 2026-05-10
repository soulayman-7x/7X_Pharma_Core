<?php 
class SaleDetail extends Model {
    protected $table = 'sale_items';

    /**
        * Retrieve all product details associated with a specific invoice
        * This function is used to display the invoice (receipt) after a sale
    */

    public function getItemsBySaleId($sale_id) {
        $sql = "SELECT 
                    sale_items.*, 
                    medicines.name as current_name,
                    medicines.barcode
                FROM sale_items
                JOIN batches ON sale_items.batch_id = batches.id
                JOIN medicines ON batches.medicine_id = medicines.id
                WHERE sale_items.sale_id = ?";
            
        $stmt = $this->query($sql, [$sale_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Data processing: Converting JSON (snapshot_data) to a PHP array
        foreach ($items as &$item) {
            if (!empty($item['snapshot_data'])) {
                $item['details'] = json_decode($item['snapshot_data'], true);
            }
        }
        return $items;
    }
    // Calculating the total number of items sold in a specific invoice
    public function getTotalItemsInSale($sale_id) {
        $sql = "SELECT SUM(quantity) as total_qty FROM {$this->table} WHERE sale_id = ?";
        $stmt = $this->query($sql, [$sale_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
        return $result['total_qty'] ?? 0;
    }
    

}