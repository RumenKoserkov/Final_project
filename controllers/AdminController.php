<?php
// Контролер за администраторски действия
class AdminController {

    // Метод за показване на таблото за администрация
    public function dashboard() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo "Достъпът е само за администратори.";
            die();
        }

        $userModel = new User();       // За работа с потребители
        $serviceModel = new Service(); // За работа с услуги
        $animalModel = new Animal();   // За работа с животни

        // Вземаме всички потребители, услуги и животни от базата
        $users = $userModel->getAll();
        $services = $serviceModel->getAll();
        $animals = $animalModel->getAll();

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    // Метод за изтриване на потребител по ID
    public function deleteUser() {
        // 🔒 Достъп само за администратори
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo "Достъпът е само за администратори.";
            die();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];             // Вземаме ID-то
            $userModel = new User();       // Зареждаме модела
            $userModel->delete($id);       // Извикваме метода за изтриване

            header("Location: index.php?page=admin_dashboard");
            die();
        } else {
            echo "Невалиден потребител.";
        }
    }

    // Метод за изтриване на услуга по ID
    public function deleteService() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo "Достъпът е само за администратори.";
            die();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $serviceModel = new Service();
            $serviceModel->delete($id);

            header("Location: index.php?page=admin_dashboard");
            die();
        } else {
            echo "Невалидна услуга.";
        }
    }

    // Метод за изтриване на животинска обява по ID
    public function deleteAnimal() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo "Достъпът е само за администратори.";
            die();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $animalModel = new Animal();
            $animalModel->delete($id);

            header("Location: index.php?page=admin_dashboard");
            die();
        } else {
            echo "Невалидно животно.";
        }
    }
}
