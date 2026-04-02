<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Sigorta Kampanyaları 2026 | Güncel İndirimler';
$pageDescription = 'Emre Sigorta\'nın 2026 güncel sigorta kampanyaları ve indirimlerinden yararlanın. Sınırlı süreli fırsatları kaçırmayın!';
$pageKeywords = 'sigorta kampanyaları, sigorta indirimleri, emre sigorta kampanya, trafik sigortası kampanya 2026, kasko kampanya, sağlık sigortası indirim';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Kampanyalar']
]) . '</script>';

// Aktif kampanyaları çek
$activeCampaigns = getAllCampaigns(['active_now' => true]);
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
            <div class="campaign-grid">
                <?php foreach ($activeCampaigns as $campaign):
                    $bgColor = $campaign['bg_color'] ?: '#1E3A8A';
                    $features = array_filter(explode("\n", $campaign['features'] ?? ''));
                    $endDate = new DateTime($campaign['end_date']);
                    $now = new DateTime();
                    $daysLeft = (int) $now->diff($endDate)->format('%r%a');
                    $hasImage = !empty($campaign['image']);
                ?>
                <div class="cmp-card">
                    <?php if ($hasImage): ?>
                        <div class="cmp-card-image" style="background-image: url('<?php echo SITE_URL . '/' . htmlspecialchars($campaign['image']); ?>');">
                            <div class="cmp-card-image-overlay" style="background: linear-gradient(135deg, <?php echo htmlspecialchars($bgColor); ?>cc, <?php echo htmlspecialchars($bgColor); ?>99);"></div>
                            <?php if ($campaign['discount_text']): ?>
                                <span class="cmp-badge"><?php echo htmlspecialchars($campaign['discount_text']); ?></span>
                            <?php endif; ?>
                            <div class="cmp-card-image-content">
                                <?php if ($campaign['icon']): ?>
                                    <div class="cmp-icon"><i class="<?php echo htmlspecialchars($campaign['icon']); ?>"></i></div>
                                <?php endif; ?>
                                <h3><?php echo htmlspecialchars($campaign['title']); ?></h3>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="cmp-card-header" style="background: linear-gradient(135deg, <?php echo htmlspecialchars($bgColor); ?>, <?php echo htmlspecialchars($bgColor); ?>dd);">
                            <?php if ($campaign['discount_text']): ?>
                                <span class="cmp-badge"><?php echo htmlspecialchars($campaign['discount_text']); ?></span>
                            <?php endif; ?>
                            <?php if ($campaign['icon']): ?>
                                <div class="cmp-icon"><i class="<?php echo htmlspecialchars($campaign['icon']); ?>"></i></div>
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($campaign['title']); ?></h3>
                        </div>
                    <?php endif; ?>
                    <div class="cmp-card-body">
                        <?php if ($campaign['short_description']): ?>
                            <p class="cmp-desc"><?php echo htmlspecialchars($campaign['short_description']); ?></p>
                        <?php endif; ?>
                        <?php if ($campaign['description']): ?>
                            <p class="cmp-desc-full"><?php echo nl2br(htmlspecialchars($campaign['description'])); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($features)): ?>
                            <ul class="cmp-features">
                                <?php foreach ($features as $feature): ?>
                                    <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars(trim($feature)); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <div class="cmp-card-footer">
                            <div class="cmp-timer">
                                <i class="far fa-clock"></i>
                                <?php if ($daysLeft > 0): ?>
                                    <span>Son <strong><?php echo $daysLeft; ?></strong> gün</span>
                                <?php elseif ($daysLeft === 0): ?>
                                    <span class="text-danger"><strong>Son gün!</strong></span>
                                <?php endif; ?>
                                <small><?php echo $endDate->format('d.m.Y'); ?></small>
                            </div>
                            <div class="cmp-actions">
                                <?php if ($campaign['link_url']): ?>
                                    <a href="<?php echo htmlspecialchars($campaign['link_url']); ?>" class="btn btn-sm cmp-btn-detail">
                                        <?php echo htmlspecialchars($campaign['link_text'] ?: 'Detay'); ?>
                                    </a>
                                <?php endif; ?>
                                <button type="button" class="btn btn-sm cmp-btn-join" data-campaign-id="<?php echo $campaign['id']; ?>" data-campaign-title="<?php echo htmlspecialchars($campaign['title']); ?>">
                                    <i class="fas fa-hand-point-up"></i> Kampanyaya Katıl
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Süresi Biten Kampanyalar -->
        <?php if (!empty($expiredCampaigns)): ?>
        <div class="cmp-expired-section">
            <h2 class="cmp-section-title">Süresi Biten Kampanyalar</h2>
            <p class="cmp-section-subtitle">Bu kampanyaların süresi dolmuştur</p>
            <div class="campaign-grid">
                <?php foreach ($expiredCampaigns as $campaign):
                    $features = array_filter(explode("\n", $campaign['features'] ?? ''));
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
    // Kampanyaya Katıl butonları
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
