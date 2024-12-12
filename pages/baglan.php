<?php
$host = 'localhost'; // Veritabanı sunucusu
$dbname = 'kütüphane'; // Veritabanı adı
$username = 'root'; // Veritabanı kullanıcı adı
$password = ''; // Veritabanı şifresi

// Veritabanı bağlantısı
$conn = new mysqli($host, $username, $password, $dbname);

// Bağlantı hatası kontrolü
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
?>
