<?php
session_start();
include 'db/connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php?message=Giriş yapmanız gerekiyor.");
    exit;
}

$restaurantID = isset($_GET['restaurant_id']) ? intval($_GET['restaurant_id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $score = $_POST['score'];
    $usernameState = $pdo->prepare("SELECT username FROM comments WHERE username = ? AND restaurantID = ?");
    $usernameState->execute([$username,$restaurantID]);
    $usernameQuery = $usernameState->fetch();
    if($usernameQuery){
        echo "<script>alert('Onceden yorum yapmissiniz');
        window.location.href='comments.php?restaurant_id=".$restaurantID."';</script>";
        exit();
    }
    if ($score < 1 || $score > 10) {
        $error = "Puan 1 ile 10 arasında olmalıdır.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO comments (userID, restaurantID, username, title, description, score) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userID, $restaurantID, $username, $title, $description, $score]);

        $success = "Yorumunuz başarıyla eklendi!";
    }
}


$commentsStmt = $pdo->prepare("SELECT username, title, description, score, created_at 
                               FROM comments 
                               WHERE restaurantID = ?");
$commentsStmt->execute([$restaurantID]);
$comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);

$avgScoreStmt = $pdo->prepare("SELECT AVG(score) as avg_score 
                               FROM comments 
                               WHERE restaurantID = ?");
$avgScoreStmt->execute([$restaurantID]);
$avgScore = $avgScoreStmt->fetch(PDO::FETCH_ASSOC)['avg_score'];
$restScore = $avgScore !== null ? $avgScore : 0;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yorum Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background-color:#121212; color:white;">

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#121212;">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img style="height:62px;width:auto;" src="logo/navbar-logo.png" alt="">
        </a>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center">Yorum Yap</h2>
    <form action="" method="POST">
        <input type="hidden" name="restaurant_id" value="<?php echo htmlspecialchars($restaurantID); ?>">
        <div class="mb-3">
            <label for="title" class="form-label">Başlık</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Açıklama</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="score" class="form-label">Puan (1-10)</label>
            <input type="number" class="form-control" id="score" name="score" min="1" max="10" required>
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary">Gönder</button>
    </form>

    <h3 class="mt-5">Yorumlar</h3>
    <div>
        <p><strong>Puan Ortalaması: </strong><?php echo number_format($restScore, 1); ?> (1-10)</p>
    </div>
    <?php if (count($comments) > 0): ?>
        <?php foreach ($comments as $comment): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($comment['title']); ?> (<?php echo htmlspecialchars($comment['score']); ?>/10)</h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($comment['username']); ?> - <?php echo htmlspecialchars($comment['created_at']); ?></h6>
                    <p class="card-text"><?php echo htmlspecialchars($comment['description']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Henüz yorum yapılmamış.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
