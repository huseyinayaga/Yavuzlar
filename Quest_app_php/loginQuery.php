<?php
    session_start();
    include 'functions/connection.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['username'];
        $password = $_POST['passwd'];

    }
    try{
        $st = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $st->bindParam(':username',$username);
        $st->bindParam(':password',$password);
        $st->execute();
        $result = $st->fetch(PDO::FETCH_ASSOC);
        if($result >0){
            $_SESSION['loggedin']=true;
            $_SESSION['roleID']=$result['roleID'];
            $_SESSION['username']=$result['username'];
            $_SESSION['userID']=$result['userID'];
            if($result['roleID'] == 1){
                header("Location: adminHomePage.php");
            }
            else{
                header("Location: homePage.php");
            }
        }
        else{
            echo "Gecersiz kullanici adi veya sifre";
            exit();
        }
    }
    catch(\Throwable $th){
        echo "Hata olustu" . $th->getMessage();
    }
?>