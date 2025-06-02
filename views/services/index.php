<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4 text-primary"><i class="bi bi-search"></i> Търсене на услуги</h2>

    <form method="GET" action="index.php" class="row g-3 bg-light p-3 rounded shadow-sm mb-5">
        
      
        <input type="hidden" name="page" value="filter_services">

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

    <!-- Показване на услугите -->
    <div class="row g-4">
        <?php if (!empty($services)) {
            foreach ($services as $service) { ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($service['image'])) { ?>
                            <img src="uploads/<?php echo htmlspecialchars($service['image']); ?>" class="card-img-top" alt="Снимка на услуга">
                        <?php } ?>
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?php echo htmlspecialchars($service['subcategory']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($service['location']); ?></p>
                            <p class="card-text fw-bold">
                                <?php
                                if (!is_null($service['price'])) {
                                    echo number_format($service['price'], 2) . ' лв.';
                                    if ($service['negotiable']) {
                                        echo ' (по договаряне)';
                                    }
                                } else {
                                    echo 'По договаряне';
                                }
                                ?>
                            </p>
                            <a href="index.php?page=details_service&id=<?php echo $service['id']; ?>" class="btn btn-outline-primary w-100">
                                Виж повече
                            </a>
                        </div>
                    </div>
                </div>
            <?php }
        } else { ?>
            <div class="col-12">
                <div class="alert alert-info text-center">Няма намерени услуги.</div>
            </div>
        <?php } ?>
    </div>
</div>


<script>
    const subcategoryOptions = {
        "Кучета": ["Груминг", "Разходки", "Обучение", "Хотел"],
        "Котки": ["Груминг", "Хотел", "Ветеринар", "Транспорт"],
        "Общи": ["Ветеринар", "Транспорт", "Консултации", "Други"]
    };

    function updateSubcategories() {
        const category = document.getElementById("categorySelect").value;
        const subSelect = document.getElementById("subcategorySelect");
        subSelect.innerHTML = '<option value="">-- Всички подкатегории --</option>';

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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
