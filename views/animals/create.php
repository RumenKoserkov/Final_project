<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    die();
}

 require_once __DIR__ . '/../layouts/header.php'; ?>


<h2 class="mb-4 text-primary"><i class="bi bi-plus-circle"></i> Добави ново животно</h2>


<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data" class="row g-3">

            <!-- Категория -->
            <div class="col-md-6">
                <label for="type" class="form-label">Категория:</label>
                <select name="type" id="type" class="form-select" required onchange="updateBreeds()">
                    <option value="">-- Избери категория --</option>
                    <option value="Кучета">Кучета</option>
                    <option value="Котки">Котки</option>
                    <option value="Гризачи">Гризачи</option>
                    <option value="Птици">Птици</option>
                    <option value="Риби">Риби</option>
                    <option value="Влечуги и земноводни">Влечуги и земноводни</option>
                </select>
            </div>

            <!-- Подкатегория (порода) -->
            <div class="col-md-6">
                <label for="breed" class="form-label">Порода:</label>
                <select name="breed" id="breed" class="form-select" required>
                    <option value="">-- Първо избери категория --</option>
                </select>
            </div>

            <!-- Възраст -->
            <div class="col-md-6">
                <label for="age" class="form-label">Възраст (в години):</label>
                <input type="number" name="age" id="age" min="0" class="form-control" required>
            </div>

            <!-- Локация -->
            <div class="col-md-6">
                <label for="location" class="form-label">Локация:</label>
                <select name="location" id="location" class="form-select" required>
                    <option value="">-- Избери град --</option>
                    <?php
                    $cities = ["Благоевград", "Бургас", "Варна", "Велико Търново", "Видин", "Враца",
                        "Габрово", "Добрич", "Кърджали", "Кюстендил", "Ловеч", "Монтана", "Пазарджик",
                        "Перник", "Плевен", "Пловдив", "Разград", "Русе", "Силистра", "Сливен", "Смолян",
                        "София", "София област", "Стара Загора", "Търговище", "Хасково", "Шумен", "Ямбол"];
                    foreach ($cities as $city) {
                        echo '<option value="' . $city . '">' . $city . '</option>';

                    }
                    ?>
                </select>
            </div>

            <!-- Телефон -->
            <div class="col-md-6">
                <label for="phone" class="form-label">Телефон за контакт:</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
            </div>

            <!-- Цена -->
            <div class="col-md-6">
                <label for="price" class="form-label">Цена:</label>
                <input type="number" name="price" id="price" step="0.01" min="0" class="form-control">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="negotiable" id="negotiable" value="1">
                    <label class="form-check-label" for="negotiable">По договаряне</label>
                </div>
            </div>

            <!-- Описание -->
            <div class="col-12">
                <label for="description" class="form-label">Описание:</label>
                <textarea name="description" id="description" rows="5" class="form-control"></textarea>
            </div>

            <!-- Снимка -->
            <div class="col-12">
                <label for="image" class="form-label">Снимка:</label>
                <input type="file" name="image" id="image" accept="image/*" class="form-control">
            </div>

            <!-- Бутон -->
            <div class="col-12">
                <button type="submit" name="create_animal" class="btn btn-primary w-100">
                    <i class="bi bi-plus-circle"></i> Създай обява
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const breeds = {
        "Кучета": ["Лабрадор ретривър", "Голдън ретривър", "Немска овчарка", "Френски булдог", "Булдог", "Пудел",
            "Бийгъл", "Ротвайлер", "Йоркширски териер", "Дакел", "Доберман пинчер", "Померан", "Сибирско хъски",
            "Чихуахуа", "Шицу", "Аляски маламут", "Австралийска овчарка", "Кавалер кинг чарлз шпаньол",
            "Бордър коли", "Боксер"],
        "Котки": ["Персийска котка", "Мейн Куун", "Британска късокосместа", "Сиамска котка", "Шотландска клепоуха",
            "Рагдол", "Бенгалска котка", "Руски син", "Сфинкс", "Абисинска котка", "Бирманска котка",
            "Норвежка горска котка", "Ориенталска късокосместа", "Американска късокосместа", "Турска ангора",
            "Хималайска котка", "Сомалийска котка", "Корниш рекс", "Египетска мау", "Тонкинска котка"],
        "Гризачи": ["Хамстери", "Морски свинчета", "Плъхове", "Мишки", "Чинчили"],
        "Птици": ["Папагали", "Канарчета", "Амадини", "Какаду", "Екзотични птици"],
        "Риби": ["Сладководни", "Соленоводни"],
        "Влечуги и земноводни": ["Костенурки", "Гущери", "Змии", "Жаби"]
    };

    function updateBreeds() {
        const type = document.getElementById("type").value;
        const breedSelect = document.getElementById("breed");
        breedSelect.innerHTML = "";

        if (breeds[type]) {
            breeds[type].forEach(function(breed) {
                const option = document.createElement("option");
                option.value = breed;
                option.text = breed;
                breedSelect.appendChild(option);
            });
        } else {
            const option = document.createElement("option");
            option.value = "";
            option.text = "-- Първо избери категория --";
            breedSelect.appendChild(option);
        }
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
