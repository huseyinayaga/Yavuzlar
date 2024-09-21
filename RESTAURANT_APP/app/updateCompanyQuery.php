<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php?message=Giriş yapmanız gerekiyor.");
    exit;
}

include 'db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = $_POST['company_id'];
    $company_name = $_POST['company_name'];
    $company_desc = $_POST['company_desc'];

    $query = "UPDATE company SET name = ?, description = ? WHERE id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$company_name, $company_desc, $company_id]);

    echo "<script>alert('Firma başarıyla güncellendi');
    window.location.href='companyManagement.php';
    </script>";
    exit;
}
?>
