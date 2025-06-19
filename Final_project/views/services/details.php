<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<h2 class="mb-4 text-primary"><i class="bi bi-tools"></i> Детайли за услугата</h2>

<?php
if ($service) {
?>

<!-- Основен контейнер -->
<div class="row mb-4">
    <!-- Лява колона със снимка -->
    <div class="col-md-5">
        <?php
        // Ако има снимка я показвам
        if (!empty($service['image'])) {
        ?>
            <img src="uploads/<?php echo htmlspecialchars($service['image']); ?>" class="img-fluid rounded shadow" alt="Снимка на услугата">
        <?php
        } else {
        ?>
            <div class="bg-light text-center py-5 border rounded">
                <i class="bi bi-image" style="font-size: 4rem;"></i><br>
                <span class="text-muted">Няма снимка</span>
            </div>
        <?php } ?>
    </div>

    <!-- Дясна колона с информация -->
    <div class="col-md-7">
        <ul class="list-group list-group-flush shadow-sm rounded">
            <!-- Категория -->
            <li class="list-group-item">
                <i class="bi bi-tags-fill text-primary"></i>
                <strong>Категория:</strong>
                <?php echo htmlspecialchars($service['category']); ?>
            </li>
            <!-- Подкатегория -->
            <li class="list-group-item">
                <i class="bi bi-gear-wide-connected text-primary"></i>
                <strong>Тип услуга:</strong>
                <?php echo htmlspecialchars($service['subcategory']); ?>
            </li>
            <!-- Локация -->
            <li class="list-group-item">
                <i class="bi bi-geo-alt-fill text-primary"></i>
                <strong>Локация:</strong>
                <?php echo htmlspecialchars($service['location']); ?>
            </li>
            <!-- Цена -->
            <li class="list-group-item">
                <i class="bi bi-cash text-primary"></i>
                <strong>Цена:</strong>
                <?php
                // Ако има цена показваме 
                if (!is_null($service['price'])) {
                    echo number_format($service['price'], 2) . ' лв.';
                    if (!empty($service['negotiable']) && $service['negotiable'] == 1) {
                        echo ' (по договаряне)';
                    }
                } else {
                    echo 'По договаряне';
                }
                ?>
            </li>
            <!-- Телефон – само за логнати потребители -->
            <li class="list-group-item">
                <i class="bi bi-telephone-fill text-primary"></i>
                <strong>Телефон:</strong>
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo htmlspecialchars($service['phone']);
                } else {
                    echo '<em>Видим само за регистрирани потребители</em>';
                }
                ?>
            </li>
            <!-- Посещения само ако има user_id и брояча е наличен -->
            <?php
            if (isset($_SESSION['user_id']) && isset($visitCount)) {
            ?>
                <li class="list-group-item">
                    <i class="bi bi-eye text-primary"></i>
                    <strong>Брой посещения:</strong>
                    <?php echo $visitCount; ?>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>

<!-- Описание -->
<div class="mb-5">
    <h5 class="text-primary"><i class="bi bi-info-circle-fill"></i> Описание</h5>
    <p class="bg-light p-3 rounded shadow-sm"><?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
</div>

<!-- Рейтинг -->
<?php
$reviewModel = new Review();
$avgRating = $reviewModel->getAverageRating($service['id']);
$totalReviews = $reviewModel->countReviews($service['id']);
?>

<div class="mb-5">
    <h5 class="text-primary"><i class="bi bi-star-fill"></i> Оценка</h5>
    <?php
    if ($totalReviews > 0) {
        // Ако има поне едно ревю – показваме средната оценка
        echo '<p><strong>Средна оценка:</strong> ' . $avgRating . ' от ' . $totalReviews . ' ревюта</p>';

        $fullStars = floor($avgRating);
        $halfStar = ($avgRating - $fullStars) >= 0.5;

        echo '<div class="mb-2">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $fullStars) {
                echo '<i class="bi bi-star-fill text-warning"></i>';
            } elseif ($halfStar && $i == $fullStars + 1) {
                echo '<i class="bi bi-star-half text-warning"></i>';
            } else {
                echo '<i class="bi bi-star text-warning"></i>';
            }
        }
        echo '</div>';
    } else {
        // Ако няма ревюта – съобщаваме
        echo '<p>Няма все още ревюта</p>';
    }
    ?>
</div>

<!-- Ревюта -->
<div class="mb-5">
    <h5 class="text-primary"><i class="bi bi-chat-dots-fill"></i> Ревюта</h5>
    <?php
    $reviews = $reviewModel->getByServiceId($service['id']);
    if (count($reviews) > 0) {
        foreach ($reviews as $review) {
    ?>
        <div class="border rounded p-3 mb-3">
            <strong><?php echo htmlspecialchars($review['author_name']); ?></strong>
            <span class="text-muted">(<?php echo (int)$review['rating']; ?> ⭐)</span><br>
            <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
        </div>
    <?php
        }
    } else {
        echo '<p>Все още няма ревюта.</p>';
    }
    ?>
</div>

<!-- Форма за ново ревю -->
<?php
if (isset($_SESSION['user_id'])) {
    $hasReviewed = $reviewModel->hasReviewed($_SESSION['user_id'], $service['id']);
    if (!$hasReviewed) {
?>
    <h5 class="text-primary"><i class="bi bi-pencil-fill"></i> Остави ревю</h5>
    <form method="POST" action="index.php?page=add_review" class="mb-5">
        <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">

        <div class="mb-3">
            <label for="rating" class="form-label">Оценка (1-5)</label>
            <select name="rating" id="rating" class="form-select" required>
                <option value="5">5 - Отлично</option>
                <option value="4">4 - Много добро</option>
                <option value="3">3 - Добро</option>
                <option value="2">2 - Лошо</option>
                <option value="1">1 - Много лошо</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">Коментар</label>
            <textarea name="comment" id="comment" class="form-control" rows="4" required></textarea>
        </div>

        <button type="submit" name="add_review" class="btn btn-primary">Изпрати</button>
    </form>
<?php
    }
}
?>

<?php
} else {
    echo '<div class="alert alert-warning">Услугата не е намерена.</div>';
}
?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
