<?php
date_default_timezone_set('Asia/Krasnoyarsk'); // Устанавливаем Красноярское время
require_once '../includes/auth_check.php';
require_once '../includes/header.php';
?>

<div class="container">
    <h1 class="page-title">Логи действий пользователей</h1>
    <a href="/beauty-salon/admin/dashboard.php" class="btn" style="margin-bottom: 20px;">← Назад</a>
    
    <div class="form-container" style="max-width: 100%;">
        <h3>Статистика посещений и действий</h3>
        
        <?php
        $logFile = '../data/user_actions.log';
        
        if (file_exists($logFile)) {
            $logs = file($logFile);
            $logs = array_reverse($logs); // Последние записи сверху
            
            // Статистика
            $totalVisits = 0;
            $totalClicks = 0;
            $totalForms = 0;
            $pageViews = [];
            
            foreach ($logs as $log) {
                $data = json_decode($log, true);
                if ($data) {
                    $totalVisits++;
                    if ($data['action'] === 'click') $totalClicks++;
                    if ($data['action'] === 'form_submit') $totalForms++;
                    
                    $page = $data['data']['page'] ?? $data['page'] ?? 'unknown';
                    if (!isset($pageViews[$page])) {
                        $pageViews[$page] = 0;
                    }
                    $pageViews[$page]++;
                }
            }
            ?>
            
            <!-- Статистика -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
                <div class="service-card" style="text-align: center;">
                    <h3>📊 Всего действий</h3>
                    <p style="font-size: 2rem; color: #b76e79;"><?= $totalVisits ?></p>
                </div>
                <div class="service-card" style="text-align: center;">
                    <h3>🖱️ Всего кликов</h3>
                    <p style="font-size: 2rem; color: #b76e79;"><?= $totalClicks ?></p>
                </div>
                <div class="service-card" style="text-align: center;">
                    <h3>📝 Отправлено форм</h3>
                    <p style="font-size: 2rem; color: #b76e79;"><?= $totalForms ?></p>
                </div>
            </div>
            
            <!-- Посещаемость страниц -->
            <h3>📈 Посещаемость страниц</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Страница</th>
                            <th>Количество просмотров</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pageViews as $page => $count): ?>
                        <tr>
                            <td><?= htmlspecialchars($page) ?></td>
                            <td><?= $count ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Детальные логи -->
            <h3 style="margin-top: 40px;">📋 Детальные логи (последние 50)</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Время (Красноярск)</th>
                            <th>IP</th>
                            <th>Страница</th>
                            <th>Действие</th>
                            <th>Данные</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $displayCount = 0;
                        foreach ($logs as $log):
                            if ($displayCount++ >= 50) break;
                            $data = json_decode($log, true);
                            if ($data):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($data['time'] ?? '') ?></td>
                            <td><?= htmlspecialchars($data['ip'] ?? '') ?></td>
                            <td><?= htmlspecialchars($data['page'] ?? '') ?></td>
                            <td>
                                <?php
                                $action = $data['action'] ?? '';
                                switch($action) {
                                    case 'page_view': echo '📄 Просмотр страницы'; break;
                                    case 'click': echo '🖱️ Клик: ' . htmlspecialchars($data['data']['button'] ?? 'unknown'); break;
                                    case 'form_submit': echo '📝 Отправка формы: ' . htmlspecialchars($data['data']['form'] ?? 'unknown'); break;
                                    case 'time_on_page': echo '⏱️ Время на странице: ' . ($data['data']['time_spent_seconds'] ?? 0) . ' сек'; break;
                                    default: echo htmlspecialchars($action);
                                }
                                ?>
                            </td>
                            <td><small><?= htmlspecialchars(substr($log, 0, 100)) ?>...</small></td>
                        </tr>
                        <?php endif; endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Кнопка очистки логов -->
            <form method="POST" style="margin-top: 20px;" onsubmit="return confirm('Очистить все логи?');">
                <button type="submit" name="clear_logs" class="btn" style="background-color: #dc3545;">🗑 Очистить логи</button>
            </form>
            
            <?php
            // Очистка логов
            if (isset($_POST['clear_logs'])) {
                file_put_contents($logFile, '');
                echo '<div class="message success">Логи очищены</div>';
                header('Refresh: 2');
            }
            ?>
            
        <?php } else { ?>
            <p>Файл логов не найден.</p>
        <?php } ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>