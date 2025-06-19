<?php
// Контролер за ревюта към услуги
class ReviewController {

    // Метод за добавяне на ревю
    public function add() {
        if (isset($_POST['add_review'])) {

            $service_id = $_POST['service_id'];

            $rating = $_POST['rating'];

            $comment = trim($_POST['comment']);

            $user_id = $_SESSION['user_id'];

            $reviewModel = new Review();

            // Проверявам дали потребителят вече е писал ревю за тази услуга
            if ($reviewModel->hasReviewed($user_id, $service_id)) {
                // Ако вече е писал ревю – пренасочвам обратно към детайли
                header("Location: index.php?page=details_service&id=" . $service_id);
                die();
            }

            // Създаваме новото ревю в базата
            $reviewModel->create($user_id, $service_id, $rating, $comment);

            header("Location: index.php?page=details_service&id=" . $service_id);
            die();
        } else {
            echo "Невалидна заявка.";
        }
    }
}
