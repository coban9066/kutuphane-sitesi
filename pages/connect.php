<?php
$host = 'localhost'; // Veritabanı sunucusu
$dbname = 'kütüphane'; // Veritabanı adı
$username = 'root'; // Veritabanı kullanıcı adı
$password = ''; // Veritabanı şifresi

try {
    // PDO ile veritabanı bağlantısı kurma
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // PDO hata modunu ayarlama
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
    exit;
}
?>
