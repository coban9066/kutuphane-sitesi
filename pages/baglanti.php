<?php
// MySQL bağlantısı
$host = 'localhost'; // Sunucu
$kullanici = 'root'; // Kullanıcı adı
$parola = ''; // Parola
$veritabani = 'kütüphane'; // Veritabanı adı

// Bağlantıyı oluştur
$baglanti = mysqli_connect($host, $kullanici, $parola, $veritabani);

// Bağlantı kontrolü
if (!$baglanti) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}
?>
