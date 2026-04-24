<?php
/**
 * Emre Sigorta - Admin Dashboard (Veritabanı Destekli)
 */
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

$adminPageTitle = 'Dashboard';
$currentPage = sanitizeInput($_GET['page'] ?? 'dashboard');
$message = '';
$messageType = '';
$adminRole = $_SESSION['admin_role'] ?? 'misafir';

// ==================== Yeni Başvuru Kontrolü (AJAX Polling) ====================
if (isset($_GET['ajax']) && $_GET['ajax'] === 'check_new') {
    header('Content-Type: application/json');
    $lastId = (int)($_GET['last_id'] ?? 0);
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, form_type, created_at, form_data FROM form_submissions WHERE id > ? ORDER BY id DESC LIMIT 10");
        $stmt->execute([$lastId]);
        $newSubs = $stmt->fetchAll();
        
        $formTypeLabelsAjax = [
            'trafik' => 'Trafik Sigortası', 'kasko' => 'Kasko', 'dask' => 'DASK',
            'el-trafik' => 'El Değiştiren Trafik', 'elektrikli-arac-kasko' => 'Elektrikli Araç Kasko',
            'kisa-sureli-trafik' => 'Kısa Süreli Trafik', 'imm' => 'İMM', 'yesil-kart' => 'Yeşil Kart',
            'tamamlayici-saglik' => 'Tamamlayıcı Sağlık', 'ozel-saglik' => 'Özel Sağlık',
            'seyahat-saglik' => 'Seyahat Sağlık', 'pembe-kurdele' => 'Pembe Kurdele',
            'konut-sigortasi' => 'Konut Sigortası', 'evim-guvende' => 'Evim Güvende',
            'cep-telefonu' => 'Cep Tel. Sigortası', 'evcil-hayvan' => 'Evcil Hayvan',
            'ferdi-kaza' => 'Ferdi Kaza', 'sube-basvurusu' => 'Şube Başvurusu',
            'police-iptal' => 'Poliçe İptal', 'iletisim' => 'İletişim',
            'anasayfa-trafik' => 'Trafik (Hızlı)', 'anasayfa-kasko' => 'Kasko (Hızlı)',
            'anasayfa-dask' => 'DASK (Hızlı)', 'anasayfa-saglik' => 'Sağlık (Hızlı)',
            'kampanya-basvuru' => 'Kampanya Başvurusu',
        ];
        
        $result = [];
        foreach ($newSubs as $s) {
            $data = json_decode($s['form_data'], true) ?: [];
            $result[] = [
                'id' => $s['id'],
                'type' => $formTypeLabelsAjax[$s['form_type']] ?? $s['form_type'],
                'name' => $data['ruhsat_sahibi'] ?? $data['ad_soyad'] ?? $data['adsoyad'] ?? '-',
                'time' => date('H:i', strtotime($s['created_at'])),
            ];
        }
        echo json_encode(['success' => true, 'submissions' => $result]);
    } catch (Exception $e) {
        echo json_encode(['success' => false]);
    }
    exit;
}

// ==================== AJAX İşlemleri ====================
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCSRF();
    $action = $_POST['action'] ?? '';
    
    // İşlem etiketleri (audit log için)
    $_actionLabels = [
        'toggle_page' => 'Sayfa durumu değiştirildi', 'save_page' => 'Sayfa kaydedildi', 'delete_page' => 'Sayfa silindi',
        'save_settings' => 'Site ayarları güncellendi', 'save_user' => 'Kullanıcı kaydedildi', 'delete_user' => 'Kullanıcı silindi',
        'update_submission_status' => 'Başvuru durumu güncellendi', 'delete_submission' => 'Başvuru silindi',
        'save_partner' => 'İş ortağı kaydedildi', 'delete_partner' => 'İş ortağı silindi', 'toggle_partner' => 'İş ortağı durumu değiştirildi',
        'save_partner_order' => 'İş ortağı sıralaması güncellendi',
        'save_social' => 'Sosyal medya kaydedildi', 'delete_social' => 'Sosyal medya silindi', 'toggle_social' => 'Sosyal medya durumu değiştirildi',
        'save_testimonial' => 'Yorum kaydedildi', 'delete_testimonial' => 'Yorum silindi', 'toggle_testimonial' => 'Yorum durumu değiştirildi',
        'save_testimonial_order' => 'Yorum sıralaması güncellendi',
        'save_faq_category' => 'SSS kategorisi kaydedildi', 'delete_faq_category' => 'SSS kategorisi silindi', 'toggle_faq_category' => 'SSS kategori durumu değiştirildi',
        'save_faq' => 'SSS kaydedildi', 'delete_faq' => 'SSS silindi', 'toggle_faq_homepage' => 'SSS anasayfa durumu değiştirildi', 'toggle_faq_active' => 'SSS durumu değiştirildi',
        'save_blog_category' => 'Blog kategorisi kaydedildi', 'delete_blog_category' => 'Blog kategorisi silindi', 'toggle_blog_category' => 'Blog kategori durumu değiştirildi',
        'save_blog_post' => 'Blog yazısı kaydedildi', 'delete_blog_post' => 'Blog yazısı silindi', 'toggle_blog_post' => 'Blog yazı durumu değiştirildi', 'toggle_blog_featured' => 'Blog öne çıkarma değiştirildi',
        'refresh_external_news' => 'Haberler güncellendi', 'toggle_external_news' => 'Haber durumu değiştirildi', 'delete_external_news' => 'Haber silindi',
        'save_campaign' => 'Kampanya kaydedildi', 'delete_campaign' => 'Kampanya silindi', 'toggle_campaign' => 'Kampanya durumu değiştirildi',
        'save_branch' => 'Şube kaydedildi', 'delete_branch' => 'Şube silindi', 'toggle_branch' => 'Şube durumu değiştirildi',
        'upload_file' => 'Dosya yüklendi', 'delete_file' => 'Dosya silindi',
        'revert_audit_log' => 'İşlem geri alındı',
    ];
    $_actionTableMap = [
        'toggle_page' => 'pages', 'save_page' => 'pages', 'delete_page' => 'pages',
        'save_settings' => 'site_settings', 'save_user' => 'admins', 'delete_user' => 'admins',
        'update_submission_status' => 'form_submissions', 'delete_submission' => 'form_submissions',
        'save_partner' => 'partners', 'delete_partner' => 'partners', 'toggle_partner' => 'partners', 'save_partner_order' => 'partners',
        'save_social' => 'social_media', 'delete_social' => 'social_media', 'toggle_social' => 'social_media',
        'save_testimonial' => 'testimonials', 'delete_testimonial' => 'testimonials', 'toggle_testimonial' => 'testimonials', 'save_testimonial_order' => 'testimonials',
        'save_faq_category' => 'faq_categories', 'delete_faq_category' => 'faq_categories', 'toggle_faq_category' => 'faq_categories',
        'save_faq' => 'faqs', 'delete_faq' => 'faqs', 'toggle_faq_homepage' => 'faqs', 'toggle_faq_active' => 'faqs',
        'save_blog_category' => 'blog_categories', 'delete_blog_category' => 'blog_categories', 'toggle_blog_category' => 'blog_categories',
        'save_blog_post' => 'blog_posts', 'delete_blog_post' => 'blog_posts', 'toggle_blog_post' => 'blog_posts', 'toggle_blog_featured' => 'blog_posts',
        'refresh_external_news' => 'external_news', 'toggle_external_news' => 'external_news', 'delete_external_news' => 'external_news',
        'save_campaign' => 'campaigns', 'delete_campaign' => 'campaigns', 'toggle_campaign' => 'campaigns',
        'save_branch' => 'branches', 'delete_branch' => 'branches', 'toggle_branch' => 'branches',
        'upload_file' => 'uploads', 'delete_file' => 'uploads',
        'revert_audit_log' => 'admin_audit_log',
    ];
    
    // Silme işlemlerinde eski veriyi önceden kaydet
    $_oldData = null;
    $_recordId = null;
    try {
        $db_log = getDB();
        if ($action === 'delete_page' && ($pid = (int)($_POST['page_id'] ?? 0))) {
            $_recordId = $pid;
            $s = $db_log->prepare("SELECT * FROM pages WHERE id = ?"); $s->execute([$pid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_user' && ($uid = (int)($_POST['user_id'] ?? 0))) {
            $_recordId = $uid;
            $s = $db_log->prepare("SELECT id, username, full_name, email, role FROM admins WHERE id = ?"); $s->execute([$uid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_submission' && ($sid = (int)($_POST['submission_id'] ?? 0))) {
            $_recordId = $sid;
            $s = $db_log->prepare("SELECT * FROM form_submissions WHERE id = ?"); $s->execute([$sid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_partner' && ($pid = (int)($_POST['partner_id'] ?? 0))) {
            $_recordId = $pid;
            $s = $db_log->prepare("SELECT * FROM partners WHERE id = ?"); $s->execute([$pid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_social' && ($sid = (int)($_POST['social_id'] ?? 0))) {
            $_recordId = $sid;
            $s = $db_log->prepare("SELECT * FROM social_media WHERE id = ?"); $s->execute([$sid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_testimonial' && ($tid = (int)($_POST['testimonial_id'] ?? 0))) {
            $_recordId = $tid;
            $s = $db_log->prepare("SELECT * FROM testimonials WHERE id = ?"); $s->execute([$tid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_faq_category' && ($cid = (int)($_POST['category_id'] ?? 0))) {
            $_recordId = $cid;
            $s = $db_log->prepare("SELECT * FROM faq_categories WHERE id = ?"); $s->execute([$cid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_faq' && ($fid = (int)($_POST['faq_id'] ?? 0))) {
            $_recordId = $fid;
            $s = $db_log->prepare("SELECT * FROM faqs WHERE id = ?"); $s->execute([$fid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_blog_category' && ($cid = (int)($_POST['category_id'] ?? 0))) {
            $_recordId = $cid;
            $s = $db_log->prepare("SELECT * FROM blog_categories WHERE id = ?"); $s->execute([$cid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_blog_post' && ($pid = (int)($_POST['post_id'] ?? 0))) {
            $_recordId = $pid;
            $s = $db_log->prepare("SELECT * FROM blog_posts WHERE id = ?"); $s->execute([$pid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_external_news' && ($nid = (int)($_POST['news_id'] ?? 0))) {
            $_recordId = $nid;
            $s = $db_log->prepare("SELECT * FROM external_news WHERE id = ?"); $s->execute([$nid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_campaign' && ($cid = (int)($_POST['campaign_id'] ?? 0))) {
            $_recordId = $cid;
            $s = $db_log->prepare("SELECT * FROM campaigns WHERE id = ?"); $s->execute([$cid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_branch' && ($bid = (int)($_POST['branch_id'] ?? 0))) {
            $_recordId = $bid;
            $s = $db_log->prepare("SELECT * FROM branches WHERE id = ?"); $s->execute([$bid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'delete_file') {
            $_oldData = ['file_path' => $_POST['file_path'] ?? ''];
        }
        // Düzenleme işlemlerinde eski veriyi kaydet
        elseif ($action === 'save_page' && ($pid = (int)($_POST['page_id'] ?? 0)) && $pid > 0) {
            $_recordId = $pid;
            $s = $db_log->prepare("SELECT * FROM pages WHERE id = ?"); $s->execute([$pid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'save_user' && ($uid = (int)($_POST['user_id'] ?? 0)) && $uid > 0) {
            $_recordId = $uid;
            $s = $db_log->prepare("SELECT id, username, full_name, email, role FROM admins WHERE id = ?"); $s->execute([$uid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'save_partner' && ($pid = (int)($_POST['partner_id'] ?? 0)) && $pid > 0) {
            $_recordId = $pid;
            $s = $db_log->prepare("SELECT * FROM partners WHERE id = ?"); $s->execute([$pid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'save_campaign' && ($cid = (int)($_POST['campaign_id'] ?? 0)) && $cid > 0) {
            $_recordId = $cid;
            $s = $db_log->prepare("SELECT * FROM campaigns WHERE id = ?"); $s->execute([$cid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'save_blog_post' && ($pid = (int)($_POST['post_id'] ?? 0)) && $pid > 0) {
            $_recordId = $pid;
            $s = $db_log->prepare("SELECT * FROM blog_posts WHERE id = ?"); $s->execute([$pid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'save_branch' && ($bid = (int)($_POST['branch_id'] ?? 0)) && $bid > 0) {
            $_recordId = $bid;
            $s = $db_log->prepare("SELECT * FROM branches WHERE id = ?"); $s->execute([$bid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'save_social' && ($sid = (int)($_POST['social_id'] ?? 0)) && $sid > 0) {
            $_recordId = $sid;
            $s = $db_log->prepare("SELECT * FROM social_media WHERE id = ?"); $s->execute([$sid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'save_testimonial' && ($tid = (int)($_POST['testimonial_id'] ?? 0)) && $tid > 0) {
            $_recordId = $tid;
            $s = $db_log->prepare("SELECT * FROM testimonials WHERE id = ?"); $s->execute([$tid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'save_faq' && ($fid = (int)($_POST['faq_id'] ?? 0)) && $fid > 0) {
            $_recordId = $fid;
            $s = $db_log->prepare("SELECT * FROM faqs WHERE id = ?"); $s->execute([$fid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'save_faq_category' && ($cid = (int)($_POST['category_id'] ?? 0)) && $cid > 0) {
            $_recordId = $cid;
            $s = $db_log->prepare("SELECT * FROM faq_categories WHERE id = ?"); $s->execute([$cid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'save_blog_category' && ($cid = (int)($_POST['category_id'] ?? 0)) && $cid > 0) {
            $_recordId = $cid;
            $s = $db_log->prepare("SELECT * FROM blog_categories WHERE id = ?"); $s->execute([$cid]); $_oldData = $s->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === 'save_settings' && !empty($_POST['settings'])) {
            // Ayar değişikliklerinde eski değerleri kaydet
            $_oldData = [];
            foreach (array_keys($_POST['settings']) as $sk) {
                $safeSk = preg_replace('/[^a-z0-9_]/', '', $sk);
                if ($safeSk) {
                    $s = $db_log->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?"); $s->execute([$safeSk]);
                    $row = $s->fetch(PDO::FETCH_ASSOC);
                    if ($row) $_oldData[$safeSk] = $row['setting_value'];
                }
            }
        }
        // Toggle işlemlerinde eski durumu kaydet (geri alma için)
        $toggleMap = [
            'toggle_page' => ['pages', 'page_id', 'is_active'],
            'toggle_partner' => ['partners', 'partner_id', 'is_active'],
            'toggle_social' => ['social_media', 'social_id', 'is_active'],
            'toggle_testimonial' => ['testimonials', 'testimonial_id', 'is_active'],
            'toggle_faq_homepage' => ['faqs', 'faq_id', 'show_on_homepage'],
            'toggle_faq_active' => ['faqs', 'faq_id', 'is_active'],
            'toggle_faq_category' => ['faq_categories', 'category_id', 'is_active'],
            'toggle_blog_post' => ['blog_posts', 'post_id', 'is_active'],
            'toggle_blog_featured' => ['blog_posts', 'post_id', 'is_featured'],
            'toggle_blog_category' => ['blog_categories', 'category_id', 'is_active'],
            'toggle_external_news' => ['external_news', 'news_id', 'is_active'],
            'toggle_campaign' => ['campaigns', 'campaign_id', 'is_active'],
            'toggle_branch' => ['branches', 'branch_id', 'is_active'],
        ];
        if (isset($toggleMap[$action])) {
            [$tbl, $idParam, $col] = $toggleMap[$action];
            $tid = (int)($_POST[$idParam] ?? 0);
            if ($tid > 0) {
                $_recordId = $tid;
                $s = $db_log->prepare("SELECT `$col` FROM `$tbl` WHERE id = ?"); $s->execute([$tid]);
                $row = $s->fetch(PDO::FETCH_ASSOC);
                if ($row) $_oldData = $row;
            }
        }
    } catch (Exception $e) { /* Loglama hatası asıl işlemi engellemesin */ }
    
    // Shutdown function: exit çağrılsa bile loglama çalışır
    $_logDone = false;
    register_shutdown_function(function() use ($action, $_actionLabels, $_actionTableMap, &$_oldData, &$_recordId, &$_logDone) {
        if ($_logDone || !$action || !isset($_actionLabels[$action])) return;
        $_logDone = true;
        $_safePost = $_POST;
        unset($_safePost['password'], $_safePost['_csrf_token']);
        if (!$_recordId) {
            $_recordId = (int)($_POST['page_id'] ?? $_POST['user_id'] ?? $_POST['partner_id'] ?? $_POST['campaign_id'] ?? $_POST['branch_id'] ?? $_POST['post_id'] ?? $_POST['submission_id'] ?? $_POST['social_id'] ?? $_POST['testimonial_id'] ?? $_POST['category_id'] ?? $_POST['faq_id'] ?? $_POST['news_id'] ?? 0);
        }
        logAdminAction(
            $action,
            $_actionLabels[$action],
            $_actionTableMap[$action] ?? null,
            $_recordId ?: null,
            $_oldData,
            $_safePost
        );
    });
    
    // Sayfa aktif/pasif toggle
    if ($action === 'toggle_page') {
        $pageId = (int)($_POST['page_id'] ?? 0);
        $status = (int)($_POST['is_active'] ?? 0);
        if ($pageId > 0) {
            try {
                $db = getDB();
                $stmt = $db->prepare("UPDATE pages SET is_active = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $pageId]);
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Sayfa durumu güncellendi.']);
                    exit;
                }
                $message = 'Sayfa durumu güncellendi.';
                $messageType = 'success';
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata oluştu.']); exit; }
                $message = 'Hata oluştu.';
                $messageType = 'danger';
            }
        }
    }
    
    // Sayfa kaydet (ekle/düzenle)
    if ($action === 'save_page') {
        $pageId = (int)($_POST['page_id'] ?? 0);
        $data = [
            'slug' => trim($_POST['slug'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'page_content' => trim($_POST['page_content'] ?? ''),
            'seo_title' => trim($_POST['seo_title'] ?? ''),
            'seo_description' => trim($_POST['seo_description'] ?? ''),
            'seo_keywords' => trim($_POST['seo_keywords'] ?? ''),
            'og_type' => trim($_POST['og_type'] ?? 'website'),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'category' => trim($_POST['category'] ?? 'genel'),
        ];
        
        if (empty($data['slug']) || empty($data['title'])) {
            $message = 'Slug ve başlık zorunludur.';
            $messageType = 'danger';
        } else {
            try {
                $db = getDB();
                if ($pageId > 0) {
                    $stmt = $db->prepare("UPDATE pages SET slug=?, title=?, page_content=?, seo_title=?, seo_description=?, seo_keywords=?, og_type=?, is_active=?, sort_order=?, category=?, updated_at=NOW() WHERE id=?");
                    $stmt->execute([$data['slug'], $data['title'], $data['page_content'], $data['seo_title'], $data['seo_description'], $data['seo_keywords'], $data['og_type'], $data['is_active'], $data['sort_order'], $data['category'], $pageId]);
                    $message = 'Sayfa başarıyla güncellendi.';
                } else {
                    $stmt = $db->prepare("INSERT INTO pages (slug, title, page_content, seo_title, seo_description, seo_keywords, og_type, is_active, sort_order, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$data['slug'], $data['title'], $data['page_content'], $data['seo_title'], $data['seo_description'], $data['seo_keywords'], $data['og_type'], $data['is_active'], $data['sort_order'], $data['category']]);
                    $message = 'Yeni sayfa eklendi.';
                    
                    // Yeni sayfa dosyası oluştur
                    $newFilePath = dirname(__DIR__) . '/' . basename($data['slug']);
                    if (preg_match('/^[a-z0-9\-]+\.php$/i', basename($data['slug'])) && !file_exists($newFilePath)) {
                        $contentSection = trim($_POST['file_content'] ?? '');
                        if (empty($contentSection)) {
                            $contentSection = "<section class=\"page-content\">\n    <div class=\"container\">\n        <div class=\"content-wrapper\">\n            <h1>" . htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8') . "</h1>\n            <p>İçerik buraya eklenecek.</p>\n        </div>\n    </div>\n</section>";
                        }
                        $fullFile = buildPageFile(
                            $data['seo_title'] ?: $data['title'],
                            $data['seo_description'],
                            $data['seo_keywords'],
                            $contentSection
                        );
                        file_put_contents($newFilePath, $fullFile);
                    }
                }
                
                // Mevcut sayfa düzenlemede: content + SEO alanlarını birleştirip dosyaya kaydet
                if ($pageId > 0 && !empty($data['slug'])) {
                    $targetPath = dirname(__DIR__) . '/' . basename($data['slug']);
                    if (preg_match('/^[a-z0-9\-]+\.php$/i', basename($data['slug']))) {
                        $contentSection = trim($_POST['file_content'] ?? '');
                        if (!empty($contentSection)) {
                            $fullFile = buildPageFile(
                                $data['seo_title'] ?: $data['title'],
                                $data['seo_description'],
                                $data['seo_keywords'],
                                $contentSection
                            );
                            file_put_contents($targetPath, $fullFile);
                        }
                    }
                }
                
                $messageType = 'success';
                $currentPage = 'sayfalar';
            } catch (Exception $e) {
                $message = 'Hata: ' . $e->getMessage();
                $messageType = 'danger';
            }
        }
    }
    
    // Sayfa sil
    if ($action === 'delete_page') {
        $pageId = (int)($_POST['page_id'] ?? 0);
        if ($pageId > 0) {
            try {
                $db = getDB();
                $db->prepare("DELETE FROM pages WHERE id = ?")->execute([$pageId]);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Sayfa silindi.']); exit; }
                $message = 'Sayfa silindi.';
                $messageType = 'success';
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Silme hatası.']); exit; }
                $message = 'Silme hatası.';
                $messageType = 'danger';
            }
        }
    }
    
    // Ayarları kaydet
    if ($action === 'save_settings') {
        if (!hasRole('yonetici')) {
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Yetkiniz yok.']); exit; }
            $message = 'Yetkiniz yok.'; $messageType = 'danger';
        } else {
            $settings = $_POST['settings'] ?? [];
            $count = 0;
            foreach ($settings as $key => $value) {
                $safeKey = preg_replace('/[^a-z0-9_]/', '', $key);
                if ($safeKey && updateSetting($safeKey, trim($value))) {
                    $count++;
                }
            }
            
            // Dosya yüklemeleri (logo, favicon)
            $uploadFields = ['site_logo', 'site_logo_white', 'site_favicon'];
            $uploadDir = dirname(__DIR__) . '/assets/images/logo/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
            
            foreach ($uploadFields as $field) {
                if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                    $allowed = ['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/x-icon', 'image/svg+xml'];
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $_FILES[$field]['tmp_name']);
                    finfo_close($finfo);
                    
                    if (in_array($mimeType, $allowed)) {
                        $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
                        $safeName = $field . '.' . preg_replace('/[^a-z0-9]/', '', strtolower($ext));
                        $targetPath = $uploadDir . $safeName;
                        
                        if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetPath)) {
                            updateSetting($field, '/assets/images/logo/' . $safeName);
                            $count++;
                        }
                    }
                }
            }
            
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => $count . ' ayar güncellendi.']); exit; }
            $message = $count . ' ayar güncellendi.';
            $messageType = 'success';
        }
    }
    
    // Kullanıcı kaydet (ekle/düzenle)
    if ($action === 'save_user') {
        if (!hasRole('yonetici')) {
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Yetkiniz yok.']); exit; }
            $message = 'Yetkiniz yok.'; $messageType = 'danger';
        } else {
            $userId = (int)($_POST['user_id'] ?? 0);
            $userData = [
                'username' => trim($_POST['username'] ?? ''),
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => trim($_POST['role'] ?? 'misafir'),
                'password' => $_POST['password'] ?? '',
            ];
            
            $validRoles = ['yonetici', 'personel', 'misafir'];
            if (!in_array($userData['role'], $validRoles)) {
                $userData['role'] = 'misafir';
            }
            
            if (empty($userData['username']) || empty($userData['full_name'])) {
                $message = 'Kullanıcı adı ve ad soyad zorunludur.';
                $messageType = 'danger';
            } elseif ($userId === 0 && empty($userData['password'])) {
                $message = 'Yeni kullanıcı için şifre zorunludur.';
                $messageType = 'danger';
            } else {
                try {
                    if ($userId > 0) {
                        updateUser($userId, $userData);
                        $message = 'Kullanıcı güncellendi.';
                        logSecurityEvent('Kullanıcı güncellendi: ' . $userData['username']);
                    } else {
                        createUser($userData);
                        $message = 'Yeni kullanıcı eklendi.';
                        logSecurityEvent('Yeni kullanıcı eklendi: ' . $userData['username']);
                    }
                    $messageType = 'success';
                    $currentPage = 'kullanicilar';
                } catch (Exception $e) {
                    $message = 'Hata: Kullanıcı adı zaten kullanılıyor olabilir.';
                    $messageType = 'danger';
                }
            }
        }
    }
    
    // Kullanıcı sil
    if ($action === 'delete_user') {
        if (!hasRole('yonetici')) {
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Yetkiniz yok.']); exit; }
            $message = 'Yetkiniz yok.'; $messageType = 'danger';
        } else {
            $userId = (int)($_POST['user_id'] ?? 0);
            if ($userId <= 0) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Geçersiz kullanıcı.']); exit; }
                $message = 'Geçersiz kullanıcı.'; $messageType = 'danger';
            } elseif ($userId === 1) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Ana yönetici hesabı silinemez.']); exit; }
                $message = 'Ana yönetici hesabı silinemez.'; $messageType = 'danger';
            } elseif ($userId === (int)($_SESSION['admin_id'] ?? 0)) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Kendi hesabınızı silemezsiniz.']); exit; }
                $message = 'Kendi hesabınızı silemezsiniz.'; $messageType = 'danger';
            } else {
                try {
                    deleteUser($userId);
                    if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Kullanıcı silindi.']); exit; }
                    $message = 'Kullanıcı silindi.';
                    $messageType = 'success';
                    logSecurityEvent('Kullanıcı silindi: ID ' . $userId);
                } catch (Exception $e) {
                    if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => $e->getMessage()]); exit; }
                    $message = $e->getMessage();
                    $messageType = 'danger';
                }
            }
        }
    }
    
    // Başvuru durumu güncelle (AJAX)
    if ($action === 'update_submission_status') {
        $subId = (int)($_POST['submission_id'] ?? 0);
        $newStatus = $_POST['new_status'] ?? '';
        if ($subId > 0 && in_array($newStatus, ['yeni', 'okundu', 'tamamlandi'], true)) {
            try {
                updateFormSubmissionStatus($subId, $newStatus);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Başvuru durumu güncellendi.']); exit; }
                $message = 'Başvuru durumu güncellendi.';
                $messageType = 'success';
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Güncelleme hatası.']); exit; }
                $message = 'Güncelleme hatası.';
                $messageType = 'danger';
            }
        }
    }
    
    // Başvuru sil
    if ($action === 'delete_submission') {
        $subId = (int)($_POST['submission_id'] ?? 0);
        if ($subId > 0) {
            try {
                deleteFormSubmission($subId);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Başvuru silindi.']); exit; }
                $message = 'Başvuru silindi.';
                $messageType = 'success';
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Silme hatası.']); exit; }
                $message = 'Silme hatası.';
                $messageType = 'danger';
            }
        }
    }

    // İş ortağı kaydet (ekle/düzenle)
    if ($action === 'save_partner') {
        $partnerId = (int)($_POST['partner_id'] ?? 0);
        $partnerData = [
            'name' => trim($_POST['partner_name'] ?? ''),
            'website' => trim($_POST['partner_website'] ?? ''),
            'sort_order' => (int)($_POST['partner_sort_order'] ?? 0),
            'is_active' => isset($_POST['partner_is_active']) ? 1 : 0,
            'logo' => '',
        ];
        
        if (empty($partnerData['name'])) {
            $message = 'Şirket adı zorunludur.';
            $messageType = 'danger';
        } else {
            // Mevcut logoyu koru (düzenleme durumunda)
            if ($partnerId > 0) {
                $existingPartner = getPartner($partnerId);
                $partnerData['logo'] = $existingPartner['logo'] ?? '';
            }
            
            // Logo yükleme
            if (!empty($_FILES['partner_logo']['name'])) {
                $uploadError = $_FILES['partner_logo']['error'] ?? UPLOAD_ERR_NO_FILE;
                $uploadErrMsg = '';
                
                if ($uploadError === UPLOAD_ERR_OK) {
                    $allowedTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'];
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->file($_FILES['partner_logo']['tmp_name']);
                    
                    if (!in_array($mimeType, $allowedTypes)) {
                        $uploadErrMsg = 'Logo formatı desteklenmiyor. PNG, JPG, WEBP veya SVG yükleyin.';
                    } elseif ($_FILES['partner_logo']['size'] > 2 * 1024 * 1024) {
                        $uploadErrMsg = 'Logo dosyası çok büyük (max 2MB). Lütfen daha küçük bir dosya seçin.';
                    } else {
                        $ext = pathinfo($_FILES['partner_logo']['name'], PATHINFO_EXTENSION);
                        $ext = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $ext));
                        if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg'])) $ext = 'png';
                        $fileName = 'partner_' . time() . '_' . random_int(1000, 9999) . '.' . $ext;
                        $uploadDir = dirname(__DIR__) . '/uploads/partners/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                        $uploadPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['partner_logo']['tmp_name'], $uploadPath)) {
                            if (!empty($partnerData['logo'])) {
                                $oldPath = dirname(__DIR__) . '/' . $partnerData['logo'];
                                if (file_exists($oldPath)) unlink($oldPath);
                            }
                            $partnerData['logo'] = 'uploads/partners/' . $fileName;
                        } else {
                            $uploadErrMsg = 'Logo yüklenirken bir hata oluştu. Lütfen tekrar deneyin.';
                        }
                    }
                } elseif ($uploadError === UPLOAD_ERR_INI_SIZE || $uploadError === UPLOAD_ERR_FORM_SIZE) {
                    $uploadErrMsg = 'Logo dosyası çok büyük (max 2MB). Lütfen daha küçük bir dosya seçin.';
                } elseif ($uploadError !== UPLOAD_ERR_NO_FILE) {
                    $uploadErrMsg = 'Logo yükleme hatası (kod: ' . $uploadError . '). Lütfen tekrar deneyin.';
                }
                
                if ($uploadErrMsg) {
                    $_SESSION['admin_message'] = $uploadErrMsg;
                    $_SESSION['admin_message_type'] = 'danger';
                    $redirectPage = $partnerId > 0 ? 'is-ortagi-duzenle&id=' . $partnerId : 'is-ortagi-ekle';
                    header('Location: ' . ADMIN_URL . '/dashboard.php?page=' . $redirectPage);
                    exit;
                }
            }
            
            if (empty($message)) {
                try {
                    savePartner($partnerData, $partnerId);
                    $_SESSION['admin_message'] = $partnerId > 0 ? 'İş ortağı güncellendi.' : 'Yeni iş ortağı eklendi.';
                    $_SESSION['admin_message_type'] = 'success';
                    header('Location: ' . ADMIN_URL . '/dashboard.php?page=is-ortaklari');
                    exit;
                } catch (Exception $e) {
                    $message = 'Kaydetme hatası.';
                    $messageType = 'danger';
                }
            }
        }
    }

    // İş ortağı sil
    if ($action === 'delete_partner') {
        $partnerId = (int)($_POST['partner_id'] ?? 0);
        if ($partnerId > 0) {
            try {
                deletePartner($partnerId);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'İş ortağı silindi.']); exit; }
                $message = 'İş ortağı silindi.';
                $messageType = 'success';
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Silme hatası.']); exit; }
                $message = 'Silme hatası.';
                $messageType = 'danger';
            }
        }
    }

    // İş ortağı aktif/pasif toggle (AJAX)
    if ($action === 'toggle_partner') {
        $partnerId = (int)($_POST['partner_id'] ?? 0);
        $status = (int)($_POST['is_active'] ?? 0);
        if ($partnerId > 0) {
            try {
                $db = getDB();
                $stmt = $db->prepare("UPDATE partners SET is_active = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $partnerId]);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Durum güncellendi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata oluştu.']); exit; }
            }
        }
    }

    // İş ortağı sıralama kaydet (AJAX)
    if ($action === 'save_partner_order') {
        header('Content-Type: application/json');
        $orderData = $_POST['order'] ?? [];
        if (!empty($orderData) && is_array($orderData)) {
            try {
                $db = getDB();
                $stmt = $db->prepare("UPDATE partners SET sort_order = ?, updated_at = NOW() WHERE id = ?");
                foreach ($orderData as $item) {
                    $stmt->execute([(int)$item['sort'], (int)$item['id']]);
                }
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    // Sosyal medya kaydet (ekle/düzenle)
    if ($action === 'save_social') {
        $socialId = (int)($_POST['social_id'] ?? 0);
        $socialData = [
            'platform' => trim($_POST['social_platform'] ?? ''),
            'label' => trim($_POST['social_label'] ?? ''),
            'icon' => trim($_POST['social_icon'] ?? ''),
            'url' => trim($_POST['social_url'] ?? ''),
            'color' => trim($_POST['social_color'] ?? '#6c757d'),
            'is_active' => isset($_POST['social_is_active']) ? 1 : 0,
            'sort_order' => (int)($_POST['social_sort_order'] ?? 0),
        ];
        if (empty($socialData['label'])) {
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Platform adı zorunludur.']); exit; }
            $message = 'Platform adı zorunludur.'; $messageType = 'danger';
        } else {
            try {
                saveSocialMedia($socialData, $socialId);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => $socialId > 0 ? 'Sosyal medya güncellendi.' : 'Yeni sosyal medya eklendi.']); exit; }
                $_SESSION['admin_message'] = $socialId > 0 ? 'Sosyal medya güncellendi.' : 'Yeni sosyal medya eklendi.';
                $_SESSION['admin_message_type'] = 'success';
                header('Location: ' . ADMIN_URL . '/dashboard.php?page=sosyal-medya');
                exit;
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Kaydetme hatası.']); exit; }
                $message = 'Kaydetme hatası.'; $messageType = 'danger';
            }
        }
    }

    // Sosyal medya sil
    if ($action === 'delete_social') {
        $socialId = (int)($_POST['social_id'] ?? 0);
        if ($socialId > 0) {
            try {
                deleteSocialMedia($socialId);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Sosyal medya silindi.']); exit; }
                $message = 'Sosyal medya silindi.'; $messageType = 'success';
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Silme hatası.']); exit; }
                $message = 'Silme hatası.'; $messageType = 'danger';
            }
        }
    }

    // Sosyal medya aktif/pasif toggle (AJAX)
    if ($action === 'toggle_social') {
        $socialId = (int)($_POST['social_id'] ?? 0);
        $status = (int)($_POST['is_active'] ?? 0);
        if ($socialId > 0) {
            try {
                $db = getDB();
                $stmt = $db->prepare("UPDATE social_media SET is_active = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $socialId]);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Durum güncellendi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata oluştu.']); exit; }
            }
        }
    }

    // Müşteri yorumu kaydet (ekle/düzenle)
    if ($action === 'save_testimonial') {
        $testimonialId = (int)($_POST['testimonial_id'] ?? 0);
        $testimonialData = [
            'author_name' => trim($_POST['author_name'] ?? ''),
            'author_title' => trim($_POST['author_title'] ?? ''),
            'rating' => max(1, min(5, (int)($_POST['rating'] ?? 5))),
            'comment' => trim($_POST['comment'] ?? ''),
            'avatar_color' => trim($_POST['avatar_color'] ?? '#0d6efd'),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ];
        if (empty($testimonialData['author_name']) || empty($testimonialData['comment'])) {
            $message = 'Ad ve yorum alanları zorunludur.';
            $messageType = 'danger';
        } else {
            try {
                saveTestimonial($testimonialData, $testimonialId);
                $_SESSION['admin_message'] = $testimonialId > 0 ? 'Yorum güncellendi.' : 'Yeni yorum eklendi.';
                $_SESSION['admin_message_type'] = 'success';
                header('Location: ' . ADMIN_URL . '/dashboard.php?page=yorumlar');
                exit;
            } catch (Exception $e) {
                $message = 'Kaydetme hatası.';
                $messageType = 'danger';
            }
        }
    }

    // Müşteri yorumu sil
    if ($action === 'delete_testimonial') {
        $testimonialId = (int)($_POST['testimonial_id'] ?? 0);
        if ($testimonialId > 0) {
            try {
                deleteTestimonial($testimonialId);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Yorum silindi.']); exit; }
                $message = 'Yorum silindi.';
                $messageType = 'success';
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Silme hatası.']); exit; }
                $message = 'Silme hatası.';
                $messageType = 'danger';
            }
        }
    }

    // Müşteri yorumu sıralama kaydet (AJAX)
    if ($action === 'save_testimonial_order') {
        header('Content-Type: application/json');
        $orderData = $_POST['order'] ?? [];
        if (!empty($orderData) && is_array($orderData)) {
            try {
                $db = getDB();
                $stmt = $db->prepare("UPDATE testimonials SET sort_order = ?, updated_at = NOW() WHERE id = ?");
                foreach ($orderData as $item) {
                    $stmt->execute([(int)$item['sort'], (int)$item['id']]);
                }
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    // Müşteri yorumu aktif/pasif toggle (AJAX)
    if ($action === 'toggle_testimonial') {
        $testimonialId = (int)($_POST['testimonial_id'] ?? 0);
        $status = (int)($_POST['is_active'] ?? 0);
        if ($testimonialId > 0) {
            try {
                $db = getDB();
                $stmt = $db->prepare("UPDATE testimonials SET is_active = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $testimonialId]);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Durum güncellendi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata oluştu.']); exit; }
            }
        }
    }

    // ==================== SSS Kategori İşlemleri ====================

    // SSS Kategori kaydet
    if ($action === 'save_faq_category') {
        $catId = (int)($_POST['category_id'] ?? 0);
        $catData = [
            'name' => trim($_POST['cat_name'] ?? ''),
            'slug' => trim($_POST['cat_slug'] ?? ''),
            'sort_order' => (int)($_POST['cat_sort_order'] ?? 0),
            'is_active' => isset($_POST['cat_is_active']) ? 1 : 0,
        ];
        if (empty($catData['name'])) {
            $message = 'Kategori adı zorunludur.';
            $messageType = 'danger';
        } else {
            if (empty($catData['slug'])) {
                $catData['slug'] = preg_replace('/[^a-z0-9\-]/', '', str_replace([' ', 'ı', 'ö', 'ü', 'ş', 'ç', 'ğ', 'İ', 'Ö', 'Ü', 'Ş', 'Ç', 'Ğ'], ['-', 'i', 'o', 'u', 's', 'c', 'g', 'i', 'o', 'u', 's', 'c', 'g'], mb_strtolower($catData['name'], 'UTF-8')));
            }
            try {
                saveFaqCategory($catData, $catId);
                $_SESSION['admin_message'] = $catId > 0 ? 'Kategori güncellendi.' : 'Yeni kategori eklendi.';
                $_SESSION['admin_message_type'] = 'success';
                header('Location: ' . ADMIN_URL . '/dashboard.php?page=sss-kategoriler');
                exit;
            } catch (Exception $e) {
                $message = 'Kaydetme hatası.';
                $messageType = 'danger';
            }
        }
    }

    // SSS Kategori sil
    if ($action === 'delete_faq_category') {
        $catId = (int)($_POST['category_id'] ?? 0);
        if ($catId > 0) {
            try {
                deleteFaqCategory($catId);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Kategori ve ilgili sorular silindi.']); exit; }
                $message = 'Kategori silindi.';
                $messageType = 'success';
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Silme hatası.']); exit; }
            }
        }
    }

    // ==================== SSS Soru İşlemleri ====================

    // SSS Soru kaydet
    if ($action === 'save_faq') {
        $faqId = (int)($_POST['faq_id'] ?? 0);
        $faqData = [
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'question' => trim($_POST['question'] ?? ''),
            'answer' => trim($_POST['answer'] ?? ''),
            'show_on_homepage' => isset($_POST['show_on_homepage']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ];
        if (empty($faqData['question']) || empty($faqData['answer']) || $faqData['category_id'] < 1) {
            $message = 'Kategori, soru ve cevap alanları zorunludur.';
            $messageType = 'danger';
        } else {
            try {
                saveFaq($faqData, $faqId);
                $_SESSION['admin_message'] = $faqId > 0 ? 'Soru güncellendi.' : 'Yeni soru eklendi.';
                $_SESSION['admin_message_type'] = 'success';
                header('Location: ' . ADMIN_URL . '/dashboard.php?page=sss');
                exit;
            } catch (Exception $e) {
                $message = 'Kaydetme hatası.';
                $messageType = 'danger';
            }
        }
    }

    // SSS Soru sil
    if ($action === 'delete_faq') {
        $faqId = (int)($_POST['faq_id'] ?? 0);
        if ($faqId > 0) {
            try {
                deleteFaq($faqId);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Soru silindi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Silme hatası.']); exit; }
            }
        }
    }

    // SSS Anasayfa toggle (AJAX)
    if ($action === 'toggle_faq_homepage') {
        $faqId = (int)($_POST['faq_id'] ?? 0);
        $status = (int)($_POST['show_on_homepage'] ?? 0);
        if ($faqId > 0) {
            try {
                $db = getDB();
                $stmt = $db->prepare("UPDATE faqs SET show_on_homepage = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $faqId]);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Anasayfa durumu güncellendi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata oluştu.']); exit; }
            }
        }
    }

    // SSS Aktif/Pasif toggle (AJAX)
    if ($action === 'toggle_faq_active') {
        $faqId = (int)($_POST['faq_id'] ?? 0);
        $status = (int)($_POST['is_active'] ?? 0);
        if ($faqId > 0) {
            try {
                $db = getDB();
                $stmt = $db->prepare("UPDATE faqs SET is_active = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $faqId]);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Durum güncellendi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata oluştu.']); exit; }
            }
        }
    }

    // SSS Kategori aktif/pasif toggle (AJAX)
    if ($action === 'toggle_faq_category') {
        $catId = (int)($_POST['category_id'] ?? 0);
        $status = (int)($_POST['is_active'] ?? 0);
        if ($catId > 0) {
            try {
                $db = getDB();
                $stmt = $db->prepare("UPDATE faq_categories SET is_active = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $catId]);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Durum güncellendi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata oluştu.']); exit; }
            }
        }
    }

    // ==================== Blog İşlemleri ====================

    // Blog Kategori kaydet
    if ($action === 'save_blog_category') {
        $catId = (int)($_POST['category_id'] ?? 0);
        $catData = [
            'id' => $catId,
            'name' => trim($_POST['cat_name'] ?? ''),
            'slug' => trim($_POST['cat_slug'] ?? ''),
            'icon' => trim($_POST['cat_icon'] ?? ''),
            'color' => trim($_POST['cat_color'] ?? '#0066cc'),
            'sort_order' => (int)($_POST['cat_sort_order'] ?? 0),
            'is_active' => isset($_POST['cat_is_active']) ? 1 : 0,
        ];
        if (empty($catData['slug'])) {
            $catData['slug'] = strtolower(str_replace(['ı','ö','ü','ş','ç','ğ',' '], ['i','o','u','s','c','g','-'], $catData['name']));
            $catData['slug'] = preg_replace('/[^a-z0-9-]/', '', $catData['slug']);
            $catData['slug'] = preg_replace('/-+/', '-', trim($catData['slug'], '-'));
        }
        try {
            saveBlogCategory($catData);
            $_SESSION['admin_message'] = $catId ? 'Kategori güncellendi.' : 'Yeni kategori eklendi.';
            $_SESSION['admin_message_type'] = 'success';
        } catch (Exception $e) {
            $_SESSION['admin_message'] = 'Hata: ' . $e->getMessage();
            $_SESSION['admin_message_type'] = 'danger';
        }
        header('Location: ' . ADMIN_URL . '/dashboard.php?page=blog-kategoriler');
        exit;
    }

    // Blog Kategori sil
    if ($action === 'delete_blog_category') {
        $catId = (int)($_POST['category_id'] ?? 0);
        if ($catId > 0) {
            try {
                deleteBlogCategory($catId);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Kategori silindi.']); exit; }
                $_SESSION['admin_message'] = 'Kategori silindi.';
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata: ' . $e->getMessage()]); exit; }
            }
        }
    }

    // Blog Yazı kaydet
    if ($action === 'save_blog_post') {
        $postId = (int)($_POST['post_id'] ?? 0);
        $postData = [
            'id' => $postId,
            'category_id' => (int)($_POST['category_id'] ?? 0) ?: null,
            'title' => trim($_POST['title'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'content' => $_POST['content'] ?? '',
            'featured_image' => trim($_POST['existing_image'] ?? ''),
            'icon' => trim($_POST['icon'] ?? ''),
            'icon_bg' => trim($_POST['icon_bg'] ?? ''),
            'reading_time' => (int)($_POST['reading_time'] ?? 5),
            'meta_title' => trim($_POST['meta_title'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'published_at' => $_POST['published_at'] ? date('Y-m-d H:i:s', strtotime($_POST['published_at'])) : date('Y-m-d H:i:s'),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ];
        if (empty($postData['slug'])) {
            $postData['slug'] = strtolower(str_replace(['ı','ö','ü','ş','ç','ğ',' '], ['i','o','u','s','c','g','-'], $postData['title']));
            $postData['slug'] = preg_replace('/[^a-z0-9-]/', '', $postData['slug']);
            $postData['slug'] = preg_replace('/-+/', '-', trim($postData['slug'], '-'));
        }
        // Görsel yükleme
        if (!empty($_FILES['featured_image']['name']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $_FILES['featured_image']['tmp_name']);
            finfo_close($finfo);
            if (in_array($mimeType, $allowedTypes) && $_FILES['featured_image']['size'] <= 5 * 1024 * 1024) {
                $uploadDir = __DIR__ . '/../uploads/blog/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $ext = pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION);
                $fileName = $postData['slug'] . '-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $uploadDir . $fileName)) {
                    // Eski resmi sil
                    if ($postData['featured_image'] && file_exists(__DIR__ . '/../' . $postData['featured_image'])) {
                        @unlink(__DIR__ . '/../' . $postData['featured_image']);
                    }
                    $postData['featured_image'] = 'uploads/blog/' . $fileName;
                }
            }
        }
        try {
            saveBlogPost($postData);
            $_SESSION['admin_message'] = $postId ? 'Yazı güncellendi.' : 'Yeni yazı eklendi.';
            $_SESSION['admin_message_type'] = 'success';
        } catch (Exception $e) {
            $_SESSION['admin_message'] = 'Hata: ' . $e->getMessage();
            $_SESSION['admin_message_type'] = 'danger';
        }
        header('Location: ' . ADMIN_URL . '/dashboard.php?page=blog');
        exit;
    }

    // Blog Yazı sil
    if ($action === 'delete_blog_post') {
        $postId = (int)($_POST['post_id'] ?? 0);
        if ($postId > 0) {
            try {
                deleteBlogPost($postId);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Yazı silindi.']); exit; }
                $_SESSION['admin_message'] = 'Yazı silindi.';
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata: ' . $e->getMessage()]); exit; }
            }
        }
    }

    // Blog Yazı aktif/pasif toggle
    if ($action === 'toggle_blog_post') {
        $postId = (int)($_POST['post_id'] ?? 0);
        $status = (int)($_POST['is_active'] ?? 0);
        if ($postId > 0) {
            try {
                $db = getDB();
                $db->prepare("UPDATE blog_posts SET is_active = ?, updated_at = NOW() WHERE id = ?")->execute([$status, $postId]);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Durum güncellendi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata oluştu.']); exit; }
            }
        }
    }

    // Blog Yazı öne çıkan toggle
    if ($action === 'toggle_blog_featured') {
        $postId = (int)($_POST['post_id'] ?? 0);
        $val = (int)($_POST['is_featured'] ?? 0);
        if ($postId > 0) {
            try {
                $db = getDB();
                $db->prepare("UPDATE blog_posts SET is_featured = ?, updated_at = NOW() WHERE id = ?")->execute([$val, $postId]);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Öne çıkarma güncellendi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata oluştu.']); exit; }
            }
        }
    }

    // Blog Kategori aktif/pasif toggle
    if ($action === 'toggle_blog_category') {
        $catId = (int)($_POST['category_id'] ?? 0);
        $status = (int)($_POST['is_active'] ?? 0);
        if ($catId > 0) {
            try {
                $db = getDB();
                $db->prepare("UPDATE blog_categories SET is_active = ?, updated_at = NOW() WHERE id = ?")->execute([$status, $catId]);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Durum güncellendi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata oluştu.']); exit; }
            }
        }
    }

    // ==================== Harici Haberler ====================

    if ($action === 'refresh_external_news') {
        try {
            $count = fetchAndCacheNews();
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => "$count haber güncellendi."]); exit; }
            $successMsg = "$count haber güncellendi.";
        } catch (Exception $e) {
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => $e->getMessage()]); exit; }
            $errorMsg = $e->getMessage();
        }
    }

    if ($action === 'toggle_external_news') {
        $newsId = (int)($_POST['news_id'] ?? 0);
        $status = (int)($_POST['is_active'] ?? 0);
        if ($newsId > 0) {
            toggleExternalNews($newsId, $status);
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Durum güncellendi.']); exit; }
        }
    }

    if ($action === 'delete_external_news') {
        $newsId = (int)($_POST['news_id'] ?? 0);
        if ($newsId > 0) {
            deleteExternalNews($newsId);
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Haber silindi.']); exit; }
        }
    }

    // ==================== Kampanyalar ====================
    if ($action === 'save_campaign') {
        $campaignId = (int)($_POST['campaign_id'] ?? 0);
        $campData = [
            'title' => trim($_POST['campaign_title'] ?? ''),
            'slug' => createCampaignSlug(trim($_POST['campaign_title'] ?? '')),
            'description' => trim($_POST['campaign_description'] ?? ''),
            'short_description' => trim($_POST['campaign_short_description'] ?? ''),
            'discount_text' => trim($_POST['campaign_discount_text'] ?? ''),
            'category' => trim($_POST['campaign_category'] ?? ''),
            'icon' => trim($_POST['campaign_icon'] ?? 'fas fa-tag'),
            'image' => '',
            'bg_color' => trim($_POST['campaign_bg_color'] ?? 'linear-gradient(135deg, #1E3A8A, #162d6b)'),
            'features' => trim($_POST['campaign_features'] ?? ''),
            'link_url' => trim($_POST['campaign_link_url'] ?? ''),
            'link_text' => trim($_POST['campaign_link_text'] ?? 'Teklif Al'),
            'start_date' => trim($_POST['campaign_start_date'] ?? ''),
            'end_date' => trim($_POST['campaign_end_date'] ?? ''),
            'is_active' => isset($_POST['campaign_is_active']) ? 1 : 0,
            'is_popular' => isset($_POST['campaign_is_popular']) ? 1 : 0,
            'sort_order' => (int)($_POST['campaign_sort_order'] ?? 0),
        ];

        if (empty($campData['title']) || empty($campData['start_date']) || empty($campData['end_date'])) {
            $message = 'Başlık, başlangıç ve bitiş tarihi zorunludur.';
            $messageType = 'danger';
        } else {
            // Mevcut görseli koru
            if ($campaignId > 0) {
                $existingCamp = getCampaign($campaignId);
                $campData['image'] = $existingCamp['image'] ?? '';
            }

            // Görsel yükleme
            if (!empty($_FILES['campaign_image']['name']) && $_FILES['campaign_image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/png', 'image/jpeg', 'image/webp'];
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($_FILES['campaign_image']['tmp_name']);
                if (in_array($mimeType, $allowedTypes) && $_FILES['campaign_image']['size'] <= 5 * 1024 * 1024) {
                    $ext = pathinfo($_FILES['campaign_image']['name'], PATHINFO_EXTENSION);
                    $ext = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $ext));
                    if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) $ext = 'jpg';
                    $fileName = 'campaign_' . time() . '_' . random_int(1000, 9999) . '.' . $ext;
                    $uploadDir = dirname(__DIR__) . '/uploads/campaigns/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    if (move_uploaded_file($_FILES['campaign_image']['tmp_name'], $uploadDir . $fileName)) {
                        if (!empty($campData['image'])) {
                            $oldPath = dirname(__DIR__) . '/' . $campData['image'];
                            if (file_exists($oldPath)) @unlink($oldPath);
                        }
                        $campData['image'] = 'uploads/campaigns/' . $fileName;
                    }
                }
            }

            try {
                saveCampaign($campData, $campaignId);
                $_SESSION['admin_message'] = $campaignId > 0 ? 'Kampanya güncellendi.' : 'Yeni kampanya eklendi.';
                $_SESSION['admin_message_type'] = 'success';
                header('Location: ' . ADMIN_URL . '/dashboard.php?page=kampanyalar');
                exit;
            } catch (Exception $e) {
                $message = 'Kaydetme hatası: ' . $e->getMessage();
                $messageType = 'danger';
            }
        }
    }

    if ($action === 'delete_campaign') {
        $campaignId = (int)($_POST['campaign_id'] ?? 0);
        if ($campaignId > 0) {
            try {
                $camp = getCampaign($campaignId);
                if ($camp && !empty($camp['image'])) {
                    $oldPath = dirname(__DIR__) . '/' . $camp['image'];
                    if (file_exists($oldPath)) @unlink($oldPath);
                }
                deleteCampaign($campaignId);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Kampanya silindi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Silme hatası.']); exit; }
            }
        }
    }

    if ($action === 'toggle_campaign') {
        $campaignId = (int)($_POST['campaign_id'] ?? 0);
        $status = (int)($_POST['is_active'] ?? 0);
        if ($campaignId > 0) {
            try {
                $db = getDB();
                $stmt = $db->prepare("UPDATE campaigns SET is_active = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $campaignId]);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Durum güncellendi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata oluştu.']); exit; }
            }
        }
    }

    // ==================== İşlem Geri Alma ====================
    if ($action === 'revert_audit_log') {
        $logId = (int)($_POST['log_id'] ?? 0);
        if ($logId > 0 && hasRole('yonetici')) {
            try {
                $result = revertAuditLog($logId);
                $_recordId = $logId;
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => $result['message']]); exit; }
                $message = $result['message']; $messageType = 'success';
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => $e->getMessage()]); exit; }
                $message = $e->getMessage(); $messageType = 'danger';
            }
        } else {
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Yetkiniz yok veya geçersiz kayıt.']); exit; }
        }
    }

    // ==================== Şubeler ====================
    if ($action === 'save_branch') {
        $branchId = (int)($_POST['branch_id'] ?? 0);
        $branchData = [
            'name' => trim($_POST['branch_name'] ?? ''),
            'city' => trim($_POST['branch_city'] ?? ''),
            'address' => trim($_POST['branch_address'] ?? ''),
            'phone' => trim($_POST['branch_phone'] ?? ''),
            'phone_alt' => trim($_POST['branch_phone_alt'] ?? ''),
            'email' => trim($_POST['branch_email'] ?? ''),
            'maps_embed' => trim($_POST['branch_maps_embed'] ?? ''),
            'maps_link' => trim($_POST['branch_maps_link'] ?? ''),
            'working_hours' => trim($_POST['branch_working_hours'] ?? ''),
            'is_headquarters' => isset($_POST['branch_is_headquarters']) ? 1 : 0,
            'is_active' => isset($_POST['branch_is_active']) ? 1 : 0,
            'sort_order' => (int)($_POST['branch_sort_order'] ?? 0),
        ];

        if (empty($branchData['name'])) {
            $message = 'Şube adı zorunludur.';
            $messageType = 'danger';
        } else {
            try {
                $newId = saveBranch($branchData, $branchId);
                $_recordId = $branchId ?: $newId;
                $_SESSION['admin_message'] = $branchId > 0 ? 'Şube güncellendi.' : 'Yeni şube eklendi.';
                $_SESSION['admin_message_type'] = 'success';
                header('Location: ' . ADMIN_URL . '/dashboard.php?page=subeler');
                exit;
            } catch (Exception $e) {
                $message = 'Kaydetme hatası: ' . $e->getMessage();
                $messageType = 'danger';
            }
        }
    }

    if ($action === 'delete_branch') {
        $branchId = (int)($_POST['branch_id'] ?? 0);
        if ($branchId > 0) {
            try {
                deleteBranch($branchId);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Şube silindi.']); exit; }
                $message = 'Şube silindi.';
                $messageType = 'success';
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Silme hatası.']); exit; }
                $message = 'Silme hatası.';
                $messageType = 'danger';
            }
        }
    }

    if ($action === 'toggle_branch') {
        $branchId = (int)($_POST['branch_id'] ?? 0);
        $status = (int)($_POST['is_active'] ?? 0);
        if ($branchId > 0) {
            try {
                $db = getDB();
                $stmt = $db->prepare("UPDATE branches SET is_active = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $branchId]);
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => true, 'message' => 'Durum güncellendi.']); exit; }
            } catch (Exception $e) {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Hata oluştu.']); exit; }
            }
        }
    }

    // ==================== Dosya Yönetimi ====================
    if ($action === 'upload_file' && hasRole('personel')) {
        $uploadDir = sanitizeInput($_POST['upload_dir'] ?? '');
        $allowedDirs = ['campaigns', 'partners', 'ruhsat'];
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'pdf', 'gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($uploadDir, $allowedDirs)) {
            $message = 'Geçersiz klasör.'; $messageType = 'danger';
        } elseif (empty($_FILES['upload_file']['name'])) {
            $message = 'Lütfen bir dosya seçin.'; $messageType = 'danger';
        } else {
            $file = $_FILES['upload_file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $origName = pathinfo($file['name'], PATHINFO_FILENAME);
            // Sanitize filename
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $origName);
            $safeName = substr($safeName, 0, 80);

            if (!in_array($ext, $allowedExts)) {
                $message = 'Bu dosya türüne izin verilmiyor. İzin verilen: ' . implode(', ', $allowedExts); $messageType = 'danger';
            } elseif ($file['size'] > $maxSize) {
                $message = 'Dosya boyutu 5MB\'dan büyük olamaz.'; $messageType = 'danger';
            } elseif ($file['error'] !== UPLOAD_ERR_OK) {
                $message = 'Dosya yükleme hatası.'; $messageType = 'danger';
            } else {
                $targetDir = __DIR__ . '/../uploads/' . $uploadDir . '/';
                if (!is_dir($targetDir)) { mkdir($targetDir, 0755, true); }
                $targetName = $safeName . '_' . time() . '.' . $ext;
                $targetPath = $targetDir . $targetName;

                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $message = 'Dosya başarıyla yüklendi: ' . $targetName; $messageType = 'success';
                } else {
                    $message = 'Dosya yüklenirken hata oluştu.'; $messageType = 'danger';
                }
            }
        }
        $currentPage = 'dosyalar';
    }

    if ($action === 'delete_file' && hasRole('personel')) {
        $filePath = $_POST['file_path'] ?? '';
        $allowedDirs = ['campaigns', 'partners', 'ruhsat'];
        $uploadsBase = realpath(__DIR__ . '/../uploads');

        // Security: resolve real path and ensure it's within uploads/
        $fullPath = realpath(__DIR__ . '/../' . $filePath);
        if ($fullPath && strpos($fullPath, $uploadsBase) === 0 && is_file($fullPath)) {
            // Check the file is in an allowed subdirectory
            $relativePath = substr($fullPath, strlen($uploadsBase) + 1);
            $dirPart = explode(DIRECTORY_SEPARATOR, $relativePath)[0] ?? '';
            // Normalize for Unix/Windows
            $dirPart = str_replace('/', '', str_replace('\\', '', $dirPart));
            // Re-check against cleaned allowed dirs
            $dirPartClean = strtolower(trim($dirPart));
            $allowed = false;
            foreach ($allowedDirs as $ad) {
                if ($dirPartClean === $ad) { $allowed = true; break; }
            }

            if ($allowed && unlink($fullPath)) {
                $message = 'Dosya silindi.'; $messageType = 'success';
            } else {
                $message = 'Dosya silinemedi veya izin yok.'; $messageType = 'danger';
            }
        } else {
            $message = 'Geçersiz dosya yolu.'; $messageType = 'danger';
        }
        $currentPage = 'dosyalar';
    }

    // Non-exit durumda shutdown'dan önce logla (çift loglama önlenir)
    if ($action && isset($_actionLabels[$action]) && !$_logDone) {
        $_logDone = true;
        $_safePost = $_POST;
        unset($_safePost['password'], $_safePost['_csrf_token']);
        if (!$_recordId) {
            $_recordId = (int)($_POST['page_id'] ?? $_POST['user_id'] ?? $_POST['partner_id'] ?? $_POST['campaign_id'] ?? $_POST['branch_id'] ?? $_POST['post_id'] ?? $_POST['submission_id'] ?? $_POST['social_id'] ?? $_POST['testimonial_id'] ?? $_POST['category_id'] ?? $_POST['faq_id'] ?? $_POST['news_id'] ?? 0);
        }
        logAdminAction(
            $action,
            $_actionLabels[$action],
            $_actionTableMap[$action] ?? null,
            $_recordId ?: null,
            $_oldData,
            $_safePost
        );
    }
}

// ==================== Veri Çek ====================
$pages = getAllPages();

// --- Parent-child ağaç yapısı oluşturucu ---
function buildPageTree($pages) {
    $tree = [];
    $refs = [];
    foreach ($pages as &$p) {
        $p['children'] = [];
        $refs[$p['id']] = &$p;
    }
    unset($p);
    foreach ($refs as $id => &$p) {
        if (!empty($p['parent_id']) && isset($refs[$p['parent_id']])) {
            $refs[$p['parent_id']]['children'][] = &$p;
        } else {
            $tree[] = &$p;
        }
    }
    unset($p);
    return $tree;
}

$pageTree = buildPageTree($pages);
$allSettings = getAllSettings();

// Sayfa içeriğinden sadece content kısmını ayıkla
function extractPageContent($fullContent) {
    // header include'dan sonrasını al
    $headerMarker = "<?php include 'includes/header.php'; ?>";
    $footerMarker = "<?php include 'includes/footer.php'; ?>";
    
    $headerPos = strpos($fullContent, $headerMarker);
    $footerPos = strrpos($fullContent, $footerMarker);
    
    if ($headerPos === false || $footerPos === false) {
        return $fullContent; // marker bulunamazsa tüm içeriği döndür
    }
    
    $contentStart = $headerPos + strlen($headerMarker);
    $content = substr($fullContent, $contentStart, $footerPos - $contentStart);
    return trim($content);
}

// Content'ten tam sayfa dosyası oluştur
function buildPageFile($seoTitle, $seoDesc, $seoKeys, $content) {
    $seoTitle = addslashes($seoTitle);
    $seoDesc = addslashes($seoDesc);
    $seoKeys = addslashes($seoKeys);
    
    $php = "<?php\n";
    $php .= "require_once __DIR__ . '/includes/config.php';\n";
    $php .= "\$pageTitle = '{$seoTitle}';\n";
    $php .= "\$pageDescription = '{$seoDesc}';\n";
    $php .= "\$pageKeywords = '{$seoKeys}';\n";
    $php .= "?>\n";
    $php .= "<?php include 'includes/header.php'; ?>\n\n";
    $php .= trim($content) . "\n\n";
    $php .= "<?php include 'includes/footer.php'; ?>";
    
    return $php;
}

// Sayfa düzenle modunda mı?
$editPage = null;
$fileContent = '';
if ($currentPage === 'sayfa-duzenle' && isset($_GET['id'])) {
    $editPage = getPage((int)$_GET['id']);
    if (!$editPage) { $currentPage = 'sayfalar'; }
    else {
        // Dosya içeriğinden sadece content kısmını oku
        $filePath = dirname(__DIR__) . '/' . $editPage['slug'];
        if (file_exists($filePath) && is_readable($filePath)) {
            $rawFile = file_get_contents($filePath);
            $fileContent = extractPageContent($rawFile);
        }
    }
}

// Kullanıcı düzenle modunda mı?
$editUser = null;
if ($currentPage === 'kullanici-duzenle' && isset($_GET['id'])) {
    $editUser = getUser((int)$_GET['id']);
    if (!$editUser) { $currentPage = 'kullanicilar'; }
}

// Kampanya düzenle modunda mı?
$editCampaign = null;
if ($currentPage === 'kampanya-duzenle' && isset($_GET['id'])) {
    $editCampaign = getCampaign((int)$_GET['id']);
    if (!$editCampaign) { $currentPage = 'kampanyalar'; }
}

// Kampanya listesi
$allCampaigns = in_array($currentPage, ['kampanyalar', 'kampanya-ekle', 'kampanya-duzenle']) ? getAllCampaigns() : [];
$nextCampSortOrder = empty($allCampaigns) ? 1 : max(array_column($allCampaigns, 'sort_order')) + 1;

// Kullanıcı listesi
$users = (hasRole('yonetici') && in_array($currentPage, ['kullanicilar', 'kullanici-ekle', 'kullanici-duzenle'])) ? getAllUsers() : [];

// İstatistikler
$totalPages = count($pages);
$activePages = count(array_filter($pages, fn($p) => $p['is_active']));
$passivePages = $totalPages - $activePages;
$newSubmissionsCount = getFormSubmissionCount(['status' => 'yeni']);

// Güvenlik loglarını oku
function getSecurityLogs($limit = 20) {
    $logDir = dirname(__DIR__) . '/logs';
    $logFile = $logDir . '/security_' . date('Y-m') . '.log';
    if (!file_exists($logFile)) return [];
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) return [];
    return array_slice(array_reverse($lines), 0, $limit);
}

// Ayarları gruplara ayır
$settingGroups = [];
foreach ($allSettings as $key => $s) {
    $group = $s['setting_group'] ?? 'genel';
    $settingGroups[$group][$key] = $s;
}

include __DIR__ . '/includes/admin-header.php';
?>

<?php
// Session'dan gelen mesajları oku
if (!empty($_SESSION['admin_message'])) {
    $message = $_SESSION['admin_message'];
    $messageType = $_SESSION['admin_message_type'] ?? 'success';
    unset($_SESSION['admin_message'], $_SESSION['admin_message_type']);
}
?>

<?php if ($message): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" style="border-radius: 10px; font-size: 14px;">
    <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
    <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($currentPage === 'dashboard'): ?>
    <!-- Dashboard İstatistikleri -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Toplam Sayfa</p>
                        <h3 class="mb-0 fw-bold"><?php echo $totalPages; ?></h3>
                    </div>
                    <div class="icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-file-alt"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Aktif Sayfa</p>
                        <h3 class="mb-0 fw-bold text-success"><?php echo $activePages; ?></h3>
                    </div>
                    <div class="icon bg-success bg-opacity-10 text-success"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Pasif Sayfa</p>
                        <h3 class="mb-0 fw-bold text-warning"><?php echo $passivePages; ?></h3>
                    </div>
                    <div class="icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-eye-slash"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <a href="?page=basvurular" class="text-decoration-none">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Yeni Başvuru</p>
                        <h3 class="mb-0 fw-bold text-danger"><?php echo $newSubmissionsCount; ?></h3>
                    </div>
                    <div class="icon bg-danger bg-opacity-10 text-danger"><i class="fas fa-bell"></i></div>
                </div>
            </div>
            </a>
        </div>
    </div>

    <!-- Hızlı İşlemler -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-bolt me-2 text-warning"></i>Hızlı İşlemler</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="<?php echo SITE_URL; ?>/" target="_blank" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-globe d-block mb-1" style="font-size: 24px;"></i>
                                <small>Siteyi Görüntüle</small>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="?page=sayfalar" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-file-alt d-block mb-1" style="font-size: 24px;"></i>
                                <small>Sayfalar</small>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="?page=ayarlar" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-cog d-block mb-1" style="font-size: 24px;"></i>
                                <small>Site Ayarları</small>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="?page=guvenlik" class="btn btn-outline-danger w-100 py-3">
                                <i class="fas fa-shield-alt d-block mb-1" style="font-size: 24px;"></i>
                                <small>Güvenlik Logları</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Son Güvenlik Logları -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
            <h6 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-info"></i>Son Güvenlik Olayları</h6>
            <a href="?page=guvenlik" class="btn btn-sm btn-outline-info">Tümünü Gör</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th style="font-size: 13px;">Tarih</th><th style="font-size: 13px;">Olay</th></tr>
                    </thead>
                    <tbody>
                        <?php $logs = getSecurityLogs(10);
                        if (empty($logs)): ?>
                            <tr><td colspan="2" class="text-center text-muted py-4">Henüz güvenlik kaydı yok.</td></tr>
                        <?php else: foreach ($logs as $log):
                            $parts = explode('] ', $log, 3);
                            $date = trim($parts[0] ?? '', '[');
                            $detail = $parts[2] ?? $parts[1] ?? $log; ?>
                            <tr>
                                <td style="font-size: 13px; white-space: nowrap;"><?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td style="font-size: 13px;"><?php echo htmlspecialchars($detail, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php elseif ($currentPage === 'sayfalar'): ?>
    <!-- Sayfa Yönetimi -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2 text-primary"></i>Sayfa Yönetimi</h6>
        <a href="?page=sayfa-ekle" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Yeni Sayfa</a>
    </div>
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size: 13px; width: 40px;">#</th>
                            <th style="font-size: 13px;">Sayfa Adı</th>
                            <th style="font-size: 13px;">Slug</th>
                            <th style="font-size: 13px;">Kategori</th>
                            <th style="font-size: 13px;">SEO</th>
                            <th style="font-size: 13px; text-align: center;">Durum</th>
                            <th style="font-size: 13px; text-align: center; width: 140px;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody id="pages-tbody">
                        <?php
                        // --- Hiyerarşik tablo satırı oluşturucu ---
                        function renderPageRows($tree, $level = 0, $parent = 0) {
                            foreach ($tree as $pg) {
                                $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
                                echo '<tr id="page-row-' . $pg['id'] . '" data-id="' . $pg['id'] . '" data-parent="' . $parent . '">';
                                echo '<td style="font-size: 13px;">' . $pg['sort_order'] . '</td>';
                                $levelAttr = ' data-level="' . $level . '"';
                                $subLabel = $level > 0 ? '<span class="sub-label">alt başlık</span>' : '';
                                echo '<td style="font-size: 13px;" class="fw-semibold"><div class="page-title-cell"' . $levelAttr . '>' . htmlspecialchars($pg['title'], ENT_QUOTES, 'UTF-8') . $subLabel . '</div></td>';
                                echo '<td style="font-size: 13px;"><code>' . htmlspecialchars($pg['slug'], ENT_QUOTES, 'UTF-8') . '</code></td>';
                                echo '<td><span class="badge bg-secondary-subtle text-secondary" style="font-size: 11px;">' . htmlspecialchars($pg['category'] ?? 'genel', ENT_QUOTES, 'UTF-8') . '</span></td>';
                                echo '<td>';
                                if (!empty($pg['seo_title'])) {
                                    echo '<span class="badge bg-success-subtle text-success" style="font-size: 11px;"><i class="fas fa-check"></i> SEO</span>';
                                } else {
                                    echo '<span class="badge bg-warning-subtle text-warning" style="font-size: 11px;"><i class="fas fa-minus"></i> Eksik</span>';
                                }
                                echo '</td>';
                                echo '<td class="text-center">';
                                echo '<div class="form-check form-switch d-flex justify-content-center">';
                                echo '<input class="form-check-input toggle-page" type="checkbox" data-id="' . $pg['id'] . '"' . ($pg['is_active'] ? ' checked' : '') . ' style="cursor: pointer; width: 40px; height: 20px;">';
                                echo '</div>';
                                echo '</td>';
                                echo '<td class="text-center">';
                                echo '<a href="' . SITE_URL . '/' . htmlspecialchars($pg['slug'], ENT_QUOTES, 'UTF-8') . '" target="_blank" class="btn btn-sm btn-outline-secondary py-0 px-2" title="Önizle"><i class="fas fa-eye"></i></a>';
                                echo '<a href="?page=sayfa-duzenle&id=' . $pg['id'] . '" class="btn btn-sm btn-outline-primary py-0 px-2" title="Düzenle"><i class="fas fa-edit"></i></a>';
                                echo '<button class="btn btn-sm btn-outline-danger py-0 px-2 btn-delete-page" data-id="' . $pg['id'] . '" data-title="' . htmlspecialchars($pg['title'], ENT_QUOTES, 'UTF-8') . '" title="Sil"><i class="fas fa-trash"></i></button>';
                                echo '</td>';
                                echo '</tr>';
                                if (!empty($pg['children'])) {
                                    renderPageRows($pg['children'], $level + 1, $pg['id']);
                                }
                            }
                        }
                        renderPageRows($pageTree);
                        ?>
                    </tbody>
                </table>

            <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
            <script>
            // --- Drag & drop ve AJAX ile sıralama kaydetme ---
            document.addEventListener('DOMContentLoaded', function () {
                var tbody = document.getElementById('pages-tbody');
                if (tbody && window.Sortable) {
                    new Sortable(tbody, {
                        animation: 180,
                        handle: 'td',
                        ghostClass: 'sortable-ghost',
                        onEnd: function (evt) {
                            // Sıralama sonrası yeni parent ve order bilgilerini topla
                            var rows = tbody.querySelectorAll('tr');
                            var data = [];
                            rows.forEach(function (tr, idx) {
                                data.push({
                                    id: tr.getAttribute('data-id'),
                                    parent_id: tr.getAttribute('data-parent') || 0,
                                    sort_order: idx + 1
                                });
                            });
                            // AJAX ile backend'e gönder
                            fetch('api/form-submit.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ action: 'save_page_order', pages: data })
                            })
                            .then(r => r.json())
                            .then(resp => {
                                if (resp.success) {
                                    // Başarı bildirimi
                                    alert('Sıralama kaydedildi!');
                                } else {
                                    alert('Bir hata oluştu!');
                                }
                            })
                            .catch(() => alert('Bir hata oluştu!'));
                        }
                    });
                }
            });
            </script>
            </div>
                    </div>
                </div>

<?php elseif ($currentPage === 'sayfa-ekle' || $currentPage === 'sayfa-duzenle'): ?>
    <!-- Sayfa Ekle / Düzenle -->
    <?php $isEdit = ($currentPage === 'sayfa-duzenle' && $editPage); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-bold"><i class="fas fa-<?php echo $isEdit ? 'edit' : 'plus-circle'; ?> me-2 text-primary"></i><?php echo $isEdit ? 'Sayfa Düzenle' : 'Yeni Sayfa Ekle'; ?></h6>
        <a href="?page=sayfalar" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Geri</a>
    </div>
    
    <form method="post" action="dashboard.php?page=sayfalar">
        <?php echo getCSRFTokenField(); ?>
        <input type="hidden" name="action" value="save_page">
        <?php if ($isEdit): ?><input type="hidden" name="page_id" value="<?php echo $editPage['id']; ?>"><?php endif; ?>
        
        <!-- SEO Alanları (Üstte) -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; border-left: 4px solid #0d6efd !important;">
            <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-search me-2 text-primary"></i>SEO Ayarları</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 13px;">SEO Başlığı</label>
                        <input type="text" name="seo_title" class="form-control form-control-sm" value="<?php echo htmlspecialchars($isEdit ? $editPage['seo_title'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Arama motorlarında görünecek başlık" maxlength="70">
                        <small class="text-muted">Önerilen: 50-60 karakter</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 13px;">OG Type</label>
                        <select name="og_type" class="form-select form-select-sm">
                            <?php $ogVal = $isEdit ? $editPage['og_type'] : 'website'; ?>
                            <option value="website" <?php echo $ogVal === 'website' ? 'selected' : ''; ?>>website</option>
                            <option value="article" <?php echo $ogVal === 'article' ? 'selected' : ''; ?>>article</option>
                            <option value="product" <?php echo $ogVal === 'product' ? 'selected' : ''; ?>>product</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size: 13px;">SEO Açıklama</label>
                        <textarea name="seo_description" class="form-control form-control-sm" rows="2" placeholder="Arama motorlarında görünecek açıklama" maxlength="160"><?php echo htmlspecialchars($isEdit ? $editPage['seo_description'] : '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        <small class="text-muted">Önerilen: 120-160 karakter</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size: 13px;">SEO Anahtar Kelimeler</label>
                        <input type="text" name="seo_keywords" class="form-control form-control-sm" value="<?php echo htmlspecialchars($isEdit ? $editPage['seo_keywords'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="virgülle ayırarak yazın">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sayfa Bilgileri -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2 text-success"></i>Sayfa Bilgileri</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 13px;">Sayfa Başlığı *</label>
                        <input type="text" name="title" class="form-control form-control-sm" value="<?php echo htmlspecialchars($isEdit ? $editPage['title'] : '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 13px;">Slug (Dosya Adı) *</label>
                        <input type="text" name="slug" class="form-control form-control-sm" value="<?php echo htmlspecialchars($isEdit ? $editPage['slug'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="ornek-sayfa.php" required>
                        <small class="text-muted">Örn: trafik-sigortasi.php</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size: 13px;">Kategori</label>
                        <select name="category" class="form-select form-select-sm">
                            <?php $catVal = $isEdit ? $editPage['category'] : 'genel'; ?>
                            <option value="arac" <?php echo $catVal === 'arac' ? 'selected' : ''; ?>>Araç Sigortaları</option>
                            <option value="saglik" <?php echo $catVal === 'saglik' ? 'selected' : ''; ?>>Sağlık Sigortaları</option>
                            <option value="konut" <?php echo $catVal === 'konut' ? 'selected' : ''; ?>>Konut Sigortaları</option>
                            <option value="diger" <?php echo $catVal === 'diger' ? 'selected' : ''; ?>>Diğer Ürünler</option>
                            <option value="kurumsal" <?php echo $catVal === 'kurumsal' ? 'selected' : ''; ?>>Kurumsal</option>
                            <option value="yasal" <?php echo $catVal === 'yasal' ? 'selected' : ''; ?>>Yasal</option>
                            <option value="genel" <?php echo $catVal === 'genel' ? 'selected' : ''; ?>>Genel</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size: 13px;">Sıra No</label>
                        <input type="number" name="sort_order" class="form-control form-control-sm" value="<?php echo $isEdit ? $editPage['sort_order'] : getNextSortOrder(); ?>" min="0">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="pageActive" <?php echo (!$isEdit || $editPage['is_active']) ? 'checked' : ''; ?> style="width: 40px; height: 20px;">
                            <label class="form-check-label fw-semibold" for="pageActive" style="font-size: 13px;">Aktif</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sayfa İçeriği -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; border-left: 4px solid #198754 !important;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-align-left me-2 text-success"></i>Sayfa İçeriği</h6>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-primary active" id="btnVisualMode" onclick="switchEditorMode('visual')">
                        <i class="fas fa-eye me-1"></i> Görsel
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btnCodeMode" onclick="switchEditorMode('code')">
                        <i class="fas fa-code me-1"></i> Kod
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <?php
                    $contentForEditor = '';
                    if ($isEdit && !empty($fileContent)) {
                        $contentForEditor = $fileContent;
                    } elseif ($isEdit) {
                        $contentForEditor = $editPage['page_content'] ?? '';
                    }
                ?>
                <!-- Görsel Editör -->
                <div id="visualEditorWrap" style="padding: 16px;">
                    <textarea id="visualEditor"><?php echo htmlspecialchars($contentForEditor, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <!-- Kod Editörü -->
                <div id="codeEditorWrap" style="display: none;">
                    <textarea name="file_content" id="codeEditor" class="form-control" rows="30" style="font-family: 'Courier New', Consolas, monospace; font-size: 13px; line-height: 1.6; border: none; border-radius: 0; resize: vertical; tab-size: 4; white-space: pre; overflow-wrap: normal; overflow-x: auto;"><?php echo htmlspecialchars($contentForEditor, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <input type="hidden" name="editor_mode" id="editorMode" value="visual">
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary" onclick="syncBeforeSave()"><i class="fas fa-save me-1"></i> <?php echo $isEdit ? 'Güncelle' : 'Kaydet'; ?></button>
            <a href="?page=sayfalar" class="btn btn-outline-secondary">İptal</a>
        </div>
    </form>

<script>
var editorMode = 'visual';
var tinyReady = false;

// TinyMCE başlat
document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: '#visualEditor',
        height: 500,
        language: 'tr',
        menubar: 'file edit view insert format tools table',
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | code fullscreen | removeformat help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 14px; line-height: 1.6; padding: 8px; }',
        branding: false,
        promotion: false,
        relative_urls: false,
        remove_script_host: false,
        entity_encoding: 'raw',
        verify_html: false,
        valid_elements: '*[*]',
        extended_valid_elements: 'script[*],style[*],link[*],meta[*],php',
        valid_children: '+body[style|script|link|meta|php]',
        forced_root_block: false,
        cleanup: false,
        setup: function(editor) {
            editor.on('init', function() {
                tinyReady = true;
            });
        }
    });
});

function switchEditorMode(mode) {
    var visualWrap = document.getElementById('visualEditorWrap');
    var codeWrap = document.getElementById('codeEditorWrap');
    var btnVisual = document.getElementById('btnVisualMode');
    var btnCode = document.getElementById('btnCodeMode');
    var codeEditor = document.getElementById('codeEditor');
    var modeField = document.getElementById('editorMode');

    if (mode === 'code') {
        // Görsel -> Kod: TinyMCE içeriğini kod editörüne aktar
        if (tinyReady && tinymce.get('visualEditor')) {
            codeEditor.value = tinymce.get('visualEditor').getContent({format: 'raw'});
        }
        visualWrap.style.display = 'none';
        codeWrap.style.display = 'block';
        btnCode.className = 'btn btn-primary active';
        btnVisual.className = 'btn btn-outline-primary';
        editorMode = 'code';
    } else {
        // Kod -> Görsel: Kod editöründen TinyMCE'ye aktar
        if (tinyReady && tinymce.get('visualEditor')) {
            tinymce.get('visualEditor').setContent(codeEditor.value);
        }
        codeWrap.style.display = 'none';
        visualWrap.style.display = 'block';
        btnVisual.className = 'btn btn-primary active';
        btnCode.className = 'btn btn-outline-primary';
        editorMode = 'visual';
    }
    modeField.value = editorMode;
}

function syncBeforeSave() {
    var codeEditor = document.getElementById('codeEditor');
    if (editorMode === 'visual' && tinyReady && tinymce.get('visualEditor')) {
        codeEditor.value = tinymce.get('visualEditor').getContent({format: 'raw'});
    }
    // page_content alanını da doldur (DB'ye kaydedilecek)
    var pcField = document.querySelector('input[name="page_content"]');
    if (!pcField) {
        pcField = document.createElement('input');
        pcField.type = 'hidden';
        pcField.name = 'page_content';
        codeEditor.form.appendChild(pcField);
    }
    pcField.value = codeEditor.value;
}

// Tab tuşu desteği (kod editöründe)
document.addEventListener('DOMContentLoaded', function() {
    var codeEditor = document.getElementById('codeEditor');
    if (codeEditor) {
        codeEditor.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                e.preventDefault();
                var start = this.selectionStart;
                var end = this.selectionEnd;
                this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);
                this.selectionStart = this.selectionEnd = start + 4;
            }
        });
    }
});
</script>

<?php elseif ($currentPage === 'ayarlar'): ?>
    <!-- Site Ayarları -->
    <h6 class="mb-3 fw-bold"><i class="fas fa-cog me-2 text-info"></i>Site Ayarları</h6>
    
    <form method="post" action="" enctype="multipart/form-data" id="settingsForm">
        <?php echo getCSRFTokenField(); ?>
        <input type="hidden" name="action" value="save_settings">
        
        <!-- Genel Bilgiler -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Genel Bilgiler</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php
                    $genelFields = ['site_name', 'site_url', 'site_domain', 'site_founded'];
                    foreach ($genelFields as $key):
                        $s = $settingGroups['genel'][$key] ?? null;
                        if (!$s) continue;
                    ?>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 13px;"><?php echo htmlspecialchars($s['setting_label'], ENT_QUOTES, 'UTF-8'); ?></label>
                        <input type="text" name="settings[<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>]" class="form-control form-control-sm" value="<?php echo htmlspecialchars($s['setting_value'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- İletişim Bilgileri (ayrı sayfada yönetiliyor) -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-phone-alt me-2 text-success"></i>İletişim Bilgileri</h6>
            </div>
            <div class="card-body text-center py-4">
                <p class="text-muted mb-3">İletişim bilgileri artık ayrı bir sayfadan yönetiliyor.</p>
                <a href="?page=iletisim-ayarlari" class="btn btn-outline-success btn-sm"><i class="fas fa-external-link-alt me-1"></i> İletişim Bilgileri Yönetimi</a>
            </div>
        </div>

        <!-- Görsel Ayarları (Logo & Favicon) -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-image me-2 text-warning"></i>Logo & Görsel Ayarları</h6>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <?php
                    $gorselFields = [
                        'site_logo' => ['Logo (Koyu)', 'Menüde ve sayfalarda kullanılır', '#f8f9fa'],
                        'site_logo_white' => ['Logo (Beyaz)', 'Footer ve koyu alanlarda kullanılır', '#343a40'],
                        'site_favicon' => ['Favicon', 'Tarayıcı sekmesinde görünen ikon', '#f8f9fa'],
                    ];
                    foreach ($gorselFields as $key => $info):
                        $currentPath = $settingGroups['gorsel'][$key]['setting_value'] ?? '';
                        $fullUrl = $currentPath ? (SITE_URL . $currentPath) : '';
                    ?>
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 h-100">
                            <label class="form-label fw-semibold d-block" style="font-size: 13px;"><?php echo $info[0]; ?></label>
                            <div class="text-center mb-3 p-3 rounded-3" style="background: <?php echo $info[2]; ?>; min-height: 80px; display:flex; align-items:center; justify-content:center;">
                                <?php if ($fullUrl): ?>
                                    <img src="<?php echo htmlspecialchars($fullUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo $info[0]; ?>" style="max-height: 50px; max-width: 100%;" id="preview_<?php echo $key; ?>">
                                <?php else: ?>
                                    <span class="text-muted" style="font-size: 12px;" id="preview_<?php echo $key; ?>">Henüz yüklenmemiş</span>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="<?php echo $key; ?>" class="form-control form-control-sm" accept="image/*" onchange="previewImage(this, 'preview_<?php echo $key; ?>')">
                            <small class="text-muted"><?php echo $info[1]; ?></small>
                            <input type="hidden" name="settings[<?php echo $key; ?>]" value="<?php echo htmlspecialchars($currentPath, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Sosyal Medya -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-share-alt me-2 text-info"></i>Sosyal Medya</h6>
            </div>
            <div class="card-body text-center py-4">
                <p class="text-muted mb-3">Sosyal medya hesapları artık ayrı bir sayfadan yönetiliyor.</p>
                <a href="?page=sosyal-medya" class="btn btn-outline-primary btn-sm"><i class="fas fa-external-link-alt me-1"></i> Sosyal Medya Yönetimi</a>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Ayarları Kaydet</button>
    </form>

    <script>
    function previewImage(input, previewId) {
        var preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxHeight = '50px';
                    img.style.maxWidth = '100%';
                    img.id = previewId;
                    preview.parentNode.replaceChild(img, preview);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>

<?php elseif ($currentPage === 'guvenlik'): ?>
    <!-- Güvenlik Logları -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
            <h6 class="mb-0 fw-bold"><i class="fas fa-shield-alt me-2 text-danger"></i>Güvenlik Logları</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size: 13px;">Tarih/Saat</th>
                            <th style="font-size: 13px;">IP Adresi</th>
                            <th style="font-size: 13px;">Olay</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $logs = getSecurityLogs(50);
                        if (empty($logs)): ?>
                            <tr><td colspan="3" class="text-center text-muted py-4">Henüz güvenlik kaydı yok.</td></tr>
                        <?php else: foreach ($logs as $log):
                            preg_match('/\[(.*?)\].*?\[IP: (.*?)\] (.*)/', $log, $matches);
                            $date = $matches[1] ?? '';
                            $ip = $matches[2] ?? '';
                            $event = $matches[3] ?? $log; ?>
                            <tr>
                                <td style="font-size: 13px; white-space: nowrap;"><?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td style="font-size: 13px;"><code><?php echo htmlspecialchars($ip, ENT_QUOTES, 'UTF-8'); ?></code></td>
                                <td style="font-size: 13px;"><?php echo htmlspecialchars($event, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php elseif ($currentPage === 'kullanicilar'): ?>
    <!-- Kullanıcı Yönetimi -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-bold"><i class="fas fa-users-cog me-2 text-primary"></i>Kullanıcı Yönetimi</h6>
        <a href="?page=kullanici-ekle" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Yeni Kullanıcı</a>
    </div>
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size: 13px; width: 40px;">#</th>
                            <th style="font-size: 13px;">Kullanıcı Adı</th>
                            <th style="font-size: 13px;">Ad Soyad</th>
                            <th style="font-size: 13px;">E-posta</th>
                            <th style="font-size: 13px;">Rol</th>
                            <th style="font-size: 13px;">Son Giriş</th>
                            <th style="font-size: 13px;">Kayıt Tarihi</th>
                            <th style="font-size: 13px; text-align: center; width: 120px;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">Henüz kullanıcı bulunmuyor.</td></tr>
                        <?php else: foreach ($users as $usr): 
                            $roleBadgeMap = ['yonetici' => ['Yönetici', 'danger'], 'personel' => ['Personel', 'primary'], 'misafir' => ['Misafir', 'secondary']];
                            $ub = $roleBadgeMap[$usr['role'] ?? 'misafir'] ?? ['Misafir', 'secondary'];
                        ?>
                        <tr>
                            <td style="font-size: 13px;"><?php echo $usr['id']; ?></td>
                            <td style="font-size: 13px;" class="fw-semibold"><?php echo htmlspecialchars($usr['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td style="font-size: 13px;"><?php echo htmlspecialchars($usr['full_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td style="font-size: 13px;"><?php echo htmlspecialchars($usr['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><span class="badge bg-<?php echo $ub[1]; ?>" style="font-size: 11px;"><?php echo $ub[0]; ?></span></td>
                            <td style="font-size: 13px;"><?php echo $usr['last_login'] ? date('d.m.Y H:i', strtotime($usr['last_login'])) : '<span class="text-muted">-</span>'; ?></td>
                            <td style="font-size: 13px;"><?php echo date('d.m.Y', strtotime($usr['created_at'])); ?></td>
                            <td class="text-center">
                                <a href="?page=kullanici-duzenle&id=<?php echo $usr['id']; ?>" class="btn btn-sm btn-outline-primary py-0 px-2" title="Düzenle"><i class="fas fa-edit"></i></a>
                                <?php if ((int)$usr['id'] !== (int)($_SESSION['admin_id'] ?? 0) && (int)$usr['id'] !== 1): ?>
                                <button class="btn btn-sm btn-outline-danger py-0 px-2 btn-delete-user" data-id="<?php echo $usr['id']; ?>" data-name="<?php echo htmlspecialchars($usr['username'], ENT_QUOTES, 'UTF-8'); ?>" title="Sil"><i class="fas fa-trash"></i></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php elseif ($currentPage === 'kullanici-ekle' || $currentPage === 'kullanici-duzenle'): ?>
    <!-- Kullanıcı Ekle / Düzenle -->
    <?php $isUserEdit = ($currentPage === 'kullanici-duzenle' && $editUser); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-bold"><i class="fas fa-<?php echo $isUserEdit ? 'user-edit' : 'user-plus'; ?> me-2 text-primary"></i><?php echo $isUserEdit ? 'Kullanıcı Düzenle' : 'Yeni Kullanıcı Ekle'; ?></h6>
        <a href="?page=kullanicilar" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Geri</a>
    </div>
    
    <form method="post" action="dashboard.php?page=kullanicilar">
        <?php echo getCSRFTokenField(); ?>
        <input type="hidden" name="action" value="save_user">
        <?php if ($isUserEdit): ?><input type="hidden" name="user_id" value="<?php echo $editUser['id']; ?>"><?php endif; ?>
        
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-user me-2 text-success"></i>Kullanıcı Bilgileri</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 13px;">Kullanıcı Adı *</label>
                        <input type="text" name="username" class="form-control form-control-sm" value="<?php echo htmlspecialchars($isUserEdit ? $editUser['username'] : '', ENT_QUOTES, 'UTF-8'); ?>" required autocomplete="off">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 13px;">Ad Soyad *</label>
                        <input type="text" name="full_name" class="form-control form-control-sm" value="<?php echo htmlspecialchars($isUserEdit ? $editUser['full_name'] : '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 13px;">E-posta</label>
                        <input type="email" name="email" class="form-control form-control-sm" value="<?php echo htmlspecialchars($isUserEdit ? ($editUser['email'] ?? '') : '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 13px;">Rol</label>
                        <select name="role" class="form-select form-select-sm">
                            <?php $roleVal = $isUserEdit ? $editUser['role'] : 'personel'; ?>
                            <option value="yonetici" <?php echo $roleVal === 'yonetici' ? 'selected' : ''; ?>>Yönetici</option>
                            <option value="personel" <?php echo $roleVal === 'personel' ? 'selected' : ''; ?>>Personel</option>
                            <option value="misafir" <?php echo $roleVal === 'misafir' ? 'selected' : ''; ?>>Misafir</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 13px;">Şifre <?php echo $isUserEdit ? '' : '*'; ?></label>
                        <input type="password" name="password" class="form-control form-control-sm" <?php echo $isUserEdit ? '' : 'required'; ?> autocomplete="new-password">
                        <?php if ($isUserEdit): ?><small class="text-muted">Değiştirmek istemiyorsanız boş bırakın.</small><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> <?php echo $isUserEdit ? 'Güncelle' : 'Kaydet'; ?></button>
            <a href="?page=kullanicilar" class="btn btn-outline-secondary">İptal</a>
        </div>
    </form>

<?php elseif ($currentPage === 'mesajlar'): ?>
<?php
    $msgFilters = ['form_type' => 'iletisim'];
    if (!empty($_GET['durum'])) $msgFilters['status'] = sanitizeInput($_GET['durum']);
    $messages = getFormSubmissions($msgFilters);
?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="fas fa-envelope me-2 text-primary"></i>İletişim Mesajları</h5>
        <div class="d-flex gap-2">
            <a href="?page=mesajlar" class="btn btn-sm <?php echo empty($_GET['durum']) ? 'btn-primary' : 'btn-outline-secondary'; ?>">Tümü</a>
            <a href="?page=mesajlar&durum=yeni" class="btn btn-sm <?php echo ($_GET['durum'] ?? '') === 'yeni' ? 'btn-danger' : 'btn-outline-danger'; ?>">Yeni</a>
            <a href="?page=mesajlar&durum=okundu" class="btn btn-sm <?php echo ($_GET['durum'] ?? '') === 'okundu' ? 'btn-warning' : 'btn-outline-warning'; ?>">Okundu</a>
            <a href="?page=mesajlar&durum=tamamlandi" class="btn btn-sm <?php echo ($_GET['durum'] ?? '') === 'tamamlandi' ? 'btn-success' : 'btn-outline-success'; ?>">Tamamlandı</a>
        </div>
    </div>
    <?php if (empty($messages)): ?>
    <div class="card border-0 shadow-sm" style="border-radius: 12px;"><div class="card-body text-center py-5">
        <i class="fas fa-inbox text-muted mb-3" style="font-size: 48px;"></i>
        <p class="text-muted">Henüz mesaj bulunmuyor.</p>
    </div></div>
    <?php else: ?>
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size: 13px;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 12px 16px;">#</th>
                        <th>Ad Soyad</th>
                        <th>Telefon</th>
                        <th>Konu</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($messages as $msg): 
                    $mData = json_decode($msg['form_data'], true) ?: [];
                ?>
                    <tr>
                        <td style="padding: 12px 16px;"><?php echo $msg['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($mData['ad_soyad'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></strong></td>
                        <td><?php echo htmlspecialchars($mData['telefon'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($mData['konu'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <?php 
                            $statusColors = ['yeni' => 'danger', 'okundu' => 'warning', 'tamamlandi' => 'success'];
                            $statusLabels = ['yeni' => 'Yeni', 'okundu' => 'Okundu', 'tamamlandi' => 'Tamamlandı'];
                            ?>
                            <span class="badge bg-<?php echo $statusColors[$msg['status']] ?? 'secondary'; ?>"><?php echo $statusLabels[$msg['status']] ?? $msg['status']; ?></span>
                        </td>
                        <td><?php echo date('d.m.Y H:i', strtotime($msg['created_at'])); ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="showSubmissionDetail(<?php echo $msg['id']; ?>)" title="Detay"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-submission" data-id="<?php echo $msg['id']; ?>" title="Sil"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

<?php elseif ($currentPage === 'basvurular'): ?>
<?php
    $subFilters = [];
    if (!empty($_GET['tip'])) $subFilters['form_type'] = sanitizeInput($_GET['tip']);
    if (!empty($_GET['durum'])) $subFilters['status'] = sanitizeInput($_GET['durum']);
    // İletişim mesajları hariç (onlar mesajlar sekmesinde)
    $allSubmissions = getFormSubmissions($subFilters);
    if (empty($_GET['tip'])) {
        $allSubmissions = array_filter($allSubmissions, fn($s) => $s['form_type'] !== 'iletisim');
    }
    
    $formTypeLabels = [
        'trafik' => 'Trafik Sigortası', 'kasko' => 'Kasko', 'dask' => 'DASK',
        'el-trafik' => 'El Değiştiren Trafik', 'elektrikli-arac-kasko' => 'Elektrikli Araç Kasko',
        'kisa-sureli-trafik' => 'Kısa Süreli Trafik', 'imm' => 'İMM', 'yesil-kart' => 'Yeşil Kart',
        'tamamlayici-saglik' => 'Tamamlayıcı Sağlık', 'ozel-saglik' => 'Özel Sağlık',
        'seyahat-saglik' => 'Seyahat Sağlık', 'pembe-kurdele' => 'Pembe Kurdele',
        'konut-sigortasi' => 'Konut Sigortası', 'evim-guvende' => 'Evim Güvende',
        'cep-telefonu' => 'Cep Tel. Sigortası', 'evcil-hayvan' => 'Evcil Hayvan',
        'ferdi-kaza' => 'Ferdi Kaza', 'sube-basvurusu' => 'Şube Başvurusu',
        'police-iptal' => 'Poliçe İptal', 'iletisim' => 'İletişim',
        'anasayfa-trafik' => 'Trafik (Hızlı)', 'anasayfa-kasko' => 'Kasko (Hızlı)',
        'anasayfa-dask' => 'DASK (Hızlı)', 'anasayfa-saglik' => 'Sağlık (Hızlı)',
        'kampanya-basvuru' => 'Kampanya Başvurusu',
    ];
?>
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0"><i class="fas fa-clipboard-list me-2 text-primary"></i>Teklif Talepleri & Başvurular</h5>
        <div class="d-flex gap-2 flex-wrap">
            <a href="?page=basvurular" class="btn btn-sm <?php echo empty($_GET['durum']) && empty($_GET['tip']) ? 'btn-primary' : 'btn-outline-secondary'; ?>">Tümü</a>
            <a href="?page=basvurular&durum=yeni" class="btn btn-sm <?php echo ($_GET['durum'] ?? '') === 'yeni' ? 'btn-danger' : 'btn-outline-danger'; ?>">Yeni</a>
            <a href="?page=basvurular&durum=okundu" class="btn btn-sm <?php echo ($_GET['durum'] ?? '') === 'okundu' ? 'btn-warning' : 'btn-outline-warning'; ?>">Okundu</a>
            <a href="?page=basvurular&durum=tamamlandi" class="btn btn-sm <?php echo ($_GET['durum'] ?? '') === 'tamamlandi' ? 'btn-success' : 'btn-outline-success'; ?>">Tamamlandı</a>
            <select class="form-select form-select-sm" style="width: auto;" onchange="location.href='?page=basvurular&tip='+this.value">
                <option value="">Tüm Tipler</option>
                <?php foreach ($formTypeLabels as $k => $v): if ($k === 'iletisim') continue; ?>
                <option value="<?php echo $k; ?>" <?php echo ($_GET['tip'] ?? '') === $k ? 'selected' : ''; ?>><?php echo $v; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php if (empty($allSubmissions)): ?>
    <div class="card border-0 shadow-sm" style="border-radius: 12px;"><div class="card-body text-center py-5">
        <i class="fas fa-inbox text-muted mb-3" style="font-size: 48px;"></i>
        <p class="text-muted">Henüz başvuru bulunmuyor.</p>
    </div></div>
    <?php else: ?>
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size: 13px;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 12px 16px;">#</th>
                        <th>Tip</th>
                        <th>Ad / Telefon</th>
                        <th>Detaylar</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($allSubmissions as $sub): 
                    $sData = json_decode($sub['form_data'], true) ?: [];
                    $sName = $sub['visitor_name'] ?? $sData['ad_soyad'] ?? $sData['adsoyad'] ?? '-';
                    $sPhone = $sub['visitor_phone'] ?? $sData['telefon'] ?? $sData['cep_telefonu'] ?? '-';
                    // İlk 2-3 önemli alanı göster
                    $preview = [];
                    foreach ($sData as $fk => $fv) {
                        if (in_array($fk, ['ad_soyad', 'adsoyad', 'telefon', 'cep_telefonu', 'kvkk', 'mesaj', 'motivasyon', 'aciklama'])) continue;
                        if (empty($fv)) continue;
                        $preview[] = htmlspecialchars($fv, ENT_QUOTES, 'UTF-8');
                        if (count($preview) >= 3) break;
                    }
                ?>
                    <tr>
                        <td style="padding: 12px 16px;"><?php echo $sub['id']; ?></td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary"><?php echo $formTypeLabels[$sub['form_type']] ?? $sub['form_type']; ?></span></td>
                        <td>
                            <strong><?php echo htmlspecialchars($sName, ENT_QUOTES, 'UTF-8'); ?></strong><br>
                            <small class="text-muted"><?php echo htmlspecialchars($sPhone, ENT_QUOTES, 'UTF-8'); ?></small>
                        </td>
                        <td><small><?php echo implode(' | ', $preview); ?></small></td>
                        <td>
                            <?php
                            $statusColors = ['yeni' => 'danger', 'okundu' => 'warning', 'tamamlandi' => 'success'];
                            $statusLabels = ['yeni' => 'Yeni', 'okundu' => 'Okundu', 'tamamlandi' => 'Tamamlandı'];
                            ?>
                            <span class="badge bg-<?php echo $statusColors[$sub['status']] ?? 'secondary'; ?>"><?php echo $statusLabels[$sub['status']] ?? $sub['status']; ?></span>
                        </td>
                        <td><?php echo date('d.m.Y H:i', strtotime($sub['created_at'])); ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="showSubmissionDetail(<?php echo $sub['id']; ?>)" title="Detay"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-submission" data-id="<?php echo $sub['id']; ?>" title="Sil"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

<?php elseif ($currentPage === 'is-ortaklari'): ?>
<?php
    $adminPageTitle = 'İş Ortakları';
    $partners = getAllPartners();
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">Anlaşmalı Sigorta Şirketleri</h5>
            <p class="text-muted small mb-0">Toplam <?php echo count($partners); ?> iş ortağı</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnToggleSortMode">
                <i class="fas fa-sort me-1"></i> Sıralamayı Düzenle
            </button>
            <button type="button" class="btn btn-success btn-sm d-none" id="btnSaveSortOrder">
                <i class="fas fa-check me-1"></i> Sıralamayı Kaydet
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm d-none" id="btnCancelSort">
                <i class="fas fa-times me-1"></i> İptal
            </button>
            <a href="?page=is-ortagi-ekle" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Yeni Ekle</a>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" id="partnersTable">
                <thead class="table-light">
                    <tr>
                        <th class="sort-handle-col d-none" style="padding: 12px 8px; width: 40px;"></th>
                        <th style="padding: 12px 16px; width: 60px;">Sıra</th>
                        <th style="width: 80px;">Logo</th>
                        <th>Şirket Adı</th>
                        <th style="width: 100px;">Durum</th>
                        <th style="width: 140px;">İşlem</th>
                    </tr>
                </thead>
                <tbody id="partnersSortable">
                <?php if (empty($partners)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Henüz iş ortağı eklenmemiş.</td></tr>
                <?php else: ?>
                    <?php foreach ($partners as $p): ?>
                    <tr data-id="<?php echo $p['id']; ?>">
                        <td class="sort-handle-col d-none" style="padding: 12px 8px; cursor: grab;">
                            <i class="fas fa-grip-vertical text-muted"></i>
                        </td>
                        <td class="sort-order-cell" style="padding: 12px 16px;"><?php echo $p['sort_order']; ?></td>
                        <td>
                            <?php if (!empty($p['logo'])): ?>
                                <img src="<?php echo SITE_URL . '/' . htmlspecialchars($p['logo'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 40px; max-width: 70px; object-fit: contain;">
                            <?php else: ?>
                                <span class="badge bg-light text-muted" style="font-size: 10px;">Logo yok</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            <?php if (!empty($p['website'])): ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars($p['website'], ENT_QUOTES, 'UTF-8'); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-partner" type="checkbox" data-id="<?php echo $p['id']; ?>" <?php echo $p['is_active'] ? 'checked' : ''; ?>>
                            </div>
                        </td>
                        <td>
                            <a href="?page=is-ortagi-duzenle&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary" title="Düzenle"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-outline-danger btn-delete-partner" data-id="<?php echo $p['id']; ?>" data-name="<?php echo htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?>" title="Sil"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
    (function() {
        var sortMode = false;
        var sortableInstance = null;
        var btnToggle = document.getElementById('btnToggleSortMode');
        var btnSave = document.getElementById('btnSaveSortOrder');
        var btnCancel = document.getElementById('btnCancelSort');
        var tbody = document.getElementById('partnersSortable');
        var handleCols = document.querySelectorAll('.sort-handle-col');
        var originalOrder = [];

        function enterSortMode() {
            sortMode = true;
            btnToggle.classList.add('d-none');
            btnSave.classList.remove('d-none');
            btnCancel.classList.remove('d-none');
            handleCols.forEach(function(el) { el.classList.remove('d-none'); });
            document.getElementById('partnersTable').classList.add('table-sort-active');
            // Önceki sırayı kaydet
            originalOrder = [];
            tbody.querySelectorAll('tr[data-id]').forEach(function(row) {
                originalOrder.push(row.outerHTML);
            });
            sortableInstance = new Sortable(tbody, {
                animation: 200,
                handle: '.sort-handle-col',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function() { updateSortNumbers(); }
            });
        }

        function exitSortMode() {
            sortMode = false;
            btnToggle.classList.remove('d-none');
            btnSave.classList.add('d-none');
            btnCancel.classList.add('d-none');
            handleCols.forEach(function(el) { el.classList.add('d-none'); });
            document.getElementById('partnersTable').classList.remove('table-sort-active');
            if (sortableInstance) { sortableInstance.destroy(); sortableInstance = null; }
        }

        function updateSortNumbers() {
            tbody.querySelectorAll('tr[data-id]').forEach(function(row, i) {
                row.querySelector('.sort-order-cell').textContent = i + 1;
            });
        }

        btnToggle.addEventListener('click', enterSortMode);

        btnCancel.addEventListener('click', function() {
            // Orijinal sıraya geri dön
            var rows = tbody.querySelectorAll('tr[data-id]');
            rows.forEach(function(r) { r.remove(); });
            var temp = document.createElement('tbody');
            temp.innerHTML = originalOrder.join('');
            while (temp.firstChild) { tbody.appendChild(temp.firstChild); }
            // Handle kolonlarını gizle
            document.querySelectorAll('.sort-handle-col').forEach(function(el) { el.classList.add('d-none'); });
            handleCols = document.querySelectorAll('.sort-handle-col');
            exitSortMode();
        });

        btnSave.addEventListener('click', function() {
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Kaydediliyor...';
            var orderData = [];
            tbody.querySelectorAll('tr[data-id]').forEach(function(row, i) {
                orderData.push({ id: row.dataset.id, sort: i + 1 });
            });
            var formData = new FormData();
            formData.append('action', 'save_partner_order');
            formData.append('<?php echo CSRF_TOKEN_NAME; ?>', '<?php echo generateCSRFToken(); ?>');
            orderData.forEach(function(item, i) {
                formData.append('order[' + i + '][id]', item.id);
                formData.append('order[' + i + '][sort]', item.sort);
            });
            adminAjax('save_partner_order', formData, function(data) {
                if (data.success) {
                    showAdminToast('success', 'Başarılı', 'Sıralama başarıyla kaydedildi.');
                } else {
                    showAdminToast('danger', 'Hata', 'Sıralama kaydedilemedi.');
                }
                exitSortMode();
                btnSave.disabled = false;
                btnSave.innerHTML = '<i class="fas fa-check me-1"></i> Sıralamayı Kaydet';
            });
        });
    })();
    </script>
    <style>
    .table-sort-active tbody tr[data-id] { transition: background 0.2s; }
    .table-sort-active tbody tr[data-id]:hover { background: #f0f7ff; }
    .sortable-ghost { background: #e8f4fd !important; opacity: 0.8; }
    .sortable-chosen { background: #dbeafe !important; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .sort-handle-col i { font-size: 16px; color: #adb5bd; transition: color 0.2s; }
    .sort-handle-col:hover i { color: #0d6efd; }
    </style>

<?php elseif ($currentPage === 'is-ortagi-ekle' || $currentPage === 'is-ortagi-duzenle'): ?>
<?php
    $isPartnerEdit = ($currentPage === 'is-ortagi-duzenle');
    $editPartner = $isPartnerEdit ? getPartner((int)($_GET['id'] ?? 0)) : null;
    if ($isPartnerEdit && !$editPartner) { echo '<div class="alert alert-danger">İş ortağı bulunamadı.</div>'; $isPartnerEdit = false; }
    $adminPageTitle = $isPartnerEdit ? 'İş Ortağı Düzenle' : 'Yeni İş Ortağı';
    // Yeni eklemede otomatik sıralama: en son sort_order + 1
    if (!$isPartnerEdit) {
        $db = getDB();
        $maxSort = $db->query("SELECT COALESCE(MAX(sort_order), 0) FROM partners")->fetchColumn();
        $nextSortOrder = $maxSort + 1;
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><?php echo $isPartnerEdit ? 'İş Ortağı Düzenle' : 'Yeni İş Ortağı Ekle'; ?></h5>
        <a href="?page=is-ortaklari" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Geri</a>
    </div>
    
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            <form method="POST" action="<?php echo ADMIN_URL; ?>/dashboard.php?page=is-ortaklari" enctype="multipart/form-data">
                <?php echo getCSRFTokenField(); ?>
                <input type="hidden" name="action" value="save_partner">
                <?php if ($isPartnerEdit): ?><input type="hidden" name="partner_id" value="<?php echo $editPartner['id']; ?>"><?php endif; ?>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Şirket Adı <span class="text-danger">*</span></label>
                        <input type="text" name="partner_name" class="form-control" value="<?php echo htmlspecialchars($editPartner['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Website</label>
                        <input type="url" name="partner_website" class="form-control" value="<?php echo htmlspecialchars($editPartner['website'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Logo</label>
                        <input type="file" name="partner_logo" class="form-control" id="partnerLogoInput" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                        <small class="text-muted">PNG, JPG, WEBP veya SVG - Max 2MB - Önerilen: 200x80px, şeffaf arka plan</small>
                        <?php if ($isPartnerEdit && !empty($editPartner['logo'])): ?>
                            <div class="mt-2 p-2 bg-light rounded d-inline-block">
                                <img src="<?php echo SITE_URL . '/' . htmlspecialchars($editPartner['logo'], ENT_QUOTES, 'UTF-8'); ?>" alt="Mevcut logo" style="max-height: 50px; max-width: 120px; object-fit: contain;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sıralama</label>
                        <input type="number" name="partner_sort_order" class="form-control" value="<?php echo $isPartnerEdit ? $editPartner['sort_order'] : $nextSortOrder; ?>" min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Durum</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="partner_is_active" id="partnerActive" <?php echo ($isPartnerEdit && $editPartner['is_active']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="partnerActive">Aktif</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" id="partnerSubmitBtn"><i class="fas fa-save me-1"></i> Kaydet</button>
                    <a href="?page=is-ortaklari" class="btn btn-outline-secondary ms-2">İptal</a>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('partnerLogoInput').addEventListener('change', function() {
        var file = this.files[0];
        if (!file) return;
        var maxSize = 2 * 1024 * 1024; // 2MB
        if (file.size > maxSize) {
            this.value = '';
            var sizeMB = (file.size / 1024 / 1024).toFixed(1);
            showAdminToast('danger', 'Dosya Çok Büyük', 'Seçilen dosya ' + sizeMB + ' MB. Maksimum 2 MB logo yükleyebilirsiniz.');
        }
    });
    </script>

<?php elseif ($currentPage === 'sosyal-medya'): ?>
<?php
    $adminPageTitle = 'Sosyal Medya';
    $socialAccounts = getAllSocialMedia();
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">Sosyal Medya Hesapları</h5>
            <p class="text-muted small mb-0">Toplam <?php echo count($socialAccounts); ?> hesap</p>
        </div>
        <a href="?page=sosyal-medya-ekle" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Yeni Hesap Ekle</a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size: 14px;">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 12px 16px; width: 50px;">#</th>
                        <th>Platform</th>
                        <th>URL</th>
                        <th style="width: 100px;" class="text-center">Durum</th>
                        <th style="width: 120px;" class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($socialAccounts)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Henüz sosyal medya hesabı eklenmemiş.</td></tr>
                <?php else: ?>
                    <?php foreach ($socialAccounts as $social): ?>
                    <tr>
                        <td style="padding: 12px 16px;">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px; background: <?php echo htmlspecialchars($social['color'], ENT_QUOTES, 'UTF-8'); ?>1a; color: <?php echo htmlspecialchars($social['color'], ENT_QUOTES, 'UTF-8'); ?>;">
                                <i class="<?php echo htmlspecialchars($social['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                            </span>
                        </td>
                        <td class="align-middle">
                            <strong><?php echo htmlspecialchars($social['label'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            <br><small class="text-muted"><?php echo htmlspecialchars($social['platform'], ENT_QUOTES, 'UTF-8'); ?></small>
                        </td>
                        <td class="align-middle">
                            <?php if (!empty($social['url'])): ?>
                                <a href="<?php echo htmlspecialchars($social['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="text-decoration-none small" rel="noopener noreferrer">
                                    <?php echo htmlspecialchars(mb_strimwidth($social['url'], 0, 50, '...'), ENT_QUOTES, 'UTF-8'); ?>
                                    <i class="fas fa-external-link-alt ms-1" style="font-size: 10px;"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center align-middle">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input toggle-social" type="checkbox" data-id="<?php echo $social['id']; ?>" <?php echo $social['is_active'] ? 'checked' : ''; ?>>
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            <a href="?page=sosyal-medya-duzenle&id=<?php echo $social['id']; ?>" class="btn btn-sm btn-outline-primary" title="Düzenle"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-outline-danger btn-delete-social" data-id="<?php echo $social['id']; ?>" data-name="<?php echo htmlspecialchars($social['label'], ENT_QUOTES, 'UTF-8'); ?>" title="Sil"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
    .toggle-social { cursor: pointer; }
    .toggle-social:checked { background-color: #198754; border-color: #198754; }
    </style>

<?php elseif ($currentPage === 'sosyal-medya-ekle' || $currentPage === 'sosyal-medya-duzenle'): ?>
<?php
    $isSocialEdit = ($currentPage === 'sosyal-medya-duzenle');
    $editSocial = $isSocialEdit ? getSocialMedia((int)($_GET['id'] ?? 0)) : null;
    if ($isSocialEdit && !$editSocial) { echo '<div class="alert alert-danger">Sosyal medya hesabı bulunamadı.</div>'; $isSocialEdit = false; }
    $adminPageTitle = $isSocialEdit ? 'Sosyal Medya Düzenle' : 'Yeni Sosyal Medya';
    if (!$isSocialEdit) {
        $db = getDB();
        $maxSort = $db->query("SELECT COALESCE(MAX(sort_order), 0) FROM social_media")->fetchColumn();
        $nextSortOrder = $maxSort + 1;
    }
    
    $presetPlatforms = [
        ['facebook', 'Facebook', 'fab fa-facebook-f', '#1877F2'],
        ['instagram', 'Instagram', 'fab fa-instagram', '#E4405F'],
        ['twitter', 'Twitter/X', 'fab fa-x-twitter', '#000000'],
        ['linkedin', 'LinkedIn', 'fab fa-linkedin-in', '#0A66C2'],
        ['youtube', 'YouTube', 'fab fa-youtube', '#FF0000'],
        ['tiktok', 'TikTok', 'fab fa-tiktok', '#000000'],
        ['whatsapp', 'WhatsApp', 'fab fa-whatsapp', '#25D366'],
        ['telegram', 'Telegram', 'fab fa-telegram-plane', '#0088CC'],
        ['pinterest', 'Pinterest', 'fab fa-pinterest-p', '#BD081C'],
        ['snapchat', 'Snapchat', 'fab fa-snapchat-ghost', '#FFFC00'],
        ['threads', 'Threads', 'fab fa-threads', '#000000'],
        ['discord', 'Discord', 'fab fa-discord', '#5865F2'],
        ['github', 'GitHub', 'fab fa-github', '#333333'],
        ['other', 'Diğer (Özel)', 'fas fa-globe', '#6c757d'],
    ];
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><?php echo $isSocialEdit ? 'Sosyal Medya Düzenle' : 'Yeni Sosyal Medya Ekle'; ?></h5>
        <a href="?page=sosyal-medya" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Geri</a>
    </div>
    
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            <form method="POST" action="<?php echo ADMIN_URL; ?>/dashboard.php?page=sosyal-medya" id="socialForm">
                <?php echo getCSRFTokenField(); ?>
                <input type="hidden" name="action" value="save_social">
                <?php if ($isSocialEdit): ?><input type="hidden" name="social_id" value="<?php echo $editSocial['id']; ?>"><?php endif; ?>
                
                <?php if (!$isSocialEdit): ?>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Platform Seçin</label>
                    <div class="row g-2" id="platformGrid">
                        <?php foreach ($presetPlatforms as $p): ?>
                        <div class="col-4 col-md-3 col-lg-2">
                            <div class="platform-card text-center p-2 rounded-3 border" style="cursor: pointer; transition: all 0.2s;" 
                                 data-platform="<?php echo $p[0]; ?>" data-label="<?php echo $p[1]; ?>" data-icon="<?php echo $p[2]; ?>" data-color="<?php echo $p[3]; ?>"
                                 onclick="selectPlatform(this)">
                                <i class="<?php echo $p[2]; ?> fa-lg mb-1 d-block" style="color: <?php echo $p[3]; ?>;"></i>
                                <small class="d-block" style="font-size: 11px;"><?php echo $p[1]; ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Platform Kodu <span class="text-danger">*</span></label>
                        <input type="text" name="social_platform" id="socialPlatform" class="form-control" value="<?php echo htmlspecialchars($editSocial['platform'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required placeholder="facebook">
                        <small class="text-muted">Küçük harf, boşluksuz</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Görünen Ad <span class="text-danger">*</span></label>
                        <input type="text" name="social_label" id="socialLabel" class="form-control" value="<?php echo htmlspecialchars($editSocial['label'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required placeholder="Facebook">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">İkon Sınıfı</label>
                        <div class="input-group">
                            <span class="input-group-text" id="iconPreview"><i class="<?php echo htmlspecialchars($editSocial['icon'] ?? 'fas fa-globe', ENT_QUOTES, 'UTF-8'); ?>"></i></span>
                            <input type="text" name="social_icon" id="socialIcon" class="form-control" value="<?php echo htmlspecialchars($editSocial['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="fab fa-facebook-f">
                        </div>
                        <small class="text-muted">Font Awesome sınıfı</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">URL</label>
                        <input type="url" name="social_url" class="form-control" value="<?php echo htmlspecialchars($editSocial['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://facebook.com/sayfaniz">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Renk</label>
                        <input type="color" name="social_color" id="socialColor" class="form-control form-control-color w-100" value="<?php echo htmlspecialchars($editSocial['color'] ?? '#6c757d', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Sıralama</label>
                        <input type="number" name="social_sort_order" class="form-control" value="<?php echo $isSocialEdit ? $editSocial['sort_order'] : $nextSortOrder; ?>" min="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Durum</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="social_is_active" id="socialActive" <?php echo ($isSocialEdit && $editSocial['is_active']) ? 'checked' : (!$isSocialEdit ? '' : ''); ?>>
                            <label class="form-check-label" for="socialActive">Aktif</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Kaydet</button>
                    <a href="?page=sosyal-medya" class="btn btn-outline-secondary ms-2">İptal</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
    function selectPlatform(el) {
        document.querySelectorAll('.platform-card').forEach(function(c) { c.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10'); c.style.transform = ''; });
        el.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
        el.style.transform = 'scale(1.05)';
        document.getElementById('socialPlatform').value = el.dataset.platform;
        document.getElementById('socialLabel').value = el.dataset.label;
        document.getElementById('socialIcon').value = el.dataset.icon;
        document.getElementById('socialColor').value = el.dataset.color;
        document.getElementById('iconPreview').innerHTML = '<i class="' + el.dataset.icon + '" style="color:' + el.dataset.color + ';"></i>';
    }
    document.getElementById('socialIcon').addEventListener('input', function() {
        document.getElementById('iconPreview').innerHTML = '<i class="' + this.value + '"></i>';
    });
    </script>

<?php elseif ($currentPage === 'yorumlar'): ?>
<?php
    $adminPageTitle = 'Müşteri Yorumları';
    $testimonials = getAllTestimonials();
    // Session mesajı
    if (!empty($_SESSION['admin_message'])) {
        $message = $_SESSION['admin_message'];
        $messageType = $_SESSION['admin_message_type'] ?? 'success';
        unset($_SESSION['admin_message'], $_SESSION['admin_message_type']);
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">Müşteri Yorumları</h5>
            <p class="text-muted small mb-0">Toplam <?php echo count($testimonials); ?> yorum</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnToggleTestimonialSort">
                <i class="fas fa-sort me-1"></i> Sıralamayı Değiştir
            </button>
            <button type="button" class="btn btn-success btn-sm d-none" id="btnSaveTestimonialOrder">
                <i class="fas fa-check me-1"></i> Sıralamayı Kaydet
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm d-none" id="btnCancelTestimonialSort">
                <i class="fas fa-times me-1"></i> İptal
            </button>
            <a href="?page=yorum-ekle" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Yeni Yorum Ekle</a>
        </div>
    </div>

    <?php if ($message): ?>
    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert" style="font-size:13px;">
        <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="testimonialsTable">
                <thead class="table-light">
                    <tr>
                        <th class="testimonial-sort-handle-col d-none" style="padding: 12px 8px; width: 40px;"></th>
                        <th style="width:50px">Sıra</th>
                        <th>Müşteri</th>
                        <th>Yorum</th>
                        <th style="width:100px">Puan</th>
                        <th style="width:80px">Durum</th>
                        <th style="width:120px">İşlem</th>
                    </tr>
                </thead>
                <tbody id="testimonialsSortable">
                    <?php if (empty($testimonials)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Henüz yorum eklenmemiş.</td></tr>
                    <?php else: ?>
                    <?php foreach ($testimonials as $t): ?>
                    <tr data-id="<?php echo $t['id']; ?>">
                        <td class="testimonial-sort-handle-col d-none" style="padding: 12px 8px; cursor: grab;">
                            <i class="fas fa-grip-vertical text-muted"></i>
                        </td>
                        <td class="testimonial-sort-order-cell text-muted small"><?php echo $t['sort_order']; ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width:35px;height:35px;font-size:14px;font-weight:700;background:<?php echo htmlspecialchars($t['avatar_color'], ENT_QUOTES, 'UTF-8'); ?>;">
                                    <?php echo mb_strtoupper(mb_substr($t['author_name'], 0, 1, 'UTF-8'), 'UTF-8'); ?>
                                </div>
                                <div>
                                    <div class="fw-bold small"><?php echo htmlspecialchars($t['author_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="text-muted" style="font-size:11px"><?php echo htmlspecialchars($t['author_title'], ENT_QUOTES, 'UTF-8'); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="text-muted small"><?php echo htmlspecialchars(mb_substr($t['comment'], 0, 60, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?><?php echo mb_strlen($t['comment'], 'UTF-8') > 60 ? '...' : ''; ?></span></td>
                        <td>
                            <?php for ($i = 0; $i < $t['rating']; $i++): ?>
                            <i class="fa-solid fa-star text-warning" style="font-size:12px"></i>
                            <?php endfor; ?>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-testimonial" type="checkbox" data-id="<?php echo $t['id']; ?>" <?php echo $t['is_active'] ? 'checked' : ''; ?>>
                            </div>
                        </td>
                        <td>
                            <a href="?page=yorum-duzenle&id=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-primary" title="Düzenle"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-outline-danger btn-delete-testimonial" data-id="<?php echo $t['id']; ?>" data-name="<?php echo htmlspecialchars($t['author_name'], ENT_QUOTES, 'UTF-8'); ?>" title="Sil"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
    (function() {
        var sortMode = false;
        var sortableInstance = null;
        var btnToggle = document.getElementById('btnToggleTestimonialSort');
        var btnSave = document.getElementById('btnSaveTestimonialOrder');
        var btnCancel = document.getElementById('btnCancelTestimonialSort');
        var tbody = document.getElementById('testimonialsSortable');
        var handleCols = document.querySelectorAll('.testimonial-sort-handle-col');
        var originalOrder = [];

        function enterSortMode() {
            sortMode = true;
            btnToggle.classList.add('d-none');
            btnSave.classList.remove('d-none');
            btnCancel.classList.remove('d-none');
            handleCols.forEach(function(el) { el.classList.remove('d-none'); });
            document.getElementById('testimonialsTable').classList.add('table-sort-active');
            originalOrder = [];
            tbody.querySelectorAll('tr[data-id]').forEach(function(row) {
                originalOrder.push(row.outerHTML);
            });
            sortableInstance = new Sortable(tbody, {
                animation: 200,
                handle: '.testimonial-sort-handle-col',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function() { updateSortNumbers(); }
            });
        }

        function exitSortMode() {
            sortMode = false;
            btnToggle.classList.remove('d-none');
            btnSave.classList.add('d-none');
            btnCancel.classList.add('d-none');
            handleCols.forEach(function(el) { el.classList.add('d-none'); });
            document.getElementById('testimonialsTable').classList.remove('table-sort-active');
            if (sortableInstance) { sortableInstance.destroy(); sortableInstance = null; }
        }

        function updateSortNumbers() {
            tbody.querySelectorAll('tr[data-id]').forEach(function(row, i) {
                row.querySelector('.testimonial-sort-order-cell').textContent = i + 1;
            });
        }

        if (btnToggle) btnToggle.addEventListener('click', enterSortMode);

        if (btnCancel) btnCancel.addEventListener('click', function() {
            var rows = tbody.querySelectorAll('tr[data-id]');
            rows.forEach(function(r) { r.remove(); });
            var temp = document.createElement('tbody');
            temp.innerHTML = originalOrder.join('');
            while (temp.firstChild) { tbody.appendChild(temp.firstChild); }
            document.querySelectorAll('.testimonial-sort-handle-col').forEach(function(el) { el.classList.add('d-none'); });
            handleCols = document.querySelectorAll('.testimonial-sort-handle-col');
            exitSortMode();
        });

        if (btnSave) btnSave.addEventListener('click', function() {
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Kaydediliyor...';
            var orderData = [];
            tbody.querySelectorAll('tr[data-id]').forEach(function(row, i) {
                orderData.push({ id: row.dataset.id, sort: i + 1 });
            });
            var formData = new FormData();
            formData.append('action', 'save_testimonial_order');
            formData.append('<?php echo CSRF_TOKEN_NAME; ?>', '<?php echo generateCSRFToken(); ?>');
            orderData.forEach(function(item, i) {
                formData.append('order[' + i + '][id]', item.id);
                formData.append('order[' + i + '][sort]', item.sort);
            });
            adminAjax('save_testimonial_order', formData, function(data) {
                if (data.success) {
                    showAdminToast('success', 'Başarılı', 'Sıralama başarıyla kaydedildi.');
                } else {
                    showAdminToast('danger', 'Hata', 'Sıralama kaydedilemedi.');
                }
                exitSortMode();
                btnSave.disabled = false;
                btnSave.innerHTML = '<i class="fas fa-check me-1"></i> Sıralamayı Kaydet';
            });
        });
    })();
    </script>
    <style>
    .table-sort-active tbody tr[data-id] { transition: background 0.2s; }
    .table-sort-active tbody tr[data-id]:hover { background: #f0f7ff; }
    .testimonial-sort-handle-col i { font-size: 16px; color: #adb5bd; transition: color 0.2s; }
    .testimonial-sort-handle-col:hover i { color: #0d6efd; }
    </style>

<?php elseif ($currentPage === 'yorum-ekle' || $currentPage === 'yorum-duzenle'): ?>
<?php
    $isTestimonialEdit = ($currentPage === 'yorum-duzenle');
    $testimonialData = null;
    if ($isTestimonialEdit) {
        $testimonialData = getTestimonial((int)($_GET['id'] ?? 0));
        if (!$testimonialData) { header('Location: ?page=yorumlar'); exit; }
    }
    $adminPageTitle = $isTestimonialEdit ? 'Yorum Düzenle' : 'Yeni Yorum Ekle';
    if (!$isTestimonialEdit) {
        $db = getDB();
        $maxSort = $db->query("SELECT COALESCE(MAX(sort_order), 0) FROM testimonials")->fetchColumn();
        $nextTestimonialSort = $maxSort + 1;
    }
    $avatarColors = [
        '#0d6efd' => 'Mavi',
        '#198754' => 'Yeşil',
        '#ffc107' => 'Sarı',
        '#dc3545' => 'Kırmızı',
        '#6f42c1' => 'Mor',
        '#fd7e14' => 'Turuncu',
        '#0dcaf0' => 'Cyan',
        '#d63384' => 'Pembe',
        '#20c997' => 'Teal',
    ];
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><?php echo $adminPageTitle; ?></h5>
        <a href="?page=yorumlar" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Geri</a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            <form method="POST" action="<?php echo ADMIN_URL; ?>/dashboard.php?page=yorumlar">
                <?php echo getCSRFTokenField(); ?>
                <input type="hidden" name="action" value="save_testimonial">
                <input type="hidden" name="testimonial_id" value="<?php echo $isTestimonialEdit ? $testimonialData['id'] : 0; ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Müşteri Adı <span class="text-danger">*</span></label>
                        <input type="text" name="author_name" class="form-control" required maxlength="100" value="<?php echo $isTestimonialEdit ? htmlspecialchars($testimonialData['author_name'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Örn: Ahmet Y.">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Sigorta Türü</label>
                        <input type="text" name="author_title" class="form-control" maxlength="200" value="<?php echo $isTestimonialEdit ? htmlspecialchars($testimonialData['author_title'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Örn: Trafik Sigortası">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Yorum <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control" rows="4" required maxlength="1000" placeholder="Müşteri yorumunu yazın..."><?php echo $isTestimonialEdit ? htmlspecialchars($testimonialData['comment'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Puan</label>
                        <select name="rating" class="form-select">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo ($isTestimonialEdit && $testimonialData['rating'] == $i) ? 'selected' : ($i == 5 && !$isTestimonialEdit ? 'selected' : ''); ?>><?php echo $i; ?> Yıldız</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Avatar Rengi</label>
                        <select name="avatar_color" class="form-select">
                            <?php foreach ($avatarColors as $hex => $label): ?>
                            <option value="<?php echo $hex; ?>" <?php echo ($isTestimonialEdit && $testimonialData['avatar_color'] === $hex) ? 'selected' : ''; ?> style="color:<?php echo $hex; ?>;font-weight:bold;">&#9679; <?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Sıra No</label>
                        <input type="number" name="sort_order" class="form-control" min="0" value="<?php echo $isTestimonialEdit ? $testimonialData['sort_order'] : $nextTestimonialSort; ?>">
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="testimonialActive" <?php echo (!$isTestimonialEdit || $testimonialData['is_active']) ? 'checked' : ''; ?>>
                            <label class="form-check-label small" for="testimonialActive">Aktif (sitede göster)</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> <?php echo $isTestimonialEdit ? 'Güncelle' : 'Kaydet'; ?></button>
                    <a href="?page=yorumlar" class="btn btn-outline-secondary btn-sm">İptal</a>
                </div>
            </form>
        </div>
    </div>

<?php elseif ($currentPage === 'sss'): ?>
<?php
    $adminPageTitle = 'Sıkça Sorulan Sorular';
    $faqCategoryFilter = (int)($_GET['cat'] ?? 0);
    $faqFilters = [];
    if ($faqCategoryFilter > 0) $faqFilters['category_id'] = $faqCategoryFilter;
    $allFaqs = getAllFaqs($faqFilters);
    $faqCategories = getAllFaqCategories();
    if (!empty($_SESSION['admin_message'])) {
        $message = $_SESSION['admin_message'];
        $messageType = $_SESSION['admin_message_type'] ?? 'success';
        unset($_SESSION['admin_message'], $_SESSION['admin_message_type']);
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">SSS Yönetimi</h5>
            <p class="text-muted small mb-0">Toplam <?php echo count($allFaqs); ?> soru</p>
        </div>
        <div class="d-flex gap-2">
            <a href="?page=sss-kategoriler" class="btn btn-outline-secondary btn-sm"><i class="fas fa-tags me-1"></i> Kategoriler</a>
            <a href="?page=sss-ekle" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Yeni Soru Ekle</a>
        </div>
    </div>

    <?php if ($message): ?>
    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert" style="font-size:13px;">
        <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Kategori Filtresi -->
    <div class="mb-3">
        <div class="d-flex gap-2 flex-wrap">
            <a href="?page=sss" class="btn btn-sm <?php echo $faqCategoryFilter === 0 ? 'btn-primary' : 'btn-outline-secondary'; ?>">Tümü</a>
            <?php foreach ($faqCategories as $fc): ?>
            <a href="?page=sss&cat=<?php echo $fc['id']; ?>" class="btn btn-sm <?php echo $faqCategoryFilter === (int)$fc['id'] ? 'btn-primary' : 'btn-outline-secondary'; ?>"><?php echo htmlspecialchars($fc['name'], ENT_QUOTES, 'UTF-8'); ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Soru</th>
                        <th style="width:140px">Kategori</th>
                        <th style="width:90px">Anasayfa</th>
                        <th style="width:80px">Durum</th>
                        <th style="width:60px">Sıra</th>
                        <th style="width:120px">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($allFaqs)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Henüz soru eklenmemiş.</td></tr>
                    <?php else: ?>
                    <?php foreach ($allFaqs as $fq): ?>
                    <tr>
                        <td class="text-muted small"><?php echo $fq['id']; ?></td>
                        <td>
                            <div class="fw-bold small"><?php echo htmlspecialchars(mb_substr($fq['question'], 0, 70, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?><?php echo mb_strlen($fq['question'], 'UTF-8') > 70 ? '...' : ''; ?></div>
                        </td>
                        <td><span class="badge bg-light text-dark" style="font-size:11px"><?php echo htmlspecialchars($fq['category_name'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-faq-homepage" type="checkbox" data-id="<?php echo $fq['id']; ?>" <?php echo $fq['show_on_homepage'] ? 'checked' : ''; ?>>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-faq-active" type="checkbox" data-id="<?php echo $fq['id']; ?>" <?php echo $fq['is_active'] ? 'checked' : ''; ?>>
                            </div>
                        </td>
                        <td class="text-muted small"><?php echo $fq['sort_order']; ?></td>
                        <td>
                            <a href="?page=sss-duzenle&id=<?php echo $fq['id']; ?>" class="btn btn-sm btn-outline-primary" title="Düzenle"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-outline-danger btn-delete-faq" data-id="<?php echo $fq['id']; ?>" data-name="<?php echo htmlspecialchars(mb_substr($fq['question'], 0, 40, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>" title="Sil"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif ($currentPage === 'sss-ekle' || $currentPage === 'sss-duzenle'): ?>
<?php
    $isFaqEdit = ($currentPage === 'sss-duzenle');
    $faqData = null;
    if ($isFaqEdit) {
        $faqData = getFaq((int)($_GET['id'] ?? 0));
        if (!$faqData) { header('Location: ?page=sss'); exit; }
    }
    $adminPageTitle = $isFaqEdit ? 'Soru Düzenle' : 'Yeni Soru Ekle';
    $faqCategories = getAllFaqCategories();
    if (!$isFaqEdit) {
        $db = getDB();
        $nextFaqSort = (int)$db->query("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM faqs")->fetchColumn();
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><?php echo $adminPageTitle; ?></h5>
        <a href="?page=sss" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Geri</a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            <form method="POST" action="<?php echo ADMIN_URL; ?>/dashboard.php?page=sss">
                <?php echo getCSRFTokenField(); ?>
                <input type="hidden" name="action" value="save_faq">
                <input type="hidden" name="faq_id" value="<?php echo $isFaqEdit ? $faqData['id'] : 0; ?>">

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label small fw-bold">Soru <span class="text-danger">*</span></label>
                        <input type="text" name="question" class="form-control" required maxlength="500" value="<?php echo $isFaqEdit ? htmlspecialchars($faqData['question'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Soru metnini yazın...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Seçiniz</option>
                            <?php foreach ($faqCategories as $fc): ?>
                            <option value="<?php echo $fc['id']; ?>" <?php echo ($isFaqEdit && $faqData['category_id'] == $fc['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($fc['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Cevap <span class="text-danger">*</span></label>
                        <textarea name="answer" class="form-control" rows="5" required placeholder="Cevap metnini yazın..."><?php echo $isFaqEdit ? htmlspecialchars($faqData['answer'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Sıra No</label>
                        <input type="number" name="sort_order" class="form-control" min="0" value="<?php echo $isFaqEdit ? $faqData['sort_order'] : $nextFaqSort; ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" name="show_on_homepage" class="form-check-input" id="faqHomepage" <?php echo ($isFaqEdit && $faqData['show_on_homepage']) ? 'checked' : ''; ?>>
                            <label class="form-check-label small" for="faqHomepage">Anasayfada Göster</label>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="faqActive" <?php echo (!$isFaqEdit || $faqData['is_active']) ? 'checked' : ''; ?>>
                            <label class="form-check-label small" for="faqActive">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> <?php echo $isFaqEdit ? 'Güncelle' : 'Kaydet'; ?></button>
                    <a href="?page=sss" class="btn btn-outline-secondary btn-sm">İptal</a>
                </div>
            </form>
        </div>
    </div>

<?php elseif ($currentPage === 'sss-kategoriler'): ?>
<?php
    $adminPageTitle = 'SSS Kategorileri';
    $faqCategories = getAllFaqCategories();
    if (!empty($_SESSION['admin_message'])) {
        $message = $_SESSION['admin_message'];
        $messageType = $_SESSION['admin_message_type'] ?? 'success';
        unset($_SESSION['admin_message'], $_SESSION['admin_message_type']);
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">SSS Kategorileri</h5>
            <p class="text-muted small mb-0">Toplam <?php echo count($faqCategories); ?> kategori</p>
        </div>
        <div class="d-flex gap-2">
            <a href="?page=sss" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Sorulara Dön</a>
            <a href="?page=sss-kategori-ekle" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Yeni Kategori</a>
        </div>
    </div>

    <?php if ($message): ?>
    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert" style="font-size:13px;">
        <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Kategori Adı</th>
                        <th style="width:120px">Soru Sayısı</th>
                        <th style="width:80px">Durum</th>
                        <th style="width:60px">Sıra</th>
                        <th style="width:120px">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($faqCategories)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Henüz kategori eklenmemiş.</td></tr>
                    <?php else: ?>
                    <?php
                        $db = getDB();
                        $faqCounts = [];
                        $countRows = $db->query("SELECT category_id, COUNT(*) as cnt FROM faqs GROUP BY category_id")->fetchAll();
                        foreach ($countRows as $cr) { $faqCounts[$cr['category_id']] = $cr['cnt']; }
                    ?>
                    <?php foreach ($faqCategories as $fc): ?>
                    <tr>
                        <td class="text-muted small"><?php echo $fc['id']; ?></td>
                        <td>
                            <div class="fw-bold small"><?php echo htmlspecialchars($fc['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="text-muted" style="font-size:11px"><?php echo htmlspecialchars($fc['slug'], ENT_QUOTES, 'UTF-8'); ?></div>
                        </td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary"><?php echo $faqCounts[$fc['id']] ?? 0; ?> soru</span></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-faq-category" type="checkbox" data-id="<?php echo $fc['id']; ?>" <?php echo $fc['is_active'] ? 'checked' : ''; ?>>
                            </div>
                        </td>
                        <td class="text-muted small"><?php echo $fc['sort_order']; ?></td>
                        <td>
                            <a href="?page=sss-kategori-duzenle&id=<?php echo $fc['id']; ?>" class="btn btn-sm btn-outline-primary" title="Düzenle"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-outline-danger btn-delete-faq-cat" data-id="<?php echo $fc['id']; ?>" data-name="<?php echo htmlspecialchars($fc['name'], ENT_QUOTES, 'UTF-8'); ?>" data-count="<?php echo $faqCounts[$fc['id']] ?? 0; ?>" title="Sil"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif ($currentPage === 'sss-kategori-ekle' || $currentPage === 'sss-kategori-duzenle'): ?>
<?php
    $isCatEdit = ($currentPage === 'sss-kategori-duzenle');
    $catData = null;
    if ($isCatEdit) {
        $catData = getFaqCategory((int)($_GET['id'] ?? 0));
        if (!$catData) { header('Location: ?page=sss-kategoriler'); exit; }
    }
    $adminPageTitle = $isCatEdit ? 'Kategori Düzenle' : 'Yeni Kategori';
    if (!$isCatEdit) {
        $db = getDB();
        $nextCatSort = (int)$db->query("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM faq_categories")->fetchColumn();
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><?php echo $adminPageTitle; ?></h5>
        <a href="?page=sss-kategoriler" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Geri</a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            <form method="POST" action="<?php echo ADMIN_URL; ?>/dashboard.php?page=sss-kategoriler">
                <?php echo getCSRFTokenField(); ?>
                <input type="hidden" name="action" value="save_faq_category">
                <input type="hidden" name="category_id" value="<?php echo $isCatEdit ? $catData['id'] : 0; ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Kategori Adı <span class="text-danger">*</span></label>
                        <input type="text" name="cat_name" class="form-control" required maxlength="200" value="<?php echo $isCatEdit ? htmlspecialchars($catData['name'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Örn: Trafik Sigortası" id="catNameInput">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Slug</label>
                        <input type="text" name="cat_slug" class="form-control" maxlength="200" value="<?php echo $isCatEdit ? htmlspecialchars($catData['slug'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Otomatik oluşturulur" id="catSlugInput">
                        <small class="text-muted">Boş bırakılırsa otomatik oluşturulur.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Sıra No</label>
                        <input type="number" name="cat_sort_order" class="form-control" min="0" value="<?php echo $isCatEdit ? $catData['sort_order'] : $nextCatSort; ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" name="cat_is_active" class="form-check-input" id="catActive" <?php echo (!$isCatEdit || $catData['is_active']) ? 'checked' : ''; ?>>
                            <label class="form-check-label small" for="catActive">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> <?php echo $isCatEdit ? 'Güncelle' : 'Kaydet'; ?></button>
                    <a href="?page=sss-kategoriler" class="btn btn-outline-secondary btn-sm">İptal</a>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('catNameInput').addEventListener('input', function() {
        var slug = this.value.toLowerCase()
            .replace(/ı/g,'i').replace(/ö/g,'o').replace(/ü/g,'u').replace(/ş/g,'s').replace(/ç/g,'c').replace(/ğ/g,'g')
            .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
        document.getElementById('catSlugInput').value = slug;
    });
    </script>

<?php elseif ($currentPage === 'blog'): ?>
<?php
    $adminPageTitle = 'Blog Yazıları';
    $blogCatFilter = (int)($_GET['cat'] ?? 0);
    $blogFilters = [];
    if ($blogCatFilter > 0) $blogFilters['category_id'] = $blogCatFilter;
    $allBlogPosts = getAllBlogPosts($blogFilters);
    $blogCategories = getAllBlogCategories();
    if (!empty($_SESSION['admin_message'])) {
        $message = $_SESSION['admin_message'];
        $messageType = $_SESSION['admin_message_type'] ?? 'success';
        unset($_SESSION['admin_message'], $_SESSION['admin_message_type']);
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">Blog Yönetimi</h5>
            <p class="text-muted small mb-0">Toplam <?php echo count($allBlogPosts); ?> yazı</p>
        </div>
        <div class="d-flex gap-2">
            <a href="?page=blog-kategoriler" class="btn btn-outline-secondary btn-sm"><i class="fas fa-tags me-1"></i> Kategoriler</a>
            <a href="?page=blog-ekle" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Yeni Yazı</a>
        </div>
    </div>

    <?php if ($message): ?>
    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert" style="font-size:13px;">
        <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="mb-3">
        <div class="d-flex gap-2 flex-wrap">
            <a href="?page=blog" class="btn btn-sm <?php echo $blogCatFilter === 0 ? 'btn-primary' : 'btn-outline-secondary'; ?>">Tümü</a>
            <?php foreach ($blogCategories as $bc): ?>
            <a href="?page=blog&cat=<?php echo $bc['id']; ?>" class="btn btn-sm <?php echo $blogCatFilter === (int)$bc['id'] ? 'btn-primary' : 'btn-outline-secondary'; ?>"><?php echo htmlspecialchars($bc['name'], ENT_QUOTES, 'UTF-8'); ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Başlık</th>
                        <th style="width:130px">Kategori</th>
                        <th style="width:100px">Tarih</th>
                        <th style="width:70px">Öne Çık</th>
                        <th style="width:70px">Durum</th>
                        <th style="width:70px">Görüntü</th>
                        <th style="width:120px">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($allBlogPosts)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Henüz yazı eklenmemiş.</td></tr>
                    <?php else: ?>
                    <?php foreach ($allBlogPosts as $bp): ?>
                    <tr>
                        <td class="text-muted small"><?php echo $bp['id']; ?></td>
                        <td>
                            <div class="fw-bold small"><?php echo htmlspecialchars(mb_substr($bp['title'], 0, 55, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?><?php echo mb_strlen($bp['title'], 'UTF-8') > 55 ? '...' : ''; ?></div>
                            <div class="text-muted" style="font-size:11px"><?php echo $bp['slug']; ?></div>
                        </td>
                        <td><span class="badge" style="background:<?php echo htmlspecialchars($bp['category_color'] ?? '#6c757d', ENT_QUOTES, 'UTF-8'); ?>; font-size:11px"><?php echo htmlspecialchars($bp['category_name'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td class="text-muted small"><?php echo $bp['published_at'] ? date('d.m.Y H:i', strtotime($bp['published_at'])) : '-'; ?></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-blog-featured" type="checkbox" data-id="<?php echo $bp['id']; ?>" <?php echo $bp['is_featured'] ? 'checked' : ''; ?>>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-blog-active" type="checkbox" data-id="<?php echo $bp['id']; ?>" <?php echo $bp['is_active'] ? 'checked' : ''; ?>>
                            </div>
                        </td>
                        <td class="text-muted small"><?php echo number_format($bp['views']); ?></td>
                        <td>
                            <a href="?page=blog-duzenle&id=<?php echo $bp['id']; ?>" class="btn btn-sm btn-outline-primary" title="Düzenle"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-outline-danger btn-delete-blog" data-id="<?php echo $bp['id']; ?>" data-name="<?php echo htmlspecialchars(mb_substr($bp['title'], 0, 40, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>" title="Sil"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif ($currentPage === 'blog-ekle' || $currentPage === 'blog-duzenle'): ?>
<?php
    $isBlogEdit = ($currentPage === 'blog-duzenle');
    $blogData = null;
    if ($isBlogEdit) {
        $blogData = getBlogPost((int)($_GET['id'] ?? 0));
        if (!$blogData) { header('Location: ?page=blog'); exit; }
    }
    $adminPageTitle = $isBlogEdit ? 'Yazı Düzenle' : 'Yeni Yazı';
    $blogCategories = getAllBlogCategories();
    if (!$isBlogEdit) {
        $db = getDB();
        $nextBlogSort = (int)$db->query("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM blog_posts")->fetchColumn();
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><?php echo $adminPageTitle; ?></h5>
        <a href="?page=blog" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Geri</a>
    </div>

    <form method="POST" action="<?php echo ADMIN_URL; ?>/dashboard.php?page=blog" enctype="multipart/form-data">
        <?php echo getCSRFTokenField(); ?>
        <input type="hidden" name="action" value="save_blog_post">
        <input type="hidden" name="post_id" value="<?php echo $isBlogEdit ? $blogData['id'] : 0; ?>">
        <input type="hidden" name="existing_image" value="<?php echo $isBlogEdit ? htmlspecialchars($blogData['featured_image'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>">

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Başlık <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required maxlength="500" value="<?php echo $isBlogEdit ? htmlspecialchars($blogData['title'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Yazı başlığı..." id="blogTitleInput">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Slug (URL)</label>
                            <input type="text" name="slug" class="form-control" maxlength="500" value="<?php echo $isBlogEdit ? htmlspecialchars($blogData['slug'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Otomatik oluşturulur" id="blogSlugInput">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Özet <span class="text-danger">*</span></label>
                            <textarea name="excerpt" class="form-control" rows="3" required placeholder="Kısa açıklama (kart önizlemesinde gösterilir)..."><?php echo $isBlogEdit ? htmlspecialchars($blogData['excerpt'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">İçerik</label>
                            <textarea name="content" class="form-control" rows="15" placeholder="HTML destekli içerik yazabilirsiniz..."><?php echo $isBlogEdit ? htmlspecialchars($blogData['content'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="fas fa-search me-1"></i> SEO Ayarları</h6>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Meta Başlık</label>
                            <input type="text" name="meta_title" class="form-control" maxlength="500" value="<?php echo $isBlogEdit ? htmlspecialchars($blogData['meta_title'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Boş bırakılırsa başlık kullanılır">
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold">Meta Açıklama</label>
                            <textarea name="meta_description" class="form-control" rows="2" placeholder="Boş bırakılırsa özet kullanılır"><?php echo $isBlogEdit ? htmlspecialchars($blogData['meta_description'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Yayınlama</h6>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Kategori</label>
                            <select name="category_id" class="form-select">
                                <option value="">Kategorisiz</option>
                                <?php foreach ($blogCategories as $bc): ?>
                                <option value="<?php echo $bc['id']; ?>" <?php echo ($isBlogEdit && $blogData['category_id'] == $bc['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($bc['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Yayın Tarihi</label>
                            <input type="datetime-local" name="published_at" class="form-control" value="<?php echo $isBlogEdit ? date('Y-m-d\TH:i', strtotime($blogData['published_at'])) : date('Y-m-d\TH:i'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Okuma Süresi (dk)</label>
                            <input type="number" name="reading_time" class="form-control" min="1" max="60" value="<?php echo $isBlogEdit ? $blogData['reading_time'] : 5; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Sıra No</label>
                            <input type="number" name="sort_order" class="form-control" min="0" value="<?php echo $isBlogEdit ? $blogData['sort_order'] : $nextBlogSort; ?>">
                        </div>
                        <div class="mb-2">
                            <div class="form-check">
                                <input type="checkbox" name="is_featured" class="form-check-input" id="blogFeatured" <?php echo ($isBlogEdit && $blogData['is_featured']) ? 'checked' : ''; ?>>
                                <label class="form-check-label small" for="blogFeatured">Öne Çıkar</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" id="blogActive" <?php echo (!$isBlogEdit || $blogData['is_active']) ? 'checked' : ''; ?>>
                                <label class="form-check-label small" for="blogActive">Aktif (Yayında)</label>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill"><i class="fas fa-save me-1"></i> <?php echo $isBlogEdit ? 'Güncelle' : 'Yayınla'; ?></button>
                            <a href="?page=blog" class="btn btn-outline-secondary btn-sm">İptal</a>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Görsel</h6>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Öne Çıkan Görsel</label>
                            <input type="file" name="featured_image" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted">Max 5MB. JPG, PNG, WebP</small>
                        </div>
                        <?php if ($isBlogEdit && $blogData['featured_image']): ?>
                        <div class="mb-3">
                            <img src="<?php echo SITE_URL . '/' . htmlspecialchars($blogData['featured_image'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded" style="max-height:150px">
                        </div>
                        <?php endif; ?>
                        <hr>
                        <h6 class="fw-bold mb-3">veya İkon</h6>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">İkon Sınıfı</label>
                            <input type="text" name="icon" class="form-control form-control-sm" value="<?php echo $isBlogEdit ? htmlspecialchars($blogData['icon'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="fas fa-file-alt">
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold">İkon Arkaplanı (CSS)</label>
                            <input type="text" name="icon_bg" class="form-control form-control-sm" value="<?php echo $isBlogEdit ? htmlspecialchars($blogData['icon_bg'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="linear-gradient(135deg, #007bff, #0056b3)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
    document.getElementById('blogTitleInput').addEventListener('input', function() {
        var slug = this.value.toLowerCase()
            .replace(/ı/g,'i').replace(/ö/g,'o').replace(/ü/g,'u').replace(/ş/g,'s').replace(/ç/g,'c').replace(/ğ/g,'g')
            .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
        document.getElementById('blogSlugInput').value = slug;
    });
    </script>

<?php elseif ($currentPage === 'blog-kategoriler'): ?>
<?php
    $adminPageTitle = 'Blog Kategorileri';
    $blogCategories = getAllBlogCategories();
    if (!empty($_SESSION['admin_message'])) {
        $message = $_SESSION['admin_message'];
        $messageType = $_SESSION['admin_message_type'] ?? 'success';
        unset($_SESSION['admin_message'], $_SESSION['admin_message_type']);
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">Blog Kategorileri</h5>
            <p class="text-muted small mb-0">Toplam <?php echo count($blogCategories); ?> kategori</p>
        </div>
        <div class="d-flex gap-2">
            <a href="?page=blog" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Yazılara Dön</a>
            <a href="?page=blog-kategori-ekle" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Yeni Kategori</a>
        </div>
    </div>

    <?php if ($message): ?>
    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert" style="font-size:13px;">
        <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Kategori</th>
                        <th style="width:100px">İkon</th>
                        <th style="width:80px">Renk</th>
                        <th style="width:100px">Yazı Sayısı</th>
                        <th style="width:80px">Durum</th>
                        <th style="width:60px">Sıra</th>
                        <th style="width:120px">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($blogCategories)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Henüz kategori eklenmemiş.</td></tr>
                    <?php else: ?>
                    <?php
                        $db = getDB();
                        $blogCatCounts = [];
                        $countRows = $db->query("SELECT category_id, COUNT(*) as cnt FROM blog_posts GROUP BY category_id")->fetchAll();
                        foreach ($countRows as $cr) { $blogCatCounts[$cr['category_id']] = $cr['cnt']; }
                    ?>
                    <?php foreach ($blogCategories as $bc): ?>
                    <tr>
                        <td class="text-muted small"><?php echo $bc['id']; ?></td>
                        <td>
                            <div class="fw-bold small"><?php echo htmlspecialchars($bc['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="text-muted" style="font-size:11px"><?php echo htmlspecialchars($bc['slug'], ENT_QUOTES, 'UTF-8'); ?></div>
                        </td>
                        <td><i class="<?php echo htmlspecialchars($bc['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></i> <span class="text-muted small"><?php echo htmlspecialchars($bc['icon'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td><span style="display:inline-block;width:24px;height:24px;border-radius:6px;background:<?php echo htmlspecialchars($bc['color'], ENT_QUOTES, 'UTF-8'); ?>"></span></td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary"><?php echo $blogCatCounts[$bc['id']] ?? 0; ?> yazı</span></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-blog-cat" type="checkbox" data-id="<?php echo $bc['id']; ?>" <?php echo $bc['is_active'] ? 'checked' : ''; ?>>
                            </div>
                        </td>
                        <td class="text-muted small"><?php echo $bc['sort_order']; ?></td>
                        <td>
                            <a href="?page=blog-kategori-duzenle&id=<?php echo $bc['id']; ?>" class="btn btn-sm btn-outline-primary" title="Düzenle"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-outline-danger btn-delete-blog-cat" data-id="<?php echo $bc['id']; ?>" data-name="<?php echo htmlspecialchars($bc['name'], ENT_QUOTES, 'UTF-8'); ?>" data-count="<?php echo $blogCatCounts[$bc['id']] ?? 0; ?>" title="Sil"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif ($currentPage === 'blog-kategori-ekle' || $currentPage === 'blog-kategori-duzenle'): ?>
<?php
    $isBlogCatEdit = ($currentPage === 'blog-kategori-duzenle');
    $blogCatData = null;
    if ($isBlogCatEdit) {
        $blogCatData = getBlogCategory((int)($_GET['id'] ?? 0));
        if (!$blogCatData) { header('Location: ?page=blog-kategoriler'); exit; }
    }
    $adminPageTitle = $isBlogCatEdit ? 'Blog Kategorisi Düzenle' : 'Yeni Blog Kategorisi';
    if (!$isBlogCatEdit) {
        $db = getDB();
        $nextBlogCatSort = (int)$db->query("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM blog_categories")->fetchColumn();
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><?php echo $adminPageTitle; ?></h5>
        <a href="?page=blog-kategoriler" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Geri</a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            <form method="POST" action="<?php echo ADMIN_URL; ?>/dashboard.php?page=blog-kategoriler">
                <?php echo getCSRFTokenField(); ?>
                <input type="hidden" name="action" value="save_blog_category">
                <input type="hidden" name="category_id" value="<?php echo $isBlogCatEdit ? $blogCatData['id'] : 0; ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Kategori Adı <span class="text-danger">*</span></label>
                        <input type="text" name="cat_name" class="form-control" required maxlength="200" value="<?php echo $isBlogCatEdit ? htmlspecialchars($blogCatData['name'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Örn: Trafik Sigortası" id="blogCatNameInput">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Slug</label>
                        <input type="text" name="cat_slug" class="form-control" maxlength="200" value="<?php echo $isBlogCatEdit ? htmlspecialchars($blogCatData['slug'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Otomatik oluşturulur" id="blogCatSlugInput">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">İkon Sınıfı</label>
                        <input type="text" name="cat_icon" class="form-control" value="<?php echo $isBlogCatEdit ? htmlspecialchars($blogCatData['icon'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="fas fa-file-alt">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Renk</label>
                        <input type="color" name="cat_color" class="form-control form-control-color" value="<?php echo $isBlogCatEdit ? htmlspecialchars($blogCatData['color'], ENT_QUOTES, 'UTF-8') : '#0066cc'; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Sıra No</label>
                        <input type="number" name="cat_sort_order" class="form-control" min="0" value="<?php echo $isBlogCatEdit ? $blogCatData['sort_order'] : $nextBlogCatSort; ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" name="cat_is_active" class="form-check-input" id="blogCatActive" <?php echo (!$isBlogCatEdit || $blogCatData['is_active']) ? 'checked' : ''; ?>>
                            <label class="form-check-label small" for="blogCatActive">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> <?php echo $isBlogCatEdit ? 'Güncelle' : 'Kaydet'; ?></button>
                    <a href="?page=blog-kategoriler" class="btn btn-outline-secondary btn-sm">İptal</a>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('blogCatNameInput').addEventListener('input', function() {
        var slug = this.value.toLowerCase()
            .replace(/ı/g,'i').replace(/ö/g,'o').replace(/ü/g,'u').replace(/ş/g,'s').replace(/ç/g,'c').replace(/ğ/g,'g')
            .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
        document.getElementById('blogCatSlugInput').value = slug;
    });
    </script>

<?php elseif ($currentPage === 'haberler'): ?>
<?php
    // Otomatik güncelleme kontrolü
    $newsIsStale = isNewsStale(120); // 2 saat
    $lastFetchTime = getLastNewsFetchTime();
    $extNews = getExternalNews([]);
    $extNewsCount = count($extNews);
?>
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="fas fa-rss text-danger me-2"></i>Sektör Haberleri (Sigortamedya)</h5>
                    <small class="text-muted">
                        Sigortamedya.com.tr'den çekilen haberler • Toplam <?php echo $extNewsCount; ?> haber
                        <?php if ($lastFetchTime): ?>
                            • <i class="fas fa-clock me-1"></i>Son güncelleme: <?php echo date('d.m.Y H:i', strtotime($lastFetchTime)); ?>
                        <?php endif; ?>
                    </small>
                    <div class="mt-1">
                        <span class="badge bg-<?php echo $newsIsStale ? 'warning text-dark' : 'success'; ?>" style="font-size:11px;">
                            <i class="fas fa-<?php echo $newsIsStale ? 'exclamation-triangle' : 'check-circle'; ?> me-1"></i>
                            <?php echo $newsIsStale ? 'Haberler güncel değil – otomatik güncelleniyor...' : 'Haberler güncel (2 saatlik aralık)'; ?>
                        </span>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm" id="btnRefreshNews">
                    <i class="fas fa-sync-alt me-1"></i> Haberleri Güncelle
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Başlık</th>
                            <th style="width:130px">Yayın Tarihi</th>
                            <th style="width:90px">Durum</th>
                            <th style="width:110px">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($extNews)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">Henüz haber yok. "Haberleri Güncelle" butonuna tıklayın.</td></tr>
                        <?php else: ?>
                        <?php foreach ($extNews as $en): ?>
                        <tr>
                            <td class="text-muted"><?php echo $en['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($en['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($en['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="" style="width:48px;height:36px;object-fit:cover;border-radius:6px;" loading="lazy">
                                    <?php else: ?>
                                    <div style="width:48px;height:36px;background:#f8d7da;border-radius:6px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-newspaper text-danger" style="font-size:14px;"></i></div>
                                    <?php endif; ?>
                                    <div>
                                        <a href="<?php echo SITE_URL; ?>/haber.php?haber=<?php echo urlencode($en['slug']); ?>" target="_blank" class="fw-semibold text-dark text-decoration-none" style="font-size:13px;">
                                            <?php echo htmlspecialchars(mb_substr($en['title'], 0, 80, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?><?php echo mb_strlen($en['title'], 'UTF-8') > 80 ? '...' : ''; ?>
                                        </a>
                                        <div><small class="text-muted"><?php echo htmlspecialchars($en['author'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small></div>
                                    </div>
                                </div>
                            </td>
                            <td><small><?php echo $en['published_at'] ? date('d.m.Y H:i', strtotime($en['published_at'])) : '-'; ?></small></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-ext-news" type="checkbox" data-id="<?php echo $en['id']; ?>" <?php echo $en['is_active'] ? 'checked' : ''; ?>>
                                </div>
                            </td>
                            <td>
                                <a href="<?php echo htmlspecialchars($en['source_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="btn btn-outline-secondary btn-sm" title="Kaynağa Git"><i class="fas fa-external-link-alt"></i></a>
                                <button class="btn btn-outline-danger btn-sm btn-delete-ext-news" data-id="<?php echo $en['id']; ?>" data-name="<?php echo htmlspecialchars(mb_substr($en['title'], 0, 50, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php elseif ($currentPage === 'subeler'): ?>
<?php $adminBranches = getAllBranches(); ?>
    <div class="card border-0 shadow-sm" style="border-radius:12px;">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="fas fa-store text-primary me-2"></i>Şubeler</h5>
                    <small class="text-muted">Toplam <?php echo count($adminBranches); ?> şube</small>
                </div>
                <a href="?page=sube-ekle" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Yeni Şube</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:13px;">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Şube Adı</th>
                        <th>Şehir</th>
                        <th>Telefon</th>
                        <th style="width:80px">Tür</th>
                        <th style="width:80px">Durum</th>
                        <th style="width:100px">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($adminBranches)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">Henüz şube eklenmemiş.</td></tr>
                    <?php else: ?>
                    <?php foreach ($adminBranches as $br): ?>
                    <tr>
                        <td class="text-muted"><?php echo $br['id']; ?></td>
                        <td class="fw-semibold"><?php echo htmlspecialchars($br['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($br['city'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><small><?php echo htmlspecialchars($br['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small></td>
                        <td>
                            <?php if ($br['is_headquarters']): ?>
                            <span class="badge bg-primary" style="font-size:11px;">Merkez</span>
                            <?php else: ?>
                            <span class="badge bg-secondary" style="font-size:11px;">Şube</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-branch" type="checkbox" data-id="<?php echo $br['id']; ?>" <?php echo $br['is_active'] ? 'checked' : ''; ?>>
                            </div>
                        </td>
                        <td>
                            <a href="?page=sube-duzenle&id=<?php echo $br['id']; ?>" class="btn btn-outline-primary btn-sm" title="Düzenle"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-outline-danger btn-sm btn-delete-branch" data-id="<?php echo $br['id']; ?>" data-name="<?php echo htmlspecialchars($br['name'], ENT_QUOTES, 'UTF-8'); ?>" title="Sil"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<!-- Şube Silme Modal -->
<div class="modal fade" id="deleteBranchModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Şube Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong id="deleteBranchName"></strong> şubesini silmek istediğinize emin misiniz?</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBranch"><i class="fas fa-trash me-1"></i>Sil</button>
            </div>
        </div>
    </div>
</div>
<script>
var _branchDeleteId = 0;
document.querySelectorAll('.btn-delete-branch').forEach(function(btn) {
    btn.addEventListener('click', function() {
        _branchDeleteId = this.dataset.id;
        document.getElementById('deleteBranchName').textContent = this.dataset.name;
        new bootstrap.Modal(document.getElementById('deleteBranchModal')).show();
    });
});
document.getElementById('confirmDeleteBranch').addEventListener('click', function() {
    if (_branchDeleteId) {
        adminAjax('delete_branch', {branch_id: _branchDeleteId}, function(data) {
            if (data.success) {
                showAdminToast('success', 'Başarılı', data.message);
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                showAdminToast('danger', 'Hata', data.message);
            }
        });
    }
});
document.querySelectorAll('.toggle-branch').forEach(function(el) {
    el.addEventListener('change', function() {
        var id = this.dataset.id, val = this.checked ? 1 : 0;
        this.disabled = true;
        var checkbox = this;
        adminAjax('toggle_branch', {branch_id: id, is_active: val}, function(data) {
            checkbox.disabled = false;
            if (data.success) {
                flashRow(checkbox.closest('tr'), val ? '#d1e7dd' : '#f8d7da');
                showAdminToast('success', 'Başarılı', data.message);
            } else {
                checkbox.checked = !checkbox.checked;
                showAdminToast('danger', 'Hata', data.message || 'Hata oluştu.');
            }
        });
    });
});
</script>

<?php elseif ($currentPage === 'sube-ekle' || $currentPage === 'sube-duzenle'): ?>
<?php
    $editBranch = null;
    $isEditBranch = ($currentPage === 'sube-duzenle');
    if ($isEditBranch) {
        $editBranch = getBranch((int)($_GET['id'] ?? 0));
        if (!$editBranch) { header('Location: ?page=subeler'); exit; }
    }
    $nextBranchSort = 0;
    if (!$isEditBranch) {
        try { $db = getDB(); $nextBranchSort = (int)$db->query("SELECT COALESCE(MAX(sort_order),0)+1 FROM branches")->fetchColumn(); } catch(Exception $e){}
    }
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><?php echo $isEditBranch ? 'Şube Düzenle' : 'Yeni Şube Ekle'; ?></h5>
        <a href="?page=subeler" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Geri</a>
    </div>
    <div class="card border-0 shadow-sm" style="border-radius:12px;">
        <div class="card-body p-4">
            <form method="POST" action="<?php echo ADMIN_URL; ?>/dashboard.php?page=subeler">
                <?php echo getCSRFTokenField(); ?>
                <input type="hidden" name="action" value="save_branch">
                <?php if ($isEditBranch): ?>
                <input type="hidden" name="branch_id" value="<?php echo $editBranch['id']; ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Şube Adı <span class="text-danger">*</span></label>
                        <input type="text" name="branch_name" class="form-control" required value="<?php echo $isEditBranch ? htmlspecialchars($editBranch['name'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Örn: Şanlıurfa Şubesi">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Şehir</label>
                        <input type="text" name="branch_city" class="form-control" value="<?php echo $isEditBranch ? htmlspecialchars($editBranch['city'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Örn: Şanlıurfa">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Adres</label>
                        <textarea name="branch_address" class="form-control" rows="2" placeholder="Tam adres bilgisi"><?php echo $isEditBranch ? htmlspecialchars($editBranch['address'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Telefon</label>
                        <input type="text" name="branch_phone" class="form-control" value="<?php echo $isEditBranch ? htmlspecialchars($editBranch['phone'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="0XXX XXX XX XX">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Telefon (Alternatif)</label>
                        <input type="text" name="branch_phone_alt" class="form-control" value="<?php echo $isEditBranch ? htmlspecialchars($editBranch['phone_alt'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="0XXX XXX XX XX">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">E-posta</label>
                        <input type="email" name="branch_email" class="form-control" value="<?php echo $isEditBranch ? htmlspecialchars($editBranch['email'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="sube@emresigorta.net">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Google Maps Embed URL</label>
                        <input type="text" name="branch_maps_embed" id="branchMapsEmbed" class="form-control" value="<?php echo $isEditBranch ? htmlspecialchars($editBranch['maps_embed'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="https://www.google.com/maps/embed?pb=...">
                        <small class="text-muted">Google Maps'ten "Paylaş > Haritayı yerleştir" ile alınan iframe src URL'i</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Google Maps Yol Tarifi Linki <span class="badge bg-info text-white" style="font-size:10px; font-weight:500;">Otomatik oluşturulur</span></label>
                        <input type="url" name="branch_maps_link" id="branchMapsLink" class="form-control" value="<?php echo $isEditBranch ? htmlspecialchars($editBranch['maps_link'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="https://maps.google.com/?q=...">
                        <small class="text-muted">Embed URL girildiğinde otomatik oluşturulur. İsterseniz manuel de düzenleyebilirsiniz.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Çalışma Saatleri</label>
                        <input type="text" name="branch_working_hours" class="form-control" value="<?php echo $isEditBranch ? htmlspecialchars($editBranch['working_hours'] ?? '', ENT_QUOTES, 'UTF-8') : 'Pazartesi - Cuma: 09:00 - 18:00'; ?>" placeholder="Pazartesi - Cuma: 09:00 - 18:00">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Sıralama</label>
                        <input type="number" name="branch_sort_order" class="form-control" value="<?php echo $isEditBranch ? $editBranch['sort_order'] : $nextBranchSort; ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold d-block">Merkez mi?</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="branch_is_headquarters" id="branchHQ" <?php echo ($isEditBranch && $editBranch['is_headquarters']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="branchHQ">Genel Merkez</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold d-block">Durum</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="branch_is_active" id="branchActive" <?php echo (!$isEditBranch || $editBranch['is_active']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="branchActive">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Kaydet</button>
                    <a href="?page=subeler" class="btn btn-outline-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    (function() {
        var embedInput = document.getElementById('branchMapsEmbed');
        var linkInput = document.getElementById('branchMapsLink');
        if (!embedInput || !linkInput) return;

        function generateDirectionsLink(embedUrl) {
            if (!embedUrl) return '';
            // Extract coordinates from embed URL patterns
            var lat = null, lng = null;
            // Pattern: !2d{lng}!3d{lat}
            var m = embedUrl.match(/!2d(-?[\d.]+)!3d(-?[\d.]+)/);
            if (m) { lng = m[1]; lat = m[2]; }
            // Pattern: @{lat},{lng}
            if (!lat) { m = embedUrl.match(/@(-?[\d.]+),(-?[\d.]+)/); if (m) { lat = m[1]; lng = m[2]; } }
            // Pattern: ll={lat},{lng}
            if (!lat) { m = embedUrl.match(/ll=(-?[\d.]+),(-?[\d.]+)/); if (m) { lat = m[1]; lng = m[2]; } }
            // Pattern: q={lat},{lng}
            if (!lat) { m = embedUrl.match(/[?&]q=(-?[\d.]+),(-?[\d.]+)/); if (m) { lat = m[1]; lng = m[2]; } }
            // Pattern: place/{name}/@{lat},{lng}
            if (!lat) { m = embedUrl.match(/place\/[^/]+\/@(-?[\d.]+),(-?[\d.]+)/); if (m) { lat = m[1]; lng = m[2]; } }
            if (lat && lng) {
                return 'https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng;
            }
            // Fallback: extract place name from /place/{name}/
            m = embedUrl.match(/place\/([^/@]+)/);
            if (m) {
                return 'https://www.google.com/maps/dir/?api=1&destination=' + encodeURIComponent(decodeURIComponent(m[1].replace(/\+/g, ' ')));
            }
            // Fallback: extract q= parameter (place query)
            m = embedUrl.match(/[?&]q=([^&]+)/);
            if (m) {
                return 'https://www.google.com/maps/dir/?api=1&destination=' + m[1];
            }
            return '';
        }

        embedInput.addEventListener('input', function() {
            var link = generateDirectionsLink(this.value.trim());
            if (link && !linkInput.dataset.manual) {
                linkInput.value = link;
                linkInput.style.transition = 'background 0.3s';
                linkInput.style.background = '#e8f5e9';
                setTimeout(function() { linkInput.style.background = ''; }, 1500);
            }
        });
        // Mark manual edit so auto-fill doesn't override
        linkInput.addEventListener('input', function() {
            if (this.value.trim()) { this.dataset.manual = '1'; }
            else { delete this.dataset.manual; }
        });
        // If embed has value but link is empty on load (edit mode), generate it
        if (embedInput.value.trim() && !linkInput.value.trim()) {
            var link = generateDirectionsLink(embedInput.value.trim());
            if (link) linkInput.value = link;
        }
    })();
    </script>

<?php elseif ($currentPage === 'iletisim-ayarlari'): ?>
    <h6 class="mb-3 fw-bold"><i class="fas fa-address-card me-2 text-primary"></i>İletişim Bilgileri Yönetimi</h6>

    <form method="post" action="" id="contactSettingsForm">
        <?php echo getCSRFTokenField(); ?>
        <input type="hidden" name="action" value="save_settings">

        <!-- Telefon Bilgileri -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-phone-alt me-2 text-success"></i>Telefon Bilgileri</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php
                    $phoneFields = ['site_phone', 'site_phone_raw', 'site_phone_alt', 'site_phone_short'];
                    foreach ($phoneFields as $key):
                        $s = $settingGroups['iletisim'][$key] ?? null;
                        if (!$s) continue;
                    ?>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 13px;"><?php echo htmlspecialchars($s['setting_label'], ENT_QUOTES, 'UTF-8'); ?></label>
                        <input type="text" name="settings[<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>]" class="form-control form-control-sm" value="<?php echo htmlspecialchars($s['setting_value'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- E-posta Bilgileri -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-envelope me-2 text-info"></i>E-posta Bilgileri</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php
                    $emailFields = ['site_email', 'site_email_alt'];
                    foreach ($emailFields as $key):
                        $s = $settingGroups['iletisim'][$key] ?? null;
                        if (!$s) continue;
                    ?>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size: 13px;"><?php echo htmlspecialchars($s['setting_label'], ENT_QUOTES, 'UTF-8'); ?></label>
                        <input type="text" name="settings[<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>]" class="form-control form-control-sm" value="<?php echo htmlspecialchars($s['setting_value'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Adres & Çalışma Saatleri -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Adres & Çalışma Bilgileri</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php
                    $adresFields = ['site_address', 'working_hours', 'whatsapp_message'];
                    foreach ($adresFields as $key):
                        $s = $settingGroups['iletisim'][$key] ?? null;
                        if (!$s) continue;
                    ?>
                    <div class="col-12">
                        <label class="form-label fw-semibold" style="font-size: 13px;"><?php echo htmlspecialchars($s['setting_label'], ENT_QUOTES, 'UTF-8'); ?></label>
                        <textarea name="settings[<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>]" class="form-control form-control-sm" rows="2"><?php echo htmlspecialchars($s['setting_value'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Google Maps -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-map-marked-alt me-2 text-warning"></i>Harita Ayarları</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="previewMapContact()"><i class="fas fa-eye me-1"></i>Ön İzleme</button>
            </div>
            <div class="card-body">
                <?php $mapUrlC = $settingGroups['iletisim']['google_maps_embed']['setting_value'] ?? ''; ?>
                <label class="form-label fw-semibold" style="font-size: 13px;">Google Maps Embed URL</label>
                <textarea name="settings[google_maps_embed]" id="mapEmbedUrlContact" class="form-control form-control-sm" rows="3" placeholder="https://www.google.com/maps/embed?pb=..."><?php echo htmlspecialchars($mapUrlC, ENT_QUOTES, 'UTF-8'); ?></textarea>
                <small class="text-muted">Google Maps'ten "Paylaş → Harita yerleştir" seçeneğinden iframe src URL'sini kopyalayın.</small>
                <div id="mapPreviewContainerContact" class="mt-3" style="border-radius: 12px; overflow: hidden; display:none;"></div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> İletişim Bilgilerini Kaydet</button>
    </form>

    <!-- Şubeler Kısayol -->
    <div class="card border-0 shadow-sm mt-4" style="border-radius: 12px;">
        <div class="card-body d-flex align-items-center justify-content-between py-3 px-4">
            <div>
                <h6 class="mb-1 fw-bold"><i class="fas fa-store me-2 text-primary"></i>Şube Yönetimi</h6>
                <small class="text-muted">Şube bilgilerini ekleyin, düzenleyin veya silin.</small>
            </div>
            <a href="?page=subeler" class="btn btn-outline-primary btn-sm"><i class="fas fa-arrow-right me-1"></i>Şubelere Git</a>
        </div>
    </div>

    <!-- Sosyal Medya Kısayol -->
    <div class="card border-0 shadow-sm mt-3" style="border-radius: 12px;">
        <div class="card-body d-flex align-items-center justify-content-between py-3 px-4">
            <div>
                <h6 class="mb-1 fw-bold"><i class="fas fa-share-alt me-2 text-info"></i>Sosyal Medya</h6>
                <small class="text-muted">Sosyal medya hesaplarını yönetin.</small>
            </div>
            <a href="?page=sosyal-medya" class="btn btn-outline-info btn-sm"><i class="fas fa-arrow-right me-1"></i>Sosyal Medyaya Git</a>
        </div>
    </div>

    <!-- Harita Ön İzleme Modal -->
    <div class="modal fade" id="mapPreviewModalContact" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold"><i class="fas fa-map-marked-alt me-2 text-danger"></i>Harita Ön İzleme</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div id="mapModalPreview" style="border-radius: 12px; overflow: hidden; background: #f0f0f0; min-height: 400px; display:flex; align-items:center; justify-content:center;">
                        <span class="text-muted">Harita URL'si giriniz</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function previewMapContact() {
        var url = document.getElementById('mapEmbedUrlContact').value.trim();
        var container = document.getElementById('mapModalPreview');
        if (url && url.indexOf('google.com/maps') !== -1) {
            container.innerHTML = '<iframe src="' + url.replace(/"/g, '&quot;') + '" width="100%" height="400" style="border:0; display:block;" allowfullscreen="" loading="eager"></iframe>';
        } else {
            container.innerHTML = '<div class="text-center p-5"><i class="fas fa-exclamation-triangle text-warning fa-2x mb-2"></i><br><span class="text-muted">Geçerli bir Google Maps Embed URL\'si giriniz.</span></div>';
        }
        new bootstrap.Modal(document.getElementById('mapPreviewModalContact')).show();
    }
    // Form AJAX submit
    document.getElementById('contactSettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        adminAjax('save_settings', formData, function(data) {
            if (data.success) {
                showAdminToast('success', 'Başarılı', data.message);
            } else {
                showAdminToast('danger', 'Hata', data.message || 'Hata oluştu.');
            }
        });
    });
    </script>

<?php elseif ($currentPage === 'kampanyalar'): ?>
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="fas fa-bullhorn text-primary me-2"></i>Kampanyalar</h5>
                    <small class="text-muted">Toplam <?php echo count($allCampaigns); ?> kampanya</small>
                </div>
                <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=kampanya-ekle" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Yeni Kampanya
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Kampanya</th>
                            <th style="width:100px">İndirim</th>
                            <th style="width:120px">Başlangıç</th>
                            <th style="width:120px">Bitiş</th>
                            <th style="width:100px">Süre</th>
                            <th style="width:80px">Aktif</th>
                            <th style="width:120px">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($allCampaigns)): ?>
                        <tr><td colspan="8" class="text-center py-4 text-muted">Henüz kampanya eklenmemiş.</td></tr>
                        <?php else: ?>
                        <?php foreach ($allCampaigns as $ci => $camp): ?>
                        <?php
                            $today = new DateTime();
                            $endDate = new DateTime($camp['end_date']);
                            $startDate = new DateTime($camp['start_date']);
                            $isExpired = $endDate < $today;
                            $isUpcoming = $startDate > $today;
                            $daysLeft = $isExpired ? 0 : (int)$today->diff($endDate)->days;
                        ?>
                        <tr class="<?php echo $isExpired ? 'table-secondary' : ''; ?>">
                            <td><?php echo $camp['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($camp['image'])): ?>
                                    <img src="<?php echo SITE_URL . '/' . htmlspecialchars($camp['image'], ENT_QUOTES, 'UTF-8'); ?>" style="width:40px;height:40px;border-radius:8px;object-fit:cover;">
                                    <?php else: ?>
                                    <div style="width:40px;height:40px;border-radius:8px;background:<?php echo htmlspecialchars($camp['bg_color'], ENT_QUOTES, 'UTF-8'); ?>;display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;">
                                        <i class="<?php echo htmlspecialchars($camp['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <strong><?php echo htmlspecialchars($camp['title'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <?php if (!empty($camp['is_popular'])): ?><span class="badge ms-1" style="font-size:10px;background:#f97316;"><i class="fas fa-fire"></i> Popüler</span><?php endif; ?>
                                        <?php if ($isExpired): ?><span class="badge bg-danger ms-1" style="font-size:10px;">Süresi Doldu</span><?php endif; ?>
                                        <?php if ($isUpcoming): ?><span class="badge bg-info ms-1" style="font-size:10px;">Yakında</span><?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-warning text-dark"><?php echo htmlspecialchars($camp['discount_text'] ?: '-', ENT_QUOTES, 'UTF-8'); ?></span></td>
                            <td><small><?php echo date('d.m.Y', strtotime($camp['start_date'])); ?></small></td>
                            <td><small><?php echo date('d.m.Y', strtotime($camp['end_date'])); ?></small></td>
                            <td>
                                <?php if ($isExpired): ?>
                                    <span class="text-danger fw-bold" style="font-size:12px;">Bitti</span>
                                <?php else: ?>
                                    <span class="text-success fw-bold" style="font-size:12px;"><?php echo $daysLeft; ?> gün</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-campaign" type="checkbox" data-id="<?php echo $camp['id']; ?>" <?php echo $camp['is_active'] ? 'checked' : ''; ?>>
                                </div>
                            </td>
                            <td>
                                <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=kampanya-duzenle&id=<?php echo $camp['id']; ?>" class="btn btn-outline-primary btn-sm" title="Düzenle"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-outline-danger btn-sm btn-delete-campaign" data-id="<?php echo $camp['id']; ?>" data-name="<?php echo htmlspecialchars($camp['title'], ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php elseif ($currentPage === 'kampanya-ekle' || $currentPage === 'kampanya-duzenle'): ?>
<?php $isCampEdit = ($currentPage === 'kampanya-duzenle' && $editCampaign); ?>
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-<?php echo $isCampEdit ? 'edit' : 'plus-circle'; ?> text-primary me-2"></i>
                    <?php echo $isCampEdit ? 'Kampanya Düzenle' : 'Yeni Kampanya Ekle'; ?>
                </h5>
                <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=kampanyalar" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Geri
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <form method="POST" enctype="multipart/form-data">
                <?php echo getCSRFTokenField(); ?>
                <input type="hidden" name="action" value="save_campaign">
                <input type="hidden" name="campaign_id" value="<?php echo $isCampEdit ? $editCampaign['id'] : 0; ?>">

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label small fw-bold">Kampanya Başlığı <span class="text-danger">*</span></label>
                        <input type="text" name="campaign_title" class="form-control" required value="<?php echo $isCampEdit ? htmlspecialchars($editCampaign['title'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Örn: Trafik Sigortası %25 İndirim">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">İndirim Etiketi</label>
                        <input type="text" name="campaign_discount_text" class="form-control" value="<?php echo $isCampEdit ? htmlspecialchars($editCampaign['discount_text'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Örn: %25 İNDİRİM">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Kampanya Kategorisi / Branş</label>
                        <input type="text" name="campaign_category" class="form-control" value="<?php echo $isCampEdit ? htmlspecialchars($editCampaign['category'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Örn: Trafik, Sağlık, Kasko, TSS" list="campCategoryList">
                        <datalist id="campCategoryList">
                            <option value="Trafik">
                            <option value="Kasko">
                            <option value="Sağlık">
                            <option value="TSS">
                            <option value="DASK">
                            <option value="Konut">
                            <option value="Seyahat">
                            <option value="Evcil Hayvan">
                            <option value="Ferdi Kaza">
                        </datalist>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-bold">Kısa Açıklama</label>
                        <input type="text" name="campaign_short_description" class="form-control" maxlength="500" value="<?php echo $isCampEdit ? htmlspecialchars($editCampaign['short_description'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Kart üzerinde görünecek kısa açıklama">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-bold">Detaylı Açıklama</label>
                        <textarea name="campaign_description" class="form-control" rows="4" placeholder="Kampanya detayları..."><?php echo $isCampEdit ? htmlspecialchars($editCampaign['description'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-bold">Özellikler <small class="text-muted">(her satıra bir özellik)</small></label>
                        <textarea name="campaign_features" class="form-control" rows="3" placeholder="Anında poliçe çıkarma&#10;Taksit imkanı&#10;7/24 yol yardım"><?php echo $isCampEdit ? htmlspecialchars($editCampaign['features'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Başlangıç Tarihi <span class="text-danger">*</span></label>
                        <input type="date" name="campaign_start_date" class="form-control" required value="<?php echo $isCampEdit ? $editCampaign['start_date'] : date('Y-m-d'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Bitiş Tarihi <span class="text-danger">*</span></label>
                        <input type="date" name="campaign_end_date" class="form-control" required value="<?php echo $isCampEdit ? $editCampaign['end_date'] : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Sıra No</label>
                        <input type="number" name="campaign_sort_order" class="form-control bg-light" min="0" readonly value="<?php echo $isCampEdit ? $editCampaign['sort_order'] : $nextCampSortOrder; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">İkon</label>
                        <div class="input-group">
                            <span class="input-group-text" id="campIconPreview" style="width:42px;text-align:center;"><i class="<?php echo $isCampEdit ? htmlspecialchars($editCampaign['icon'], ENT_QUOTES, 'UTF-8') : 'fas fa-tag'; ?>"></i></span>
                            <input type="text" name="campaign_icon" id="campIconInput" class="form-control bg-light" readonly value="<?php echo $isCampEdit ? htmlspecialchars($editCampaign['icon'], ENT_QUOTES, 'UTF-8') : 'fas fa-tag'; ?>">
                            <button type="button" class="btn btn-outline-primary" id="btnOpenIconPicker"><i class="fas fa-icons me-1"></i>Seç</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Arka Plan Rengi</label>
                        <div class="input-group">
                            <input type="color" id="campColorPicker" class="form-control form-control-color" style="max-width:50px;padding:4px;" value="<?php echo $isCampEdit ? htmlspecialchars($editCampaign['bg_color'], ENT_QUOTES, 'UTF-8') : '#1E3A8A'; ?>">
                            <input type="text" name="campaign_bg_color" id="campBgColorInput" class="form-control" value="<?php echo $isCampEdit ? htmlspecialchars($editCampaign['bg_color'], ENT_QUOTES, 'UTF-8') : '#1E3A8A'; ?>" placeholder="#1E3A8A">
                        </div>
                        <div id="campColorSwatches" class="d-flex flex-wrap gap-1 mt-2">
                            <?php
                            $presetColors = ['#1E3A8A','#3B82F6','#0066cc','#6f42c1','#e83e8c','#dc3545','#fd7e14','#F97316','#ffc107','#28a745','#10B981','#17a2b8','#20c997','#6610f2','#343a40','#495057'];
                            foreach ($presetColors as $pc): ?>
                            <button type="button" class="camp-color-swatch" data-color="<?php echo $pc; ?>" style="width:24px;height:24px;border-radius:6px;border:2px solid #dee2e6;background:<?php echo $pc; ?>;cursor:pointer;padding:0;" title="<?php echo $pc; ?>"></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Kampanya Görseli</label>
                        <input type="file" name="campaign_image" class="form-control" accept="image/png,image/jpeg,image/webp">
                        <?php if ($isCampEdit && !empty($editCampaign['image'])): ?>
                        <small class="text-muted">Mevcut: <?php echo htmlspecialchars(basename($editCampaign['image']), ENT_QUOTES, 'UTF-8'); ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Yönlendirme Linki</label>
                        <input type="text" name="campaign_link_url" class="form-control" value="<?php echo $isCampEdit ? htmlspecialchars($editCampaign['link_url'], ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="trafik-sigortasi.php">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Buton Yazısı</label>
                        <input type="text" name="campaign_link_text" class="form-control" value="<?php echo $isCampEdit ? htmlspecialchars($editCampaign['link_text'], ENT_QUOTES, 'UTF-8') : 'Teklif Al'; ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="campaign_is_active" id="campActive" <?php echo (!$isCampEdit || $editCampaign['is_active']) ? 'checked' : ''; ?>>
                            <label class="form-check-label small" for="campActive">Aktif</label>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="campaign_is_popular" id="campPopular" style="background-color: var(--bs-orange, #f97316);" <?php echo ($isCampEdit && !empty($editCampaign['is_popular'])) ? 'checked' : ''; ?>>
                            <label class="form-check-label small" for="campPopular"><i class="fas fa-fire text-warning"></i> Popüler</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> <?php echo $isCampEdit ? 'Güncelle' : 'Kaydet'; ?></button>
                    <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=kampanyalar" class="btn btn-outline-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>

<?php elseif ($currentPage === 'dosyalar'): ?>
<?php
    $adminPageTitle = 'Dosya Yönetimi';
    $uploadsDir = __DIR__ . '/../uploads/';
    $folders = ['campaigns' => 'Kampanyalar', 'partners' => 'İş Ortakları', 'ruhsat' => 'Ruhsat'];
    $folderIcons = ['campaigns' => 'fa-bullhorn', 'partners' => 'fa-handshake', 'ruhsat' => 'fa-id-card'];
    $folderColors = ['campaigns' => '#4f46e5', 'partners' => '#10b981', 'ruhsat' => '#f59e0b'];
    $activeFolder = sanitizeInput($_GET['dir'] ?? 'campaigns');
    if (!array_key_exists($activeFolder, $folders)) $activeFolder = 'campaigns';
    
    // Veritabanından dosya kullanım bilgisi çek
    $db = getDB();
    $fileUsageMap = [];
    
    if ($activeFolder === 'campaigns') {
        $stmt = $db->query("SELECT id, title, image, is_active FROM campaigns WHERE image IS NOT NULL AND image != ''");
        foreach ($stmt->fetchAll() as $row) {
            $fileUsageMap[basename($row['image'])] = [
                'type' => 'Kampanya',
                'name' => $row['title'],
                'active' => (bool)$row['is_active'],
                'link' => ADMIN_URL . '/dashboard.php?page=kampanya-duzenle&id=' . $row['id'],
            ];
        }
    } elseif ($activeFolder === 'partners') {
        $stmt = $db->query("SELECT id, name, logo, is_active FROM partners WHERE logo IS NOT NULL AND logo != ''");
        foreach ($stmt->fetchAll() as $row) {
            $fileUsageMap[basename($row['logo'])] = [
                'type' => 'İş Ortağı',
                'name' => $row['name'],
                'active' => (bool)$row['is_active'],
                'link' => ADMIN_URL . '/dashboard.php?page=is-ortagi-duzenle&id=' . $row['id'],
            ];
        }
    } elseif ($activeFolder === 'ruhsat') {
        $stmt = $db->query("SELECT id, form_type, visitor_name, form_data FROM form_submissions WHERE form_data LIKE '%ruhsat%'");
        foreach ($stmt->fetchAll() as $row) {
            $formData = json_decode($row['form_data'], true);
            if (!empty($formData['ruhsat_foto'])) {
                $fileUsageMap[basename($formData['ruhsat_foto'])] = [
                    'type' => 'Başvuru #' . $row['id'],
                    'name' => $row['visitor_name'] ?: $row['form_type'],
                    'active' => true,
                    'link' => ADMIN_URL . '/dashboard.php?page=basvurular',
                ];
            }
        }
    }
    
    // Klasördeki dosyaları listele
    $files = [];
    $scanDir = $uploadsDir . $activeFolder . '/';
    if (is_dir($scanDir)) {
        $items = scandir($scanDir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..' || $item === '.htaccess' || $item === '.gitkeep') continue;
            $fullPath = $scanDir . $item;
            if (!is_file($fullPath)) continue;
            $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
            $usage = $fileUsageMap[$item] ?? null;
            $files[] = [
                'name' => $item,
                'path' => 'uploads/' . $activeFolder . '/' . $item,
                'size' => filesize($fullPath),
                'date' => filemtime($fullPath),
                'ext' => $ext,
                'is_image' => in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']),
                'usage' => $usage,
            ];
        }
        // En yeni dosya en üstte
        usort($files, function($a, $b) { return $b['date'] - $a['date']; });
    }
    
    function formatFileSize($bytes) {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
?>

<!-- Klasör Kartları -->
<div class="row g-3 mb-4">
    <?php foreach ($folders as $fKey => $fLabel): 
        $fPath = $uploadsDir . $fKey . '/';
        $fCount = 0;
        if (is_dir($fPath)) {
            $fItems = array_diff(scandir($fPath), ['.', '..', '.htaccess', '.gitkeep']);
            $fCount = count(array_filter($fItems, function($f) use ($fPath) { return is_file($fPath . $f); }));
        }
        $isActive = ($fKey === $activeFolder);
    ?>
    <div class="col-md-4">
        <a href="<?php echo ADMIN_URL; ?>/dashboard.php?page=dosyalar&dir=<?php echo $fKey; ?>" class="text-decoration-none">
            <div class="stat-card <?php echo $isActive ? 'stat-primary' : ''; ?>" style="cursor:pointer;<?php echo $isActive ? 'border-color:rgba(79,70,229,0.3);background:rgba(79,70,229,0.03);' : ''; ?>">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="<?php echo $isActive ? '' : 'background:' . $folderColors[$fKey] . '15;color:' . $folderColors[$fKey]; ?>">
                        <i class="fas <?php echo $folderIcons[$fKey]; ?>"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size:20px;"><?php echo $fCount; ?></div>
                        <div class="stat-label"><?php echo $fLabel; ?></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<!-- Yükleme + Liste -->
<div class="admin-card mb-4">
    <div class="admin-card-header">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-folder-open" style="color:<?php echo $folderColors[$activeFolder]; ?>"></i>
            <h6 class="mb-0"><?php echo $folders[$activeFolder]; ?> <span class="text-muted fw-normal">(<?php echo count($files); ?> dosya)</span></h6>
        </div>
        <button class="btn btn-sm" style="background:var(--admin-primary);color:#fff;border-radius:8px;font-size:13px;" data-bs-toggle="modal" data-bs-target="#uploadFileModal">
            <i class="fas fa-cloud-upload-alt me-1"></i> Dosya Yükle
        </button>
    </div>
    <div class="admin-card-body p-0">
        <?php if (empty($files)): ?>
        <div class="text-center py-5">
            <i class="fas fa-folder-open d-block mb-2" style="font-size:40px;color:#cbd5e1;"></i>
            <p class="text-muted mb-0">Bu klasörde henüz dosya yok.</p>
        </div>
        <?php else: ?>
        <?php
            // Görsel index haritası oluştur
            $_imgIndexMap = [];
            $_imgCounter = 0;
            foreach ($files as $_fi) {
                if ($_fi['is_image']) { $_imgIndexMap[$_fi['path']] = $_imgCounter++; }
            }
        ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:50px;">Önizleme</th>
                        <th>Dosya Adı</th>
                        <th>Kullanıldığı Yer</th>
                        <th>Boyut</th>
                        <th>Tarih</th>
                        <th style="width:120px;" class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($files as $f): ?>
                    <tr>
                        <td>
                            <?php if ($f['is_image']): ?>
                            <img src="<?php echo SITE_URL . '/' . htmlspecialchars($f['path'], ENT_QUOTES, 'UTF-8'); ?>" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                            <?php else: ?>
                            <div style="width:40px;height:40px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:16px;">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-size:13px;font-weight:600;color:#1e293b;word-break:break-all;"><?php echo htmlspecialchars($f['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                            <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;"><?php echo strtoupper($f['ext']); ?></div>
                        </td>
                        <td>
                            <?php if ($f['usage']): ?>
                            <a href="<?php echo htmlspecialchars($f['usage']['link'], ENT_QUOTES, 'UTF-8'); ?>" class="text-decoration-none d-inline-flex align-items-center gap-1" style="font-size:12.5px;">
                                <span class="admin-badge <?php echo $f['usage']['active'] ? 'admin-badge-success' : 'admin-badge-warning'; ?>" style="font-size:10.5px;">
                                    <i class="fas <?php echo $f['usage']['active'] ? 'fa-check-circle' : 'fa-pause-circle'; ?>" style="font-size:9px;"></i>
                                    <?php echo htmlspecialchars($f['usage']['type'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <span style="color:#334155;font-weight:500;"><?php echo htmlspecialchars($f['usage']['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </a>
                            <?php else: ?>
                            <span class="admin-badge admin-badge-danger" style="font-size:10.5px;">
                                <i class="fas fa-unlink" style="font-size:9px;"></i> Kullanılmıyor
                            </span>
                            <?php endif; ?>
                        </td>
                        <td><span style="font-size:13px;color:#64748b;"><?php echo formatFileSize($f['size']); ?></span></td>
                        <td><span style="font-size:13px;color:#64748b;"><?php echo date('d.m.Y H:i', $f['date']); ?></span></td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <?php if ($f['is_image']): ?>
                                <button class="btn-action btn-action-view" title="Görüntüle" onclick="openPreview(<?php echo $_imgIndexMap[$f['path']]; ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php else: ?>
                                <a href="<?php echo SITE_URL . '/' . htmlspecialchars($f['path'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="btn-action btn-action-view" title="Görüntüle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php endif; ?>
                                <button class="btn-action btn-action-delete" title="Sil" onclick="confirmDeleteFile('<?php echo htmlspecialchars($f['path'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($f['name'], ENT_QUOTES, 'UTF-8'); ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Dosya Yükleme Modal -->
<div class="modal fade" id="uploadFileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-cloud-upload-alt me-2" style="color:var(--admin-primary);"></i>Dosya Yükle</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <?php echo getCSRFTokenField(); ?>
                <input type="hidden" name="action" value="upload_file">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600;">Hedef Klasör</label>
                        <select name="upload_dir" class="form-select" style="border-radius:8px;font-size:13px;">
                            <?php foreach ($folders as $fKey => $fLabel): ?>
                            <option value="<?php echo $fKey; ?>" <?php echo $fKey === $activeFolder ? 'selected' : ''; ?>><?php echo $fLabel; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600;">Dosya Seç</label>
                        <input type="file" name="upload_file" class="form-control" style="border-radius:8px;font-size:13px;" accept=".jpg,.jpeg,.png,.webp,.svg,.pdf,.gif" required>
                        <div class="form-text" style="font-size:11px;">İzin verilen: JPG, PNG, WEBP, SVG, PDF, GIF — Maks 5MB</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal" style="border-radius:8px;">İptal</button>
                    <button type="submit" class="btn btn-sm" style="background:var(--admin-primary);color:#fff;border-radius:8px;"><i class="fas fa-upload me-1"></i> Yükle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Dosya Silme Modal -->
<div class="modal fade" id="deleteFileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Dosya Sil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <?php echo getCSRFTokenField(); ?>
                <input type="hidden" name="action" value="delete_file">
                <input type="hidden" name="file_path" id="deleteFilePath">
                <div class="modal-body">
                    <p style="font-size:14px;"><strong id="deleteFileName"></strong> dosyasını silmek istediğinize emin misiniz?<br><small class="text-danger">Bu işlem geri alınamaz.</small></p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal" style="border-radius:8px;">İptal</button>
                    <button type="submit" class="btn btn-danger btn-sm" style="border-radius:8px;"><i class="fas fa-trash me-1"></i> Sil</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Görsel Önizleme Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;background:#0f172a;">
            <div class="modal-header border-0 py-2 px-3" style="background:rgba(255,255,255,0.05);">
                <span id="previewTitle" style="font-size:13px;color:rgba(255,255,255,0.7);font-weight:500;"></span>
                <div class="d-flex align-items-center gap-2">
                    <span id="previewCounter" style="font-size:12px;color:rgba(255,255,255,0.4);"></span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="font-size:12px;"></button>
                </div>
            </div>
            <div class="modal-body p-0 position-relative" style="min-height:400px;display:flex;align-items:center;justify-content:center;">
                <img id="previewImage" src="" alt="" style="max-width:100%;max-height:75vh;object-fit:contain;display:block;margin:auto;padding:16px;">
                <button id="previewPrev" class="position-absolute start-0 top-50 translate-middle-y ms-2" style="width:44px;height:44px;border-radius:12px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.7);font-size:18px;cursor:pointer;backdrop-filter:blur(8px);transition:all .2s;display:flex;align-items:center;justify-content:center;" onclick="slidePreview(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="previewNext" class="position-absolute end-0 top-50 translate-middle-y me-2" style="width:44px;height:44px;border-radius:12px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.7);font-size:18px;cursor:pointer;backdrop-filter:blur(8px);transition:all .2s;display:flex;align-items:center;justify-content:center;" onclick="slidePreview(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div id="previewThumbs" class="d-flex gap-2 px-3 pb-3 pt-2" style="overflow-x:auto;background:rgba(255,255,255,0.03);"></div>
        </div>
    </div>
</div>

<script>
<?php
    // Sadece görselleri JS dizisine aktar
    $imageFiles = array_values(array_filter($files, fn($x) => $x['is_image']));
?>
var _gallery = [
    <?php foreach ($imageFiles as $img): ?>
    { src: '<?php echo SITE_URL . '/' . htmlspecialchars($img['path'], ENT_QUOTES, 'UTF-8'); ?>', name: '<?php echo htmlspecialchars($img['name'], ENT_QUOTES, 'UTF-8'); ?>' },
    <?php endforeach; ?>
];
var _currentSlide = 0;
var _previewModal = null;

function openPreview(idx) {
    _currentSlide = idx;
    if (!_previewModal) _previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    updatePreview();
    _previewModal.show();
}

function slidePreview(dir) {
    _currentSlide = (_currentSlide + dir + _gallery.length) % _gallery.length;
    updatePreview();
}

function updatePreview() {
    var item = _gallery[_currentSlide];
    document.getElementById('previewImage').src = item.src;
    document.getElementById('previewTitle').textContent = item.name;
    document.getElementById('previewCounter').textContent = (_currentSlide + 1) + ' / ' + _gallery.length;
    // Navigasyon butonları
    document.getElementById('previewPrev').style.display = _gallery.length > 1 ? 'flex' : 'none';
    document.getElementById('previewNext').style.display = _gallery.length > 1 ? 'flex' : 'none';
    // Thumbnails
    var thumbs = document.getElementById('previewThumbs');
    thumbs.innerHTML = '';
    if (_gallery.length > 1) {
        _gallery.forEach(function(g, i) {
            var t = document.createElement('img');
            t.src = g.src;
            t.alt = g.name;
            t.style.cssText = 'width:52px;height:52px;object-fit:cover;border-radius:8px;cursor:pointer;border:2px solid ' + (i === _currentSlide ? '#818cf8' : 'transparent') + ';opacity:' + (i === _currentSlide ? '1' : '0.5') + ';transition:all .2s;flex-shrink:0;';
            t.onclick = function() { _currentSlide = i; updatePreview(); };
            thumbs.appendChild(t);
        });
        // Aktif thumbnail'ı görünür yap
        setTimeout(function() {
            var active = thumbs.children[_currentSlide];
            if (active) active.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }, 50);
    }
}

// Klavye tuşları ile navigasyon
document.addEventListener('keydown', function(e) {
    var modal = document.getElementById('previewModal');
    if (!modal.classList.contains('show')) return;
    if (e.key === 'ArrowLeft') slidePreview(-1);
    else if (e.key === 'ArrowRight') slidePreview(1);
});

function confirmDeleteFile(path, name) {
    document.getElementById('deleteFilePath').value = path;
    document.getElementById('deleteFileName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteFileModal')).show();
}
</script>

<?php endif; ?>

<?php if ($currentPage === 'islem-gecmisi'): ?>
<?php
    // Filtreler
    $_filtAdmin = (int)($_GET['admin_id'] ?? 0);
    $_filtAction = trim($_GET['filter_action'] ?? '');
    $_filtTable = trim($_GET['filter_table'] ?? '');
    $_filtFrom = trim($_GET['date_from'] ?? '');
    $_filtTo = trim($_GET['date_to'] ?? '');
    $_filtSearch = trim($_GET['search'] ?? '');
    $_filtPage = max(1, (int)($_GET['p'] ?? 1));
    $_perPage = 50;

    $_logFilters = [];
    if ($_filtAdmin) $_logFilters['admin_id'] = $_filtAdmin;
    if ($_filtAction) $_logFilters['action'] = $_filtAction;
    if ($_filtTable) $_logFilters['table_name'] = $_filtTable;
    if ($_filtFrom) $_logFilters['date_from'] = $_filtFrom;
    if ($_filtTo) $_logFilters['date_to'] = $_filtTo;
    if ($_filtSearch) $_logFilters['search'] = $_filtSearch;

    $_totalLogs = getAuditLogCount($_logFilters);
    $_logFilters['limit'] = $_perPage;
    $_logFilters['offset'] = ($_filtPage - 1) * $_perPage;
    $_logs = getAuditLogs($_logFilters);
    $_totalPages = ceil($_totalLogs / $_perPage);

    // Admin listesi (filtre için)
    $_adminList = [];
    try { $db_al = getDB(); $_adminList = $db_al->query("SELECT id, username, full_name FROM admins ORDER BY full_name")->fetchAll(); } catch (Exception $e) {}

    // Aksiyon renkleri
    $_actionColors = [
        'login' => 'success', 'logout' => 'secondary',
        'save_page' => 'primary', 'delete_page' => 'danger', 'toggle_page' => 'warning',
        'save_settings' => 'info', 'save_user' => 'primary', 'delete_user' => 'danger',
        'save_partner' => 'primary', 'delete_partner' => 'danger', 'toggle_partner' => 'warning',
        'save_social' => 'primary', 'delete_social' => 'danger', 'toggle_social' => 'warning',
        'save_testimonial' => 'primary', 'delete_testimonial' => 'danger', 'toggle_testimonial' => 'warning',
        'save_faq' => 'primary', 'delete_faq' => 'danger', 'delete_faq_category' => 'danger', 'toggle_faq_category' => 'warning', 'toggle_faq_homepage' => 'warning', 'toggle_faq_active' => 'warning', 'save_faq_category' => 'primary',
        'save_blog_post' => 'primary', 'delete_blog_post' => 'danger', 'toggle_blog_post' => 'warning', 'toggle_blog_featured' => 'warning', 'save_blog_category' => 'primary', 'delete_blog_category' => 'danger', 'toggle_blog_category' => 'warning',
        'save_campaign' => 'primary', 'delete_campaign' => 'danger', 'toggle_campaign' => 'warning',
        'save_branch' => 'primary', 'delete_branch' => 'danger', 'toggle_branch' => 'warning',
        'upload_file' => 'success', 'delete_file' => 'danger',
        'refresh_external_news' => 'info', 'delete_external_news' => 'danger', 'toggle_external_news' => 'warning',
        'save_partner_order' => 'info', 'save_testimonial_order' => 'info',
        'revert_audit_log' => 'dark',
        'update_submission_status' => 'info', 'delete_submission' => 'danger',
    ];
?>
    <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="fas fa-history text-indigo me-2"></i>İşlem Geçmişi</h5>
                    <small class="text-muted">Tüm admin işlemleri ve değişiklik kayıtları • Toplam <?php echo $_totalLogs; ?> kayıt</small>
                </div>
            </div>
        </div>
        <div class="card-body px-4 py-3">
            <form method="get" class="row g-2 align-items-end mb-3">
                <input type="hidden" name="page" value="islem-gecmisi">
                <div class="col-md-2">
                    <label class="form-label small mb-1">Kullanıcı</label>
                    <select name="admin_id" class="form-select form-select-sm">
                        <option value="">Tümü</option>
                        <?php foreach ($_adminList as $adm): ?>
                        <option value="<?php echo $adm['id']; ?>" <?php echo $_filtAdmin == $adm['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($adm['full_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">İşlem Türü</label>
                    <select name="filter_table" class="form-select form-select-sm">
                        <option value="">Tümü</option>
                        <option value="pages" <?php echo $_filtTable === 'pages' ? 'selected' : ''; ?>>Sayfalar</option>
                        <option value="admins" <?php echo $_filtTable === 'admins' ? 'selected' : ''; ?>>Kullanıcılar</option>
                        <option value="campaigns" <?php echo $_filtTable === 'campaigns' ? 'selected' : ''; ?>>Kampanyalar</option>
                        <option value="partners" <?php echo $_filtTable === 'partners' ? 'selected' : ''; ?>>İş Ortakları</option>
                        <option value="blog_posts" <?php echo $_filtTable === 'blog_posts' ? 'selected' : ''; ?>>Blog</option>
                        <option value="faqs" <?php echo $_filtTable === 'faqs' ? 'selected' : ''; ?>>SSS</option>
                        <option value="external_news" <?php echo $_filtTable === 'external_news' ? 'selected' : ''; ?>>Haberler</option>
                        <option value="site_settings" <?php echo $_filtTable === 'site_settings' ? 'selected' : ''; ?>>Ayarlar</option>
                        <option value="uploads" <?php echo $_filtTable === 'uploads' ? 'selected' : ''; ?>>Dosyalar</option>
                        <option value="branches" <?php echo $_filtTable === 'branches' ? 'selected' : ''; ?>>Şubeler</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Başlangıç</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="<?php echo htmlspecialchars($_filtFrom); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Bitiş</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="<?php echo htmlspecialchars($_filtTo); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Ara</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="İşlem ara..." value="<?php echo htmlspecialchars($_filtSearch); ?>">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill"><i class="fas fa-search me-1"></i>Filtrele</button>
                    <a href="?page=islem-gecmisi" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:13px;">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">#</th>
                        <th style="width:140px">Tarih</th>
                        <th style="width:130px">Kullanıcı</th>
                        <th>İşlem</th>
                        <th style="width:100px">Tablo</th>
                        <th style="width:60px">ID</th>
                        <th style="width:110px">IP</th>
                        <th style="width:80px">Detay</th>
                        <th style="width:80px">Geri Al</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($_logs)): ?>
                    <tr><td colspan="9" class="text-center py-4 text-muted">Henüz işlem kaydı bulunmuyor.</td></tr>
                    <?php else: ?>
                    <?php foreach ($_logs as $log): ?>
                    <?php $_color = $_actionColors[$log['action']] ?? 'secondary'; ?>
                    <tr>
                        <td class="text-muted"><?php echo $log['id']; ?></td>
                        <td><small><?php echo date('d.m.Y H:i:s', strtotime($log['created_at'])); ?></small></td>
                        <td>
                            <span class="fw-semibold"><?php echo htmlspecialchars($log['admin_username']); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $_color; ?>" style="font-size:11px;">
                                <?php echo htmlspecialchars($log['action_label'] ?: $log['action']); ?>
                            </span>
                        </td>
                        <td><small class="text-muted"><?php echo htmlspecialchars($log['table_name'] ?? '-'); ?></small></td>
                        <td><small class="text-muted"><?php echo $log['record_id'] ?: '-'; ?></small></td>
                        <td><small class="text-muted"><?php echo htmlspecialchars($log['ip_address'] ?? ''); ?></small></td>
                        <td>
                            <?php if ($log['old_data'] || $log['new_data']): ?>
                            <button class="btn btn-outline-secondary btn-sm" onclick="showAuditDetail(<?php echo $log['id']; ?>)" title="Detay Göster">
                                <i class="fas fa-eye"></i>
                            </button>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $canRevert = false;
                            if ($log['old_data'] && $log['action'] !== 'revert_audit_log' && $log['action'] !== 'login' && $log['action'] !== 'logout') {
                                $canRevert = true;
                            }
                            ?>
                            <?php if ($canRevert && hasRole('yonetici')): ?>
                            <button class="btn btn-outline-warning btn-sm" onclick="revertAction(<?php echo $log['id']; ?>, '<?php echo htmlspecialchars($log['action_label'] ?: $log['action'], ENT_QUOTES, 'UTF-8'); ?>')" title="Geri Al">
                                <i class="fas fa-undo"></i>
                            </button>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($_totalPages > 1): ?>
        <div class="card-footer bg-white border-0 py-3 px-4">
            <nav>
                <ul class="pagination pagination-sm justify-content-center mb-0">
                    <?php for ($p = 1; $p <= $_totalPages; $p++): ?>
                    <?php
                        $params = $_GET;
                        $params['p'] = $p;
                        $qs = http_build_query($params);
                    ?>
                    <li class="page-item <?php echo $p === $_filtPage ? 'active' : ''; ?>">
                        <a class="page-link" href="?<?php echo $qs; ?>"><?php echo $p; ?></a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>

<!-- İşlem Detay Modal -->
<div class="modal fade" id="auditDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-info-circle text-primary me-2"></i>İşlem Detayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="auditDetailBody">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</div>
            </div>
        </div>
    </div>
</div>

<script>
// Audit log verilerini hazırla (tüm sayfadaki kayıtlar için)
var _auditData = {};
<?php foreach ($_logs as $log): ?>
_auditData[<?php echo $log['id']; ?>] = {
    id: <?php echo $log['id']; ?>,
    date: <?php echo json_encode(date('d.m.Y H:i:s', strtotime($log['created_at']))); ?>,
    user: <?php echo json_encode($log['admin_username']); ?>,
    action: <?php echo json_encode($log['action']); ?>,
    label: <?php echo json_encode($log['action_label']); ?>,
    table: <?php echo json_encode($log['table_name']); ?>,
    recordId: <?php echo json_encode($log['record_id']); ?>,
    ip: <?php echo json_encode($log['ip_address']); ?>,
    ua: <?php echo json_encode(mb_substr($log['user_agent'] ?? '', 0, 100, 'UTF-8')); ?>,
    oldData: <?php echo $log['old_data'] ?: 'null'; ?>,
    newData: <?php echo $log['new_data'] ?: 'null'; ?>
};
<?php endforeach; ?>

function showAuditDetail(id) {
    var d = _auditData[id];
    if (!d) return;
    var html = '<div class="mb-3">';
    html += '<div class="row g-2 mb-3">';
    html += '<div class="col-6"><strong>Tarih:</strong> ' + d.date + '</div>';
    html += '<div class="col-6"><strong>Kullanıcı:</strong> ' + escHtml(d.user) + '</div>';
    html += '<div class="col-6"><strong>İşlem:</strong> ' + escHtml(d.label || d.action) + '</div>';
    html += '<div class="col-6"><strong>Tablo:</strong> ' + escHtml(d.table || '-') + ' / ID: ' + (d.recordId || '-') + '</div>';
    html += '<div class="col-6"><strong>IP:</strong> ' + escHtml(d.ip || '-') + '</div>';
    html += '<div class="col-6"><strong>Tarayıcı:</strong> ' + escHtml(d.ua || '-') + '</div>';
    html += '</div>';

    if (d.oldData) {
        html += '<h6 class="fw-bold text-danger"><i class="fas fa-minus-circle me-1"></i>Eski Veri (Kurtarılabilir)</h6>';
        html += '<div class="bg-light p-3 rounded mb-3" style="max-height:250px;overflow:auto;font-size:12px;">';
        html += renderDataTable(d.oldData);
        html += '</div>';
    }
    if (d.newData) {
        html += '<h6 class="fw-bold text-success"><i class="fas fa-plus-circle me-1"></i>Yeni Veri</h6>';
        html += '<div class="bg-light p-3 rounded" style="max-height:250px;overflow:auto;font-size:12px;">';
        html += renderDataTable(d.newData);
        html += '</div>';
    }
    html += '</div>';
    document.getElementById('auditDetailBody').innerHTML = html;
    new bootstrap.Modal(document.getElementById('auditDetailModal')).show();
}

function renderDataTable(data) {
    if (typeof data !== 'object' || data === null) return '<em>Veri yok</em>';
    var rows = '';
    for (var key in data) {
        if (!data.hasOwnProperty(key)) continue;
        var val = data[key];
        if (val === null || val === '') val = '<em class="text-muted">boş</em>';
        else if (typeof val === 'object') val = '<pre style="margin:0;font-size:11px;white-space:pre-wrap;">' + escHtml(JSON.stringify(val, null, 2)) + '</pre>';
        else {
            var s = String(val);
            if (s.length > 300) s = s.substring(0, 300) + '...';
            val = escHtml(s);
        }
        rows += '<tr><td class="fw-semibold text-nowrap pe-3" style="vertical-align:top;">' + escHtml(key) + '</td><td>' + val + '</td></tr>';
    }
    return '<table class="table table-sm table-borderless mb-0">' + rows + '</table>';
}

function escHtml(t) {
    var d = document.createElement('div');
    d.textContent = t;
    return d.innerHTML;
}

var _revertId = 0;
function revertAction(id, label) {
    _revertId = id;
    document.getElementById('revertActionLabel').textContent = label;
    new bootstrap.Modal(document.getElementById('revertConfirmModal')).show();
}
document.addEventListener('DOMContentLoaded', function() {
    var confirmBtn = document.getElementById('confirmRevertBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (!_revertId) return;
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Geri alınıyor...';
            adminAjax('revert_audit_log', {log_id: _revertId}, function(data) {
                if (data.success) {
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() { location.reload(); }, 1200);
                } else {
                    showAdminToast('danger', 'Hata', data.message || 'İşlem geri alınamadı.');
                    document.getElementById('confirmRevertBtn').disabled = false;
                    document.getElementById('confirmRevertBtn').innerHTML = '<i class="fas fa-undo me-1"></i>Evet, Geri Al';
                }
            });
        });
    }
});
</script>

<!-- Geri Alma Onay Modal -->
<div class="modal fade" id="revertConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-undo text-warning me-2"></i>İşlemi Geri Al</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size:14px;">
                    <strong>"<span id="revertActionLabel"></span>"</strong> işlemini geri almak istediğinize emin misiniz?
                </p>
                <div class="alert alert-warning py-2" style="font-size:13px;">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Bu işlem, kayıtlı eski veriyi geri yükleyecektir. Geri alma işlemi de İşlem Geçmişi'nde kayıt altına alınacaktır.
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-warning btn-sm" id="confirmRevertBtn"><i class="fas fa-undo me-1"></i>Evet, Geri Al</button>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<!-- Kampanya Silme Modal -->
<div class="modal fade" id="deleteCampaignModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Kampanya Sil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px;">
                    <strong id="deleteCampaignName"></strong> kampanyasını silmek istediğinize emin misiniz?<br>
                    <small class="text-danger">Bu işlem geri alınamaz.</small>
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <input type="hidden" id="deleteCampaignId" value="">
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteCampaign"><i class="fas fa-trash me-1"></i> Sil</button>
            </div>
        </div>
    </div>
</div>

<!-- İkon Seçici Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-2">
                <h6 class="modal-title fw-bold"><i class="fas fa-icons text-primary me-2"></i>İkon Seç</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="px-3 pb-2">
                <input type="text" id="iconSearchInput" class="form-control form-control-sm" placeholder="İkon ara... (car, home, heart, shield...)">
            </div>
            <div class="modal-body pt-1" style="min-height: 300px; max-height: 50vh;">
                <div id="iconGrid" class="d-flex flex-wrap gap-2 justify-content-center"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSocialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Sosyal Medya Sil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px;">
                    <strong id="deleteSocialName"></strong> hesabını silmek istediğinize emin misiniz?<br>
                    <small class="text-danger">Bu işlem geri alınamaz.</small>
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <input type="hidden" id="deleteSocialId" value="">
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteSocial"><i class="fas fa-trash me-1"></i> Sil</button>
            </div>
        </div>
    </div>
</div>

<!-- Yorum Silme Modal -->
<div class="modal fade" id="deleteTestimonialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Yorumu Sil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px;">
                    <strong id="deleteTestimonialName"></strong> yorumunu silmek istediğinize emin misiniz?<br>
                    <small class="text-danger">Bu işlem geri alınamaz.</small>
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <input type="hidden" id="deleteTestimonialId" value="">
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteTestimonial"><i class="fas fa-trash me-1"></i> Sil</button>
            </div>
        </div>
    </div>
</div>

<!-- Blog Yazı Silme Modal -->
<div class="modal fade" id="deleteBlogModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Yazıyı Sil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px;">
                    <strong id="deleteBlogName"></strong> yazısını silmek istediğinize emin misiniz?<br>
                    <small class="text-danger">Bu işlem geri alınamaz.</small>
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <input type="hidden" id="deleteBlogId" value="">
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBlog"><i class="fas fa-trash me-1"></i> Sil</button>
            </div>
        </div>
    </div>
</div>

<!-- Blog Kategori Silme Modal -->
<div class="modal fade" id="deleteBlogCatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Kategoriyi Sil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px;">
                    <strong id="deleteBlogCatName"></strong> kategorisini silmek istediğinize emin misiniz?<br>
                    <span id="deleteBlogCatWarning" class="text-danger" style="font-size:13px;"></span>
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <input type="hidden" id="deleteBlogCatId" value="">
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBlogCat"><i class="fas fa-trash me-1"></i> Sil</button>
            </div>
        </div>
    </div>
</div>

<!-- Harici Haber Silme Modal -->
<div class="modal fade" id="deleteExtNewsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Haberi Sil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px;">
                    <strong id="deleteExtNewsName"></strong> haberini silmek istediğinize emin misiniz?<br>
                    <small class="text-danger">Bu işlem geri alınamaz.</small>
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <input type="hidden" id="deleteExtNewsId" value="">
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteExtNews"><i class="fas fa-trash me-1"></i> Sil</button>
            </div>
        </div>
    </div>
</div>

<!-- SSS Soru Silme Modal -->
<div class="modal fade" id="deleteFaqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Soruyu Sil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px;">
                    <strong id="deleteFaqName"></strong> sorusunu silmek istediğinize emin misiniz?<br>
                    <small class="text-danger">Bu işlem geri alınamaz.</small>
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <input type="hidden" id="deleteFaqId" value="">
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteFaq"><i class="fas fa-trash me-1"></i> Sil</button>
            </div>
        </div>
    </div>
</div>

<!-- SSS Kategori Silme Modal -->
<div class="modal fade" id="deleteFaqCatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Kategoriyi Sil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px;">
                    <strong id="deleteFaqCatName"></strong> kategorisini silmek istediğinize emin misiniz?<br>
                    <span id="deleteFaqCatWarning" class="text-danger" style="font-size:13px;"></span>
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <input type="hidden" id="deleteFaqCatId" value="">
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteFaqCat"><i class="fas fa-trash me-1"></i> Sil</button>
            </div>
        </div>
    </div>
</div>

<!-- İş Ortağı Silme Modal -->
<div class="modal fade" id="deletePartnerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>İş Ortağını Sil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px;">
                    <strong id="deletePartnerName"></strong> iş ortağını silmek istediğinize emin misiniz?<br>
                    <small class="text-danger">Logo dosyası da silinecektir. Bu işlem geri alınamaz.</small>
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <input type="hidden" id="deletePartnerId" value="">
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeletePartner"><i class="fas fa-trash me-1"></i> Sil</button>
            </div>
        </div>
    </div>
</div>

<!-- Sayfa Silme Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Sayfayı Sil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px;">
                    <strong id="deletePageTitle"></strong> sayfasını silmek istediğinize emin misiniz?<br>
                    <small class="text-danger">Bu işlem geri alınamaz.</small>
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <input type="hidden" id="deletePageId" value="">
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeletePage"><i class="fas fa-trash me-1"></i> Sil</button>
            </div>
        </div>
    </div>
</div>

<style>
.toggle-page { cursor: pointer; }
.toggle-page:checked { background-color: #198754; border-color: #198754; }
</style>

<!-- Kullanıcı Silme Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Kullanıcıyı Sil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px;">
                    <strong id="deleteUserName"></strong> kullanıcısını silmek istediğinize emin misiniz?<br>
                    <small class="text-danger">Bu işlem geri alınamaz.</small>
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
                <input type="hidden" id="deleteUserId" value="">
                <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteUser"><i class="fas fa-trash me-1"></i> Sil</button>
            </div>
        </div>
    </div>
</div>

<!-- Başvuru Detay Modal -->
<div class="modal fade" id="submissionDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-file-alt text-primary me-2"></i>Başvuru Detayı <span id="subDetailId" class="text-muted"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="subDetailBody">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <div class="d-flex gap-2 w-100">
                    <div class="d-inline">
                        <input type="hidden" id="subStatusId" value="">
                        <select id="subStatusSelect" class="form-select form-select-sm d-inline-block" style="width: auto;">
                            <option value="yeni">Yeni</option>
                            <option value="okundu">Okundu</option>
                            <option value="tamamlandi">Tamamlandı</option>
                        </select>
                    </div>
                    <a href="#" id="subWhatsappLink" target="_blank" class="btn btn-sm btn-success ms-auto"><i class="fab fa-whatsapp me-1"></i> WhatsApp</a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ==================== Admin AJAX Altyapısı ====================
var CSRF_NAME = '<?php echo CSRF_TOKEN_NAME; ?>';
var CSRF_TOKEN = '<?php echo generateCSRFToken(); ?>';

function adminAjax(action, extraData, callback) {
    var formData;
    if (extraData instanceof FormData) {
        formData = extraData;
        if (!formData.has('action')) formData.append('action', action);
        if (!formData.has(CSRF_NAME)) formData.append(CSRF_NAME, CSRF_TOKEN);
    } else {
        formData = new FormData();
        formData.append('action', action);
        formData.append(CSRF_NAME, CSRF_TOKEN);
        if (extraData) {
            for (var key in extraData) {
                if (extraData.hasOwnProperty(key)) formData.append(key, extraData[key]);
            }
        }
    }
    fetch('dashboard.php', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    }).then(function(r) { return r.json(); }).then(function(data) {
        if (callback) callback(data);
    }).catch(function(err) {
        showAdminToast('danger', 'Hata', 'Sunucu ile bağlantı kurulamadı.');
        if (callback) callback({ success: false, message: 'Bağlantı hatası.' });
    });
}

function showAdminToast(type, title, message) {
    var colors = { success: '#198754', danger: '#dc3545', warning: '#ffc107', info: '#0dcaf0' };
    var icons = { success: 'fa-check-circle', danger: 'fa-exclamation-triangle', warning: 'fa-exclamation-circle', info: 'fa-info-circle' };
    var color = colors[type] || colors.info;
    var icon = icons[type] || icons.info;
    var toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.borderLeftColor = color;
    toast.innerHTML = '<div class="toast-icon" style="background:' + color + '1a;color:' + color + ';"><i class="fas ' + icon + '"></i></div>'
        + '<div class="toast-body"><div class="toast-title">' + title + '</div><div class="toast-message">' + message + '</div></div>'
        + '<button class="toast-close" onclick="this.parentElement.remove()">&times;</button>';
    var container = document.getElementById('toastContainer');
    if (container) { container.appendChild(toast); }
    setTimeout(function() { toast.classList.add('toast-hide'); setTimeout(function() { toast.remove(); }, 400); }, 5000);
}

function fadeOutRow(row, callback) {
    row.style.transition = 'opacity 0.4s, transform 0.4s';
    row.style.opacity = '0';
    row.style.transform = 'translateX(30px)';
    setTimeout(function() { row.remove(); if (callback) callback(); }, 400);
}

function flashRow(row, color) {
    row.style.transition = 'background 0.3s';
    row.style.background = color;
    setTimeout(function() { row.style.background = ''; }, 800);
}

document.addEventListener('DOMContentLoaded', function() {

    // ==================== Toggle Sayfa Aktif/Pasif ====================
    document.querySelectorAll('.toggle-page').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            var el = this;
            var pageId = el.dataset.id;
            var isActive = el.checked ? 1 : 0;
            el.disabled = true;
            adminAjax('toggle_page', { page_id: pageId, is_active: isActive }, function(data) {
                el.disabled = false;
                if (data.success) {
                    flashRow(el.closest('tr'), isActive ? '#d1e7dd' : '#f8d7da');
                    showAdminToast('success', 'Başarılı', data.message);
                } else {
                    el.checked = !el.checked;
                    showAdminToast('danger', 'Hata', data.message || 'İşlem başarısız.');
                }
            });
        });
    });

    // ==================== Sayfa Silme (Modal + AJAX) ====================
    document.querySelectorAll('.btn-delete-page').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('deletePageId').value = this.dataset.id;
            document.getElementById('deletePageTitle').textContent = this.dataset.title;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });
    var confirmDeletePageBtn = document.getElementById('confirmDeletePage');
    if (confirmDeletePageBtn) {
        confirmDeletePageBtn.addEventListener('click', function() {
            var btn = this;
            var pageId = document.getElementById('deletePageId').value;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Siliniyor...';
            adminAjax('delete_page', { page_id: pageId }, function(data) {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check me-1 text-white"></i> Silindi!';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-success');
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        if (modal) modal.hide();
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-danger');
                        var row = document.querySelector('.btn-delete-page[data-id="' + pageId + '"]');
                        if (row) fadeOutRow(row.closest('tr'));
                    }, 2000);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                    showAdminToast('danger', 'Hata', data.message || 'Silme başarısız.');
                }
            });
        });
    }

    // ==================== Kullanıcı Silme (Modal + AJAX) ====================
    document.querySelectorAll('.btn-delete-user').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('deleteUserId').value = this.dataset.id;
            document.getElementById('deleteUserName').textContent = this.dataset.name;
            new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
        });
    });
    var confirmDeleteUserBtn = document.getElementById('confirmDeleteUser');
    if (confirmDeleteUserBtn) {
        confirmDeleteUserBtn.addEventListener('click', function() {
            var btn = this;
            var userId = document.getElementById('deleteUserId').value;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Siliniyor...';
            adminAjax('delete_user', { user_id: userId }, function(data) {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check me-1 text-white"></i> Silindi!';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-success');
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('deleteUserModal'));
                        if (modal) modal.hide();
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-danger');
                        var row = document.querySelector('.btn-delete-user[data-id="' + userId + '"]');
                        if (row) fadeOutRow(row.closest('tr'));
                    }, 2000);
                    return;
                }
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                showAdminToast('danger', 'Hata', data.message || 'Silme başarısız.');
            });
        });
    }

    // ==================== İş Ortağı Toggle ====================
    document.querySelectorAll('.toggle-partner').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            var el = this;
            var partnerId = el.dataset.id;
            var isActive = el.checked ? 1 : 0;
            el.disabled = true;
            adminAjax('toggle_partner', { partner_id: partnerId, is_active: isActive }, function(data) {
                el.disabled = false;
                if (data.success) {
                    flashRow(el.closest('tr'), isActive ? '#d1e7dd' : '#f8d7da');
                    showAdminToast('success', 'Başarılı', data.message);
                } else {
                    el.checked = !el.checked;
                    showAdminToast('danger', 'Hata', data.message || 'İşlem başarısız.');
                }
            });
        });
    });

    // ==================== İş Ortağı Silme (Modal + AJAX) ====================
    document.querySelectorAll('.btn-delete-partner').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('deletePartnerId').value = this.dataset.id;
            document.getElementById('deletePartnerName').textContent = this.dataset.name;
            new bootstrap.Modal(document.getElementById('deletePartnerModal')).show();
        });
    });
    var confirmDeletePartnerBtn = document.getElementById('confirmDeletePartner');
    if (confirmDeletePartnerBtn) {
        confirmDeletePartnerBtn.addEventListener('click', function() {
            var btn = this;
            var partnerId = document.getElementById('deletePartnerId').value;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Siliniyor...';
            adminAjax('delete_partner', { partner_id: partnerId }, function(data) {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check me-1 text-white"></i> Silindi!';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-success');
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('deletePartnerModal'));
                        if (modal) modal.hide();
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-danger');
                        var row = document.querySelector('.btn-delete-partner[data-id="' + partnerId + '"]');
                        if (row) fadeOutRow(row.closest('tr'));
                    }, 2000);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                    showAdminToast('danger', 'Hata', data.message || 'Silme başarısız.');
                }
            });
        });
    }

    // ==================== Sosyal Medya Toggle ====================
    document.querySelectorAll('.toggle-social').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            var el = this;
            var socialId = el.dataset.id;
            var isActive = el.checked ? 1 : 0;
            el.disabled = true;
            adminAjax('toggle_social', { social_id: socialId, is_active: isActive }, function(data) {
                el.disabled = false;
                if (data.success) {
                    flashRow(el.closest('tr'), isActive ? '#d1e7dd' : '#f8d7da');
                    showAdminToast('success', 'Başarılı', data.message);
                } else {
                    el.checked = !el.checked;
                    showAdminToast('danger', 'Hata', data.message || 'İşlem başarısız.');
                }
            });
        });
    });

    // ==================== Sosyal Medya Silme (Modal + AJAX) ====================
    document.querySelectorAll('.btn-delete-social').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('deleteSocialId').value = this.dataset.id;
            document.getElementById('deleteSocialName').textContent = this.dataset.name;
            new bootstrap.Modal(document.getElementById('deleteSocialModal')).show();
        });
    });
    var confirmDeleteSocialBtn = document.getElementById('confirmDeleteSocial');
    if (confirmDeleteSocialBtn) {
        confirmDeleteSocialBtn.addEventListener('click', function() {
            var btn = this;
            var socialId = document.getElementById('deleteSocialId').value;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Siliniyor...';
            adminAjax('delete_social', { social_id: socialId }, function(data) {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check me-1 text-white"></i> Silindi!';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-success');
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('deleteSocialModal'));
                        if (modal) modal.hide();
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-danger');
                        var row = document.querySelector('.btn-delete-social[data-id="' + socialId + '"]');
                        if (row) fadeOutRow(row.closest('tr'));
                    }, 2000);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                    showAdminToast('danger', 'Hata', data.message || 'Silme başarısız.');
                }
            });
        });
    }

    // ==================== Yorum Toggle ====================
    document.querySelectorAll('.toggle-testimonial').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            var el = this;
            var testimonialId = el.dataset.id;
            var isActive = el.checked ? 1 : 0;
            el.disabled = true;
            adminAjax('toggle_testimonial', { testimonial_id: testimonialId, is_active: isActive }, function(data) {
                el.disabled = false;
                if (data.success) {
                    flashRow(el.closest('tr'), isActive ? '#d1e7dd' : '#f8d7da');
                    showAdminToast('success', 'Başarılı', data.message);
                } else {
                    el.checked = !el.checked;
                    showAdminToast('danger', 'Hata', data.message || 'İşlem başarısız.');
                }
            });
        });
    });

    // ==================== Yorum Silme (Modal + AJAX) ====================
    document.querySelectorAll('.btn-delete-testimonial').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('deleteTestimonialId').value = this.dataset.id;
            document.getElementById('deleteTestimonialName').textContent = this.dataset.name;
            new bootstrap.Modal(document.getElementById('deleteTestimonialModal')).show();
        });
    });
    var confirmDeleteTestimonialBtn = document.getElementById('confirmDeleteTestimonial');
    if (confirmDeleteTestimonialBtn) {
        confirmDeleteTestimonialBtn.addEventListener('click', function() {
            var btn = this;
            var testimonialId = document.getElementById('deleteTestimonialId').value;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Siliniyor...';
            adminAjax('delete_testimonial', { testimonial_id: testimonialId }, function(data) {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check me-1 text-white"></i> Silindi!';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-success');
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('deleteTestimonialModal'));
                        if (modal) modal.hide();
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-danger');
                        var row = document.querySelector('.btn-delete-testimonial[data-id="' + testimonialId + '"]');
                        if (row) fadeOutRow(row.closest('tr'));
                    }, 2000);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                    showAdminToast('danger', 'Hata', data.message || 'Silme başarısız.');
                }
            });
        });
    }

    // ==================== Blog Toggle Active (AJAX) ====================
    document.querySelectorAll('.toggle-blog-active').forEach(function(el) {
        el.addEventListener('change', function() {
            var postId = el.dataset.id;
            var val = el.checked ? 1 : 0;
            el.disabled = true;
            adminAjax('toggle_blog_post', { post_id: postId, is_active: val }, function(data) {
                el.disabled = false;
                if (data.success) { flashRow(el.closest('tr'), val ? '#d1e7dd' : '#f8d7da'); showAdminToast('success', 'Başarılı', data.message); }
                else { el.checked = !el.checked; showAdminToast('danger', 'Hata', data.message || 'İşlem başarısız.'); }
            });
        });
    });

    // ==================== Blog Toggle Featured (AJAX) ====================
    document.querySelectorAll('.toggle-blog-featured').forEach(function(el) {
        el.addEventListener('change', function() {
            var postId = el.dataset.id;
            var val = el.checked ? 1 : 0;
            el.disabled = true;
            adminAjax('toggle_blog_featured', { post_id: postId, is_featured: val }, function(data) {
                el.disabled = false;
                if (data.success) { flashRow(el.closest('tr'), val ? '#d1e7dd' : '#f8d7da'); showAdminToast('success', 'Başarılı', data.message); }
                else { el.checked = !el.checked; showAdminToast('danger', 'Hata', data.message || 'İşlem başarısız.'); }
            });
        });
    });

    // ==================== Blog Kategori Toggle (AJAX) ====================
    document.querySelectorAll('.toggle-blog-cat').forEach(function(el) {
        el.addEventListener('change', function() {
            var catId = el.dataset.id;
            var val = el.checked ? 1 : 0;
            el.disabled = true;
            adminAjax('toggle_blog_category', { category_id: catId, is_active: val }, function(data) {
                el.disabled = false;
                if (data.success) { flashRow(el.closest('tr'), val ? '#d1e7dd' : '#f8d7da'); showAdminToast('success', 'Başarılı', data.message); }
                else { el.checked = !el.checked; showAdminToast('danger', 'Hata', data.message || 'İşlem başarısız.'); }
            });
        });
    });

    // ==================== Blog Yazı Silme (Modal + AJAX) ====================
    document.querySelectorAll('.btn-delete-blog').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('deleteBlogId').value = this.dataset.id;
            document.getElementById('deleteBlogName').textContent = this.dataset.name;
            new bootstrap.Modal(document.getElementById('deleteBlogModal')).show();
        });
    });
    var confirmDeleteBlogBtn = document.getElementById('confirmDeleteBlog');
    if (confirmDeleteBlogBtn) {
        confirmDeleteBlogBtn.addEventListener('click', function() {
            var btn = this;
            var postId = document.getElementById('deleteBlogId').value;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Siliniyor...';
            adminAjax('delete_blog_post', { post_id: postId }, function(data) {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check me-1 text-white"></i> Silindi!';
                    btn.classList.remove('btn-danger'); btn.classList.add('btn-success');
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('deleteBlogModal'));
                        if (modal) modal.hide();
                        btn.disabled = false; btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                        btn.classList.remove('btn-success'); btn.classList.add('btn-danger');
                        var row = document.querySelector('.btn-delete-blog[data-id="' + postId + '"]');
                        if (row) fadeOutRow(row.closest('tr'));
                    }, 2000);
                } else {
                    btn.disabled = false; btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                    showAdminToast('danger', 'Hata', data.message || 'Silme başarısız.');
                }
            });
        });
    }

    // ==================== Blog Kategori Silme (Modal + AJAX) ====================
    document.querySelectorAll('.btn-delete-blog-cat').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('deleteBlogCatId').value = this.dataset.id;
            document.getElementById('deleteBlogCatName').textContent = this.dataset.name;
            var count = parseInt(this.dataset.count || 0);
            document.getElementById('deleteBlogCatWarning').textContent = count > 0
                ? 'Bu kategoride ' + count + ' yazı bulunmaktadır. Kategori silindiğinde yazılar da silinecektir!'
                : 'Bu işlem geri alınamaz.';
            new bootstrap.Modal(document.getElementById('deleteBlogCatModal')).show();
        });
    });
    var confirmDeleteBlogCatBtn = document.getElementById('confirmDeleteBlogCat');
    if (confirmDeleteBlogCatBtn) {
        confirmDeleteBlogCatBtn.addEventListener('click', function() {
            var btn = this;
            var catId = document.getElementById('deleteBlogCatId').value;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Siliniyor...';
            adminAjax('delete_blog_category', { category_id: catId }, function(data) {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check me-1 text-white"></i> Silindi!';
                    btn.classList.remove('btn-danger'); btn.classList.add('btn-success');
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('deleteBlogCatModal'));
                        if (modal) modal.hide();
                        btn.disabled = false; btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                        btn.classList.remove('btn-success'); btn.classList.add('btn-danger');
                        var row = document.querySelector('.btn-delete-blog-cat[data-id="' + catId + '"]');
                        if (row) fadeOutRow(row.closest('tr'));
                    }, 2000);
                } else {
                    btn.disabled = false; btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                    showAdminToast('danger', 'Hata', data.message || 'Silme başarısız.');
                }
            });
        });
    }

    // ==================== SSS Toggle Homepage (AJAX) ====================

    // ==================== Harici Haberler ====================
    // Sayfa yüklendiğinde otomatik güncelleme (haberler güncel değilse)
    var newsIsStale = <?php echo json_encode($newsIsStale ?? false); ?>;
    if (newsIsStale && document.getElementById('btnRefreshNews')) {
        (function() {
            var btn = document.getElementById('btnRefreshNews');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Otomatik güncelleniyor...';
            adminAjax('refresh_external_news', {}, function(data) {
                if (data.success) {
                    showAdminToast('success', 'Otomatik Güncelleme', data.message);
                    setTimeout(function() { location.reload(); }, 1500);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Haberleri Güncelle';
                    showAdminToast('danger', 'Hata', data.message || 'Otomatik güncelleme başarısız.');
                }
            });
        })();
    }

    // Refresh butonu (manuel)
    var btnRefresh = document.getElementById('btnRefreshNews');
    if (btnRefresh) {
        btnRefresh.addEventListener('click', function() {
            var btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Güncelleniyor...';
            adminAjax('refresh_external_news', {}, function(data) {
                if (data.success) {
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() { location.reload(); }, 1500);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Haberleri Güncelle';
                    showAdminToast('danger', 'Hata', data.message || 'Güncelleme başarısız.');
                }
            });
        });
    }
    // Toggle aktif/pasif
    document.querySelectorAll('.toggle-ext-news').forEach(function(el) {
        el.addEventListener('change', function() {
            var id = el.dataset.id;
            var val = el.checked ? 1 : 0;
            el.disabled = true;
            adminAjax('toggle_external_news', { news_id: id, is_active: val }, function(data) {
                el.disabled = false;
                if (data.success) {
                    flashRow(el.closest('tr'), val ? '#d1e7dd' : '#f8d7da');
                    showAdminToast('success', 'Başarılı', data.message);
                } else {
                    el.checked = !el.checked;
                    showAdminToast('danger', 'Hata', data.message || 'İşlem başarısız.');
                }
            });
        });
    });
    // Silme
    document.querySelectorAll('.btn-delete-ext-news').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('deleteExtNewsId').value = this.dataset.id;
            document.getElementById('deleteExtNewsName').textContent = this.dataset.name;
            new bootstrap.Modal(document.getElementById('deleteExtNewsModal')).show();
        });
    });
    var confirmDelExtNews = document.getElementById('confirmDeleteExtNews');
    if (confirmDelExtNews) {
        confirmDelExtNews.addEventListener('click', function() {
            var btn = this;
            var newsId = document.getElementById('deleteExtNewsId').value;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Siliniyor...';
            adminAjax('delete_external_news', { news_id: newsId }, function(data) {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check me-1 text-white"></i> Silindi!';
                    btn.classList.remove('btn-danger'); btn.classList.add('btn-success');
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('deleteExtNewsModal'));
                        if (modal) modal.hide();
                        btn.disabled = false; btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                        btn.classList.remove('btn-success'); btn.classList.add('btn-danger');
                        var row = document.querySelector('.btn-delete-ext-news[data-id="' + newsId + '"]');
                        if (row) fadeOutRow(row.closest('tr'));
                    }, 2000);
                } else {
                    btn.disabled = false; btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                    showAdminToast('danger', 'Hata', data.message || 'Silme başarısız.');
                }
            });
        });
    }

    // ==================== SSS Toggle Homepage (AJAX) (continued) ====================
    document.querySelectorAll('.toggle-faq-homepage').forEach(function(el) {
        el.addEventListener('change', function() {
            var faqId = el.dataset.id;
            var val = el.checked ? 1 : 0;
            el.disabled = true;
            adminAjax('toggle_faq_homepage', { faq_id: faqId, show_on_homepage: val }, function(data) {
                el.disabled = false;
                if (data.success) {
                    flashRow(el.closest('tr'), val ? '#d1e7dd' : '#f8d7da');
                    showAdminToast('success', 'Başarılı', data.message);
                } else {
                    el.checked = !el.checked;
                    showAdminToast('danger', 'Hata', data.message || 'İşlem başarısız.');
                }
            });
        });
    });

    // ==================== SSS Toggle Active (AJAX) ====================
    document.querySelectorAll('.toggle-faq-active').forEach(function(el) {
        el.addEventListener('change', function() {
            var faqId = el.dataset.id;
            var val = el.checked ? 1 : 0;
            el.disabled = true;
            adminAjax('toggle_faq_active', { faq_id: faqId, is_active: val }, function(data) {
                el.disabled = false;
                if (data.success) {
                    flashRow(el.closest('tr'), val ? '#d1e7dd' : '#f8d7da');
                    showAdminToast('success', 'Başarılı', data.message);
                } else {
                    el.checked = !el.checked;
                    showAdminToast('danger', 'Hata', data.message || 'İşlem başarısız.');
                }
            });
        });
    });

    // ==================== SSS Kategori Toggle Active (AJAX) ====================
    document.querySelectorAll('.toggle-faq-category').forEach(function(el) {
        el.addEventListener('change', function() {
            var catId = el.dataset.id;
            var val = el.checked ? 1 : 0;
            el.disabled = true;
            adminAjax('toggle_faq_category', { category_id: catId, is_active: val }, function(data) {
                el.disabled = false;
                if (data.success) {
                    flashRow(el.closest('tr'), val ? '#d1e7dd' : '#f8d7da');
                    showAdminToast('success', 'Başarılı', data.message);
                } else {
                    el.checked = !el.checked;
                    showAdminToast('danger', 'Hata', data.message || 'İşlem başarısız.');
                }
            });
        });
    });

    // ==================== SSS Soru Silme (Modal + AJAX) ====================
    document.querySelectorAll('.btn-delete-faq').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('deleteFaqId').value = this.dataset.id;
            document.getElementById('deleteFaqName').textContent = this.dataset.name;
            new bootstrap.Modal(document.getElementById('deleteFaqModal')).show();
        });
    });
    var confirmDeleteFaqBtn = document.getElementById('confirmDeleteFaq');
    if (confirmDeleteFaqBtn) {
        confirmDeleteFaqBtn.addEventListener('click', function() {
            var btn = this;
            var faqId = document.getElementById('deleteFaqId').value;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Siliniyor...';
            adminAjax('delete_faq', { faq_id: faqId }, function(data) {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check me-1 text-white"></i> Silindi!';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-success');
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('deleteFaqModal'));
                        if (modal) modal.hide();
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-danger');
                        var row = document.querySelector('.btn-delete-faq[data-id="' + faqId + '"]');
                        if (row) fadeOutRow(row.closest('tr'));
                    }, 2000);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                    showAdminToast('danger', 'Hata', data.message || 'Silme başarısız.');
                }
            });
        });
    }

    // ==================== SSS Kategori Silme (Modal + AJAX) ====================
    document.querySelectorAll('.btn-delete-faq-cat').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('deleteFaqCatId').value = this.dataset.id;
            document.getElementById('deleteFaqCatName').textContent = this.dataset.name;
            var count = parseInt(this.dataset.count || 0);
            document.getElementById('deleteFaqCatWarning').textContent = count > 0
                ? 'Bu kategoride ' + count + ' soru bulunmaktadır. Kategori silindiğinde sorular da silinecektir!'
                : 'Bu işlem geri alınamaz.';
            new bootstrap.Modal(document.getElementById('deleteFaqCatModal')).show();
        });
    });
    var confirmDeleteFaqCatBtn = document.getElementById('confirmDeleteFaqCat');
    if (confirmDeleteFaqCatBtn) {
        confirmDeleteFaqCatBtn.addEventListener('click', function() {
            var btn = this;
            var catId = document.getElementById('deleteFaqCatId').value;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Siliniyor...';
            adminAjax('delete_faq_category', { category_id: catId }, function(data) {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check me-1 text-white"></i> Silindi!';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-success');
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('deleteFaqCatModal'));
                        if (modal) modal.hide();
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-danger');
                        var row = document.querySelector('.btn-delete-faq-cat[data-id="' + catId + '"]');
                        if (row) fadeOutRow(row.closest('tr'));
                    }, 2000);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash me-1"></i> Sil';
                    showAdminToast('danger', 'Hata', data.message || 'Silme başarısız.');
                }
            });
        });
    }

    // ==================== Başvuru Silme (AJAX) ====================
    document.querySelectorAll('.btn-delete-submission').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var el = this;
            var subId = el.dataset.id;
            if (!confirm('Bu kaydı silmek istediğinize emin misiniz?')) return;
            el.disabled = true;
            el.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            adminAjax('delete_submission', { submission_id: subId }, function(data) {
                if (data.success) {
                    el.innerHTML = '<i class="fas fa-check text-success"></i>';
                    el.classList.remove('btn-outline-danger');
                    el.classList.add('btn-outline-success');
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() { fadeOutRow(el.closest('tr')); }, 2000);
                } else {
                    el.disabled = false;
                    el.innerHTML = '<i class="fas fa-trash"></i>';
                    showAdminToast('danger', 'Hata', data.message || 'Silme başarısız.');
                }
            });
        });
    });

    // ==================== Başvuru Durumu Güncelle (Modal Select AJAX) ====================
    var subStatusSelect = document.getElementById('subStatusSelect');
    if (subStatusSelect) {
        subStatusSelect.addEventListener('change', function() {
            var select = this;
            var subId = document.getElementById('subStatusId').value;
            var newStatus = select.value;
            if (!subId) return;
            select.disabled = true;
            adminAjax('update_submission_status', { submission_id: subId, new_status: newStatus }, function(data) {
                select.disabled = false;
                if (data.success) {
                    showAdminToast('success', 'Başarılı', data.message);
                    // Tablodaki badge'i güncelle
                    var statusColors = { yeni: 'danger', okundu: 'warning', tamamlandi: 'success' };
                    var statusLabels = { yeni: 'Yeni', okundu: 'Okundu', tamamlandi: 'Tamamlandı' };
                    var detailBtn = document.querySelector('button[onclick="showSubmissionDetail(' + subId + ')"]');
                    if (detailBtn) {
                        var badge = detailBtn.closest('tr').querySelector('.badge');
                        if (badge) {
                            badge.className = 'badge bg-' + (statusColors[newStatus] || 'secondary');
                            badge.textContent = statusLabels[newStatus] || newStatus;
                        }
                    }
                    // _submissions objesini güncelle
                    if (typeof _submissions !== 'undefined' && _submissions[subId]) {
                        _submissions[subId].status = newStatus;
                    }
                } else {
                    showAdminToast('danger', 'Hata', data.message || 'Güncelleme başarısız.');
                }
            });
        });
    }

    // ==================== Ayarlar Formu (AJAX) ====================
    var settingsForm = document.getElementById('settingsForm');
    if (settingsForm) {
        settingsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var form = this;
            var btn = form.querySelector('button[type="submit"]');
            var origHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Kaydediliyor...';
            var formData = new FormData(form);
            adminAjax('save_settings', formData, function(data) {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check me-1"></i> Kaydedildi!';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-success');
                    showAdminToast('success', 'Başarılı', data.message);
                    setTimeout(function() {
                        btn.disabled = false;
                        btn.innerHTML = origHTML;
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-primary');
                    }, 2000);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = origHTML;
                    showAdminToast('danger', 'Hata', data.message || 'Kaydetme başarısız.');
                }
            });
        });
    }
});

// ==================== Başvuru Detay ====================
<?php
$allSubsForJs = [];
if (in_array($currentPage, ['mesajlar', 'basvurular'])) {
    $subsSource = ($currentPage === 'mesajlar') ? ($messages ?? []) : ($allSubmissions ?? []);
    foreach ($subsSource as $s) {
        $allSubsForJs[$s['id']] = $s;
        $allSubsForJs[$s['id']]['form_data_parsed'] = json_decode($s['form_data'], true) ?: [];
    }
}
?>
var _submissions = <?php echo json_encode($allSubsForJs, JSON_UNESCAPED_UNICODE); ?>;
var _fieldLabels = {
    tc_kimlik: 'TC Kimlik', plaka: 'Plaka', telefon: 'Telefon', email: 'E-posta',
    ad_soyad: 'Ad Soyad', adsoyad: 'Ad Soyad', dogum_tarihi: 'Doğum Tarihi',
    ruhsat_sahibi: 'Ruhsat Sahibi', ruhsat_foto: 'Ruhsat Fotoğrafı',
    arac_engeli: 'Araç Engeli', imm_teminat: 'İMM Teminat', ikame_arac: 'İkame Araç',
    kasko_tipi: 'Kasko Tipi', il: 'İl', ilce: 'İlçe', bucak: 'Bucak/Köy', mahalle: 'Mahalle',
    cadde_sokak: 'Cadde/Sokak', bina_no: 'Bina No', ic_kapi_no: 'İç Kapı No', adres_kodu: 'Adres Kodu (UAVT)',
    yapi_tarzi: 'Yapı Tarzı', brut_metrekare: 'Brüt m²',
    bina_insa_yili: 'Bina İnşa Yılı', bina_kat_sayisi: 'Bina Kat Sayısı',
    bina_hasar_durumu: 'Bina Hasar Durumu', bulundugu_kat: 'Bulunduğu Kat',
    sigorta_sifati: 'Sigorta Ettiren Sıfatı', kullanim_sekli: 'Kullanım Şekli',
    bina_degeri: 'Bina Değeri', esya_degeri: 'Eşya Değeri', cam_degeri: 'Cam Değeri',
    demirbas_degeri: 'Demirbaşlar', ucuncu_sahis: '3. Şahıs Sorumluluk',
    banka_serhi: 'Banka/Kurum Şerhi', banka_adi: 'Banka Adı', sube_adi: 'Şube Adı',
    arac_markasi: 'Araç Markası', sure: 'Süre', ulke: 'Ülke',
    seyahat_baslangic: 'Seyahat Başlangıç', seyahat_bitis: 'Seyahat Bitiş',
    bolge: 'Bölge', plan_tipi: 'Plan Tipi', plan: 'Plan', konut_tipi: 'Konut Tipi',
    telefon_markasi: 'Telefon Markası', imei: 'IMEI', hayvan_turu: 'Hayvan Türü',
    hayvan_yasi: 'Hayvan Yaşı', meslek: 'Meslek', konu: 'Konu', mesaj: 'Mesaj',
    egitim: 'Eğitim', deneyim: 'Sigorta Deneyimi', ofis: 'Mevcut Ofis',
    motivasyon: 'Motivasyon', police_no: 'Poliçe No', police_turu: 'Poliçe Türü',
    iptal_sebebi: 'İptal Sebebi', aciklama: 'Açıklama', tc_vergi_no: 'TC/Vergi No',
    cep_telefonu: 'Telefon', adres: 'Adres', kvkk: 'KVKK Onay',
    kampanya_id: 'Kampanya ID', kampanya_adi: 'Kampanya Adı', eposta: 'E-posta', not: 'Not'
};
var _fieldGroups = {
    'Kişisel Bilgiler': ['ad_soyad','adsoyad','ruhsat_sahibi','tc_kimlik','tc_vergi_no','dogum_tarihi','telefon','cep_telefonu','email'],
    'Adres Bilgileri': ['il','ilce','bucak','mahalle','cadde_sokak','bina_no','ic_kapi_no','adres_kodu','adres'],
    'Bina Bilgileri': ['yapi_tarzi','brut_metrekare','bina_insa_yili','bina_kat_sayisi','bina_hasar_durumu','bulundugu_kat','sigorta_sifati','kullanim_sekli','konut_tipi'],
    'Değer Bilgileri': ['bina_degeri','esya_degeri','cam_degeri','demirbas_degeri','ucuncu_sahis'],
    'Banka/Kurum Şerhi': ['banka_serhi','banka_adi','sube_adi'],
    'Araç Bilgileri': ['plaka','arac_markasi','ruhsat_foto','arac_engeli','kasko_tipi','ikame_arac','imm_teminat'],
    'Sigorta Bilgileri': ['plan','plan_tipi','sure','bolge','ulke','seyahat_baslangic','seyahat_bitis','konut_tipi','telefon_markasi','imei','hayvan_turu','hayvan_yasi','meslek'],
    'Diğer': ['konu','mesaj','aciklama','motivasyon','egitim','deneyim','ofis','police_no','police_turu','iptal_sebebi','kvkk'],
    'Kampanya': ['kampanya_id','kampanya_adi','not']
};
var _groupIcons = {
    'Kişisel Bilgiler': 'fas fa-user',
    'Adres Bilgileri': 'fas fa-map-marker-alt',
    'Bina Bilgileri': 'fas fa-building',
    'Araç Bilgileri': 'fas fa-car',
    'Sigorta Bilgileri': 'fas fa-shield-alt',
    'Değer Bilgileri': 'fas fa-money-bill-wave',
    'Banka/Kurum Şerhi': 'fas fa-university',
    'Diğer': 'fas fa-info-circle'
};
var _groupColors = {
    'Kişisel Bilgiler': '#0d6efd',
    'Adres Bilgileri': '#198754',
    'Bina Bilgileri': '#6f42c1',
    'Araç Bilgileri': '#fd7e14',
    'Sigorta Bilgileri': '#0dcaf0',
    'Değer Bilgileri': '#dc3545',
    'Banka/Kurum Şerhi': '#20c997',
    'Diğer': '#6c757d'
};
var _typeLabels = <?php echo json_encode($formTypeLabels ?? [], JSON_UNESCAPED_UNICODE); ?>;

function showSubmissionDetail(id) {
    var sub = _submissions[id];
    if (!sub) return;
    var data = sub.form_data_parsed || {};
    var typeLabel = _typeLabels[sub.form_type] || sub.form_type;
    
    var html = '<div class="d-flex align-items-center justify-content-between mb-3 pb-2" style="border-bottom:2px solid #e9ecef;">';
    html += '<div><span class="badge bg-primary px-3 py-2" style="font-size:13px;">' + typeLabel + '</span></div>';
    html += '<div class="text-end"><small class="text-muted"><i class="fas fa-clock me-1"></i>' + sub.created_at + '</small>';
    html += '<br><small class="text-muted"><i class="fas fa-globe me-1"></i>IP: ' + (sub.visitor_ip || '-') + '</small></div>';
    html += '</div>';

    var usedKeys = {};
    for (var groupName in _fieldGroups) {
        var fields = _fieldGroups[groupName];
        var groupRows = '';
        for (var i = 0; i < fields.length; i++) {
            var key = fields[i];
            if (!data[key] || data[key] === '') continue;
            usedKeys[key] = true;
            var label = _fieldLabels[key] || key;
            var val = data[key].replace ? data[key].replace(/</g,'&lt;').replace(/>/g,'&gt;') : data[key];
            if (key === 'mesaj' || key === 'motivasyon' || key === 'aciklama') {
                val = '<div style="white-space:pre-wrap;max-height:150px;overflow:auto;background:#f8f9fa;padding:8px;border-radius:6px;font-size:12px;">' + val + '</div>';
            }
            if (key === 'ruhsat_foto' && data[key]) {
                val = '<a href="<?php echo SITE_URL; ?>/' + data[key].replace(/</g,'&lt;').replace(/>/g,'&gt;') + '" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="fas fa-file-image me-1"></i>Görüntüle</a>';
            }
            if (key === 'adres_kodu') {
                val = '<span class="badge bg-dark px-2 py-1" style="font-size:13px;letter-spacing:1px;">' + val + '</span>';
            }
            groupRows += '<tr><td class="text-muted" style="width:140px;font-size:12px;padding:6px 10px;">' + label + '</td>';
            groupRows += '<td style="font-size:13px;font-weight:500;padding:6px 10px;">' + val + '</td></tr>';
        }
        if (!groupRows) continue;
        var color = _groupColors[groupName] || '#6c757d';
        var icon = _groupIcons[groupName] || 'fas fa-info-circle';
        html += '<div class="mb-3">';
        html += '<div class="d-flex align-items-center mb-1"><span style="width:4px;height:18px;background:' + color + ';border-radius:2px;display:inline-block;margin-right:8px;"></span>';
        html += '<small class="fw-bold text-uppercase" style="font-size:11px;color:' + color + ';letter-spacing:.5px;"><i class="' + icon + ' me-1"></i>' + groupName + '</small></div>';
        html += '<table class="table table-sm mb-0" style="background:#fafbfc;border-radius:8px;overflow:hidden;">' + groupRows + '</table>';
        html += '</div>';
    }

    var ungrouped = '';
    for (var key in data) {
        if (!data[key] || data[key] === '' || usedKeys[key]) continue;
        var label = _fieldLabels[key] || key;
        var val = data[key].replace ? data[key].replace(/</g,'&lt;').replace(/>/g,'&gt;') : data[key];
        ungrouped += '<tr><td class="text-muted" style="width:140px;font-size:12px;padding:6px 10px;">' + label + '</td>';
        ungrouped += '<td style="font-size:13px;font-weight:500;padding:6px 10px;">' + val + '</td></tr>';
    }
    if (ungrouped) {
        html += '<div class="mb-3">';
        html += '<div class="d-flex align-items-center mb-1"><span style="width:4px;height:18px;background:#adb5bd;border-radius:2px;display:inline-block;margin-right:8px;"></span>';
        html += '<small class="fw-bold text-uppercase" style="font-size:11px;color:#6c757d;letter-spacing:.5px;"><i class="fas fa-ellipsis-h me-1"></i>Ek Bilgiler</small></div>';
        html += '<table class="table table-sm mb-0" style="background:#fafbfc;border-radius:8px;overflow:hidden;">' + ungrouped + '</table>';
        html += '</div>';
    }
    
    document.getElementById('subDetailBody').innerHTML = html;
    document.getElementById('subDetailId').textContent = '#' + id;
    document.getElementById('subStatusId').value = id;
    var statusSelect = document.getElementById('subStatusSelect');
    if (statusSelect) statusSelect.value = sub.status;
    
    var phone = '<?php echo str_replace('+', '', SITE_PHONE_RAW); ?>';
    var waMsg = 'Merhaba, ' + typeLabel + ' başvurunuz (#' + id + ') hakkında bilgi vermek istiyoruz.';
    document.getElementById('subWhatsappLink').href = 'https://wa.me/' + (data.telefon || data.cep_telefonu || phone).replace(/[^0-9]/g,'') + '?text=' + encodeURIComponent(waMsg);
    
    new bootstrap.Modal(document.getElementById('submissionDetailModal')).show();
    
    // Otomatik okundu yap
    if (sub.status === 'yeni') {
        adminAjax('update_submission_status', { submission_id: id, new_status: 'okundu' }, function(data) {
            if (data.success) {
                sub.status = 'okundu';
                var row = document.querySelector('button[onclick="showSubmissionDetail(' + id + ')"]');
                if (row) {
                    var badge = row.closest('tr').querySelector('.badge');
                    if (badge) { badge.className = 'badge bg-warning'; badge.textContent = 'Okundu'; }
                }
            }
        });
    }
}

// ==================== Kampanya Toggle (AJAX) ====================
document.querySelectorAll('.toggle-campaign').forEach(function(el) {
    el.addEventListener('change', function() {
        var campId = el.dataset.id;
        var val = el.checked ? 1 : 0;
        el.disabled = true;
        adminAjax('toggle_campaign', { campaign_id: campId, is_active: val }, function(data) {
            el.disabled = false;
            if (data.success) {
                flashRow(el.closest('tr'), val ? '#d1e7dd' : '#f8d7da');
                showAdminToast('success', 'Başarılı', data.message);
            } else {
                el.checked = !el.checked;
                showAdminToast('danger', 'Hata', data.message || 'İşlem başarısız.');
            }
        });
    });
});

// Kampanya sil
document.querySelectorAll('.btn-delete-campaign').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('deleteCampaignId').value = btn.dataset.id;
        document.getElementById('deleteCampaignName').textContent = btn.dataset.name;
        new bootstrap.Modal(document.getElementById('deleteCampaignModal')).show();
    });
});
var confirmDeleteCampaign = document.getElementById('confirmDeleteCampaign');
if (confirmDeleteCampaign) {
    confirmDeleteCampaign.addEventListener('click', function() {
        var campId = document.getElementById('deleteCampaignId').value;
        adminAjax('delete_campaign', { campaign_id: campId }, function(data) {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('deleteCampaignModal')).hide();
                var row = document.querySelector('.btn-delete-campaign[data-id="' + campId + '"]');
                if (row) row.closest('tr').remove();
                showAdminToast('success', 'Başarılı', data.message);
            }
        });
    });
}

// ==================== İkon Seçici ====================
(function() {
    var campaignIcons = [
        'fas fa-car','fas fa-car-side','fas fa-truck','fas fa-motorcycle','fas fa-bus','fas fa-taxi',
        'fas fa-shuttle-van','fas fa-ambulance','fas fa-ship','fas fa-plane','fas fa-plane-departure',
        'fas fa-helicopter','fas fa-bicycle','fas fa-gas-pump','fas fa-road','fas fa-traffic-light',
        'fas fa-heartbeat','fas fa-heart','fas fa-hospital','fas fa-medkit','fas fa-pills',
        'fas fa-stethoscope','fas fa-syringe','fas fa-tooth','fas fa-baby','fas fa-wheelchair',
        'fas fa-home','fas fa-building','fas fa-city','fas fa-warehouse','fas fa-store',
        'fas fa-hotel','fas fa-church','fas fa-school','fas fa-university','fas fa-industry',
        'fas fa-shield-alt','fas fa-shield-virus','fas fa-lock','fas fa-key','fas fa-umbrella',
        'fas fa-user-shield','fas fa-life-ring','fas fa-hard-hat','fas fa-fire-extinguisher',
        'fas fa-bolt','fas fa-battery-full','fas fa-charging-station','fas fa-plug','fas fa-solar-panel',
        'fas fa-seedling','fas fa-leaf','fas fa-tree','fas fa-water','fas fa-wind',
        'fas fa-paw','fas fa-dog','fas fa-cat','fas fa-horse','fas fa-fish',
        'fas fa-dove','fas fa-spider','fas fa-bug','fas fa-hippo','fas fa-dragon',
        'fas fa-globe','fas fa-globe-europe','fas fa-map-marked-alt','fas fa-route','fas fa-passport',
        'fas fa-suitcase','fas fa-luggage-cart','fas fa-compass','fas fa-mountain','fas fa-campground',
        'fas fa-phone','fas fa-mobile-alt','fas fa-laptop','fas fa-tablet-alt','fas fa-tv',
        'fas fa-desktop','fas fa-camera','fas fa-gamepad','fas fa-headphones','fas fa-microchip',
        'fas fa-money-bill-wave','fas fa-coins','fas fa-credit-card','fas fa-wallet','fas fa-piggy-bank',
        'fas fa-hand-holding-usd','fas fa-chart-line','fas fa-chart-bar','fas fa-chart-pie','fas fa-percentage',
        'fas fa-tag','fas fa-tags','fas fa-gift','fas fa-award','fas fa-trophy',
        'fas fa-medal','fas fa-star','fas fa-crown','fas fa-gem','fas fa-certificate',
        'fas fa-bullhorn','fas fa-bell','fas fa-megaphone','fas fa-newspaper','fas fa-ad',
        'fas fa-users','fas fa-user','fas fa-user-tie','fas fa-people-carry','fas fa-handshake',
        'fas fa-briefcase','fas fa-file-contract','fas fa-file-invoice-dollar','fas fa-file-alt','fas fa-clipboard',
        'fas fa-gavel','fas fa-balance-scale','fas fa-landmark','fas fa-flag','fas fa-ribbon',
        'fas fa-band-aid','fas fa-procedures','fas fa-x-ray','fas fa-dna','fas fa-brain',
        'fas fa-tractor','fas fa-tools','fas fa-wrench','fas fa-hammer','fas fa-paint-roller',
        'fas fa-clock','fas fa-hourglass-half','fas fa-calendar-alt','fas fa-stopwatch','fas fa-history',
        'fas fa-sun','fas fa-moon','fas fa-cloud','fas fa-cloud-rain','fas fa-snowflake',
        'fas fa-fire','fas fa-bomb','fas fa-radiation','fas fa-biohazard','fas fa-skull-crossbones',
        'fas fa-anchor','fas fa-ring','fas fa-glasses','fas fa-hat-wizard','fas fa-mask',
        'fas fa-rocket','fas fa-satellite','fas fa-atom','fas fa-magnet','fas fa-microscope',
        'fas fa-recycle','fas fa-trash-alt','fas fa-dumpster-fire','fas fa-broom','fas fa-fan',
        'fas fa-check-circle','fas fa-info-circle','fas fa-exclamation-triangle','fas fa-question-circle','fas fa-times-circle',
        'fas fa-plus-circle','fas fa-minus-circle','fas fa-arrow-circle-right','fas fa-thumbs-up','fas fa-thumbs-down'
    ];

    var btnOpen = document.getElementById('btnOpenIconPicker');
    if (!btnOpen) return;

    var iconGrid = document.getElementById('iconGrid');
    var iconSearch = document.getElementById('iconSearchInput');
    var iconInput = document.getElementById('campIconInput');
    var iconPreview = document.getElementById('campIconPreview');
    var iconModalEl = document.getElementById('iconPickerModal');
    var iconModal = null;

    function getIconModal() {
        if (!iconModal && typeof bootstrap !== 'undefined') {
            iconModal = new bootstrap.Modal(iconModalEl);
        }
        return iconModal;
    }

    function renderIcons(filter) {
        filter = (filter || '').toLowerCase();
        iconGrid.innerHTML = '';
        var filtered = campaignIcons.filter(function(ic) {
            return !filter || ic.toLowerCase().indexOf(filter) > -1;
        });
        filtered.forEach(function(ic) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-outline-secondary icon-pick-btn' + (iconInput.value === ic ? ' active btn-primary text-white' : '');
            btn.style.cssText = 'width:48px;height:48px;display:flex;align-items:center;justify-content:center;font-size:18px;border-radius:10px;';
            btn.innerHTML = '<i class="' + ic + '"></i>';
            btn.title = ic;
            btn.addEventListener('click', function() {
                iconInput.value = ic;
                iconPreview.innerHTML = '<i class="' + ic + '"></i>';
                iconGrid.querySelectorAll('.icon-pick-btn').forEach(function(b) { b.classList.remove('active','btn-primary','text-white'); b.classList.add('btn-outline-secondary'); });
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('active','btn-primary','text-white');
                setTimeout(function() { var m = getIconModal(); if(m) m.hide(); }, 200);
            });
            iconGrid.appendChild(btn);
        });
        if (!filtered.length) iconGrid.innerHTML = '<p class="text-muted small py-3">İkon bulunamadı.</p>';
    }

    btnOpen.addEventListener('click', function() {
        iconSearch.value = '';
        renderIcons('');
        var m = getIconModal();
        if (m) m.show();
        setTimeout(function() { iconSearch.focus(); }, 300);
    });

    iconSearch.addEventListener('input', function() {
        renderIcons(this.value);
    });

    // ==================== Renk Seçici ====================
    var colorPicker = document.getElementById('campColorPicker');
    var colorInput = document.getElementById('campBgColorInput');
    var swatches = document.querySelectorAll('.camp-color-swatch');

    if (colorPicker && colorInput) {
        colorPicker.addEventListener('input', function() {
            colorInput.value = this.value;
        });
        colorInput.addEventListener('input', function() {
            if (/^#[0-9a-fA-F]{6}$/.test(this.value)) {
                colorPicker.value = this.value;
            }
        });
        swatches.forEach(function(sw) {
            sw.addEventListener('click', function() {
                var c = this.dataset.color;
                colorInput.value = c;
                colorPicker.value = c;
                swatches.forEach(function(s) { s.style.borderColor = '#dee2e6'; s.style.transform = 'scale(1)'; });
                sw.style.borderColor = '#000';
                sw.style.transform = 'scale(1.2)';
            });
        });
    }
})();
</script>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>
