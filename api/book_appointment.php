<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Krasnoyarsk');

$input = json_decode(file_get_contents('php://input'), true);

if (empty($input['name']) || empty($input['phone']) || empty($input['service_id']) || empty($input['date']) || empty($input['time'])) {
    echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    exit;
}

$name = trim($input['name']);
$phone = trim($input['phone']);
$email = isset($input['email']) ? trim($input['email']) : '';
$service_id = (int)$input['service_id'];
$date = trim($input['date']);
$time = trim($input['time']);

$file = '../data/appointments.json';
$appointments = [];

if (file_exists($file)) {
    $appointments = json_decode(file_get_contents($file), true);
    if (!is_array($appointments)) {
        $appointments = [];
    }
    
    // Проверка на дубликат (та же дата, время и услуга)
    foreach ($appointments as $app) {
        if ($app['date'] == $date && $app['time'] == $time && $app['service_id'] == $service_id) {
            echo json_encode(['success' => false, 'message' => 'Это время уже занято. Выберите другое время.']);
            exit;
        }
    }
}

$newAppointment = [
    'id' => time() . rand(100, 999),
    'client_name' => htmlspecialchars($name),
    'client_phone' => htmlspecialchars($phone),
    'client_email' => htmlspecialchars($email),
    'service_id' => $service_id,
    'date' => $date,
    'time' => $time,
    'created_at' => date('Y-m-d H:i:s')
];

$appointments[] = $newAppointment;

$result = file_put_contents($file, json_encode($appointments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

if ($result === false) {
    echo json_encode(['success' => false, 'message' => 'Ошибка сохранения данных']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Вы успешно записаны!']);
?>