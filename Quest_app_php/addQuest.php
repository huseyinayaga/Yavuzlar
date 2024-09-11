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
    <title>Soru Ekleme Formu</title>
    <link rel="stylesheet" href="css/formStyle.css">
</head>
<body>
<div class="main">
        <form id="questForm" action="addQuestQuery.php" method="POST">
            <div class="form-input">
                <label>Soru Başlığını Girin: </label>
                <input type="text" name="quest-title" id="quest-title" required maxlength="150">
            </div>
            <div class="form-input">
                <label>A Şıkkını Girin: </label>
                <input type="text" id="option-one" name="option-one" required>
            </div>
            <div class="form-input">
                <label>B Şıkkını Girin: </label>
                <input type="text" id="option-two" name="option-two" required>
            </div>
            <div class="form-input">
                <label>C Şıkkını Girin: </label>
                <input type="text" id="option-three" name="option-three" required>
            </div>
            <div class="form-input">
                <label>D Şıkkını Girin: </label>
                <input type="text" id="option-four" name="option-four" required>
            </div>
            <div>
                <label>Soru Zorluğunu Seçin :</label>
                <label style="cursor: pointer;"><input id="easy" type="radio" name="zorluk" value="kolay" required>Kolay</label>
                <label style="cursor: pointer;"><input id="mid" type="radio" name="zorluk" value="orta" required>Orta</label>
                <label style="cursor: pointer;"><input id="hard" type="radio" name="zorluk" value="zor" required>Zor</label>
            </div>
            <div style="margin-top: 20px;">
                <label>Dogru Şıkkı Seçin :</label>
                <label style="cursor: pointer;"><input id="A" type="radio" name="secenek" value="A" required>A</label>
                <label style="cursor: pointer;"><input id="B" type="radio" name="secenek" value="B" required>B</label>
                <label style="cursor: pointer;"><input id="C" type="radio" name="secenek" value="C" required>C</label>
                <label style="cursor: pointer;"><input id="D" type="radio" name="secenek" value="D" required>D</label>
            </div>
            <button type="submit" class="form-btn">Soruyu Ekle</button>
        </form>
        <a href="adminHomePage.php"><button class="form-btn">Geri</button>
        <p id="p" style="color: aquamarine; font-size: 20px;"></p>
    </div>
    
</body>
</html>