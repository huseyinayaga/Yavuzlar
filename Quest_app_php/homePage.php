<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php?message=Giriş yapmanız gerekiyor.");
        exit;
    }
    if(!isset($_SESSION['roleID']) || $_SESSION['roleID'] != 2){
        echo "<script>
            window.location.href='adminHomePage.php';
            alert('Bu sayfa Kullanici Sayfasi');
            </script>";
    }
    else{
        $username = $_SESSION['username'];
    }
    include 'functions/helper.php';
    try{
        $scores = getScores();

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
    <title>Home Page</title>
    <link rel="stylesheet" href="css/homePage.css">
</head>
<body>
    <div class="main">
        <div class="left">
            <a href="homePage.php">Ana sayfa</a>
            <a href="startQuiz.php">Cozmeye Basla</a>
        </div>
        <div class="center">
            <table class="score-table">
                <thead>
                    <tr>
                        <th>Kullanıcı Adı</th>
                        <th>Skor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; foreach($scores as $score): $i++; ?>
                    <tr>
                        <td><strong><?php echo $i; ?>. </strong><?php echo htmlspecialchars($score['username']); ?></td>
                        <td><?php echo htmlspecialchars($score['point']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="right">
            <div class="username-lbl"><label for="username"><?php echo htmlspecialchars($username);  ?></label></div>
            <div class="btn-logout">
                <form action="logout.php" method="POST" style="width:100%; height:100%;">
                    <button>Cikis Yap</button>
                </form>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
