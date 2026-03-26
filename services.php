<?php
require_once 'includes/header.php';
$services = [];
if (file_exists('data/services.json')) {
    $services = json_decode(file_get_contents('data/services.json'), true);
}
?>

<div class="container">
    <h1 class="page-title">Наши услуги и цены</h1>
    
    <!-- Баннер с фото - ИСПРАВЛЕННЫЙ ПУТЬ -->
    <div style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/beauty-salon/assets/images/services-bg.jpg'); background-size: cover; background-position: center; border-radius: 20px; padding: 60px; text-align: center; margin-bottom: 40px;">
        <h2 style="color: white; font-size: 2rem;">Выберите идеальную услугу</h2>
        <p style="color: white; font-size: 1.1rem;">Профессиональный подход к каждой детали</p>
    </div>
    
    <div class="services-grid">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
                <div class="service-card">
                    <h3><?= htmlspecialchars($service['name']) ?></h3>
                    <p><?= htmlspecialchars($service['description']) ?></p>
                    <div class="price"><?= htmlspecialchars($service['price']) ?> <small>руб.</small></div>
                    <a href="/beauty-salon/booking.php?service_id=<?= $service['id'] ?>" class="btn">Записаться</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Услуги пока не добавлены.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>