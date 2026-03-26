<?php
require_once 'includes/header.php';
require_once 'includes/logger.php';

$services = [];
if (file_exists('data/services.json')) {
    $services = json_decode(file_get_contents('data/services.json'), true);
}
?>
<div class="container">
    <h1 class="page-title">Онлайн-запись</h1>
    <div class="form-container">
        <form id="bookingForm">
            <div class="form-group">
                <label for="name">Ваше имя *</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Телефон *</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="service">Выберите услугу *</label>
                <select id="service" name="service_id" required>
                    <option value="">-- Выберите услугу --</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['name']) ?> - <?= $service['price'] ?>₽</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Выберите дату *</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="time">Выберите время *</label>
                <select id="time" name="time" required>
                    <option value="">Сначала выберите дату и услугу</option>
                </select>
            </div>
            
            <!-- НОВОЕ: Чекбокс согласия на обработку ПДн -->
            <div class="form-group" style="margin-top: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="consent" required style="width: auto;">
                    <span>Я согласен(на) на обработку моих персональных данных в соответствии с <a href="#" style="color: #b76e79;" onclick="alert('Политика обработки ПДн: Ваши данные используются только для записи и не передаются третьим лицам.'); return false;">политикой конфиденциальности</a> *</span>
                </label>
            </div>
            
            <button type="submit" class="btn" style="width: 100%;">Записаться</button>
        </form>
        <div id="bookingMessage"></div>
    </div>
</div>

<script>
    const serviceSelect = document.getElementById('service');
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');

    function updateTimes() {
        const serviceId = serviceSelect.value;
        const date = dateInput.value;
        if (serviceId && date) {
            // Логируем выбор даты и услуги
            fetch('/beauty-salon/api/log_click.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({button: 'update_times', service_id: serviceId, date: date})
            });
            
            fetch('/beauty-salon/api/get_timeslots.php?service_id=' + serviceId + '&date=' + date)
                .then(response => response.json())
                .then(data => {
                    timeSelect.innerHTML = '<option value="">Выберите время</option>';
                    data.times.forEach(time => {
                        const option = document.createElement('option');
                        option.value = time;
                        option.textContent = time;
                        timeSelect.appendChild(option);
                    });
                });
        }
    }

    serviceSelect.addEventListener('change', updateTimes);
    dateInput.addEventListener('change', updateTimes);
</script>

<?php require_once 'includes/footer.php'; ?>