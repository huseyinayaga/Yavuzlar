<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php?message=Giriş yapmanız gerekiyor.");
    exit;
}
if (!isset($_SESSION['roleID']) || $_SESSION['roleID'] !== 2) {
    echo "<script>alert('Bu sayfayı görüntüleme yetkiniz yok'); window.location.href='index.php';</script>";
    exit;
}

include '../db/connection.php';
$user_id = $_SESSION['userID'];
if (!isset($_GET['restaurant_id'])) {
    echo "<script>alert('Geçersiz Restoran ID'); window.location.href='companyHomePage.php';</script>";
    exit;
}

$restaurant_id = $_GET['restaurant_id'];
$query = "SELECT u.username, o.id, o.order_status, o.total_price 
FROM orders o JOIN orders_items oi ON o.id = oi.orderID 
JOIN food f ON oi.foodID = f.id 
JOIN users u ON o.userID = u.userID 
WHERE f.restaurantID = ?";
$statement = $pdo->prepare($query);
$statement->execute([$restaurant_id]);
$orders = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['order_id']) && isset($_POST['status'])) {
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];

        $updateQuery = "UPDATE orders SET order_status = ? WHERE id = ?";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([$status, $order_id]);

        echo "<script>alert('Sipariş durumu başarıyla güncellendi.'); window.location.href='orderManagement.php?restaurant_id=".htmlspecialchars($restaurant_id)."';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#121212;">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #07bc0c;">
        <div class="container-fluid">
            <a class="navbar-brand" href="companyHomePage.php">Yemek Siparişi Yönetimi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Çıkış Yap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="text-center mb-4" style="color:#07bc0c;">Sipariş Yönetimi</h2>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Müşteri</th>
                        <th>Toplam Tutar</th>
                        <th>Durum</th>
                        <th class="text-end">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['username']); ?></td>
                            <td><?php echo htmlspecialchars($order['total_price']); ?> ₺</td>
                            <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                            <td class="text-end">
                                <form action="orderManagement.php?restaurant_id=<?php echo htmlspecialchars($restaurant_id); ?>" method="POST" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                                    <select name="status" class="form-select" required>
                                        <option value="Hazırlanıyor" <?php echo ($order['order_status'] === 'Hazırlanıyor') ? 'selected' : ''; ?>>Hazırlanıyor</option>
                                        <option value="Yola Çıktı" <?php echo ($order['order_status'] === 'Yola Çıktı') ? 'selected' : ''; ?>>Yola Çıktı</option>
                                        <option value="Teslim Edildi" <?php echo ($order['order_status'] === 'Teslim Edildi') ? 'selected' : ''; ?>>Teslim Edildi</option>
                                    </select>
                                    <button type="submit" class="btn btn-success btn-sm">Güncelle</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
