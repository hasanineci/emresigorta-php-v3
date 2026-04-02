<?php
// Veritabanı oluştur ve tabloları kur
$pdo = new PDO('mysql:host=localhost', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
]);

$pdo->exec('CREATE DATABASE IF NOT EXISTS webhasan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$pdo->exec('USE webhasan');

// Admin kullanıcıları tablosu
$pdo->exec("CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL DEFAULT '',
    email VARCHAR(100) NOT NULL DEFAULT '',
    last_login DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Site ayarları tablosu
$pdo->exec("CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    setting_label VARCHAR(200) NOT NULL DEFAULT '',
    setting_group VARCHAR(50) NOT NULL DEFAULT 'genel',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Sayfalar tablosu
$pdo->exec("CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(200) NOT NULL UNIQUE,
    title VARCHAR(300) NOT NULL,
    page_content LONGTEXT NULL,
    seo_title VARCHAR(300) NOT NULL DEFAULT '',
    seo_description TEXT NOT NULL DEFAULT '',
    seo_keywords TEXT NOT NULL DEFAULT '',
    og_type VARCHAR(50) NOT NULL DEFAULT 'website',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    category VARCHAR(100) NOT NULL DEFAULT 'genel',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Slider tablosu (admin login sayfası için)
$pdo->exec("CREATE TABLE IF NOT EXISTS admin_sliders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote_text TEXT NOT NULL,
    author_name VARCHAR(100) NOT NULL,
    author_title VARCHAR(200) NOT NULL DEFAULT '',
    bg_image VARCHAR(500) NOT NULL DEFAULT '',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Müşteri yorumları tablosu
$pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_name VARCHAR(100) NOT NULL,
    author_title VARCHAR(200) NOT NULL DEFAULT '',
    rating TINYINT NOT NULL DEFAULT 5,
    comment TEXT NOT NULL,
    avatar_color VARCHAR(20) NOT NULL DEFAULT '#0d6efd',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

echo "Tables created.\n";

// Default admin ekle
$hash = password_hash('admin2024!', PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT IGNORE INTO admins (username, password, full_name, email) VALUES (?, ?, ?, ?)");
$stmt->execute(['admin', $hash, 'Site Yöneticisi', 'info@emresigorta.net']);
echo "Admin user created.\n";

// Site ayarlarını ekle
$settings = [
    ['site_name', 'Emre Sigorta', 'Site Adı', 'genel'],
    ['site_url', 'http://localhost/yenitasarim', 'Site URL', 'genel'],
    ['site_domain', 'www.emresigorta.net', 'Domain', 'genel'],
    ['site_email', 'info@emresigorta.net', 'E-posta', 'iletisim'],
    ['site_email_alt', 'hasanineci@gmail.com', 'Alternatif E-posta', 'iletisim'],
    ['site_phone', '0541 514 85 15', 'Telefon', 'iletisim'],
    ['site_phone_raw', '+905415148515', 'Telefon (Ham)', 'iletisim'],
    ['site_address', 'Bamyasuyu Mahallesi Göbeklitepe Ticaret Merkezi B Blok No:2/38 Haliliye/Şanlıurfa', 'Adres', 'iletisim'],
    ['site_founded', '2022', 'Kuruluş Yılı', 'genel'],
    ['site_logo', '/assets/images/logo/logo-siyah.png', 'Logo (Siyah)', 'gorsel'],
    ['site_logo_white', '/assets/images/logo/logo-beyaz.png', 'Logo (Beyaz)', 'gorsel'],
    ['site_favicon', '/assets/images/logo/logo-siyah.png', 'Favicon', 'gorsel'],
    ['social_facebook', '', 'Facebook', 'sosyal'],
    ['social_instagram', '', 'Instagram', 'sosyal'],
    ['social_twitter', '', 'Twitter/X', 'sosyal'],
    ['social_linkedin', '', 'LinkedIn', 'sosyal'],
    ['whatsapp_message', 'Merhaba, sigorta hakkında bilgi almak istiyorum.', 'WhatsApp Mesajı', 'iletisim'],
    ['site_phone_alt', '0541 514 85 15', 'Alternatif Telefon', 'iletisim'],
    ['site_phone_short', '0541 514 85 15', 'Kısa Telefon', 'iletisim'],
    ['google_maps_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3179.6464774106275!2d38.79505390378001!3d37.16110474518357!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x153471cf33392c7f%3A0xcc83a3a671f41924!2sEmre%20Sigorta%20Arac%C4%B1l%C4%B1k%20Hizmetleri!5e0!3m2!1str!2str!4v1774446960165!5m2!1str!2str', 'Google Maps Embed URL', 'iletisim'],
    ['working_hours', 'Hafta İçi 09:00 - 18:00 | Cumartesi 10:00 - 14:00 | Pazar Kapalı', 'Çalışma Saatleri', 'iletisim'],
    ['social_youtube', '', 'YouTube', 'sosyal'],
    ['social_tiktok', '', 'TikTok', 'sosyal'],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO site_settings (setting_key, setting_value, setting_label, setting_group) VALUES (?, ?, ?, ?)");
foreach ($settings as $s) {
    $stmt->execute($s);
}
echo "Settings inserted.\n";

// Sayfaları ekle
$pages = [
    ['index.php', 'Ana Sayfa', 'Online Sigorta Teklifi Al | En Uygun Fiyat Garantisi', 'Emre Sigorta - Şanlıurfa\'nın güvenilir sigorta acentesi.', 'sigorta, online sigorta, trafik sigortası', 'urun', 1],
    ['trafik-sigortasi.php', 'Trafik Sigortası', 'Zorunlu Trafik Sigortası | Online Teklif Al', 'En uygun trafik sigortası fiyatları.', 'trafik sigortası, zorunlu sigorta', 'urun', 2],
    ['kasko.php', 'Kasko', 'Kasko Sigortası | Online Teklif Al', 'Kasko sigortası fiyat karşılaştırma.', 'kasko, kasko sigortası', 'urun', 3],
    ['el-trafik-sigortasi.php', '2. El Trafik Sigortası', '2. El Trafik Sigortası', '2. El araç trafik sigortası.', 'el trafik, ikinci el sigorta', 'urun', 4],
    ['yesil-kart.php', 'Yeşil Kart', 'Yeşil Kart Sigortası', 'Yurtdışı araç sigortası.', 'yeşil kart, yurtdışı sigorta', 'urun', 5],
    ['elektrikli-arac-kasko.php', 'Elektrikli Araç Kasko', 'Elektrikli Araç Kasko Sigortası', 'Elektrikli araçlar için kasko.', 'elektrikli araç kasko', 'urun', 6],
    ['kisa-sureli-trafik.php', 'Kısa Süreli Trafik', 'Kısa Süreli Trafik Sigortası', 'Kısa süreli trafik sigortası.', 'kısa süreli trafik', 'urun', 7],
    ['imm.php', 'İMM', 'İhtiyari Mali Mesuliyet', 'İMM sigortası.', 'imm sigortası', 'urun', 8],
    ['tamamlayici-saglik.php', 'Tamamlayıcı Sağlık', 'Tamamlayıcı Sağlık Sigortası', 'Tamamlayıcı sağlık sigortası.', 'tamamlayıcı sağlık', 'urun', 9],
    ['ozel-saglik.php', 'Özel Sağlık', 'Özel Sağlık Sigortası', 'Özel sağlık sigortası.', 'özel sağlık', 'urun', 10],
    ['seyahat-saglik.php', 'Seyahat Sağlık', 'Seyahat Sağlık Sigortası', 'Seyahat sağlık sigortası.', 'seyahat sağlık', 'urun', 11],
    ['pembe-kurdele.php', 'Pembe Kurdele', 'Pembe Kurdele Sigortası', 'Pembe kurdele sağlık sigortası.', 'pembe kurdele', 'urun', 12],
    ['dask.php', 'DASK', 'DASK - Zorunlu Deprem Sigortası', 'DASK zorunlu deprem sigortası.', 'dask, deprem sigortası', 'urun', 13],
    ['konut-sigortasi.php', 'Konut Sigortası', 'Konut Sigortası', 'Konut sigortası.', 'konut sigortası', 'urun', 14],
    ['evim-guvende.php', 'Evim Güvende', 'Evim Güvende Paketi', 'Ev koruma sigortası.', 'evim güvende', 'urun', 15],
    ['cep-telefonu.php', 'Cep Telefonu Sigortası', 'Cep Telefonu Sigortası', 'Cep telefonu sigortası.', 'cep telefonu sigortası', 'urun', 16],
    ['evcil-hayvan.php', 'Evcil Hayvan Sigortası', 'Evcil Hayvan Sigortası', 'Evcil hayvan sigortası.', 'evcil hayvan sigortası', 'urun', 17],
    ['ferdi-kaza.php', 'Ferdi Kaza Sigortası', 'Ferdi Kaza Sigortası', 'Ferdi kaza sigortası.', 'ferdi kaza sigortası', 'urun', 18],
    ['kampanyalar.php', 'Kampanyalar', 'Sigorta Kampanyaları', 'Güncel sigorta kampanyaları.', 'sigorta kampanyaları', 'genel', 19],
    ['police-iptal.php', 'Poliçe İptal', 'Online Poliçe İptal', 'Online poliçe iptal işlemi.', 'poliçe iptal', 'genel', 20],
    ['blog.php', 'Blog', 'Sigorta Blog', 'Sigorta hakkında bilgiler.', 'sigorta blog', 'genel', 21],
    ['hakkimizda.php', 'Hakkımızda', 'Hakkımızda', 'Emre Sigorta hakkında.', 'hakkımızda', 'genel', 22],
    ['sss.php', 'SSS', 'Sıkça Sorulan Sorular', 'Sigorta ile ilgili SSS.', 'sss', 'genel', 23],
    ['iletisim.php', 'İletişim', 'İletişim', 'İletişim bilgileri.', 'iletişim', 'genel', 24],
    ['sube-basvurusu.php', 'Şube Başvurusu', 'Şube Başvurusu', 'Şube başvuru formu.', 'şube başvurusu', 'genel', 25],
    ['kvkk.php', 'KVKK', 'KVKK Aydınlatma Metni', 'KVKK aydınlatma.', 'kvkk', 'yasal', 26],
    ['gizlilik.php', 'Gizlilik Politikası', 'Gizlilik Politikası', 'Gizlilik politikası.', 'gizlilik', 'yasal', 27],
    ['cerez-politikasi.php', 'Çerez Politikası', 'Çerez Politikası', 'Çerez politikası.', 'çerez', 'yasal', 28],
    ['acik-riza.php', 'Açık Rıza Metni', 'Açık Rıza Metni', 'Açık rıza metni.', 'açık rıza', 'yasal', 29],
    ['mesafeli-satis.php', 'Mesafeli Satış', 'Mesafeli Satış Sözleşmesi', 'Mesafeli satış sözleşmesi.', 'mesafeli satış', 'yasal', 30],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO pages (slug, title, seo_title, seo_description, seo_keywords, category, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
foreach ($pages as $p) {
    $stmt->execute($p);
}
echo "Pages inserted.\n";

// Default slider verileri
$sliders = [
    ['Emre Sigorta ile tüm sigorta işlemlerinizi güvenle yönetin. 20+ sigorta şirketinden en uygun teklifleri anında karşılaştırın.', 'Emre Sigorta', 'Şanlıurfa Sigorta Acentesi', ''],
    ['Trafik sigortası, kasko, DASK ve sağlık sigortası başta olmak üzere tüm branşlarda profesyonel hizmet sunuyoruz.', 'Profesyonel Hizmet', '2022\'den Beri Güvenilir Çözümler', ''],
    ['Online poliçe işlemleri, anında teklif alma ve 7/24 müşteri desteği ile sigorta süreçlerinizi kolaylaştırıyoruz.', 'Dijital Sigorta Deneyimi', 'Hızlı, Kolay, Güvenilir', ''],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO admin_sliders (quote_text, author_name, author_title, bg_image) VALUES (?, ?, ?, ?)");
foreach ($sliders as $s) {
    $stmt->execute($s);
}
echo "Sliders inserted.\n";

// Default müşteri yorumları
$testimonials = [
    ['Ahmet Y.', 'Trafik Sigortası', 5, 'Trafik sigortamı hızlı ve uygun fiyata yaptırdım. İlgi ve alakaları mükemmeldi. Kesinlikle tavsiye ederim.', '#0d6efd', 1],
    ['Fatma K.', 'Kasko', 5, 'Kasko fiyatları piyasanın çok altındaydı. Hasar sürecinde de beni yalnız bırakmadılar. Çok memnunum.', '#198754', 2],
    ['Mehmet A.', 'DASK & Konut', 5, 'DASK ve konut sigortamı tek seferde hallettik. Profesyonel ve güler yüzlü bir ekip. Teşekkürler Emre Sigorta!', '#ffc107', 3],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO testimonials (author_name, author_title, rating, comment, avatar_color, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
foreach ($testimonials as $t) {
    $stmt->execute($t);
}
echo "Testimonials inserted.\n";

echo "=== Setup completed successfully! ===\n";
