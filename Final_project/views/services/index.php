<?php require_once __DIR__ . '/../layouts/header.php'; ?> <!-- Зареждаме хедъра -->

<div class="container py-4">
    <!-- Заглавие -->
    <h2 class="mb-4 text-primary"><i class="bi bi-search"></i> Търсене на услуги</h2>

    <!-- Форма за филтриране -->
    <form method="GET" action="index.php" class="row g-3 bg-light p-3 rounded shadow-sm mb-5">
        <input type="hidden" name="page" value="filter_services"> <!-- Скрита стойност за страницата -->

        <!-- Категория -->
        <div class="col-md-4">
            <label for="categorySelect" class="form-label">Категория:</label>
            <select name="category" id="categorySelect" class="form-select" onchange="updateSubcategories()">
                <option value="">-- Всички категории --</option>
                <option value="Кучета">Кучета</option>
                <option value="Котки">Котки</option>
                <option value="Общи">Общи</option>
            </select>
        </div>

        <!-- Подкатегория -->
        <div class="col-md-4">
            <label for="subcategorySelect" class="form-label">Подкатегория:</label>
            <select name="subcategory" id="subcategorySelect" class="form-select">
                <option value="">-- Всички подкатегории --</option>
            </select>
        </div>

        <!-- Локация -->
        <div class="col-md-4">
            <label for="location" class="form-label">Локация:</label>
            <select name="location" id="location" class="form-select">
                <option value="">-- Всички градове --</option>
                <?php
                // Статичен списък с градове
                $cities = [
                    "Благоевград", "Бургас", "Варна", "Велико Търново", "Видин", "Враца",
                    "Габрово", "Добрич", "Кърджали", "Кюстендил", "Ловеч", "Монтана",
                    "Пазарджик", "Перник", "Плевен", "Пловдив", "Разград", "Русе",
                    "Силистра", "Сливен", "Смолян", "София", "София област", "Стара Загора",
                    "Търговище", "Хасково", "Шумен", "Ямбол"
                ];
                foreach ($cities as $city) {
                    echo '<option value="' . $city . '">' . $city . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Бутон за търсене -->
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Търси
            </button>
        </div>
    </form>

    <!-- Карти с намерените услуги -->
    <div class="row g-4">
        <?php
        if (!empty($services)) {
            foreach ($services as $service) {
                echo '<div class="col-md-4">';
                echo '<div class="card h-100 shadow-sm">';

                // Снимка (ако има)
                if (!empty($service['image'])) {
                    echo '<img src="uploads/' . htmlspecialchars($service['image']) . '" class="card-img-top" alt="Снимка на услуга">';
                }

                echo '<div class="card-body">';
                echo '<h5 class="card-title text-primary">' . htmlspecialchars($service['subcategory']) . '</h5>';
                echo '<p class="card-text">' . htmlspecialchars($service['location']) . '</p>';

                // Цена
                echo '<p class="card-text fw-bold">';
                if (!is_null($service['price'])) {
                    echo number_format($service['price'], 2) . ' лв.';
                    if ($service['negotiable'] == 1) {
                        echo ' (по договаряне)';
                    }
                } else {
                    echo 'По договаряне';
                }
                echo '</p>';

                // Бутон за детайли
                echo '<a href="index.php?page=details_service&id=' . $service['id'] . '" class="btn btn-outline-primary w-100">Виж повече</a>';

                echo '</div></div></div>';
            }
        } else {
            // Ако няма резултати
            echo '<div class="col-12"><div class="alert alert-info text-center">Няма намерени услуги.</div></div>';
        }
        ?>
    </div>

    <!-- Странициране -->
    <div class="mt-4">
        <?php
        if (isset($currentPage) && isset($totalPages) && $totalPages > 1) {
            echo '<nav><ul class="pagination justify-content-center">';
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<li class="page-item';
                if ($i == $currentPage) {
                    echo ' active';
                }
                echo '"><a class="page-link" href="index.php?page=services&p=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul></nav>';
        }
        ?>
    </div>
</div>

<!-- Скрипт за динамични подкатегории -->
<script>
    // Обект със стойности за подкатегории по категория
    const subcategoryOptions = {
        "Кучета": ["Груминг", "Разходки", "Обучение", "Хотел"],
        "Котки": ["Груминг", "Хотел", "Ветеринар", "Транспорт"],
        "Общи": ["Ветеринар", "Транспорт", "Консултации", "Други"]
    };

    // Обновяване на подкатегориите спрямо избрана категория
    function updateSubcategories() {
        const category = document.getElementById("categorySelect").value;
        const subSelect = document.getElementById("subcategorySelect");

        // Изчистваме текущите опции
        subSelect.innerHTML = '<option value="">-- Всички подкатегории --</option>';

        // Добавяме нови опции, ако съществуват
        if (subcategoryOptions[category]) {
            subcategoryOptions[category].forEach(function (sub) {
                const option = document.createElement("option");
                option.value = sub;
                option.text = sub;
                subSelect.appendChild(option);
            });
        }
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> <!-- Зареждаме футъра -->
