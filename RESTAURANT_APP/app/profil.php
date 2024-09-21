<?php
session_start();
include 'db/connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$sql = 'SELECT name, surname, username, balance FROM users WHERE username = :username';
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];

    $update_sql = 'UPDATE users SET name = :name, surname = :surname, username = :new_username WHERE username = :current_username';
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->execute([
        'name' => $name,
        'surname' => $surname,
        'new_username' => $username,
        'current_username' => $_SESSION['username']
    ]);

    $_SESSION['username'] = $username;
    $_SESSION['success_message'] = 'Bilgileriniz güncellendi.';
    header('Location: profil.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_ARGON2ID);

    $password_sql = 'UPDATE users SET password = :password WHERE username = :username';
    $password_stmt = $pdo->prepare($password_sql);
    $password_stmt->execute(['password' => $new_password, 'username' => $username]);

    $_SESSION['success_message'] = 'Şifreniz güncellendi.';
    header('Location: profil.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #fff;
        }
        .container {
            margin-top: 50px;
        }
        .form-control, .btn {
            background-color: #07bc0c;
            border: none;
            color: white;
        }
        .form-control:focus {
            box-shadow: none;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .logo {
            height: 50px;
        }
    </style>
</head>
<body>


<nav class="navbar navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="logo/navbar-logo.png" alt="Logo" class="logo">
        </a>
    </div>
</nav>

<div class="container">
    <h2 class="text-center mb-4">Profil Sayfası</h2>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['success_message'];
            unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <h4>Hesabınızdaki Mevcut Para: ₺<?php echo number_format($user['balance'], 2); ?></h4>
    </div>

    <h4>Profil Güncelleme</h4>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Adınız</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="surname" class="form-label">Soyadınız</label>
            <input type="text" class="form-control" id="surname" name="surname" value="<?php echo $user['surname']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Kullanıcı Adınız</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
        </div>
        <button type="submit" name="update_profile" class="btn btn-primary">Profil Güncelle</button>
    </form>

    <h4 class="mt-4">Şifre Güncelleme</h4>
    <form method="POST">
        <div class="mb-3">
            <label for="new_password" class="form-label">Yeni Şifre</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <button type="submit" name="update_password" class="btn btn-primary">Şifre Güncelle</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
