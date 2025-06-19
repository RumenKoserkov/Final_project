<?php
// Ако потребителят не е логнат, го пренасочваме към login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    die();
}

// Включваме header
require_once __DIR__ . '/../layouts/header.php';
?>

<h2 class="mb-4"><i class="bi bi-person-circle"></i> Моят профил</h2>

<!-- ✅ Съобщения -->
<?php
if (!empty($error_message)) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>';
}

if (!empty($success_message)) {
    echo '<div class="alert alert-success">' . htmlspecialchars($success_message) . '</div>';
}
?>

<!-- 👤 Профилна информация -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Информация за профила</h5>
        <p><strong>Потребителско име:</strong> <?php echo htmlspecialchars($userData['username']); ?></p>
        <p><strong>Име:</strong> <?php echo htmlspecialchars($userData['first_name']); ?></p>
        <p><strong>Фамилия:</strong> <?php echo htmlspecialchars($userData['last_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
    </div>
</div>

<!-- ✏️ Форма за редакция на имена -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Редактирай профила</h5>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Потребителско име:</label>
                <input type="text" class="form-control" id="username" name="username"
                       value="<?php echo htmlspecialchars($userData['username']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="first_name" class="form-label">Име:</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                       value="<?php echo htmlspecialchars($userData['first_name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Фамилия:</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                       value="<?php echo htmlspecialchars($userData['last_name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email (неподлежащ на редакция):</label>
                <input type="email" class="form-control" id="email"
                       value="<?php echo htmlspecialchars($userData['email']); ?>" readonly>
            </div>

            <button type="submit" name="update_profile" class="btn btn-primary">Запази промените</button>
        </form>
    </div>
</div>

<!-- 🔐 Смяна на парола -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Смени паролата</h5>
        <form method="POST">
            <div class="mb-3">
                <label for="current_password" class="form-label">Текуща парола:</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">Нова парола:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Потвърди новата парола:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" name="change_password" class="btn btn-warning">Запази новата парола</button>
        </form>
    </div>
</div>

<!-- 🐾 Моите обяви за животни -->
<?php if (!empty($userAnimals)) { ?>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Моите обяви за животни</h5>
            <?php foreach ($userAnimals as $animal) { ?>
                <div class="border rounded p-3 mb-3 bg-light">
                    <strong><?php echo htmlspecialchars($animal['type']) . ' - ' . htmlspecialchars($animal['breed']); ?></strong><br>
                    <small>Град: <?php echo htmlspecialchars($animal['location']); ?> | Възраст: <?php echo (int)$animal['age']; ?> г.</small><br>
                    <div class="mt-2">
                        <a href="index.php?page=edit_animal&id=<?php echo $animal['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil-square"></i> Редактирай
                        </a>

                        <!-- ✅ Изтриване с POST -->
                        <form method="POST" action="index.php?page=delete_animal" style="display:inline;"
                              onsubmit="return confirm('Сигурни ли сте, че искате да изтриете тази обява?');">
                            <input type="hidden" name="id" value="<?php echo $animal['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Изтрий
                            </button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<!-- 🛠️ Моите обяви за услуги -->
<?php if (!empty($userServices)) { ?>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Моите обяви за услуги</h5>
            <?php foreach ($userServices as $service) { ?>
                <div class="border rounded p-3 mb-3 bg-light">
                    <strong><?php echo htmlspecialchars($service['category']) . ' - ' . htmlspecialchars($service['subcategory']); ?></strong><br>
                    <small>Град: <?php echo htmlspecialchars($service['location']); ?> | Цена:
                        <?php
                        if ($service['negotiable']) {
                            echo 'По договаряне';
                        } else {
                            echo number_format($service['price'], 2) . ' лв.';
                        }
                        ?>
                    </small><br>
                    <div class="mt-2">
                        <a href="index.php?page=edit_service&id=<?php echo $service['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil-square"></i> Редактирай
                        </a>

                        <!-- ✅ Изтриване с POST -->
                        <form method="POST" action="index.php?page=delete_service" style="display:inline;"
                              onsubmit="return confirm('Сигурни ли сте, че искате да изтриете тази услуга?');">
                            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Изтрий
                            </button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<?php
// Включваме footer
require_once __DIR__ . '/../layouts/footer.php';
?>
