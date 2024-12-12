<?php
session_start();          // Oturumu başlat
session_destroy();        // Tüm oturum verilerini sonlandır
header('Location: login-register.php'); // Giriş sayfasına yönlendirme
exit;                     // Kodun devamını çalıştırmayı engelle
?>
