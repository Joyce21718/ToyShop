<?php
session_start();
require "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "msg" => "Not logged in"]);
    exit;
}

$userId = $_SESSION['user_id'];
$baseDir = "../uploads/profile/";
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0777, true);
}

if (!isset($_FILES['profile'])) {
    echo json_encode(["status" => "error", "msg" => "No file uploaded"]);
    exit;
}

$file = $_FILES['profile'];
$allowed = ["jpg", "jpeg", "png", "webp"];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    echo json_encode(["status" => "error", "msg" => "Invalid file type"]);
    exit;
}

if ($file['size'] > 2 * 1024 * 1024) {
    echo json_encode(["status" => "error", "msg" => "Max 2MB allowed"]);
    exit;
}

$newName = "user_" . $userId . "_" . time() . "." . $ext;
$uploadPath = $baseDir . $newName;

$stmt = $conn->prepare("SELECT profile_img FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$old = $result->fetch_assoc()['profile_img'];

if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
    if ($old && $old !== "default.png" && file_exists($baseDir . $old)) {
        unlink($baseDir . $old);
    }

    $stmt = $conn->prepare("UPDATE users SET profile_img=? WHERE id=?");
    $stmt->bind_param("si", $newName, $userId);
    $stmt->execute();

    $_SESSION['profile_img'] = $newName;

    echo json_encode([
        "status" => "success",
        "image" => $newName
    ]);
} else {
    echo json_encode(["status" => "error", "msg" => "Upload failed"]);
}
