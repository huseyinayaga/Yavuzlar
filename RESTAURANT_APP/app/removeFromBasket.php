<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$food_id = isset($_GET['food_id']) ? intval($_GET['food_id']) : 0;

if ($food_id > 0 && isset($_SESSION['cart'][$food_id])) {

    $_SESSION['total_quantity'] -= $_SESSION['cart'][$food_id]['quantity'];


    unset($_SESSION['cart'][$food_id]);
}


header("Location: basket.php");
exit;
?>
