<?php
// Клас Visit – модел за работа с посещенията на услуги
class Visit {

    private $conn;

    public function __construct() {
        $db = new Database();                
        $this->conn = $db->connect();       
    }

    // Метод за записване на посещение от потребител за дадена услуга (само ако все още няма такова)
    public function recordVisit($user_id, $service_id) {
        $sql = "SELECT id FROM visits WHERE user_id = ? AND service_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id, $service_id]);

        // Ако не е намерено съществуващо посещение
        if ($stmt->fetch(PDO::FETCH_ASSOC) === false) {
            $insert_sql = "INSERT INTO visits (user_id, service_id) VALUES (?, ?)";
            $insert = $this->conn->prepare($insert_sql);
            return $insert->execute([$user_id, $service_id]);
        }

        //  Ако вече има такова посещение
        return false;
    }

    // Метод за броене на посещения на дадена услуга
    public function countVisits($service_id) {
        $sql = "SELECT COUNT(*) AS total FROM visits WHERE service_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$service_id]);

        // Взимам резултата (total)
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Връщам числото с общия брой посещения
        return $row['total'];
    }
}
