<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php?message=Giriş yapmanız gerekiyor.");
    exit;
}
if (!isset($_SESSION['roleID']) || $_SESSION['roleID'] !== 2) {
    echo "<script>alert('Bu sayfayı görüntüleme yetkiniz yok'); window.location.href='index.php';</script>";
    exit;
}

include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['food_id'])) {
        $food_id = $_POST['food_id'];

        $query = "UPDATE food SET deleted_at = NOW() WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$food_id]);

        echo "<script>alert('Yemek başarıyla silindi.'); window.location.href='companyHomePage.php';</script>";
    } else {
        echo "<script>alert('Yemek ID bulunamadı.'); window.location.href='companyHomePage.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Geçersiz istek.'); window.location.href='companyHomePage.php';</script>";
    exit;
}
?>
