<?php 
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

// Genel sayfalar için cache başlığını geçersiz kıl (SEO için - security.php'nin no-store'unu ezer)
header('Cache-Control: public, max-age=3600, s-maxage=86400');
header_remove('Pragma');

// Sayfa aktiflik kontrolü (admin panelinden pasif edilen sayfalar engellensin)
$_currentSlug = basename($_SERVER['PHP_SELF']);
if ($_currentSlug !== 'index.php' && $_currentSlug !== '404.php' && !isPageActive($_currentSlug)) {
    include __DIR__ . '/../404.php';
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo SITE_URL; ?>/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : SITE_NAME . ' - Şanlıurfa\'nın güvenilir sigorta acentesi. Trafik sigortası, kasko, DASK, sağlık sigortası ve daha fazlası için en uygun fiyat tekliflerini alın.'; ?>">
    <meta name="keywords" content="<?php echo isset($pageKeywords) ? htmlspecialchars($pageKeywords, ENT_QUOTES, 'UTF-8') : 'sigorta, trafik sigortası, kasko, dask, sağlık sigortası, konut sigortası, şanlıurfa sigorta, emre sigorta, sigorta acentesi, online sigorta, sigorta teklifi'; ?>">
    <meta name="author" content="<?php echo SITE_NAME; ?> Aracılık Hizmetleri">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="bingbot" content="index, follow">
    <meta name="language" content="Turkish">
    <meta name="revisit-after" content="7 days">
    <meta name="rating" content="general">
    <meta name="geo.region" content="TR-63">
    <meta name="geo.placename" content="Şanlıurfa">
    <meta name="geo.position" content="37.1591;38.7969">
    <meta name="ICBM" content="37.1591, 38.7969">
    <meta name="format-detection" content="telephone=yes">
    <meta name="theme-color" content="#0066cc">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="<?php echo SITE_NAME; ?>">
    <link rel="canonical" href="<?php echo getCanonicalURL(); ?>">
    <link rel="alternate" hreflang="tr" href="<?php echo getCanonicalURL(); ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo getCanonicalURL(); ?>">
    
    <!-- Open Graph -->
    <meta property="og:type" content="<?php echo isset($ogType) ? $ogType : 'website'; ?>">
    <meta property="og:url" content="<?php echo getCanonicalURL(); ?>">
    <meta property="og:title" content="<?php echo isset($pageTitle) ? htmlspecialchars(getPageTitle($pageTitle), ENT_QUOTES, 'UTF-8') : htmlspecialchars(getPageTitle(), ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : SITE_NAME . ' - Şanlıurfa\'nın güvenilir sigorta acentesi.'; ?>">
    <meta property="og:image" content="https://<?php echo SITE_DOMAIN . SITE_LOGO; ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?php echo SITE_NAME; ?> - Şanlıurfa Sigorta Acentesi">
    <meta property="og:locale" content="tr_TR">
    <meta property="og:site_name" content="<?php echo SITE_NAME; ?>">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo isset($pageTitle) ? htmlspecialchars(getPageTitle($pageTitle), ENT_QUOTES, 'UTF-8') : htmlspecialchars(getPageTitle(), ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : SITE_NAME . ' - Şanlıurfa\'nın güvenilir sigorta acentesi.'; ?>">
    <meta name="twitter:image" content="https://<?php echo SITE_DOMAIN . SITE_LOGO; ?>">
    <meta name="twitter:image:alt" content="<?php echo SITE_NAME; ?> - Şanlıurfa Sigorta Acentesi">
    
    <!-- Structured Data - Organization -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "InsuranceAgency",
        "name": "<?php echo SITE_NAME; ?> Aracılık Hizmetleri",
        "alternateName": "<?php echo SITE_NAME; ?>",
        "url": "https://<?php echo SITE_DOMAIN; ?>",
        "logo": {
            "@type": "ImageObject",
            "url": "https://<?php echo SITE_DOMAIN . SITE_LOGO; ?>",
            "width": 300,
            "height": 60
        },
        "image": "https://<?php echo SITE_DOMAIN . SITE_LOGO; ?>",
        "description": "Şanlıurfa'nın güvenilir sigorta acentesi. Trafik sigortası, kasko, DASK, sağlık sigortası ve daha fazlası.",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Bamyasuyu Mahallesi Göbeklitepe Ticaret Merkezi B Blok No:2/38",
            "addressLocality": "Haliliye",
            "addressRegion": "Şanlıurfa",
            "postalCode": "63000",
            "addressCountry": "TR"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "37.1591",
            "longitude": "38.7969"
        },
        "telephone": "<?php echo SITE_PHONE_RAW; ?>",
        "email": "<?php echo SITE_EMAIL; ?>",
        "foundingDate": "<?php echo SITE_FOUNDED; ?>",
        "priceRange": "₺₺",
        "currenciesAccepted": "TRY",
        "paymentAccepted": "Kredi Kartı, Banka Kartı, Havale, EFT",
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
                "opens": "09:00",
                "closes": "18:00"
            }
        ],
        "sameAs": [],
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "<?php echo SITE_PHONE_RAW; ?>",
            "contactType": "customer service",
            "availableLanguage": "Turkish",
            "areaServed": "TR"
        },
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Sigorta Ürünleri",
            "itemListElement": [
                {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Trafik Sigortası"}},
                {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Kasko Sigortası"}},
                {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "DASK - Zorunlu Deprem Sigortası"}},
                {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Konut Sigortası"}},
                {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Tamamlayıcı Sağlık Sigortası"}},
                {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Özel Sağlık Sigortası"}},
                {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Seyahat Sağlık Sigortası"}}
            ]
        }
    }
    </script>

    <?php if (isset($pageSchema)) echo $pageSchema; ?>

    <title><?php echo isset($pageTitle) ? htmlspecialchars(getPageTitle($pageTitle), ENT_QUOTES, 'UTF-8') : htmlspecialchars(getPageTitle(), ENT_QUOTES, 'UTF-8'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo SITE_FAVICON; ?>">
    <link rel="apple-touch-icon" href="<?php echo SITE_FAVICON; ?>">
    
    <!-- DNS Prefetch & Preconnect -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- AOS - Animate On Scroll -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/variables.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <!-- CSRF Token & Site URL (JS için) -->
    <meta name="csrf-token" content="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="site-url" content="<?php echo SITE_URL; ?>">
</head>
<body>

<!-- Top Bar -->
<div class="top-bar d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <span class="top-bar-item">
                    <span class="top-bar-icon"><i class="fa-solid fa-location-dot"></i></span>
                    <?php echo SITE_ADDRESS; ?>
                </span>
            </div>
            <div class="col text-end">
                <a href="mailto:<?php echo SITE_EMAIL; ?>" class="top-bar-item top-bar-link">
                    <span class="top-bar-icon"><i class="fa-solid fa-envelope"></i></span>
                    <?php echo SITE_EMAIL; ?>
                </a>
                <span class="top-bar-divider"></span>
                <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="top-bar-item top-bar-link">
                    <span class="top-bar-icon"><i class="fa-solid fa-phone"></i></span>
                    <?php echo SITE_PHONE; ?>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm" id="mainNavbar">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="<?php echo SITE_URL; ?>/" title="<?php echo SITE_NAME; ?> - Ana Sayfa">
            <img src="<?php echo SITE_URL . SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?> Aracılık Hizmetleri" class="logo-img" height="52">
        </a>
        
        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="Menü">
            <i class="fa-solid fa-bars fs-4"></i>
        </button>
        
        <!-- Desktop Nav -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto">
                <!-- Ürünlerimiz Mega Menu -->
                <?php
                // Veritabanından aktif sayfaları kategoriye göre çek
                $_navPages = getActivePagesByCategory();
                $_categoryConfig = [
                    'arac'   => ['label' => 'Aracım',   'icon' => 'fa-solid fa-car',          'color' => 'primary'],
                    'saglik' => ['label' => 'Sağlığım', 'icon' => 'fa-solid fa-heart-pulse',   'color' => 'success'],
                    'konut'  => ['label' => 'Evim',     'icon' => 'fa-solid fa-house',         'color' => 'info'],
                    'diger'  => ['label' => 'Diğer',    'icon' => 'fa-solid fa-shield-halved', 'color' => 'secondary'],
                ];
                // Ürün kategorilerindeki tüm slug'ları topla (active page highlight için)
                $_productSlugs = [];
                foreach (['arac','saglik','konut','diger'] as $_cat) {
                    foreach ($_navPages[$_cat] ?? [] as $_np) {
                        $_productSlugs[] = $_np['slug'];
                    }
                }
                // Genel kategorideki sayfalar (kampanyalar, police-iptal vb.)
                $_genelPages = $_navPages['genel'] ?? [];
                // Genel sayfalardan bilgi merkezi olanları ayır
                $_bilgiSlugs = ['blog.php','hakkimizda.php','sss.php','iletisim.php','sube-basvurusu.php'];
                $_bilgiPages = [];
                $_ustMenuPages = [];
                foreach ($_genelPages as $_gp) {
                    if ($_gp['slug'] === 'index.php') continue;
                    if (in_array($_gp['slug'], $_bilgiSlugs)) {
                        $_bilgiPages[] = $_gp;
                    } else {
                        $_ustMenuPages[] = $_gp;
                    }
                }
                ?>
                <!-- Ürünlerimiz Mega Menu -->
                <li class="nav-item dropdown mega-dropdown">
                    <a class="nav-link dropdown-toggle <?php echo isActivePage($_productSlugs); ?>" href="#" id="urunlerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Ürünlerimiz
                    </a>
                    <div class="dropdown-menu mega-menu shadow-lg border-0" aria-labelledby="urunlerDropdown">
                        <div class="row g-3">
                            <?php foreach ($_categoryConfig as $_catKey => $_catInfo):
                                $_catPages = $_navPages[$_catKey] ?? [];
                                if (empty($_catPages)) continue;
                            ?>
                            <div class="col-lg-3">
                                <h6 class="text-<?php echo $_catInfo['color']; ?> fw-bold border-bottom pb-2 mb-2"><i class="<?php echo $_catInfo['icon']; ?> me-1"></i> <?php echo $_catInfo['label']; ?></h6>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($_catPages as $_cp): ?>
                                    <li><a class="dropdown-item" href="<?php echo SITE_URL . '/' . htmlspecialchars($_cp['slug'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($_cp['title'], ENT_QUOTES, 'UTF-8'); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </li>
                
                <?php foreach ($_ustMenuPages as $_up): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActivePage($_up['slug']); ?>" href="<?php echo SITE_URL . '/' . htmlspecialchars($_up['slug'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($_up['title'], ENT_QUOTES, 'UTF-8'); ?></a>
                </li>
                <?php endforeach; ?>
                
                <?php if (!empty($_bilgiPages)): ?>
                <!-- Bilgi Merkezi -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo isActivePage(array_column($_bilgiPages, 'slug')); ?>" href="#" role="button" data-bs-toggle="dropdown">
                        Bilgi Merkezi
                    </a>
                    <ul class="dropdown-menu shadow border-0">
                        <?php foreach ($_bilgiPages as $_bp): ?>
                        <li><a class="dropdown-item" href="<?php echo SITE_URL . '/' . htmlspecialchars($_bp['slug'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($_bp['title'], ENT_QUOTES, 'UTF-8'); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            
            <!-- Desktop Right -->
            <div class="d-flex align-items-center gap-3">
                <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="btn btn-phone-header">
                    <span class="btn-phone-icon"><i class="fa-solid fa-phone"></i></span>
                    <span><?php echo SITE_PHONE; ?></span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header border-bottom">
        <a href="<?php echo SITE_URL; ?>/">
            <img src="<?php echo SITE_URL . SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>" height="35">
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Kapat"></button>
    </div>
    <div class="offcanvas-body">
        <div class="d-flex flex-column gap-2 mb-3">
            <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="btn btn-outline-primary rounded-pill">
                <i class="fa-solid fa-phone me-2"></i><?php echo SITE_PHONE; ?>
            </a>
        </div>
        
        <div class="accordion" id="mobileAccordion">
            <!-- Ürünlerimiz -->
            <div class="accordion-item border-0">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#mobileUrunler">
                        Ürünlerimiz
                    </button>
                </h2>
                <div id="mobileUrunler" class="accordion-collapse collapse" data-bs-parent="#mobileAccordion">
                    <div class="accordion-body py-2">
                        <?php
                        $_mobileFirst = true;
                        foreach ($_categoryConfig as $_catKey => $_catInfo):
                            $_catPages = $_navPages[$_catKey] ?? [];
                            if (empty($_catPages)) continue;
                            if (!$_mobileFirst) echo '<hr class="my-2">';
                            $_mobileFirst = false;
                        ?>
                        <p class="text-<?php echo $_catInfo['color']; ?> fw-bold small mb-1"><?php echo $_catInfo['label']; ?></p>
                        <?php foreach ($_catPages as $_cp): ?>
                        <a class="d-block py-1 text-dark text-decoration-none" href="<?php echo SITE_URL . '/' . htmlspecialchars($_cp['slug'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($_cp['title'], ENT_QUOTES, 'UTF-8'); ?></a>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <?php foreach ($_ustMenuPages as $_up): ?>
            <a class="accordion-button collapsed fw-semibold text-decoration-none" href="<?php echo SITE_URL . '/' . htmlspecialchars($_up['slug'], ENT_QUOTES, 'UTF-8'); ?>" style="background:none;"><?php echo htmlspecialchars($_up['title'], ENT_QUOTES, 'UTF-8'); ?></a>
            <?php endforeach; ?>
            
            <?php if (!empty($_bilgiPages)): ?>
            <div class="accordion-item border-0">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#mobileBilgi">
                        Bilgi Merkezi
                    </button>
                </h2>
                <div id="mobileBilgi" class="accordion-collapse collapse" data-bs-parent="#mobileAccordion">
                    <div class="accordion-body py-2">
                        <?php foreach ($_bilgiPages as $_bp): ?>
                        <a class="d-block py-1 text-dark text-decoration-none" href="<?php echo SITE_URL . '/' . htmlspecialchars($_bp['slug'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($_bp['title'], ENT_QUOTES, 'UTF-8'); ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
    </div>
</div>
