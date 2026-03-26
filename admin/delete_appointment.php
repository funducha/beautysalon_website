<?php
require_once '../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $file = '../data/appointments.json';
    
    if (file_exists($file)) {
        $appointments = json_decode(file_get_contents($file), true);
        $appointments = array_filter($appointments, function($app) use ($id) {
            return $app['id'] != $id;
        });
        file_put_contents($file, json_encode(array_values($appointments), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

header('Location: dashboard.php');
exit;
?>