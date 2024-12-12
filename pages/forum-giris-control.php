<?php
// Veritabanı bağlantısı için gerekli bilgiler
$host = 'localhost';
$dbname = 'kütüphane';
$username = 'root';
$password = '';

try {
    // PDO ile veritabanı bağlantısı kuruyoruz
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Hata ayıklama modunu aktif hale getirelim
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Veritabanı sorgusu
    $email = $_POST['email'];
    $pswd = $_POST['pswd'];

    // Veritabanında kullanıcıyı sorgulama
    $sql = "SELECT * FROM kullanıcılar WHERE email = :email";
    $stmt = $pdo->prepare($sql);  // Sorguyu hazırlıyoruz
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);  // Parametreyi bağlıyoruz
    $stmt->execute();  // Sorguyu çalıştırıyoruz

    // Sonuçları kontrol ediyoruz
    if ($stmt->rowCount() > 0) {
        // Kayıt varsa, veri çekiyoruz
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Şifreyi kontrol ediyoruz
        if ($pswd === $row['sifre']) {
            // Kullanıcı doğruysa, oturumu başlatıyoruz
            session_start();
            $_SESSION['kullanici'] = $row['id'];
            $_SESSION['email'] = $email;
            $_SESSION['ad'] = $row['ad'];
            $_SESSION['soyad'] = $row['soyad'];

            // Kullanıcı türüne göre yönlendirme yapıyoruz
            if ($row['yetki'] === 'admin') {
                echo "<script>
                        alert('Giriş başarılı! Admin paneline yönlendiriliyorsunuz.');
                        window.location.href = 'admin.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Giriş başarılı! Hoş geldiniz.');
                        window.location.href = 'forum.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Hatalı şifre! Lütfen tekrar deneyin.');
                    window.location.href = 'forum-giris.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Hatalı parametreler! E-posta adresiniz kayıtlı değil.');
                window.location.href = 'forum-giris.php';
              </script>";
    }

} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>
