<?php
// Контролерът за животни – управлява създаване, показване, изтриване и филтриране на обяви
class AnimalController {

    // Създава нова животинска обява, обработва формата, качва снимка и записва в базата
    public function create() {
        if (isset($_POST['create_animal'])) {
            $type = $_POST['type'];
            $breed = $_POST['breed'];
            $age = $_POST['age'];
            $location = $_POST['location'];
            $phone = $_POST['phone'];
            $description = $_POST['description'];

            $price = '';
            if (!empty($_POST['price'])) {
                $price = $_POST['price'];
            }

            $negotiable = 0;
            if (isset($_POST['negotiable'])) {
                $negotiable = 1;
            }

            $user_id = $_SESSION['user_id'];

            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $imageName = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../public/uploads/' . $imageName);
                $image = $imageName;
            }

            $animal = new Animal();
            $success = $animal->create(
                $user_id,
                $type,
                $breed,
                $age,
                $location,
                $phone,
                $price,
                $negotiable,
                $description,
                $image
            );

            if ($success) {
                header("Location: index.php?page=animals");
                die();
            } else {
                echo "Грешка при създаване на обява.";
            }
        }

        require_once __DIR__ . '/../views/animals/create.php';
    }

    // Зарежда и показва списък с всички животински обяви от базата
    public function index() {
        $animal = new Animal();
        $animals = $animal->getAll();
        require_once __DIR__ . '/../views/animals/index.php';
    }

    // Показва детайлна информация за конкретна обява
    public function details() {
        if (isset($_GET['id'])) {
            $animal_id = $_GET['id'];
            $animalModel = new Animal();
            $animal = $animalModel->getById($animal_id);
            require_once __DIR__ . '/../views/animals/details.php';
        } else {
            echo "Невалидна заявка.";
        }
    }

    // Изтрива обява по ID, само ако потребителят е създател или администратор
    public function delete() {
        if (isset($_GET['id'])) {
            $animal_id = $_GET['id'];
            $animal = new Animal();
            $currentAnimal = $animal->getById($animal_id);

            if (!$currentAnimal) {
                echo "Обявата не съществува.";
                return;
            }

            $user_id = $_SESSION['user_id'];
            $role = $_SESSION['user_role'];

            if ($currentAnimal['user_id'] == $user_id || $role == 'admin') {
                $deleted = $animal->delete($animal_id);

                if ($deleted) {
                    header("Location: index.php?page=animals");
                    die();
                } else {
                    echo "Грешка при изтриване.";
                }
            } else {
                echo "Нямате право да изтриете тази обява.";
            }
        } else {
            echo "Невалидна заявка.";
        }
    }

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
}

