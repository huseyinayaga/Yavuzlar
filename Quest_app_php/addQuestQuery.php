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
    include 'functions/connection.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $questTitle = $_POST['quest-title'];
        $optionOne = $_POST['option-one'];
        $optionTwo = $_POST['option-two'];
        $optionThree = $_POST['option-three'];
        $optionFour = $_POST['option-four'];
        $difficulty = $_POST['zorluk'];
        $correct = $_POST['secenek'];

        $query = "INSERT INTO questions (questTitle, answerA, answerB, answerC, answerD, difficulty, correct) VALUES (:questTitle, :answerA, :answerB, :answerC, :answerD, :difficulty, :correct)";

        $st = $pdo->prepare($query);
        $st->bindParam(':questTitle',$questTitle);
        $st->bindParam(':answerA',$optionOne);
        $st->bindParam(':answerB',$optionTwo);
        $st->bindParam(':answerC',$optionThree);
        $st->bindParam(':answerD',$optionFour);
        $st->bindParam(':difficulty',$difficulty);
        $st->bindParam(':correct',$correct);
        
        if($st->execute()){
            echo "<script>
            alert('Soru basariyla Eklendi');
            window.location.href='addQuest.php';
            </script>";
        }
        else{
            echo "<script>
            alert('Bir hata olustu');
            window.location.href='addQuest.php';
            </script>";
        }
    }
?>