<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<h2 class="mb-4 text-primary"><i class="bi bi-plus-circle"></i> Добави нова услуга</h2>

<?php if (!empty($errors)) { ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error) { ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>

<div class="card shadow-sm">
    <div class="card-body">
        <!-- Формата за добавяне на услуга -->
        <form method="POST" action="" enctype="multipart/form-data" class="row g-3">

            <!-- Категория -->
            <div class="col-md-6">
                <label for="category" class="form-label">Категория:</label>
                <select name="category" id="category" class="form-select" required onchange="updateSubcategories()">
                    <option value="">-- Избери категория --</option>
                    <option value="Кучета">Кучета</option>
                    <option value="Котки">Котки</option>
                    <option value="Общи">Общи</option>
                </select>
            </div>

            <!-- Подкатегория (зависи от категорията) -->
            <div class="col-md-6">
                <label for="subcategory" class="form-label">Подкатегория:</label>
                <select name="subcategory" id="subcategory" class="form-select" required>
                    <option value="">-- Първо избери категория --</option>
                </select>
            </div>

            <!-- Локация (град от списък) -->
            <div class="col-md-6">
                <label for="location" class="form-label">Локация:</label>
                <select name="location" id="location" class="form-select" required>
                    <option value="">-- Избери град --</option>
                    <?php
                    $cities = [
                        "Благоевград", "Бургас", "Варна", "Велико Търново", "Видин", "Враца",
                        "Габрово", "Добрич", "Кърджали", "Кюстендил", "Ловеч", "Монтана",
                        "Пазарджик", "Перник", "Плевен", "Пловдив", "Разград", "Русе",
                        "Силистра", "Сливен", "Смолян", "София", "София област", "Стара Загора",
                        "Търговище", "Хасково", "Шумен", "Ямбол"
                    ];
    
                    foreach ($cities as $city) {
                        echo '<option value="' . htmlspecialchars($city) . '">' . htmlspecialchars($city) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Телефон за контакт -->
            <div class="col-md-6">
                <label for="phone" class="form-label">Телефон за контакт:</label>
                <input type="text" name="phone" id="phone" class="form-control" required pattern="[0-9]{8,15}" title="Телефонът трябва да е между 8 и 15 цифри.">
            </div>

            <!-- Цена и чекбокс "По договаряне" -->
            <div class="col-md-6">
                <label for="price" class="form-label">Цена:</label>
                <input type="number" name="price" id="price" step="0.01" min="0" class="form-control">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="negotiable" id="negotiable" value="1" onchange="togglePrice()">
                    <label class="form-check-label" for="negotiable">По договаряне</label>
                </div>
            </div>

            <!-- Снимка -->
            <div class="col-md-6">
                <label for="image" class="form-label">Снимка:</label>
                <input type="file" name="image" id="image" accept="image/jpeg, image/png" class="form-control">
            </div>

            <!-- Описание -->
            <div class="col-12">
                <label for="description" class="form-label">Описание:</label>
                <textarea name="description" id="description" class="form-control" rows="5"></textarea>
            </div>

            <!-- Бутон за създаване -->
            <div class="col-12">
                <button type="submit" name="create_service" class="btn btn-primary w-100">
                    <i class="bi bi-check-circle"></i> Създай услуга
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JS -->

<script>
    // Списък с подкатегории за всяка категория
    const subcategories = {
        "Кучета": ["Груминг", "Разходки", "Обучение", "Хотел"],
        "Котки": ["Груминг", "Хотел", "Ветеринар", "Транспорт"],
        "Общи": ["Ветеринар", "Транспорт", "Консултации", "Други"]
    };

    // Обновява списъка с подкатегории, когато потребителят избере категория
    function updateSubcategories() {
        const category = document.getElementById("category").value;
        const subSelect = document.getElementById("subcategory");
        subSelect.innerHTML = '<option value="">-- Избери подкатегория --</option>';

        if (subcategories[category]) {
            subcategories[category].forEach(function (sub) {
                const option = document.createElement("option");
                option.value = sub;
                option.text = sub;
                subSelect.appendChild(option);
            });
        }
    }

    // Деактивира полето за цена, ако е избрано "по договаряне"
    function togglePrice() {
        const checkbox = document.getElementById("negotiable");
        const priceInput = document.getElementById("price");

        if (checkbox.checked) {
            priceInput.value = "";      // Изчистваме полето
            priceInput.disabled = true; // Забраняваме редакция
        } else {
            priceInput.disabled = false;
        }
    }

    // Стартираме функциите при зареждане на страницата
    window.addEventListener("DOMContentLoaded", function () {
        updateSubcategories();
        togglePrice();
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

