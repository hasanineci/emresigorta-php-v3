<?php
/**
 * Blog AJAX Filter API
 * Returns HTML cards for blog posts filtered by category and page
 */
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json; charset=utf-8');

$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';
$sayfa = max(1, (int)($_GET['sayfa'] ?? 1));
$perPage = 9;

$blogCategories = getAllBlogCategories(true);
$catIdFilter = 0;
if ($kategori) {
    foreach ($blogCategories as $bc) {
        if ($bc['slug'] === $kategori) { $catIdFilter = $bc['id']; break; }
    }
}

$countFilters = ['is_active' => 1];
if ($catIdFilter) $countFilters['category_id'] = $catIdFilter;
$totalPosts = getBlogPostCount($countFilters);
$totalPages = max(1, ceil($totalPosts / $perPage));
if ($sayfa > $totalPages) $sayfa = $totalPages;

$postFilters = ['is_active' => 1, 'limit' => $perPage, 'offset' => ($sayfa - 1) * $perPage];
if ($catIdFilter) $postFilters['category_id'] = $catIdFilter;
$posts = getAllBlogPosts($postFilters);

// Build cards HTML
$cardsHtml = '';
if (empty($posts)) {
    $cardsHtml = '<div class="col-12 text-center py-5">
        <i class="fas fa-newspaper text-muted" style="font-size: 3rem; opacity: .3;"></i>
        <p class="text-muted mt-3">Bu kategoride henüz yazı bulunmuyor.</p>
        <a href="' . SITE_URL . '/blog.php" class="btn btn-primary rounded-pill px-4 mt-2">Tüm Yazıları Gör</a>
    </div>';
} else {
    foreach ($posts as $post) {
        $imgHtml = '';
        if ($post['featured_image']) {
            $imgHtml = '<img src="' . SITE_URL . '/' . htmlspecialchars($post['featured_image'], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') . '" loading="lazy">';
        } else {
            $imgHtml = '<div class="blog-icon-cover" style="background: ' . htmlspecialchars($post['icon_bg'] ?? 'linear-gradient(135deg, #0066cc, #004499)', ENT_QUOTES, 'UTF-8') . '"><i class="' . htmlspecialchars($post['icon'] ?? 'fas fa-file-alt', ENT_QUOTES, 'UTF-8') . '"></i></div>';
        }
        $catColor = htmlspecialchars($post['category_color'] ?? '#0066cc', ENT_QUOTES, 'UTF-8');
        $catName = htmlspecialchars($post['category_name'] ?? 'Genel', ENT_QUOTES, 'UTF-8');
        $title = htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8');
        $slug = urlencode($post['slug']);
        $date = turkishDate($post['published_at'], 'short');
        $time = turkishDate($post['published_at'], 'time');
        $excerpt = htmlspecialchars(mb_substr($post['excerpt'], 0, 120, 'UTF-8'), ENT_QUOTES, 'UTF-8');
        if (mb_strlen($post['excerpt'], 'UTF-8') > 120) $excerpt .= '...';

        $cardsHtml .= '<div class="col-lg-4 col-md-6 blog-ajax-card">
            <article class="blog-card-v2">
                <a href="' . SITE_URL . '/blog.php?yazi=' . $slug . '" class="blog-card-v2-image">
                    ' . $imgHtml . '
                    <span class="blog-cat-badge" style="background:' . $catColor . '">' . $catName . '</span>
                </a>
                <div class="blog-card-v2-body">
                    <div class="blog-card-v2-meta">
                        <span><i class="far fa-calendar-alt"></i> ' . $date . '</span>
                        <span><i class="far fa-clock"></i> ' . $time . '</span>
                    </div>
                    <h3 class="blog-card-v2-title">
                        <a href="' . SITE_URL . '/blog.php?yazi=' . $slug . '">' . $title . '</a>
                    </h3>
                    <p class="blog-card-v2-excerpt">' . $excerpt . '</p>
                    <a href="' . SITE_URL . '/blog.php?yazi=' . $slug . '" class="blog-card-v2-link">
                        Devamını Oku <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </article>
        </div>';
    }
}

// Build pagination HTML
$paginationHtml = '';
if ($totalPages > 1) {
    $paginationHtml = '<nav class="blog-pagination mt-5"><ul class="pagination justify-content-center">';
    if ($sayfa > 1) {
        $paginationHtml .= '<li class="page-item"><a class="page-link" href="#" data-page="' . ($sayfa - 1) . '"><i class="fas fa-chevron-left"></i></a></li>';
    }
    for ($p = 1; $p <= $totalPages; $p++) {
        $active = $p === $sayfa ? ' active' : '';
        $paginationHtml .= '<li class="page-item' . $active . '"><a class="page-link" href="#" data-page="' . $p . '">' . $p . '</a></li>';
    }
    if ($sayfa < $totalPages) {
        $paginationHtml .= '<li class="page-item"><a class="page-link" href="#" data-page="' . ($sayfa + 1) . '"><i class="fas fa-chevron-right"></i></a></li>';
    }
    $paginationHtml .= '</ul></nav>';
}

echo json_encode([
    'success' => true,
    'html' => $cardsHtml,
    'pagination' => $paginationHtml,
    'total' => $totalPosts,
    'pages' => $totalPages,
    'page' => $sayfa
]);
