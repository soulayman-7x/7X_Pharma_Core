<?php
class Medicine extends Model {
    protected $table = 'medicines';

    public function searchByName($name) {
        $sql = "SELECT * FROM {$this->table} WHERE name LIKE = ?";

        $stmt = $this->query($sql, ["%$name%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mn b3d t9der tziid "SearchByBarcode()"
    // ...
}