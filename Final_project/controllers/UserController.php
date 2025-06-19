<?php
// Контролер за потребителите – управлява регистрация, вход, изход и редакция на профил
class UserController {

    // Метод за регистрация на нов потребител
    public function register() {
        $error_message = '';
        $success_message = '';

        if (isset($_POST['register'])) {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = [];

            if (strlen($first_name) < 2 || strlen($first_name) > 50 || !preg_match('/^[а-яА-Яa-zA-Z\s]+$/u', $first_name)) {
                $errors[] = "Моля, въведете валидно собствено име (2-50 букви).";
            }

            if (strlen($last_name) < 2 || strlen($last_name) > 50 || !preg_match('/^[а-яА-Яa-zA-Z\s]+$/u', $last_name)) {
                $errors[] = "Моля, въведете валидна фамилия (2-50 букви).";
            }

            if (strlen($username) < 3 || strlen($username) > 50 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $errors[] = "Потребителското име трябва да е между 3 и 50 символа и да съдържа само букви, цифри и _.";
            }

            if (strlen($password) < 6) {
                $errors[] = "Паролата трябва да е поне 6 символа.";
            }

            if (!preg_match('/[A-Z]/', $password)) {
                $errors[] = "Паролата трябва да съдържа поне една главна буква.";
            }

            if (!preg_match('/[0-9]/', $password)) {
                $errors[] = "Паролата трябва да съдържа поне една цифра.";
            }

            if (!empty($errors)) {
                $error_message = implode('<br>', $errors);
                require_once __DIR__ . '/../views/users/register.php';
                return;
            }

            $user = new User();

            if ($user->emailExists($email)) {
                $error_message = "Имейлът вече съществува. Моля, използвайте друг.";
                require_once __DIR__ . '/../views/users/register.php';
                return;
            }

            $success = $user->register($first_name, $last_name, $username, $email, $password);

            if ($success) {
                header("Location: index.php?page=login");
                die();
            } else {
                $error_message = "Грешка при регистрация.";
            }
        }

        require_once __DIR__ . '/../views/users/register.php';
    }

    // Метод за вход
    public function login() {
        $error_message = '';

        if (isset($_POST['login'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = new User();
            $data = $user->login($email);

            if ($data && password_verify($password, $data['password'])) {
                $_SESSION['user_id'] = $data['id'];
                $_SESSION['user_name'] = $data['first_name'];
                $_SESSION['user_role'] = $data['role'];
                $_SESSION['user_username'] = $data['username'];

                header("Location: index.php?page=home");
                die();
            } else {
                $error_message = "Грешен имейл или парола.";
            }
        }

        require_once __DIR__ . '/../views/users/login.php';
    }

    // Метод за изход
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?page=login");
        die();
    }

    // Метод за редакция на профил
    public function edit() {
        $user = new User();
        $userData = $user->getById($_SESSION['user_id']);

        $animalModel = new Animal();
        $userAnimals = $animalModel->getByUserId($_SESSION['user_id']);

        $serviceModel = new Service();
        $userServices = $serviceModel->getByUserId($_SESSION['user_id']);

        $error_message = '';
        $success_message = '';

        // Редакция на имена и username
        if (isset($_POST['update_profile'])) {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $username = $_POST['username'];

            $errors = [];

            if (strlen($first_name) < 2 || strlen($first_name) > 50 || !preg_match('/^[а-яА-Яa-zA-Z\s]+$/u', $first_name)) {
                $errors[] = "Моля, въведете валидно собствено име (2-50 букви).";
            }

            if (strlen($last_name) < 2 || strlen($last_name) > 50 || !preg_match('/^[а-яА-Яa-zA-Z\s]+$/u', $last_name)) {
                $errors[] = "Моля, въведете валидна фамилия (2-50 букви).";
            }

            if (strlen($username) < 3 || strlen($username) > 50 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $errors[] = "Потребителското име трябва да е между 3 и 50 символа и да съдържа само букви, цифри и _.";
            }

            if (!empty($errors)) {
                $error_message = implode('<br>', $errors);
            } else {
                $success = $user->updateProfile($_SESSION['user_id'], $first_name, $last_name, $username);

                if ($success) {
                    $success_message = "Профилът е обновен успешно.";
                    $userData = $user->getById($_SESSION['user_id']);
                } else {
                    $error_message = "Грешка при обновяване на профила.";
                }
            }
        }

        // Смяна на парола
        if (isset($_POST['change_password'])) {
            $current = $_POST['current_password'];
            $new = $_POST['new_password'];
            $confirm = $_POST['confirm_password'];

            $data = $user->getById($_SESSION['user_id']);

            if (!password_verify($current, $data['password'])) {
                $error_message = "Грешна текуща парола.";
            } elseif ($new !== $confirm) {
                $error_message = "Новата парола и потвърждението не съвпадат.";
            } elseif (strlen($new) < 6 || !preg_match('/[A-Z]/', $new) || !preg_match('/[0-9]/', $new)) {
                $error_message = "Новата парола трябва да е поне 6 символа, с главна буква и цифра.";
            } else {
                $changed = $user->changePassword($_SESSION['user_id'], $new);
                if ($changed) {
                    $success_message = "Паролата е сменена успешно.";
                } else {
                    $error_message = "Грешка при смяна на паролата.";
                }
            }
        }

        require_once __DIR__ . '/../views/users/edit.php';
    }
}
