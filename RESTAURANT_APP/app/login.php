<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Giriş Yap</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212;
      color: #fff;
    }
    .login-container {
      max-width: 500px;
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
    .login-header {
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <div class="login-container">
      <div class="login-header">
        <h2>Giriş Yap</h2>
      </div>
      <form action="loginQuery.php" method="post">
        <div class="mb-3">
          <label for="username" class="form-label">Kullanıcı Adı</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Şifre</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Giriş Yap</button>
        </div>
        <div class="mt-3 text-center">
          <a href="register.php" class="text-light">Hesabınız yok mu? Kayıt olun</a>
        </div>
        <div class="mt-3 text-center">
          <a href="companyRegister.php" class="text-light">Firmanız için kayıt oluşturun</a>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
