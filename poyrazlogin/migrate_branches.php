<?php
/**
 * Migration: Şubeler (branches) tablosu
 * Çalıştır: /admin/migrate_branches.php
 */
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();
requireRole('yonetici');

try {
    $db = getDB();
    
    $db->exec("CREATE TABLE IF NOT EXISTS branches (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(200) NOT NULL,
        city VARCHAR(100) DEFAULT NULL,
        address TEXT DEFAULT NULL,
        phone VARCHAR(50) DEFAULT NULL,
        phone_alt VARCHAR(50) DEFAULT NULL,
        email VARCHAR(100) DEFAULT NULL,
        maps_embed TEXT DEFAULT NULL,
        maps_link VARCHAR(500) DEFAULT NULL,
        working_hours VARCHAR(200) DEFAULT 'Pazartesi - Cuma: 09:00 - 18:00',
        is_headquarters TINYINT(1) DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Mevcut genel merkez bilgilerini ekle
    $stmt = $db->query("SELECT COUNT(*) FROM branches");
    if ((int)$stmt->fetchColumn() === 0) {
        $db->prepare("INSERT INTO branches (name, city, address, phone, email, maps_embed, is_headquarters, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, 1, 1, 0)")
           ->execute([
               'Genel Merkez',
               'Şanlıurfa',
               getSetting('site_address', ''),
               getSetting('site_phone', ''),
               getSetting('site_email', ''),
               getSetting('google_maps_embed', '')
           ]);
    }

    echo '<div style="font-family:Inter,sans-serif;padding:40px;text-align:center;">';
    echo '<h2 style="color:#16a34a;">✓ Migration başarılı!</h2>';
    echo '<p><code>branches</code> tablosu oluşturuldu.</p>';
    echo '<p><a href="dashboard.php?page=subeler">Şubelere Git</a> | <a href="dashboard.php">Dashboard</a></p>';
    echo '</div>';
} catch (Exception $e) {
    echo '<div style="font-family:Inter,sans-serif;padding:40px;text-align:center;color:red;">';
    echo '<h2>Migration Hatası</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '</div>';
}
