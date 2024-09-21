<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php?message=Giriş yapmanız gerekiyor.");
        exit;
    }
    if(!isset($_SESSION['roleID']) || $_SESSION['roleID'] !== 2){
        echo "<script>alert('Bu sayfayı görüntüleme yetkiniz yok');
        window.location.href='index.php';
        </script>";
        exit;
    }

    if (!isset($_GET['restaurant_id'])) {
        echo "<script>alert('Restoran ID bulunamadı.');
        window.location.href='companyHomePage.php';
        </script>";
        exit;
    } else {
        $restaurant_id = $_GET['restaurant_id'];
    }

    include '../db/connection.php';

    $foods = [];
    if (isset($_GET['food_name'])) {
        $food_name = $_GET['food_name'];
        $query = "SELECT * FROM food WHERE name LIKE ? AND restaurantID = ? AND deleted_at IS NULL";
        $state = $pdo->prepare($query);
        $state->execute(["%$food_name%", $restaurant_id]);
        $foods = $state->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $query = "SELECT * FROM food WHERE restaurantID = ? AND deleted_at IS NULL";
        $statement = $pdo->prepare($query);
        $statement->execute([$restaurant_id]);
        $foods = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Yönetimi</title>
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
        <h2 class="text-center mb-4" style="color:#07bc0c;">Yemek Yönetimi</h2>

        <div class="text-center mb-4">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFoodModal">Yemek Ekle</button>
        </div>

        <form method="GET" class="mb-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="Yemek Ara..." name="food_name" required>
                </div>
                <div class="col-md-4">
                    <input type="hidden" name="restaurant_id" value="<?php echo htmlspecialchars($restaurant_id); ?>">
                    <button type="submit" class="btn btn-primary w-100">Ara</button>
                </div>
            </div>
        </form>


        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Yemek Adı</th>
                        <th>Fiyat</th>
                        <th>Açıklama</th>
                        <th>Resim</th>
                        <th class="text-end">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($foods as $food): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($food['id']); ?></td>
                            <td><?php echo htmlspecialchars($food['name']); ?></td>
                            <td><?php echo htmlspecialchars($food['price']); ?> ₺</td>
                            <td><?php echo htmlspecialchars($food['description']); ?></td>
                            <td><img src="../<?php echo htmlspecialchars($food['image_path']); ?>" alt="" style="width:100px"></td>
                            <td class="text-end">
                                <form action="updateFood.php" method="GET" class="d-inline">
                                    <input type="hidden" name="food_id" value="<?php echo htmlspecialchars($food['id']); ?>">
                                    <button type="submit" class="btn btn-warning btn-sm">Güncelle</button>
                                </form>
                                <form action="deleteFood.php" method="POST" class="d-inline">
                                    <input type="hidden" name="food_id" value="<?php echo htmlspecialchars($food['id']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addFoodModal" tabindex="-1" aria-labelledby="addFoodModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFoodModalLabel">Yeni Yemek Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <form action="addFood.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="foodName" class="form-label">Yemek Adı</label>
                            <input type="text" class="form-control" id="foodName" name="food_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="foodPrice" class="form-label">Fiyat</label>
                            <input type="number" class="form-control" id="foodPrice" name="food_price" required>
                        </div>
                        <div class="mb-3">
                            <label for="foodPrice" class="form-label">Indirim</label>
                            <input type="number" class="form-control" id="foodDiscount" name="food_discount" required>
                        </div>
                        <div class="mb-3">
                            <label for="foodDesc" class="form-label">Açıklama</label>
                            <input type="text" class="form-control" id="foodDesc" name="food_desc" required>
                        </div>
                        <div class="mb-3">
                            <label for="foodImage" class="form-label">Yemek Fotoğrafı</label>
                            <input type="file" class="form-control" id="foodImage" name="food_image" accept="image/*" required>
                        </div>
                        <input type="hidden" name="restaurant_id" value="<?php echo htmlspecialchars($restaurant_id); ?>">
                        <button type="submit" class="btn btn-primary">Ekle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
