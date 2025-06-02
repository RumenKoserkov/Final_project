<?php
// User – съдържа всички операции, свързани с потребителите
class User {

    private $conn;

    public function __construct() {
        require_once __DIR__ . '/Database.php';  
        $db = new Database();                    
        $this->conn = $db->connect();            
    }

    // Регистрация на нов потребител
    public function register($name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Хешираме паролата 

        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')"; 
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $email, $hashedPassword]);
    }

    // Вход – намира потребител по имейл
    public function login($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Връща потребител по ID
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Актуализира име и имейл на потребител
    public function updateProfile($id, $name, $email) {
        $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $email, $id]);
    }

    // Връща всички потребители (за админ панела)
    public function getAll() {
        $sql = "SELECT * FROM users ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Изтрива потребител по ID
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
