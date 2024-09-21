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
    include '../db/connection.php';
    $user_id = $_SESSION['userID'];

    $state = $pdo->prepare("SELECT companyID FROM users WHERE userID = ?");
    $state->execute([$user_id]);
    $companyID = $state->fetchColumn();

    $query = "SELECT * FROM restaurant WHERE companyID = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$companyID]);
    $restaurants = $statement->fetchAll(PDO::FETCH_ASSOC);
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
                    <?php foreach ($restaurants as $restaurant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($restaurant['id']); ?></td>
                            <td><?php echo htmlspecialchars($restaurant['name']); ?></td>
                            <td><?php echo htmlspecialchars($restaurant['description']); ?></td>
                            <td class="text-end">
                                <a href="restaurantManagement.php?restaurant_id=<?php echo htmlspecialchars($restaurant['id']); ?>" class="btn btn-info btn-sm">Yemek Yönetimi</a>
                                <a href="orderManagement.php?restaurant_id=<?php echo htmlspecialchars($restaurant['id']); ?>" class="btn btn-warning btn-sm">Sipariş Yönetimi</a>
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
