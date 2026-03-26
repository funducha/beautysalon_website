<?php
require_once 'includes/header.php';
?>
<div class="container">
    <div class="form-container" style="max-width: 500px;">
        <h1 class="page-title" style="text-align: center;">Активация подарочного сертификата</h1>
        <form id="giftActivationForm">
            <div class="form-group">
                <label for="gift_name">Ваше полное имя *</label>
                <input type="text" id="gift_name" name="name" required autocomplete="name">
            </div>
            <div class="form-group">
                <label for="gift_phone">Номер телефона *</label>
                <input type="tel" id="gift_phone" name="phone" required autocomplete="tel">
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
            <button type="submit" class="btn" style="width: 100%;">Активировать сертификат</button>
        </form>
        <div id="giftMessage" style="margin-top: 20px;"></div>
    </div>
</div>

<script>
    document.getElementById('giftActivationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const name = document.getElementById('gift_name').value.trim();
        const phone = document.getElementById('gift_phone').value.trim();
        const amount = document.getElementById('gift_amount').value;
        
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
            } else {
                showModal('Ошибка', result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showModal('Ошибка', 'Произошла ошибка. Попробуйте позже.');
        });
    });

    // Функция для показа модального окна с ошибкой
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