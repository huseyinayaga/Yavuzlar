<?php
session_start();
include 'db/connection.php';

if (isset($_GET['query'])) {
    $searchQuery = htmlspecialchars($_GET['query']);

    $st = $pdo->prepare("SELECT f.name,f.description,f.restaurantID,f.image_path FROM restaurant r 
    JOIN food f ON r.id = f.restaurantID WHERE r.name LIKE ?");
    $st->execute(['%' . $searchQuery . '%']);
    $foods = $st->fetchAll(PDO::FETCH_ASSOC);
} else {
    $foods = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arama Sonuçları</title>
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
    <h2 class="text-center" style="color:white;">Arama Sonuçları</h2>

    <?php if (count($foods) > 0): ?>
        <div class="row">
            <?php foreach ($foods as $food): ?>
                <div class="col-md-4 mb-4">
                    <div class="card" style="background-color:#07bc0c;">
                        <img src="<?php echo htmlspecialchars($food['image_path']); ?>" class="card-img-top" alt="Yemek Resmi">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($food['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($food['description']); ?></p>
                            <a href="menu.php?restaurant_id=<?php echo htmlspecialchars($food['restaurantID']); ?>" class="btn btn-primary">Sipariş Ver</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-white">Aradığınız yemek bulunamadı.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
