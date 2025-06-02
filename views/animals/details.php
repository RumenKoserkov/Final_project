<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
    <?php if ($animal) { ?>
        <div class="row">
            <!-- Снимка -->
            <div class="col-12 col-md-5 mb-4">
                <?php if (!empty($animal['image'])) { ?>
                    <img src="uploads/<?php echo htmlspecialchars($animal['image']); ?>" class="img-fluid rounded shadow" alt="Снимка на животно">
                <?php } ?>
            </div>

            <!-- Информация -->
            <div class="col-12 col-md-7">
                <h2 class="mb-3 text-primary">
                    <?php echo htmlspecialchars($animal['type']) . ' - ' . htmlspecialchars($animal['breed']); ?>
                </h2>

                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><strong>Възраст:</strong> <?php echo $animal['age']; ?> години</li>
                    <li class="list-group-item"><strong>Локация:</strong> <?php echo htmlspecialchars($animal['location']); ?></li>
                    <li class="list-group-item">
                        <strong>Цена:</strong>
                        <?php if (!is_null($animal['price'])) { ?>
                            <?php echo number_format($animal['price'], 2); ?> лв.
                            <?php if ($animal['negotiable']) { echo '(по договаряне)'; } ?>
                        <?php } else { ?>
                            По договаряне
                        <?php } ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Телефон за контакт:</strong>
                        <?php if (isset($_SESSION['user_id'])) {
                            echo htmlspecialchars($animal['phone']);
                        } else {
                            echo '<em class="text-muted">Видим само за регистрирани потребители</em>';
                        } ?>
                    </li>
                </ul>

                <div>
                    <h5 class="fw-bold">Описание</h5>
                    <p><?php echo nl2br(htmlspecialchars($animal['description'])); ?></p>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning">Обявата не е намерена.</div>
    <?php } ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
