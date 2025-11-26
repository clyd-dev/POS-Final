<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'db_pos';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        $this->conn = null;
        
        try {
            $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
            
            if (!$this->conn) {
                throw new Exception("Connection failed: " . mysqli_connect_error());
            }
            
            mysqli_set_charset($this->conn, 'utf8mb4');
            
        } catch(Exception $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }
        
        return $this->conn;
    }
    
    public function close() {
        if ($this->conn) {
            mysqli_close($this->conn);
        }
    }
}
?>