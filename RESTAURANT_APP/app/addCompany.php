<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php?message=Giriş yapmanız gerekiyor.");
        exit;
    }
    if (!isset($_SESSION['roleID']) || $_SESSION['roleID'] !== 1) {
        echo "<script>alert('Bu sayfayı görüntüleme yetkiniz yok');
        window.location.href='index.php';
        </script>";
        exit;
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        include 'db/connection.php';
        $company_name = $_POST['company_name'];
        $company_desc = $_POST['company_desc'];
        $query = "INSERT INTO company(name,description) VALUES(?,?)";
        $statement = $pdo->prepare($query);
        $statement->execute([$company_name,$company_desc]);

        echo "<script>alert('Firma başarıyla eklendi');
                window.location.href='companyManagement.php';
                </script>";
    }
?>