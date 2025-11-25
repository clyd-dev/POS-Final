<?php
// Database Configuration Class
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "product_management";
    protected $conn;

    public function __construct() {
        // Connection is established via connect() method
    }

    // Establish database connection
    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    // Close connection
    public function disconnect() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>