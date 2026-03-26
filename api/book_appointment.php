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

// ИСПРАВЛЕНО: путь к файлу appointments.json
$file = '../data/appointments.json';
$appointments = [];

if (file_exists($file)) {
    $appointments = json_decode(file_get_contents($file), true);
    if (!is_array($appointments)) {
        $appointments = [];
    }
}

// Создаем новую запись
$newAppointment = [
    'id' => time() . rand(100, 999), // уникальный id
    'client_name' => htmlspecialchars($name),
    'client_phone' => htmlspecialchars($phone),
    'service_id' => $service_id,
    'date' => $date,
    'time' => $time,
    'created_at' => date('Y-m-d H:i:s')
];

$appointments[] = $newAppointment;

// Сохраняем обратно в файл
$result = file_put_contents($file, json_encode($appointments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

if ($result === false) {
    echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Вы успешно записаны!']);
?>