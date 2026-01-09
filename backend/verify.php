<?php
include 'db.php'; 

$token = isset($_GET['token']) ? $_GET['token'] : '';

if (empty($token)) {
    die("Invalid verification link.");
}

$stmt = $conn->prepare("
    SELECT id, full_name, token_expiry, is_verified 
    FROM users 
    WHERE verify_token = ?
");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid or expired verification link.");
}

$user = $result->fetch_assoc();

if ((int)$user['is_verified'] === 1) {
    die("Your email is already verified.");
}

$current_time = date("Y-m-d H:i:s");
if ($current_time > $user['token_expiry']) {
    die("Verification link has expired. Please register again.");
}

$update = $conn->prepare("
    UPDATE users 
    SET is_verified = 1, 
        verify_token = NULL, 
        token_expiry = NULL 
    WHERE id = ?
");
$update->bind_param("i", $user['id']);

if ($update->execute()) {
    echo "<h2>Email Verified!</h2>";
    echo "<p>Hi <b>" . htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8') . "</b>, your email has been successfully verified.</p>";
    echo "<p><a href='../page/login.html'>Click here to login</a></p>";
} else {
    echo "Failed to verify email. Please try again.";
}

$update->close();
$stmt->close();
$conn->close();
?>
