<?php
// Модел за услугите – съдържа всички методи за работа с услугите
class Service {
    private $conn;

    // Свързваме се с базата чрез конструктора
    public function __construct() {
        require_once __DIR__ . '/Database.php'; 
        $db = new Database();                   
        $this->conn = $db->connect();           
    }

    // Метод за създаване на нова услуга
    public function create($user_id, $category, $subcategory, $location, $phone, $price, $negotiable, $description, $image) {
        $sql = "INSERT INTO services (user_id, category, subcategory, location, phone, price, negotiable, description, image, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql); 
        return $stmt->execute([$user_id, $category, $subcategory, $location, $phone, $price, $negotiable, $description, $image]);
    }

    // Връща всички услуги от базата
    public function getAll() {
        $sql = "SELECT * FROM services ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Връща услуга по ID
    public function getById($id) {
        $sql = "SELECT * FROM services WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Връща всички услуги, създадени от даден потребител
    public function getByUserId($user_id) {
        $sql = "SELECT * FROM services WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Изтрива услуга + свързаните й ревюта и посещения
    public function delete($id) {
        // Изтриваме ревютата
        $sql1 = "DELETE FROM reviews WHERE service_id = ?";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->execute([$id]);

        // Изтриваме посещенията
        $sql2 = "DELETE FROM visits WHERE service_id = ?";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->execute([$id]);

        // Изтриваме самата услуга
        $sql3 = "DELETE FROM services WHERE id = ?";
        $stmt3 = $this->conn->prepare($sql3);
        return $stmt3->execute([$id]);
    }

    // Връща последно добавените 5 услуги
    public function getLatest() {
        $sql = "SELECT * FROM services ORDER BY created_at DESC LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Метод за филтриране по избрани полета 
    public function filterServices($category, $subcategory, $location) {

    $sql = "SELECT * FROM services WHERE 1=1";

    $params = [];

    if (!empty($category)) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }

    if (!empty($subcategory)) {
        $sql .= " AND subcategory = ?";
        $params[] = $subcategory;
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
}
