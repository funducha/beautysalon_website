<?php
// Заголовок, что возвращаем JSON
header('Content-Type: application/json');

// Простая заглушка. В реальности нужно читать appointments.json и вычислять свободные слоты.
$allTimes = ['10:00', '11:00', '12:00', '14:00', '15:00', '16:00', '18:00'];
// Для простоты вернем все, кроме одного "занятого"
$times = $allTimes;
if (isset($_GET['date']) && $_GET['date'] === date('Y-m-d')) {
    // На сегодня сделаем вид, что 12:00 занято
    $times = array_diff($allTimes, ['12:00']);
    $times = array_values($times);
}

echo json_encode(['times' => $times]);
?>