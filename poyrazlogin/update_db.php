<?php
/**
 * Emre Sigorta - Veritabanı Güncelleme (v2)
 * Role sistemi ve kategori düzeltmeleri
 * BU DOSYAYI TARAYICIDA BİR KEZ ÇALIŞTIRIN: /admin/update_db.php
 */
require_once __DIR__ . '/../includes/db.php';

try {
    $pdo = getDB();
    
    // 1. admins tablosuna role kolonu ekle
    $columns = $pdo->query("SHOW COLUMNS FROM admins LIKE 'role'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE admins ADD COLUMN role ENUM('yonetici','personel','misafir') NOT NULL DEFAULT 'personel' AFTER email");
        echo "✅ Role kolonu admins tablosuna eklendi.<br>";
    } else {
        echo "ℹ️ Role kolonu zaten mevcut.<br>";
    }
    
    // 2. İlk admin kullanıcısını yönetici yap
    $pdo->exec("UPDATE admins SET role = 'yonetici' WHERE id = 1");
    echo "✅ İlk admin kullanıcısı yönetici olarak ayarlandı.<br>";
    
    // 3. Sayfa kategorilerini düzelt (urun → doğru alt kategoriler)
    $categoryUpdates = [
        'arac' => ['trafik-sigortasi.php', 'kasko.php', 'el-trafik-sigortasi.php', 'yesil-kart.php', 'elektrikli-arac-kasko.php', 'kisa-sureli-trafik.php', 'imm.php'],
        'saglik' => ['tamamlayici-saglik.php', 'ozel-saglik.php', 'seyahat-saglik.php', 'pembe-kurdele.php'],
        'konut' => ['dask.php', 'konut-sigortasi.php', 'evim-guvende.php'],
        'diger' => ['cep-telefonu.php', 'evcil-hayvan.php', 'ferdi-kaza.php'],
        'genel' => ['kampanyalar.php', 'police-iptal.php', 'blog.php', 'hakkimizda.php', 'sss.php', 'iletisim.php', 'sube-basvurusu.php', 'index.php'],
        'yasal' => ['kvkk.php', 'gizlilik.php', 'cerez-politikasi.php', 'acik-riza.php', 'mesafeli-satis.php'],
    ];
    
    $stmt = $pdo->prepare("UPDATE pages SET category = ? WHERE slug = ?");
    $updated = 0;
    foreach ($categoryUpdates as $category => $slugs) {
        foreach ($slugs as $slug) {
            $stmt->execute([$category, $slug]);
            $updated += $stmt->rowCount();
        }
    }
    echo "✅ {$updated} sayfa kategorisi güncellendi.<br>";
    
    // 4. Eksik site ayarlarını ekle (v3)
    $newSettings = [
        ['site_phone_alt', '0541 514 85 15', 'Alternatif Telefon', 'iletisim'],
        ['site_phone_short', '0541 514 85 15', 'Kısa Telefon', 'iletisim'],
        ['google_maps_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3179.6464774106275!2d38.79505390378001!3d37.16110474518357!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x153471cf33392c7f%3A0xcc83a3a671f41924!2sEmre%20Sigorta%20Arac%C4%B1l%C4%B1k%20Hizmetleri!5e0!3m2!1str!2str!4v1774446960165!5m2!1str!2str', 'Google Maps Embed URL', 'iletisim'],
        ['working_hours', 'Hafta İçi 09:00 - 18:00 | Cumartesi 10:00 - 14:00 | Pazar Kapalı', 'Çalışma Saatleri', 'iletisim'],
        ['social_youtube', '', 'YouTube', 'sosyal'],
        ['social_tiktok', '', 'TikTok', 'sosyal'],
    ];
    $stmtNew = $pdo->prepare("INSERT IGNORE INTO site_settings (setting_key, setting_value, setting_label, setting_group) VALUES (?, ?, ?, ?)");
    $addedCount = 0;
    foreach ($newSettings as $s) {
        $stmtNew->execute($s);
        $addedCount += $stmtNew->rowCount();
    }
    echo "✅ {$addedCount} yeni ayar eklendi.<br>";

    // 5. İş ortakları tablosu oluştur
    $pdo->exec("CREATE TABLE IF NOT EXISTS partners (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(200) NOT NULL,
        logo VARCHAR(500) NOT NULL DEFAULT '',
        website VARCHAR(500) NOT NULL DEFAULT '',
        sort_order INT NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ Partners tablosu oluşturuldu.<br>";

    // Mevcut iş ortaklarını ekle (eğer tablo boşsa)
    $partnerCount = $pdo->query("SELECT COUNT(*) FROM partners")->fetchColumn();
    if ($partnerCount == 0) {
        $defaultPartners = [
            'Allianz', 'Axa', 'Anadolu Sigorta', 'Sompo', 'HDI',
            'Mapfre', 'Zurich', 'Unico', 'Neova', 'Türk Nippon',
            'Quick', 'Doğa', 'Hepiyi', 'Magdeburger', 'Gulf'
        ];
        $stmtP = $pdo->prepare("INSERT INTO partners (name, sort_order) VALUES (?, ?)");
        foreach ($defaultPartners as $i => $pName) {
            $stmtP->execute([$pName, $i + 1]);
        }
        echo "✅ " . count($defaultPartners) . " varsayılan iş ortağı eklendi.<br>";
    }

    // uploads/partners klasörünü oluştur
    $partnersDir = dirname(__DIR__) . '/uploads/partners';
    if (!is_dir($partnersDir)) {
        mkdir($partnersDir, 0755, true);
        echo "✅ uploads/partners klasörü oluşturuldu.<br>";
    }

    // 6. Sosyal medya tablosu oluştur
    $pdo->exec("CREATE TABLE IF NOT EXISTS social_media (
        id INT AUTO_INCREMENT PRIMARY KEY,
        platform VARCHAR(50) NOT NULL,
        label VARCHAR(100) NOT NULL,
        icon VARCHAR(100) NOT NULL DEFAULT '',
        url VARCHAR(500) NOT NULL DEFAULT '',
        color VARCHAR(20) NOT NULL DEFAULT '#6c757d',
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        sort_order INT NOT NULL DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ social_media tablosu oluşturuldu.<br>";

    // Mevcut sosyal medya ayarlarını yeni tabloya taşı (eğer tablo boşsa)
    $socialCount = $pdo->query("SELECT COUNT(*) FROM social_media")->fetchColumn();
    if ($socialCount == 0) {
        $defaultSocials = [
            ['facebook', 'Facebook', 'fab fa-facebook-f', '#1877F2'],
            ['instagram', 'Instagram', 'fab fa-instagram', '#E4405F'],
            ['twitter', 'Twitter/X', 'fab fa-x-twitter', '#000000'],
            ['linkedin', 'LinkedIn', 'fab fa-linkedin-in', '#0A66C2'],
            ['youtube', 'YouTube', 'fab fa-youtube', '#FF0000'],
            ['tiktok', 'TikTok', 'fab fa-tiktok', '#000000'],
        ];
        $stmtS = $pdo->prepare("INSERT INTO social_media (platform, label, icon, url, color, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $socialKeyMap = [
            'facebook' => 'social_facebook', 'instagram' => 'social_instagram',
            'twitter' => 'social_twitter', 'linkedin' => 'social_linkedin',
            'youtube' => 'social_youtube', 'tiktok' => 'social_tiktok',
        ];
        foreach ($defaultSocials as $i => $s) {
            $existingUrl = '';
            $settingKey = $socialKeyMap[$s[0]] ?? '';
            if ($settingKey) {
                $existingUrl = $pdo->query("SELECT setting_value FROM site_settings WHERE setting_key = " . $pdo->quote($settingKey))->fetchColumn();
                if ($existingUrl === false) $existingUrl = '';
            }
            $isActive = !empty($existingUrl) ? 1 : 0;
            $stmtS->execute([$s[0], $s[1], $s[2], $existingUrl, $s[3], $isActive, $i + 1]);
        }
        echo "✅ " . count($defaultSocials) . " sosyal medya hesabı aktarıldı.<br>";
    }

    echo "<br><strong>🎉 Veritabanı güncellemesi başarıyla tamamlandı!</strong><br>";
    echo "<br><a href='" . (defined('ADMIN_URL') ? ADMIN_URL : '') . "/index.php'>← Admin Paneline Dön</a>";
    
} catch (Exception $e) {
    echo "❌ Hata: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
