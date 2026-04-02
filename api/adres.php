<?php
/**
 * Türkiye İl/İlçe/Mahalle API (Veritabanı destekli)
 * GET ?type=iller → Tüm iller
 * GET ?type=ilceler&il=63 → Seçili ilin ilçeleri
 * GET ?type=mahalleler&il=63&ilce=Haliliye → Seçili ilçenin mahalleleri
 */
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=86400');

require_once __DIR__ . '/../includes/db.php';

$type = $_GET['type'] ?? '';

try {
    $pdo = getDB();
} catch (Exception $e) {
    echo json_encode(['error' => 'Veritabanı bağlantı hatası'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($type === 'iller') {
    $stmt = $pdo->query("SELECT il_kod AS kod, il_ad AS ad FROM adres_iller ORDER BY il_ad COLLATE utf8mb4_turkish_ci");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // kod'u int olarak döndür
    foreach ($result as &$r) { $r['kod'] = (int)$r['kod']; }
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($type === 'ilceler') {
    $ilKod = (int)($_GET['il'] ?? 0);
    if ($ilKod < 1 || $ilKod > 81) {
        echo json_encode([], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $stmt = $pdo->prepare("SELECT ilce_ad FROM adres_ilceler WHERE il_kod = ? ORDER BY ilce_ad COLLATE utf8mb4_turkish_ci");
    $stmt->execute([$ilKod]);
    $list = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($list, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($type === 'mahalleler') {
    $ilKod = (int)($_GET['il'] ?? 0);
    $ilce = trim($_GET['ilce'] ?? '');
    if ($ilKod < 1 || $ilKod > 81 || empty($ilce)) {
        echo json_encode([], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $stmt = $pdo->prepare("SELECT mahalle_ad FROM adres_mahalleler WHERE il_kod = ? AND ilce_ad = ? ORDER BY mahalle_ad COLLATE utf8mb4_turkish_ci");
    $stmt->execute([$ilKod, $ilce]);
    $list = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($list, JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(['error' => 'Geçersiz istek'], JSON_UNESCAPED_UNICODE);
