-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 Ara 2024, 09:01:52
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `kütüphane`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kitaplar`
--

CREATE TABLE `kitaplar` (
  `id` int(11) NOT NULL,
  `kitap_adi` varchar(255) NOT NULL,
  `yazar_adi` varchar(255) NOT NULL,
  `kiralayan_ad` varchar(100) DEFAULT NULL,
  `kiralayan_soyad` varchar(100) DEFAULT NULL,
  `durum` enum('dolu','bos') NOT NULL DEFAULT 'bos'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kitaplar`
--

INSERT INTO `kitaplar` (`id`, `kitap_adi`, `yazar_adi`, `kiralayan_ad`, `kiralayan_soyad`, `durum`) VALUES
(1, 'Sefiller', 'Victor Hugo', NULL, NULL, 'bos'),
(2, 'Suç ve Ceza', 'Fyodor Dostoyevski', NULL, NULL, 'bos'),
(3, '1984', 'George Orwell', NULL, NULL, 'bos'),
(4, 'Kürk Mantolu Madonna', 'Sabahattin Ali', NULL, NULL, 'bos'),
(5, 'Bir İdam Mahkumunun Son Günü', 'Victor Hugo', NULL, NULL, 'bos'),
(6, 'Tutunamayanlar', 'Oğuz Atay', NULL, NULL, 'bos'),
(7, 'Simyacı', 'Paulo Coelho', NULL, NULL, 'bos'),
(8, 'Beyaz Zambaklar Ülkesinde', 'Grigory Petrov', NULL, NULL, 'bos'),
(9, 'Aşk', 'Elif Şafak', NULL, NULL, 'bos'),
(10, 'Çalıkuşu', 'Reşat Nuri Güntekin', NULL, NULL, 'bos'),
(11, 'Saatleri Ayarlama Enstitüsü', 'Ahmet Hamdi Tanpınar', NULL, NULL, 'bos'),
(12, 'Küçük Prens', 'Antoine de Saint-Exupéry', NULL, NULL, 'bos'),
(13, 'İnce Memed', 'Yaşar Kemal', NULL, NULL, 'bos'),
(14, 'Fahrenheit 451', 'Ray Bradbury', NULL, NULL, 'bos'),
(15, 'Bir Çöküşün Günlüğü', 'Stefan Zweig', NULL, NULL, 'bos'),
(16, 'Baba ve Piç', 'Elif Şafak', NULL, NULL, 'bos'),
(17, 'Germinal', 'Émile Zola', NULL, NULL, 'bos'),
(18, 'Aylak Adam', 'Yusuf Atılgan', NULL, NULL, 'bos'),
(19, 'Mektuplar', 'Friedrich Nietzsche', NULL, NULL, 'bos'),
(20, 'Savaş ve Barış', 'Lev Tolstoy', NULL, NULL, 'bos');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanıcılar`
--

CREATE TABLE `kullanıcılar` (
  `id` int(6) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `ad` varchar(50) NOT NULL,
  `soyad` varchar(50) NOT NULL,
  `dogum_tarihi` date NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `yetki` varchar(50) DEFAULT 'kullanıcı'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kullanıcılar`
--

INSERT INTO `kullanıcılar` (`id`, `email`, `ad`, `soyad`, `dogum_tarihi`, `sifre`, `reg_date`, `yetki`) VALUES
(1, 'coban@gmail.com', 'Mehmet', 'Çoban', '2004-03-12', 'zabaza01', '2024-12-09 13:45:16', 'kullanıcı'),
(24, 'yagiz@gmail.com', 'Mahmut Yağız', 'Ekşi', '2003-09-02', 'zabaza01', '2024-12-09 13:45:29', 'kullanıcı'),
(25, 'admin@gmail.com', 'Admin', 'Hesabı', '1995-11-01', 'admin1234', '2024-12-01 12:05:53', 'admin'),
(27, 'azat@gmail.com', 'Azat', 'Aydın', '2024-12-12', 'AZATyunus', '2024-12-11 14:20:13', 'kullanıcı');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rezervasyon`
--

CREATE TABLE `rezervasyon` (
  `id` int(11) NOT NULL,
  `ad` varchar(100) NOT NULL,
  `soyad` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rezervasyon_tarihi` datetime DEFAULT NULL,
  `tarih` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yorumlar`
--

CREATE TABLE `yorumlar` (
  `id` int(11) NOT NULL,
  `kitap_adi` varchar(255) NOT NULL,
  `yorum` text NOT NULL,
  `ad` varchar(100) NOT NULL,
  `soyad` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `yorumlar`
--

INSERT INTO `yorumlar` (`id`, `kitap_adi`, `yorum`, `ad`, `soyad`) VALUES
(2, 'Sefiller', 'MERHABA', 'Mahmut', 'Yağız Ekşi');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `kitaplar`
--
ALTER TABLE `kitaplar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kullanıcılar`
--
ALTER TABLE `kullanıcılar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `rezervasyon`
--
ALTER TABLE `rezervasyon`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `yorumlar`
--
ALTER TABLE `yorumlar`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `kitaplar`
--
ALTER TABLE `kitaplar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `kullanıcılar`
--
ALTER TABLE `kullanıcılar`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Tablo için AUTO_INCREMENT değeri `rezervasyon`
--
ALTER TABLE `rezervasyon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `yorumlar`
--
ALTER TABLE `yorumlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
