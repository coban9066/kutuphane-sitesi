<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root"; // Veritabanı kullanıcı adınız
$password = ""; // Veritabanı şifreniz
$dbname = "kütüphane"; // Veritabanı adı

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Form verilerini al
$email = $_POST['email'];
$ad = $_POST['ad'];
$soyad = $_POST['soyad'];
$dogum_tarihi = $_POST['dogum_tarihi'];
$sifre = $_POST['sifre'];

// E-posta kontrolü
$sql = "SELECT * FROM kullanıcılar WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // E-posta zaten kullanılıyor, kullanıcıyı aynı sayfada tut
    echo "<script>
            alert('Bu e-posta adresi zaten kullanılıyor.');
            window.location.href = 'forum-giris.php'; // Kullanıcıyı login-register.php sayfasına yönlendiriyoruz
          </script>";
} else {
   

// İsim ve soyad doğrulaması
if (strlen($ad) < 2) {
    echo "<script>alert('Ad en az 2 harften oluşmalıdır.');  window.location.href = 'forum-giris.php';</script>";
    exit;
}

if (strlen($soyad) < 2) {
    echo "<script>alert('Soyad en az 2 harften oluşmalıdır.');window.location.href = 'forum-giris.php';</script>";
    exit;
}

// Şifre doğrulaması
if (strlen($sifre) < 8) {
    echo "<script>alert('Şifre en az 8 harften oluşmalıdır.');window.location.href = 'forum-giris.php';</script>";
    exit;
}

// E-posta kontrolü
$sql = "SELECT * FROM kullanıcılar WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();


    // Şifreyi hash'lemek yerine düz metin olarak kaydediyoruz (güvenlik riski)
    // $hashed_sifre = password_hash($sifre, PASSWORD_DEFAULT); // Eğer hash'leme yapmak isterseniz, bu satırı açın.

    // Veritabanına kullanıcıyı ekle
    $sql = "INSERT INTO kullanıcılar (email, ad, soyad, dogum_tarihi, sifre) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $email, $ad, $soyad, $dogum_tarihi, $sifre); // Düz metin şifre kaydediliyor

    if ($stmt->execute()) {
        echo "<script>alert('Kayıt başarılı!'); window.location.href = 'forum.php';</script>";
    } else {
        echo "<script>alert('Kayıt sırasında bir hata oluştu.');</script>";
    }


}
$conn->close();
?>
