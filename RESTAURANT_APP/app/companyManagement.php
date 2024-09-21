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

    $companies = [];
    include 'db/connection.php'; 

    if (isset($_GET['company_name'])) {
        $company_name = $_GET['company_name'];
        $query = "SELECT * FROM company WHERE name LIKE ?";
        $statement = $pdo->prepare($query); 
        $statement->execute(['%' . $company_name . '%']);
        $companies = $statement->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $query = "SELECT * FROM company WHERE deleted_at IS NULL";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $companies = $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $company_id = $_POST['company_id'];

        if ($company_id >= 0) {
            $st = $pdo->prepare("SELECT * FROM company WHERE id = ? AND deleted_at IS NOT NULL");
            $st->execute([$company_id]);
            $result = $st->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo "<script>alert('Firma daha önceden silinmiş');
                window.location.href='companyManagement.php';
                </script>";
            } else {
                $state = $pdo->prepare("UPDATE company SET deleted_at = NOW() WHERE id = ?");
                $state->execute([$company_id]);

                echo "<script>alert('Firma başarıyla silindi');
                window.location.href='companyManagement.php';
                </script>";
            }
        } else {
            echo "<script>alert('Geçersiz Firma ID');
            window.location.href='companyManagement.php';
            </script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firma Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#121212;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">Admin Panel</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="text-center mb-4" style="color:#07bc0c;">Firma Yönetimi</h2>

        <div class="text-center mb-4">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCompanyModal">Firma Ekle</button>
        </div>

        <form method="GET" class="mb-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="Firma Ara..." name="company_name" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Ara</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Logo</th>
                        <th>ID</th>
                        <th>Firma Adı</th>
                        <th>Açıklama</th>
                        <th class="text-end">İşlem</th> 
                    </tr>
                </thead>
                <?php if (count($companies) > 0): ?>
                    <?php foreach ($companies as $company): ?>
                <tbody>
                    <tr>
                        <td>
                            <?php if (!empty($company['logo_path'])): ?>
                                <img src="<?php echo htmlspecialchars($company['logo_path']); ?>" alt="Logo" style="width:50px; height:auto;">
                            <?php else: ?>
                                <img src="default-logo.png" alt="Varsayılan Logo" style="width:50px; height:auto;">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($company['id']); ?></td>
                        <td><?php echo htmlspecialchars($company['name']); ?></td>
                        <td><?php echo htmlspecialchars($company['description']); ?></td>
                        <td class="text-end">
                            <a href="restaurantList.php?companyID=<?php echo htmlspecialchars($company['id']); ?>">
                                <button class="btn btn-info btn-sm">Restoranları Listele</button>
                            </a>
                            <form action="updateCompany.php" method="GET" class="d-inline">
                                <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($company['id']); ?>">
                                <button type="submit" class="btn btn-warning btn-sm">Güncelle</button>
                            </form>
                            <form action="companyManagement.php" method="POST" class="d-inline">
                                <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($company['id']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addCompanyModal" tabindex="-1" aria-labelledby="addCompanyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyModalLabel">Yeni Firma Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <form action="addCompany.php" method="POST">
                        <div class="mb-3">
                            <label for="companyName" class="form-label">Firma Adı</label>
                            <input type="text" class="form-control" id="companyName" name="company_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="companyDesc" class="form-label">Açıklama</label>
                            <input type="text" class="form-control" id="companyDesc" name="company_desc" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Ekle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
