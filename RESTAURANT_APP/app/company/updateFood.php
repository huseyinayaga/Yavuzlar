<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php?message=Giriş yapmanız gerekiyor.");
    exit;
}
if (!isset($_SESSION['roleID']) || $_SESSION['roleID'] !== 2) {
    echo "<script>alert('Bu sayfayı görüntüleme yetkiniz yok');
    window.location.href='index.php';
    </script>";
    exit;
}

include '../db/connection.php';

if (isset($_GET['food_id'])) {
    $food_id = $_GET['food_id'];
    $query = "SELECT * FROM food WHERE id = ? AND deleted_at IS NULL";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$food_id]);
    $food = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$food) {
        echo "<script>alert('Yemek bulunamadı.'); window.location.href='companyHomePage.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Yemek ID bulunamadı.'); window.location.href='companyHomePage.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $food_name = $_POST['food_name'];
    $food_price = $_POST['food_price'];
    $food_discount = floatval($_POST['food_discount']);
    $food_desc = $_POST['food_desc'];

    $query = "UPDATE food SET name = ?, price = ?, description = ?, discount = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$food_name, $food_price, $food_desc, $food_discount, $food_id]);

    echo "<script>alert('Yemek başarıyla güncellendi.'); window.location.href='companyHomePage.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Güncelle</title>
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
        <h2 class="text-center mb-4" style="color:#07bc0c;">Yemek Güncelle</h2>

        <form action="updateFood.php?food_id=<?php echo htmlspecialchars($food['id']); ?>" method="POST">
            <div class="mb-3">
                <label for="foodName" class="form-label" style="color:#07bc0c;">Yemek Adı</label>
                <input type="text" class="form-control" id="foodName" name="food_name" value="<?php echo htmlspecialchars($food['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="foodPrice" class="form-label" style="color:#07bc0c;">Fiyat</label>
                <input type="number" class="form-control" id="foodPrice" name="food_price" value="<?php echo htmlspecialchars($food['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="foodDiscount" class="form-label" style="color:#07bc0c;">İndirim</label>
                <input type="text" class="form-control" id="foodDiscount" name="food_discount" value="<?php echo htmlspecialchars($food['discount']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="foodDesc" class="form-label" style="color:#07bc0c;">Açıklama</label>
                <input type="text" class="form-control" id="foodDesc" name="food_desc" value="<?php echo htmlspecialchars($food['description']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
