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


$coupons = [];
if (isset($_GET['restaurant_id'])) {
    $restaurant_id = $_GET['restaurant_id'];
    $query = "SELECT * FROM cupon WHERE restaurantID = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$restaurant_id]);
    $coupons = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "<script>alert('Restoran ID bulunamadı.');
    window.location.href='restaurantList.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kupon Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#121212;">
    <div class="container mt-5">
        <h2 class="text-center mb-4" style="color:#07bc0c;">Kupon Yönetimi</h2>

        <div class="text-center mb-4">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCouponModal">Kupon Ekle</button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Kupon Kodu</th>
                        <th>İndirim</th>
                        <th>Oluşturulma Tarihi</th>
                        <th class="text-end">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($coupons) > 0): ?>
                        <?php foreach ($coupons as $coupon): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($coupon['id']); ?></td>
                                <td><?php echo htmlspecialchars($coupon['name']); ?></td>
                                <td><?php echo htmlspecialchars($coupon['discount']); ?>TL</td>
                                <td><?php echo htmlspecialchars($coupon['created_at']); ?></td>
                                <td class="text-end">
                                    <form action="deleteCoupon.php" method="POST" class="d-inline">
                                        <input type="hidden" name="cupon_id" value="<?php echo htmlspecialchars($coupon['id']); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Bu restorana ait kupon bulunamadı.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="companyManagement.php" class="btn btn-secondary">Geri Dön</a>
        </div>
    </div>

    <div class="modal fade" id="addCouponModal" tabindex="-1" aria-labelledby="addCouponModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCouponModalLabel">Yeni Kupon Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <form action="addCoupon.php" method="POST">
                        <input type="hidden" name="restaurant_id" value="<?php echo htmlspecialchars($restaurant_id); ?>">
                        <div class="mb-3">
                            <label for="couponCode" class="form-label">Kupon Kodu</label>
                            <input type="text" class="form-control" id="couponCode" name="cupon_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="couponDiscount" class="form-label">İndirim TL</label>
                            <input type="number" class="form-control" id="couponDiscount" name="cupon_discount" required min="1" max="100">
                        </div>
                        <button type="submit" class="btn btn-primary">Ekle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
