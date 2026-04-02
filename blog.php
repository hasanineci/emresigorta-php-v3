<?php
require_once __DIR__ . '/includes/config.php'; 

// Tekil yazı görüntüleme
$singleSlug = isset($_GET['yazi']) ? trim($_GET['yazi']) : '';
if ($singleSlug) {
    $singlePost = getBlogPostBySlug($singleSlug);
    if (!$singlePost || !$singlePost['is_active']) {
        header('Location: ' . SITE_URL . '/blog.php');
        exit;
    }
    incrementBlogViews($singlePost['id']);
    $pageTitle = ($singlePost['meta_title'] ?: $singlePost['title']) . ' | Emre Sigorta Blog';
    $pageDescription = $singlePost['meta_description'] ?: mb_substr(strip_tags($singlePost['excerpt']), 0, 160, 'UTF-8');
    $pageKeywords = 'sigorta blog, ' . htmlspecialchars($singlePost['title'], ENT_QUOTES, 'UTF-8');
    $ogType = 'article';
    $pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
        ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
        ['name' => 'Blog', 'url' => 'https://' . SITE_DOMAIN . '/blog.php'],
        ['name' => $singlePost['title']]
    ]) . '</script>';

    // İlgili yazılar (aynı kategoriden, kendisi hariç)
    $relatedPosts = getAllBlogPosts(['is_active' => 1, 'category_id' => $singlePost['category_id'], 'limit' => 3]);
    $relatedPosts = array_filter($relatedPosts, fn($rp) => $rp['id'] !== $singlePost['id']);
    $relatedPosts = array_slice($relatedPosts, 0, 3);

    include 'includes/header.php';
    ?>

    <section class="page-banner">
        <div class="container">
            <h1><?php echo htmlspecialchars($singlePost['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p><?php echo htmlspecialchars($singlePost['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
            <div class="breadcrumb">
                <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
                <span>/</span>
                <a href="<?php echo SITE_URL; ?>/blog.php">Blog</a>
                <span>/</span>
                <span><?php echo htmlspecialchars($singlePost['title'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Meta & Kategori -->
                    <div class="blog-detail-header" data-aos="fade-up">
                        <span class="blog-cat-badge mb-3" style="background:<?php echo htmlspecialchars($singlePost['category_color'] ?? '#0066cc', ENT_QUOTES, 'UTF-8'); ?>">
                            <i class="<?php echo htmlspecialchars($singlePost['category_icon'] ?? 'fas fa-tag', ENT_QUOTES, 'UTF-8'); ?> me-1"></i>
                            <?php echo htmlspecialchars($singlePost['category_name'] ?? 'Genel', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <div class="blog-card-v2-meta justify-content-center mt-3 mb-4" style="font-size:14px;">
                            <span><i class="far fa-calendar-alt"></i> <?php echo turkishDate($singlePost['published_at']); ?></span>
                            <span><i class="far fa-clock"></i> <?php echo turkishDate($singlePost['published_at'], 'time'); ?></span>
                            <span><i class="far fa-eye"></i> <?php echo number_format($singlePost['views']); ?> görüntülenme</span>
                        </div>
                    </div>

                    <?php if ($singlePost['featured_image']): ?>
                    <div class="mb-5 text-center" data-aos="fade-up">
                        <img src="<?php echo SITE_URL . '/' . htmlspecialchars($singlePost['featured_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($singlePost['title'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded-4" style="max-height: 500px; object-fit: cover; width: 100%;">
                    </div>
                    <?php elseif ($singlePost['icon']): ?>
                    <div class="mb-5 text-center" data-aos="fade-up">
                        <div class="blog-icon-cover rounded-4 mx-auto" style="background: <?php echo htmlspecialchars($singlePost['icon_bg'] ?? 'linear-gradient(135deg, #0066cc, #004499)', ENT_QUOTES, 'UTF-8'); ?>; height: 300px; font-size: 72px;">
                            <i class="<?php echo htmlspecialchars($singlePost['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- İçerik -->
                    <div class="blog-detail-content" data-aos="fade-up">
                        <?php echo $singlePost['content']; ?>
                    </div>

                    <!-- Paylaş -->
                    <div class="text-center mt-5 pt-4 border-top" data-aos="fade-up">
                        <p class="text-muted mb-3"><strong>Bu yazıyı paylaşın</strong></p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . SITE_DOMAIN . '/blog.php?yazi=' . $singlePost['slug']); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary rounded-pill px-3"><i class="fab fa-facebook-f me-1"></i> Facebook</a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . SITE_DOMAIN . '/blog.php?yazi=' . $singlePost['slug']); ?>&text=<?php echo urlencode($singlePost['title']); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info rounded-pill px-3"><i class="fab fa-twitter me-1"></i> Twitter</a>
                            <a href="https://wa.me/?text=<?php echo urlencode($singlePost['title'] . ' - https://' . SITE_DOMAIN . '/blog.php?yazi=' . $singlePost['slug']); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success rounded-pill px-3"><i class="fab fa-whatsapp me-1"></i> WhatsApp</a>
                        </div>
                    </div>

                    <?php if (!empty($relatedPosts)): ?>
                    <!-- İlgili Yazılar -->
                    <div class="mt-5 pt-4" data-aos="fade-up">
                        <h4 class="fw-bold mb-4 text-center">İlgili Yazılar</h4>
                        <div class="row g-4">
                            <?php foreach ($relatedPosts as $rp): ?>
                            <div class="col-md-4">
                                <article class="blog-card-v2">
                                    <a href="<?php echo SITE_URL; ?>/blog.php?yazi=<?php echo urlencode($rp['slug']); ?>" class="blog-card-v2-image">
                                        <?php if ($rp['featured_image']): ?>
                                        <img src="<?php echo SITE_URL . '/' . htmlspecialchars($rp['featured_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($rp['title'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                                        <?php else: ?>
                                        <div class="blog-icon-cover" style="background: <?php echo htmlspecialchars($rp['icon_bg'] ?? 'linear-gradient(135deg, #0066cc, #004499)', ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="<?php echo htmlspecialchars($rp['icon'] ?? 'fas fa-file-alt', ENT_QUOTES, 'UTF-8'); ?>"></i>
                                        </div>
                                        <?php endif; ?>
                                        <span class="blog-cat-badge" style="background:<?php echo htmlspecialchars($rp['category_color'] ?? '#0066cc', ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($rp['category_name'] ?? 'Genel', ENT_QUOTES, 'UTF-8'); ?></span>
                                    </a>
                                    <div class="blog-card-v2-body">
                                        <h3 class="blog-card-v2-title">
                                            <a href="<?php echo SITE_URL; ?>/blog.php?yazi=<?php echo urlencode($rp['slug']); ?>"><?php echo htmlspecialchars($rp['title'], ENT_QUOTES, 'UTF-8'); ?></a>
                                        </h3>
                                        <a href="<?php echo SITE_URL; ?>/blog.php?yazi=<?php echo urlencode($rp['slug']); ?>" class="blog-card-v2-link">
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
                            <i class="fas fa-arrow-left me-2"></i>Tüm Yazılara Dön
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php
    include 'includes/footer.php';
    exit;
}

// ===== BLOG LİSTESİ =====
$pageTitle = 'Sigorta Blog | Güncel Sigorta Haberleri ve Rehberler';
$pageDescription = 'Emre Sigorta blog - Sigorta sektörü haberleri, güncel fiyatlar, uzman ipuçları ve kapsamlı sigorta rehberleri.';
$pageKeywords = 'sigorta blog, sigorta haberleri, sigorta rehberi, trafik sigortası rehber, kasko rehber, sağlık sigortası rehber';
$ogType = 'blog';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Blog']
]) . '</script>';

// Filtreler
$selectedCat = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';
$currentPageNum = max(1, (int)($_GET['sayfa'] ?? 1));
$perPage = 9;

$blogCategories = getAllBlogCategories(true);
$catIdFilter = 0;
if ($selectedCat) {
    foreach ($blogCategories as $bc) {
        if ($bc['slug'] === $selectedCat) { $catIdFilter = $bc['id']; break; }
    }
}

$countFilters = ['is_active' => 1];
if ($catIdFilter) $countFilters['category_id'] = $catIdFilter;
$totalPosts = getBlogPostCount($countFilters);
$totalPages = max(1, ceil($totalPosts / $perPage));
if ($currentPageNum > $totalPages) $currentPageNum = $totalPages;

$postFilters = ['is_active' => 1, 'limit' => $perPage, 'offset' => ($currentPageNum - 1) * $perPage];
if ($catIdFilter) $postFilters['category_id'] = $catIdFilter;
$posts = getAllBlogPosts($postFilters);

// Öne çıkan yazılar (her zaman göster)
$featuredPosts = getAllBlogPosts(['is_active' => 1, 'is_featured' => 1, 'limit' => 8]);
if (count($featuredPosts) < 3) {
    $featuredPosts = getAllBlogPosts(['is_active' => 1, 'limit' => 8]);
}

// Son dakika haberleri (Sigortamedya RSS)
$tickerNews = getExternalNews(['is_active' => 1, 'limit' => 10]);
?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>Blog & Sigorta Rehberi</h1>
        <p>Sigorta dünyasındaki gelişmeleri, uzman görüşlerini ve faydalı bilgileri keşfedin.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>Blog</span>
        </div>
    </div>
</section>

<section class="blog-section py-5">
    <div class="container">

        <?php if (!empty($featuredPosts)): ?>
        <!-- Öne Çıkan Slider -->
        <div class="blog-slider-wrapper mb-4">
            <div class="blog-hero-area">
                <?php foreach ($featuredPosts as $fIdx => $fp): ?>
                <a href="<?php echo SITE_URL; ?>/blog.php?yazi=<?php echo urlencode($fp['slug']); ?>" class="blog-hero-slide <?php echo $fIdx === 0 ? 'active' : ''; ?>" data-index="<?php echo $fIdx; ?>">
                    <?php if ($fp['featured_image']): ?>
                    <img src="<?php echo SITE_URL . '/' . htmlspecialchars($fp['featured_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($fp['title'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php else: ?>
                    <div class="blog-icon-cover" style="background: <?php echo htmlspecialchars($fp['icon_bg'] ?? 'linear-gradient(135deg, #0066cc, #004499)', ENT_QUOTES, 'UTF-8'); ?>">
                        <i class="<?php echo htmlspecialchars($fp['icon'] ?? 'fas fa-file-alt', ENT_QUOTES, 'UTF-8'); ?>"></i>
                    </div>
                    <?php endif; ?>
                    <div class="blog-hero-overlay">
                        <span class="blog-cat-badge" style="background:<?php echo htmlspecialchars($fp['category_color'] ?? '#0066cc', ENT_QUOTES, 'UTF-8'); ?>">
                            <i class="<?php echo htmlspecialchars($fp['category_icon'] ?? 'fas fa-tag', ENT_QUOTES, 'UTF-8'); ?> me-1"></i>
                            <?php echo htmlspecialchars($fp['category_name'] ?? 'Genel', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <h3><?php echo htmlspecialchars($fp['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p class="blog-hero-excerpt d-none d-md-block"><?php echo htmlspecialchars(mb_substr($fp['excerpt'], 0, 150, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?><?php echo mb_strlen($fp['excerpt'], 'UTF-8') > 150 ? '...' : ''; ?></p>
                        <div class="blog-meta-white">
                            <span><i class="far fa-calendar-alt"></i> <?php echo turkishDate($fp['published_at'], 'short'); ?></span>
                            <span><i class="far fa-clock"></i> <?php echo turkishDate($fp['published_at'], 'time'); ?></span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <!-- Numara Tabları + Son Dakika Ticker -->
            <div class="blog-slider-bar">
                <div class="blog-ticker-area">
                    <span class="blog-ticker-badge"><i class="fas fa-bolt"></i> SON DAKİKA</span>
                    <?php if (!empty($tickerNews)): ?>
                    <div class="blog-ticker-wrap">
                        <div class="blog-ticker-track">
                            <?php foreach ($tickerNews as $tn): ?>
                            <a href="<?php echo SITE_URL; ?>/haber.php?haber=<?php echo urlencode($tn['slug']); ?>" class="blog-ticker-item"><?php echo htmlspecialchars($tn['title'], ENT_QUOTES, 'UTF-8'); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="blog-slider-nums">
                    <?php foreach ($featuredPosts as $fIdx => $fp): ?>
                    <button class="blog-num-btn <?php echo $fIdx === 0 ? 'active' : ''; ?>" data-index="<?php echo $fIdx; ?>">
                        <?php echo $fIdx + 1; ?>
                        <span class="blog-num-progress"></span>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Kategori Filtreleri -->
        <div class="blog-filters mb-4" id="blogFilters">
            <div class="blog-filters-track">
                <a href="<?php echo SITE_URL; ?>/blog.php" class="blog-filter-btn <?php echo !$selectedCat ? 'active' : ''; ?>" data-kategori="">
                    Tümü
                </a>
                <?php foreach ($blogCategories as $bc): ?>
                <a href="<?php echo SITE_URL; ?>/blog.php?kategori=<?php echo urlencode($bc['slug']); ?>" class="blog-filter-btn <?php echo $selectedCat === $bc['slug'] ? 'active' : ''; ?>" data-kategori="<?php echo htmlspecialchars($bc['slug'], ENT_QUOTES, 'UTF-8'); ?>" data-color="<?php echo htmlspecialchars($bc['color'], ENT_QUOTES, 'UTF-8'); ?>" style="<?php echo $selectedCat === $bc['slug'] ? 'background:' . htmlspecialchars($bc['color'], ENT_QUOTES, 'UTF-8') . ';border-color:' . htmlspecialchars($bc['color'], ENT_QUOTES, 'UTF-8') . ';color:#fff;' : ''; ?>">
                    <?php echo htmlspecialchars($bc['name'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="blogGridContainer">
        <?php if (empty($posts)): ?>
        <div class="col-12 text-center py-5">
            <i class="fas fa-newspaper text-muted" style="font-size: 3rem; opacity: .3;"></i>
            <p class="text-muted mt-3">Bu kategoride henüz yazı bulunmuyor.</p>
            <a href="<?php echo SITE_URL; ?>/blog.php" class="btn btn-primary rounded-pill px-4 mt-2">Tüm Yazıları Gör</a>
        </div>
        <?php else: ?>

        <!-- Blog Grid -->
        <div class="row g-4" id="blogGrid">
            <?php foreach ($posts as $post): ?>
            <div class="col-lg-4 col-md-6 blog-ajax-card">
                <article class="blog-card-v2">
                    <a href="<?php echo SITE_URL; ?>/blog.php?yazi=<?php echo urlencode($post['slug']); ?>" class="blog-card-v2-image">
                        <?php if ($post['featured_image']): ?>
                        <img src="<?php echo SITE_URL . '/' . htmlspecialchars($post['featured_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                        <?php else: ?>
                        <div class="blog-icon-cover" style="background: <?php echo htmlspecialchars($post['icon_bg'] ?? 'linear-gradient(135deg, #0066cc, #004499)', ENT_QUOTES, 'UTF-8'); ?>">
                            <i class="<?php echo htmlspecialchars($post['icon'] ?? 'fas fa-file-alt', ENT_QUOTES, 'UTF-8'); ?>"></i>
                        </div>
                        <?php endif; ?>
                        <span class="blog-cat-badge" style="background:<?php echo htmlspecialchars($post['category_color'] ?? '#0066cc', ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($post['category_name'] ?? 'Genel', ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                    <div class="blog-card-v2-body">
                        <div class="blog-card-v2-meta">
                            <span><i class="far fa-calendar-alt"></i> <?php echo turkishDate($post['published_at'], 'short'); ?></span>
                            <span><i class="far fa-clock"></i> <?php echo turkishDate($post['published_at'], 'time'); ?></span>
                        </div>
                        <h3 class="blog-card-v2-title">
                            <a href="<?php echo SITE_URL; ?>/blog.php?yazi=<?php echo urlencode($post['slug']); ?>"><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></a>
                        </h3>
                        <p class="blog-card-v2-excerpt"><?php echo htmlspecialchars(mb_substr($post['excerpt'], 0, 120, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?><?php echo mb_strlen($post['excerpt'], 'UTF-8') > 120 ? '...' : ''; ?></p>
                        <a href="<?php echo SITE_URL; ?>/blog.php?yazi=<?php echo urlencode($post['slug']); ?>" class="blog-card-v2-link">
                            Devamını Oku <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
        <!-- Sayfalama -->
        <div id="blogPagination">
        <nav class="blog-pagination mt-5">
            <ul class="pagination justify-content-center">
                <?php if ($currentPageNum > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="#" data-page="<?php echo $currentPageNum - 1; ?>"><i class="fas fa-chevron-left"></i></a>
                </li>
                <?php endif; ?>
                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <li class="page-item <?php echo $p === $currentPageNum ? 'active' : ''; ?>">
                    <a class="page-link" href="#" data-page="<?php echo $p; ?>"><?php echo $p; ?></a>
                </li>
                <?php endfor; ?>
                <?php if ($currentPageNum < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="#" data-page="<?php echo $currentPageNum + 1; ?>"><i class="fas fa-chevron-right"></i></a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        </div>
        <?php endif; ?>

        <?php endif; ?>
        </div><!-- /blogGridContainer -->

        <!-- CTA -->
        <div class="blog-cta mt-5" data-aos="fade-up">
            <div class="blog-cta-inner">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3>Sigorta Hakkında Bilgi Almak İster Misiniz?</h3>
                        <p>Uzman ekibimiz tüm sorularınızı yanıtlamaya hazır. Hemen bize ulaşın!</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="iletisim.php" class="btn btn-light btn-lg rounded-pill px-4 fw-bold">
                            <i class="fas fa-paper-plane me-2"></i>Bize Ulaşın
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.blog-hero-slide');
    const numBtns = document.querySelectorAll('.blog-num-btn');
    if (!slides.length) return;
    let current = 0;
    let autoTimer = null;
    const DELAY = 5000;

    function goTo(idx) {
        slides[current].classList.remove('active');
        numBtns[current].classList.remove('active');
        current = idx;
        slides[current].classList.add('active');
        numBtns[current].classList.add('active');
    }
    function nextSlide() {
        goTo((current + 1) % slides.length);
    }
    function startAuto() {
        stopAuto();
        autoTimer = setInterval(nextSlide, DELAY);
    }
    function stopAuto() {
        if (autoTimer) { clearInterval(autoTimer); autoTimer = null; }
    }

    numBtns.forEach(function(btn) {
        btn.addEventListener('mouseenter', function() {
            stopAuto();
            goTo(parseInt(this.dataset.index));
        });
        btn.addEventListener('mouseleave', function() {
            startAuto();
        });
    });

    startAuto();

    // Ticker: clone items for seamless infinite scroll
    var tickerTrack = document.querySelector('.blog-ticker-track');
    if (tickerTrack) {
        var items = tickerTrack.innerHTML;
        tickerTrack.innerHTML = items + items;
    }

    // ===== AJAX Blog Filtering =====
    var currentKategori = '<?php echo addslashes($selectedCat); ?>';
    var currentPage = <?php echo $currentPageNum; ?>;
    var isLoading = false;
    var gridContainer = document.getElementById('blogGridContainer');
    var filtersEl = document.getElementById('blogFilters');
    var apiUrl = '<?php echo SITE_URL; ?>/api/blog-filter.php';

    function loadBlog(kategori, sayfa) {
        if (isLoading) return;
        isLoading = true;

        // Fade out current content
        gridContainer.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        gridContainer.style.opacity = '0';
        gridContainer.style.transform = 'translateY(15px)';

        var url = apiUrl + '?sayfa=' + sayfa;
        if (kategori) url += '&kategori=' + encodeURIComponent(kategori);

        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.success) return;
                currentKategori = kategori;
                currentPage = sayfa;

                // Update URL without reload
                var newUrl = '<?php echo SITE_URL; ?>/blog.php';
                var params = [];
                if (kategori) params.push('kategori=' + encodeURIComponent(kategori));
                if (sayfa > 1) params.push('sayfa=' + sayfa);
                if (params.length) newUrl += '?' + params.join('&');
                history.replaceState(null, '', newUrl);

                // Update grid HTML
                var gridHtml = '<div class="row g-4" id="blogGrid">' + data.html + '</div>';
                var paginationEl = document.getElementById('blogPagination');
                if (paginationEl) paginationEl.remove();

                var existingGrid = document.getElementById('blogGrid');
                if (existingGrid) {
                    existingGrid.outerHTML = gridHtml;
                } else {
                    gridContainer.innerHTML = gridHtml;
                }

                // Add pagination
                if (data.pagination) {
                    var pDiv = document.createElement('div');
                    pDiv.id = 'blogPagination';
                    pDiv.innerHTML = data.pagination;
                    gridContainer.appendChild(pDiv);
                    bindPagination();
                }

                // Update active filter button
                filtersEl.querySelectorAll('.blog-filter-btn').forEach(function(btn) {
                    var kat = btn.dataset.kategori;
                    btn.classList.remove('active');
                    btn.style.background = '';
                    btn.style.borderColor = '';
                    btn.style.color = '';
                    if (kat === kategori) {
                        btn.classList.add('active');
                        if (btn.dataset.color) {
                            btn.style.background = btn.dataset.color;
                            btn.style.borderColor = btn.dataset.color;
                            btn.style.color = '#fff';
                        }
                    }
                });

                // Fade in new content
                requestAnimationFrame(function() {
                    gridContainer.style.opacity = '1';
                    gridContainer.style.transform = 'translateY(0)';
                    // Stagger card animations
                    var cards = gridContainer.querySelectorAll('.blog-ajax-card');
                    cards.forEach(function(card, i) {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                        setTimeout(function() {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, i * 80);
                    });
                });

                // Smooth scroll to filters
                filtersEl.scrollIntoView({ behavior: 'smooth', block: 'start' });

                isLoading = false;
            })
            .catch(function() {
                gridContainer.style.opacity = '1';
                gridContainer.style.transform = 'translateY(0)';
                isLoading = false;
            });
    }

    // Filter button clicks
    filtersEl.addEventListener('click', function(e) {
        var btn = e.target.closest('.blog-filter-btn');
        if (!btn) return;
        e.preventDefault();
        var kat = btn.dataset.kategori || '';
        if (kat === currentKategori && currentPage === 1) return;
        loadBlog(kat, 1);
    });

    // Pagination clicks (event delegation on container)
    function bindPagination() {
        var pagDiv = document.getElementById('blogPagination');
        if (!pagDiv) return;
        pagDiv.addEventListener('click', function(e) {
            var link = e.target.closest('.page-link');
            if (!link) return;
            e.preventDefault();
            var page = parseInt(link.dataset.page);
            if (!page || page === currentPage) return;
            loadBlog(currentKategori, page);
        });
    }
    bindPagination();
});
</script>

<?php include 'includes/footer.php'; ?>
