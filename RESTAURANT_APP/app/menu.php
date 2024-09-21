<?php
session_start();
include 'db/connection.php';


$restaurant_id = isset($_GET['restaurant_id']) ? intval($_GET['restaurant_id']) : 0;


if ($restaurant_id <= 0) {
    die("Geçersiz restoran ID.");
}


$st = $pdo->prepare("SELECT * FROM food WHERE restaurantID = ?");
$st->execute([$restaurant_id]);
$menu_items = $st->fetchAll(PDO::FETCH_ASSOC);


$st_restaurant = $pdo->prepare("SELECT name FROM restaurant WHERE id = ?");
$st_restaurant->execute([$restaurant_id]);
$restaurant = $st_restaurant->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($restaurant['name']); ?> - Menü</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .card-img-top {
      height: 200px;
      object-fit: cover;
      width: 100%;
    }
  </style>
  <link rel="icon" href="logo/header-logo.png" type="image/png">
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
    <h2 class="text-center" style="color:white;"><?php echo htmlspecialchars($restaurant['name']); ?> Menüler</h2>
    <div class="row">
      <?php foreach($menu_items as $item): ?>
      <div class="col-md-4 mb-4">
        <div class="card" style="background-color:#07bc0c;">
          <img src="<?php echo htmlspecialchars($item['image_path']); ?>" class="card-img-top" alt="Yemek">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
            <p class="card-text">Fiyat: <?php echo htmlspecialchars($item['price']); ?> TL</p>
            <p class="card-text">Indirim: <?php echo htmlspecialchars($item['discount']); ?> TL</p>
            <p class="card-text">Toplam: <?php echo htmlspecialchars($item['price'] - $item['discount']); ?> TL</p>
            <a href="food.php?menu_id=<?php echo htmlspecialchars($item['id']); ?>" class="btn btn-primary">Sepete Ekle</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
