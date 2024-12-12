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

// Kitapları veritabanından çekmek için sorgu
$sql_kitaplar = "SELECT kitap_adi, yazar_adi FROM kitaplar";
$stmt_kitaplar = $pdo->prepare($sql_kitaplar);
$stmt_kitaplar->execute();
$kitaplar = $stmt_kitaplar->fetchAll();

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Sayfası</title>
    <link rel="stylesheet" href="../source/forum.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        
    </style>
</head>
<body>
<header class="header">
        <img id="logo"  src="../img/CK.png" alt="">
        <nav class="navbar ">
            <a href="../index.php">Ana Sayfa</a>
            <a href="login-register.php">Anında Kitap Kirala</a>
            <a href="dolulukorani.php">Rezervasyon</a>


            
        </nav>
        <div class="buttons">
            <button onclick="window.location.href='tel:+905051042851'">
                <i class="fa-solid fa-phone"></i>
            </button>
            <button onclick="window.open('https://wa.me/905051042851?text=Merhaba', '_blank')">
    <i class="fa-solid fa-message"></i>
</button>
            <button id="menu-btn">
                <i  class="fa-solid fa-bars"></i>

            </button>
            
        </div>
    </header> 


<div class="kitaplar">
    <h2>Kitaplar</h2>
    <?php foreach ($kitaplar as $kitap): ?>
        <div class="kitap">
            <h3><?php echo htmlspecialchars($kitap['kitap_adi']); ?></h3>
            <p>Yazar: <?php echo htmlspecialchars($kitap['yazar_adi']); ?></p>

            <form action="yorum.php" method="POST">
                <input type="hidden" name="kitap_adi" value="<?php echo $kitap['kitap_adi']; ?>">
                
                <!-- Ad ve Soyad artık otomatik olarak alınır -->
                <input type="hidden" name="ad" value="<?php echo htmlspecialchars($ad); ?>">
                <input type="hidden" name="soyad" value="<?php echo htmlspecialchars($soyad); ?>">

                <label for="yorum">Fikrini Paylaş:</label>
                <textarea name="yorum" required></textarea><br>
                <input type="submit" value="Yorum Yap">
            </form>

            <div class="yorumlar">
                <h4>Yorumlar:</h4>
                <?php
                // Kitap için yorumları çek
                $sql_yorumlar = "SELECT * FROM yorumlar WHERE kitap_adi = ?";
                $stmt_yorumlar = $pdo->prepare($sql_yorumlar);
                $stmt_yorumlar->execute([$kitap['kitap_adi']]);
                $yorumlar = $stmt_yorumlar->fetchAll();

                foreach ($yorumlar as $yorum):
                ?>
                    <div class="yorum">
                        <p><strong><?php echo htmlspecialchars($yorum['ad']) . ' ' . htmlspecialchars($yorum['soyad']); ?>:</strong></p>
                        <p><?php echo nl2br(htmlspecialchars($yorum['yorum'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="profile">
        <i id="profile" class="fa-solid fa-user"></i>
        <h1><?php echo $ad . ' ' . $soyad; ?></h1>
        <a href="logout.php"><i id="profile" class="fa-solid fa-right-from-bracket"></i></a>
        <a href="logout.php"><h1>Çıkış Yap</h1></a>
    </div>

    <script src="../scripts/scripts.js"></script>

</body>
</html>
