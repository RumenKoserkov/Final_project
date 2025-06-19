<?php
// Модел за услугите – съдържа всички методи за работа с услугите
class Service {
    private $conn;

    public function __construct() {
        $db = new Database();                   
        $this->conn = $db->connect();           
    }

    // Създаване на нова услуга
    public function create($user_id, $category, $subcategory, $location, $phone, $price, $negotiable, $description, $image) {
        $sql = "INSERT INTO services (user_id, category, subcategory, location, phone, price, negotiable, description, image, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql); 
        return $stmt->execute([$user_id, $category, $subcategory, $location, $phone, $price, $negotiable, $description, $image]);
    }

    // Връща всички услуги (без странициране)
    public function getAll() {
        $sql = "SELECT * FROM services ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Връща услуги със странициране
    public function getPaginated($limit, $offset) {
        $sql = "SELECT * FROM services ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        // Въвеждам стойностите с bindValue, за да задам тип
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Връща общия брой услуги
    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM services";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Взима услуга по ID
    public function getById($id) {
        $sql = "SELECT * FROM services WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Взима услуги на конкретен потребител
    public function getByUserId($user_id) {
        $sql = "SELECT * FROM services WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // public function getByUser($user_id) {
    //     return $this->getByUserId($user_id);
    // }

    // Изтриване на услуга и свързаните с нея записи
    public function delete($id) {
        // Изтривам първо свързаните ревюта
        $sql1 = "DELETE FROM reviews WHERE service_id = ?";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->execute([$id]);

        // След това изтривам посещенията
        $sql2 = "DELETE FROM visits WHERE service_id = ?";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->execute([$id]);

        // Накрая самата услуга
        $sql3 = "DELETE FROM services WHERE id = ?";
        $stmt3 = $this->conn->prepare($sql3);
        return $stmt3->execute([$id]);
    }

    // Последните 5 услуги (за начална страница)
    public function getLatest() {
        $sql = "SELECT * FROM services ORDER BY created_at DESC LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Филтриране по категория, подкатегория и локация
    public function filterServices($category, $subcategory, $location) {
        // Начална заявка (1=1 позволява лесно добавяне на AND условия)
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

    // Обновяване на услуга 
    public function updateService($id, $user_id, $category, $subcategory, $location, $phone, $price, $negotiable, $description) {
        $sql = "UPDATE services 
                SET category = ?, subcategory = ?, location = ?, phone = ?, price = ?, negotiable = ?, description = ?
                WHERE id = ? AND user_id = ?";
        
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $category,
            $subcategory,
            $location,
            $phone,
            $price,
            $negotiable,
            $description,
            $id,
            $user_id
        ]);
    }
}
