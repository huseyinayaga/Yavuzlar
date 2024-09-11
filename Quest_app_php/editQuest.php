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

    if(isset($_GET['questionID'])){
        $questionID = $_GET['questionID'];
        $st = $pdo->prepare("SELECT * FROM questions WHERE questionID = :questionID");
        $st->bindParam(':questionID',$questionID);
        $st->execute();

        $question = $st->fetch();
    }
    else{
        header("Location: questionsList.php?error=Soru bulunamadı.");
        exit();
    }
    include 'functions/helper.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $questTitle = $_POST['quest-title'];
        $optionOne = $_POST['option-one'];
        $optionTwo = $_POST['option-two'];
        $optionThree = $_POST['option-three'];
        $optionFour = $_POST['option-four'];
        $difficulty = $_POST['zorluk'];
        $correct = $_POST['secenek'];

        try {
            $query = "UPDATE questions SET questTitle = :questTitle, answerA = :answerA, answerB = :answerB, answerC = :answerC,
            answerD = :answerD, difficulty = :difficulty, correct = :correct WHERE questionID = :questionID";
            $st = $pdo->prepare($query);
            $st->bindParam(':questTitle',$questTitle);
            $st->bindParam(':answerA',$optionOne);
            $st->bindParam(':answerB',$optionTwo);
            $st->bindParam(':answerC',$optionThree);
            $st->bindParam(':answerD',$optionFour);
            $st->bindParam(':difficulty',$difficulty);
            $st->bindParam(':correct',$correct);
            $st->bindParam(':questionID',$questionID);
            $st->execute();

            echo "<script>
                alert('Soru başarıyla güncellendi');
                window.location.href='editQuest.php?questionID=" . $questionID . "';
            </script>";
        } catch (PDOException $e) {
            echo "Hata: " . $e->getMessage();
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soru Duzenle</title>
    <link rel="stylesheet" href="css/formStyle.css">
    <style>
    .form-input{
        display: flex;
        justify-content: left;
        border-radius: 10px;
        height: 30px;
        margin-bottom: 10px;
    }
    .form-input input{
        width: 100%;
        border-radius: 6px;

    }
    </style>
</head>
<body>
    <div class="main" style="background-color: #228B22;">
    <form id="questForm" action="editQuest.php?questionID=<?php echo $questionID; ?>" method="POST">
            <div class="form-input">
                <label>Soru Başlığını Girin: </label>
                <input type="text" name="quest-title" id="quest-title" value="<?php echo htmlspecialchars($question['questTitle']); ?>" required>
            </div>
            <div class="form-input">
                <label>A Şıkkını Girin: </label>
                <input type="text" id="option-one" name="option-one" value="<?php echo htmlspecialchars($question['answerA']); ?>" required>
            </div>
            <div class="form-input">
                <label>B Şıkkını Girin: </label>
                <input type="text" id="option-two" name="option-two" value="<?php echo htmlspecialchars($question['answerB']); ?>" required>
            </div>
            <div class="form-input">
                <label>C Şıkkını Girin: </label>
                <input type="text" id="option-three" name="option-three" value="<?php echo htmlspecialchars($question['answerC']); ?>" required>
            </div>
            <div class="form-input">
                <label>D Şıkkını Girin: </label>
                <input type="text" id="option-four" name="option-four" value="<?php echo htmlspecialchars($question['answerD']); ?>" required>
            </div>
        <div>
            <label style="cursor: pointer;">
                <input type="radio" name="zorluk" value="kolay" <?= $question['difficulty'] === 'kolay' ? 'checked' : '' ?> required>Kolay
            </label>
            <label style="cursor: pointer;">
                <input type="radio" name="zorluk" value="orta" <?= $question['difficulty'] === 'orta' ? 'checked' : '' ?> required>Orta
            </label>
            <label style="cursor: pointer;">
                <input type="radio" name="zorluk" value="zor" <?= $question['difficulty'] === 'zor' ? 'checked' : '' ?> required>Zor
            </label>
        </div>
        <div style="margin-top: 20px;">
            <label style="cursor: pointer;">
                <input type="radio" name="secenek" value="A" <?= $question['correct'] === 'A' ? 'checked' : '' ?> required>A
            </label>
            <label style="cursor: pointer;">
                <input type="radio" name="secenek" value="B" <?= $question['correct'] === 'B' ? 'checked' : '' ?> required>B
            </label>
            <label style="cursor: pointer;">
                <input type="radio" name="secenek" value="C" <?= $question['correct'] === 'C' ? 'checked' : '' ?> required>C
            </label>
            <label style="cursor: pointer;">
                <input type="radio" name="secenek" value="D" <?= $question['correct'] === 'D' ? 'checked' : '' ?> required>D
            </label>
        </div>
        <button type="submit" class="form-btn">Soruyu Güncelle</button>
        </form>
        <a href="questList.php"><button class="form-btn">Geri</button></a>

    </div>
    
</body>
</html>