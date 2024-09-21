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
        $restaurant_id = $_POST['restaurant_id'];
        $cupon_name = $_POST['cupon_name'];
        $cupon_discount = $_POST['cupon_discount'];
    

        $query = "INSERT INTO cupon(restaurantID,name,discount) VALUES(?, ?, ?)";
        $statement = $pdo->prepare($query);
        $statement->execute([$restaurant_id,$cupon_name,$cupon_discount]);
        echo "<script>alert('Kupon Basariyla eklendi');
        window.location.href='couponManagement.php?restaurant_id=".$restaurant_id."';
        </script>";


    }
?>