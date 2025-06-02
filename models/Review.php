<?php
// Review – управлява ревютата за услуги
class Review {
    private $conn;

    // Конструктор 
    public function __construct() {
        require_once __DIR__ . '/Database.php';
        $db = new Database();               
        $this->conn = $db->connect();      
    }

    // Метод за създаване на ново ревю
    public function create($user_id, $service_id, $rating, $comment) {
        $sql = "INSERT INTO reviews (user_id, service_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$user_id, $service_id, $rating, $comment]);
    }

    // Метод за проверка дали потребителят вече е писал ревю за конкретна услуга
    public function hasReviewed($user_id, $service_id) {
        $sql = "SELECT id FROM reviews WHERE user_id = ? AND service_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id, $service_id]);

        // Проверяваме дали има резултат
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Взима всички ревюта за дадена услуга, включително името на автора
    public function getByServiceId($service_id) {
        $sql = "
            SELECT r.*, u.name AS author_name
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.service_id = ?
            ORDER BY r.created_at DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$service_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Изчислява средната оценка за дадена услуга
    public function getAverageRating($service_id) {
        $sql = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE service_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$service_id]);

        // Връщаме закръглената стойност до 2 знака
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return round($row['avg_rating'], 2);
    }

    // Връща броя на ревютата за дадена услуга
    public function countReviews($service_id) {
        // заявка за броене на всички редове
        $sql = "SELECT COUNT(*) AS total FROM reviews WHERE service_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$service_id]);

        // Връщаме стойността total
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
