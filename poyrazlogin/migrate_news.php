<?php
/**
 * Migration: external_news tablosu (Sigortamedya RSS haberleri)
 * Tek seferlik çalıştırılacak migration dosyası.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/includes/auth.php';

$db = getDB();

// external_news tablosu
$db->exec("CREATE TABLE IF NOT EXISTS external_news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    slug VARCHAR(500) NOT NULL,
    excerpt TEXT,
    content LONGTEXT,
    source_url VARCHAR(1000) NOT NULL,
    image_url VARCHAR(1000) DEFAULT NULL,
    author VARCHAR(200) DEFAULT NULL,
    published_at DATETIME DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_source (source_url(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

echo "<h3>✅ external_news tablosu oluşturuldu.</h3>";

// İlk haberleri çek
try {
    $count = fetchAndCacheNews();
    echo "<p>✅ $count haber başarıyla çekildi ve kaydedildi.</p>";
} catch (Exception $e) {
    echo "<p>⚠️ Haber çekilemedi: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<p><a href='dashboard.php'>← Admin Panele Dön</a></p>";
