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
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $customer_id = $_POST['customer_id'];
        if ($customer_id > 0) {
            include 'db/connection.php';
            $state = $pdo->prepare("SELECT * FROM users WHERE deleted_at IS NOT NULL AND userID = ?");
            $state->execute([$customer_id]);
            $result = $state->fetch(PDO::FETCH_ASSOC);
            if($result){
                echo "<script>alert('Musteri zaten silinmis');
                window.location.href='customerManagement.php';</script>";
                exit;
            }
            $statement = $pdo->prepare("UPDATE users SET deleted_at = NOW() WHERE userID = ?");
            $statement->execute([$customer_id]);
        
            if ($statement->rowCount() > 0) {
                echo "<script>alert('Musteri basariyla silindi');
                window.location.href='customerManagement.php';</script>";
            } else {
                echo "<script>alert('Musteri bulunamadi');</script>";
            }
        } else {
            echo "Geçersiz müşteri ID'si.";
        }
    }
?>