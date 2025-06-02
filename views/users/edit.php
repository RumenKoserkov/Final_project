<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    die();
}

require_once __DIR__ . '/../layouts/header.php';
?>

<h2 class="mb-4"><i class="bi bi-person-circle"></i> Моят профил</h2>

<!-- Показваме текущите данни -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Информация за профила</h5>
        <p><strong>Име:</strong> <?php echo htmlspecialchars($userData['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
    </div>
</div>

<!-- Форма за редакция -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Редактирай профила</h5>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Ново име:</label>
                <input type="text" class="form-control" id="name" name="name"
                       value="<?php echo htmlspecialchars($userData['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Нов Email:</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?php echo htmlspecialchars($userData['email']); ?>" required>
            </div>

            <button type="submit" name="update_profile" class="btn btn-primary">Запази промените</button>
        </form>
    </div>
</div>


<h3 class="mb-3">🐾 Моите животни</h3>

<?php
$animalModel = new Animal();
$myAnimals = $animalModel->getByUserId($_SESSION['user_id']);

if (!empty($myAnimals)) { ?>
    <div class="row">
        <?php foreach ($myAnimals as $animal) { ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($animal['image'])) { ?>
                        <img src="uploads/<?php echo htmlspecialchars($animal['image']); ?>" class="card-img-top" alt="Снимка на животно">
                    <?php } ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($animal['type']) . " - " . htmlspecialchars($animal['breed']); ?></h5>
                        <p class="card-text">
                            Възраст: <?php echo (int)$animal['age']; ?> години<br>
                            Локация: <?php echo htmlspecialchars($animal['location']); ?>
                        </p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="index.php?page=animal_details&id=<?php echo $animal['id']; ?>" class="btn btn-sm btn-outline-primary">Детайли</a>
                        <a href="index.php?page=delete_animal&id=<?php echo $animal['id']; ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Сигурен ли си, че искаш да изтриеш това животно?');">Изтрий</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } else {
    echo "<p>Нямате добавени животни.</p>";
} ?>

<hr>



<h3 class="mb-3">Моите услуги</h3>

<?php
$serviceModel = new Service();
$myServices = $serviceModel->getByUserId($_SESSION['user_id']);

if (!empty($myServices)) { ?>
    <div class="row">
        <?php foreach ($myServices as $service) { ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($service['image'])) { ?>
                        <img src="uploads/<?php echo htmlspecialchars($service['image']); ?>" class="card-img-top" alt="Снимка на услуга">
                    <?php } ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($service['category']) . " - " . htmlspecialchars($service['subcategory']); ?></h5>
                        <p class="card-text">
                            Локация: <?php echo htmlspecialchars($service['location']); ?>
                        </p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="index.php?page=details_service&id=<?php echo $service['id']; ?>" class="btn btn-sm btn-outline-primary">Детайли</a>
                        <a href="index.php?page=delete_service&id=<?php echo $service['id']; ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Сигурен ли си, че искаш да изтриеш тази услуга?');">Изтрий</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } else {
    echo "<p>Нямате добавени услуги.</p>";
} ?>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
