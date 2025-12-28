<?php
require_once __DIR__ . '/../config/db.php';

class Model {
    protected $db;

 public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    public function lastInsertId() {
    return $this->db->lastInsertId();
}

}
