<?php
session_start(); // Oturumu başlat

// Kullanıcının oturum açıp açmadığını kontrol et
if (!isset($_SESSION['kullanici'])) {
    header('Location: login-register.php');
    exit;
}

// Kullanıcı bilgilerini kontrol et
$current_user = $_SESSION['kullanici'];

// Kullanıcı oturumunu doğrula
if (!isset($current_user)) {
    session_unset();
    session_destroy();
    header('Location: login-register.php');
    exit;
}

// Kullanıcı bilgilerini güvenli bir şekilde çek
$ad = isset($_SESSION['ad']) ? htmlspecialchars($_SESSION['ad']) : 'Ad';
$soyad = isset($_SESSION['soyad']) ? htmlspecialchars($_SESSION['soyad']) : 'Soyad';

// Veritabanı bağlantısı
require_once('baglan.php');

// Kullanıcının halihazırda kiraladığı kitabı sorgula
$query_kiralanan_kitap = "SELECT * FROM kitaplar WHERE kiralayan_ad = '$ad' AND kiralayan_soyad = '$soyad' AND durum = 'dolu'";
$result_kiralanan_kitap = $conn->query($query_kiralanan_kitap);
$kiralanan_kitap = $result_kiralanan_kitap->fetch_assoc(); // Tek sonuç varsa çek

// Form gönderildiğinde işlem yap
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['iade'])) {
        // Kitabı iade et
        $query_iade = "UPDATE kitaplar SET durum = 'boş', kiralayan_ad = NULL, kiralayan_soyad = NULL WHERE kitap_adi = '{$kiralanan_kitap['kitap_adi']}' AND yazar_adi = '{$kiralanan_kitap['yazar_adi']}' AND durum = 'dolu'";
        if ($conn->query($query_iade) === TRUE) {
            echo "<script>alert('Kitap başarıyla iade edildi!'); window.location.href='kirala.php';</script>";
        } else {
            echo "Hata: " . $conn->error;
        }
    } else {
        // Kitap kirala
        $kitap_adi = $_POST['kitap_adi'];
        $yazar_adi = $_POST['yazar_adi'];
        $query_kirala = "UPDATE kitaplar SET durum = 'dolu', kiralayan_ad = '$ad', kiralayan_soyad = '$soyad' WHERE kitap_adi = '$kitap_adi' AND yazar_adi = '$yazar_adi' AND durum = 'boş'";
        if ($conn->query($query_kirala) === TRUE) {
            echo "<script>alert('Kitap başarıyla kiralandı!'); window.location.href='kirala.php';</script>";
        } else {
            echo "Hata: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çoban Kütüphane Cafe</title>
    <link rel="stylesheet" href="../source/books.css">
</head>
<body>
    <header class="header">
        <img id="logo" src="../img/CK.png" alt="">
        <nav class="navbar">
            <a href="../index.php">Ana Sayfa</a>
            <a href="forum-giris.php">Forum</a>
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
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </header>

    <div class="profile">
        <i id="profile" class="fa-solid fa-user"></i>
        <h1><?php echo $ad . ' ' . $soyad; ?></h1>
        <a href="logout.php"><i id="profile" class="fa-solid fa-right-from-bracket"></i></a>
        <a href="logout.php"><h1>Çıkış Yap</h1></a>
    </div>

    <section class="main-screen">
        <?php if ($kiralanan_kitap) { ?>
            <form method="POST" action="kirala.php">
                <h3><?php echo htmlspecialchars($kiralanan_kitap['kitap_adi']); ?></h3>
                <p>Yazar: <?php echo htmlspecialchars($kiralanan_kitap['yazar_adi']); ?></p>
                <button type="submit" name="iade">İade Et</button>
            </form>
        <?php } else {
            $query_kitaplar = "SELECT * FROM kitaplar WHERE durum = 'boş'";
            $result_kitaplar = $conn->query($query_kitaplar);
            while ($row = $result_kitaplar->fetch_assoc()) { ?>
                <form method="POST" action="kirala.php">
                    <input type="hidden" name="kitap_adi" value="<?php echo $row['kitap_adi']; ?>">
                    <input type="hidden" name="yazar_adi" value="<?php echo $row['yazar_adi']; ?>">
                    <h3><?php echo htmlspecialchars($row['kitap_adi']); ?></h3>
                    <p>Yazar: <?php echo htmlspecialchars($row['yazar_adi']); ?></p>
                    <button type="submit">Kitap Kirala</button>
                </form>
            <?php } ?>
        <?php } ?>
    </section>

    <script src="./scripts/scripts.js"></script>
</body>
</html>

<?php
$conn->close(); // Veritabanı bağlantısını kapat
?>
