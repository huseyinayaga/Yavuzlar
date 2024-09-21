<?php 
    session_start();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $company_name = $_POST['company_name'];
        $password = $_POST['password'];
        $confirm_password = $_POST['password_confirm'];
        
        include 'db/connection.php';
        $state = $pdo->prepare("SELECT * FROM company WHERE name = ?");
        $state->execute([$company_name]);
        $company = $state->fetch();

        if(!$company){
            echo "<script>alert('Firma adinizin kaydi sistemde bulunamadi');
            window.location.href='companyRegister.php';
            </script>";
            exit;
        }
        else{
            $st = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $st->execute([$company_name]);
            $user = $st->fetch();
            if($user){
                echo "<script>alert('Bu firmaya ait kullanıcı hesabı zaten mevcut');
                    window.location.href='companyRegister.php';
                    </script>";
            }
            else if($password !== $confirm_password){
                echo "<script>alert('Şifrele uyuşmuyor');
                    window.location.href='companyRegister.php';
                    </script>";
            }
            else{
                $hashedPassword = password_hash($password,PASSWORD_ARGON2ID);
                $statement = $pdo->prepare("INSERT INTO users(companyID,roleID,name,surname,username,password) 
                VALUES(?, ?, ?, ?, ?, ?)");
                $statement->execute([$company['id'],2,$name,$surname,$company_name,$hashedPassword]);
                
                echo "<script>alert('Kayıt başarılı');
                    window.location.href='login.php';
                    </script>";
            }
        }
    }
?>




<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Firma Kayıt</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212;
      color: #fff;
    }
    .register-container {
      max-width: 600px;
      margin: auto;
      padding: 20px;
      background-color: #07bc0c;
      border-radius: 8px;
    }
    .form-control {
      background-color: #2c2c2c;
      border: 1px solid #444;
      color: #fff;
    }
    .form-control:focus {
      border-color: #07bc0c;
      box-shadow: none;
    }
    .btn-primary {
      background-color: #07bc0c;
      border-color: #07bc0c;
    }
    .btn-primary:hover {
      background-color: #06a00a;
      border-color: #06a00a;
    }
    .register-header {
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <div class="register-container">
      <div class="register-header">
        <h2>Firma Kayıt</h2>
      </div>
      <form method="post">
        <div class="mb-3">
          <label for="company_name" class="form-label">Adınız</label>
          <input type="text" class="form-control" id="company_name" name="name" required>
        </div>
        <div class="mb-3">
          <label for="company_name" class="form-label">Soyadınız</label>
          <input type="text" class="form-control" id="company_name" name="surname" required>
        </div>
        <div class="mb-3">
          <label for="company_name" class="form-label">Sistemde Kayıtlı Firma Adınızı Girin</label>
          <input type="text" class="form-control" id="company_name" name="company_name" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Şifre</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
          <label for="password_confirm" class="form-label">Şifreyi Doğrula</label>
          <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Kayıt Ol</button>
        </div>
        <div class="mt-3 text-center">
          <a href="login.php" class="text-light">Zaten bir hesabınız var mı? Giriş yapın</a>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
