<?php
header('Content-Type: application/json');

// Подключаем логгер
require_once __DIR__ . '/../includes/logger.php';

// Получаем данные
$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    logFormSubmit($input['form'] ?? 'unknown', $input['data'] ?? []);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data']);
}
?>