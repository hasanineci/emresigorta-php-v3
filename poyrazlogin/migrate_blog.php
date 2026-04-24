<?php
require_once __DIR__ . '/../includes/config.php';
$db = getDB();

// Blog kategorileri tablosu
$db->exec("CREATE TABLE IF NOT EXISTS blog_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL,
    icon VARCHAR(100) DEFAULT NULL,
    color VARCHAR(20) DEFAULT '#0066cc',
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Blog yazıları tablosu
$db->exec("CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT DEFAULT NULL,
    title VARCHAR(500) NOT NULL,
    slug VARCHAR(500) NOT NULL,
    excerpt TEXT DEFAULT NULL,
    content LONGTEXT DEFAULT NULL,
    featured_image VARCHAR(500) DEFAULT NULL,
    icon VARCHAR(100) DEFAULT NULL,
    icon_bg VARCHAR(50) DEFAULT NULL,
    reading_time INT DEFAULT 5,
    meta_title VARCHAR(500) DEFAULT NULL,
    meta_description TEXT DEFAULT NULL,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    views INT DEFAULT 0,
    published_at DATE DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_slug (slug),
    FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

echo "Tablolar olusturuldu.\n";

// Seed kategoriler
$cats = [
    ['Trafik Sigortası', 'trafik-sigortasi', 'fas fa-car-crash', '#0066cc', 1],
    ['Sağlık Sigortası', 'saglik-sigortasi', 'fas fa-heartbeat', '#28a745', 2],
    ['Konut Sigortası', 'konut-sigortasi', 'fas fa-home', '#ff6600', 3],
    ['Kasko', 'kasko', 'fas fa-shield-alt', '#6f42c1', 4],
    ['Seyahat Sigortası', 'seyahat-sigortasi', 'fas fa-plane', '#e83e8c', 5],
    ['Elektrikli Araç', 'elektrikli-arac', 'fas fa-bolt', '#17a2b8', 6],
    ['Cep Telefonu', 'cep-telefonu', 'fas fa-mobile-alt', '#fd7e14', 7],
    ['Evcil Hayvan', 'evcil-hayvan', 'fas fa-paw', '#20c997', 8],
    ['Genel Bilgi', 'genel-bilgi', 'fas fa-balance-scale', '#007bff', 9],
];

$catStmt = $db->prepare("INSERT IGNORE INTO blog_categories (name, slug, icon, color, sort_order) VALUES (?, ?, ?, ?, ?)");
foreach ($cats as $c) {
    $catStmt->execute($c);
}
echo count($cats) . " kategori eklendi.\n";

// Kategori id'lerini çek
$catMap = [];
foreach ($db->query("SELECT id, slug FROM blog_categories")->fetchAll() as $r) {
    $catMap[$r['slug']] = $r['id'];
}

// Seed blog yazıları
$posts = [
    [
        'cat' => 'trafik-sigortasi',
        'title' => '2025 Yılı Trafik Sigortası Fiyatları ve Değişiklikler',
        'slug' => '2025-trafik-sigortasi-fiyatlari',
        'excerpt' => '2025 yılında trafik sigortası primlerinde yapılan değişiklikler, yeni düzenlemeler ve tasarruf yöntemleri hakkında bilmeniz gereken her şey.',
        'content' => '<h2>2025 Trafik Sigortası Fiyatları</h2><p>2025 yılında trafik sigortası primlerinde önemli değişiklikler yapılmıştır. Hazine ve Maliye Bakanlığı\'nın yeni düzenlemeleri ile birlikte, sigorta şirketlerinin uygulayabileceği minimum ve maksimum prim tutarları yeniden belirlenmiştir.</p><h3>Fiyatları Etkileyen Faktörler</h3><ul><li>Araç türü ve motor hacmi</li><li>Sürücünün yaşı ve deneyimi</li><li>Hasarsızlık indirimi oranı</li><li>İkamet edilen il</li><li>Aracın kullanım amacı</li></ul><p>Hasarsızlık indirimi, trafik sigortası primlerini önemli ölçüde düşüren bir faktördür. Her yıl hasarsız geçirdiğinizde indirim oranınız artar ve %60\'a kadar çıkabilir.</p><h3>Tasarruf Yöntemleri</h3><p>Trafik sigortası primlerinden tasarruf etmek için farklı sigorta şirketlerinden teklif alarak karşılaştırma yapabilirsiniz. Ayrıca hasarsızlık indiriminizi korumak da uzun vadede önemli tasarruf sağlar.</p>',
        'icon' => 'fas fa-car-crash',
        'icon_bg' => 'linear-gradient(135deg, #0066cc, #004499)',
        'reading_time' => 8,
        'published_at' => '2025-01-15',
        'sort_order' => 1,
    ],
    [
        'cat' => 'saglik-sigortasi',
        'title' => 'Tamamlayıcı Sağlık Sigortası mı, Özel Sağlık Sigortası mı?',
        'slug' => 'tamamlayici-ozel-saglik-sigortasi-karsilastirma',
        'excerpt' => 'İki sigorta türü arasındaki farkları, avantajlarını ve hangi durumda hangisini tercih etmeniz gerektiğini detaylıca karşılaştırdık.',
        'content' => '<h2>Tamamlayıcı vs Özel Sağlık Sigortası</h2><p>Sağlık sigortası seçimi yaparken iki temel seçenek karşınıza çıkar: Tamamlayıcı sağlık sigortası ve özel sağlık sigortası. Her ikisinin de kendine göre avantajları ve dezavantajları bulunmaktadır.</p><h3>Tamamlayıcı Sağlık Sigortası</h3><p>SGK\'lı bireyler için idealdir. SGK fark ücretlerini karşılayarak özel hastanelerde uygun fiyatlarla tedavi olmanızı sağlar. Primi daha düşüktür.</p><h3>Özel Sağlık Sigortası</h3><p>SGK şartı aranmaz. Daha kapsamlı teminatlar sunar. Yurt dışı tedavileri de kapsayabilir ancak primi daha yüksektir.</p>',
        'icon' => 'fas fa-heartbeat',
        'icon_bg' => 'linear-gradient(135deg, #28a745, #1e7e34)',
        'reading_time' => 6,
        'published_at' => '2025-01-10',
        'sort_order' => 2,
    ],
    [
        'cat' => 'konut-sigortasi',
        'title' => 'DASK ve Konut Sigortası: Farkları ve Birlikte Kullanımı',
        'slug' => 'dask-konut-sigortasi-farklari',
        'excerpt' => 'DASK zorunlu deprem sigortası ile isteğe bağlı konut sigortası arasındaki farklar, teminat kapsamları ve neden ikisini de yaptırmanız gerektiği.',
        'content' => '<h2>DASK ve Konut Sigortası Karşılaştırması</h2><p>DASK zorunlu deprem sigortasıdır ve tüm mesken nitelikli taşınmazlar için yaptırılması gerekmektedir. Konut sigortası ise isteğe bağlıdır ancak evinizi yangın, hırsızlık, su basması gibi birçok riske karşı korur.</p><h3>Neden İkisine de İhtiyacınız Var?</h3><p>DASK sadece deprem hasarını karşılar. Konut sigortası ise çok daha kapsamlı bir koruma sağlar. İkisini birlikte yaptırarak evinizi her türlü riske karşı güvence altına alabilirsiniz.</p>',
        'icon' => 'fas fa-home',
        'icon_bg' => 'linear-gradient(135deg, #ff6600, #cc5200)',
        'reading_time' => 10,
        'published_at' => '2025-01-05',
        'sort_order' => 3,
    ],
    [
        'cat' => 'kasko',
        'title' => 'Kasko Sigortasında Muafiyet Nedir? Avantajları ve Dezavantajları',
        'slug' => 'kasko-muafiyet-avantajlari-dezavantajlari',
        'excerpt' => 'Kasko poliçenizde muafiyet seçmek primi nasıl etkiler? Muafiyetli ve muafiyetsiz kasko arasındaki farkları ve hangi durumda hangisini seçmeniz gerektiğini anlattık.',
        'content' => '<h2>Kasko Muafiyeti Nedir?</h2><p>Kasko muafiyeti, poliçenizde belirlenen bir tutarın altındaki hasarların sigorta kapsamı dışında tutulmasıdır. Bu sayede kasko priminiz düşer ancak küçük hasarları kendiniz karşılarsınız.</p><h3>Avantajları</h3><ul><li>Prim tutarı önemli ölçüde düşer</li><li>Hasarsızlık indirimi korunur</li></ul><h3>Dezavantajları</h3><ul><li>Küçük hasarlar kapsam dışında kalır</li><li>Hasar durumunda belirli bir tutarı kendiniz ödersiniz</li></ul>',
        'icon' => 'fas fa-shield-alt',
        'icon_bg' => 'linear-gradient(135deg, #6f42c1, #5a32a3)',
        'reading_time' => 7,
        'published_at' => '2024-12-28',
        'sort_order' => 4,
    ],
    [
        'cat' => 'seyahat-sigortasi',
        'title' => 'Schengen Vizesi İçin Seyahat Sağlık Sigortası: Bilmeniz Gerekenler',
        'slug' => 'schengen-vizesi-seyahat-saglik-sigortasi',
        'excerpt' => 'Schengen vizesi başvurusu için gerekli seyahat sağlık sigortası şartları, minimum teminat tutarları ve en uygun poliçeyi nasıl bulacağınız.',
        'content' => '<h2>Schengen Vizesi ve Seyahat Sigortası</h2><p>Schengen ülkelerine seyahat edecekler için seyahat sağlık sigortası zorunludur. Minimum 30.000 Euro teminat tutarı ile yaptırılmalıdır.</p><h3>Gerekli Teminatlar</h3><ul><li>Minimum 30.000 Euro teminat</li><li>Acil tıbbi müdahale</li><li>Sağlık harcamaları</li><li>Cenaze nakil masrafları</li></ul>',
        'icon' => 'fas fa-plane',
        'icon_bg' => 'linear-gradient(135deg, #e83e8c, #c2185b)',
        'reading_time' => 5,
        'published_at' => '2024-12-20',
        'sort_order' => 5,
    ],
    [
        'cat' => 'elektrikli-arac',
        'title' => 'Elektrikli Araç Sigortası: 2025 Rehberi',
        'slug' => 'elektrikli-arac-sigortasi-2025-rehberi',
        'excerpt' => 'Elektrikli araçlar için özel sigorta çözümleri, batarya teminatı, şarj istasyonu hasarları ve elektrikli araç sahiplerinin bilmesi gerekenler.',
        'content' => '<h2>Elektrikli Araç Sigortası</h2><p>Elektrikli araçların sayısı her geçen gün artıyor. Bu araçlar için standart kasko poliçelerinin yanı sıra özel teminatlar da sunulmaktadır.</p><h3>Özel Teminatlar</h3><ul><li>Batarya hasarı teminatı</li><li>Şarj istasyonu hasarları</li><li>Elektrik arızası teminatı</li><li>Yol yardım hizmetleri</li></ul>',
        'icon' => 'fas fa-bolt',
        'icon_bg' => 'linear-gradient(135deg, #17a2b8, #117a8b)',
        'reading_time' => 9,
        'published_at' => '2024-12-15',
        'sort_order' => 6,
    ],
    [
        'cat' => 'cep-telefonu',
        'title' => 'Cep Telefonu Sigortası Yaptırmaya Değer mi?',
        'slug' => 'cep-telefonu-sigortasi-yaptirmaya-deger-mi',
        'excerpt' => 'Cep telefonu sigortasının sağladığı korumalar, fiyat-fayda analizi ve sigortayı ne zaman yaptırmanız gerektiğini detaylıca inceledik.',
        'content' => '<h2>Cep Telefonu Sigortası</h2><p>Akıllı telefonların fiyatları arttıkça, cep telefonu sigortası daha önemli hale geliyor. Peki gerçekten yaptırmaya değer mi?</p><h3>Ne Zaman Yaptırmalı?</h3><p>Yüksek fiyatlı bir telefonunuz varsa ve sık seyahat ediyorsanız cep telefonu sigortası yaptırmanız önerilir.</p>',
        'icon' => 'fas fa-mobile-alt',
        'icon_bg' => 'linear-gradient(135deg, #fd7e14, #e36209)',
        'reading_time' => 4,
        'published_at' => '2024-12-10',
        'sort_order' => 7,
    ],
    [
        'cat' => 'evcil-hayvan',
        'title' => 'Evcil Hayvan Sigortası ile Dostlarınızı Koruyun',
        'slug' => 'evcil-hayvan-sigortasi',
        'excerpt' => 'Kedi ve köpekler için evcil hayvan sigortası nedir, ne işe yarar? Teminat kapsamı, fiyatları ve başvuru şartları hakkında tüm bilgiler.',
        'content' => '<h2>Evcil Hayvan Sigortası</h2><p>Evcil hayvan sigortası, kedi ve köpeklerinizin veteriner masraflarını karşılayan bir sigorta türüdür.</p><h3>Teminat Kapsamı</h3><ul><li>Veteriner muayene ücretleri</li><li>Ameliyat masrafları</li><li>İlaç giderleri</li><li>Aşı ücretleri</li></ul>',
        'icon' => 'fas fa-paw',
        'icon_bg' => 'linear-gradient(135deg, #20c997, #1a9e7a)',
        'reading_time' => 6,
        'published_at' => '2024-12-05',
        'sort_order' => 8,
    ],
    [
        'cat' => 'genel-bilgi',
        'title' => 'Sigorta Poliçenizi Nasıl İptal Edebilirsiniz?',
        'slug' => 'sigorta-policesi-iptal-rehberi',
        'excerpt' => 'Sigorta poliçesi iptal süreçleri, cayma hakkı, iade koşulları ve dikkat etmeniz gereken önemli noktalar hakkında kapsamlı rehberimiz.',
        'content' => '<h2>Poliçe İptal Süreci</h2><p>Sigorta poliçenizi belirli koşullar altında iptal edebilirsiniz. Cayma hakkı süresi içinde iptal ederseniz primin tamamı iade edilir.</p><h3>Cayma Hakkı</h3><ul><li>Hayat dışı sigortalar: 14 gün</li><li>Hayat sigortaları: 30 gün</li></ul><h3>İade Koşulları</h3><p>Cayma süresi dışında iptal ederseniz, kısa dönem tarifesine göre kullanılmamış prim kısmı iade edilir.</p>',
        'icon' => 'fas fa-balance-scale',
        'icon_bg' => 'linear-gradient(135deg, #007bff, #0056b3)',
        'reading_time' => 12,
        'published_at' => '2024-12-01',
        'sort_order' => 9,
    ],
];

$postStmt = $db->prepare("INSERT IGNORE INTO blog_posts (category_id, title, slug, excerpt, content, icon, icon_bg, reading_time, published_at, sort_order, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($posts as $i => $p) {
    $catId = $catMap[$p['cat']] ?? null;
    $featured = $i < 4 ? 1 : 0;
    $postStmt->execute([$catId, $p['title'], $p['slug'], $p['excerpt'], $p['content'], $p['icon'], $p['icon_bg'], $p['reading_time'], $p['published_at'], $p['sort_order'], $featured]);
}
echo count($posts) . " blog yazisi eklendi.\n";
echo "Blog migration tamamlandi!\n";
