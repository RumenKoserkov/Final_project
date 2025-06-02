<?php
// Контролер за услугите – управлява създаване, показване, детайли, филтриране и изтриване на услуги
class ServiceController {

    // Метод за създаване на нова услуга
    public function create() {

        if (isset($_POST['create_service'])) {

            $category = $_POST['category'];            
            $subcategory = $_POST['subcategory'];      
            $location = $_POST['location'];             
            $phone = $_POST['phone'];                   
            $description = $_POST['description'];       

            if (!empty($_POST['price'])) {
                $price = $_POST['price'];
            } else {
                $price = null;
            }

            if (isset($_POST['negotiable'])) {
                $negotiable = 1;
            } else {
                $negotiable = 0;
            }

            $user_id = $_SESSION['user_id'];

            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $imageName = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../public/uploads/' . $imageName);
                $image = $imageName;
            }

            $service = new Service();

            $success = $service->create($user_id, $category, $subcategory, $location, $phone, $price, $negotiable, $description, $image);

            if ($success) {
                header("Location: index.php?page=services");
                die();
            } else {
                echo "Грешка при създаване на услуга.";
            }
        }

        require_once __DIR__ . '/../views/services/create.php';
    }

    // Метод за показване на всички услуги
    public function index() {
        $service = new Service(); 
        $services = $service->getAll(); 
        require_once __DIR__ . '/../views/services/index.php'; 
    }

    // Метод за показване на детайли за една услуга
    public function details() {
        
        if (isset($_GET['id'])) {
            $id = $_GET['id']; 

            $serviceModel = new Service();
            $service = $serviceModel->getById($id);

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

    // Метод за филтриране на услуги
    public function filter() {
        $service = new Service(); 

        if (isset($_GET['category'])) {
            $category = $_GET['category'];
        } else {
            $category = '';
        }

        if (isset($_GET['subcategory'])) {
            $subcategory = $_GET['subcategory'];
        } else {
            $subcategory = '';
        }

        if (isset($_GET['location'])) {
            $location = $_GET['location'];
        } else {
            $location = '';
        }

        $services = $service->filterServices($category, $subcategory, $location);

        require_once __DIR__ . '/../views/services/index.php';
    }

    // Метод за изтриване на услуга
    public function delete() {

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $service = new Service();
            $currentService = $service->getById($id);

            if (!$currentService) {
                echo "Услугата не съществува.";
                return;
            }

            $user_id = $_SESSION['user_id'];
            $role = $_SESSION['user_role'];

            // Само собственик или админ може да изтрие
            if ($currentService['user_id'] == $user_id || $role == 'admin') {
                $deleted = $service->delete($id);

                if ($deleted) {
                    header("Location: index.php?page=services");
                    die();
                } else {
                    echo "Грешка при изтриване.";
                }
            } else {
                echo "Нямате право да изтриете тази услуга.";
            }
        } else {
            echo "Невалидна заявка.";
        }
    }
}
