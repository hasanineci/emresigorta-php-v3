<?php
/**
 * Emre Sigorta - Veritabanı Bağlantısı
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'webhasan');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]);
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            die('Veritabanı bağlantı hatası.');
        }
    }
    return $pdo;
}

// Site ayarını veritabanından çek

// Türkçe tarih formatlama
function turkishDate($datetime, $format = 'long') {
    if (!$datetime) return '';
    $ts = strtotime($datetime);
    $aylar = ['', 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
    $gun = date('j', $ts);
    $ay = $aylar[(int)date('n', $ts)];
    $yil = date('Y', $ts);
    $saat = date('H:i', $ts);
    if ($format === 'short') return $gun . ' ' . $ay . ' ' . $yil;
    if ($format === 'time') return $saat;
    return $gun . ' ' . $ay . ' ' . $yil . ', ' . $saat;
}
function getSetting($key, $default = '') {
    static $cache = [];
    if (isset($cache[$key])) return $cache[$key];
    
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        $cache[$key] = ($val !== false) ? $val : $default;
        return $cache[$key];
    } catch (Exception $e) {
        return $default;
    }
}

// Tüm site ayarlarını çek
function getAllSettings() {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT setting_key, setting_value, setting_label, setting_group FROM site_settings ORDER BY setting_group, id");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row;
        }
        return $settings;
    } catch (Exception $e) {
        return [];
    }
}

// Site ayarını güncelle
function updateSetting($key, $value) {
    try {
        $db = getDB();
        $stmt = $db->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
        return $stmt->execute([$value, $key]);
    } catch (Exception $e) {
        return false;
    }
}

// Sayfa aktif mi kontrol (website tarafı için)
function isPageActive($slug) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT is_active FROM pages WHERE slug = ?");
        $stmt->execute([$slug]);
        $result = $stmt->fetchColumn();
        return $result === false ? true : (bool)$result; // yoksa aktif say
    } catch (Exception $e) {
        return true;
    }
}

// Tüm sayfaları çek
function getAllPages() {
    try {
        $db = getDB();
        return $db->query("SELECT * FROM pages ORDER BY sort_order ASC")->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Tek sayfa çek
function getPage($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

// Aktif sayfaları kategoriye göre grupla (header/footer menü için)
function getActivePagesByCategory() {
    static $cache = null;
    if ($cache !== null) return $cache;
    try {
        $db = getDB();
        $stmt = $db->query("SELECT slug, title, category, sort_order FROM pages WHERE is_active = 1 ORDER BY sort_order ASC");
        $pages = $stmt->fetchAll();
        $grouped = [];
        foreach ($pages as $p) {
            $grouped[$p['category']][] = $p;
        }
        $cache = $grouped;
        return $cache;
    } catch (Exception $e) {
        return [];
    }
}

// Sonraki sıra numarasını al
function getNextSortOrder() {
    try {
        $db = getDB();
        $max = $db->query("SELECT MAX(sort_order) FROM pages")->fetchColumn();
        return ($max !== null && $max !== false) ? (int)$max + 1 : 1;
    } catch (Exception $e) {
        return 1;
    }
}

// ==================== Kullanıcı Yönetimi ====================

// Tüm kullanıcıları çek (şifre hariç)
function getAllUsers() {
    try {
        $db = getDB();
        return $db->query("SELECT id, username, full_name, email, role, last_login, created_at FROM admins ORDER BY id ASC")->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Tek kullanıcı çek (şifre hariç)
function getUser($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, username, full_name, email, role, last_login, created_at FROM admins WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

// Kullanıcı oluştur
function createUser($data) {
    $db = getDB();
    $hash = password_hash($data['password'], PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO admins (username, password, full_name, email, role) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$data['username'], $hash, $data['full_name'], $data['email'], $data['role']]);
}

// Kullanıcı güncelle (şifre opsiyonel)
function updateUser($id, $data) {
    $db = getDB();
    if (!empty($data['password'])) {
        $hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $db->prepare("UPDATE admins SET username=?, password=?, full_name=?, email=?, role=?, updated_at=NOW() WHERE id=?");
        return $stmt->execute([$data['username'], $hash, $data['full_name'], $data['email'], $data['role'], $id]);
    } else {
        $stmt = $db->prepare("UPDATE admins SET username=?, full_name=?, email=?, role=?, updated_at=NOW() WHERE id=?");
        return $stmt->execute([$data['username'], $data['full_name'], $data['email'], $data['role'], $id]);
    }
}

// Kullanıcı sil
function deleteUser($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM admins WHERE id = ? AND id != 1");
    return $stmt->execute([$id]);
}

// ==================== Form Başvuruları ====================

// Yeni form başvurusu kaydet
function createFormSubmission($formType, $formData, $visitorName = null, $visitorPhone = null) {
    $db = getDB();
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    // Sadece ilk IP'yi al (proxy arkasında virgülle ayrılmış olabilir)
    $ip = explode(',', $ip)[0];
    $ip = filter_var(trim($ip), FILTER_VALIDATE_IP) ? trim($ip) : '';
    
    $stmt = $db->prepare("INSERT INTO form_submissions (form_type, form_data, visitor_name, visitor_phone, visitor_ip) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$formType, json_encode($formData, JSON_UNESCAPED_UNICODE), $visitorName, $visitorPhone, $ip]);
    return $db->lastInsertId();
}

// Tüm başvuruları çek (filtre ve sayfalama)
function getFormSubmissions($filters = []) {
    $db = getDB();
    $where = [];
    $params = [];
    
    if (!empty($filters['form_type'])) {
        $where[] = "form_type = ?";
        $params[] = $filters['form_type'];
    }
    if (!empty($filters['status'])) {
        $where[] = "status = ?";
        $params[] = $filters['status'];
    }
    
    $sql = "SELECT * FROM form_submissions";
    if ($where) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }
    $sql .= " ORDER BY created_at DESC";
    
    if (!empty($filters['limit'])) {
        $sql .= " LIMIT " . (int)$filters['limit'];
        if (!empty($filters['offset'])) {
            $sql .= " OFFSET " . (int)$filters['offset'];
        }
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Başvuru sayısı
function getFormSubmissionCount($filters = []) {
    $db = getDB();
    $where = [];
    $params = [];
    
    if (!empty($filters['form_type'])) {
        $where[] = "form_type = ?";
        $params[] = $filters['form_type'];
    }
    if (!empty($filters['status'])) {
        $where[] = "status = ?";
        $params[] = $filters['status'];
    }
    
    $sql = "SELECT COUNT(*) FROM form_submissions";
    if ($where) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
}

// Tek başvuru çek
function getFormSubmission($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM form_submissions WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Başvuru durumunu güncelle
function updateFormSubmissionStatus($id, $status) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE form_submissions SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $id]);
}

// Başvuru sil
function deleteFormSubmission($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM form_submissions WHERE id = ?");
    return $stmt->execute([$id]);
}

// ==================== İş Ortakları ====================

// Tüm iş ortaklarını çek
function getAllPartners($onlyActive = false) {
    $db = getDB();
    $sql = "SELECT * FROM partners";
    if ($onlyActive) $sql .= " WHERE is_active = 1";
    $sql .= " ORDER BY sort_order ASC, id ASC";
    return $db->query($sql)->fetchAll();
}

// Tek iş ortağı çek
function getPartner($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM partners WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// İş ortağı kaydet (ekle/güncelle)
function savePartner($data, $id = 0) {
    $db = getDB();
    if ($id > 0) {
        $stmt = $db->prepare("UPDATE partners SET name=?, logo=?, website=?, sort_order=?, is_active=?, updated_at=NOW() WHERE id=?");
        return $stmt->execute([$data['name'], $data['logo'], $data['website'], $data['sort_order'], $data['is_active'], $id]);
    } else {
        $stmt = $db->prepare("INSERT INTO partners (name, logo, website, sort_order, is_active) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['logo'], $data['website'], $data['sort_order'], $data['is_active']]);
        return $db->lastInsertId();
    }
}

// İş ortağı sil
function deletePartner($id) {
    $db = getDB();
    // Önce logo dosyasını sil
    $partner = getPartner($id);
    if ($partner && !empty($partner['logo'])) {
        $logoPath = dirname(__DIR__) . '/' . $partner['logo'];
        if (file_exists($logoPath)) {
            unlink($logoPath);
        }
    }
    $stmt = $db->prepare("DELETE FROM partners WHERE id = ?");
    return $stmt->execute([$id]);
}

// ==================== Sosyal Medya Fonksiyonları ====================

function getAllSocialMedia($onlyActive = false) {
    try {
        $db = getDB();
        $sql = "SELECT * FROM social_media";
        if ($onlyActive) $sql .= " WHERE is_active = 1";
        $sql .= " ORDER BY sort_order ASC, id ASC";
        return $db->query($sql)->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function getSocialMedia($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM social_media WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

function saveSocialMedia($data, $id = 0) {
    $db = getDB();
    if ($id > 0) {
        $stmt = $db->prepare("UPDATE social_media SET platform=?, label=?, icon=?, url=?, color=?, is_active=?, sort_order=?, updated_at=NOW() WHERE id=?");
        return $stmt->execute([$data['platform'], $data['label'], $data['icon'], $data['url'], $data['color'], $data['is_active'], $data['sort_order'], $id]);
    } else {
        $stmt = $db->prepare("INSERT INTO social_media (platform, label, icon, url, color, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['platform'], $data['label'], $data['icon'], $data['url'], $data['color'], $data['is_active'], $data['sort_order']]);
    }
}

function deleteSocialMedia($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM social_media WHERE id = ?");
    return $stmt->execute([$id]);
}

// ==================== Müşteri Yorumları ====================

function getAllTestimonials($onlyActive = false) {
    try {
        $db = getDB();
        $sql = "SELECT * FROM testimonials";
        if ($onlyActive) $sql .= " WHERE is_active = 1";
        $sql .= " ORDER BY sort_order ASC, id ASC";
        return $db->query($sql)->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function getTestimonial($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM testimonials WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

function saveTestimonial($data, $id = 0) {
    $db = getDB();
    if ($id > 0) {
        $stmt = $db->prepare("UPDATE testimonials SET author_name=?, author_title=?, rating=?, comment=?, avatar_color=?, is_active=?, sort_order=?, updated_at=NOW() WHERE id=?");
        return $stmt->execute([$data['author_name'], $data['author_title'], $data['rating'], $data['comment'], $data['avatar_color'], $data['is_active'], $data['sort_order'], $id]);
    } else {
        $stmt = $db->prepare("INSERT INTO testimonials (author_name, author_title, rating, comment, avatar_color, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['author_name'], $data['author_title'], $data['rating'], $data['comment'], $data['avatar_color'], $data['is_active'], $data['sort_order']]);
    }
}

function deleteTestimonial($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM testimonials WHERE id = ?");
    return $stmt->execute([$id]);
}

// ==================== SSS Kategorileri ====================

function getAllFaqCategories($onlyActive = false) {
    try {
        $db = getDB();
        $sql = "SELECT * FROM faq_categories";
        if ($onlyActive) $sql .= " WHERE is_active = 1";
        $sql .= " ORDER BY sort_order ASC, id ASC";
        return $db->query($sql)->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function getFaqCategory($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM faq_categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

function saveFaqCategory($data, $id = 0) {
    $db = getDB();
    if ($id > 0) {
        $stmt = $db->prepare("UPDATE faq_categories SET name=?, slug=?, sort_order=?, is_active=?, updated_at=NOW() WHERE id=?");
        return $stmt->execute([$data['name'], $data['slug'], $data['sort_order'], $data['is_active'], $id]);
    } else {
        $stmt = $db->prepare("INSERT INTO faq_categories (name, slug, sort_order, is_active) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['name'], $data['slug'], $data['sort_order'], $data['is_active']]);
    }
}

function deleteFaqCategory($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM faq_categories WHERE id = ?");
    return $stmt->execute([$id]);
}

// ==================== SSS Soruları ====================

function getAllFaqs($filters = []) {
    try {
        $db = getDB();
        $sql = "SELECT f.*, c.name as category_name FROM faqs f LEFT JOIN faq_categories c ON f.category_id = c.id";
        $where = [];
        $params = [];
        if (!empty($filters['category_id'])) {
            $where[] = "f.category_id = ?";
            $params[] = $filters['category_id'];
        }
        if (isset($filters['show_on_homepage'])) {
            $where[] = "f.show_on_homepage = ?";
            $params[] = $filters['show_on_homepage'];
        }
        if (isset($filters['is_active'])) {
            $where[] = "f.is_active = ?";
            $params[] = $filters['is_active'];
        }
        if ($where) $sql .= " WHERE " . implode(' AND ', $where);
        $sql .= " ORDER BY f.sort_order ASC, f.id ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function getFaq($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT f.*, c.name as category_name FROM faqs f LEFT JOIN faq_categories c ON f.category_id = c.id WHERE f.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

function saveFaq($data, $id = 0) {
    $db = getDB();
    if ($id > 0) {
        $stmt = $db->prepare("UPDATE faqs SET category_id=?, question=?, answer=?, show_on_homepage=?, is_active=?, sort_order=?, updated_at=NOW() WHERE id=?");
        return $stmt->execute([$data['category_id'], $data['question'], $data['answer'], $data['show_on_homepage'], $data['is_active'], $data['sort_order'], $id]);
    } else {
        $stmt = $db->prepare("INSERT INTO faqs (category_id, question, answer, show_on_homepage, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['category_id'], $data['question'], $data['answer'], $data['show_on_homepage'], $data['is_active'], $data['sort_order']]);
    }
}

function deleteFaq($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM faqs WHERE id = ?");
    return $stmt->execute([$id]);
}

function getFaqsGroupedByCategory($onlyActive = true) {
    try {
        $db = getDB();
        $catSql = "SELECT * FROM faq_categories" . ($onlyActive ? " WHERE is_active = 1" : "") . " ORDER BY sort_order ASC";
        $categories = $db->query($catSql)->fetchAll();
        $faqSql = "SELECT * FROM faqs" . ($onlyActive ? " WHERE is_active = 1" : "") . " ORDER BY sort_order ASC, id ASC";
        $faqs = $db->query($faqSql)->fetchAll();
        $grouped = [];
        foreach ($categories as $cat) {
            $cat['faqs'] = [];
            foreach ($faqs as $faq) {
                if ($faq['category_id'] == $cat['id']) {
                    $cat['faqs'][] = $faq;
                }
            }
            if (!empty($cat['faqs'])) {
                $grouped[] = $cat;
            }
        }
        return $grouped;
    } catch (Exception $e) {
        return [];
    }
}

// ==================== Blog Kategorileri ====================

function getAllBlogCategories($onlyActive = false) {
    try {
        $db = getDB();
        $sql = "SELECT * FROM blog_categories" . ($onlyActive ? " WHERE is_active = 1" : "") . " ORDER BY sort_order ASC";
        return $db->query($sql)->fetchAll();
    } catch (Exception $e) { return []; }
}

function getBlogCategory($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM blog_categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) { return null; }
}

function saveBlogCategory($data) {
    $db = getDB();
    if (!empty($data['id'])) {
        $stmt = $db->prepare("UPDATE blog_categories SET name=?, slug=?, icon=?, color=?, sort_order=?, is_active=?, updated_at=NOW() WHERE id=?");
        $stmt->execute([$data['name'], $data['slug'], $data['icon'], $data['color'], $data['sort_order'], $data['is_active'], $data['id']]);
    } else {
        $stmt = $db->prepare("INSERT INTO blog_categories (name, slug, icon, color, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['slug'], $data['icon'], $data['color'], $data['sort_order'], $data['is_active']]);
    }
    return true;
}

function deleteBlogCategory($id) {
    $db = getDB();
    $db->prepare("DELETE FROM blog_posts WHERE category_id = ?")->execute([$id]);
    $db->prepare("DELETE FROM blog_categories WHERE id = ?")->execute([$id]);
    return true;
}

// ==================== Blog Yazıları ====================

function getAllBlogPosts($filters = []) {
    try {
        $db = getDB();
        $where = [];
        $params = [];
        if (isset($filters['is_active'])) { $where[] = "p.is_active = ?"; $params[] = $filters['is_active']; }
        if (isset($filters['category_id'])) { $where[] = "p.category_id = ?"; $params[] = $filters['category_id']; }
        if (isset($filters['is_featured'])) { $where[] = "p.is_featured = ?"; $params[] = $filters['is_featured']; }
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon, c.color as category_color FROM blog_posts p LEFT JOIN blog_categories c ON p.category_id = c.id";
        if (!empty($where)) $sql .= " WHERE " . implode(" AND ", $where);
        $sql .= " ORDER BY p.published_at DESC, p.sort_order ASC";
        if (isset($filters['limit'])) { $sql .= " LIMIT " . (int)$filters['limit']; }
        if (isset($filters['offset'])) { $sql .= " OFFSET " . (int)$filters['offset']; }
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) { return []; }
}

function getBlogPostCount($filters = []) {
    try {
        $db = getDB();
        $where = [];
        $params = [];
        if (isset($filters['is_active'])) { $where[] = "is_active = ?"; $params[] = $filters['is_active']; }
        if (isset($filters['category_id'])) { $where[] = "category_id = ?"; $params[] = $filters['category_id']; }
        $sql = "SELECT COUNT(*) FROM blog_posts";
        if (!empty($where)) $sql .= " WHERE " . implode(" AND ", $where);
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    } catch (Exception $e) { return 0; }
}

function getBlogPost($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon, c.color as category_color FROM blog_posts p LEFT JOIN blog_categories c ON p.category_id = c.id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) { return null; }
}

function getBlogPostBySlug($slug) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon, c.color as category_color FROM blog_posts p LEFT JOIN blog_categories c ON p.category_id = c.id WHERE p.slug = ? AND p.is_active = 1");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    } catch (Exception $e) { return null; }
}

function saveBlogPost($data) {
    $db = getDB();
    if (!empty($data['id'])) {
        $stmt = $db->prepare("UPDATE blog_posts SET category_id=?, title=?, slug=?, excerpt=?, content=?, featured_image=?, icon=?, icon_bg=?, reading_time=?, meta_title=?, meta_description=?, is_featured=?, is_active=?, published_at=?, sort_order=?, updated_at=NOW() WHERE id=?");
        $stmt->execute([$data['category_id'], $data['title'], $data['slug'], $data['excerpt'], $data['content'], $data['featured_image'], $data['icon'], $data['icon_bg'], $data['reading_time'], $data['meta_title'], $data['meta_description'], $data['is_featured'], $data['is_active'], $data['published_at'], $data['sort_order'], $data['id']]);
    } else {
        $stmt = $db->prepare("INSERT INTO blog_posts (category_id, title, slug, excerpt, content, featured_image, icon, icon_bg, reading_time, meta_title, meta_description, is_featured, is_active, published_at, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data['category_id'], $data['title'], $data['slug'], $data['excerpt'], $data['content'], $data['featured_image'], $data['icon'], $data['icon_bg'], $data['reading_time'], $data['meta_title'], $data['meta_description'], $data['is_featured'], $data['is_active'], $data['published_at'], $data['sort_order']]);
    }
    return true;
}

function deleteBlogPost($id) {
    $db = getDB();
    // Önce resmi sil
    $post = getBlogPost($id);
    if ($post && $post['featured_image'] && file_exists(__DIR__ . '/../' . $post['featured_image'])) {
        @unlink(__DIR__ . '/../' . $post['featured_image']);
    }
    $db->prepare("DELETE FROM blog_posts WHERE id = ?")->execute([$id]);
    return true;
}

function incrementBlogViews($id) {
    try {
        $db = getDB();
        $db->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = ?")->execute([$id]);
    } catch (Exception $e) {}
}

// ==================== Harici Haberler (Sigortamedya RSS) ====================

function fetchAndCacheNews($feedUrl = 'https://sigortamedya.com.tr/feed/') {
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 15,
            'user_agent' => 'Mozilla/5.0 (compatible; EmreSigorta/1.0)'
        ],
        'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
    ]);
    $xml = @file_get_contents($feedUrl, false, $ctx);
    if (!$xml) throw new Exception('RSS feed alınamadı.');

    libxml_use_internal_errors(true);
    $rss = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    if (!$rss) throw new Exception('RSS parse hatası.');

    $db = getDB();
    $count = 0;
    $namespaces = $rss->channel->item[0]->getNameSpaces(true);

    foreach ($rss->channel->item as $item) {
        $title = trim((string)$item->title);
        $link = trim((string)$item->link);
        $desc = trim((string)$item->description);
        $pubDate = date('Y-m-d H:i:s', strtotime((string)$item->pubDate));
        $author = trim((string)$item->children('dc', true)->creator);

        // content:encoded
        $content = '';
        if (isset($namespaces['content'])) {
            $contentNs = $item->children($namespaces['content']);
            if (isset($contentNs->encoded)) {
                $content = (string)$contentNs->encoded;
            }
        }

        // İlk resmi content'ten çıkar
        $imageUrl = null;
        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $content, $m)) {
            $imageUrl = $m[1];
        }

        // Slug oluştur
        $slug = createNewsSlug($title);

        $stmt = $db->prepare("INSERT INTO external_news (title, slug, excerpt, content, source_url, image_url, author, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title=VALUES(title), excerpt=VALUES(excerpt), content=VALUES(content), image_url=VALUES(image_url), author=VALUES(author), published_at=VALUES(published_at), updated_at=NOW()");
        $stmt->execute([$title, $slug, $desc, $content, $link, $imageUrl, $author, $pubDate]);
        $count++;
    }
    return $count;
}

function createNewsSlug($title) {
    $tr = ['ç'=>'c','Ç'=>'c','ğ'=>'g','Ğ'=>'g','ı'=>'i','İ'=>'i','ö'=>'o','Ö'=>'o','ş'=>'s','Ş'=>'s','ü'=>'u','Ü'=>'u'];
    $slug = strtr($title, $tr);
    $slug = mb_strtolower($slug, 'UTF-8');
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', trim($slug));
    return substr($slug, 0, 200);
}

function getExternalNews($filters = []) {
    try {
        $db = getDB();
        $where = [];
        $params = [];
        if (isset($filters['is_active'])) { $where[] = "is_active = ?"; $params[] = $filters['is_active']; }
        $sql = "SELECT * FROM external_news";
        if ($where) $sql .= " WHERE " . implode(' AND ', $where);
        $sql .= " ORDER BY published_at DESC";
        if (isset($filters['limit'])) $sql .= " LIMIT " . (int)$filters['limit'];
        if (isset($filters['offset'])) $sql .= " OFFSET " . (int)$filters['offset'];
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) { return []; }
}

function getExternalNewsCount($filters = []) {
    try {
        $db = getDB();
        $where = [];
        $params = [];
        if (isset($filters['is_active'])) { $where[] = "is_active = ?"; $params[] = $filters['is_active']; }
        $sql = "SELECT COUNT(*) FROM external_news";
        if ($where) $sql .= " WHERE " . implode(' AND ', $where);
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    } catch (Exception $e) { return 0; }
}

function getExternalNewsById($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM external_news WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) { return null; }
}

function getExternalNewsBySlug($slug) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM external_news WHERE slug = ? AND is_active = 1");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    } catch (Exception $e) { return null; }
}

function toggleExternalNews($id, $isActive) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE external_news SET is_active = ? WHERE id = ?");
    return $stmt->execute([$isActive, $id]);
}

function deleteExternalNews($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM external_news WHERE id = ?");
    return $stmt->execute([$id]);
}

// ==================== KAMPANYALAR ====================
function getAllCampaigns($filters = []) {
    $db = getDB();
    $where = ['1=1'];
    $params = [];
    if (isset($filters['is_active'])) {
        $where[] = 'is_active = ?';
        $params[] = (int)$filters['is_active'];
    }
    if (!empty($filters['active_now'])) {
        $where[] = 'is_active = 1 AND start_date <= CURDATE() AND end_date >= CURDATE()';
    }
    if (!empty($filters['expired'])) {
        $where[] = 'end_date < CURDATE()';
    }
    $sql = "SELECT * FROM campaigns WHERE " . implode(' AND ', $where) . " ORDER BY sort_order ASC, end_date ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getCampaign($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM campaigns WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getCampaignBySlug($slug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM campaigns WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function saveCampaign($data, $id = 0) {
    $db = getDB();
    if ($id > 0) {
        $stmt = $db->prepare("UPDATE campaigns SET title=?, slug=?, description=?, short_description=?, discount_text=?, icon=?, image=?, bg_color=?, features=?, link_url=?, link_text=?, start_date=?, end_date=?, is_active=?, sort_order=?, updated_at=NOW() WHERE id=?");
        $stmt->execute([
            $data['title'], $data['slug'], $data['description'], $data['short_description'],
            $data['discount_text'], $data['icon'], $data['image'], $data['bg_color'],
            $data['features'], $data['link_url'], $data['link_text'],
            $data['start_date'], $data['end_date'], $data['is_active'], $data['sort_order'], $id
        ]);
        return $id;
    } else {
        $stmt = $db->prepare("INSERT INTO campaigns (title, slug, description, short_description, discount_text, icon, image, bg_color, features, link_url, link_text, start_date, end_date, is_active, sort_order) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $data['title'], $data['slug'], $data['description'], $data['short_description'],
            $data['discount_text'], $data['icon'], $data['image'], $data['bg_color'],
            $data['features'], $data['link_url'], $data['link_text'],
            $data['start_date'], $data['end_date'], $data['is_active'], $data['sort_order']
        ]);
        return $db->lastInsertId();
    }
}

function deleteCampaign($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM campaigns WHERE id = ?");
    return $stmt->execute([$id]);
}

function createCampaignSlug($title) {
    $tr = ['ç'=>'c','ğ'=>'g','ı'=>'i','ö'=>'o','ş'=>'s','ü'=>'u','Ç'=>'C','Ğ'=>'G','İ'=>'I','Ö'=>'O','Ş'=>'S','Ü'=>'U'];
    $slug = strtr($title, $tr);
    $slug = strtolower(trim($slug));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}
?>
