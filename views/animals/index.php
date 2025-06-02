<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<h2 class="mb-4">Обяви за животни</h2>

<!-- Търсачка за животни-->
<div class="bg-white p-4 rounded shadow-sm mb-5 border">
    <form method="GET" action="index.php">
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
                    <option value="София">София</option>
                    <option value="Пловдив">Пловдив</option>
                    <option value="Варна">Варна</option>
                    <option value="Бургас">Бургас</option>
                    <option value="Русе">Русе</option>
                    <option value="Стара Загора">Стара Загора</option>
                    <option value="Плевен">Плевен</option>
                    <option value="Добрич">Добрич</option>
                    <option value="Сливен">Сливен</option>
                    <option value="Шумен">Шумен</option>
                    <option value="Перник">Перник</option>
                    <option value="Хасково">Хасково</option>
                    <option value="Ямбол">Ямбол</option>
                    <option value="Благоевград">Благоевград</option>
                    <option value="Враца">Враца</option>
                    <option value="Габрово">Габрово</option>
                    <option value="Казанлък">Казанлък</option>
                    <option value="Кюстендил">Кюстендил</option>
                    <option value="Монтана">Монтана</option>
                    <option value="Кърджали">Кърджали</option>
                    <option value="Видин">Видин</option>
                    <option value="Търговище">Търговище</option>
                    <option value="Разград">Разград</option>
                    <option value="Силистра">Силистра</option>
                    <option value="Ловеч">Ловеч</option>
                    <option value="Сандански">Сандански</option>
                    <option value="Пазарджик">Пазарджик</option>
                    <option value="Друг">Друг</option>
                </select>
            </div>

            <!-- Бутон -->
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
<?php if (!empty($animals)) { ?>
    <?php foreach ($animals as $animal) { ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <?php if (!empty($animal['image'])) { ?>
                    <img src="uploads/<?php echo htmlspecialchars($animal['image']); ?>" class="card-img-top" alt="Животно">
                <?php } ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($animal['type']); ?> - <?php echo htmlspecialchars($animal['breed']); ?></h5>
                    <p class="card-text">
                        <strong>Възраст:</strong> <?php echo (int)$animal['age']; ?> години<br>
                        <strong>Локация:</strong> <?php echo htmlspecialchars($animal['location']); ?>
                    </p>
                    <a href="index.php?page=animal_details&id=<?php echo $animal['id']; ?>" class="btn btn-outline-primary btn-sm">Виж детайли</a>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } else { ?>
    <p>Няма резултати.</p>
<?php } ?>
</div>

<!-- JavaScript за породите -->
<script>
const breedOptions = {
    "Кучета": ["Лабрадор ретривър", "Немска овчарка", "Голдън ретривър", "Френски булдог", "Булдог", "Пудел", "Бийгъл", "Ротвайлер", "Йоркширски териер", "Дакел"],
    "Котки": ["Мейн Куун", "Персийска котка", "Британска късокосместа", "Сиамска котка", "Шотландска клепоуха", "Бенгалска котка", "Руски син", "Сфинкс", "Рагдол", "Норвежка горска котка"],
    "Гризачи": ["Хамстер", "Морско свинче", "Плъх", "Мишка", "Чинчила"],
    "Птици": ["Папагал", "Канарче", "Амадина", "Какаду", "Екзотична птица"],
    "Риби": ["Сладководна", "Соленоводна"],
    "Влечуги и земноводни": ["Костенурка", "Гущер", "Змия", "Жаба"]
};

document.getElementById('categorySelect').addEventListener('change', function () {
    const category = this.value;
    const breedSelect = document.getElementById('breedSelect');
    breedSelect.innerHTML = '<option value="">Изберете</option>';

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
