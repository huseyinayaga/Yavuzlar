<?php
    session_start();
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
        header("Location: login.php?message=Giriş yapmanız gerekiyor.");
        exit;
    }
    if(!isset($_SESSION['roleID']) || $_SESSION['roleID'] !== 1){
        echo "<script>alert('Bu sayfayı görüntüleme yetkiniz yok');
        window.location.href='index.php';
        </script>";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#121212;">
    <div class="container mt-5">
        <h2 class="text-center mb-4" style="color:#07bc0c;">Admin Panel</h2>
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Müşteri Yönetimi</h5>
                        <a href="customerManagement.php" class="btn btn-primary">Yönet</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Firma Yönetimi</h5>
                        <a href="companyManagement.php" class="btn btn-primary">Yönet</a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <form action="logout.php" method="POST">
                    <button type="submit" class="btn btn-danger">Çıkış Yap</button>
                </form>
            </div>
            
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
