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


$restaurants = [];
if (isset($_GET['companyID'])) {
    $companyID = $_GET['companyID'];
    $query = "SELECT * FROM restaurant WHERE companyID = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$companyID]);
    $restaurants = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "<script>alert('Firma ID bulunamadı.');
    window.location.href='companyManagement.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#121212;">
    <div class="container mt-5">
        <h2 class="text-center mb-4" style="color:#07bc0c;">Restoran Listesi</h2>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Restoran Adı</th>
                        <th>Açıklama</th>
                        <th class="text-end">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($restaurants) > 0): ?>
                        <?php foreach ($restaurants as $restaurant): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($restaurant['id']); ?></td>
                                <td><?php echo htmlspecialchars($restaurant['name']); ?></td>
                                <td><?php echo htmlspecialchars($restaurant['description']); ?></td>
                                <td class="text-end">
                                    <a href="menu.php?restaurant_id=<?php echo htmlspecialchars($restaurant['id']); ?>">
                                        <button class="btn btn-info btn-sm">Menüleri Gör</button>
                                    </a>
                                    <form action="couponManagement.php" method="GET" class="d-inline">
                                        <input type="hidden" name="restaurant_id" value="<?php echo htmlspecialchars($restaurant['id']); ?>">
                                        <button type="submit" class="btn btn-warning btn-sm">Kuponlar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Bu firmaya ait restoran bulunamadı.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="companyManagement.php" class="btn btn-secondary">Geri Dön</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
