<?php
session_start();
// Если уже залогинен, отправляем в админку
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: /beauty-salon/admin/dashboard.php');  // ← ИСПРАВЛЕНО
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    // Читаем хеш из файла
    if (file_exists('data/admin.json')) {
        $adminData = json_decode(file_get_contents('data/admin.json'), true);
        if (isset($adminData['password_hash']) && password_verify($password, $adminData['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: /beauty-salon/admin/dashboard.php');  // ← ИСПРАВЛЕНО
            exit;
        } else {
            $error = 'Неверный пароль';
        }
    } else {
        $error = 'Ошибка конфигурации';
    }
}
require_once 'includes/header.php';
?>

<div class="container">
    <div class="form-container" style="max-width: 400px;">
        <h2 style="text-align: center; margin-bottom: 20px;">Вход для администратора</h2>
        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn" style="width: 100%;">Войти</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>