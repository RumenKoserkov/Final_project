<?php
// Модел за животните – съдържа методите за работа с животинските обяви
class Animal {

    private $conn;

    public function __construct() {
        $db = new Database();

        $this->conn = $db->connect();
    }

    // Създаване на нова животинска обява
    public function create($user_id, $type, $breed, $age, $location, $phone, $price, $negotiable, $description, $image) {
        $sql = "INSERT INTO animals (user_id, type, breed, age, location, phone, price, negotiable, description, image, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $user_id, $type, $breed, $age, $location, $phone,
            $price, $negotiable, $description, $image
        ]);
    }

    // Връща всички животни – без странициране
    public function getAll() {
        $sql = "SELECT * FROM animals ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Връща животни с лимит и отместване – за странициране
    public function getAllPaginated($limit, $offset) {
        $sql = "SELECT * FROM animals ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);  // Свързваме първия параметър
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT); // Свързваме втория параметър
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Връща общия брой животни – за изчисляване на страниците
    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM animals";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total']; // Връща общия брой
    }

    // Връща животно по неговото ID
    public function getById($id) {
        $sql = "SELECT * FROM animals WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Връща всички обяви на конкретен потребител
    public function getByUserId($user_id) {
        $sql = "SELECT * FROM animals WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    // public function getByUser($user_id) {
    //     return $this->getByUserId($user_id);
    // }

    // Изтриване на животинска обява по ID
    public function delete($id) {
        $sql = "DELETE FROM animals WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Връща последните 5 животни – за началната страница
    public function getLatest() {
        $sql = "SELECT * FROM animals ORDER BY created_at DESC LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Филтриране по тип, порода и локация
    public function filterAnimals($type, $breed, $location) {
        $sql = "SELECT * FROM animals WHERE 1=1"; // Започваме с валидно условие
        // WHERE 1=1 позволява ни винаги да добавяме условия с AND без да се тревожим дали сме в началото или не.
        $params = [];

        // Добавям филтри само ако имат стойности
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

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Обновяване на съществуваща животинска обява
    public function updateAnimal($id, $user_id, $type, $breed, $age, $location, $phone, $price, $negotiable, $description) {
        $sql = "UPDATE animals 
                SET type = ?, breed = ?, age = ?, location = ?, phone = ?, price = ?, negotiable = ?, description = ?
                WHERE id = ? AND user_id = ?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $type, $breed, $age, $location, $phone,
            $price, $negotiable, $description, $id, $user_id
        ]);
    }
}
