<?php
// Site Konfigürasyonu - Veritabanından yükle, fallback olarak sabit değerler
require_once __DIR__ . '/db.php';

// DB'den ayarları yükle
$_siteSettings = [];
try {
    $_siteSettings = getAllSettings();
} catch (Exception $e) {
    $_siteSettings = [];
}

function _s($key, $default = '') {
    global $_siteSettings;
    return isset($_siteSettings[$key]) ? $_siteSettings[$key]['setting_value'] : $default;
}

define('SITE_NAME', _s('site_name', 'Emre Sigorta'));
// SITE_URL ve SITE_DOMAIN: sunucu ortamından otomatik tespit
$_autoHost = $_SERVER['HTTP_HOST'] ?? 'www.emresigorta.net';
$_autoScheme = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) ? 'https' : 'http';
// Proje kök dizini ile document root arasındaki farkı bul (alt dizin tespiti)
$_projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
$_docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
$_basePath = '';
if ($_projectRoot && $_docRoot && strpos($_projectRoot, $_docRoot) === 0) {
    $_basePath = substr($_projectRoot, strlen($_docRoot));
}
// Production (emresigorta.net) her zaman https ve root'ta çalışır
if (strpos($_autoHost, 'localhost') === false && strpos($_autoHost, '127.0.0.1') === false) {
    $_basePath = '';
    $_autoScheme = 'https';
}
define('SITE_URL', $_autoScheme . '://' . $_autoHost . $_basePath);
define('SITE_DOMAIN', $_autoHost);
define('ADMIN_SLUG', 'poyrazlogin');
define('ADMIN_URL', SITE_URL . '/' . ADMIN_SLUG);
define('TURNSTILE_SITE_KEY', _s('turnstile_site_key', ''));
define('TURNSTILE_SECRET_KEY', _s('turnstile_secret_key', ''));
define('SITE_EMAIL', _s('site_email', 'info@emresigorta.net'));
define('SITE_EMAIL_ALT', _s('site_email_alt', 'hasanineci@gmail.com'));
define('SITE_PHONE', _s('site_phone', '0541 514 85 15'));
define('SITE_PHONE_RAW', _s('site_phone_raw', '+905415148515'));
define('SITE_PHONE_ALT', _s('site_phone_alt', '0541 514 85 15'));
define('SITE_PHONE_SHORT', _s('site_phone_short', '0541 514 85 15'));
define('SITE_ADDRESS', _s('site_address', 'Bamyasuyu Mahallesi Göbeklitepe Ticaret Merkezi B Blok No:2/38 Haliliye/Şanlıurfa'));
define('SITE_YEAR', date('Y'));
define('SITE_FOUNDED', _s('site_founded', '2022'));
define('SITE_LOGO', _s('site_logo', '/assets/images/logo/logo-siyah.png'));
define('SITE_LOGO_WHITE', _s('site_logo_white', '/assets/images/logo/logo-beyaz.png'));
define('SITE_FAVICON', _s('site_favicon', '/assets/images/logo/logo-siyah.png'));
define('GOOGLE_MAPS_EMBED', _s('google_maps_embed', ''));
define('WHATSAPP_MESSAGE', _s('whatsapp_message', 'Merhaba, sigorta hakkında bilgi almak istiyorum.'));

// Sayfa başlığı oluşturucu
function getPageTitle($title = '') {
    if ($title) {
        return $title . ' | ' . SITE_NAME;
    }
    return SITE_NAME . ' - Sigortada Güvenin Adresi';
}

// Aktif sayfa kontrolü
function isActivePage($page) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    if (is_array($page)) {
        return in_array($currentPage, $page) ? 'active' : '';
    }
    return ($currentPage == $page) ? 'active' : '';
}

// Canonical URL oluşturucu
function getCanonicalURL() {
    $page = basename($_SERVER['PHP_SELF']);
    if ($page === 'index.php') {
        return 'https://' . SITE_DOMAIN . '/';
    }
    return 'https://' . SITE_DOMAIN . '/' . $page;
}

// BreadcrumbList Schema oluşturucu
function getBreadcrumbSchema($items) {
    $listItems = [];
    foreach ($items as $i => $item) {
        $listItem = [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $item['name']
        ];
        if (isset($item['url'])) {
            $listItem['item'] = $item['url'];
        }
        $listItems[] = $listItem;
    }
    return json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $listItems
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

// FAQ Schema oluşturucu
function getFAQSchema($faqs) {
    $mainEntity = [];
    foreach ($faqs as $faq) {
        $mainEntity[] = [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['answer']
            ]
        ];
    }
    return json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $mainEntity
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

// Product/Service Schema oluşturucu
function getServiceSchema($name, $description, $url) {
    return json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $name,
        'description' => $description,
        'url' => $url,
        'provider' => [
            '@type' => 'InsuranceAgency',
            'name' => SITE_NAME . ' Aracılık Hizmetleri',
            'url' => 'https://' . SITE_DOMAIN,
            'telephone' => SITE_PHONE,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Bamyasuyu Mahallesi Göbeklitepe Ticaret Merkezi B Blok No:2/38',
                'addressLocality' => 'Haliliye',
                'addressRegion' => 'Şanlıurfa',
                'postalCode' => '63000',
                'addressCountry' => 'TR'
            ]
        ],
        'areaServed' => [
            '@type' => 'City',
            'name' => 'Şanlıurfa'
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}
?>
