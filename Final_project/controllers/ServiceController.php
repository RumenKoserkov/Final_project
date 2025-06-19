<?php
// Контролер за услугите – управлява създаване, показване, филтриране, детайли, изтриване и редакция
class ServiceController {

    // Метод за създаване на нова услуга
public function create() {
    $errors = [];

    if (isset($_POST['create_service'])) {
        $category = '';
        if (isset($_POST['category'])) {
            $category = $_POST['category'];
        }

        $subcategory = '';
        if (isset($_POST['subcategory'])) {
            $subcategory = $_POST['subcategory'];
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

        // Валидация на телефон
        if (!preg_match('/^[0-9]{8,15}$/', $phone)) {
            $errors[] = "Телефонът трябва да съдържа само цифри (8–15 знака).";
        }

        // Валидация на цена и "по договаряне"
        if ($negotiable == 1 && $price !== '') {
            $errors[] = "Не може да въведете цена и да изберете 'по договаряне'.";
        }

        if ($price !== '' && (!is_numeric($price) || $price < 0)) {
            $errors[] = "Цената трябва да е положително число.";
        }

        // Работа със снимка
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $check = getimagesize($_FILES['image']['tmp_name']);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $fileSize = $_FILES['image']['size'];

            if ($check === false || !in_array($check['mime'], $allowedTypes)) {
                $errors[] = "Качете валидно изображение (.jpg, .jpeg или .png).";
            }

            if ($fileSize > 2 * 1024 * 1024) {
                $errors[] = "Снимката трябва да е под 2MB.";
            }

            if (empty($errors)) {
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../public/uploads/' . $imageName);
                $image = $imageName;
            }
        } else {
            $errors[] = "Моля, прикачете снимка на услугата.";
        }

        if (empty($errors)) {
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];

                require_once __DIR__ . '/../models/Service.php';
                $service = new Service();

                $success = $service->create($user_id, $category, $subcategory, $location, $phone, $price, $negotiable, $description, $image);

                if ($success) {
                    header("Location: index.php?page=services");
                    die();
                } else {
                    $errors[] = "Грешка при създаване на услуга.";
                }
            } else {
                $errors[] = "Няма активна сесия.";
            }
        }
    }

    require_once __DIR__ . '/../views/services/create.php';
}

// Метод за показване на услугите със странициране
public function index() {
    $service = new Service();

    // Задаваме колко услуги да се показват на една страница
    $limit = 6;

    // Начално задаваме текущата страница да е 1
    $page = 1;

    // Ако е подадена GET променлива "p" (номер на страница) и тя е валидно число > 0
    if (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0) {
        // Задаваме текущата страница според подадената стойност
        $page = (int)$_GET['p'];
    }

    // Изчисляваме offset – колко реда да пропуснем преди да вземем $limit резултата
    $offset = ($page - 1) * $limit;

    $services = $service->getPaginated($limit, $offset);

    $totalServices = $service->countAll();

    $totalPages = ceil($totalServices / $limit);

    require_once __DIR__ . '/../views/services/index.php';
}

    // Метод за детайли за конкретна услуга
    public function details() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // require_once __DIR__ . '/../models/Service.php';
            // require_once __DIR__ . '/../models/Visit.php';

            $serviceModel = new Service();
            $service = $serviceModel->getById($id);

            // Броим посещения само ако потребителят е логнат
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];

                $visitModel = new Visit();
                $visitModel->recordVisit($user_id, $id);
                $visitCount = $visitModel->countVisits($id);
            } else {
                $visitCount = null;
            }

            require_once __DIR__ . '/../views/services/details.php';
        } else {
            echo "Невалидна заявка.";
        }
    }

    // Метод за филтриране по категория, подкатегория и локация
    public function filter() {
        $category = '';
        if (isset($_GET['category'])) {
            $category = $_GET['category'];
        }

        $subcategory = '';
        if (isset($_GET['subcategory'])) {
            $subcategory = $_GET['subcategory'];
        }

        $location = '';
        if (isset($_GET['location'])) {
            $location = $_GET['location'];
        }

        require_once __DIR__ . '/../models/Service.php';
        $service = new Service();
        $services = $service->filterServices($category, $subcategory, $location);

        require_once __DIR__ . '/../views/services/index.php';
    }

    // Метод за изтриване на услуга
    public function delete() {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];

            $service = new Service();

            $currentService = $service->getById($id);

            if (!$currentService) {
                echo "Услугата не съществува.";
                return;
            }

            // Проверяваме правата (дали потребителят е създател или админ)
            if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
                $user_id = $_SESSION['user_id'];
                $role = $_SESSION['user_role'];

                if ($currentService['user_id'] == $user_id || $role === 'admin') {
                    // Изтриваме услугата
                    $deleted = $service->delete($id);

                    if ($deleted) {
                        header("Location: index.php?page=edit_profile");
                        die();
                    } else {
                        echo "Грешка при изтриване.";
                    }
                } else {
                    echo "Нямате право да изтриете тази услуга.";
                }
            } else {
                echo "Невалидна сесия.";
            }
        } else {
            echo "Невалидна заявка.";
        }
    }


// Метод за редакция на услуга
public function edit() {
    if (!isset($_GET['id'])) {
        echo "Липсва ID на услугата.";
        return;
    }

    $id = $_GET['id'];

    $serviceModel = new Service();
    $service = $serviceModel->getById($id);

    if (!$service) {
        echo "Услугата не съществува.";
        return;
    }

    // Проверка дали логнатият потребител е собственик
    if (!isset($_SESSION['user_id']) || $service['user_id'] != $_SESSION['user_id']) {
        echo "Нямате право да редактирате тази услуга.";
        return;
    }

    $error = '';
    $success = '';

    if (isset($_POST['update_service'])) {
        $category = '';
        if (isset($_POST['category'])) {
            $category = $_POST['category'];
        }

        $subcategory = '';
        if (isset($_POST['subcategory'])) {
            $subcategory = $_POST['subcategory'];
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

        $price = null;
        if (isset($_POST['price']) && $_POST['price'] !== '') {
            $price = $_POST['price'];
        }

        $negotiable = 0;
        if (isset($_POST['negotiable'])) {
            $negotiable = 1;
        }

        if (!preg_match('/^[0-9]{8,15}$/', $phone)) {
            $error = "Телефонът трябва да съдържа само цифри (8–15 знака).";
        }

        if ($price !== null && (!is_numeric($price) || $price < 0)) {
            $error = "Цената трябва да е положително число.";
        }

        if ($negotiable == 1 && $price !== null && $price !== '') {
            $error = "Не може да въведете цена и да изберете 'по договаряне'.";
        }


        if ($error === '') {
            $updated = $serviceModel->updateService(
                $id,
                $_SESSION['user_id'],
                $category,
                $subcategory,
                $location,
                $phone,
                $price,
                $negotiable,
                $description
            );

            if ($updated) {
            header("Location: index.php?page=details_service&id=" . $id);
            die();
        } else {
            $error = "Грешка при редакция на услугата.";
        }
        }
    }

    require_once __DIR__ . '/../views/services/edit.php';
}

}

