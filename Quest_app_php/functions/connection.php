<?php
    try{
        $pdo = new PDO('sqlite:/Applications/XAMPP/xamppfiles/htdocs/QUEST-APP-PHP/Quest_app_php/db/quest_app.db');

    
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
    }
    catch(\Throwable $th){
        echo "Hata Olustu " .$th;
    }
?>