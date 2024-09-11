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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home Page</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
       <div class="wrapper-btn">
            <button style="background-color: #2c7abe; border: 1px solid #2c7abe;"
            onclick="getAddQuest()">Soru Ekle</button>
            <button style="border: 1px solid white;" onclick="getQuestList()">Sorulari Listele</button>
       </div>
        <!--
       <div class="wrapper-btn">
            <button style="background-color: #05a50b; border: 1px solid #05a50b;" 
            onclick="getEditQuest()">Soru Guncelle</button>
            <button style="background-color: rgba(255, 0, 0, 0.641); border: 1px solid rgba(255, 0, 0, 0.641);"
            onclick="getDeleteQuest()">Soru Sil</button>
        </div>
        -->
        <div class="wrapper-btn"  style="width:40%;">
            <form action="logout.php" method="POST" style="display:flex; width:100%; height:100%;">
                <button type="submit" style="background-color:orange;">Cikis Yap</button>
            </form>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>