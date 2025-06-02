<?php
// Контролер за потребителите – управлява регистрация, вход, изход и редакция на профил
class UserController {

    // Метод за регистрация
    public function register() {
        
        if (isset($_POST['register'])) {

            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = new User();

            $success = $user->register($name, $email, $password);

            if ($success) {
                header("Location: index.php?page=login");
                die();
            } else {
                echo "Грешка при регистрация.";
            }
        }

        require_once __DIR__ . '/../views/users/register.php';
    }

    // Метод за вход 
    public function login() {

        if (isset($_POST['login'])) {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = new User();
            $data = $user->login($email);

            // Ако такъв потребител съществува и паролата съвпада
            if ($data && password_verify($password, $data['password'])) {

                // Записваме информацията в сесията, за да впишем потребителя
                $_SESSION['user_id'] = $data['id'];
                $_SESSION['user_name'] = $data['name'];
                $_SESSION['user_role'] = $data['role'];

                header("Location: index.php?page=home");
                die();
            } else {
                $error = "Грешен имейл или парола.";
            }
        }

        require_once __DIR__ . '/../views/users/login.php';
    }

    // Метод за изход
    public function logout() {
        //  Не стартирам session_start(), защото сесията вече е стартирана в index.php
        session_unset(); //  Изчистваме всички сесийни променливи
        session_destroy();
        header("Location: index.php?page=login");
        die();
    }

    // Метод за редакция на потребителския профил
    public function edit() {
        $user = new User();
        $userData = $user->getById($_SESSION['user_id']);

        if (isset($_POST['update_profile'])) {
            $name = $_POST['name'];       
            $email = $_POST['email'];   

            $success = $user->updateProfile($_SESSION['user_id'], $name, $email);

            if ($success) {
                $userData = $user->getById($_SESSION['user_id']);
            } else {
                echo "Грешка при обновяване на профила.";
            }
        }
        
        require_once __DIR__ . '/../views/users/edit.php';
    }
}
