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

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status"=>"error","message"=>"Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(["status"=>"error","message"=>"No data received"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];
$user_name = $_SESSION['user_name'];

$name = $data['name'] ?? '';
$price = $data['price'] ?? '';
$quantity = (int)($data['quantity'] ?? 1);
$image = $data['image'] ?? '';
$address = $data['address'] ?? '';
$payment = $data['payment'] ?? 'COD';
$status = "Pending";
$order_date = date("Y-m-d H:i:s");

if (!$name || !$price || !$address) {
    echo json_encode(["status"=>"error","message"=>"Missing required fields"]);
    exit;
}

$stmt = $conn->prepare("
INSERT INTO orders 
(user_id,name,price,quantity,image,address,payment,status,order_date)
VALUES (?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
    "ississsss",
    $user_id,
    $name,
    $price,
    $quantity,
    $image,
    $address,
    $payment,
    $status,
    $order_date
);

if ($stmt->execute()) {

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gaasjoyce51@gmail.com';
        $mail->Password = 'hhdc hcdp nqti flnf';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('gaasjoyce51@gmail.com', 'ToyShop');
        $mail->addAddress($user_email, $user_name);

        $mail->isHTML(true);
        $mail->Subject = 'Order Confirmation - ToyShop';
        $mail->Body = "
            <h2>Order Confirmation</h2>
            <p>Hi <b>$user_name</b>,</p>
            <ul>
                <li>Product: $name</li>
                <li>Price: $price</li>
                <li>Quantity: $quantity</li>
                <li>Address: $address</li>
                <li>Payment: $payment</li>
                <li>Status: $status</li>
                <li>Date: $order_date</li>
            </ul>
            <p>Thank you for shopping with us!</p>
        ";

        $mail->send();

        echo json_encode([
            "status"=>"success",
            "message"=>"Order saved and email sent"
        ]);

    } catch (Exception $e) {
        echo json_encode([
            "status"=>"success",
            "message"=>"Order saved but email failed: ".$mail->ErrorInfo
        ]);
    }

} else {
    echo json_encode(["status"=>"error","message"=>$stmt->error]);
}

$stmt->close();
$conn->close();
