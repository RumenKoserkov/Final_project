<?php
// Клас Review – управлява ревютата за услуги
class Review {
    private $conn;

    public function __construct() {
        $db = new Database();

        $this->conn = $db->connect();
    }

    // Създаване на ново ревю
    public function create($user_id, $service_id, $rating, $comment) {
        $sql = "INSERT INTO reviews (user_id, service_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$user_id, $service_id, $rating, $comment]);
    }

    // Проверка дали потребителят вече е оставил ревю за дадена услуга
    public function hasReviewed($user_id, $service_id) {
        $sql = "SELECT id FROM reviews WHERE user_id = ? AND service_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id, $service_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Връща всички ревюта за дадена услуга + името на автора
    public function getByServiceId($service_id) {
        // взимам всички ревюта + имената на авторите от users
        $sql = "
            SELECT reviews.*, CONCAT(users.first_name, ' ', users.last_name) AS author_name
            FROM reviews
            JOIN users ON reviews.user_id = users.id
            WHERE reviews.service_id = ?
            ORDER BY reviews.created_at DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$service_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Връща средна оценка на услуга (float число)
    public function getAverageRating($service_id) {
        $sql = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE service_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$service_id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Закръглям до 2 знака след десетичната запетая
        return round($row['avg_rating'], 2);
    }

    // Връща броя ревюта за дадена услуга
    public function countReviews($service_id) {
        $sql = "SELECT COUNT(*) AS total FROM reviews WHERE service_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$service_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Връщам общия брой
        return $row['total'];
    }
}
