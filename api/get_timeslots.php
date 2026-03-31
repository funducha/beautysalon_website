<?php
// Заголовок, что возвращаем JSON
header('Content-Type: application/json');

// Базовые временные слоты - 5 слотов: 9:00, 11:00, 13:00, 15:00, 17:00
$allTimes = ['9:00', '11:00', '13:00', '15:00', '17:00'];

// Получаем параметры
$service_id = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;
$date = isset($_GET['date']) ? $_GET['date'] : '';

// Если дата не указана, возвращаем все слоты
if (empty($date)) {
    echo json_encode(['times' => $allTimes]);
    exit;
}

// Проверяем занятые слоты из appointments.json
$appointmentsFile = '../data/appointments.json';
$bookedTimes = [];

if (file_exists($appointmentsFile)) {
    $appointments = json_decode(file_get_contents($appointmentsFile), true);
    if (is_array($appointments)) {
        foreach ($appointments as $app) {
            // Проверяем совпадение по дате и услуге
            if ($app['date'] == $date && $app['service_id'] == $service_id) {
                $bookedTimes[] = $app['time'];
            }
        }
    }
}

// Фильтруем свободные слоты
$freeTimes = array_diff($allTimes, $bookedTimes);
$freeTimes = array_values($freeTimes);

echo json_encode(['times' => $freeTimes]);
?>