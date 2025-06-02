<?php
session_start();

spl_autoload_register(function ($class) {
    if (file_exists(__DIR__ . '/../models/' . $class . '.php')) {
        require_once __DIR__ . '/../models/' . $class . '.php';
    }

    elseif (file_exists(__DIR__ . '/../controllers/' . $class . '.php')) {
        require_once __DIR__ . '/../controllers/' . $class . '.php';
    }
});

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Проверяваме стойността на $page и зареждаме съответния контролер и метод
switch ($page) {

    case 'register':
        $controller = new UserController();
        $controller->register();
        break;

    case 'login':
        $controller = new UserController();
        $controller->login();
        break;

    case 'logout':
        $controller = new UserController();
        $controller->logout();
        break;

    case 'animals':
        $controller = new AnimalController();
        $controller->index();
        break;

    case 'create_animal':
        $controller = new AnimalController();
        $controller->create();
        break;

    case 'delete_animal':
        $controller = new AnimalController();
        $controller->delete();
        break;

    case 'animal_details':
        $controller = new AnimalController();
        $controller->details();
        break;

    case 'services':
        $controller = new ServiceController();
        $controller->index();
        break;

    case 'create_service':
        $controller = new ServiceController();
        $controller->create();
        break;

    case 'details_service':
        $controller = new ServiceController();
        $controller->details();
        break;

    case 'delete_service':
        $controller = new ServiceController(); 
        $controller->delete();                 
        break;

    case 'add_review':
        $controller = new ReviewController();
        $controller->add();
        break;

    case 'edit_profile':
        $controller = new UserController();
        $controller->edit();
        break;

    case 'admin':
    case 'admin_dashboard':
        $controller = new AdminController();
        $controller->dashboard();
        break;

    case 'admin_delete_user':
        $controller = new AdminController();
        $controller->deleteUser();
        break;

    case 'admin_delete_service':
        $controller = new AdminController();
        $controller->deleteService();
        break;

    case 'admin_delete_animal':
        $controller = new AdminController();
        $controller->deleteAnimal();
        break;

    case 'filter_animals':
        $controller = new AnimalController();
        $controller->filter();
        break;


    case 'filter_services':
        $controller = new ServiceController();
        $controller->filter();
        break;


    case 'home':
    default:
        require_once __DIR__ . '/../views/home/index.php';
        break;
}
