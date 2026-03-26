<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (empty($input['name']) || empty($input['phone']) || empty($input['amount'])) {
    echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    exit;
}

$name = trim(htmlspecialchars($input['name']));
$phone = trim(htmlspecialchars($input['phone']));
$amount = (int)$input['amount'];

$file = '../data/gift_certificates.json';
$certificates = [];

if (file_exists($file)) {
    $certificates = json_decode(file_get_contents($file), true);
}

$newCertificate = [
    'id' => time() . rand(100, 999),
    'client_name' => $name,
    'client_phone' => $phone,
    'amount' => $amount,
    'status' => 'activated',
    'activated_at' => date('Y-m-d H:i:s')
];

$certificates[] = $newCertificate;
file_put_contents($file, json_encode($certificates, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['success' => true, 'message' => 'Сертификат на сумму ' . $amount . ' ₽ успешно активирован!']);
?>