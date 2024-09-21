<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php?message=Giriş yapmanız gerekiyor.");
        exit;
    }
    if(!isset($_SESSION['roleID']) || $_SESSION['roleID'] !== 2){
        echo "<script>alert('Bu sayfayı görüntüleme yetkiniz yok');
        window.location.href='index.php';
        </script>";
        exit;
    }
    include '../db/connection.php';
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $restaurant_id = $_POST['restaurant_id'];
        $food_name = $_POST['food_name'];
        $food_price = $_POST['food_price'];
        $food_discount = $_POST['food_discount'];
        $food_desc = $_POST['food_desc'];
               
        if (isset($_FILES['food_image']) && $_FILES['food_image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['food_image']['tmp_name'];
            $file_name = basename($_FILES['food_image']['name']);
            $upload_dir = 'food_photo/';
            $file_path = $upload_dir . $file_name;
    

            if (move_uploaded_file($file_tmp, '../'.$file_path)) {

                $query = "INSERT INTO food (restaurantID, name, description, image_path, price) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$restaurant_id, $food_name, $food_desc, $file_path, $food_price]);
    
                echo "<script>alert('Yemek başarıyla eklendi.'); window.location.href='companyHomePage.php';</script>";
            } else {
                echo "<script>alert('Dosya yüklenirken bir hata oluştu.'); window.location.href='companyHomePage.php';</script>";
            }
        }
        else {
            echo "<script>alert('Fotoğraf yüklenmedi.'); window.location.href='companyHomePage.php';</script>";
        }
    }



?>