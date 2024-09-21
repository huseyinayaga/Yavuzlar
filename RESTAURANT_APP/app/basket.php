<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db/connection.php';

    $user_id = $_SESSION['userID'];
    $total_price = 0;
    $order_items = [];
    $restaurant_ids = [];

    foreach ($cart as $food_id => $details) {
        $st = $pdo->prepare("SELECT restaurantID FROM food WHERE id = ?");
        $st->execute([$food_id]);
        $food = $st->fetch(PDO::FETCH_ASSOC);

        if (!$food) {
            die('Geçersiz yemek ID.');
        }

        $restaurant_id = $food['restaurantID'];
        if (!in_array($restaurant_id, $restaurant_ids)) {
            $restaurant_ids[] = $restaurant_id;
        }

        $total_price += $details['totalPrice'];
        $order_items[] = [
            'food_id' => $food_id,
            'quantity' => $details['quantity'],
            'total_price' => $details['totalPrice']
        ];
    }

    if (count($restaurant_ids) > 1) {
        echo "<script>alert('Sepetinizdeki ürünler farklı restoranlardan. Lütfen tek bir restorana ait ürünleri sepetinize ekleyin.'); window.location.href='basket.php';</script>";
        exit();
    }

    try {
        $pdo->beginTransaction();

        
        $state = $pdo->prepare("SELECT balance FROM users WHERE userID = ?");
        $state->execute([$user_id]);
        $balance = $state->fetchColumn();

        if ($balance < $total_price) {
            echo "<script>alert('Bakiyeniz Yetersiz.'); window.location.href='addBalance.php';</script>";
            exit();
        }


        $st = $pdo->prepare("INSERT INTO orders (userID, total_price) VALUES (?, ?)");
        $st->execute([$user_id, $total_price]);
        $order_id = $pdo->lastInsertId();


        $st = $pdo->prepare("INSERT INTO orders_items(orderID, foodID, quantity, price) VALUES (?, ?, ?, ?)");
        $basket_st = $pdo->prepare("INSERT INTO basket(userID, foodID, note, quantity) VALUES (?, ?, ?, ?)");
        foreach ($order_items as $item) {

            $st->execute([$order_id, $item['food_id'], $item['quantity'], $item['total_price']]);


            $basket_st->execute([$user_id, $item['food_id'], $cart[$item['food_id']]['note'], $item['quantity']]);
        }


        $state2 = $pdo->prepare("UPDATE users SET balance = ? WHERE userID = ?");
        $state2->execute([($balance - $total_price), $user_id]);


        unset($_SESSION['cart']);
        $_SESSION['total_quantity'] = 0;

        $pdo->commit();

        echo "<script>alert('Siparişiniz başarılı bir şekilde alındı.'); window.location.href='index.php';</script>";


    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('Bir hata oluştu: " . $e->getMessage() . "'); window.location.href='basket.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#121212;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img style="height:62px;width:auto;" src="logo/navbar-logo.png" alt="">
            </a>
        </div>
  </nav>
<div class="container mt-4">
    <h2>Sepetim</h2>
    <?php if (empty($cart)): ?>
        <p>Sepetinizde ürün yok.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Ürün ID</th>
                    <th>Miktar</th>
                    <th>Not</th>
                    <th>Toplam Fiyat</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $food_id => $details): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($food_id); ?></td>
                        <td><?php echo htmlspecialchars($details['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($details['note']); ?></td>
                        <td><?php echo htmlspecialchars($details['totalPrice']); ?></td>
                        <td>
                            <a href="removeFromBasket.php?food_id=<?php echo htmlspecialchars($food_id); ?>" class="btn btn-danger">Kaldır</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form action="basket.php" method="post">
            <button type="submit" class="btn btn-primary">Sepeti Onayla</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
