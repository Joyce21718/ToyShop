<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['name'], $data['price'], $data['quantity'], $data['img'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$itemExists = false;
foreach ($_SESSION['cart'] as &$cartItem) {
    if ($cartItem['name'] === $data['name']) {
        $cartItem['quantity'] += $data['quantity'];
        $itemExists = true;
        break;
    }
}
if (!$itemExists) {
    $_SESSION['cart'][] = $data;
}

echo json_encode(['status' => 'success']);
