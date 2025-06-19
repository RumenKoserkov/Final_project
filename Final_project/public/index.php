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

$page = 'home';

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

switch ($page) {

    // Регистрация
    case 'register':
        $controller = new UserController();
        $controller->register();
        break;

    // Вход
    case 'login':
        $controller = new UserController();
        $controller->login();
        break;

    // Изход
    case 'logout':
        $controller = new UserController();
        $controller->logout();
        break;

    // Всички животни
    case 'animals':
        $controller = new AnimalController();
        $controller->index();
        break;

    // Създаване на животинска обява
    case 'create_animal':
        $controller = new AnimalController();
        $controller->create();
        break;

    // Редакция на животинска обява
    case 'edit_animal':
        $controller = new AnimalController();
        $controller->edit();
        break;

    // Изтриване на животинска обява
    case 'delete_animal':
        $controller = new AnimalController();
        $controller->delete();
        break;

    // Детайли за животно
    case 'animal_details':
        $controller = new AnimalController();
        $controller->details();
        break;

    // Всички услуги
    case 'services':
        $controller = new ServiceController();
        $controller->index();
        break;

    // Създаване на услуга
    case 'create_service':
        $controller = new ServiceController();
        $controller->create();
        break;

    // Детайли за услуга
    case 'details_service':
        $controller = new ServiceController();
        $controller->details();
        break;

    // Редакция на услуга
    case 'edit_service':
        $controller = new ServiceController();
        $controller->edit();
        break;

    // Изтриване на услуга
    case 'delete_service':
        $controller = new ServiceController();
        $controller->delete();
        break;

    // Добавяне на ревю към услуга
    case 'add_review':
        $controller = new ReviewController();
        $controller->add();
        break;

    // Редакция на потребителски профил
    case 'edit_profile':
        $controller = new UserController();
        $controller->edit();
        break;

    // Достъп до админ панела
    case 'admin':
    case 'admin_dashboard':
        $controller = new AdminController();
        $controller->dashboard();
        break;

    // Изтриване на потребител от админ
    case 'admin_delete_user':
        $controller = new AdminController();
        $controller->deleteUser();
        break;

    // Изтриване на услуга от админ
    case 'admin_delete_service':
        $controller = new AdminController();
        $controller->deleteService();
        break;

    // Изтриване на животно от админ
    case 'admin_delete_animal':
        $controller = new AdminController();
        $controller->deleteAnimal();
        break;

    // Филтриране на животни
    case 'filter_animals':
        $controller = new AnimalController();
        $controller->filter();
        break;

    // Филтриране на услуги
    case 'filter_services':
        $controller = new ServiceController();
        $controller->filter();
        break;

    // Начална страница
    case 'home':
        require_once __DIR__ . '/../views/home/index.php';
        break;

    // Ако стойността на page не съвпада с никой от горните – зареждаме страница 404
    default:
        require_once __DIR__ . '/../views/errors/404.php';
        break;
}
