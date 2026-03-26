<?php
require_once '../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $file = '../data/gift_certificates.json';
    
    if (file_exists($file)) {
        $certificates = json_decode(file_get_contents($file), true);
        $certificates = array_filter($certificates, function($cert) use ($id) {
            return $cert['id'] != $id;
        });
        file_put_contents($file, json_encode(array_values($certificates), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

header('Location: gift_certificates.php');
exit;
?>