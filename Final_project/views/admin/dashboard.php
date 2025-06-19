<?php 
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
    <h2 class="mb-5 text-primary"><i class="bi bi-shield-lock"></i> Админ табло</h2>

    <?php 
    $adminId = null;
    if (isset($_SESSION['user_id'])) {
        $adminId = $_SESSION['user_id'];
    }
    ?>

    <!-- Потребители -->
    <h3 class="text-success"><i class="bi bi-people"></i> Потребители</h3>
    <div class="row g-4 mb-5">
        <?php 
        if (!empty($users)) {
            foreach ($users as $user) {
                echo '<div class="col-md-4">';
                echo '<div class="card shadow-sm h-100">';
                echo '<div class="card-body">';

                if (isset($user['first_name']) && isset($user['last_name'])) {
                    echo '<h5 class="card-title">' . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . '</h5>';
                } else {
                    echo '<h5 class="card-title text-muted">Без име</h5>';
                }

                echo '<p class="card-text mb-1"><strong>Email:</strong> ' . htmlspecialchars($user['email']) . '</p>';
                echo '<p class="card-text mb-2"><strong>Роля:</strong> ' . htmlspecialchars($user['role']) . '</p>';

                if ($user['id'] != $adminId) {
                    echo '<form method="POST" action="index.php?page=admin_delete_user" onsubmit="return confirm(\'Сигурен ли си, че искаш да изтриеш този потребител?\');">';
                    echo '<input type="hidden" name="id" value="' . $user['id'] . '">';
                    echo '<button type="submit" class="btn btn-sm btn-danger w-100"><i class="bi bi-trash"></i> Изтрий</button>';
                    echo '</form>';
                } else {
                    echo '<span class="text-muted">(Това е твоят акаунт)</span>';
                }

                echo '</div></div></div>';
            }
        } else {
            echo '<p class="text-muted">Няма потребители.</p>';
        }
        ?>
    </div>

    <!-- Услуги -->
    <h3 class="text-info"><i class="bi bi-briefcase"></i> Услуги</h3>
    <div class="row g-4 mb-5">
        <?php 
        if (!empty($services)) {
            foreach ($services as $service) {
                echo '<div class="col-md-4">';
                echo '<div class="card shadow-sm h-100">';
                echo '<div class="card-body">';

                echo '<h5 class="card-title">' . htmlspecialchars($service['subcategory']) . '</h5>';
                echo '<p class="card-text mb-1"><strong>Категория:</strong> ' . htmlspecialchars($service['category']) . '</p>';
                echo '<p class="card-text mb-1"><strong>Локация:</strong> ' . htmlspecialchars($service['location']) . '</p>';
                echo '<p class="card-text mb-2"><strong>Потребител ID:</strong> ' . $service['user_id'] . '</p>';

                echo '<form method="POST" action="index.php?page=admin_delete_service" onsubmit="return confirm(\'Сигурен ли си, че искаш да изтриеш тази услуга?\');">';
                echo '<input type="hidden" name="id" value="' . $service['id'] . '">';
                echo '<button type="submit" class="btn btn-sm btn-danger w-100"><i class="bi bi-trash"></i> Изтрий</button>';
                echo '</form>';

                echo '</div></div></div>';
            }
        } else {
            echo '<p class="text-muted">Няма добавени услуги.</p>';
        }
        ?>
    </div>

    <!-- Животни -->
    <h3 class="text-warning"><i class="bi bi-paw"></i> Животни</h3>
    <div class="row g-4 mb-5">
        <?php 
        if (!empty($animals)) {
            foreach ($animals as $animal) {
                echo '<div class="col-md-4">';
                echo '<div class="card shadow-sm h-100">';
                echo '<div class="card-body">';

                echo '<h5 class="card-title">' . htmlspecialchars($animal['breed']) . '</h5>';
                echo '<p class="card-text mb-1"><strong>Тип:</strong> ' . htmlspecialchars($animal['type']) . '</p>';
                echo '<p class="card-text mb-1"><strong>Локация:</strong> ' . htmlspecialchars($animal['location']) . '</p>';
                echo '<p class="card-text mb-2"><strong>Потребител ID:</strong> ' . $animal['user_id'] . '</p>';

                echo '<form method="POST" action="index.php?page=admin_delete_animal" onsubmit="return confirm(\'Сигурен ли си, че искаш да изтриеш това животно?\');">';
                echo '<input type="hidden" name="id" value="' . $animal['id'] . '">';
                echo '<button type="submit" class="btn btn-sm btn-danger w-100"><i class="bi bi-trash"></i> Изтрий</button>';
                echo '</form>';

                echo '</div></div></div>';
            }
        } else {
            echo '<p class="text-muted">Няма добавени животни.</p>';
        }
        ?>
    </div>
</div>

<?php 
require_once __DIR__ . '/../layouts/footer.php'; 
?>
