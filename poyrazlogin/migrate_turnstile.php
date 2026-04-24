<?php
require_once __DIR__ . '/../includes/db.php';
$db = getDB();

// site_url'i düzelt
$stmt = $db->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
$stmt->execute(['https://www.emresigorta.net', 'site_url']);
echo "site_url updated.\n";

// Turnstile ayarlarını ekle
$stmt = $db->prepare("INSERT IGNORE INTO site_settings (setting_key, setting_value, setting_label, setting_group) VALUES (?, ?, ?, ?)");
$stmt->execute(['turnstile_site_key', '', 'Cloudflare Turnstile Site Key', 'guvenlik']);
$stmt->execute(['turnstile_secret_key', '', 'Cloudflare Turnstile Secret Key', 'guvenlik']);
echo "Turnstile settings added.\n";

echo "Done.\n";
