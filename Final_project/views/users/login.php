<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4 text-center">Вход</h2>

        <!-- Показване на съобщение за грешка -->
        <?php
        if (!empty($error_message)) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            echo '<i class="bi bi-exclamation-triangle-fill"></i> ';
            echo htmlspecialchars($error_message);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Затвори"></button>';
            echo '</div>';
        }
        ?>

        <!-- Форма за вход -->
        <form method="POST" action="" class="border p-4 shadow rounded bg-white">

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

            <!-- Бутон за вход -->
            <div class="d-grid mb-3">
                <button type="submit" name="login" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Вход
                </button>
            </div>

            <!-- Линк към регистрация -->
            <div class="text-center">
                <p>Нямаш акаунт? <a href="index.php?page=register">Регистрирай се</a></p>
            </div>

        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
