<?php 
session_start();
include 'db/connection.php';

$menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0;

if ($menu_id <= 0) {
    die("Geçersiz yemek ID.");
}

$st = $pdo->prepare("SELECT * FROM food WHERE id = ?");
$st->execute([$menu_id]);
$food = $st->fetch(PDO::FETCH_ASSOC);

if (!$food) {
    die("Yemek bulunamadı.");
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($food['name']); ?> | Yemek Detayları</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .card-img-top {
      height: 200px;
      object-fit: cover;
      width: 100%;
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
      </div>
    </div>
  </nav>
  <div class="container mt-4">
    <div class="card" style="background-color:#07bc0c;">
      <img src="<?php echo htmlspecialchars($food['image_path']); ?>" class="card-img-top" alt="Yemek">
      <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($food['name']); ?></h5>
        <p class="card-text"><?php echo htmlspecialchars($food['description']); ?></p>
        <span class="text-white"><?php echo htmlspecialchars($food['price']-$food['discount']); ?> TL</span>
        <form action="addToBasket.php" method="post" class="mt-4">
          <input type="hidden" name="menu_id" value="<?php echo htmlspecialchars($food['id']); ?>">
          <div class="mb-3">
            <label for="quantity" class="form-label text-white">Miktar:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
          </div>
          <div class="mb-3">
            <label for="note" class="form-label text-white">Not:</label>
            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="cupon" class="form-label text-white">Cupon Kodu:</label>
            <input type="text" class="form-control" id="cupon" name="cupon"></input>
          </div>
          <button type="submit" class="btn btn-primary">Sepete Ekle</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
