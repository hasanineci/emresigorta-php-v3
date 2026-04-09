<?php
/**
 * Cron-uyumlu haber güncelleme endpoint'i
 * 
 * Token her admin girişinde otomatik olarak yenilenir.
 * Güncel token'ı Admin Panel > Ayarlar'dan görebilirsiniz.
 * 
 * Kullanım:
 * 1. HTTP ile: /api/cron-news.php?token=GUNCEL_TOKEN
 * 2. CLI ile:  php api/cron-news.php
 * 
 * Windows Görev Zamanlayıcı örneği:
 *   Program: C:\xampp\php\php.exe
 *   Argümanlar: C:\xampp\htdocs\yenitasarim\api\cron-news.php
 *   Zamanlama: Her 2 saatte bir
 */

require_once __DIR__ . '/../includes/db.php';

$isCli = (php_sapi_name() === 'cli');

if (!$isCli) {
    header('Content-Type: application/json; charset=utf-8');

    $token = $_GET['token'] ?? '';
    $dbToken = getCronToken();
    
    if (empty($dbToken) || !hash_equals($dbToken, $token)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Geçersiz veya süresi dolmuş token.']);
        exit;
    }
}

try {
    $count = fetchAndCacheNews();
    $message = "$count haber güncellendi. (" . date('d.m.Y H:i:s') . ")";

    if ($isCli) {
        echo $message . PHP_EOL;
    } else {
        echo json_encode(['success' => true, 'message' => $message, 'count' => $count]);
    }
} catch (Exception $e) {
    $errorMsg = 'Haber güncelleme hatası: ' . $e->getMessage();

    if ($isCli) {
        fwrite(STDERR, $errorMsg . PHP_EOL);
        exit(1);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $errorMsg]);
    }
}
