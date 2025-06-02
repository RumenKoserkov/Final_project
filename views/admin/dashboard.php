<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container">

    <h2 class="mb-4">Админ табло</h2>

    <!-- Списък с потребители -->
    <h3 class="text-primary">Потребители</h3>

    <?php if (!empty($users)) { ?>
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th> 
                        <th>Име</th> 
                        <th>Email</th> 
                        <th>Роля</th>
                        <th>Действие</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td> 
                            <td><?php echo htmlspecialchars($user['name']); ?></td> 
                            <td><?php echo htmlspecialchars($user['email']); ?></td> 
                            <td><?php echo htmlspecialchars($user['role']); ?></td> 
                            <td>
                                <!-- Бутон за изтриване с потвърждение -->
                                <a href="index.php?page=admin_delete_user&id=<?php echo $user['id']; ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Сигурен ли си, че искаш да изтриеш този потребител?');">
                                    Изтрий
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <p class="text-muted">Няма потребители.</p>
    <?php } ?>

    <!-- Списък с услуги -->
    <h3 class="text-primary">Услуги</h3>

    <?php if (!empty($services)) { ?>
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th> 
                        <th>Категория</th> 
                        <th>Тип</th> 
                        <th>Локация</th> 
                        <th>Собственик (ID)</th> 
                        <th>Действие</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service) { ?>
                        <tr>
                            <td><?php echo $service['id']; ?></td>
                            <td><?php echo htmlspecialchars($service['category']); ?></td>
                            <td><?php echo htmlspecialchars($service['subcategory']); ?></td>
                            <td><?php echo htmlspecialchars($service['location']); ?></td>
                            <td><?php echo $service['user_id']; ?></td>
                            <td>
                                <!-- Бутон за изтриване на услуга с потвърждение -->
                                <a href="index.php?page=admin_delete_service&id=<?php echo $service['id']; ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Сигурен ли си, че искаш да изтриеш тази услуга?');">
                                    Изтрий
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <p class="text-muted">Няма добавени услуги.</p>
    <?php } ?>

    <!-- Списък с животни -->
    <h3 class="text-primary">Животни</h3>

    <?php if (!empty($animals)) { ?>
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th> 
                        <th>Тип</th> 
                        <th>Порода</th> 
                        <th>Локация</th> 
                        <th>Собственик (ID)</th> 
                        <th>Действие</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($animals as $animal) {  ?>
                        <tr>
                            <td><?php echo $animal['id']; ?></td>
                            <td><?php echo htmlspecialchars($animal['type']); ?></td>
                            <td><?php echo htmlspecialchars($animal['breed']); ?></td>
                            <td><?php echo htmlspecialchars($animal['location']); ?></td>
                            <td><?php echo $animal['user_id']; ?></td>
                            <td>
                                <!-- Бутон за изтриване на животно с потвърждение -->
                                <a href="index.php?page=admin_delete_animal&id=<?php echo $animal['id']; ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Сигурен ли си, че искаш да изтриеш това животно?');">
                                    Изтрий
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <p class="text-muted">Няма добавени животни.</p>
    <?php } ?>

</div> 

<?php

require_once __DIR__ . '/../layouts/footer.php';
?>
