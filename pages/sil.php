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

// URL'den gelen parametreleri al
$type = $_GET['type'];
$id = $_GET['id'];

// Hangi tabloya göre işlem yapılacağını belirle
switch ($type) {
    case 'kullanici':
        $sql = "DELETE FROM kullanıcılar WHERE id = :id";
        break;
    case 'kitap':
        $sql = "DELETE FROM kitaplar WHERE id = :id";
        break;
    case 'yorum':
        $sql = "DELETE FROM yorumlar WHERE id = :id";
        break;
    default:
        echo "Geçersiz silme işlemi.";
        exit;
}

// Veritabanından sil
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo ucfirst($type) . " başarıyla silindi!";
    header("Location: admin.php"); // Admin paneline yönlendirme
    exit;
} else {
    echo ucfirst($type) . " silinirken bir hata oluştu.";
}
?>
