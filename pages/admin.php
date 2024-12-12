<?php
session_start(); // Oturum başlatılır

// Eğer kullanıcı giriş yapmamışsa, forum.php sayfasına erişim engellenir
if (!isset($_SESSION['kullanici'])) {
    header('Location: forum-giris.php'); // Giriş sayfasına yönlendir
    exit;
}

// Giriş yapan kullanıcının bilgilerini alabilirsiniz
$ad = $_SESSION['ad'];
$soyad = $_SESSION['soyad'];

// Veritabanı bağlantısı (pdo örneği)
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

// Kullanıcıları veritabanından çekmek için sorgu
$sql_kullanıcılar = "SELECT id, email, ad, soyad FROM kullanıcılar";
$stmt_kullanıcılar = $pdo->prepare($sql_kullanıcılar);  // Burada da $sql_kullanıcılar kullanılmalı

$stmt_kullanıcılar->execute();
$kullanicilar = $stmt_kullanıcılar->fetchAll();  // Değişken adı burada da doğru olmalı

// Kitapları veritabanından çekmek için sorgu
$sql_kitaplar = "SELECT id, kitap_adi, yazar_adi FROM kitaplar";
$stmt_kitaplar = $pdo->prepare($sql_kitaplar);
$stmt_kitaplar->execute();
$kitaplar = $stmt_kitaplar->fetchAll();

// Yorumları veritabanından çekmek için sorgu
$sql_yorumlar = "SELECT id, kitap_adi, yorum, ad, soyad FROM yorumlar";
$stmt_yorumlar = $pdo->prepare($sql_yorumlar);
$stmt_yorumlar->execute();
$yorumlar = $stmt_yorumlar->fetchAll();

// Eğer silme işlemi yapılıyorsa
if (isset($_GET['type']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_GET['type'] === 'kitap') {
        // Kitap silme işlemi
        $sql_sil_kitap = "DELETE FROM kitaplar WHERE id = :id";
        $stmt_sil_kitap = $pdo->prepare($sql_sil_kitap);
        $stmt_sil_kitap->execute(['id' => $id]);
        header("Location: admin.php");
        exit;
    } elseif ($_GET['type'] === 'yorum') {
        // Yorum silme işlemi
        $sql_sil_yorum = "DELETE FROM yorumlar WHERE id = :id";
        $stmt_sil_yorum = $pdo->prepare($sql_sil_yorum);
        $stmt_sil_yorum->execute(['id' => $id]);
        header("Location: admin.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="../source/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
</head>
<body>
<header class="header">
    <h1>Admin Paneli</h1>
</header>

<div class="container">
    <h2>Kullanıcılar</h2>
    <table>
        <tr>
            <th>Email</th>
            <th>Ad</th>
            <th>Soyad</th>
            <th>Sil</th>
        </tr>
        <?php foreach ($kullanicilar as $kullanici): ?>
        <tr>
            <td><?= htmlspecialchars($kullanici['email']) ?></td>
            <td><?= htmlspecialchars($kullanici['ad']) ?></td>
            <td><?= htmlspecialchars($kullanici['soyad']) ?></td>
            <td><a href="sil.php?type=kullanici&id=<?= $kullanici['id'] ?>" class="delete-icon"><i class="fa fa-trash"></i></a></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Kitaplar</h2>
    <form action="ekle.php" method="POST">
        <input type="text" name="kitap_adi" placeholder="Kitap Adı" required>
        <input type="text" name="yazar_adi" placeholder="Yazar Adı" required>
        <button type="submit">Ekle</button>
    </form>
    <table>
        <tr>
            <th>Kitap Adı</th>
            <th>Yazar Adı</th>
            <th>Sil</th>
        </tr>
        <?php foreach ($kitaplar as $kitap): ?>
        <tr>
            <td><?= htmlspecialchars($kitap['kitap_adi']) ?></td>
            <td><?= htmlspecialchars($kitap['yazar_adi']) ?></td>
            <td><a href="sil.php?type=kitap&id=<?= $kitap['id']; ?>" class="delete-icon"><i class="fa-solid fa-trash"></i></a></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Yorumlar</h2>
    <table>
        <tr>
            <th>Kitap Adı</th>
            <th>Yorum</th>
            <th>Kullanıcı</th>
            <th>Sil</th>
        </tr>
        <?php foreach ($yorumlar as $yorum): ?>
        <tr>
            <td><?= htmlspecialchars($yorum['kitap_adi']) ?></td>
            <td><?= htmlspecialchars($yorum['yorum']) ?></td>
            <td><?= htmlspecialchars($yorum['ad'] . ' ' . $yorum['soyad']) ?></td>
            <td><a href="sil.php?type=yorum&id=<?= $yorum['id'] ?>" class="delete-icon"><i class="fa fa-trash"></i></a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
