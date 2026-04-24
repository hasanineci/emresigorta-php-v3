<?php
$pdo = new PDO('mysql:host=localhost;dbname=webhasan;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$pdo->exec("CREATE TABLE IF NOT EXISTS faq_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    question VARCHAR(500) NOT NULL,
    answer TEXT NOT NULL,
    show_on_homepage TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES faq_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

echo "Tables created.\n";

// Seed categories
$cats = [
    ['Genel Sorular', 'genel-sorular', 1],
    ['Trafik Sigortası', 'trafik-sigortasi', 2],
    ['Kasko Sigortası', 'kasko-sigortasi', 3],
    ['Sağlık Sigortası', 'saglik-sigortasi', 4],
    ['DASK & Konut Sigortası', 'dask-konut-sigortasi', 5],
    ['Ödeme ve İptal İşlemleri', 'odeme-iptal-islemleri', 6],
];
$stmt = $pdo->prepare("INSERT INTO faq_categories (name, slug, sort_order) VALUES (?, ?, ?)");
foreach ($cats as $c) { $stmt->execute($c); }
echo "Categories inserted.\n";

// Get cat IDs
$catIds = [];
$rows = $pdo->query("SELECT id, slug FROM faq_categories ORDER BY sort_order")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) { $catIds[$r['slug']] = $r['id']; }

// Seed FAQs - [category_id, question, answer, show_on_homepage, sort_order]
$faqs = [
    [$catIds['genel-sorular'], 'Sigorta poliçemi nasıl görüntüleyebilirim?', 'Sigorta poliçenizi satın aldıktan sonra e-posta adresinize gönderilen poliçe belgenizi görüntüleyebilirsiniz. Ayrıca hesabınıza giriş yaparak aktif ve geçmiş tüm poliçelerinize ulaşabilirsiniz.', 0, 1],
    [$catIds['genel-sorular'], 'Sigorta yaptırırken hangi belgeler gereklidir?', 'Araç sigortaları için ruhsat fotokopisi ve kimlik bilgileri, sağlık sigortaları için kimlik ve SGK bilgileri, konut sigortaları için tapu veya kira sözleşmesi bilgileri gereklidir.', 0, 2],
    [$catIds['genel-sorular'], 'Poliçem ne zaman başlar?', 'Satın aldığınız poliçe, ödemenizin onaylanmasının ardından hemen geçerli olur. Trafik sigortası ve kasko poliçeleri, ödeme anında başlangıç saatinden itibaren aktiftir.', 0, 3],
    [$catIds['genel-sorular'], 'Poliçemi nasıl yenileyebilirim?', 'Hesabınıza giriş yaparak mevcut poliçenizi yenileyebilirsiniz. Web sitemiz üzerinden yeni teklif alarak yenileyebilirsiniz. Poliçe bitiş tarihinden 30 gün önce hatırlatma e-postası ve SMS gönderilir.', 0, 4],
    [$catIds['trafik-sigortasi'], 'Trafik sigortası yaptırmazsam ne olur?', 'Trafik sigortası yasal olarak zorunludur. Sigortasız araç kullanmanın cezası her yıl güncellenmektedir. Ayrıca sigortasız araçla kaza yapmanız durumunda tüm masraflar size yansır.', 1, 1],
    [$catIds['trafik-sigortasi'], 'Trafik sigortası fiyatı nasıl belirlenir?', 'Trafik sigortası fiyatı aracın türü, motor hacmi, kullanım tarzı, ruhsat sahibinin ikamet ili, sürücünün yaşı ve hasar geçmişine bağlıdır.', 0, 2],
    [$catIds['trafik-sigortasi'], 'Trafik sigortasını taksitle ödeyebilir miyim?', 'Evet, trafik sigortası priminizi taksitli ödeyebilirsiniz. Kredi kartınıza 2, 3, 4, 6, 9 veya 12 taksit seçeneklerinden yararlanabilirsiniz.', 0, 3],
    [$catIds['kasko-sigortasi'], 'Kasko ve trafik sigortası arasındaki fark nedir?', 'Trafik sigortası zorunludur ve yalnızca üçüncü kişilere verilen zararları karşılar. Kasko ise isteğe bağlıdır ve kendi aracınızdaki hasarları da karşılar.', 1, 1],
    [$catIds['kasko-sigortasi'], 'Kasko muafiyeti nedir?', 'Kasko muafiyeti, hasar durumunda belirli bir tutarın altındaki hasarların sigorta kapsamı dışında tutulmasıdır. Muafiyetli kasko poliçeleri daha uygun fiyatlıdır.', 0, 2],
    [$catIds['kasko-sigortasi'], 'Mini kasko nedir?', 'Mini kasko, tam kaskodan daha dar kapsamlı ve daha uygun fiyatlı bir kasko türüdür. Genellikle hırsızlık, yangın ve doğal afet gibi belirli riskleri kapsar.', 0, 3],
    [$catIds['saglik-sigortasi'], 'Tamamlayıcı sağlık sigortasının avantajları nelerdir?', 'SGK\'nın karşılamadığı fark ücretlerini güvence altına alır. Özel hastanelerde fark ödemeden muayene, tetkik ve tedavi imkanı sunar.', 1, 1],
    [$catIds['saglik-sigortasi'], 'Tamamlayıcı ve özel sağlık sigortası farkı nedir?', 'Tamamlayıcı sağlık sigortasında SGK zorunludur ve SGK fark ücretlerini karşılar. Özel sağlık sigortasında SGK zorunlu değildir, daha kapsamlı teminatlar sunar.', 0, 2],
    [$catIds['dask-konut-sigortasi'], 'DASK (Deprem Sigortası) zorunlu mudur?', 'Evet, DASK zorunlu deprem sigortasıdır. Türkiye\'deki tüm mesken nitelikli taşınmazlar için zorunludur.', 1, 1],
    [$catIds['dask-konut-sigortasi'], 'Konut sigortası neleri kapsar?', 'Konut sigortası evinizi ve eşyalarınızı yangın, hırsızlık, su basması, fırtına, dolu, cam kırılması gibi risklere karşı korur.', 0, 2],
    [$catIds['odeme-iptal-islemleri'], 'Online sigorta yaptırmak güvenli midir?', 'Evet, tamamen güvenlidir. 256-bit SSL şifreleme kullanıyoruz ve KVKK uyumlu altyapımızla tüm kişisel verileriniz güvende tutulmaktadır.', 1, 1],
    [$catIds['odeme-iptal-islemleri'], 'Hangi ödeme yöntemlerini kullanabilirim?', 'Kredi kartı (taksitli veya tek çekim), banka kartı, havale/EFT ve sanal kart ile ödeme yapabilirsiniz.', 0, 2],
    [$catIds['odeme-iptal-islemleri'], 'Poliçemi iptal edersem paramı geri alabilir miyim?', 'Cayma hakkı süresi içinde iptal ederseniz primin tamamı iade edilir. Süre dışında kısa dönem tarifesine göre kullanılmamış prim kısmı iade edilir.', 0, 3],
    [$catIds['odeme-iptal-islemleri'], 'Hasar durumunda ne yapmalıyım?', 'Olay yerinde güvenliğinizi sağlayın, gerekli hallerde 112/155/110\'u arayın, sigorta şirketinizin hasar hattını arayın, olay yerinde fotoğraf çekin ve kaza tutanağı doldurun.', 0, 4],
];

$stmt = $pdo->prepare("INSERT INTO faqs (category_id, question, answer, show_on_homepage, sort_order) VALUES (?, ?, ?, ?, ?)");
foreach ($faqs as $f) { $stmt->execute($f); }
echo "FAQs inserted.\n";
echo "=== FAQ migration completed! ===\n";
