<?php
// Декларираме контролер за животинските обяви
class AnimalController {

    // Метод за създаване на нова обява
    public function create() {
        $errors = [];

        if (isset($_POST['create_animal'])) {

            $type = '';
            if (isset($_POST['type'])) {
                $type = $_POST['type'];
            }

            $breed = '';
            if (isset($_POST['breed'])) {
                $breed = $_POST['breed'];
            }

            $age = '';
            if (isset($_POST['age'])) {
                $age = $_POST['age'];
            }

            $location = '';
            if (isset($_POST['location'])) {
                $location = $_POST['location'];
            }

            $phone = '';
            if (isset($_POST['phone'])) {
                $phone = $_POST['phone'];
            }

            $description = '';
            if (isset($_POST['description'])) {
                $description = $_POST['description'];
            }

            $price = '';
            if (isset($_POST['price']) && $_POST['price'] !== '') {
                $price = $_POST['price'];
            }

            $negotiable = 0; // Стойност 0 = не е по договаряне
            if (isset($_POST['negotiable'])) {
                $negotiable = 1; // Стойност 1 = по договаряне
            }

            if (!preg_match('/^[0-9]{8,15}$/', $phone)) {
                $errors[] = "Телефонът трябва да съдържа само цифри (8–15 знака).";
            }

            if (!is_numeric($age) || $age < 0 || $age > 30) {
                $errors[] = "Възрастта трябва да е число между 0 и 30.";
            }

            if ($negotiable == 1 && $price !== '') {
                $errors[] = "Не може да въведете цена и да изберете 'по договаряне'.";
            }

            if ($price !== '' && (!is_numeric($price) || $price < 0)) {
                $errors[] = "Цената трябва да е положително число.";
            }

            // Работа със снимка – ако е качена
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $check = getimagesize($_FILES['image']['tmp_name']); // Проверка дали е изображение
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg']; // Разрешени типове
                $fileSize = $_FILES['image']['size']; // Размер на файла

                // Проверка дали типът е валиден
                if ($check === false || !in_array($check['mime'], $allowedTypes)) {
                    $errors[] = "Качете валидно изображение (.jpg, .jpeg или .png).";
                }

                // Проверка за размер на файла (макс 2MB)
                if ($fileSize > 2 * 1024 * 1024) {
                    $errors[] = "Снимката трябва да е под 2MB.";
                }

                if (empty($errors)) {
                    $imageName = time() . '_' . basename($_FILES['image']['name']); 
                    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../public/uploads/' . $imageName);
                    $image = $imageName;
                }
            } else {
                $errors[] = "Моля, прикачете снимка на животното.";
            }

            // Ако няма грешки – записвам обявата
            if (empty($errors)) {
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];

                    $animal = new Animal();

                    $success = $animal->create($user_id, $type, $breed, $age, $location, $phone, $price, $negotiable, $description, $image);

                    if ($success) {
                        header("Location: index.php?page=animals");
                        die();
                    } else {
                        $errors[] = "Грешка при създаване на обява.";
                    }
                } else {
                    $errors[] = "Няма активна сесия.";
                }
            }
        }

        require_once __DIR__ . '/../views/animals/create.php';
    }

    // Показване на всички обяви със странициране
    public function index() {
        $animal = new Animal();

        $limit = 6; // Брой обяви на страница
        $page = 1; // Начална страница

        // Проверка дали има зададена страница
        if (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0) {
            $page = (int)$_GET['p'];
        }

        $offset = ($page - 1) * $limit; // Изчисляваме отместването
        $totalAnimals = $animal->countAll(); 
        $totalPages = ceil($totalAnimals / $limit); // Общо страници
        $animals = $animal->getAllPaginated($limit, $offset); // Взимаме животните за текущата страница

        require_once __DIR__ . '/../views/animals/index.php'; 
    }

    // Показване на детайлите за конкретно животно
    public function details() {
        if (isset($_GET['id'])) {
            $animal_id = $_GET['id'];

            $animalModel = new Animal();

            $animal = $animalModel->getById($animal_id); // Вземаме обявата по ID
            require_once __DIR__ . '/../views/animals/details.php';
        } else {
            echo "Невалидна заявка.";
        }
    }

    // Изтриване на обява
    public function delete() {
        if (isset($_POST['id'])) {
            $animal_id = $_POST['id'];

            require_once __DIR__ . '/../models/Animal.php';
            $animal = new Animal();
            $currentAnimal = $animal->getById($animal_id);

            // Проверка дали животното съществува
            if (!$currentAnimal) {
                echo "Обявата не съществува.";
                return;
            }

            if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
                $user_id = $_SESSION['user_id'];
                $role = $_SESSION['user_role'];

                if ($currentAnimal['user_id'] == $user_id || $role === 'admin') {
                    $deleted = $animal->delete($animal_id);

                    if ($deleted) {
                        header("Location: index.php?page=edit_profile");
                        exit;
                    } else {
                        echo "Грешка при изтриване.";
                    }
                } else {
                    echo "Нямате право да изтриете тази обява.";
                }
            } else {
                echo "Невалидна сесия.";
            }
        } else {
            echo "Невалидна заявка.";
        }
    }

    // Филтриране по тип, порода и локация
    public function filter() {
        $animalModel = new Animal();

        $type = '';
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
        }

        $breed = '';
        if (isset($_GET['breed'])) {
            $breed = $_GET['breed'];
        }

        $location = '';
        if (isset($_GET['location'])) {
            $location = $_GET['location'];
        }

        $animals = $animalModel->filterAnimals($type, $breed, $location);

        require_once __DIR__ . '/../views/animals/index.php';
    }

    // Редактиране на обява
    public function edit() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "Липсва или е невалидно ID на обявата.";
            return;
        }

        $animal_id = $_GET['id'];

        $animalModel = new Animal();
        $animal = $animalModel->getById($animal_id);

        if (!$animal) {
            echo "Обявата не съществува.";
            return;
        }

        // Проверка дали логнатият потребител е собственик
        if (!isset($_SESSION['user_id']) || $animal['user_id'] != $_SESSION['user_id']) {
            echo "Нямате право да редактирате тази обява.";
            return;
        }

        $errors = [];

        if (isset($_POST['update_animal'])) {

            $type = '';
            if (isset($_POST['type'])) {
                $type = $_POST['type'];
            }

            $breed = '';
            if (isset($_POST['breed'])) {
                $breed = $_POST['breed'];
            }

            $age = '';
            if (isset($_POST['age'])) {
                $age = $_POST['age'];
            }

            $location = '';
            if (isset($_POST['location'])) {
                $location = $_POST['location'];
            }

            $phone = '';
            if (isset($_POST['phone'])) {
                $phone = $_POST['phone'];
            }

            $description = '';
            if (isset($_POST['description'])) {
                $description = $_POST['description'];
            }

            $price = '';
            if (isset($_POST['price']) && $_POST['price'] !== '') {
                $price = $_POST['price'];
            }

            $negotiable = 0;
            if (isset($_POST['negotiable'])) {
                $negotiable = 1;
            }

            if (!preg_match('/^[0-9]{8,15}$/', $phone)) {
                $errors[] = "Телефонът трябва да съдържа само цифри (8–15 знака).";
            }

            if (!is_numeric($age) || $age < 0 || $age > 30) {
                $errors[] = "Възрастта трябва да е число между 0 и 30.";
            }

            if ($negotiable == 1 && $price !== '') {
                $errors[] = "Не може да въведете цена и да изберете 'по договаряне'.";
            }

            if ($price !== '' && (!is_numeric($price) || $price < 0)) {
                $errors[] = "Цената трябва да е положително число.";
            }

            if (empty($errors)) {
                $updated = $animalModel->updateAnimal(
                    $animal_id,
                    $_SESSION['user_id'],
                    $type,
                    $breed,
                    $age,
                    $location,
                    $phone,
                    $price,
                    $negotiable,
                    $description
                );

                if ($updated) {
                    header("Location: index.php?page=animal_details&id=" . $animal_id);
                    die();
                } else {
                    $errors[] = "Грешка при обновяване на обявата.";
                }
            }
        }

        require_once __DIR__ . '/../views/animals/edit.php';
    }
}
