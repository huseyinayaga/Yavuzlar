<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php?message=Giriş yapmanız gerekiyor.");
    exit;
}
if (!isset($_SESSION['roleID']) || $_SESSION['roleID'] !== 1) {
    echo "<script>alert('Bu sayfayı görüntüleme yetkiniz yok');
    window.location.href='index.php';
    </script>";
    exit;
}
include 'db/connection.php';

$users_orders = [];

if (isset($_GET['customer_username'])) {
    $customer_username = $_GET['customer_username'];
    $query = "SELECT U.userID, U.name, U.surname, U.username, F.name AS food_name, O.order_status, O.created_at 
              FROM users U 
              INNER JOIN orders O ON U.userID = O.userID 
              INNER JOIN orders_items OI ON O.id = OI.orderID 
              INNER JOIN food F ON OI.foodID = F.id
              WHERE O.order_status != ? AND U.deleted_at IS NULL AND U.username LIKE ?";
    $statement = $pdo->prepare($query);
    $statement->execute(['teslim edildi', '%' . $customer_username . '%']);
    $users_orders = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    $query = "SELECT U.userID, U.name, U.surname, U.username, F.name AS food_name, O.order_status, O.created_at 
              FROM users U 
              INNER JOIN orders O ON U.userID = O.userID 
              INNER JOIN orders_items OI ON O.id = OI.orderID 
              INNER JOIN food F ON OI.foodID = F.id
              WHERE O.order_status != ? AND U.deleted_at IS NULL";
    $statement = $pdo->prepare($query);
    $statement->execute(['teslim edildi']);
    $users_orders = $statement->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filter = $_POST['filter'];
    switch ($filter) {
        case 'active':
            $query = "SELECT U.userID, U.name, U.surname, U.username, F.name AS food_name, O.order_status, O.created_at 
                      FROM users U 
                      INNER JOIN orders O ON U.userID = O.userID 
                      INNER JOIN orders_items OI ON O.id = OI.orderID 
                      INNER JOIN food F ON OI.foodID = F.id
                      WHERE U.deleted_at IS NULL";
            $statement = $pdo->prepare($query);
            $statement->execute();
            $users_orders = $statement->fetchAll(PDO::FETCH_ASSOC);
            break;
        case 'all':
            $query = "SELECT U.userID, U.name, U.surname, U.username, F.name AS food_name, O.order_status, O.created_at 
                      FROM users U 
                      INNER JOIN orders O ON U.userID = O.userID 
                      INNER JOIN orders_items OI ON O.id = OI.orderID 
                      INNER JOIN food F ON OI.foodID = F.id";
            $statement = $pdo->prepare($query);
            $statement->execute();
            $users_orders = $statement->fetchAll(PDO::FETCH_ASSOC);
            break;
        case 'deleted':
            $query = "SELECT U.userID, U.name, U.surname, U.username, F.name AS food_name, O.order_status, O.created_at 
                      FROM users U 
                      INNER JOIN orders O ON U.userID = O.userID 
                      INNER JOIN orders_items OI ON O.id = OI.orderID 
                      INNER JOIN food F ON OI.foodID = F.id
                      WHERE U.deleted_at IS NOT NULL";
            $statement = $pdo->prepare($query);
            $statement->execute();
            $users_orders = $statement->fetchAll(PDO::FETCH_ASSOC);
            break;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Müşteri Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#f8f9fa;">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin.php">Admin Panel</a>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center mb-4">Müşteri Yönetimi</h2>

    <form method="GET">
        <div class="row mb-3">
            <div class="col-md-8">
                <input type="text" class="form-control" placeholder="Müşteri Ara..." name="customer_username" id="customerSearch" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Ara</button>
            </div>
        </div>
    </form>

    <form action="customerManagement.php" method="POST">
        <div class="row mb-3">
            <div class="col-md-12 text-center">
                <div class="btn-group" role="group" aria-label="Müşteri Filtreleme">
                    <button type="submit" class="btn btn-outline-secondary" name="filter" value="all">Tüm Müşteriler</button>
                    <button type="submit" class="btn btn-outline-success" name="filter" value="active">Aktif Müşteriler</button>
                    <button type="submit" class="btn btn-outline-danger" name="filter" value="deleted">Silinen Müşteriler</button>
                </div>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Adı</th>
                    <th>Soyadı</th>
                    <th>Kullanıcı Adı</th>
                    <th>Menu Adı</th>
                    <th>Sipariş Durumu</th>
                    <th>Oluşturulma Zamanı</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users_orders) > 0): ?>
                    <?php foreach ($users_orders as $user_order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user_order['userID']); ?></td>
                        <td><?php echo htmlspecialchars($user_order['name']); ?></td>
                        <td><?php echo htmlspecialchars($user_order['surname']); ?></td>
                        <td><?php echo htmlspecialchars($user_order['username']); ?></td>
                        <td><?php echo htmlspecialchars($user_order['food_name']); ?></td>
                        <td><?php echo htmlspecialchars($user_order['order_status']); ?></td>
                        <td><?php echo htmlspecialchars($user_order['created_at']); ?></td>
                        <td>
                            <form action="banCustomer.php" method="POST">
                                <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($user_order['userID']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Banla</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Arama sonucu bulunamadı.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <nav aria-label="Müşteri Listeleme Sayfaları">
        <ul class="pagination justify-content-center mt-4">
            <li class="page-item disabled">
                <a class="page-link">Önceki</a>
            </li>
            <li class="page-item active">
                <a class="page-link" href="#">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">Sonraki</a>
            </li>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
