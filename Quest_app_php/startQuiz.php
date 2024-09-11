<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php?message=Giriş yapmanız gerekiyor.");
        exit;
    }
    try{
        include 'functions/connection.php';
        $userID = $_SESSION['userID'];
        $st = $pdo->prepare("SELECT * FROM submissions WHERE userID = :userID");
        $st->bindParam(':userID',$userID);
        $st->execute();
        $result = $st->fetchAll();
        if(count($result) > 0){
            echo "<script>alert
            ('Bu Quizi daha once cozmussunuz');
            window.location.href='homePage.php';
            </script>";
        }
        include 'functions/helper.php';
        $questions = getQuestions();
            if(!$questions){
            echo "<script>alert('Hicbir Soru bulunamadi');
            window.location.href='homePage.php';
            </script>";
            }
    }
    catch(\Throwable $th){
        echo "Hata " .$th->getMessage();
    }
    
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Started Quizz</title>
    <link rel="stylesheet" href="css/formStyle.css">
</head>
<body style="background-color:#001e4d;">
    <div class="main" style="background-color:white;">
        <form method="POST" action="submitQuiz.php">
            <?php $i = 0; foreach($questions as $question): $i++; ?>
            <div class="quiz">
                <h2><strong><?php echo $i ?>. </strong><?php echo $question['questTitle']; ?></h2>
                <input type="radio" name="answer[<?php echo $question['questionID']; ?>]" value="A" required> <?php echo $question['answerA']; ?><br>
                <input type="radio" name="answer[<?php echo $question['questionID']; ?>]" value="B" required> <?php echo $question['answerB']; ?><br>
                <input type="radio" name="answer[<?php echo $question['questionID']; ?>]" value="C" required> <?php echo $question['answerC']; ?><br>
                <input type="radio" name="answer[<?php echo $question['questionID']; ?>]" value="D" required> <?php echo $question['answerD']; ?><br>
                <h4>Zorluk: <?php echo htmlspecialchars($question['difficulty']); ?></h4>
            </div>
            <?php endforeach; ?>
            <button type="submit">Quiz'i Gönder</button>
        </form>

    </div>
    
</body>
</html>
