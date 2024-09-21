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
    include 'db/connection.php';
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $cupon_id = $_POST['cupon_id'];

        $state=$pdo->prepare("SELECT restaurantID FROM cupon WHERE id = ?");
        $state->execute([$cupon_id]);
        $restaurant_id = $state->fetchColumn();

        if($cupon_id > 0 ){
            $query = "DELETE FROM cupon WHERE id = ?";
            $statement = $pdo->prepare($query);
            $statement->execute([$cupon_id]);

            echo "<script>
            alert('Kupon basariyla silindi');
            window.location.href='couponManagement.php?restaurant_id=".$restaurant_id."';
            </script>";
        }
        else{
            echo "<script>
            alert('Gecersiz Kupon ID');
            window.location.href='couponManagement.php?restaurant_id=".$restaurant_id."';
            </script>";
        }

    }
?>