<?php
// Настройка сессии: куки сессии будут удалены при закрытии браузера
if (session_status() === PHP_SESSION_NONE) {
    // Устанавливаем время жизни сессии = 0 (до закрытия браузера)
    ini_set('session.cookie_lifetime', 0);
    session_start();
}

// Подключаем логгер
require_once __DIR__ . '/logger.php';

// Логируем посещение страницы
logPageView(basename($_SERVER['PHP_SELF']));

// Определяем текущую страницу для подсветки меню
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Салон красоты "Эстетика"</title>
    <link rel="stylesheet" href="/beauty-salon/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="/beauty-salon/index.php" class="logo">BeautySalon</a>
            <div style="display: flex; align-items: center; gap: 30px;">
                <nav>
                    <a href="/beauty-salon/index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Главная</a>
                    <a href="/beauty-salon/services.php" class="<?= $current_page == 'services.php' ? 'active' : '' ?>">Услуги</a>
                    <a href="/beauty-salon/booking.php" class="<?= $current_page == 'booking.php' ? 'active' : '' ?>">Онлайн-запись</a>
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <a href="/beauty-salon/admin/dashboard.php">Админка</a>
                        <a href="/beauty-salon/admin/logout.php">Выйти</a>
                    <?php else: ?>
                        <a href="/beauty-salon/login.php">Вход для админа</a>
                    <?php endif; ?>
                </nav>
                <div class="phone-number" style="color: #fff5f0; font-weight: 500;">
                    📞 +7 (999) 123-45-67
                </div>
            </div>
        </div>
    </header>
    <main>