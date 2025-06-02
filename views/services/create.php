<?php require_once __DIR__ . '/../layouts/header.php'; ?>


<h2 class="mb-4 text-primary"><i class="bi bi-plus-circle"></i> Добави нова услуга</h2>


<form method="POST" action="" enctype="multipart/form-data" class="bg-white p-4 shadow rounded">


    <div class="mb-3">
        <label for="category" class="form-label">Категория:</label>
        <select name="category" id="category" class="form-select" required onchange="updateSubcategories()">
            <option value="">-- Избери категория --</option>
            <option value="Кучета">Кучета</option>
            <option value="Котки">Котки</option>
            <option value="Общи">Общи</option>
        </select>
    </div>

 
    <div class="mb-3">
        <label for="subcategory" class="form-label">Подкатегория:</label>
        <select name="subcategory" id="subcategory" class="form-select" required>
            <option value="">-- Първо избери категория --</option>
        </select>
    </div>

  
    <div class="mb-3">
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
                echo "<option value=\"$city\">$city</option>";
            }
            ?>
        </select>
    </div>


    <div class="mb-3">
        <label for="phone" class="form-label">Телефон за контакт:</label>
        <input type="text" name="phone" id="phone" class="form-control" required>
    </div>

    
    <div class="mb-3">
        <label for="price" class="form-label">Цена:</label>
        <input type="number" name="price" id="price" step="0.01" min="0" class="form-control">
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="negotiable" id="negotiable" value="1">
            <label class="form-check-label" for="negotiable">По договаряне</label>
        </div>
    </div>

   
    <div class="mb-3">
        <label for="description" class="form-label">Описание:</label>
        <textarea name="description" id="description" class="form-control" rows="5"></textarea>
    </div>

  
    <div class="mb-3">
        <label for="image" class="form-label">Снимка:</label>
        <input type="file" name="image" id="image" accept="image/*" class="form-control">
    </div>

    <button type="submit" name="create_service" class="btn btn-primary w-100">
        <i class="bi bi-check-circle"></i> Създай услуга
    </button>
</form>

<script>
    const subcategories = {
        "Кучета": ["Груминг", "Разходки", "Обучение", "Хотел"],
        "Котки": ["Груминг", "Хотел", "Ветеринар", "Транспорт"],
        "Общи": ["Ветеринар", "Транспорт", "Консултации", "Други"]
    };

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
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
