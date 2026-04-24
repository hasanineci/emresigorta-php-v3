<?php
/**
 * Emre Sigorta - Güvenlik Katmanı
 * XSS, CSRF, SQL Injection ve diğer saldırılara karşı koruma
 */

// Oturum güvenliği - header gönderilmeden önce başlat
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0); // HTTPS'de 1 yapın
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Strict');
    session_name('EMRE_SID');
    session_start();
}

// Session fixation koruması - her 30 dakikada bir yenile
if (!isset($_SESSION['_last_regeneration'])) {
    $_SESSION['_last_regeneration'] = time();
} elseif (time() - $_SESSION['_last_regeneration'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['_last_regeneration'] = time();
}

// ==========================================
// Güvenlik Sabitleri
// ==========================================
define('CSRF_TOKEN_NAME', '_csrf_token');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 300); // 5 dakika

// ==========================================
// Güvenlik Başlıkları
// ==========================================
function setSecurityHeaders() {
    // Clickjacking koruması
    header('X-Frame-Options: SAMEORIGIN');
    // XSS koruması
    header('X-XSS-Protection: 1; mode=block');
    // MIME type sniffing koruması
    header('X-Content-Type-Options: nosniff');
    // Referrer politikası
    header('Referrer-Policy: strict-origin-when-cross-origin');
    // Permissions politikası
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    // Content Security Policy
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://unpkg.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data: https:; connect-src 'self'; frame-ancestors 'self';");
    // Cache control - hassas sayfalarda
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
}

// ==========================================
// CSRF Token İşlemleri
// ==========================================
function generateCSRFToken() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function getCSRFTokenField() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

function validateCSRFToken($token) {
    if (empty($_SESSION[CSRF_TOKEN_NAME]) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

function checkCSRF() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        // post_max_size aşıldığında $_POST tamamen boş gelir
        if (empty($_POST) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            $maxSize = ini_get('post_max_size');
            http_response_code(413);
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                die(json_encode(['success' => false, 'message' => 'Dosya çok büyük. Maks: ' . $maxSize]));
            }
            die('Yüklenen dosya çok büyük. Maksimum izin verilen boyut: ' . $maxSize . '. Lütfen daha küçük bir dosya seçin.');
        }
        $token = $_POST[CSRF_TOKEN_NAME] ?? '';
        if (!validateCSRFToken($token)) {
            http_response_code(403);
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                die(json_encode(['success' => false, 'message' => 'Güvenlik doğrulaması başarısız. Sayfayı yenileyip tekrar deneyin.']));
            }
            die('Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.');
        }
    }
}

// ==========================================
// Girdi Temizleme (Input Sanitization)
// ==========================================
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function sanitizeEmail($email) {
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
}

function sanitizeInt($value) {
    return filter_var($value, FILTER_VALIDATE_INT) !== false ? (int)$value : false;
}

function sanitizeTC($tc) {
    $tc = preg_replace('/[^0-9]/', '', $tc);
    if (strlen($tc) !== 11 || $tc[0] === '0') {
        return false;
    }
    return $tc;
}

function sanitizePhone($phone) {
    return preg_replace('/[^0-9+]/', '', $phone);
}

// ==========================================
// URL Güvenliği
// ==========================================
function validateRequestURI() {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    
    // Tehlikeli URL kalıplarını engelle
    $dangerousPatterns = [
        '/\.\.\//i',           // Directory traversal
        '/<script/i',          // Script injection
        '/javascript:/i',      // JavaScript protocol
        '/vbscript:/i',        // VBScript protocol
        '/on\w+\s*=/i',       // Event handlers (onclick=, onerror=, etc.)
        '/union\s+select/i',   // SQL injection
        '/insert\s+into/i',    // SQL injection
        '/drop\s+table/i',     // SQL injection
        '/\bexec\b/i',        // Command execution
        '/\beval\b/i',        // Code execution
        '/%00/',               // Null byte injection
        '/&#/i',               // HTML entity injection
        '/\x00/',              // Null byte
    ];
    
    foreach ($dangerousPatterns as $pattern) {
        if (preg_match($pattern, urldecode($uri))) {
            http_response_code(403);
            logSecurityEvent('Tehlikeli URL tespit edildi: ' . $uri);
            die('Erişim reddedildi.');
        }
    }
}

// GET parametrelerini temizle
function cleanGETParams() {
    foreach ($_GET as $key => $value) {
        $_GET[sanitizeInput($key)] = sanitizeInput($value);
    }
}

// ==========================================
// Rate Limiting (Brute Force Koruması)
// ==========================================
function checkRateLimit($action = 'login') {
    $key = '_rate_' . $action;
    $lockKey = '_lock_' . $action;
    
    // Kilitli mi kontrol et
    if (isset($_SESSION[$lockKey]) && $_SESSION[$lockKey] > time()) {
        $remaining = $_SESSION[$lockKey] - time();
        return ['blocked' => true, 'remaining' => $remaining];
    }
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
    }
    
    // Zaman penceresi geçtiyse sıfırla
    if (time() - $_SESSION[$key]['first_attempt'] > LOGIN_LOCKOUT_TIME) {
        $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
    }
    
    return ['blocked' => false, 'count' => $_SESSION[$key]['count']];
}

function incrementRateLimit($action = 'login') {
    $key = '_rate_' . $action;
    $lockKey = '_lock_' . $action;
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
    }
    
    $_SESSION[$key]['count']++;
    
    if ($_SESSION[$key]['count'] >= MAX_LOGIN_ATTEMPTS) {
        $_SESSION[$lockKey] = time() + LOGIN_LOCKOUT_TIME;
        return true; // Kilitlendi
    }
    
    return false;
}

function resetRateLimit($action = 'login') {
    unset($_SESSION['_rate_' . $action]);
    unset($_SESSION['_lock_' . $action]);
}

// ==========================================
// Güvenlik Loglama
// ==========================================
function logSecurityEvent($message) {
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0750, true);
    }
    $logFile = $logDir . '/security_' . date('Y-m') . '.log';
    $logEntry = '[' . date('Y-m-d H:i:s') . '] '
        . '[IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . '] '
        . $message . PHP_EOL;
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

// ==========================================
// IP Kontrolü
// ==========================================
function getClientIP() {
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// ==========================================
// Güvenlik Kontrolleri Uygula
// ==========================================
setSecurityHeaders();
validateRequestURI();
cleanGETParams();
generateCSRFToken();
?>
