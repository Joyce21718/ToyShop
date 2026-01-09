<?php
header('Content-Type: application/json');
include 'db.php';

session_start();

set_error_handler(function ($severity, $message, $file, $line) {
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $message
    ]);
    exit;
});

$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (empty($email) || empty($password)) {
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required"
    ]);
    exit;
}

$stmt = $conn->prepare("
    SELECT id, full_name, email, password, is_verified 
    FROM users 
    WHERE email = ?
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
    exit;
}

$user = $result->fetch_assoc();

if ((int)$user['is_verified'] === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Please verify your email before logging in"
    ]);
    exit;
}

if (!password_verify($password, $user['password'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['full_name'];
$_SESSION['user_email'] = $user['email'];

echo json_encode([
    "status" => "success",
    "message" => "Login successful"
]);

$stmt->close();
$conn->close();
?>
