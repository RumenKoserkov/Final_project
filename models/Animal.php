<?php
// Модел за животните – съдържа методите за работа с животинските обяви
class Animal {
    private $conn;

    // Конструктор – свързва се с базата данни
    public function __construct() {
        require_once __DIR__ . '/Database.php';
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Създаване на нова обява
    public function create($user_id, $type, $breed, $age, $location, $phone, $price, $negotiable, $description, $image) {
        $sql = "INSERT INTO animals (user_id, type, breed, age, location, phone, price, negotiable, description, image, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$user_id, $type, $breed, $age, $location, $phone, $price, $negotiable, $description, $image]);
    }

    // Взимане на всички животни
    public function getAll() {
        $sql = "SELECT * FROM animals ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Взимане по ID
    public function getById($id) {
        $sql = "SELECT * FROM animals WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Взимане на животни на потребител
    public function getByUserId($user_id) {
        $sql = "SELECT * FROM animals WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Изтриване
    public function delete($id) {
        $sql = "DELETE FROM animals WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Последно добавени (5 броя)
    public function getLatest() {
        $sql = "SELECT * FROM animals ORDER BY created_at DESC LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Филтриране – работи и с 1, 2, 3 или всички полета
    public function filterAnimals($type, $breed, $location) {
        // Започваме с базова заявка, валидна при всички случаи
        $sql = "SELECT * FROM animals WHERE 1=1";

        // Масив с параметри, които ще се подават към execute()
        $params = [];

        // Добавяме условия само ако са попълнени
        if (!empty($type)) {
            $sql .= " AND type = ?";
            $params[] = $type;
        }

        if (!empty($breed)) {
            $sql .= " AND breed = ?";
            $params[] = $breed;
        }

        if (!empty($location)) {
            $sql .= " AND location = ?";
            $params[] = $location;
        }

        // Добавяме сортиране по дата (последни първо)
        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
