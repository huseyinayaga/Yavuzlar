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
    include 'functions/helper.php';
    $questions = getQuestions();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorular</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        #input-quest-search{
            background-color: black;
            width: 100%;
            height: 100%;
        }
        .search-box{
            margin-top: 20%;
            justify-content: center;
            align-items: center;
            width: 70%;
            height: 40px;
            border-radius: 10px;
        }
        .questions-list{
            width: 90%;
            max-width: 600px;
            border-radius: 20px;
            padding: 20px;
        }
        .questions{
            display: flex;
            align-items: center;
            justify-content: left;
            height: 50px;
            margin-top: 10px;
            background-color:rgb(128, 148, 159);
            border-radius: 10px;
        }
        .quest{
            margin-left: 10px;
            width: 100%;
            height: 100%;
            cursor: pointer;
            white-space: nowrap;     
            overflow: hidden;
            text-overflow: ellipsis; 
        }
        .questions:hover{
            background-color: rgb(52, 47, 47);
        }
        .btn-edit{
            width: 100%;
            border-radius: 6px;
            height: 100%;
            margin-right: 15px;
            background-color: rgb(173, 216, 230);
            border-color: rgb(173, 216, 230);
            cursor: pointer;

        }
        .btn-delete{
            width: 100%;
            border-radius: 5px;
            height: 100%;
            margin-right: 15px;
            background-color: rgb(213, 123, 132);
            border: rgb(213, 123, 132);
            cursor: pointer;
        }

        .inline-form {
        display: inline;
        height: 40px;
        margin: 1%;
        }
        .btn-back{
            border: none;
            border-radius: 10px;
            width: 40%;
            height: 40px;
            background-color: antiquewhite;
            box-shadow: 3px 3px 10px antiquewhite;
            transition: all 0.3s;
            cursor: pointer;
        }
        .btn-back:hover{
            transform: scale(1.1);

        }

    </style>
</head>
<body>
    <div class="container">
        <div class="search-box">
            <input type="search" name="quest-search" id="input-quest-search" placeholder="Soru Ara">
        </div>
        <div class="questions-list">
            <?php $i=0; foreach($questions as $question): $i++; ?>
            <div class='questions'>
                <div class='quest'>
                    <strong><?php echo $i ?>.</strong><?php echo $question['questTitle'] ?>
                </div>
                <form action="editQuest.php" method="GET" class="inline-form">
                        <input type="hidden" name="questionID" value="<?php echo $question['questionID']; ?>">
                        <button class='btn-edit' type="submit">Düzenle</button>
                </form>
                <form action="deleteQuest.php" method="POST" class="inline-form">
                        <input type="hidden" name="questionID" value="<?php echo $question['questionID']; ?>">
                        <button class='btn-delete' type="submit" onclick="return confirm('Bu soruyu silmek istediğinizden emin misiniz?');">Sil</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
        <a href="adminHomePage.php" style="width:100%; margin-left:50%"><button class="btn-back">Geri</button></a>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('input-quest-search');
        const questionsList = document.querySelector('.questions-list');
    
        searchInput.addEventListener('input', function() {
        const searchTerm = searchInput.value.toLowerCase();
        const questions = questionsList.querySelectorAll('.questions');
        
                questions.forEach(question => {
                    const questTitle = question.querySelector('.quest').textContent.toLowerCase();
                    if (questTitle.includes(searchTerm)) {
                    question.style.display = '';
                    } else {
                        question.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>