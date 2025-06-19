<?php
// Включваме хедъра на сайта
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Основен контейнер -->
<h2 class="mb-4">Обяви за животни</h2>

<!-- Търсачка за животни -->
<div class="bg-white p-4 rounded shadow-sm mb-5 border">
    <form method="GET" action="index.php">
        <!-- Скрито поле за да запазим page параметъра -->
        <input type="hidden" name="page" value="filter_animals">
        <div class="row g-3 align-items-end">
            <!-- Категория животно -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Категория животно</label>
                <select name="type" class="form-select" id="categorySelect" required>
                    <option value="">Изберете</option>
                    <option value="Кучета">Кучета</option>
                    <option value="Котки">Котки</option>
                    <option value="Гризачи">Гризачи</option>
                    <option value="Птици">Птици</option>
                    <option value="Риби">Риби</option>
                    <option value="Влечуги и земноводни">Влечуги и земноводни</option>
                </select>
            </div>

            <!-- Порода -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Порода</label>
                <select name="breed" class="form-select" id="breedSelect">
                    <option value="">Изберете категория първо</option>
                </select>
            </div>

            <!-- Локация -->
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Локация</label>
                <select name="location" class="form-select">
                    <option value="">-- Избери град --</option>
                    <!-- Списък с всички областни градове -->
                    <?php
                    $cities = ["София", "Пловдив", "Варна", "Бургас", "Русе", "Стара Загора", "Плевен", "Добрич", "Сливен", "Шумен", "Перник", "Хасково", "Ямбол", "Благоевград", "Враца", "Габрово", "Казанлък", "Кюстендил", "Монтана", "Кърджали", "Видин", "Търговище", "Разград", "Силистра", "Ловеч", "Сандански", "Пазарджик", "Друг"];
                    foreach ($cities as $city) {
                        echo '<option value="' . htmlspecialchars($city) . '">' . htmlspecialchars($city) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Бутон за търсене -->
            <div class="col-12 col-md-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Търси
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Карти с животни -->
<div class="row">
<?php
// Проверка дали има резултати от търсенето
if (!empty($animals)) {
    foreach ($animals as $animal) {
?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <?php
                // Ако има снимка на животното, я показваме
                if (!empty($animal['image'])) {
                    echo '<img src="uploads/' . htmlspecialchars($animal['image']) . '" class="card-img-top" alt="Животно">';
                }
                ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($animal['type']) . ' - ' . htmlspecialchars($animal['breed']); ?></h5>
                    <p class="card-text">
                        <strong>Възраст:</strong> <?php echo (int)$animal['age']; ?> години<br>
                        <strong>Локация:</strong> <?php echo htmlspecialchars($animal['location']); ?>
                    </p>
                    <a href="index.php?page=animal_details&id=<?php echo $animal['id']; ?>" class="btn btn-outline-primary btn-sm">Виж детайли</a>
                </div>
            </div>
        </div>
<?php
    }
} else {
    // Ако няма животни, показвам съобщение
    echo '<p>Няма резултати.</p>';
}
?>
</div>

<!-- Странициране -->
<?php
if (isset($totalPages) && $totalPages > 1) {
    echo '<nav aria-label="Пагинация">';
    echo '<ul class="pagination justify-content-center mt-4">';

    // Предишна страница
    if (isset($page) && $page > 1) {
        echo '<li class="page-item">';
        echo '<a class="page-link" href="index.php?page=animals&p=' . ($page - 1) . '">Назад</a>';
        echo '</li>';
    }

    // Номера на страниците
    for ($i = 1; $i <= $totalPages; $i++) {
        if (isset($page) && $i == $page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="index.php?page=animals&p=' . $i . '">' . $i . '</a></li>';
        }
    }

    // Следваща страница
    if (isset($page) && $page < $totalPages) {
        echo '<li class="page-item">';
        echo '<a class="page-link" href="index.php?page=animals&p=' . ($page + 1) . '">Напред</a>';
        echo '</li>';
    }

    echo '</ul>';
    echo '</nav>';
}
?>

<!-- JS за автоматично попълване на породите -->
<script>
// Обект със списъци на породи за всяка категория
const breedOptions = {
    "Кучета": ["Лабрадор ретривър", "Немска овчарка", "Голдън ретривър", "Френски булдог", "Булдог", "Пудел", "Бийгъл", "Ротвайлер", "Йоркширски териер", "Дакел"],
    "Котки": ["Мейн Куун", "Персийска котка", "Британска късокосместа", "Сиамска котка", "Шотландска клепоуха", "Бенгалска котка", "Руски син", "Сфинкс", "Рагдол", "Норвежка горска котка"],
    "Гризачи": ["Хамстер", "Морско свинче", "Плъх", "Мишка", "Чинчила"],
    "Птици": ["Папагал", "Канарче", "Амадина", "Какаду", "Екзотична птица"],
    "Риби": ["Сладководна", "Соленоводна"],
    "Влечуги и земноводни": ["Костенурка", "Гущер", "Змия", "Жаба"]
};

// При промяна на категорията се актуализират породите
document.getElementById('categorySelect').addEventListener('change', function () {
    const category = this.value;
    const breedSelect = document.getElementById('breedSelect');

    // Изчистване на текущите опции
    breedSelect.innerHTML = '<option value="">Изберете</option>';

    // Добавяне на породите за избраната категория
    if (breedOptions[category]) {
        breedOptions[category].forEach(function(breed) {
            const option = document.createElement('option');
            option.value = breed;
            option.textContent = breed;
            breedSelect.appendChild(option);
        });
    } else {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'Няма налични породи';
        breedSelect.appendChild(option);
    }
});
</script>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
