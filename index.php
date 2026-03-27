<?php
require_once 'includes/header.php';
// Читаем услуги
$services = [];
if (file_exists('data/services.json')) {
    $services = json_decode(file_get_contents('data/services.json'), true);
    $services = array_slice($services, 0, 3);
}
?>

<!-- Герой-секция с фоновым фото -->
<div class="hero">
    <div class="container">
        <h1>Салон красоты "Эстетика"</h1>
        <p>Преображение, которое вдохновляет</p>
        <a href="/beauty-salon/booking.php" class="btn">Записаться онлайн</a>
    </div>
</div>

<div class="container">
    <!-- О нас с узорной рамкой -->
    <div class="about-section">
        <h2>О нас</h2>
        <p>Наш салон — это пространство красоты и гармонии. Мы используем только профессиональные материалы и следим за новейшими тенденциями в индустрии красоты. Каждый клиент для нас особенный, и мы стремимся сделать ваш визит незабываемым.</p>
        <p style="margin-top: 15px; font-weight: 500;">📍 Мы находимся по адресу: <strong>г. Красноярск, ул. Мира, д. 123</strong></p>
    </div>

    <!-- Популярные услуги -->
    <section>
        <h2 style="text-align: center; margin-bottom: 40px; font-size: 2rem; color: #b76e79; font-family: 'Playfair Display', serif;">Популярные услуги</h2>
        <div class="services-grid">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <div class="service-card">
                        <h3><?= htmlspecialchars($service['name']) ?></h3>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                        <div class="price"><?= htmlspecialchars($service['price']) ?> ₽</div>
                        <a href="/beauty-salon/booking.php" class="btn">Записаться</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Список услуг временно пуст.</p>
            <?php endif; ?>
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <a href="/beauty-salon/services.php" class="btn">Все услуги</a>
        </div>
    </section>

    <!-- Подарочный сертификат -->
    <div class="gift-card" id="giftCard">
        <h2>🎁 Подарочный сертификат</h2>
        <p>Лучший подарок для близких! Сертификат на любые услуги салона.</p>
        <div class="price">
            <span class="old-price">5 000 ₽</span>
            <span>3 500 ₽</span>
            <span class="discount">-30%</span>
        </div>
        <p style="margin: 20px 0;">✨ Действует 6 месяцев ✨</p>
        <a href="/beauty-salon/activate_gift.php" class="btn">Активировать сертификат</a>
    </div>

    <!-- Наши мастера -->
    <section>
        <h2 style="text-align: center; margin: 60px 0 40px; font-size: 2rem; color: #b76e79; font-family: 'Playfair Display', serif;">Наши мастера</h2>
        <div class="masters-grid">
            <div class="master-card">
                <img src="/beauty-salon/assets/images/masters/anna.jpg" alt="Мастер Анна" class="master-photo" onerror="this.src='/beauty-salon/assets/images/placeholder.jpg'; this.onerror=null;">
                <h3>Анна К.</h3>
                <p>Стилист-парикмахер<br>Стаж 8 лет</p>
            </div>
            <div class="master-card">
                <img src="/beauty-salon/assets/images/masters/elena.jpg" alt="Мастер Елена" class="master-photo" onerror="this.src='/beauty-salon/assets/images/placeholder.jpg'; this.onerror=null;">
                <h3>Елена М.</h3>
                <p>Мастер маникюра<br>Стаж 6 лет</p>
            </div>
            <div class="master-card">
                <img src="/beauty-salon/assets/images/masters/darya.jpg" alt="Мастер Дарья" class="master-photo" onerror="this.src='/beauty-salon/assets/images/placeholder.jpg'; this.onerror=null;">
                <h3>Дарья С.</h3>
                <p>Косметолог<br>Стаж 5 лет</p>
            </div>
            <div class="master-card">
                <img src="/beauty-salon/assets/images/masters/olga.jpg" alt="Мастер Ольга" class="master-photo" onerror="this.src='/beauty-salon/assets/images/placeholder.jpg'; this.onerror=null;">
                <h3>Ольга В.</h3>
                <p>Визажист<br>Стаж 7 лет</p>
            </div>
        </div>
    </section>

    <!-- Галерея работ с рамками -->
    <section>
        <h2 style="text-align: center; margin: 60px 0 40px; font-size: 2rem; color: #b76e79; font-family: 'Playfair Display', serif;">Наши работы</h2>
        <div class="gallery">
            <div class="gallery-item">
                <img src="/beauty-salon/assets/images/gallery/work1.jpg" alt="Стрижка" class="gallery-img" onerror="this.src='/beauty-salon/assets/images/placeholder.jpg'; this.onerror=null;">
                <div class="gallery-overlay">Модельная стрижка</div>
            </div>
            <div class="gallery-item">
                <img src="/beauty-salon/assets/images/gallery/work2.jpg" alt="Маникюр" class="gallery-img" onerror="this.src='/beauty-salon/assets/images/placeholder.jpg'; this.onerror=null;">
                <div class="gallery-overlay">Дизайн ногтей</div>
            </div>
            <div class="gallery-item">
                <img src="/beauty-salon/assets/images/gallery/work3.jpg" alt="Макияж" class="gallery-img" onerror="this.src='/beauty-salon/assets/images/placeholder.jpg'; this.onerror=null;">
                <div class="gallery-overlay">Вечерний макияж</div>
            </div>
        </div>
    </section>

    <!-- Отзывы -->
    <section style="margin: 60px 0;">
        <h2 style="text-align: center; margin-bottom: 40px; font-size: 2rem; color: #b76e79; font-family: 'Playfair Display', serif;">Отзывы клиентов</h2>
        <div class="services-grid">
            <div class="service-card">
                <p style="font-size: 2rem;">⭐⭐⭐⭐⭐</p>
                <p>"Отличный салон! Делала стрижку у Анны, результат превзошел ожидания. Обязательно вернусь!"</p>
                <p style="margin-top: 15px; color: #b76e79;">— Екатерина</p>
            </div>
            <div class="service-card">
                <p style="font-size: 2rem;">⭐⭐⭐⭐⭐</p>
                <p>"Маникюр просто шикарный! Елена очень внимательный мастер, учла все пожелания."</p>
                <p style="margin-top: 15px; color: #b76e79;">— Мария</p>
            </div>
            <div class="service-card">
                <p style="font-size: 2rem;">⭐⭐⭐⭐⭐</p>
                <p>"Купила подарочный сертификат подруге. Очень удобно, можно выбрать любую услугу."</p>
                <p style="margin-top: 15px; color: #b76e79;">— Анна</p>
            </div>
        </div>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>