<?php
session_start();

// Veritabanı bağlantısı
$dsn = 'mysql:host=localhost;dbname=kütüphane';
$username = 'root';
$password = '';
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Veritabanı bağlantısı hatası: ' . $e->getMessage();
    exit;
}

// Formdan gelen verileri al
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kitap_adi = $_POST['kitap_adi'];
    $yazar_adi = $_POST['yazar_adi'];

    // Veritabanına ekle
    $sql = "INSERT INTO kitaplar (kitap_adi, yazar_adi) VALUES (:kitap_adi, :yazar_adi)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':kitap_adi', $kitap_adi);
    $stmt->bindParam(':yazar_adi', $yazar_adi);

    if ($stmt->execute()) {
        echo "Kitap başarıyla eklendi!";
        header("Location: admin.php"); // Admin paneline yönlendirme
        exit;
    } else {
        echo "Kitap eklenirken bir hata oluştu.";
    }
}
?>
