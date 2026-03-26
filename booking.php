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
                <input type="text" id="name" name="name" required autocomplete="name">
            </div>
            <div class="form-group">
                <label for="phone">Телефон *</label>
                <input type="tel" id="phone" name="phone" required autocomplete="tel">
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
    // Получаем элементы формы
    const serviceSelect = document.getElementById('service');
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const bookingForm = document.getElementById('bookingForm');
    const consentCheckbox = document.getElementById('consent');
    const messageDiv = document.getElementById('bookingMessage');

    // Функция для загрузки доступного времени
    function updateTimes() {
        const serviceId = serviceSelect.value;
        const date = dateInput.value;
        
        if (serviceId && date) {
            // Показываем загрузку
            timeSelect.innerHTML = '<option value="">Загрузка...</option>';
            timeSelect.disabled = true;
            
            // Загружаем доступные слоты
            fetch('/beauty-salon/api/get_timeslots.php?service_id=' + serviceId + '&date=' + date)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    timeSelect.disabled = false;
                    if (data.times && data.times.length > 0) {
                        timeSelect.innerHTML = '<option value="">Выберите время</option>';
                        data.times.forEach(time => {
                            const option = document.createElement('option');
                            option.value = time;
                            option.textContent = time;
                            timeSelect.appendChild(option);
                        });
                    } else {
                        timeSelect.innerHTML = '<option value="">Нет свободного времени</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading times:', error);
                    timeSelect.disabled = false;
                    timeSelect.innerHTML = '<option value="">Ошибка загрузки времени</option>';
                    showModal('Ошибка', 'Не удалось загрузить свободное время. Попробуйте позже.');
                });
        } else {
            timeSelect.innerHTML = '<option value="">Сначала выберите дату и услугу</option>';
            timeSelect.disabled = false;
        }
    }

    // Добавляем обработчики событий
    serviceSelect.addEventListener('change', updateTimes);
    dateInput.addEventListener('change', updateTimes);
    
    // Устанавливаем минимальную дату (сегодня)
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;
    
    // Обработка отправки формы
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Проверка согласия
        if (!consentCheckbox || !consentCheckbox.checked) {
            showModal('Внимание', 'Пожалуйста, дайте согласие на обработку персональных данных');
            return;
        }
        
        // Собираем данные
        const data = {
            name: document.getElementById('name').value.trim(),
            phone: document.getElementById('phone').value.trim(),
            service_id: serviceSelect.value,
            date: dateInput.value,
            time: timeSelect.value,
            consent: true
        };
        
        // Валидация
        if (!data.name) {
            showModal('Внимание', 'Пожалуйста, введите ваше имя');
            return;
        }
        if (!data.phone) {
            showModal('Внимание', 'Пожалуйста, введите номер телефона');
            return;
        }
        if (!data.service_id) {
            showModal('Внимание', 'Пожалуйста, выберите услугу');
            return;
        }
        if (!data.date) {
            showModal('Внимание', 'Пожалуйста, выберите дату');
            return;
        }
        if (!data.time || data.time === 'Выберите время') {
            showModal('Внимание', 'Пожалуйста, выберите время');
            return;
        }
        
        // Показываем сообщение о загрузке
        messageDiv.innerHTML = '<div class="message">Отправка...</div>';
        
        // Логируем отправку формы
        fetch('/beauty-salon/api/log_form_submit.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({form: 'booking', data: {service_id: data.service_id, date: data.date, time: data.time}})
        }).catch(err => console.log('Log error:', err));
        
        // Отправляем запрос на запись
        fetch('/beauty-salon/api/book_appointment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error ' + response.status);
            }
            return response.json();
        })
        .then(result => {
            console.log('Response:', result);
            if (result.success) {
                showSuccessModal('✅ Запись успешна!', 'Вы успешно записались на ' + data.date + ' в ' + data.time + '. Мы ждем вас в салоне!');
                // Сбрасываем форму
                bookingForm.reset();
                // Сбрасываем select времени
                timeSelect.innerHTML = '<option value="">Сначала выберите дату и услугу</option>';
                messageDiv.innerHTML = '';
                // Обновляем доступные слоты
                updateTimes();
            } else {
                showModal('Ошибка', result.message || 'Ошибка записи');
                messageDiv.innerHTML = '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showModal('Ошибка', 'Произошла ошибка. Попробуйте позже.');
            messageDiv.innerHTML = '';
        });
    });

    // Функция для показа модального окна с ошибкой/предупреждением
    function showModal(title, message) {
        const modal = createModal(title, message, false);
        document.body.appendChild(modal);
    }

    // Функция для показа успешного модального окна
    function showSuccessModal(title, message) {
        const modal = createModal(title, message, true);
        document.body.appendChild(modal);
    }

    // Функция создания модального окна
    function createModal(title, message, hasReturnButton) {
        const modal = document.createElement('div');
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modal.style.zIndex = '1000';
        
        const modalContent = document.createElement('div');
        modalContent.style.backgroundColor = '#fff';
        modalContent.style.borderRadius = '20px';
        modalContent.style.maxWidth = '450px';
        modalContent.style.width = '90%';
        modalContent.style.boxShadow = '0 5px 25px rgba(0,0,0,0.2)';
        modalContent.style.position = 'relative';
        modalContent.style.animation = 'fadeInUp 0.3s ease';
        
        // Крестик закрытия
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '✕';
        closeBtn.style.position = 'absolute';
        closeBtn.style.top = '15px';
        closeBtn.style.right = '20px';
        closeBtn.style.fontSize = '24px';
        closeBtn.style.background = 'none';
        closeBtn.style.border = 'none';
        closeBtn.style.cursor = 'pointer';
        closeBtn.style.color = '#999';
        closeBtn.style.fontWeight = 'bold';
        closeBtn.style.transition = 'color 0.3s';
        closeBtn.onmouseover = () => closeBtn.style.color = '#b76e79';
        closeBtn.onmouseout = () => closeBtn.style.color = '#999';
        closeBtn.onclick = () => modal.remove();
        
        // Заголовок
        const titleEl = document.createElement('h3');
        titleEl.textContent = title;
        titleEl.style.color = '#b76e79';
        titleEl.style.margin = '0';
        titleEl.style.padding = '25px 30px 10px 30px';
        titleEl.style.fontSize = '1.5rem';
        titleEl.style.fontFamily = "'Playfair Display', serif";
        
        // Сообщение
        const messageEl = document.createElement('p');
        messageEl.textContent = message;
        messageEl.style.padding = '0 30px';
        messageEl.style.margin = '10px 0 25px 0';
        messageEl.style.lineHeight = '1.6';
        messageEl.style.color = '#5a4a42';
        
        modalContent.appendChild(closeBtn);
        modalContent.appendChild(titleEl);
        modalContent.appendChild(messageEl);
        
        if (hasReturnButton) {
            const returnBtn = document.createElement('button');
            returnBtn.textContent = 'Вернуться на главную';
            returnBtn.style.background = 'linear-gradient(135deg, #b76e79, #d89aa4)';
            returnBtn.style.color = 'white';
            returnBtn.style.border = 'none';
            returnBtn.style.padding = '12px 25px';
            returnBtn.style.borderRadius = '50px';
            returnBtn.style.cursor = 'pointer';
            returnBtn.style.fontSize = '1rem';
            returnBtn.style.fontWeight = '600';
            returnBtn.style.margin = '0 30px 30px 30px';
            returnBtn.style.display = 'block';
            returnBtn.style.width = 'calc(100% - 60px)';
            returnBtn.style.transition = 'all 0.3s ease';
            returnBtn.onclick = () => {
                window.location.href = '/beauty-salon/index.php';
            };
            modalContent.appendChild(returnBtn);
        } else {
            const closeModalBtn = document.createElement('button');
            closeModalBtn.textContent = 'Закрыть';
            closeModalBtn.style.background = 'linear-gradient(135deg, #b76e79, #d89aa4)';
            closeModalBtn.style.color = 'white';
            closeModalBtn.style.border = 'none';
            closeModalBtn.style.padding = '12px 25px';
            closeModalBtn.style.borderRadius = '50px';
            closeModalBtn.style.cursor = 'pointer';
            closeModalBtn.style.fontSize = '1rem';
            closeModalBtn.style.fontWeight = '600';
            closeModalBtn.style.margin = '0 30px 30px 30px';
            closeModalBtn.style.display = 'block';
            closeModalBtn.style.width = 'calc(100% - 60px)';
            closeModalBtn.onclick = () => modal.remove();
            modalContent.appendChild(closeModalBtn);
        }
        
        modal.appendChild(modalContent);
        
        // Закрытие по клику на фон
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
        
        return modal;
    }
</script>

<?php require_once 'includes/footer.php'; ?>