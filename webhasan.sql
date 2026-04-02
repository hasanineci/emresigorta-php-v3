-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 25 Mar 2026, 09:25:11
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
-- Veritabanı: `webhasan`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `full_name`, `email`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$dZ1kVqHG8YfNMq9.Kkj.OeB2H8sG5Z1xqNvUvGX3sLsYWO8WTXW.', 'Site Yöneticisi', 'info@emresigorta.net', '2026-03-25 00:42:53', '2026-03-25 00:21:10', '2026-03-25 01:49:15');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin_sliders`
--

CREATE TABLE `admin_sliders` (
  `id` int(11) NOT NULL,
  `quote_text` text NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `author_title` varchar(200) NOT NULL DEFAULT '',
  `bg_image` varchar(500) NOT NULL DEFAULT '',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `admin_sliders`
--

INSERT INTO `admin_sliders` (`id`, `quote_text`, `author_name`, `author_title`, `bg_image`, `is_active`, `sort_order`, `created_at`) VALUES
(1, 'Emre Sigorta ile tüm sigorta işlemlerinizi güvenle yönetin. 20+ sigorta şirketinden en uygun teklifleri anında karşılaştırın.', 'Emre Sigorta', 'Şanlıurfa Sigorta Acentesi', '', 1, 0, '2026-03-25 00:21:10'),
(2, 'Trafik sigortası, kasko, DASK ve sağlık sigortası başta olmak üzere tüm branşlarda profesyonel hizmet sunuyoruz.', 'Profesyonel Hizmet', '2022\'den Beri Güvenilir Çözümler', '', 1, 0, '2026-03-25 00:21:10'),
(3, 'Online poliçe işlemleri, anında teklif alma ve 7/24 müşteri desteği ile sigorta süreçlerinizi kolaylaştırıyoruz.', 'Dijital Sigorta Deneyimi', 'Hızlı, Kolay, Güvenilir', '', 1, 0, '2026-03-25 00:21:10');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `title` varchar(300) NOT NULL,
  `page_content` longtext DEFAULT NULL,
  `seo_title` varchar(300) NOT NULL DEFAULT '',
  `seo_description` text NOT NULL DEFAULT '',
  `seo_keywords` text NOT NULL DEFAULT '',
  `og_type` varchar(50) NOT NULL DEFAULT 'website',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `category` varchar(100) NOT NULL DEFAULT 'genel',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `pages`
--

INSERT INTO `pages` (`id`, `slug`, `title`, `page_content`, `seo_title`, `seo_description`, `seo_keywords`, `og_type`, `is_active`, `sort_order`, `category`, `created_at`, `updated_at`) VALUES
(1, 'index.php', 'Ana Sayfa', NULL, 'Online Sigorta Teklifi Al | En Uygun Fiyat Garantisi', 'Emre Sigorta - Şanlıurfa\'nın güvenilir sigorta acentesi.', 'sigorta, online sigorta, trafik sigortası', 'website', 1, 1, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(2, 'trafik-sigortasi.php', 'Trafik Sigortası', NULL, 'Zorunlu Trafik Sigortası | Online Teklif Al', 'En uygun trafik sigortası fiyatları.', 'trafik sigortası, zorunlu sigorta', 'website', 1, 2, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(3, 'kasko.php', 'Kasko', NULL, 'Kasko Sigortası | Online Teklif Al', 'Kasko sigortası fiyat karşılaştırma.', 'kasko, kasko sigortası', 'website', 1, 3, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(4, 'el-trafik-sigortasi.php', '2. El Trafik Sigortası', NULL, '2. El Trafik Sigortası', '2. El araç trafik sigortası.', 'el trafik, ikinci el sigorta', 'website', 1, 4, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(5, 'yesil-kart.php', 'Yeşil Kart', NULL, 'Yeşil Kart Sigortası', 'Yurtdışı araç sigortası.', 'yeşil kart, yurtdışı sigorta', 'website', 0, 5, 'arac', '2026-03-25 00:21:10', '2026-03-25 00:44:56'),
(6, 'elektrikli-arac-kasko.php', 'Elektrikli Araç Kasko', NULL, 'Elektrikli Araç Kasko Sigortası', 'Elektrikli araçlar için kasko.', 'elektrikli araç kasko', 'website', 1, 6, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(7, 'kisa-sureli-trafik.php', 'Kısa Süreli Trafik', NULL, 'Kısa Süreli Trafik Sigortası', 'Kısa süreli trafik sigortası.', 'kısa süreli trafik', 'website', 1, 7, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(8, 'imm.php', 'İMM', NULL, 'İhtiyari Mali Mesuliyet', 'İMM sigortası.', 'imm sigortası', 'website', 1, 8, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(9, 'tamamlayici-saglik.php', 'Tamamlayıcı Sağlık', NULL, 'Tamamlayıcı Sağlık Sigortası', 'Tamamlayıcı sağlık sigortası.', 'tamamlayıcı sağlık', 'website', 1, 9, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(10, 'ozel-saglik.php', 'Özel Sağlık', NULL, 'Özel Sağlık Sigortası', 'Özel sağlık sigortası.', 'özel sağlık', 'website', 1, 10, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(11, 'seyahat-saglik.php', 'Seyahat Sağlık', NULL, 'Seyahat Sağlık Sigortası', 'Seyahat sağlık sigortası.', 'seyahat sağlık', 'website', 1, 11, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(12, 'pembe-kurdele.php', 'Pembe Kurdele', NULL, 'Pembe Kurdele Sigortası', 'Pembe kurdele sağlık sigortası.', 'pembe kurdele', 'website', 1, 12, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(13, 'dask.php', 'DASK', NULL, 'DASK - Zorunlu Deprem Sigortası', 'DASK zorunlu deprem sigortası.', 'dask, deprem sigortası', 'website', 1, 13, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(14, 'konut-sigortasi.php', 'Konut Sigortası', NULL, 'Konut Sigortası', 'Konut sigortası.', 'konut sigortası', 'website', 1, 14, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(15, 'evim-guvende.php', 'Evim Güvende', NULL, 'Evim Güvende Paketi', 'Ev koruma sigortası.', 'evim güvende', 'website', 1, 15, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(16, 'cep-telefonu.php', 'Cep Telefonu Sigortası', NULL, 'Cep Telefonu Sigortası', 'Cep telefonu sigortası.', 'cep telefonu sigortası', 'website', 1, 16, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(17, 'evcil-hayvan.php', 'Evcil Hayvan Sigortası', NULL, 'Evcil Hayvan Sigortası', 'Evcil hayvan sigortası.', 'evcil hayvan sigortası', 'website', 1, 17, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(18, 'ferdi-kaza.php', 'Ferdi Kaza Sigortası', NULL, 'Ferdi Kaza Sigortası', 'Ferdi kaza sigortası.', 'ferdi kaza sigortası', 'website', 1, 18, 'urun', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(19, 'kampanyalar.php', 'Kampanyalar', NULL, 'Sigorta Kampanyaları', 'Güncel sigorta kampanyaları.', 'sigorta kampanyaları', 'website', 1, 19, 'genel', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(20, 'police-iptal.php', 'Poliçe İptal', NULL, 'Online Poliçe İptal', 'Online poliçe iptal işlemi.', 'poliçe iptal', 'website', 1, 20, 'genel', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(21, 'blog.php', 'Blog', NULL, 'Sigorta Blog', 'Sigorta hakkında bilgiler.', 'sigorta blog', 'website', 1, 21, 'genel', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(22, 'hakkimizda.php', 'Hakkımızda', NULL, 'Hakkımızda', 'Emre Sigorta hakkında.', 'hakkımızda', 'website', 1, 22, 'genel', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(23, 'sss.php', 'SSS', NULL, 'Sıkça Sorulan Sorular', 'Sigorta ile ilgili SSS.', 'sss', 'website', 1, 23, 'genel', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(24, 'iletisim.php', 'İletişim', NULL, 'İletişim', 'İletişim bilgileri.', 'iletişim', 'website', 1, 24, 'genel', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(25, 'sube-basvurusu.php', 'Şube Başvurusu', NULL, 'Şube Başvurusu', 'Şube başvuru formu.', 'şube başvurusu', 'website', 1, 25, 'genel', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(26, 'kvkk.php', 'KVKK', NULL, 'KVKK Aydınlatma Metni', 'KVKK aydınlatma.', 'kvkk', 'website', 1, 26, 'yasal', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(27, 'gizlilik.php', 'Gizlilik Politikası', NULL, 'Gizlilik Politikası', 'Gizlilik politikası.', 'gizlilik', 'website', 1, 27, 'yasal', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(28, 'cerez-politikasi.php', 'Çerez Politikası', NULL, 'Çerez Politikası', 'Çerez politikası.', 'çerez', 'website', 1, 28, 'yasal', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(29, 'acik-riza.php', 'Açık Rıza Metni', NULL, 'Açık Rıza Metni', 'Açık rıza metni.', 'açık rıza', 'website', 1, 29, 'yasal', '2026-03-25 00:21:10', '2026-03-25 00:21:10'),
(30, 'mesafeli-satis.php', 'Mesafeli Satış', NULL, 'Mesafeli Satış Sözleşmesi', 'Mesafeli satış sözleşmesi.', 'mesafeli satış', 'website', 1, 30, 'yasal', '2026-03-25 00:21:10', '2026-03-25 00:21:10');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `setting_label` varchar(200) NOT NULL DEFAULT '',
  `setting_group` varchar(50) NOT NULL DEFAULT 'genel',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_label`, `setting_group`, `updated_at`) VALUES
(1, 'site_name', 'Emre Sigorta', 'Site Adı', 'genel', '2026-03-25 00:21:10'),
(2, 'site_url', 'http://localhost/yenitasarim', 'Site URL', 'genel', '2026-03-25 00:21:10'),
(3, 'site_domain', 'www.emresigorta.net', 'Domain', 'genel', '2026-03-25 00:21:10'),
(4, 'site_email', 'info@emresigorta.net', 'E-posta', 'iletisim', '2026-03-25 00:21:10'),
(5, 'site_email_alt', 'hasanineci@gmail.com', 'Alternatif E-posta', 'iletisim', '2026-03-25 00:21:10'),
(6, 'site_phone', '0541 514 85 15', 'Telefon', 'iletisim', '2026-03-25 00:21:10'),
(7, 'site_phone_raw', '+905415148515', 'Telefon (Ham)', 'iletisim', '2026-03-25 00:21:10'),
(8, 'site_address', 'Bamyasuyu Mahallesi Göbeklitepe Ticaret Merkezi B Blok No:2/38 Haliliye/Şanlıurfa', 'Adres', 'iletisim', '2026-03-25 00:21:10'),
(9, 'site_founded', '2022', 'Kuruluş Yılı', 'genel', '2026-03-25 00:21:10'),
(10, 'site_logo', '/assets/images/logo/logo-siyah.png', 'Logo (Siyah)', 'gorsel', '2026-03-25 00:21:10'),
(11, 'site_logo_white', '/assets/images/logo/logo-beyaz.png', 'Logo (Beyaz)', 'gorsel', '2026-03-25 00:21:10'),
(12, 'site_favicon', '/assets/images/logo/logo-siyah.png', 'Favicon', 'gorsel', '2026-03-25 00:21:10'),
(13, 'social_facebook', '', 'Facebook', 'sosyal', '2026-03-25 00:21:10'),
(14, 'social_instagram', '', 'Instagram', 'sosyal', '2026-03-25 00:21:10'),
(15, 'social_twitter', '', 'Twitter/X', 'sosyal', '2026-03-25 00:21:10'),
(16, 'social_linkedin', '', 'LinkedIn', 'sosyal', '2026-03-25 00:21:10'),
(17, 'whatsapp_message', 'Merhaba, sigorta hakkında bilgi almak istiyorum.', 'WhatsApp Mesajı', 'iletisim', '2026-03-25 00:21:10');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Tablo için indeksler `admin_sliders`
--
ALTER TABLE `admin_sliders`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Tablo için indeksler `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `admin_sliders`
--
ALTER TABLE `admin_sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Tablo için AUTO_INCREMENT değeri `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
