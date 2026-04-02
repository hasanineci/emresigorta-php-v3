<?php
/**
 * Emre Sigorta - Admin Kimlik Doğrulama (Veritabanı Destekli)
 */
require_once dirname(__DIR__, 2) . '/includes/security.php';
require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/db.php';

function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true
        && isset($_SESSION['admin_ip']) && $_SESSION['admin_ip'] === getClientIP();
}

function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: ' . SITE_URL . '/admin/index.php');
        exit;
    }
    if (isset($_SESSION['admin_last_activity']) && (time() - $_SESSION['admin_last_activity'] > 1800)) {
        adminLogout();
        header('Location: ' . SITE_URL . '/admin/index.php?timeout=1');
        exit;
    }
    $_SESSION['admin_last_activity'] = time();
}

// Rol kontrolü
function hasRole($requiredRole) {
    $role = $_SESSION['admin_role'] ?? 'misafir';
    $hierarchy = ['yonetici' => 3, 'personel' => 2, 'misafir' => 1];
    return ($hierarchy[$role] ?? 0) >= ($hierarchy[$requiredRole] ?? 0);
}

function requireRole($requiredRole) {
    if (!hasRole($requiredRole)) {
        http_response_code(403);
        echo '<div style="text-align:center;padding:60px;font-family:Inter,sans-serif"><h1>403</h1><p>Bu sayfaya erişim yetkiniz yok.</p><a href="' . SITE_URL . '/admin/dashboard.php">Dashboard\'a Dön</a></div>';
        exit;
    }
}

function adminLogin($username, $password) {
    $rateCheck = checkRateLimit('admin_login');
    if ($rateCheck['blocked']) {
        return ['success' => false, 'message' => 'Çok fazla deneme yaptınız. ' . ceil($rateCheck['remaining'] / 60) . ' dakika sonra tekrar deneyin.'];
    }
    
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, username, password, full_name, role FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_fullname'] = $admin['full_name'];
            $_SESSION['admin_role'] = $admin['role'] ?? 'misafir';
            $_SESSION['admin_ip'] = getClientIP();
            $_SESSION['admin_last_activity'] = time();
            $_SESSION['admin_login_time'] = time();
            
            $db->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?")->execute([$admin['id']]);
            resetRateLimit('admin_login');
            logSecurityEvent('Admin giriş başarılı: ' . $username);
            return ['success' => true];
        }
    } catch (Exception $e) {
        error_log('Admin login error: ' . $e->getMessage());
    }
    
    $locked = incrementRateLimit('admin_login');
    logSecurityEvent('Admin giriş başarısız: ' . $username);
    
    if ($locked) {
        return ['success' => false, 'message' => 'Çok fazla hatalı deneme. Hesap 15 dakika kilitlendi.'];
    }
    return ['success' => false, 'message' => 'Kullanıcı adı veya şifre hatalı.'];
}

function adminLogout() {
    logSecurityEvent('Admin çıkış: ' . ($_SESSION['admin_username'] ?? 'unknown'));
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

function getLoginSliders() {
    try {
        $db = getDB();
        return $db->query("SELECT * FROM admin_sliders WHERE is_active = 1 ORDER BY sort_order ASC")->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}
?>
