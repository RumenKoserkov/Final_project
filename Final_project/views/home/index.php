<?php
require_once __DIR__ . '/../layouts/header.php';

$animalModel = new Animal();
$latestAnimals = $animalModel->getLatest();

$serviceModel = new Service();
$latestServices = $serviceModel->getLatest();
?>

<!-- Hero секция – представяне -->
<div class="bg-light rounded shadow-sm mb-5 p-4 text-center">
    <!-- Снимка -->
    <img src="assets/hero-animals.jpg" alt="Домашни любимци" class="img-fluid rounded mb-4" style="max-height: 400px; object-fit: cover; width: 100%;">
    <!-- Заглавие и описание -->
    <h1 class="text-primary fw-bold">Добре дошли в Pet Marketplace</h1>
    <p class="lead mt-3">
        Вашето място за осиновяване, купуване и продажба на домашни любимци, както и намиране на услуги като ветеринар, груминг и разходки.
        Разгледайте обявите или създайте своя – лесно, бързо и напълно безплатно!
    </p>
</div>

<!-- Търсачка -->
<div class="bg-white p-4 rounded shadow-sm mb-5 border">
    <form method="GET" action="index.php">
        <!-- Скрито поле, което казва коя страница искаме -->
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

            <!-- Порода с JS) -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold">Порода</label>
                <select name="breed" class="form-select" id="breedSelect">
                    <option value="">Изберете категория първо</option>
                </select>
            </div>

            <!-- Бутон за търсене -->
            <div class="col-12 col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Търси
                </button>
            </div>
        </div>
    </form>
</div>

<!-- 🐶 Последно добавени животни -->
<h3 class="mb-3 text-primary"><i class="bi bi-paw-fill"></i> Последно добавени животни</h3>
<div class="row g-4 mb-5">
    <?php foreach ($latestAnimals as $animal) { ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <!-- Снимка на животното -->
                <img src="uploads/<?php echo htmlspecialchars($animal['image']); ?>" class="card-img-top" alt="Животно">
                <div class="card-body">
                    <!-- Заглавие -->
                    <h5 class="card-title">
                        <?php echo htmlspecialchars($animal['type']) . ' - ' . htmlspecialchars($animal['breed']); ?>
                    </h5>
                    <!-- Кратко описание -->
                    <p class="card-text">
                        <?php echo htmlspecialchars($animal['description']); ?>
                    </p>
                    <!-- Бутон за детайли -->
                    <a href="index.php?page=animal_details&id=<?php echo $animal['id']; ?>" class="btn btn-outline-primary w-100">
                        <i class="bi bi-info-circle"></i> Детайли
                    </a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<!-- Последно добавени услуги -->
<h3 class="mb-3 text-primary"><i class="bi bi-tools"></i> Последно добавени услуги</h3>
<div class="row g-4 mb-5">
    <?php foreach ($latestServices as $service) { ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <!-- Снимка на услугата -->
                <img src="uploads/<?php echo htmlspecialchars($service['image']); ?>" class="card-img-top" alt="Услуга">
                <div class="card-body">
                    <!-- Заглавие -->
                    <h5 class="card-title">
                        <?php echo htmlspecialchars($service['category']) . ' - ' . htmlspecialchars($service['subcategory']); ?>
                    </h5>
                    <!-- Кратко описание -->
                    <p class="card-text">
                        <?php echo htmlspecialchars($service['description']); ?>
                    </p>
                    <!-- Бутон за детайли -->
                    <a href="index.php?page=details_service&id=<?php echo $service['id']; ?>" class="btn btn-outline-primary w-100">
                        <i class="bi bi-info-circle"></i> Детайли
                    </a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<!-- JavaScript за динамично зареждане на породите -->
<script>
const breedOptions = {
    "Кучета": ["Лабрадор ретривър", "Немска овчарка", "Голдън ретривър", "Френски булдог", "Булдог", "Пудел", "Бийгъл", "Ротвайлер", "Йоркширски териер", "Дакел"],
    "Котки": ["Мейн Куун", "Персийска котка", "Британска късокосместа", "Сиамска котка", "Шотландска клепоуха", "Бенгалска котка", "Руски син", "Сфинкс", "Рагдол", "Норвежка горска котка"],
    "Гризачи": ["Хамстер", "Морско свинче", "Плъх", "Мишка", "Чинчила"],
    "Птици": ["Папагал", "Канарче", "Амадина", "Какаду", "Екзотична птица"],
    "Риби": ["Сладководна", "Соленоводна"],
    "Влечуги и земноводни": ["Костенурка", "Гущер", "Змия", "Жаба"]
};

// При смяна на категорията се зареждат съответните породи
document.getElementById('categorySelect').addEventListener('change', function () {
    const category = this.value;
    const breedSelect = document.getElementById('breedSelect');

    // Изчистваме текущите опции
    breedSelect.innerHTML = '<option value="">Изберете</option>';

    // Ако има породи за избраната категория, ги добавяме
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
