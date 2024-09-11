<?php
session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php?message=Giriş yapmanız gerekiyor.");
        exit;
    }
    if(!isset($_SESSION['roleID']) || $_SESSION['roleID'] != 1){
        echo "<script>
            window.location.href='homePage.php';
            alert('Bu sayfayi goruntulemeye yetkiniz yok');
            </script>";
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include 'functions/connection.php';
        $questionID = $_POST['questionID'];

        try{
            $st = $pdo->prepare("DELETE FROM questions WHERE questionID = :questionID");
            $st->bindParam(':questionID',$questionID);
            $st->execute();
            echo "<script>
            alert('Soru basariyla silindi');
            window.location.href='questList.php';
            </script>";
        }
        catch(\Throwable $th){
            echo "Hata " .$th->getMessage();
        }
    }
?>