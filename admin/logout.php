<?php
/**
 * Emre Sigorta - Admin Çıkış
 */
require_once __DIR__ . '/includes/auth.php';
adminLogout();
header('Location: ' . SITE_URL . '/admin/index.php');
exit;
?>
