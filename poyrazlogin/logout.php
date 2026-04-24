<?php
/**
 * Emre Sigorta - Admin Çıkış
 */
require_once __DIR__ . '/includes/auth.php';
adminLogout();
header('Location: ' . ADMIN_URL . '/index.php');
exit;
?>
