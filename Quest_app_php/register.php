<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanici Kayit Formu</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Kullanici Kayit</h2>
        <div class="form">
            <form action="registerQuery.php" method="POST">
                <input type="text" name="name" placeholder="Adinizi Girin" required>
                <input type="text" name="surname" placeholder="Soyadinizi Girin" required>
                <input type="email" name="email" placeholder="Email Girin" required>
                <input type="text" name="username" placeholder="Kullanici Adi Girin" required>
                <input type="password" name="passwd" placeholder="Sifre Girin" required>
                <button type="submit">Register</button>
            </form>
        </div>
    </div>
</body>
</html>