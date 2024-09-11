<?php
    session_start();
    if (isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === true) {
        session_unset();
        session_destroy();
        header("Location: index.php?message=Çıkış yapıldı");
        exit();
    }
    else{
        header("Location: login.php?message=Giriş yapmanız gerekiyor.");
        exit;
    }
    
?>