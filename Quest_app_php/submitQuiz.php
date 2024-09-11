<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php?message=Giriş yapmanız gerekiyor.");
        exit;
    }
    include 'functions/helper.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answer'])){
        $selectedAnswers = $_POST['answer'];
        $userID = $_SESSION['userID'];
        $score =0;

        try{
            foreach($selectedAnswers as $questionID =>$answer){
                $st = $pdo->prepare("SELECT * FROM questions WHERE questionID = :questionID");
                $st->bindParam(':questionID',$questionID);
                $st->execute();
                $question = $st->fetch();

                $points = 0;
                $difficulty = $question['difficulty'];
                switch($difficulty){
                    case 'kolay':
                        $points=1;
                        break;
                    case 'orta':
                        $points=2;
                    case 'zor':
                        $points=3;
                        break;
                }

                if($question['correct'] === $answer){
                    $score +=$points;
                }
                $isCorrect = ($question['correct'] == $answer);
                logQuest($userID,$questionID,$answer,$isCorrect);
            }

            logScore($userID,$score);

            echo "<script>
            alert('Quiz başarıyla gönderildi!');
            window.location.href='homePage.php';
            </script>";
        }
        
        catch(\Throwable $th){
            echo "Hata " .$th->getMessage();
        }

    }
?>