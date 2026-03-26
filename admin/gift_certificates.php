<?php
require_once '../includes/auth_check.php';
require_once '../includes/header.php';

$certificates = [];
if (file_exists('../data/gift_certificates.json')) {
    $certificates = json_decode(file_get_contents('../data/gift_certificates.json'), true);
    // Сортируем по дате активации, новые сверху
    usort($certificates, function($a, $b) {
        return strtotime($b['activated_at']) - strtotime($a['activated_at']);
    });
}
?>

<div class="container">
    <h1 class="page-title">Активированные сертификаты</h1>
    <a href="/beauty-salon/admin/dashboard.php" class="btn" style="margin-bottom: 20px;">← Назад</a>
    
    <?php if (empty($certificates)): ?>
        <p>Пока нет активированных сертификатов.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя клиента</th>
                        <th>Телефон</th>
                        <th>Сумма (₽)</th>
                        <th>Дата активации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($certificates as $cert): ?>
                    <tr>
                        <td><?= $cert['id'] ?></td>
                        <td><?= htmlspecialchars($cert['client_name']) ?></td>
                        <td><?= htmlspecialchars($cert['client_phone']) ?></td>
                        <td><?= htmlspecialchars($cert['amount']) ?></td>
                        <td><?= htmlspecialchars($cert['activated_at']) ?></td>
                        <td>
                            <form method="POST" action="/beauty-salon/admin/delete_gift.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $cert['id'] ?>">
                                <button type="submit" class="btn btn-small" onclick="return confirm('Отметить сертификат как использованный?')">✔ Прочитано</button>
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