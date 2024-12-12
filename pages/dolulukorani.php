<?php
session_start();

// Kullanıcı giriş kontrolü
if (!isset($_SESSION['kullanici'])) {
    header('Location: forum-giris.php');
    exit;
}

// Kullanıcı bilgileri
$ad = $_SESSION['ad'];
$soyad = $_SESSION['soyad'];
$email = $_SESSION['email'];

// Verilerin saklanacağı dosya
$filename = 'doluluk_data.json'; 

if (file_exists($filename)) {
    $data = json_decode(file_get_contents($filename), true);

    // Saat kontrolü: Eğer son güncellemeden bu yana 1 saat geçmişse veriyi güncelle
    if (time() - $data['timestamp'] >= 3600) {
        $data['dolu_koltuk'] = rand(1000, 3000);
        $data['timestamp'] = time();
        file_put_contents($filename, json_encode($data));
    }
} else {
    // Dosya yoksa yeni veriler oluştur
    $data = [
        'dolu_koltuk' => rand(1000, 3000),
        'timestamp' => time()
    ];
    file_put_contents($filename, json_encode($data));
}

$dolu_koltuk = $data['dolu_koltuk'];
$toplam_koltuk = 3000;
$bos_koltuk = $toplam_koltuk - $dolu_koltuk;

// Doluluk oranını hesapla (tam sayı kısmı)
$doluluk_orani = round(($dolu_koltuk / $toplam_koltuk) * 100);

// Kullanıcı daha önce rezervasyon yaptı mı kontrol et
include 'baglanti.php'; // Veritabanı bağlantısı

$query = "SELECT * FROM rezervasyon WHERE email = '$email' LIMIT 1";
$result = mysqli_query($baglanti, $query);
$reservasyon = mysqli_fetch_assoc($result);

$rezervasyon_mesaj = '';
$form_active = true; // Formun aktif olup olmadığına dair bir değişken

if ($reservasyon) {
    // Kullanıcı daha önce rezervasyon yapmış
    $rezervasyon_mesaj = "Zaten bir rezervasyonunuz var: " . $reservasyon['rezervasyon_tarihi'];
    $form_active = false; // Formu pasifleştir
} else {
    // Rezervasyon işlemi
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rezervasyon_yap'])) {
        // Kullanıcının seçtiği saati al
        $saat = $_POST['saat'];
        
        // Geçmiş saat için rezervasyon yapmayı engelle
        $current_date = date('Y-m-d'); // Bugünün tarihi
        $current_time = date('H:i'); // Şu anki saat
        
        if ($saat < $current_time) {
            // Geçmiş bir saat seçildiğinde JavaScript ile uyarı mesajı göster
            echo "<script>alert('Geçmiş bir saat için rezervasyon yapılamaz.');</script>";
        } else {
            // Kullanıcıdan saat bilgisi alındığında, tam tarih saat bilgisi oluştur
            $rezervasyon_tarihi = $current_date . ' ' . $saat . ':00';

            if ($bos_koltuk > 0) {
                // Rezervasyon işlemi için veritabanına ekle
                $query = "INSERT INTO rezervasyon (ad, soyad, email, rezervasyon_tarihi) VALUES ('$ad', '$soyad', '$email', '$rezervasyon_tarihi')";
                if (mysqli_query($baglanti, $query)) {
                    $rezervasyon_mesaj = "Rezervasyon başarıyla oluşturuldu.";
                    $data['dolu_koltuk']++; // Dolu koltuk sayısını artır
                    file_put_contents($filename, json_encode($data)); // Dosyayı güncelle
                } else {
                    $rezervasyon_mesaj = "Rezervasyon sırasında bir hata oluştu.";
                }
            } else {
                $rezervasyon_mesaj = "Şu an boş masamız yok.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kütüphane Doluluk Oranı</title>
    <link rel="stylesheet" href="../source/forum.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <style>
    :root {
        --main-color: #e84242; /* Ana renk */
        --secondary-color: #f7f7f7; /* İkincil renk */
        --text-color: #333; /* Genel metin rengi */
        --border-color: rgba(0, 0, 0, 0.1); /* Gölge ve kenar renkleri */
    }

    .doluluk-container {
        text-align: center;
        background-color: var(--secondary-color);
        padding: 3rem;
        border-radius: 2rem;
        box-shadow: 0px 4px 20px var(--border-color);
        margin-top: 3rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        align-items:center;
    }

    .doluluk-container:hover {
        transform: scale(1.02); 
        box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.2); 
    }

    /* İlerleme Çubuğu */
    .progress-bar {
        width: 80%;
        height: 2.5rem;
        background-color: #f0f0f0;
        margin: 2.5rem auto;
        border-radius: 2rem;
        overflow: hidden;
        position: relative;
        border: 0.2rem solid var(--border-color);
    }

    .progress {
        height: 100%;
        width: <?php echo $doluluk_orani; ?>%; 
        background: linear-gradient(90deg, var(--main-color), #ff6b6b);
        text-align: center;
        color: white;
        line-height: 2.5rem;
        font-size: 1.6rem;
        font-weight: bold;
        box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.3); 
        transition: width 0.5s ease-in-out; 
    }

    /* Koltuk Bilgi Metni */
    .koltuk-bilgi {
        font-size: 2rem;
        margin: 1.5rem 0;
        color: var(--text-color);
        font-weight: 500;
        text-shadow: 0px 1px 3px rgba(0, 0, 0, 0.2); 
    }

    /* Responsive Tasarım */
    @media (max-width: 768px) {
        .doluluk-container {
            padding: 2rem;
        }

        .progress-bar {
            width: 90%; 
        }

        .progress {
            font-size: 1.4rem;
        }

        .koltuk-bilgi {
            font-size: 1.8rem;
        }
    }
    .rezervasyon-btn {
    background-color: var(--main-color);
    color: white;
    padding: 1rem 2rem;
    border: none;
    border-radius: 1rem;
    font-size: 1.6rem;
    cursor: pointer;
    margin-top: 2rem;
    transition: background-color 0.3s ease-in-out;
}

.rezervasyon-btn:hover {
    background-color: darkred;
}

.rezervasyon-mesaj {
    margin-top: 1rem;
    font-size: 1.4rem;
    color: var(--text-color);
}

</style>

</head>
<body>
<header class="header">
    <img id="logo" src="../img/CK.png" alt="Logo">
    <nav class="navbar">
        <a href="../index.php">Ana Sayfa</a>
        <a href="login-register.php">Anında Kitap Kirala</a>
        <a href="forum.php">Forum</a>
    </nav>
    <div class="buttons">
        <button onclick="window.location.href='tel:+905051042851'">
            <i class="fa-solid fa-phone"></i>
        </button>
        <button onclick="window.open('https://wa.me/905051042851?text=Merhaba', '_blank')">
            <i class="fa-solid fa-message"></i>
        </button>
        <button id="menu-btn"><i class="fa-solid fa-bars"></i></button>
    </div>
</header>
<div class="doluluk-container">
    <h2>Kütüphane Doluluk Oranı</h2>
    <div class="progress-bar">
        <div id="progress" class="progress" style="width: <?php echo $doluluk_orani; ?>%;">
            <?php echo $doluluk_orani; ?>%
        </div>
    </div>
    <p class="koltuk-bilgi"><?php echo $dolu_koltuk; ?> masa dolu, <?php echo $bos_koltuk; ?> masa boş.</p>
    
    <?php if ($rezervasyon_mesaj): ?>
        <p class="rezervasyon-mesaj"><?php echo $rezervasyon_mesaj; ?></p>
    <?php elseif ($form_active && !$reservasyon): ?>
        <form method="POST">
            <label for="saat">Saat Seçin:</label>
            <input type="time" id="saat" name="saat" required>
            <button type="submit" name="rezervasyon_yap" class="rezervasyon-btn">Rezervasyon Yap</button>
        </form>
    <?php endif; ?>
</div>
<div class="profile">
    <i id="profile" class="fa-solid fa-user"></i>
    <h1><?php echo $ad . ' ' . $soyad; ?></h1>
    <a href="logout.php"><i id="profile" class="fa-solid fa-right-from-bracket"></i></a>
    <a href="logout.php"><h1>Çıkış Yap</h1></a>
</div>

</body>
</html>
