<?php
// User – съдържа всички операции, свързани с потребителите
class User {
    private $conn;

    public function __construct() {
        $db = new Database();                    
        $this->conn = $db->connect();            
    }

    // Регистрация на нов потребител 
    public function register($first_name, $last_name, $username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 

        $sql = "INSERT INTO users (first_name, last_name, username, email, password, role) 
                VALUES (?, ?, ?, ?, ?, 'user')";

        $stmt = $this->conn->prepare($sql); 
        return $stmt->execute([$first_name, $last_name, $username, $email, $hashedPassword]); 
    }

    // Проверка дали имейл вече съществува
    public function emailExists($email) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);     
        $stmt->execute([$email]);           
        $result = $stmt->fetch(PDO::FETCH_ASSOC);            

        if ($result) {
            return true;                       
        } else {
            return false;                    
        }
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

    // Актуализира профил 
    public function updateProfile($id, $first_name, $last_name, $username) {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, username = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$first_name, $last_name, $username, $id]); 
    }

    // Смяна на парола по ID
    public function changePassword($id, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT); 
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$hashed, $id]); 
    }

    // Връща всички потребители – за админ панела
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
