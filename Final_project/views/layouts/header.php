<!DOCTYPE html> 
<html>
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">


</head>
<body>

<!--НАВИГАЦИЯ -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container"> <!-- Контейнер, който центрира менюто -->

        <!-- Лого с икона и заглавие -->
        <a class="navbar-brand" href="index.php?page=home">
            <i class="bi bi-house-door-fill"></i>
            Pet Marketplace
        </a>

        <!-- Бутон за мобилна навигация (работи при малки екрани) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Основна навигация -->
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto"> <!-- ms-auto = бута менюто вдясно -->

                <li class="nav-item"><a class="nav-link" href="index.php?page=home">Начало</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=animals">Животни</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=services">Услуги</a></li>

                <!--  Ако потребителят е логнат -->
                <?php if (isset($_SESSION['user_id'])) { ?>
                    
                    <!-- Линкове за публикуване -->
                    <li class="nav-item"><a class="nav-link" href="index.php?page=create_animal">Добави животно</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=create_service">Добави услуга</a></li>

                    <!-- Ако потребителят е админ – показваме бутон за админ панел -->
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') { ?>
                        <li class="nav-item">
                            <a class="nav-link text-danger fw-bold" href="index.php?page=admin_dashboard">
                                <i class="bi bi-shield-lock-fill"></i> Админ панел
                            </a>
                        </li>
                    <?php } ?>

                    <!-- Линкове за профил и изход -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=edit_profile">
                            <i class="bi bi-person-circle"></i> Профил
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=logout">
                            <i class="bi bi-box-arrow-right"></i> Изход
                        </a>
                    </li>

                <?php } else { ?>
                    <!-- Ако потребителят не е логнат -->
                    <li class="nav-item"><a class="nav-link" href="index.php?page=register">Регистрация</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=login">Вход</a></li>
                <?php } ?>

            </ul>
        </div>
    </div>
</nav>

<!-- Главен контейнер за съдържание на страницата -->
<div class="container mt-4">
