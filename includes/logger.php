<?php
// Устанавливаем часовой пояс для Красноярска
date_default_timezone_set('Asia/Krasnoyarsk');

/**
 * Функция для записи логов действий пользователя
 * @param string $action Действие пользователя
 * @param array $data Дополнительные данные
 */
function logUserAction($action, $data = []) {
    // Получаем IP адрес пользователя
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    // Получаем страницу, на которой произошло действие
    $page = $_SERVER['REQUEST_URI'] ?? 'unknown';
    
    // Получаем время
    $time = date('Y-m-d H:i:s');
    
    // Получаем информацию о браузере
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    // Формируем запись лога
    $logEntry = [
        'time' => $time,
        'ip' => $ip,
        'page' => $page,
        'action' => $action,
        'data' => $data,
        'user_agent' => $userAgent
    ];
    
    // Если пользователь авторизован как админ, записываем его
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        $logEntry['admin'] = true;
    }
    
    // Преобразуем в JSON строку
    $logLine = json_encode($logEntry, JSON_UNESCAPED_UNICODE) . "\n";
    
    // Путь к файлу логов
    $logFile = __DIR__ . '/../data/user_actions.log';
    
    // Записываем в файл
    file_put_contents($logFile, $logLine, FILE_APPEND);
}

/**
 * Функция для записи посещений страниц
 */
function logPageView($pageName) {
    logUserAction('page_view', ['page' => $pageName]);
}

/**
 * Функция для записи кликов
 */
function logClick($buttonName, $additionalData = []) {
    logUserAction('click', array_merge(['button' => $buttonName], $additionalData));
}

/**
 * Функция для записи отправки формы
 */
function logFormSubmit($formName, $formData = []) {
    // Не записываем полные данные формы из соображений безопасности
    $safeData = [];
    if (isset($formData['service_id'])) {
        $safeData['service_id'] = $formData['service_id'];
    }
    if (isset($formData['date'])) {
        $safeData['date'] = $formData['date'];
    }
    if (isset($formData['time'])) {
        $safeData['time'] = $formData['time'];
    }
    logUserAction('form_submit', ['form' => $formName, 'data' => $safeData]);
}
?>