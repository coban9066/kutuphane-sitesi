<?php
include 'connect.php';

// Yorum gönderme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kitap_adi = $_POST['kitap_adi'];
    $yorum = $_POST['yorum'];
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];

    // Yorum veritabanına ekle
    $sql = "INSERT INTO yorumlar (kitap_adi, yorum, ad, soyad) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$kitap_adi, $yorum, $ad, $soyad]);

    // Yorum ekleme işlemi tamamlandığında forum sayfasına yönlendir
    header("Location: forum.php");
    exit();
}
?>
