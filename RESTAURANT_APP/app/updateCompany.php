<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php?message=Giriş yapmanız gerekiyor.");
    exit;
}
if (!isset($_SESSION['roleID']) || $_SESSION['roleID'] !== 1) {
    echo "<script>alert('Bu sayfayı görüntüleme yetkiniz yok');
    window.location.href='index.php';
    </script>";
    exit;
}

include 'db/connection.php';

$company = null;
if (isset($_GET['company_id'])) {
    $company_id = $_GET['company_id'];
    $query = "SELECT * FROM company WHERE id = ? AND deleted_at IS NULL";
    $statement = $pdo->prepare($query);
    $statement->execute([$company_id]);
    $company = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$company) {
        echo "<script>alert('Firma bulunamadı'); window.location.href='companyManagement.php';</script>";
        exit;
    }
} 
else {
    echo "<script>alert('Geri dön'); window.location.href='companyManagement.php';</script>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firma Güncelle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#121212;">
    <div class="container mt-5">
        <h2 class="text-center mb-4" style="color:#07bc0c;">Firma Güncelle</h2>

        <form action="updateCompanyQuery.php" method="POST">
            <div class="mb-3">
                <label for="companyName" class="form-label">Firma Adı</label>
                <input type="text" class="form-control" id="companyName" name="company_name" value="<?php echo htmlspecialchars($company['name']); ?>" required>
                <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($company['id']); ?>">
            </div>
            <div class="mb-3">
                <label for="companyDesc" class="form-label">Açıklama</label>
                <input type="text" class="form-control" id="companyDesc" name="company_desc" value="<?php echo htmlspecialchars($company['description']); ?>" required>
            </div>
            <button type="submit" class="btn btn-warning">Güncelle</button>
            <a href="companyManagement.php" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
