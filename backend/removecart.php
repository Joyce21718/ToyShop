<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$name = isset($_POST['name']) ? $_POST['name'] : '';

if ($name) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['name'] === $name) {
            unset($_SESSION['cart'][$key]);

            $_SESSION['cart'] = array_values($_SESSION['cart']);
            break;
        }
    }
}

header("Location: ../page/cart.php");
exit;
