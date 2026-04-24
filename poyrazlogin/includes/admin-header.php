<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="tr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo isset($adminPageTitle) ? htmlspecialchars($adminPageTitle, ENT_QUOTES, 'UTF-8') . ' | ' . SITE_NAME . ' Admin' : SITE_NAME . ' Admin Paneli'; ?></title>
    <link rel="icon" type="image/png" href="<?php echo SITE_URL; ?>/assets/images/logo/logo-siyah.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body class="admin-body">
<!-- Toast Notification Container -->
<div class="toast-container-custom" id="toastContainer"></div>
<audio id="notifSound" preload="auto"></audio>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <img src="<?php echo SITE_URL; ?>/assets/images/logo/logo-siyah.png" alt="<?php echo SITE_NAME; ?>">
        <div>
            <div class="sidebar-brand-text">Admin Panel</div>
            <div class="sidebar-brand-sub"><?php echo SITE_NAME; ?></div>
        </div>
    </div>
    
    <div class="sidebar-nav-wrap">
        <div class="sidebar-section">
            <p class="sidebar-section-title">Ana Menü</p>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' && empty($_GET['page']) ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </div>
        
        <div class="sidebar-section">
            <p class="sidebar-section-title">Yönetim</p>
            <?php if (hasRole('personel')): ?>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=sayfalar" class="sidebar-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['sayfalar', 'sayfa-ekle', 'sayfa-duzenle']) ? 'active' : ''; ?>">
                <i class="fas fa-file-alt"></i> Sayfalar
            </a>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=is-ortaklari" class="sidebar-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['is-ortaklari', 'is-ortagi-ekle', 'is-ortagi-duzenle']) ? 'active' : ''; ?>">
                <i class="fas fa-handshake"></i> İş Ortakları
            </a>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=yorumlar" class="sidebar-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['yorumlar', 'yorum-ekle', 'yorum-duzenle']) ? 'active' : ''; ?>">
                <i class="fas fa-star"></i> Müşteri Yorumları
            </a>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=sss" class="sidebar-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['sss', 'sss-ekle', 'sss-duzenle', 'sss-kategoriler', 'sss-kategori-ekle', 'sss-kategori-duzenle']) ? 'active' : ''; ?>">
                <i class="fas fa-question-circle"></i> SSS Yönetimi
            </a>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=blog" class="sidebar-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['blog', 'blog-ekle', 'blog-duzenle', 'blog-kategoriler', 'blog-kategori-ekle', 'blog-kategori-duzenle']) ? 'active' : ''; ?>">
                <i class="fas fa-blog"></i> Blog Yönetimi
            </a>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=haberler" class="sidebar-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['haberler']) ? 'active' : ''; ?>">
                <i class="fas fa-rss"></i> Sektör Haberleri
            </a>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=kampanyalar" class="sidebar-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['kampanyalar', 'kampanya-ekle', 'kampanya-duzenle']) ? 'active' : ''; ?>">
                <i class="fas fa-bullhorn"></i> Kampanyalar
            </a>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=dosyalar" class="sidebar-link <?php echo ($_GET['page'] ?? '') === 'dosyalar' ? 'active' : ''; ?>">
                <i class="fas fa-folder-open"></i> Dosya Yönetimi
            </a>
            <?php endif; ?>
        </div>
        
        <div class="sidebar-section">
            <p class="sidebar-section-title">İletişim</p>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=iletisim-ayarlari" class="sidebar-link <?php echo ($_GET['page'] ?? '') === 'iletisim-ayarlari' ? 'active' : ''; ?>">
                <i class="fas fa-address-card"></i> İletişim Bilgileri
            </a>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=mesajlar" class="sidebar-link <?php echo ($_GET['page'] ?? '') === 'mesajlar' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i> Mesajlar
                <?php $newMsgCount = getFormSubmissionCount(['form_type' => 'iletisim', 'status' => 'yeni']); if ($newMsgCount > 0): ?>
                <span class="badge bg-danger"><?php echo $newMsgCount; ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=basvurular" class="sidebar-link <?php echo ($_GET['page'] ?? '') === 'basvurular' ? 'active' : ''; ?>">
                <i class="fas fa-clipboard-list"></i> Başvurular
                <?php $newSubCount = getFormSubmissionCount(['status' => 'yeni']) - $newMsgCount; if ($newSubCount > 0): ?>
                <span class="badge bg-danger"><?php echo $newSubCount; ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=subeler" class="sidebar-link <?php echo in_array(($_GET['page'] ?? ''), ['subeler', 'sube-ekle', 'sube-duzenle']) ? 'active' : ''; ?>">
                <i class="fas fa-store"></i> Şubeler
            </a>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=sosyal-medya" class="sidebar-link <?php echo in_array(sanitizeInput($_GET['page'] ?? ''), ['sosyal-medya', 'sosyal-medya-ekle', 'sosyal-medya-duzenle']) ? 'active' : ''; ?>">
                <i class="fas fa-share-alt"></i> Sosyal Medya
            </a>
        </div>

        <div class="sidebar-section">
            <p class="sidebar-section-title">Sistem</p>
            <?php if (hasRole('yonetici')): ?>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=kullanicilar" class="sidebar-link <?php echo in_array(($_GET['page'] ?? ''), ['kullanicilar', 'kullanici-ekle', 'kullanici-duzenle']) ? 'active' : ''; ?>">
                <i class="fas fa-users-cog"></i> Kullanıcılar
            </a>
            <?php endif; ?>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=guvenlik" class="sidebar-link <?php echo ($_GET['page'] ?? '') === 'guvenlik' ? 'active' : ''; ?>">
                <i class="fas fa-shield-alt"></i> Güvenlik Logları
            </a>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=islem-gecmisi" class="sidebar-link <?php echo ($_GET['page'] ?? '') === 'islem-gecmisi' ? 'active' : ''; ?>">
                <i class="fas fa-history"></i> İşlem Geçmişi
            </a>
            <?php if (hasRole('yonetici')): ?>
            <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=ayarlar" class="sidebar-link <?php echo ($_GET['page'] ?? '') === 'ayarlar' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> Ayarlar
            </a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="sidebar-footer">
        <a href="<?php echo SITE_URL; ?>/" class="sidebar-link" target="_blank">
            <i class="fas fa-external-link-alt"></i> Siteyi Görüntüle
        </a>
        <a href="<?php echo ADMIN_URL; ?>/logout.php" class="sidebar-link text-danger">
            <i class="fas fa-sign-out-alt"></i> Çıkış Yap
        </a>
    </div>
</div>

<!-- Top Bar -->
<div class="admin-content">
    <div class="admin-topbar">
        <div class="topbar-left">
            <button class="topbar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="page-title"><?php echo isset($adminPageTitle) ? htmlspecialchars($adminPageTitle, ENT_QUOTES, 'UTF-8') : 'Dashboard'; ?></h1>
        </div>
        <div class="topbar-right">
            <?php 
            $roleBadges = ['yonetici' => ['Yönetici', 'danger'], 'personel' => ['Personel', 'primary'], 'misafir' => ['Misafir', 'secondary']];
            $r = $_SESSION['admin_role'] ?? 'misafir';
            $rb = $roleBadges[$r] ?? ['Misafir', 'secondary'];
            $fullname = $_SESSION['admin_fullname'] ?? $_SESSION['admin_username'] ?? 'Admin';
            $initials = mb_strtoupper(mb_substr($fullname, 0, 1, 'UTF-8'), 'UTF-8');
            ?>
            <a href="<?php echo SITE_URL; ?>/" target="_blank" class="topbar-btn" title="Siteyi Görüntüle">
                <i class="fas fa-external-link-alt"></i>
            </a>
            <div class="topbar-user">
                <div class="topbar-user-avatar"><?php echo $initials; ?></div>
                <div class="topbar-user-info d-none d-sm-block">
                    <div class="topbar-user-name"><?php echo htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="topbar-user-role"><?php echo $rb[0]; ?></div>
                </div>
            </div>
            <a href="<?php echo ADMIN_URL; ?>/logout.php" class="topbar-btn" title="Çıkış Yap" style="color: #ef4444;">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
    <div class="admin-main">
