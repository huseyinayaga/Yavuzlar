<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    if ($password !== $confirm_password) {
        echo "<script>alert('Şifreler eşleşmiyor'); window.location.href='register.php';</script>";
        exit();
    }


    $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

    try {
        include 'db/connection.php';


        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $checkUser = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $checkUser->bindParam(':username', $username);
        $checkUser->execute();
        
        if ($checkUser->rowCount() > 0) {
            echo "<script>alert('Bu kullanıcı adı zaten mevcut'); window.location.href='register.php';</script>";
            exit();
        }


        $st = $pdo->prepare("INSERT INTO users (roleID, name, surname, username, password) 
                             VALUES (3, :name, :surname, :username, :password)");
        $st->bindParam(':name', $name);
        $st->bindParam(':surname', $surname);
        $st->bindParam(':username', $username);
        $st->bindParam(':password', $hashedPassword);
        $st->execute();

        echo "<script>alert('Kayıt başarılı'); window.location.href='login.php';</script>";
        exit();
        
    } catch (PDOException $e) {
        echo "Hata: " . $e->getMessage();
        exit();
    }
}
?>
