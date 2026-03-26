// assets/js/script.js
document.addEventListener('DOMContentLoaded', function() {
    // Логирование всех кликов на кнопки
    document.querySelectorAll('.btn, button, a.btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const buttonText = this.textContent.trim();
            const buttonId = this.id || 'no-id';
            
            // Отправляем запрос на логирование клика (не блокируем основное действие)
            fetch('/beauty-salon/api/log_click.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    button: buttonText,
                    button_id: buttonId,
                    page: window.location.pathname
                })
            }).catch(err => console.log('Log error:', err));
        });
    });
    
    // Логирование времени на странице
    let startTime = Date.now();
    window.addEventListener('beforeunload', function() {
        const timeSpent = Math.round((Date.now() - startTime) / 1000);
        // Отправляем данные о времени пребывания (используем sendBeacon для надежности)
        const data = {
            page: window.location.pathname,
            time_spent: timeSpent
        };
        navigator.sendBeacon('/beauty-salon/api/log_time.php', JSON.stringify(data));
    });
    
    // Обработка формы записи
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Проверяем согласие на обработку ПДн
            const consentCheckbox = document.getElementById('consent');
            if (!consentCheckbox || !consentCheckbox.checked) {
                const messageDiv = document.getElementById('bookingMessage');
                messageDiv.innerHTML = '<div class="message error">Пожалуйста, дайте согласие на обработку персональных данных</div>';
                return;
            }
            
            const formData = new FormData(bookingForm);
            const data = {
                name: formData.get('name'),
                phone: formData.get('phone'),
                service_id: formData.get('service_id'),
                date: formData.get('date'),
                time: formData.get('time'),
                consent: true
            };
            
            // Логируем отправку формы
            fetch('/beauty-salon/api/log_form_submit.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({form: 'booking', data: data})
            });
            
            fetch('/beauty-salon/api/book_appointment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                const messageDiv = document.getElementById('bookingMessage');
                if (result.success) {
                    messageDiv.innerHTML = '<div class="message success">' + result.message + '</div>';
                    bookingForm.reset();
                } else {
                    messageDiv.innerHTML = '<div class="message error">' + result.message + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
});