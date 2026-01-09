<?php
session_start();
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$orderId = $data['order_id'];
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "DELETE FROM orders WHERE id=? AND user_id=?"
);
$stmt->bind_param("ii", $orderId, $userId);
$stmt->execute();

echo json_encode(["status" => "success"]);
