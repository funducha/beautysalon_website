<?php
header('Content-Type: application/json');

// Получаем данные из POST-запроса (AJAX отправит JSON)
$input = json_decode(file_get_contents('php://input'), true);

// Простейшая валидация
if (empty($input['name']) || empty($input['phone']) || empty($input['service_id']) || empty($input['date']) || empty($input['time'])) {
    echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    exit;
}

$name = trim($input['name']);
$phone = trim($input['phone']);
$service_id = (int)$input['service_id'];
$date = trim($input['date']);
$time = trim($input['time']);

// Читаем текущие записи
$file = 'data/appointments.json';
$appointments = [];
if (file_exists($file)) {
    $appointments = json_decode(file_get_contents($file), true);
}

// Создаем новую запись (без id пользователя, т.к. нет регистрации)
$newAppointment = [
    'id' => time(), // простой уникальный id
    'client_name' => htmlspecialchars($name), // Защита XSS
    'client_phone' => htmlspecialchars($phone),
    'service_id' => $service_id,
    'date' => $date,
    'time' => $time,
    'created_at' => date('Y-m-d H:i:s')
];

$appointments[] = $newAppointment;

// Сохраняем обратно в файл
file_put_contents($file, json_encode($appointments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['success' => true, 'message' => 'Вы успешно записаны!']);
?>