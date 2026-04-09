<?php
require_once __DIR__ . '/includes/config.php';
http_response_code(404);
$pageTitle = 'Sayfa Bulunamadı (404)';
$pageDescription = 'Aradığınız sayfa bulunamadı. Emre Sigorta ana sayfasına dönebilir veya aşağıdaki bağlantıları kullanabilirsiniz.';
$pageKeywords = '404, sayfa bulunamadı, emre sigorta';
$pageSchema = '';
include 'includes/header.php';
?>

<section class="page-banner" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
    <div class="container text-center">
        <h1>Sayfa Bulunamadı</h1>
        <p>Aradığınız sayfa mevcut değil veya kaldırılmış olabilir.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div style="padding: 40px 20px;">
                    <div style="font-size: 120px; font-weight: 900; color: #e9ecef; line-height: 1; margin-bottom: 10px;">404</div>
                    <h2 class="fw-bold mb-3" style="color: #333;">Üzgünüz, bu sayfayı bulamadık</h2>
                    <p class="text-muted mb-4" style="font-size: 16px; max-width: 500px; margin: 0 auto;">
                        Aradığınız sayfa taşınmış, silinmiş veya hiç var olmamış olabilir. Aşağıdaki bağlantılardan devam edebilirsiniz.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
                        <a href="<?php echo SITE_URL; ?>/" class="btn btn-primary btn-lg rounded-pill px-4">
                            <i class="fas fa-home me-2"></i>Ana Sayfa
                        </a>
                        <a href="<?php echo SITE_URL; ?>/iletisim.php" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                            <i class="fas fa-envelope me-2"></i>İletişim
                        </a>
                        <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="btn btn-outline-success btn-lg rounded-pill px-4">
                            <i class="fas fa-phone me-2"></i>Bizi Arayın
                        </a>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top">
                    <h5 class="fw-bold mb-4">Popüler Sayfalar</h5>
                    <div class="row g-3 text-start">
                        <div class="col-md-4">
                            <div class="list-group list-group-flush">
                                <a href="<?php echo SITE_URL; ?>/trafik-sigortasi.php" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-car text-primary me-2"></i>Trafik Sigortası</a>
                                <a href="<?php echo SITE_URL; ?>/kasko.php" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-shield-halved text-primary me-2"></i>Kasko</a>
                                <a href="<?php echo SITE_URL; ?>/dask.php" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-house text-primary me-2"></i>DASK</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="list-group list-group-flush">
                                <a href="<?php echo SITE_URL; ?>/tamamlayici-saglik.php" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-heart-pulse text-success me-2"></i>Tamamlayıcı Sağlık</a>
                                <a href="<?php echo SITE_URL; ?>/ozel-saglik.php" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-stethoscope text-success me-2"></i>Özel Sağlık</a>
                                <a href="<?php echo SITE_URL; ?>/konut-sigortasi.php" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-home text-info me-2"></i>Konut Sigortası</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="list-group list-group-flush">
                                <a href="<?php echo SITE_URL; ?>/hakkimizda.php" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-building text-secondary me-2"></i>Hakkımızda</a>
                                <a href="<?php echo SITE_URL; ?>/blog.php" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-blog text-secondary me-2"></i>Blog</a>
                                <a href="<?php echo SITE_URL; ?>/sss.php" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-question-circle text-secondary me-2"></i>SSS</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
