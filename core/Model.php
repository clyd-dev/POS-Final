<?php
require_once '../config/database.php';

class Model {
    protected $db;
    protected $conn;
    
    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }
    
    protected function query($sql) {
        return mysqli_query($this->conn, $sql);
    }
    
    protected function prepare($sql) {
        return mysqli_prepare($this->conn, $sql);
    }
    
    protected function escape($string) {
        return mysqli_real_escape_string($this->conn, $string);
    }
    
    protected function fetchAll($result) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    
    protected function fetch($result) {
        return mysqli_fetch_assoc($result);
    }
    
    protected function lastInsertId() {
        return mysqli_insert_id($this->conn);
    }
    
    protected function affectedRows() {
        return mysqli_affected_rows($this->conn);
    }
    
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
?>