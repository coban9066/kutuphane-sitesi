<?php
session_start(); // Mevcut oturumu başlat

// Eğer başka bir kullanıcı oturum açmışsa, oturumu sonlandır
if (isset($_SESSION['kullanici'])) {
    session_unset(); // Tüm oturum değişkenlerini temizle
    session_destroy(); // Oturumu sonlandır
    session_start(); // Yeni bir oturum başlat
}
if (isset($_SESSION['kullanici_id'])) {
    header("Location: kirala.php");
    exit;
}

// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kütüphane";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Form verilerini al
$email = $_POST['email'];
$pswd = $_POST['pswd'];

$sql = "SELECT * FROM kullanıcılar WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    if ($pswd === $row['sifre']) {
        // Yeni oturumu başlat
        $_SESSION['kullanici'] = $row['id'];
        $_SESSION['email'] = $email;
        $_SESSION['ad'] = $row['ad'];
        $_SESSION['soyad'] = $row['soyad'];

        if ($row['yetki'] === 'admin') {
            echo "<script>
                    alert('Giriş başarılı! Admin paneline yönlendiriliyorsunuz.');
                    window.location.href = 'admin.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Giriş başarılı! Hoş geldiniz.');
                    window.location.href = 'kirala.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Hatalı şifre! Lütfen tekrar deneyin.');
                window.location.href = 'login-register.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Hatalı parametreler! E-posta adresiniz kayıtlı değil.');
            window.location.href = 'login-register.php';
          </script>";
}

$conn->close();
?>
