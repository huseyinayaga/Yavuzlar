<?php
session_start();
include 'db/connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_id = filter_input(INPUT_POST, 'menu_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    $note = filter_input(INPUT_POST, 'note', FILTER_DEFAULT);
    $cupon = filter_input(INPUT_POST, 'cupon', FILTER_DEFAULT);

    if ($menu_id <= 0 || $quantity <= 0) {
        die('Geçersiz değerler.');
    }


    $st = $pdo->prepare("SELECT * FROM food WHERE id = ?");
    $st->execute([$menu_id]);
    $food = $st->fetch(PDO::FETCH_ASSOC);

    if (!$food) {
        die('Geçersiz yemek ID.');
    }


    $discount = 0; 
    $st = $pdo->prepare("SELECT C.discount, R.id 
                         FROM cupon C 
                         INNER JOIN restaurant R ON C.restaurantID = R.id 
                         INNER JOIN food F ON R.id = F.restaurantID 
                         WHERE C.name = ?");
    $st->execute([$cupon]);
    $result = $st->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['id'] === $food['restaurantID']) {
        $discount = $result['discount'];
    }
    else if($cupon === ''){
        $discount=0;
    }
    else {

        echo "<script>
                alert('Geçersiz kupon');
                window.location.href = 'food.php?menu_id=" . htmlspecialchars($menu_id, ENT_QUOTES, 'UTF-8') . "';
              </script>";
        exit();
    }


    $price = $food['price'] - $food['discount'];
    $totalPrice = ($price - $discount) * $quantity;


    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
        $_SESSION['total_quantity'] = 0;
    }


    if (isset($_SESSION['cart'][$menu_id])) {
        $_SESSION['total_quantity'] -= $_SESSION['cart'][$menu_id]['quantity']; 
        $_SESSION['cart'][$menu_id]['quantity'] += $quantity;
        $_SESSION['cart'][$menu_id]['totalPrice'] = ($price - $discount) * $_SESSION['cart'][$menu_id]['quantity'];
    } else {

        $_SESSION['cart'][$menu_id] = [
            'quantity' => $quantity,
            'note' => $note,
            'totalPrice' => $totalPrice
        ];
    }

    // Toplam miktarı güncelleyin
    $_SESSION['total_quantity'] += $_SESSION['cart'][$menu_id]['quantity'];

    // Yönlendirme yapmadan önce hiçbir çıktı olmadığından emin olun
    header("Location: basket.php");
    exit;
} else {
    echo "Geçersiz istek.";
}

?>
