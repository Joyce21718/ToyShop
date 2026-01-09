<?php
session_start();
header('Content-Type: application/json');
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$fullName = trim($_POST['regName'] ?? '');
$email = strtolower(trim($_POST['regEmail'] ?? ''));
$phone = trim($_POST['regPhone'] ?? '');
$address = trim($_POST['regAddress'] ?? '');
$password = $_POST['regPassword'] ?? '';
$confirmPassword = $_POST['regConfirmPassword'] ?? '';

if (!$fullName || !$email || !$phone || !$address || !$password || !$confirmPassword) {
    echo json_encode(["status"=>"error","message"=>"All fields are required"]);
    exit;
}

if (!preg_match('/@gmail\.com$/', $email)) {
    echo json_encode(["status"=>"error","message"=>"Only Gmail allowed"]);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode(["status"=>"error","message"=>"Passwords do not match"]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$token = bin2hex(random_bytes(32));
$expiry = date("Y-m-d H:i:s", strtotime("+1 day"));

$stmt = $conn->prepare("
INSERT INTO users 
(full_name,email,phone,address,password,verify_token,token_expiry,is_verified)
VALUES (?,?,?,?,?,?,?,0)
");

$stmt->bind_param(
    "sssssss",
    $fullName,
    $email,
    $phone,
    $address,
    $hashedPassword,
    $token,
    $expiry
);

if (!$stmt->execute()) {
    echo json_encode(["status"=>"error","message"=>"Email already registered"]);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gaasjoyce51@gmail.com';
    $mail->Password = 'hhdc hcdp nqti flnf'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('gaasjoyce51@gmail.com', 'ToyShop');
    $mail->addAddress($email, $fullName);
$verifyLink = "https://mywebsite45.free.nf/backend/verify.php?token=$token";


    $mail->isHTML(true);
    $mail->Subject = 'Verify Your Email - ToyShop';
    $mail->Body = "
        <h2>Email Verification</h2>
        <p>Hello <b>$fullName</b>,</p>
        <p><a href='$verifyLink'>Verify Email</a></p>
    ";

    $mail->send();

    echo json_encode(["status"=>"success","message"=>"Check your Gmail to verify"]);

} catch (Exception $e) {
    echo json_encode(["status"=>"error","message"=>$mail->ErrorInfo]);
}

$stmt->close();
$conn->close();
