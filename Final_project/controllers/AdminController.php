<?php
// Контролер за администраторски действия
class AdminController {

    // Метод за показване на таблото за администрация
    public function dashboard() {
        // Проверка дали потребителят е логнат и има админ роля
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo "Достъпът е само за администратори.";
            die();
        }

        $userModel = new User();
        $serviceModel = new Service();
        $animalModel = new Animal();

        // Взимаме всички потребители, услуги и животни
        $users = $userModel->getAll();
        $services = $serviceModel->getAll();
        $animals = $animalModel->getAll();

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    // Метод за изтриване на потребител
    public function deleteUser() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo "Достъпът е само за администратори.";
            die();
        }

        if (isset($_POST['id'])) {
            $id = $_POST['id'];

            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
                echo "Не можеш да изтриеш своя собствен акаунт.";
                die();
            }

            $userModel = new User();
            $userModel->delete($id);

            header("Location: index.php?page=admin_dashboard");
            die();
        }

        echo "Невалиден потребител.";
        die();
    }

    // Метод за изтриване на услуга
    public function deleteService() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo "Достъпът е само за администратори.";
            die();
        }

        if (isset($_POST['id'])) {
            $id = $_POST['id'];

            $serviceModel = new Service();
            $serviceModel->delete($id);

            header("Location: index.php?page=admin_dashboard");
            die();
        }

        echo "Невалидна услуга.";
        die();
    }

    // Метод за изтриване на животно
    public function deleteAnimal() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo "Достъпът е само за администратори.";
            die();
        }
        if (isset($_POST['id'])) {
            $id = $_POST['id'];

            $animalModel = new Animal();
            $animalModel->delete($id);

            header("Location: index.php?page=admin_dashboard");
            die();
        }

        echo "Невалидно животно.";
        die();
    }
}
