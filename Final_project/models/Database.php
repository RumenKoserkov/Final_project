<?php
// Клас Database, който ще се използва за връзка с базата чрез PDO
class Database {

    private $host = "localhost";       
    private $dbname = "pets_db";     
    private $username = "root";        
    private $password = "";            
    private $conn;                   

    public function connect() {
        if ($this->conn) {
            return $this->conn;
        }

        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->conn;

        } catch (PDOException $e) {
            die("Грешка при свързване с базата: " . $e->getMessage());
        }
    }
}
