<?php
session_start();
include 'db/connection.php';


$st = $pdo->prepare("SELECT * FROM restaurant");
$st->execute();
$restaurants = $st->fetchAll(PDO::FETCH_ASSOC);


if (!isset($_SESSION['reviewed_orders'])) {
    $_SESSION['reviewed_orders'] = [];
}


if (isset($_SESSION['userID'])) {
    $state = $pdo->prepare("SELECT orders.id as orderID, food.restaurantID, food.name 
                          FROM orders 
                          INNER JOIN orders_items ON orders.id = orders_items.orderID 
                          INNER JOIN food ON orders_items.foodID = food.id  
                          WHERE orders.userID = ? AND orders.order_status = ?");
    $state->execute([$_SESSION['userID'], 'Teslim Edildi']);
    $orders = $state->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orders as $order) {
        $orderID = $order['orderID'];
        $foodName = $order['name'];
        $restaurantID = $order['restaurantID'];


        $state2 = $pdo->prepare("SELECT * FROM comments WHERE userID = ? AND restaurantID = ?");
        $state2->execute([$_SESSION['userID'], $restaurantID]);
        $commentExists = $state2->fetch(PDO::FETCH_ASSOC);


        if (!$commentExists && !in_array($orderID, $_SESSION['reviewed_orders'])) {
            echo "<script>
                    if (confirm('$foodName yemeğiniz teslim edildi. Yorum yapmak ister misiniz?')) {
                        window.location.href = 'comments.php?restaurant_id=" . $restaurantID . "';
                    }
                  </script>";


            $_SESSION['reviewed_orders'][] = $orderID;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yemek Siparişi | Anasayfa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .card-img-top {
      height: 200px;
      object-fit: cover;
      width: 100%;
    }
    .card {
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    .card-body {
      flex: 1;
    }
    .dropdown-menu {
      background-color: #07bc0c;
      margin-left: -50px; 
    }
    .dropdown-item {
      color: #fff;
    }
    .dropdown-item:hover {
      background-color: #06a00a;
    }
    .navbar-nav.ms-3 {
      margin-left: 0;
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
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <form class="d-flex ms-3" role="search" action="search.php" method="GET">
          <input class="form-control me-2" type="search" name="query" placeholder="Yemek Arayın" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Ara</button>
        </form>

        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="basket.php"><i class="fas fa-shopping-cart"></i> Sepet (<span id="cart-count"><?php echo isset($_SESSION['total_quantity']) ? $_SESSION['total_quantity'] : 0; ?></span>)</a>
          </li>
        </ul>
        <ul class="navbar-nav ms-3">
          <li class="nav-item dropdown">
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
              <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user"></i> <?php echo ucfirst($_SESSION['username']); ?>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="profil.php">Profil Görüntüle</a></li>
                <li><a class="dropdown-item" href="addBalance.php">Bakiye Yükle</a></li>
                <li><a class="dropdown-item" href="orders.php">Siparişlerim</a></li>
                <li><a class="dropdown-item" href="logout.php">Çıkış Yap</a></li>
              </ul>
            <?php else: ?>
              <a class="nav-link" href="login.php">
                <i class="fas fa-user"></i> Giriş Yap
              </a>
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <h2 class="text-center" style="color:white;">Restoranlar</h2>
    <div class="row">
      <?php foreach($restaurants as $restaurant):
        $avgScoreStmt = $pdo->prepare("SELECT AVG(score) as avg_score FROM comments WHERE restaurantID = ?");
        $avgScoreStmt->execute([$restaurant['id']]);
        $avgScore = $avgScoreStmt->fetch(PDO::FETCH_ASSOC);
        $restScore = $avgScore['avg_score'] ? $avgScore['avg_score'] : 0; 
      ?>
      <div class="col-md-4 mb-4">
        <div class="card" style="background-color:#07bc0c;">
          <img src="<?php echo htmlspecialchars($restaurant['image_path']); ?>" class="card-img-top" alt="Restoran">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($restaurant['name']); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($restaurant['description']); ?></p>
            <div class="d-flex justify-content-between align-items-center">
            <a href="menu.php?restaurant_id=<?php echo htmlspecialchars($restaurant['id']); ?>" class="btn btn-primary">Sipariş Ver</a>
              <a href="comments.php?restaurant_id=<?php echo htmlspecialchars($restaurant['id']); ?>" class="btn btn-secondary">Yorumlar</a>
            </div>
            <div class="mt-2">
              <span class="text-warning">
                <i class="fas fa-star"></i> <?php echo number_format($restScore, 1);  ?> (12345+)
              </span>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach;  ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>