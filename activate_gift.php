<?php
require_once 'includes/header.php';
?>
<div class="container">
    <div class="form-container" style="max-width: 500px;">
        <h1 class="page-title" style="text-align: center;">Активация подарочного сертификата</h1>
        <form id="giftActivationForm">
            <div class="form-group">
                <label for="gift_name">Полное имя получателя *</label>
                <input type="text" id="gift_name" name="name" required autocomplete="name">
            </div>
            <div class="form-group">
                <label for="gift_phone">Номер телефона *</label>
                <input type="tel" id="gift_phone" name="phone" required autocomplete="tel" placeholder="8 999 123 45 67">
            </div>
            <div class="form-group">
                <label for="gift_email">Электронная почта</label>
                <input type="email" id="gift_email" name="email" autocomplete="email" placeholder="example@mail.ru">
            </div>
            <div class="form-group">
                <label for="gift_amount">Выберите номинал сертификата *</label>
                <select id="gift_amount" name="amount" required>
                    <option value="">-- Выберите сумму --</option>
                    <option value="2000">2 000 ₽</option>
                    <option value="3000">3 000 ₽</option>
                    <option value="4000">4 000 ₽</option>
                    <option value="5000">5 000 ₽</option>
                </select>
            </div>
            
            <!-- Блок согласия на обработку ПДн -->
            <div class="form-group" style="margin-top: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="gift_consent" required style="width: auto;">
                    <span>Я согласен(на) на обработку моих персональных данных в соответствии с <a href="#" style="color: #b76e79;" onclick="alert('Политика обработки ПДн: Ваши данные используются только для активации сертификата и не передаются третьим лицам.'); return false;">политикой конфиденциальности</a> *</span>
                </label>
            </div>

            <button type="submit" class="btn" style="width: 100%;">Активировать сертификат</button>
        </form>
        <div id="giftMessage" style="margin-top: 20px;"></div>
    </div>
</div>

<script>
    // Функция форматирования номера телефона
    function formatPhoneNumber(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 0) {
            let formatted = '';
            if (value.length >= 1) formatted = value.substring(0, 1);
            if (value.length >= 2) formatted = value.substring(0, 1) + ' ' + value.substring(1, 4);
            if (value.length >= 5) formatted = value.substring(0, 1) + ' ' + value.substring(1, 4) + ' ' + value.substring(4, 7);
            if (value.length >= 8) formatted = value.substring(0, 1) + ' ' + value.substring(1, 4) + ' ' + value.substring(4, 7) + ' ' + value.substring(7, 9);
            if (value.length >= 10) formatted = value.substring(0, 1) + ' ' + value.substring(1, 4) + ' ' + value.substring(4, 7) + ' ' + value.substring(7, 9) + ' ' + value.substring(9, 11);
            input.value = formatted;
        }
    }
    
    // Применяем форматирование к полю телефона
    const giftPhoneInput = document.getElementById('gift_phone');
    if (giftPhoneInput) {
        giftPhoneInput.addEventListener('input', function() {
            formatPhoneNumber(this);
        });
    }
    
    document.getElementById('giftActivationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Проверка согласия на обработку ПДн
        const consentCheckbox = document.getElementById('gift_consent');
        if (!consentCheckbox.checked) {
            showModal('Внимание', 'Пожалуйста, дайте согласие на обработку персональных данных');
            return;
        }

        const name = document.getElementById('gift_name').value.trim();
        let phone = document.getElementById('gift_phone').value.trim();
        const email = document.getElementById('gift_email').value.trim();
        const amount = document.getElementById('gift_amount').value;
        
        // Убираем пробелы из номера телефона перед отправкой
        phone = phone.replace(/\s/g, '');
        
        if (!name) {
            showModal('Ошибка', 'Пожалуйста, введите ваше имя');
            return;
        }
        if (!phone) {
            showModal('Ошибка', 'Пожалуйста, введите номер телефона');
            return;
        }
        if (!amount) {
            showModal('Ошибка', 'Пожалуйста, выберите сумму сертификата');
            return;
        }
        
        const data = {
            name: name,
            phone: phone,
            email: email,
            amount: amount
        };

        fetch('/beauty-salon/api/activate_gift.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showSuccessModal('🎉 Сертификат успешно активирован!', 'Ваш сертификат на сумму ' + amount + ' ₽ активирован. Всю информацию уточняйте у администратора в салоне.');
                document.getElementById('giftActivationForm').reset();
                // Сбрасываем чекбокс согласия
                document.getElementById('gift_consent').checked = false;
            } else {
                showModal('Ошибка', result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showModal('Ошибка', 'Произошла ошибка. Попробуйте позже.');
        });
    });

    // Функции модальных окон
    function showModal(title, message) {
        const modal = createModal(title, message, false);
        document.body.appendChild(modal);
    }

    function showSuccessModal(title, message) {
        const modal = createModal(title, message, true);
        document.body.appendChild(modal);
    }

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
        closeBtn.onclick = () => modal.remove();
        
        const titleEl = document.createElement('h3');
        titleEl.textContent = title;
        titleEl.style.color = '#b76e79';
        titleEl.style.margin = '0';
        titleEl.style.padding = '25px 30px 10px 30px';
        titleEl.style.fontSize = '1.5rem';
        titleEl.style.fontFamily = "'Playfair Display', serif";
        
        const messageEl = document.createElement('p');
        messageEl.textContent = message;
        messageEl.style.padding = '0 30px';
        messageEl.style.margin = '10px 0 25px 0';
        messageEl.style.lineHeight = '1.6';
        messageEl.style.color = '#5a4a42';
        
        modalContent.appendChild(closeBtn);
        modalContent.appendChild(titleEl);
        modalContent.appendChild(messageEl);
        
        const closeModalBtn = document.createElement('button');
        closeModalBtn.textContent = hasReturnButton ? 'Вернуться на главную' : 'Закрыть';
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
        
        if (hasReturnButton) {
            closeModalBtn.onclick = () => {
                window.location.href = '/beauty-salon/index.php';
            };
        } else {
            closeModalBtn.onclick = () => modal.remove();
        }
        
        modalContent.appendChild(closeModalBtn);
        modal.appendChild(modalContent);
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
        
        return modal;
    }
</script>

<?php require_once 'includes/footer.php'; ?>