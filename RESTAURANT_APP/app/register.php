<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kayıt Ol</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212;
      color: #fff;
    }
    .register-container {
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
        <h2>Kayıt Ol</h2>
      </div>
      <form action="registerQuery.php" method="post">
        <div class="mb-3">
          <label for="name" class="form-label">Ad</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
          <label for="surname" class="form-label">Soyad</label>
          <input type="text" class="form-control" id="surname" name="surname" required>
        </div>
        <div class="mb-3">
          <label for="username" class="form-label">Kullanıcı Adı</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Şifre</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Şifre Tekrar</label>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
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
