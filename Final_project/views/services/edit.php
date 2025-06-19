<?php require_once __DIR__ . '/../layouts/header.php'; ?> 

<!-- Контейнер за формата -->
<div class="container mt-5">
    <!-- Заглавие -->
    <h2 class="mb-4 text-primary"><i class="bi bi-pencil-square"></i> Редакция на услуга</h2>

    <!-- Показване на съобщение за грешка -->
    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger"><?php echo htmlentities($error); ?></div>
    <?php } ?>

    <!-- Показване на съобщение за успех -->
    <?php if (!empty($success)) { ?>
        <div class="alert alert-success"><?php echo htmlentities($success); ?></div>
    <?php } ?>

    <!-- Форма за редакция на услуга -->
    <form method="POST" action="index.php?page=edit_service&id=<?php echo $service['id']; ?>" class="bg-white p-4 shadow rounded row g-3">

        <!-- Категория -->
        <div class="col-md-6">
            <label for="category" class="form-label">Категория:</label>
            <select name="category" id="category" class="form-select" required onchange="updateSubcategories()">
                <option value="">-- Избери --</option>
                <?php
                $categories = ["Кучета", "Котки", "Общи"]; // Списък с възможни категории
                foreach ($categories as $cat) {
                    echo '<option value="' . $cat . '"';
                    if ($service['category'] == $cat) {
                        echo ' selected'; 
                    }
                    echo '>' . $cat . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Подкатегория -->
        <div class="col-md-6">
            <label for="subcategory" class="form-label">Подкатегория:</label>
            <select name="subcategory" id="subcategory" class="form-select" required></select> <!-- ще се запълни чрез JavaScript -->
        </div>

        <!-- Локация -->
        <div class="col-md-4">
            <label for="location" class="form-label">Град:</label>
            <select name="location" id="location" class="form-select" required>
                <option value="">-- Избери град --</option>
                <?php
                $cities = ['София', 'Пловдив', 'Варна', 'Бургас', 'Русе', 'Стара Загора', 'Плевен', 'Сливен', 'Добрич', 'Шумен'];
                foreach ($cities as $city) {
                    echo '<option value="' . $city . '"';
                    if ($service['location'] == $city) {
                        echo ' selected'; // Маркираме текущата локация
                    }
                    echo '>' . $city . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Телефон -->
        <div class="col-md-4">
            <label for="phone" class="form-label">Телефон:</label>
            <input type="text" name="phone" id="phone" class="form-control"
                   value="<?php echo htmlspecialchars($service['phone']); ?>" required> <!-- Преизползваме стойността -->
        </div>

        <!-- Цена и опция "по договаряне" -->
        <div class="col-md-4">
            <label for="price" class="form-label">Цена:</label>
            <input type="number" name="price" id="price" class="form-control"
                   value="<?php echo htmlspecialchars($service['price']); ?>" step="0.01" min="0">

            <div class="form-check mt-2">
                <input type="checkbox" name="negotiable" id="negotiable" class="form-check-input"
                       <?php if ($service['negotiable'] == 1) { echo 'checked'; } ?>>
                <label for="negotiable" class="form-check-label">По договаряне</label>
            </div>
        </div>

        <!-- Описание -->
        <div class="col-12">
            <label for="description" class="form-label">Описание:</label>
            <textarea name="description" id="description" class="form-control" rows="4" required><?php echo htmlspecialchars($service['description']); ?></textarea>
        </div>

        <!-- Бутони -->
        <div class="col-12">
            <button type="submit" name="update_service" class="btn btn-success">
                <i class="bi bi-save"></i> Запази промените
            </button>
            <a href="index.php?page=services" class="btn btn-secondary">Отказ</a>
        </div>
    </form>
</div>

<!-- JS-->
<script>
    const subcategoriesByCategory = {
        "Кучета": ["Груминг", "Разходки", "Обучение", "Хотел"],
        "Котки": ["Груминг", "Хотел", "Ветеринар", "Транспорт"],
        "Общи": ["Ветеринар", "Транспорт", "Консултации", "Други"]
    };

    // Функция за обновяване на подкатегории при избор на категория
    function updateSubcategories() {
        const category = document.getElementById("category").value;
        const subSelect = document.getElementById("subcategory");
        const currentSub = "<?php echo $service['subcategory']; ?>"; 

        subSelect.innerHTML = '<option value="">-- Избери подкатегория --</option>';

        if (subcategoriesByCategory[category]) {
            subcategoriesByCategory[category].forEach(function (sub) {
                const option = document.createElement("option");
                option.value = sub;
                option.text = sub;

                if (sub === currentSub) {
                    option.selected = true; 
                }

                subSelect.appendChild(option);
            });
        }
    }

    // При зареждане на страницата
    window.addEventListener("DOMContentLoaded", function () {
        updateSubcategories(); // зареждаме подкатегориите

        const priceInput = document.getElementById("price");
        const negotiableCheckbox = document.getElementById("negotiable");

        // Ако се маркира чекбокс "по договаряне", изчистваме цената
        negotiableCheckbox.addEventListener("change", function () {
            if (this.checked) {
                priceInput.value = '';
            }
        });

        // Ако се въведе цена, премахваме отметката "по договаряне"
        priceInput.addEventListener("input", function () {
            if (this.value !== '') {
                negotiableCheckbox.checked = false;
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 
