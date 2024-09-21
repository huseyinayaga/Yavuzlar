<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        include 'db/connection.php';
        $st = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $st->bindParam(':username', $username);
        $st->execute();
        $result = $st->fetch(PDO::FETCH_ASSOC);
        
        if ($result && password_verify($password, $result['password'])) {
            $_SESSION['userID'] = $result['userID'];
            $_SESSION['roleID'] = $result['roleID'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['loggedin'] = true;


            if ($result['roleID'] == 1) {
                header("Location: admin.php");
            } elseif ($result['roleID'] == 2) {
                header("Location: company/companyHomePage.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            
            echo "Kullanici adi veya sifre hatali";
            exit();
        }
    } catch (PDOException $e) {
        echo "Hata oluştu: " . $e->getMessage();
    }
}
?>
