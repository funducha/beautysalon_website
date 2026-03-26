<?php
require_once '../includes/auth_check.php';
require_once '../includes/header.php';
?>
<div class="container">
    <h1 class="page-title">Управление услугами</h1>
    <a href="/beauty-salon/admin/dashboard.php" class="btn" style="margin-bottom: 20px;">← Назад</a>

    <!-- Форма добавления -->
    <div class="form-container" style="max-width: 500px;">
        <h3>Добавить новую услугу</h3>
        <form id="addServiceForm">
            <div class="form-group">
                <label>Название</label>
                <input type="text" id="serviceName" required>
            </div>
            <div class="form-group">
                <label>Цена (₽)</label>
                <input type="number" id="servicePrice" required>
            </div>
            <div class="form-group">
                <label>Описание</label>
                <textarea id="serviceDesc" rows="3"></textarea>
            </div>
            <button type="submit" class="btn">Добавить</button>
        </form>
        <div id="addMessage"></div>
    </div>

    <!-- Список услуг -->
    <h2 style="margin-top: 40px;">Существующие услуги</h2>
    <div id="servicesList"></div>
</div>

<script>
    // Функция для загрузки и отображения услуг
    function loadServices() {
        fetch('/beauty-salon/api/admin_services.php?action=list')
            .then(response => response.json())
            .then(data => {
                let html = '<div class="services-grid">';
                data.services.forEach(service => {
                    html += `
                        <div class="service-card" data-id="${service.id}">
                            <h3 contenteditable="false">${service.name}</h3>
                            <p contenteditable="false">${service.description}</p>
                            <div class="price" contenteditable="false">${service.price} ₽</div>
                            <button class="btn-small edit-btn">✎</button>
                            <button class="btn-small delete-btn" style="background-color: #dc3545;">🗑</button>
                        </div>
                    `;
                });
                html += '</div>';
                document.getElementById('servicesList').innerHTML = html;

                // Добавляем обработчики для кнопок Удалить и Редактировать
                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', deleteService);
                });
                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', editService);
                });
            });
    }

    // Добавление услуги
    document.getElementById('addServiceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const data = {
            action: 'add',
            name: document.getElementById('serviceName').value,
            price: document.getElementById('servicePrice').value,
            description: document.getElementById('serviceDesc').value
        };
        fetch('/beauty-salon/api/admin_services.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            document.getElementById('addMessage').innerHTML = `<div class="message ${result.status}">${result.message}</div>`;
            if (result.status === 'success') {
                document.getElementById('addServiceForm').reset();
                loadServices(); // Перезагрузить список
            }
        });
    });

    // Функция удаления
    function deleteService(e) {
        const card = e.target.closest('.service-card');
        const id = card.dataset.id;
        if (confirm('Удалить услугу?')) {
            fetch('/beauty-salon/api/admin_services.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({action: 'delete', id: id})
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    loadServices(); // Перезагрузить список
                } else {
                    alert('Ошибка удаления');
                }
            });
        }
    }

    // Функция редактирования
    function editService(e) {
        const card = e.target.closest('.service-card');
        const isEditing = e.target.textContent === 'Сохранить';
        const id = card.dataset.id;
        const nameEl = card.querySelector('h3');
        const descEl = card.querySelector('p');
        const priceEl = card.querySelector('.price');

        if (!isEditing) {
            // Переключаемся в режим редактирования
            nameEl.contentEditable = true;
            descEl.contentEditable = true;
            priceEl.contentEditable = true;
            e.target.textContent = '✓';
        } else {
            // Сохраняем изменения
            nameEl.contentEditable = false;
            descEl.contentEditable = false;
            priceEl.contentEditable = false;
            e.target.textContent = '✎';

            const data = {
                action: 'edit',
                id: id,
                name: nameEl.textContent,
                price: priceEl.textContent.replace(' ₽', ''),
                description: descEl.textContent
            };
            fetch('/beauty-salon/api/admin_services.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.status !== 'success') {
                    alert('Ошибка сохранения');
                    loadServices(); // Перезагрузить, чтобы откатить
                }
            });
        }
    }

    // Загружаем услуги при загрузке страницы
    loadServices();
</script>

<?php require_once '../includes/footer.php'; ?>