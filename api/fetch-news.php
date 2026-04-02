<?php
/**
 * API: Harici haber RSS çekme (Sigortamedya)
 * GET ile çağrılır, admin oturumu gerektirir.
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/config.php';

session_start();
if (empty($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Yetkisiz erişim.']);
    exit;
}

try {
    $count = fetchAndCacheNews();
    echo json_encode(['success' => true, 'message' => "$count haber güncellendi.", 'count' => $count]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
