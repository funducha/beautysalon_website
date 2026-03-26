<?php
header('Content-Type: application/json');

// Подключаем логгер
require_once __DIR__ . '/../includes/logger.php';

// Получаем данные
$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    logUserAction('time_on_page', [
        'page' => $input['page'] ?? 'unknown',
        'time_spent_seconds' => $input['time_spent'] ?? 0
    ]);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data']);
}
?>