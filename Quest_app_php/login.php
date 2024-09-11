<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giris Formu</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <img src="logo/image2.png" alt="" class="logo">
        <h2 style="color:white">Kullanici Giris</h2>
        <form action="loginQuery.php" method="POST" class="form">
            <input type="text" name="username" placeholder="Kullanici Adi" required style="height:50px">
            <input type="password" name="passwd" placeholder="Sifre" required style="height:50px">
            <button>Giris Yap</button>
        </form>
    </div>
</body>
</html>