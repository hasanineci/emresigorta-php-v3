<?php
/**
 * Migration: Admin İşlem Geçmişi (Audit Log) tablosu
 * Bu dosyayı bir kez çalıştırın: /admin/migrate_audit_log.php
 */
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();
requireRole('yonetici');

try {
    $db = getDB();
    
    $db->exec("CREATE TABLE IF NOT EXISTS admin_audit_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        admin_id INT NOT NULL,
        admin_username VARCHAR(50) NOT NULL,
        action VARCHAR(100) NOT NULL,
        action_label VARCHAR(200) DEFAULT NULL,
        table_name VARCHAR(100) DEFAULT NULL,
        record_id INT DEFAULT NULL,
        old_data JSON DEFAULT NULL,
        new_data JSON DEFAULT NULL,
        ip_address VARCHAR(45) DEFAULT NULL,
        user_agent VARCHAR(500) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_admin_id (admin_id),
        INDEX idx_action (action),
        INDEX idx_table_name (table_name),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // cron_token setting
    $stmt = $db->prepare("SELECT COUNT(*) FROM site_settings WHERE setting_key = 'cron_token'");
    $stmt->execute();
    if ((int)$stmt->fetchColumn() === 0) {
        $db->prepare("INSERT INTO site_settings (setting_key, setting_value, setting_label, setting_group) VALUES (?, ?, ?, ?)")
           ->execute(['cron_token', bin2hex(random_bytes(32)), 'Cron Token', 'sistem']);
    }

    echo '<div style="font-family:Inter,sans-serif;padding:40px;text-align:center;">';
    echo '<h2 style="color:#16a34a;">✓ Migration başarılı!</h2>';
    echo '<p><code>admin_audit_log</code> tablosu oluşturuldu.</p>';
    echo '<p><code>cron_token</code> ayarı eklendi.</p>';
    echo '<p><a href="dashboard.php">Dashboard\'a Dön</a></p>';
    echo '</div>';
} catch (Exception $e) {
    echo '<div style="font-family:Inter,sans-serif;padding:40px;text-align:center;color:red;">';
    echo '<h2>Migration Hatası</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '</div>';
}
