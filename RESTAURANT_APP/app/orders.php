<?php
session_start();
include 'db/connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['userID'];

$st = $pdo->prepare("SELECT * FROM orders WHERE userID = ? ORDER BY created_at DESC");
$st->execute([$user_id]);
$orders = $st->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişlerim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#121212;">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#121212;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img style="height:62px;width:auto;" src="logo/navbar-logo.png" alt="">
            </a>
        </div>
  </nav>

<div class="container mt-4">
    <h2  style="color:#07bc0c">Siparişlerim</h2>

    <?php if (empty($orders)): ?>
        <p>Henüz bir siparişiniz yok.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="card mb-3">
                <div class="card-header" >
                    <strong>Tarih:</strong> <?php echo htmlspecialchars($order['created_at']); ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Toplam Fiyat: <?php echo htmlspecialchars($order['total_price']); ?> TL</h5>
                    <h5 class="card-title">Sipariş Durumu: <?php echo htmlspecialchars($order['order_status']); ?></h5>
                    <?php
                    $st = $pdo->prepare("SELECT oi.*, f.name 
                                         FROM orders_items oi 
                                         INNER JOIN food f ON oi.foodID = f.id 
                                         WHERE oi.orderID = ?");
                    $st->execute([$order['id']]);
                    $order_items = $st->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <?php if (!empty($order_items)): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Ürün Adı</th>
                                    <th>Miktar</th>
                                    <th>Fiyat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                        <td><?php echo htmlspecialchars($item['price']); ?> TL</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Bu sipariş için ürün bilgisi bulunamadı.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
