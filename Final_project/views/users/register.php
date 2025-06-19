<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4 text-center">Регистрация</h2>

        <!-- 🔴 Показване на съобщения за грешка -->
        <?php
        if (!empty($error_message)) {
            echo '<div class="alert alert-danger">' . htmlentities($error_message) . '</div>';
        }

        if (!empty($success_message)) {
            echo '<div class="alert alert-success">' . htmlentities($success_message) . '</div>';
        }
        ?>

        <!-- ✅ Форма за регистрация -->
        <form method="POST" action="" class="border p-4 shadow rounded bg-white">

            <!-- Собствено име -->
            <div class="mb-3">
                <label for="first_name" class="form-label">Име:</label>
                <input type="text" name="first_name" id="first_name" class="form-control" required>
            </div>

            <!-- Фамилия -->
            <div class="mb-3">
                <label for="last_name" class="form-label">Фамилия:</label>
                <input type="text" name="last_name" id="last_name" class="form-control" required>
            </div>

            <!-- Потребителско име -->
            <div class="mb-3">
                <label for="username" class="form-label">Потребителско име:</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>

            <!-- Имейл -->
            <div class="mb-3">
                <label for="email" class="form-label">Имейл:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <!-- Парола -->
            <div class="mb-3">
                <label for="password" class="form-label">Парола:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <!-- Бутон -->
            <div class="d-grid mb-3">
                <button type="submit" name="register" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill me-2"></i> Регистрирай се
                </button>
            </div>

            <!-- Линк към вход -->
            <div class="text-center">
                <p>Вече имаш акаунт? <a href="index.php?page=login">Вход</a></p>
            </div>

        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
