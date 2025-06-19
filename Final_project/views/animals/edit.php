<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-primary"><i class="bi bi-pencil-square"></i> Редакция на животинска обява</h2>

    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger"><?php echo htmlentities($error); ?></div>
    <?php } ?>

    <?php if (!empty($success)) { ?>
        <div class="alert alert-success"><?php echo htmlentities($success); ?></div>
    <?php } ?>

    <!-- Форма за редакция на животинска обява -->
    <form method="POST" action="index.php?page=edit_animal&id=<?php echo $animal['id']; ?>" class="bg-white p-4 shadow rounded row g-3">

        <!-- Категория -->
        <div class="col-md-6">
            <label for="type" class="form-label">Категория:</label>
            <select name="type" id="type" class="form-select" required onchange="updateBreeds()">
                <option value="">-- Избери --</option>
                <?php
                $types = ["Кучета", "Котки", "Гризачи", "Птици", "Риби", "Влечуги и земноводни"];
                $selectedType = isset($_POST['type']) ? $_POST['type'] : $animal['type'];
                foreach ($types as $type) {
                    echo '<option value="' . $type . '"';
                    if ($selectedType === $type) {
                        echo ' selected';
                    }
                    echo '>' . $type . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Порода -->
        <div class="col-md-6">
            <label for="breed" class="form-label">Порода:</label>
            <select name="breed" id="breed" class="form-select" required></select>
        </div>

        <!-- Възраст -->
        <div class="col-md-4">
            <label for="age" class="form-label">Възраст:</label>
            <input type="number" name="age" id="age" class="form-control" min="0"
                   value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : htmlspecialchars($animal['age']); ?>" required>
        </div>

        <!-- Локация -->
        <div class="col-md-4">
            <label for="location" class="form-label">Град:</label>
            <select name="location" id="location" class="form-select" required>
                <option value="">-- Избери град --</option>
                <?php
                $cities = ['София', 'Пловдив', 'Варна', 'Бургас', 'Русе', 'Стара Загора', 'Плевен', 'Сливен', 'Добрич', 'Шумен'];
                $selectedCity = isset($_POST['location']) ? $_POST['location'] : $animal['location'];
                foreach ($cities as $city) {
                    echo '<option value="' . $city . '"';
                    if ($selectedCity === $city) {
                        echo ' selected';
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
                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : htmlspecialchars($animal['phone']); ?>" required>
        </div>

        <!-- Цена и по договаряне -->
        <div class="col-md-4">
            <label for="price" class="form-label">Цена:</label>
            <input type="number" name="price" id="price" class="form-control"
                   value="<?php
                       if (isset($_POST['price'])) {
                           echo htmlspecialchars($_POST['price']);
                       } else {
                           echo htmlspecialchars($animal['price']);
                       }
                   ?>" step="0.01" min="0">
            <div class="form-check mt-2">
                <input type="checkbox" name="negotiable" id="negotiable" class="form-check-input"
                    <?php
                    $negotiableChecked = isset($_POST['negotiable']) || (!isset($_POST['update_animal']) && $animal['negotiable'] == 1);
                    if ($negotiableChecked) {
                        echo 'checked';
                    }
                    ?>>
                <label for="negotiable" class="form-check-label">По договаряне</label>
            </div>
        </div>

        <!-- Описание -->
        <div class="col-12">
            <label for="description" class="form-label">Описание:</label>
            <textarea name="description" id="description" class="form-control" rows="4" required><?php
                echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : htmlspecialchars($animal['description']);
            ?></textarea>
        </div>

        <!-- Бутон за запис -->
        <div class="col-12">
            <button type="submit" name="update_animal" class="btn btn-success">
                <i class="bi bi-save"></i> Запази промените
            </button>
            <a href="index.php?page=animals" class="btn btn-secondary">Отказ</a>
        </div>
    </form>
</div>

<!-- JavaScript за динамично попълване на породи -->
<script>
    const breedsByType = {
        "Кучета": ["Лабрадор ретривър", "Голдън ретривър", "Немска овчарка", "Френски булдог", "Булдог", "Пудел", "Бийгъл", "Ротвайлер", "Йоркширски териер", "Дакел", "Доберман пинчер", "Померан", "Сибирско хъски", "Чихуахуа", "Шицу", "Аляски маламут", "Австралийска овчарка", "Кавалер кинг чарлз шпаньол", "Бордър коли", "Боксер"],
        "Котки": ["Персийска котка", "Мейн Куун", "Британска късокосместа", "Сиамска котка", "Шотландска клепоуха", "Рагдол", "Бенгалска котка", "Руски син", "Сфинкс", "Абисинска котка", "Бирманска котка", "Норвежка горска котка", "Ориенталска късокосместа", "Американска късокосместа", "Турска ангора", "Хималайска котка", "Сомалийска котка", "Корниш рекс", "Египетска мау", "Тонкинска котка"],
        "Гризачи": ["Хамстери", "Морски свинчета", "Плъхове", "Мишки", "Чинчили"],
        "Птици": ["Папагали", "Канарчета", "Амадини", "Какаду", "Екзотични птици"],
        "Риби": ["Сладководни", "Соленоводни"],
        "Влечуги и земноводни": ["Костенурки", "Гущери", "Змии", "Жаби"]
    };

    function updateBreeds() {
        const type = document.getElementById("type").value;
        const breedSelect = document.getElementById("breed");
        const currentBreed = "<?php echo isset($_POST['breed']) ? $_POST['breed'] : $animal['breed']; ?>";

        breedSelect.innerHTML = '<option value="">-- Избери порода --</option>';

        if (breedsByType[type]) {
            breedsByType[type].forEach(function (breed) {
                const option = document.createElement("option");
                option.value = breed;
                option.text = breed;
                if (breed === currentBreed) {
                    option.selected = true;
                }
                breedSelect.appendChild(option);
            });
        }
    }

    window.addEventListener("DOMContentLoaded", function () {
        updateBreeds();

        const priceInput = document.getElementById("price");
        const negotiableCheckbox = document.getElementById("negotiable");

        negotiableCheckbox.addEventListener("change", function () {
            if (this.checked) {
                priceInput.value = '';
            }
        });

        priceInput.addEventListener("input", function () {
            if (this.value !== '') {
                negotiableCheckbox.checked = false;
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
