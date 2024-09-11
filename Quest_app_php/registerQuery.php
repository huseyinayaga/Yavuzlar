<?php
    include 'functions/connection.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['passwd'];
    }
    try{
        $st = $pdo->prepare("INSERT INTO users (name, surname, username, password, email) 
        VALUES (:name, :surname, :username, :password, :email)");
        $st->bindParam(':name',$name);
        $st->bindParam(':surname',$surname);
        $st->bindParam(':username',$username);
        $st->bindParam(':password',$password);
        $st->bindParam(':email',$email);
        $st->execute();
        echo "<script>
        alert('Kayit basarili')
        window.location.href='login.php';
        </script>";
    }
    catch(\Throwable $th){
        echo "Hata Olustu" . $th->getMessage();
    }
    
    
?>