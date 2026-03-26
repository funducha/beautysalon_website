<?php
session_start();
header('Content-Type: application/json');

// Защита: доступ только для авторизованного админа
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
    exit;
}

$file = '../data/services.json';

// Чтение всех услуг (GET запрос)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'list') {
    $services = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    echo json_encode(['status' => 'success', 'services' => $services]);
    exit;
}

// POST запросы (add, delete, edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';

    $services = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    if ($action === 'add') {
        // Валидация
        if (empty($input['name']) || empty($input['price'])) {
            echo json_encode(['status' => 'error', 'message' => 'Заполните название и цену']);
            exit;
        }
        $newService = [
            'id' => time(), // Простой уникальный ID
            'name' => htmlspecialchars($input['name']),
            'price' => (int)$input['price'],
            'description' => htmlspecialchars($input['description'] ?? '')
        ];
        $services[] = $newService;
        file_put_contents($file, json_encode($services, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status' => 'success', 'message' => 'Услуга добавлена']);
        exit;
    }

    if ($action === 'delete') {
        $id = $input['id'] ?? 0;
        $services = array_filter($services, function($service) use ($id) {
            return $service['id'] != $id;
        });
        file_put_contents($file, json_encode(array_values($services), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status' => 'success', 'message' => 'Услуга удалена']);
        exit;
    }

    if ($action === 'edit') {
        $id = $input['id'] ?? 0;
        foreach ($services as &$service) {
            if ($service['id'] == $id) {
                $service['name'] = htmlspecialchars($input['name']);
                $service['price'] = (int)$input['price'];
                $service['description'] = htmlspecialchars($input['description'] ?? '');
                break;
            }
        }
        file_put_contents($file, json_encode($services, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status' => 'success', 'message' => 'Услуга обновлена']);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Неизвестное действие']);
?>