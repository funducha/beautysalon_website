<?php
require_once '../includes/auth_check.php'; // Проверка входа
require_once '../includes/header.php';

$appointments = [];
if (file_exists('../data/appointments.json')) {
    $appointments = json_decode(file_get_contents('../data/appointments.json'), true);
    // Для красоты можно отсортировать по дате
    usort($appointments, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
}

// Загрузим услуги для отображения названия
$services = [];
if (file_exists('../data/services.json')) {
    $services = json_decode(file_get_contents('../data/services.json'), true);
    $servicesMap = [];
    foreach ($services as $s) {
        $servicesMap[$s['id']] = $s['name'];
    }
}
?>

<div class="container">
    <h1 class="page-title">Панель администратора</h1>
    <nav style="margin-bottom: 20px; text-align: center;">
        <a href="/beauty-salon/admin/services.php" class="btn" style="margin-right: 10px;">Управление услугами</a>
        <a href="/beauty-salon/admin/logs.php" class="btn" style="margin-right: 10px; background-color: #17a2b8;">📊 Просмотр логов</a>
        <a href="/beauty-salon/admin/logout.php" class="btn" style="background-color: #6c757d;">Выйти</a>
    </nav>

    <h2>Все записи клиентов</h2>
    <?php if (empty($appointments)): ?>
        <p>Пока нет ни одной записи.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Телефон</th>
                        <th>Услуга</th>
                        <th>Дата</th>
                        <th>Время</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $app): ?>
                    <tr>
                        <td><?= $app['id'] ?></td>
                        <td><?= htmlspecialchars($app['client_name']) ?></td>
                        <td><?= htmlspecialchars($app['client_phone']) ?></td>
                        <td><?= isset($servicesMap[$app['service_id']]) ? htmlspecialchars($servicesMap[$app['service_id']]) : 'Неизвестно' ?></td>
                        <td><?= htmlspecialchars($app['date']) ?></td>
                        <td><?= htmlspecialchars($app['time']) ?></td>
                        <td>
                            <form method="POST" action="/beauty-salon/admin/delete_appointment.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                <button type="submit" class="btn btn-small" onclick="return confirm('Удалить запись?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>