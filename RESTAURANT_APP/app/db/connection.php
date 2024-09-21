<?php
$servername = "db";
$db_username = "restaurant_app";
$db_password = "12345678";
$dbname = "restaurant_app";

try {

    $dsn = "mysql:host=$servername;dbname=$dbname";
    $pdo = new PDO($dsn, $db_username, $db_password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
} catch (PDOException $e) {
    echo "Bağlantı başarısız: " . $e->getMessage();
}
?>