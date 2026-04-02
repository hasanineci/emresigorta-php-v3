<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

// Tekil kampanya görüntüleme
$detaySlug = isset($_GET['detay']) ? trim($_GET['detay']) : '';
if ($detaySlug) {
    $campaign = getCampaignBySlug($detaySlug);
    if (!$campaign || !$campaign['is_active']) {
        header('Location: ' . SITE_URL . '/kampanyalar.php');
        exit;
    }
    if (!isset($_SESSION['viewed_campaigns'])) {
        $_SESSION['viewed_campaigns'] = [];
    }
    if (!in_array($campaign['id'], $_SESSION['viewed_campaigns'])) {
        incrementCampaignViews($campaign['id']);
        $_SESSION['viewed_campaigns'][] = $campaign['id'];
    }
    $bgColor = $campaign['bg_color'] ?: '#1E3A8A';
    $features = array_filter(explode("\n", $campaign['features'] ?? ''));
    $endDate = new DateTime($campaign['end_date']);
    $now = new DateTime();
    $daysLeft = (int) $now->diff($endDate)->format('%r%a');

    // Sidebar: tüm aktif kampanyalar
    $allCampaigns = getAllCampaigns(['active_now' => true]);

    $pageTitle = htmlspecialchars($campaign['title']) . ' | Emre Sigorta Kampanya';
    $pageDescription = $campaign['short_description'] ?: mb_substr(strip_tags($campaign['description']), 0, 160, 'UTF-8');
    $pageKeywords = 'sigorta kampanyası, ' . htmlspecialchars($campaign['title']);
    $pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
        ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
        ['name' => 'Kampanyalar', 'url' => 'https://' . SITE_DOMAIN . '/kampanyalar.php'],
        ['name' => $campaign['title']]
    ]) . '</script>';
    include 'includes/header.php';
    ?>

    <section class="page-banner">
        <div class="container">
            <h1><?php echo htmlspecialchars($campaign['title']); ?></h1>
            <p><?php echo htmlspecialchars($campaign['short_description']); ?></p>
            <div class="breadcrumb">
                <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
                <span>/</span>
                <a href="<?php echo SITE_URL; ?>/kampanyalar.php">Kampanyalar</a>
                <span>/</span>
                <span><?php echo htmlspecialchars($campaign['title']); ?></span>
            </div>
        </div>
    </section>

    <section class="page-content">
        <div class="container">
            <div class="row g-4">
                <!-- Ana İçerik -->
                <div class="col-lg-8">
                    <div class="cmp-detail-card" data-aos="fade-up">
                        <?php if (!empty($campaign['image'])): ?>
                        <div class="cmp-detail-banner" style="background: linear-gradient(135deg, <?php echo htmlspecialchars($bgColor); ?>, <?php echo htmlspecialchars($bgColor); ?>cc);">
                            <div class="cmp-detail-banner-text">
                                <?php if (!empty($campaign['is_popular'])): ?>
                                    <span class="cmp-detail-popular-tag"><i class="fas fa-fire"></i> Popüler</span>
                                <?php endif; ?>
                                <h2><?php echo htmlspecialchars($campaign['title']); ?></h2>
                                <div class="cmp-detail-brand">
                                    <span>Emre Sigorta</span>
                                    <span class="cmp-detail-brand-sep">|</span>
                                    <span><?php echo htmlspecialchars($campaign['category'] ?: 'Kampanya'); ?></span>
                                </div>
                            </div>
                            <div class="cmp-detail-banner-img">
                                <img src="<?php echo SITE_URL . '/' . htmlspecialchars($campaign['image']); ?>" alt="<?php echo htmlspecialchars($campaign['title']); ?>">
                                <?php if ($campaign['discount_text']): ?>
                                    <div class="cmp-discount-ribbon">
                                        <span><?php echo htmlspecialchars($campaign['discount_text']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="cmp-detail-header" style="background: linear-gradient(135deg, <?php echo htmlspecialchars($bgColor); ?>, <?php echo htmlspecialchars($bgColor); ?>dd);">
                            <?php if (!empty($campaign['is_popular'])): ?>
                                <span style="display:inline-flex; align-items:center; gap:6px; background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; padding:6px 18px; border-radius:20px; font-size:13px; font-weight:700; letter-spacing:0.5px; margin-bottom:12px; box-shadow:0 3px 10px rgba(249,115,22,.35);"><i class="fas fa-fire"></i> Popüler Kampanya</span>
                            <?php endif; ?>
                            <?php if ($campaign['discount_text']): ?>
                                <div class="cmp-discount-ribbon" style="position:static; transform:none; display:inline-block; margin-bottom:12px;">
                                    <span><?php echo htmlspecialchars($campaign['discount_text']); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($campaign['icon']): ?>
                                <div class="cmp-detail-icon"><i class="<?php echo htmlspecialchars($campaign['icon']); ?>"></i></div>
                            <?php endif; ?>
                            <h2><?php echo htmlspecialchars($campaign['title']); ?></h2>
                        </div>
                        <?php endif; ?>

                        <?php
                        $shareUrl = SITE_URL . '/kampanyalar.php?detay=' . urlencode($campaign['slug']);
                        $shareTitle = htmlspecialchars($campaign['title']);
                        $shareText = htmlspecialchars($campaign['short_description'] ?: $campaign['title']);
                        ?>
                        <div class="cmp-detail-topbar">
                            <div class="cmp-detail-topbar-info">
                                <span><i class="far fa-calendar-alt"></i> <?php echo (new DateTime($campaign['created_at']))->format('d.m.Y'); ?></span>
                                <span class="cmp-detail-topbar-sep">|</span>
                                <span><i class="far fa-eye"></i> <?php echo number_format($campaign['views'] ?? 0); ?> görüntülenme</span>
                            </div>
                            <div class="cmp-detail-share">
                                <span class="cmp-share-label">Paylaş</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($shareUrl); ?>" target="_blank" rel="noopener" class="cmp-share-btn cmp-share-fb" title="Facebook'ta Paylaş"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($shareUrl); ?>&text=<?php echo urlencode($shareText); ?>" target="_blank" rel="noopener" class="cmp-share-btn cmp-share-tw" title="X'te Paylaş"><i class="fab fa-x-twitter"></i></a>
                                <a href="https://wa.me/?text=<?php echo urlencode($shareTitle . ' - ' . $shareUrl); ?>" target="_blank" rel="noopener" class="cmp-share-btn cmp-share-wp" title="WhatsApp'ta Paylaş"><i class="fab fa-whatsapp"></i></a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($shareUrl); ?>" target="_blank" rel="noopener" class="cmp-share-btn cmp-share-li" title="LinkedIn'de Paylaş"><i class="fab fa-linkedin-in"></i></a>
                                <button type="button" class="cmp-share-btn cmp-share-copy" title="Linki Kopyala" onclick="navigator.clipboard.writeText('<?php echo $shareUrl; ?>'); this.innerHTML='<i class=\'fas fa-check\'></i>'; setTimeout(()=>this.innerHTML='<i class=\'fas fa-link\'></i>', 2000);"><i class="fas fa-link"></i></button>
                            </div>
                        </div>
                        <div class="cmp-detail-body">
                            <?php if ($campaign['short_description']): ?>
                                <p class="cmp-detail-short"><?php echo htmlspecialchars($campaign['short_description']); ?></p>
                            <?php endif; ?>

                            <?php if ($campaign['description']): ?>
                                <div class="cmp-detail-desc">
                                    <?php echo nl2br(htmlspecialchars($campaign['description'])); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($features)): ?>
                                <h5 class="cmp-detail-features-title"><i class="fas fa-list-check me-2"></i>Kampanya Kapsamı</h5>
                                <ul class="cmp-detail-features">
                                    <?php foreach ($features as $feature): ?>
                                        <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars(trim($feature)); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <div class="cmp-detail-meta">
                                <div class="cmp-detail-timer">
                                    <i class="far fa-clock"></i>
                                    <?php if ($daysLeft > 0): ?>
                                        <span>Kampanya bitimine <strong><?php echo $daysLeft; ?> gün</strong> kaldı</span>
                                    <?php elseif ($daysLeft === 0): ?>
                                        <span class="text-danger"><strong>Bugün son gün!</strong></span>
                                    <?php endif; ?>
                                    <small>(<?php echo $endDate->format('d.m.Y'); ?> tarihine kadar geçerli)</small>
                                </div>
                            </div>

                            <div class="cmp-detail-actions">
                                <button type="button" class="btn cmp-btn-join-lg" data-campaign-id="<?php echo $campaign['id']; ?>" data-campaign-title="<?php echo htmlspecialchars($campaign['title']); ?>">
                                    <i class="fas fa-hand-point-up me-2"></i> Kampanyaya Katıl
                                </button>
                                <a href="<?php echo SITE_URL; ?>/kampanyalar.php" class="btn cmp-btn-back">
                                    <i class="fas fa-arrow-left me-1"></i> Tüm Kampanyalar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="cmp-sidebar" data-aos="fade-left">
                        <h4 class="cmp-sidebar-title"><i class="fas fa-bullhorn me-2"></i>Tüm Kampanyalar</h4>
                        <div class="cmp-sidebar-list">
                            <?php foreach ($allCampaigns as $sc):
                                $isActive = ($sc['slug'] === $campaign['slug']);
                            ?>
                            <a href="<?php echo SITE_URL; ?>/kampanyalar.php?detay=<?php echo urlencode($sc['slug']); ?>" class="cmp-sidebar-item<?php echo $isActive ? ' active' : ''; ?><?php echo !empty($sc['is_popular']) ? ' popular' : ''; ?>">
                                <div class="cmp-sidebar-item-icon" style="background: <?php echo htmlspecialchars($sc['bg_color'] ?: '#1E3A8A'); ?>;">
                                    <i class="<?php echo htmlspecialchars($sc['icon'] ?: 'fas fa-tag'); ?>"></i>
                                </div>
                                <div class="cmp-sidebar-item-info">
                                    <span class="cmp-sidebar-item-title"><?php echo htmlspecialchars($sc['title']); ?></span>
                                    <?php if (!empty($sc['is_popular'])): ?>
                                        <span class="cmp-sidebar-popular-tag"><i class="fas fa-fire"></i> Popüler</span>
                                    <?php endif; ?>
                                    <?php if ($sc['discount_text']): ?>
                                        <span class="cmp-sidebar-discount"><?php echo htmlspecialchars($sc['discount_text']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Kampanya Kuralları Sidebar -->
                    <div class="cmp-sidebar-rules mt-4" data-aos="fade-left" data-aos-delay="100">
                        <h5><i class="fas fa-info-circle me-2"></i>Kampanya Koşulları</h5>
                        <ul>
                            <li>Kampanyalar stokla sınırlıdır.</li>
                            <li>Diğer indirimlerle birleştirilemez.</li>
                            <li>Koşullarda değişiklik hakkı saklıdır.</li>
                            <li>Bilgi: <?php echo SITE_PHONE; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kampanyaya Katıl Modal (Detay) -->
    <div class="modal fade" id="campaignJoinModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header cmp-modal-header">
                    <div>
                        <h5 class="modal-title"><i class="fas fa-bullhorn me-2"></i>Kampanyaya Katıl</h5>
                        <p class="mb-0 cmp-modal-campaign-name" id="modalCampaignName"></p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="campaignJoinForm" data-form-type="kampanya-basvuru" enctype="multipart/form-data">
                        <input type="hidden" name="form_type" value="kampanya-basvuru">
                        <input type="hidden" name="kampanya_id" id="joinCampaignId" value="">
                        <input type="hidden" name="kampanya_adi" id="joinCampaignTitle" value="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-page">
                                    <label>Adınız Soyadınız</label>
                                    <input type="text" name="ad_soyad" class="form-control" placeholder="Adınız ve soyadınız" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-page">
                                    <label>Telefon</label>
                                    <input type="tel" name="telefon" class="form-control" placeholder="05XX XXX XX XX" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group-page">
                                    <label>E-posta</label>
                                    <input type="email" name="eposta" class="form-control" placeholder="ornek@mail.com">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group-page">
                                    <label>Notunuz <small class="text-muted">(İsteğe bağlı)</small></label>
                                    <textarea name="not" class="form-control" rows="3" placeholder="Kampanya hakkında sormak istedikleriniz..."></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn cmp-btn-submit w-100">
                                    <i class="fas fa-paper-plane me-1"></i> Başvuru Gönder
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.cmp-btn-join-lg, .cmp-btn-join').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-campaign-id');
                var title = this.getAttribute('data-campaign-title');
                document.getElementById('joinCampaignId').value = id;
                document.getElementById('joinCampaignTitle').value = title;
                document.getElementById('modalCampaignName').textContent = title;
                var modal = new bootstrap.Modal(document.getElementById('campaignJoinModal'));
                modal.show();
            });
        });
    });
    </script>

    <?php include 'includes/footer.php'; ?>
    <?php exit; ?>
<?php } ?>

<?php
// ==================== KAMPANYA LİSTELEME ====================
$pageTitle = 'Sigorta Kampanyaları 2026 | Güncel İndirimler';
$pageDescription = 'Emre Sigorta\'nın 2026 güncel sigorta kampanyaları ve indirimlerinden yararlanın. Sınırlı süreli fırsatları kaçırmayın!';
$pageKeywords = 'sigorta kampanyaları, sigorta indirimleri, emre sigorta kampanya, trafik sigortası kampanya 2026, kasko kampanya, sağlık sigortası indirim';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Kampanyalar']
]) . '</script>';

// Pagination ayarları
$perPageOptions = [6, 15, 24, 48, 100];
$perPage = isset($_GET['adet']) ? (int)$_GET['adet'] : 6;
if (!in_array($perPage, $perPageOptions)) $perPage = 6;
$currentPage = max(1, (int)($_GET['sayfa'] ?? 1));

// Aktif kampanyaları çek
$allActiveCampaigns = getAllCampaigns(['active_now' => true]);
$totalActive = count($allActiveCampaigns);
$totalPages = max(1, ceil($totalActive / $perPage));
$currentPage = min($currentPage, $totalPages);
$offset = ($currentPage - 1) * $perPage;
$activeCampaigns = array_slice($allActiveCampaigns, $offset, $perPage);

// Popüler kampanyalar (tüm aktiflerden)
$popularCampaigns = array_filter($allActiveCampaigns, fn($c) => !empty($c['is_popular']));

// Süresi bitmiş kampanyalar
$expiredCampaigns = getAllCampaigns(['expired' => true]);
?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>Kampanyalar</h1>
        <p>Emre Sigorta'ya özel indirim ve kampanyalardan yararlanarak sigortanızı daha uygun fiyata yaptırın.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>Kampanyalar</span>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">

        <!-- Aktif Kampanyalar -->
        <h2 class="cmp-section-title">Aktif Kampanyalar</h2>
        <p class="cmp-section-subtitle">Sınırlı süreli fırsatları kaçırmayın!</p>

        <?php if (empty($activeCampaigns)): ?>
            <div class="cmp-empty">
                <i class="fas fa-bullhorn"></i>
                <p>Şu an aktif kampanya bulunmamaktadır. Yeni kampanyalar için takipte kalın!</p>
            </div>
        <?php else: ?>
            <div class="campaign-grid-3">
                <?php foreach ($activeCampaigns as $campaign):
                    $bgColor = $campaign['bg_color'] ?: '#1E3A8A';
                    $features = array_filter(explode("\n", $campaign['features'] ?? ''));
                    $features = array_slice($features, 0, 3); // Listede max 3 özellik
                    $endDate = new DateTime($campaign['end_date']);
                    $now = new DateTime();
                    $daysLeft = (int) $now->diff($endDate)->format('%r%a');
                    $detailUrl = SITE_URL . '/kampanyalar.php?detay=' . urlencode($campaign['slug']);
                    $hasImage = !empty($campaign['image']);
                ?>
                <a href="<?php echo $detailUrl; ?>" class="cmp-card-link">
                <div class="cmp-card<?php echo !empty($campaign['is_popular']) ? ' cmp-card-popular' : ''; ?>">
                    <?php if ($hasImage): ?>
                        <div class="cmp-card-img">
                            <img src="<?php echo SITE_URL . '/' . htmlspecialchars($campaign['image']); ?>" alt="<?php echo htmlspecialchars($campaign['title']); ?>">
                            <?php if ($campaign['discount_text']): ?>
                                <div class="cmp-discount-ribbon cmp-discount-ribbon-sm">
                                    <span><?php echo htmlspecialchars($campaign['discount_text']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="cmp-card-header" style="background: linear-gradient(135deg, <?php echo htmlspecialchars($bgColor); ?>, <?php echo htmlspecialchars($bgColor); ?>dd);">
                            <?php if ($campaign['discount_text']): ?>
                                <div class="cmp-discount-ribbon cmp-discount-ribbon-sm">
                                    <span><?php echo htmlspecialchars($campaign['discount_text']); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($campaign['icon']): ?>
                                <div class="cmp-icon"><i class="<?php echo htmlspecialchars($campaign['icon']); ?>"></i></div>
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($campaign['title']); ?></h3>
                        </div>
                    <?php endif; ?>
                    <div class="cmp-card-body">
                        <div class="cmp-card-meta-bar">
                            <?php if (!empty($campaign['is_popular'])): ?>
                                <span class="cmp-meta-popular"><i class="fas fa-fire"></i> Popüler</span>
                            <?php endif; ?>
                            <span class="cmp-meta-stats">
                                <i class="far fa-eye"></i> <?php echo number_format($campaign['views'] ?? 0); ?>
                                <i class="far fa-envelope ms-2"></i> <?php echo number_format($campaign['inquiry_count'] ?? 0); ?>
                            </span>
                        </div>
                        <?php if ($hasImage): ?>
                            <h3 class="cmp-card-title"><?php echo htmlspecialchars($campaign['title']); ?></h3>
                        <?php endif; ?>
                        <?php if ($campaign['short_description']): ?>
                            <p class="cmp-desc"><?php echo htmlspecialchars($campaign['short_description']); ?></p>
                        <?php endif; ?>
                        <div class="cmp-card-footer">
                            <div class="cmp-footer-row">
                                <div class="cmp-timer">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>Kampanya Bitiş: <strong><?php echo $endDate->format('d.m.Y'); ?></strong></span>
                                </div>
                                <button type="button" class="btn btn-sm cmp-btn-join" data-campaign-id="<?php echo $campaign['id']; ?>" data-campaign-title="<?php echo htmlspecialchars($campaign['title']); ?>" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="fas fa-hand-point-up"></i> Katıl
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                </a>
                <?php endforeach; ?>
            </div>
            
            <?php if (true): ?>
            <div class="cmp-pagination-bar mt-5">
                <div class="cmp-pagination-info">
                    <span>Toplam <strong><?php echo $totalActive; ?></strong> kampanya</span>
                    <span class="cmp-pagination-separator">|</span>
                    <span>Sayfa <strong><?php echo $currentPage; ?></strong> / <strong><?php echo $totalPages; ?></strong></span>
                    <span class="cmp-pagination-separator">|</span>
                    <span>Sayfa başına:</span>
                    <select class="cmp-perpage-select" onchange="window.location.href='?adet='+this.value+'&sayfa=1'">
                        <?php foreach ($perPageOptions as $opt): ?>
                            <option value="<?php echo $opt; ?>" <?php echo $perPage === $opt ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <nav class="cmp-pagination-nav">
                    <ul class="pagination mb-0">
                        <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?adet=<?php echo $perPage; ?>&sayfa=<?php echo max(1, $currentPage - 1); ?>"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item <?php echo $p === $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?adet=<?php echo $perPage; ?>&sayfa=<?php echo $p; ?>"><?php echo $p; ?></a>
                        </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?adet=<?php echo $perPage; ?>&sayfa=<?php echo min($totalPages, $currentPage + 1); ?>"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>

        <?php endif; ?>

        <!-- En Çok Talep Edilen Kampanyalar -->
        <?php if (!empty($popularCampaigns)): ?>
        <div class="cmp-popular-section">
            <h2 class="cmp-section-title"><i class="fas fa-fire text-warning me-2"></i>En Çok Talep Edilen</h2>
            <p class="cmp-section-subtitle">Müşterilerimizin en çok tercih ettiği kampanyalar</p>
            <div class="cmp-popular-slider">
                <?php foreach ($popularCampaigns as $pc):
                    $pcBg = $pc['bg_color'] ?: '#1E3A8A';
                    $pcEnd = new DateTime($pc['end_date']);
                    $pcNow = new DateTime();
                    $pcDays = (int) $pcNow->diff($pcEnd)->format('%r%a');
                ?>
                <a href="<?php echo SITE_URL; ?>/kampanyalar.php?detay=<?php echo urlencode($pc['slug']); ?>" class="cmp-popular-card" style="border-left: 4px solid <?php echo htmlspecialchars($pcBg); ?>;">
                    <div class="cmp-popular-card-icon" style="background: <?php echo htmlspecialchars($pcBg); ?>;">
                        <i class="<?php echo htmlspecialchars($pc['icon'] ?: 'fas fa-tag'); ?>"></i>
                    </div>
                    <div class="cmp-popular-card-info">
                        <h5><?php echo htmlspecialchars($pc['title']); ?></h5>
                        <p><?php echo htmlspecialchars($pc['short_description']); ?></p>
                        <div class="cmp-popular-card-meta">
                            <?php if ($pc['discount_text']): ?>
                                <span class="cmp-popular-card-discount"><?php echo htmlspecialchars($pc['discount_text']); ?></span>
                            <?php endif; ?>
                            <span class="cmp-popular-card-time"><i class="far fa-clock"></i> Son <?php echo $pcDays; ?> gün</span>
                        </div>
                    </div>
                    <div class="cmp-popular-card-arrow"><i class="fas fa-chevron-right"></i></div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Süresi Biten Kampanyalar -->
        <?php if (!empty($expiredCampaigns)): ?>
        <div class="cmp-expired-section">
            <h2 class="cmp-section-title">Süresi Biten Kampanyalar</h2>
            <p class="cmp-section-subtitle">Bu kampanyaların süresi dolmuştur</p>
            <div class="campaign-grid-3">
                <?php foreach ($expiredCampaigns as $campaign):
                    $features = array_filter(explode("\n", $campaign['features'] ?? ''));
                    $features = array_slice($features, 0, 3);
                    $endDate = new DateTime($campaign['end_date']);
                ?>
                <div class="cmp-card cmp-card-expired">
                    <div class="cmp-card-header cmp-expired-header">
                        <span class="cmp-badge cmp-badge-expired">Süresi Doldu</span>
                        <?php if ($campaign['icon']): ?>
                            <div class="cmp-icon"><i class="<?php echo htmlspecialchars($campaign['icon']); ?>"></i></div>
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($campaign['title']); ?></h3>
                    </div>
                    <div class="cmp-card-body">
                        <?php if ($campaign['short_description']): ?>
                            <p class="cmp-desc"><?php echo htmlspecialchars($campaign['short_description']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($features)): ?>
                            <ul class="cmp-features">
                                <?php foreach ($features as $feature): ?>
                                    <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars(trim($feature)); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <div class="cmp-card-footer">
                            <div class="cmp-timer cmp-timer-expired">
                                <i class="far fa-calendar-times"></i>
                                <span>Bitiş: <?php echo $endDate->format('d.m.Y'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Kampanyaya Katıl Modal -->
        <div class="modal fade" id="campaignJoinModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header cmp-modal-header">
                        <div>
                            <h5 class="modal-title"><i class="fas fa-bullhorn me-2"></i>Kampanyaya Katıl</h5>
                            <p class="mb-0 cmp-modal-campaign-name" id="modalCampaignName"></p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="campaignJoinForm" data-form-type="kampanya-basvuru" enctype="multipart/form-data">
                            <input type="hidden" name="form_type" value="kampanya-basvuru">
                            <input type="hidden" name="kampanya_id" id="joinCampaignId" value="">
                            <input type="hidden" name="kampanya_adi" id="joinCampaignTitle" value="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group-page">
                                        <label>Adınız Soyadınız</label>
                                        <input type="text" name="ad_soyad" class="form-control" placeholder="Adınız ve soyadınız" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-page">
                                        <label>Telefon</label>
                                        <input type="tel" name="telefon" class="form-control" placeholder="05XX XXX XX XX" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group-page">
                                        <label>E-posta</label>
                                        <input type="email" name="eposta" class="form-control" placeholder="ornek@mail.com">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group-page">
                                        <label>Notunuz <small class="text-muted">(İsteğe bağlı)</small></label>
                                        <textarea name="not" class="form-control" rows="3" placeholder="Kampanya hakkında sormak istedikleriniz..."></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn cmp-btn-submit w-100">
                                        <i class="fas fa-paper-plane me-1"></i> Başvuru Gönder
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kampanya Kuralları -->
        <div class="cmp-rules">
            <h3><i class="fas fa-info-circle me-2"></i>Kampanya Koşulları</h3>
            <ul>
                <li>Kampanyalar stokla sınırlıdır ve belirtilen tarihler arasında geçerlidir.</li>
                <li>Kampanya indirimleri diğer indirimlerle birleştirilemez.</li>
                <li>Emre Sigorta kampanya koşullarında değişiklik yapma hakkını saklı tutar.</li>
                <li>Kampanya kapsamındaki poliçeler standart iptal koşullarına tabidir.</li>
                <li>Detaylı bilgi için müşteri hizmetlerimizi arayabilirsiniz: <?php echo SITE_PHONE; ?></li>
            </ul>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cmp-btn-join').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-campaign-id');
            var title = this.getAttribute('data-campaign-title');
            document.getElementById('joinCampaignId').value = id;
            document.getElementById('joinCampaignTitle').value = title;
            document.getElementById('modalCampaignName').textContent = title;
            var modal = new bootstrap.Modal(document.getElementById('campaignJoinModal'));
            modal.show();
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
