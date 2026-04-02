<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="tr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo isset($adminPageTitle) ? htmlspecialchars($adminPageTitle, ENT_QUOTES, 'UTF-8') . ' | ' . SITE_NAME . ' Admin' : SITE_NAME . ' Admin Paneli'; ?></title>
    <link rel="icon" type="image/png" href="<?php echo SITE_URL; ?>/assets/images/logo/logo-siyah.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        :root {
            --admin-primary: #0d6efd;
            --admin-dark: #1a1d21;
            --admin-sidebar: #212529;
        }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8f9fa; }
        .admin-sidebar {
            position: fixed; left: 0; top: 0; bottom: 0; width: 260px;
            background: var(--admin-sidebar); color: #fff; z-index: 1000;
            overflow-y: auto; transition: transform 0.3s;
        }
        .admin-sidebar .brand {
            padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; gap: 12px;
        }
        .admin-sidebar .brand img { height: 32px; filter: brightness(0) invert(1); }
        .admin-sidebar .nav-section { padding: 15px 0; }
        .admin-sidebar .nav-section-title {
            font-size: 11px; text-transform: uppercase; letter-spacing: 1px;
            color: rgba(255,255,255,0.4); padding: 5px 20px; margin: 0;
        }
        .admin-sidebar .nav-link {
            color: rgba(255,255,255,0.7); padding: 10px 20px; display: flex;
            align-items: center; gap: 12px; text-decoration: none; transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active {
            color: #fff; background: rgba(255,255,255,0.08);
            border-left-color: var(--admin-primary);
        }
        .admin-sidebar .nav-link i { width: 20px; text-align: center; }
        .admin-content { margin-left: 260px; min-height: 100vh; }
        .admin-topbar {
            background: #fff; border-bottom: 1px solid #e9ecef;
            padding: 12px 24px; display: flex; align-items: center;
            justify-content: space-between; position: sticky; top: 0; z-index: 999;
        }
        .admin-main { padding: 24px; }
        .stat-card {
            background: #fff; border-radius: 12px; padding: 24px;
            border: 1px solid #e9ecef; transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .stat-card .icon {
            width: 48px; height: 48px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; font-size: 20px;
        }
        @media (max-width: 991px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.show { transform: translateX(0); }
            .admin-content { margin-left: 0; }
        }
        /* Toast Notifications */
        .toast-container-custom {
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            display: flex; flex-direction: column; gap: 10px; max-width: 380px;
        }
        .toast-notification {
            background: #fff; border-radius: 12px; padding: 16px 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15); border-left: 4px solid #0d6efd;
            display: flex; align-items: flex-start; gap: 12px;
            animation: toastSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            transition: all 0.3s ease;
        }
        .toast-notification.toast-hide {
            opacity: 0; transform: translateX(100%);
        }
        .toast-notification .toast-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: rgba(13,110,253,0.1); color: #0d6efd;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .toast-notification .toast-body { flex: 1; min-width: 0; }
        .toast-notification .toast-title {
            font-weight: 700; font-size: 13px; color: #1a1d21; margin-bottom: 2px;
        }
        .toast-notification .toast-message {
            font-size: 12px; color: #6c757d; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis;
        }
        .toast-notification .toast-time {
            font-size: 11px; color: #adb5bd; margin-top: 4px;
        }
        .toast-notification .toast-close {
            background: none; border: none; color: #adb5bd; font-size: 16px;
            cursor: pointer; padding: 0; line-height: 1; flex-shrink: 0;
        }
        .toast-notification .toast-close:hover { color: #495057; }
        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</head>
<body>
<!-- Toast Notification Container -->
<div class="toast-container-custom" id="toastContainer"></div>
<audio id="notifSound" preload="auto"></audio>

<!-- Sidebar -->
<div class="admin-sidebar" id="adminSidebar">
    <div class="brand">
        <img src="<?php echo SITE_URL; ?>/assets/images/logo/logo-siyah.png" alt="<?php echo SITE_NAME; ?>">
        <div>
            <div class="fw-bold" style="font-size: 14px;">Admin Panel</div>
            <div style="font-size: 11px; color: rgba(255,255,255,0.5);"><?php echo SITE_NAME; ?></div>
        </div>
    </div>
    
    <div class="nav-section">
        <p class="nav-section-title">Ana Menü</p>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </div>
    
    <div class="nav-section">
        <p class="nav-section-title">Yönetim</p>
        <?php if (hasRole('personel')): ?>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=sayfalar" class="nav-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['sayfalar', 'sayfa-ekle', 'sayfa-duzenle']) ? 'active' : ''; ?>">
            <i class="fas fa-file-alt"></i> Sayfalar
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=is-ortaklari" class="nav-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['is-ortaklari', 'is-ortagi-ekle', 'is-ortagi-duzenle']) ? 'active' : ''; ?>">
            <i class="fas fa-handshake"></i> İş Ortakları
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=sosyal-medya" class="nav-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['sosyal-medya', 'sosyal-medya-ekle', 'sosyal-medya-duzenle']) ? 'active' : ''; ?>">
            <i class="fas fa-share-alt"></i> Sosyal Medya
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=yorumlar" class="nav-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['yorumlar', 'yorum-ekle', 'yorum-duzenle']) ? 'active' : ''; ?>">
            <i class="fas fa-star"></i> Müşteri Yorumları
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=sss" class="nav-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['sss', 'sss-ekle', 'sss-duzenle', 'sss-kategoriler', 'sss-kategori-ekle', 'sss-kategori-duzenle']) ? 'active' : ''; ?>">
            <i class="fas fa-question-circle"></i> SSS Yönetimi
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=blog" class="nav-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['blog', 'blog-ekle', 'blog-duzenle', 'blog-kategoriler', 'blog-kategori-ekle', 'blog-kategori-duzenle']) ? 'active' : ''; ?>">
            <i class="fas fa-blog"></i> Blog Yönetimi
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=haberler" class="nav-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['haberler']) ? 'active' : ''; ?>">
            <i class="fas fa-rss"></i> Sektör Haberleri
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=kampanyalar" class="nav-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['kampanyalar', 'kampanya-ekle', 'kampanya-duzenle']) ? 'active' : ''; ?>">
            <i class="fas fa-bullhorn"></i> Kampanyalar
        </a>
        <?php endif; ?>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=mesajlar" class="nav-link <?php echo ($_GET['page'] ?? '') === 'mesajlar' ? 'active' : ''; ?>">
            <i class="fas fa-envelope"></i> Mesajlar
            <?php $newMsgCount = getFormSubmissionCount(['form_type' => 'iletisim', 'status' => 'yeni']); if ($newMsgCount > 0): ?>
            <span class="badge bg-danger ms-auto"><?php echo $newMsgCount; ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=basvurular" class="nav-link <?php echo ($_GET['page'] ?? '') === 'basvurular' ? 'active' : ''; ?>">
            <i class="fas fa-clipboard-list"></i> Başvurular
            <?php $newSubCount = getFormSubmissionCount(['status' => 'yeni']) - $newMsgCount; if ($newSubCount > 0): ?>
            <span class="badge bg-danger ms-auto"><?php echo $newSubCount; ?></span>
            <?php endif; ?>
        </a>
    </div>

    <div class="nav-section">
        <p class="nav-section-title">Sistem</p>
        <?php if (hasRole('yonetici')): ?>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=kullanicilar" class="nav-link <?php echo in_array(($_GET['page'] ?? ''), ['kullanicilar', 'kullanici-ekle', 'kullanici-duzenle']) ? 'active' : ''; ?>">
            <i class="fas fa-users-cog"></i> Kullanıcılar
        </a>
        <?php endif; ?>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=guvenlik" class="nav-link <?php echo ($_GET['page'] ?? '') === 'guvenlik' ? 'active' : ''; ?>">
            <i class="fas fa-shield-alt"></i> Güvenlik Logları
        </a>
        <?php if (hasRole('yonetici')): ?>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php?page=ayarlar" class="nav-link <?php echo ($_GET['page'] ?? '') === 'ayarlar' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> Ayarlar
        </a>
        <?php endif; ?>
    </div>
    
    <div class="nav-section" style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: auto;">
        <a href="<?php echo SITE_URL; ?>/" class="nav-link" target="_blank">
            <i class="fas fa-external-link-alt"></i> Siteyi Görüntüle
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/logout.php" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt"></i> Çıkış Yap
        </a>
    </div>
</div>

<!-- Top Bar -->
<div class="admin-content">
    <div class="admin-topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="mb-0 fw-bold"><?php echo isset($adminPageTitle) ? htmlspecialchars($adminPageTitle, ENT_QUOTES, 'UTF-8') : 'Dashboard'; ?></h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">
                <i class="fas fa-user-shield me-1"></i>
                <?php echo htmlspecialchars($_SESSION['admin_fullname'] ?? $_SESSION['admin_username'] ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?>
                <?php 
                $roleBadges = ['yonetici' => ['Yönetici', 'danger'], 'personel' => ['Personel', 'primary'], 'misafir' => ['Misafir', 'secondary']];
                $r = $_SESSION['admin_role'] ?? 'misafir';
                $rb = $roleBadges[$r] ?? ['Misafir', 'secondary'];
                ?>
                <span class="badge bg-<?php echo $rb[1]; ?> ms-1" style="font-size: 10px;"><?php echo $rb[0]; ?></span>
            </span>
            <a href="<?php echo SITE_URL; ?>/admin/logout.php" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
    <div class="admin-main">
