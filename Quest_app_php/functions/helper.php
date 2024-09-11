<?php
    include 'connection.php';
    
    function getQuestions(){
        global $pdo;
        try{
            $query = "SELECT * FROM questions";
            $st = $pdo->prepare($query);
            $st->execute();

            $questions = $st->fetchAll(PDO::FETCH_ASSOC);
            return $questions;
        }
        catch(\Throwable $th){
            echo "Hata " . $th->getMessage();
            return false;
        }
    }
    function logQuest($userID,$questionID,$selectedAnswer,$isCorrect){
        if($isCorrect){
            $isCorrect = "true";
        }
        else{
            $isCorrect = "false";
        }
        global $pdo;
        $query = "INSERT INTO submissions (questionID, userID, thawDate, selectedAnswer,isCorrect)
        VALUES(:questionID, :userID, datetime('now'), :selectedAnswer, :isCorrect)";
        $st = $pdo->prepare($query);
        $st->bindParam(':userID',$userID);
        $st->bindParam(':questionID',$questionID);
        $st->bindParam(':selectedAnswer',$selectedAnswer);
        $st->bindParam(':isCorrect',$isCorrect);
        $st->execute();
    }
    
    function logScore($userID,$points){
        global $pdo;
        $query = "INSERT INTO scores(userID,point) VALUES(:userID, :point)";
        $st = $pdo->prepare($query);
        $st->bindParam(':userID',$userID);
        $st->bindParam(':point',$points);
        $st->execute();

    }

    function getScores(){
        global $pdo;
        $st = $pdo->prepare("SELECT scores.point,users.username FROM scores INNER JOIN users ON scores.userID = users.userID
        ORDER BY scores.point DESC, users.username ASC ");
        $st->execute();
        $scores = $st->fetchAll(PDO::FETCH_ASSOC);
        return $scores;
    }

?>