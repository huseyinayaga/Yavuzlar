<?php
session_start();
include 'db/connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$userID = $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];

    if ($amount > 0) {

        $sql = 'SELECT balance FROM users WHERE userID = :userID';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userID' => $userID]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        $new_balance = $user['balance'] + $amount;
        $update_sql = 'UPDATE users SET balance = :balance WHERE userID = :userID';
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute(['balance' => $new_balance, 'userID' => $userID]);

        echo "<script>alert('Bakiye başarıyla yüklendi!');</script>";
    } else {
        echo "<script>alert('Geçerli bir miktar girin.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakiye Yükleme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: white;
        }
        .container {
            margin-top: 50px;
        }
        .form-control, .btn {
            background-color: #07bc0c;
            border: none;
            color: white;
        }
        .form-control:focus {
            box-shadow: none;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .logo {
            height: 50px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="logo/navbar-logo.png" alt="Logo" class="logo">
            </a>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center mb-4">Bakiye Yükleme</h2>

        <form method="POST">
            <div class="mb-3">
                <label for="amount" class="form-label">Yüklenecek Miktar (₺)</label>
                <input type="number" class="form-control" id="amount" name="amount" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Bakiye Yükle</button>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
