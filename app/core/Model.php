<?php

class Model {
    protected $db;
    protected $table;

    public function __construct() {
        require_once ROOT_DIR . '/app/config/Database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }

    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Expose the PDO connection for transaction management in controllers
    public function getDb() {
        return $this->db;
    }

    // 1. Retrieve all data from the table
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Retrieve 1 row by id
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} where id = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Delete row by id
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt->rowCount(); // The number of rows deleted 
    }

    // 4. insert function
    public function insert($data) {
        // Extracting column names (Keys)
        $columns = implode(', ', array_keys($data)); // result => like: name, email, pass

        // create "?"
        $placeholders = implode(', ', array_fill(0 , count($data), '?')); // if count($data) = 3 | result like: ?, ?, ? 
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $this->query($sql, array_values($data));

        // Restore the new id
        return $this->db->lastInsertId();
    }

    // 4. update function
        public function update($id, $data) {
            $setClause = "";
            // Preparing the modification form : name = ?, price = ?
            foreach (array_keys($data) as $key) {
                $setClause .= "{$key} = ?, ";
            }
            $setClause = rtrim($setClause, ', '); // ازالة الفاصلة الزائدة في الاخير  || rtrim = Right trim
            
            $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = ?";

            $values = array_values($data);
            $values[] = $id;

            $stmt = $this->query($sql, $values);
            return $stmt->rowCount();
        }
}