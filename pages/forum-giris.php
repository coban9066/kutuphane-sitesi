<?php
session_start(); // Oturum başlatılır

// Kullanıcının oturum açıp açmadığını kontrol et
if (isset($_SESSION['kullanici'])) {
    // Eğer kullanıcı zaten giriş yapmışsa, forum.php'ye yönlendir
    header('Location: forum.php');
    exit; // Yönlendirme yaptıktan sonra kodun geri kalanını çalıştırma
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çoban Kütüphane Kafe</title>
    <link rel="stylesheet" href="../source/login-register.css">
</head>
<body>
    <header class="header">
        <img id="logo"  src="../img/CK.png" alt="">
        <nav class="navbar ">
            <a href="../index.php">Ana Sayfa</a>
            <a href="login-register.php">Anında Kitap Kirala</a>
            <a href="dolulukorani.php">Doluluk Oranı</a>


            
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
    <div class="main-container">
        <div class="main">
          <input type="checkbox" id="chk" aria-hidden="true">
      
          <div class="signup">
    <form action="forum-giris-kayıt.php" method="POST">
        <label for="chk" aria-hidden="true">Kayıt Ol</label>
        <input type="email" name="email" placeholder="Mail Adresi" required="">
<input type="text" name="ad" placeholder="Ad" required="">
<input type="text" name="soyad" placeholder="Soyad" required="">
<input type="date" name="dogum_tarihi" placeholder="Doğum Tarihi" required="">
<input type="password" name="sifre" placeholder="Şifre" required="">
<button type="submit">Kayıt Ol</button>

    </form>
</div>

<div class="login">
    <form action="forum-giris-control.php" method="POST">
        <label for="chk" aria-hidden="true">Giriş</label>
        <input type="email" name="email" placeholder="Email" required="">
        <input type="password" name="pswd" placeholder="Şifre" required="">
        <button type="submit">Giriş</button>
    </form>
</div>
        </div>
      </div>
    
    <script src="../scripts/scripts.js"></script>
</body>
</html>