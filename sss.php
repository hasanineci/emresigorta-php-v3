<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Sıkça Sorulan Sorular (SSS)';
$pageDescription = 'Sigorta hakkında sıkça sorulan sorular ve cevapları. Trafik sigortası, kasko, DASK, sağlık sigortası, ödeme ve poliçe iptal işlemleri hakkında merak ettiğiniz her şey.';
$pageKeywords = 'sigorta sıkça sorulan sorular, sigorta sss, trafik sigortası soru cevap, kasko soru cevap, dask soru, sağlık sigortası soru, emre sigorta sss, sigorta bilgi';

// DB'den FAQ verilerini çek
$faqGrouped = getFaqsGroupedByCategory(true);

// FAQ Schema - DB'den oluştur
$faqItems = [];
foreach ($faqGrouped as $cat) {
    foreach ($cat['faqs'] as $f) {
        $faqItems[] = ['question' => $f['question'], 'answer' => $f['answer']];
    }
}

$pageSchema = '<script type="application/ld+json">' . getFAQSchema($faqItems) . '</script>';

// Breadcrumb Schema
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Sıkça Sorulan Sorular']
]) . '</script>';

?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>Sıkça Sorulan Sorular</h1>
        <p>Sigorta ürünleri ve hizmetlerimiz hakkında merak edilen soruların yanıtlarını bulun.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>Sıkça Sorulan Sorular</span>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper" style="max-width: 900px; margin: 0 auto;">

            <?php if (empty($faqGrouped)): ?>
            <p class="text-center text-muted py-5">Henüz soru eklenmemiş.</p>
            <?php else: ?>
            <?php foreach ($faqGrouped as $catIdx => $category): ?>
            <h2<?php echo $catIdx > 0 ? ' style="margin-top: 40px;"' : ''; ?>><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <div class="faq-section">
                <?php foreach ($category['faqs'] as $faq): ?>
                <div class="faq-item">
                    <div class="faq-question">
                        <span><?php echo htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <div class="faq-answer">
                        <p><?php echo nl2br(htmlspecialchars($faq['answer'], ENT_QUOTES, 'UTF-8')); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

            <!-- İletişim CTA -->
            <div style="background: var(--primary); color: #fff; border-radius: 16px; padding: 40px; text-align: center; margin-top: 50px;">
                <h3 style="color: #fff; margin-bottom: 10px;">Sorunuzun Yanıtını Bulamadınız mı?</h3>
                <p style="color: rgba(255,255,255,0.85); margin-bottom: 20px;">Uzman ekibimize ulaşarak tüm sorularınızın yanıtını alabilirsiniz.</p>
                <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                    <a href="iletisim.php" class="btn btn-light">Bize Yazın</a>
                    <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="btn" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-phone-alt"></i> <?php echo SITE_PHONE; ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
