<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
    <?php 
    // Ако обявата е намерена
    if ($animal) { 
    ?>
        <div class="row">

            <!-- Снимка на животното -->
            <div class="col-12 col-md-5 mb-4">
                <?php 
                if (!empty($animal['image'])) { 
                ?>
                    <img 
                        src="uploads/<?php echo htmlspecialchars($animal['image']); ?>" 
                        class="img-fluid rounded shadow" 
                        alt="Снимка на животно"
                    >
                <?php 
                } 
                ?>
            </div>

            <!-- Информация за животното -->
            <div class="col-12 col-md-7">
                <h2 class="mb-3 text-primary">
                    <?php 
                    // Извеждам типа и породата, напр. "Куче - Голдън ретривър"
                    echo htmlspecialchars($animal['type']) . ' - ' . htmlspecialchars($animal['breed']); 
                    ?>
                </h2>

                <!-- Списък с допълнителни данни -->
                <ul class="list-group list-group-flush mb-3">
                    <!-- Възраст -->
                    <li class="list-group-item">
                        <strong>Възраст:</strong> <?php echo $animal['age']; ?> години
                    </li>

                    <!-- Локация -->
                    <li class="list-group-item">
                        <strong>Локация:</strong> <?php echo htmlspecialchars($animal['location']); ?>
                    </li>

                    <!-- Цена -->
                    <li class="list-group-item">
                        <strong>Цена:</strong>
                        <?php 
                        // Ако има зададена цена
                        if (!is_null($animal['price'])) { 
                            echo number_format($animal['price'], 2); // Форматирана цена

                            // Ако е отбелязано "по договаряне", добавям текста
                            if ($animal['negotiable']) {
                                echo ' (по договаряне)';
                            }
                        } else {
                            // Ако няма цена, показвам само "по договаряне"
                            echo 'По договаряне';
                        }
                        ?>
                    </li>

                    <!-- Телефон за контакт -->
                    <li class="list-group-item">
                        <strong>Телефон за контакт:</strong>
                        <?php 
                        // Ако потребителят е логнат – показвам телефона
                        if (isset($_SESSION['user_id'])) {
                            echo htmlspecialchars($animal['phone']);
                        } else {
                            // Иначе показвам, че се вижда само от регистрирани
                            echo '<em class="text-muted">Видим само за регистрирани потребители</em>';
                        }
                        ?>
                    </li>
                </ul>

                <!-- Описание -->
                <div>
                    <h5 class="fw-bold">Описание</h5>
                    <p>
                        <?php 
                        // Показвам описанието, като запазваме новите редове
                        echo nl2br(htmlspecialchars($animal['description'])); 
                        ?>
                    </p>
                </div>
            </div>
        </div>
    <?php 
    } else { 
        // Ако животното не е намерено
    ?>
        <div class="alert alert-warning">Обявата не е намерена.</div>
    <?php 
    } 
    ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

