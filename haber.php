<?php
/**
 * Harici Haber Detay Sayfası
 * Sigortamedya'dan çekilen haberlerin kendi sitemizde görüntülenmesi
 */
require_once __DIR__ . '/includes/config.php';

// Arka planda haberleri güncelle (2 saatte bir)
autoRefreshNewsIfNeeded(120);

$newsSlug = isset($_GET['haber']) ? trim($_GET['haber']) : '';
$newsId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$news = null;
if ($newsSlug) {
    $news = getExternalNewsBySlug($newsSlug);
} elseif ($newsId) {
    $news = getExternalNewsById($newsId);
    if ($news && !$news['is_active']) $news = null;
}

if (!$news) {
    header('Location: ' . SITE_URL . '/blog.php');
    exit;
}

$pageTitle = htmlspecialchars($news['title'], ENT_QUOTES, 'UTF-8') . ' | Sigorta Haberleri';
$pageDescription = mb_substr(strip_tags($news['excerpt']), 0, 160, 'UTF-8');
$pageKeywords = 'sigorta haberleri, son dakika, sigorta sektörü';
$ogType = 'article';
$_newsImage = !empty($news['image_url']) ? $news['image_url'] : 'https://' . SITE_DOMAIN . SITE_LOGO;
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Blog', 'url' => 'https://' . SITE_DOMAIN . '/blog.php'],
    ['name' => $news['title']]
]) . '</script>';
$pageSchema .= '<script type="application/ld+json">' . json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $news['title'],
    'description' => $pageDescription,
    'image' => $_newsImage,
    'datePublished' => date('c', strtotime($news['published_at'])),
    'dateModified' => date('c', strtotime($news['fetched_at'] ?? $news['published_at'])),
    'author' => ['@type' => 'Organization', 'name' => !empty($news['source']) ? $news['source'] : 'Sigorta Medya'],
    'publisher' => [
        '@type' => 'Organization',
        'name' => SITE_NAME . ' Aracılık Hizmetleri',
        'logo' => ['@type' => 'ImageObject', 'url' => 'https://' . SITE_DOMAIN . SITE_LOGO]
    ],
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => 'https://' . SITE_DOMAIN . '/haber.php?haber=' . $news['slug']]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';

include 'includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1><?php echo htmlspecialchars($news['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars(mb_substr(strip_tags($news['excerpt']), 0, 150, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <a href="<?php echo SITE_URL; ?>/blog.php">Blog</a>
            <span>/</span>
            <span>Sektör Haberleri</span>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="blog-detail-header" data-aos="fade-up">
                    <span class="blog-cat-badge mb-3" style="background:#dc3545">
                        <i class="fas fa-bolt me-1"></i> Sektör Haberleri
                    </span>
                    <div class="blog-card-v2-meta justify-content-center mt-3 mb-4" style="font-size:14px;">
                        <span><i class="far fa-calendar-alt"></i> <?php echo turkishDate($news['published_at']); ?></span>
                        <?php if ($news['author']): ?>
                        <span><i class="far fa-user"></i> <?php echo htmlspecialchars($news['author'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                        <span><i class="fas fa-external-link-alt"></i> Kaynak: Sigortamedya</span>
                    </div>
                </div>

                <?php if ($news['image_url']): ?>
                <div class="mb-5 text-center" data-aos="fade-up">
                    <img src="<?php echo htmlspecialchars($news['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($news['title'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded-4" style="max-height: 500px; object-fit: cover; width: 100%;" loading="lazy">
                </div>
                <?php endif; ?>

                <div class="blog-detail-content" data-aos="fade-up">
                    <?php echo $news['content']; ?>
                </div>

                <div class="text-center mt-4 pt-3 border-top" data-aos="fade-up">
                    <small class="text-muted d-block mb-3">
                        <i class="fas fa-info-circle me-1"></i> Bu haber <a href="<?php echo htmlspecialchars($news['source_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Sigortamedya</a> kaynaklıdır.
                    </small>
                </div>

                <div class="text-center mt-4 pt-3 border-top" data-aos="fade-up">
                    <p class="text-muted mb-3"><strong>Bu haberi paylaşın</strong></p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . SITE_DOMAIN . '/haber.php?haber=' . $news['slug']); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary rounded-pill px-3"><i class="fab fa-facebook-f me-1"></i> Facebook</a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . SITE_DOMAIN . '/haber.php?haber=' . $news['slug']); ?>&text=<?php echo urlencode($news['title']); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info rounded-pill px-3"><i class="fab fa-twitter me-1"></i> Twitter</a>
                        <a href="https://wa.me/?text=<?php echo urlencode($news['title'] . ' - https://' . SITE_DOMAIN . '/haber.php?haber=' . $news['slug']); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success rounded-pill px-3"><i class="fab fa-whatsapp me-1"></i> WhatsApp</a>
                    </div>
                </div>

                <?php
                // Son haberler
                $recentNews = getExternalNews(['is_active' => 1, 'limit' => 4]);
                $recentNews = array_filter($recentNews, fn($n) => $n['id'] !== $news['id']);
                $recentNews = array_slice($recentNews, 0, 3);
                if (!empty($recentNews)):
                ?>
                <div class="mt-5 pt-4" data-aos="fade-up">
                    <h4 class="fw-bold mb-4 text-center">Diğer Sektör Haberleri</h4>
                    <div class="row g-4">
                        <?php foreach ($recentNews as $rn): ?>
                        <div class="col-md-4">
                            <article class="blog-card-v2">
                                <a href="<?php echo SITE_URL; ?>/haber.php?haber=<?php echo urlencode($rn['slug']); ?>" class="blog-card-v2-image">
                                    <?php if ($rn['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($rn['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($rn['title'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                                    <?php else: ?>
                                    <div class="blog-icon-cover" style="background: linear-gradient(135deg, #dc3545, #a71d2a)">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                    <?php endif; ?>
                                    <span class="blog-cat-badge" style="background:#dc3545">Sektör Haberleri</span>
                                </a>
                                <div class="blog-card-v2-body">
                                    <div class="blog-card-v2-meta">
                                        <span><i class="far fa-calendar-alt"></i> <?php echo turkishDate($rn['published_at'], 'short'); ?></span>
                                    </div>
                                    <h3 class="blog-card-v2-title">
                                        <a href="<?php echo SITE_URL; ?>/haber.php?haber=<?php echo urlencode($rn['slug']); ?>"><?php echo htmlspecialchars($rn['title'], ENT_QUOTES, 'UTF-8'); ?></a>
                                    </h3>
                                    <a href="<?php echo SITE_URL; ?>/haber.php?haber=<?php echo urlencode($rn['slug']); ?>" class="blog-card-v2-link">
                                        Devamını Oku <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="text-center mt-5">
                    <a href="<?php echo SITE_URL; ?>/blog.php" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Blog'a Dön
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
