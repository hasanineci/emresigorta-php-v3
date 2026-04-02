<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Kısa Süreli Trafik Sigortası | 1-30 Gün Geçici Poliçe';
$pageDescription = 'Kısa süreli trafik sigortası ile 1-30 gün arası aracınızı güvence altına alın. Araç devir, geçici kullanım veya acil durumlar için ideal. Emre Sigorta\'dan anında poliçe.';
$pageKeywords = 'kısa süreli trafik sigortası, geçici trafik sigortası, günlük trafik sigortası, 1 günlük sigorta, araç devir sigortası kısa süreli, emre sigorta';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Kısa Süreli Trafik Sigortası', '1-30 gün süreli geçici trafik sigortası. Araç devir işlemleri için ideal.', 'https://' . SITE_DOMAIN . '/kisa-sureli-trafik.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Kısa Süreli Trafik Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>
<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Kısa Süreli Trafik Sigortası</h1>
                <p>1 ila 30 gün arasında geçerli kısa süreli trafik sigortası. Araç devir, geçici kullanım veya acil durumlar için ideal çözüm.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> 1-30 gün süreli poliçe</li>
                    <li><i class="fa-solid fa-circle-check"></i> Araç devir işlemleri için ideal</li>
                    <li><i class="fa-solid fa-circle-check"></i> Anında poliçe oluşturma</li>
                    <li><i class="fa-solid fa-circle-check"></i> Uygun fiyat</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Kısa Süreli Trafik Teklifi</h3>
                <p class="form-subtitle">Hemen kısa süreli poliçenizi oluşturun</p>
                <form data-form-type="kisa-sureli-trafik">
                    <input type="hidden" name="form_type" value="kisa-sureli-trafik">
                    <div class="row g-3">
                        <div class="col-md-4"><div class="form-group-page"><label>Ruhsat Sahibi Adı Soyadı</label><input type="text" name="ruhsat_sahibi" placeholder="Ruhsat sahibinin adı ve soyadı" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" maxlength="11" placeholder="T.C. Kimlik Numaranız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Araç Plakası</label><input type="text" name="plaka" placeholder="Örn: 34 ABC 123" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Araç Ruhsat Seri No</label><input type="text" name="ruhsat_seri_no" placeholder="Ruhsat seri numarası" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Süre</label>
                            <select name="sure" required><option value="">Seçiniz</option><option value="2_ay">2 Ay</option><option value="3_ay">3 Ay</option><option value="4_ay">4 Ay</option><option value="6_ay">6 Ay</option><option value="9_ay">9 Ay</option></select>
                        </div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Telefon</label><input type="tel" name="telefon" placeholder="05XX XXX XX XX" required></div></div>
                        <div class="col-12"><button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-3"><i class="fa-solid fa-bolt"></i> Teklif Al</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<section class="page-content">
    <div class="container">
        <div class="content-wrapper">
            <h2>Kısa Süreli Trafik Sigortası Nedir?</h2>
            <p>Kısa süreli trafik sigortası, standart 1 yıllık poliçe yerine 1 ila 30 gün arasında geçerli olan zorunlu trafik sigortası türüdür. Araç devir işlemleri, geçici araç kullanımı, yıllık poliçe yenileme arasındaki boşluk dönemleri ve acil durumlar için idealdir.</p>
            
            <h2>Ne Zaman Gerekli?</h2>
            <ul>
                <li><strong>Araç devir:</strong> Satın aldığınız aracın devir işlemi için kısa süreli poliçe gerekebilir.</li>
                <li><strong>Araç nakli:</strong> Aracınızı bir ilden diğerine nakil ederken.</li>
                <li><strong>Geçici kullanım:</strong> Kısa süreli araç kullanım ihtiyaclarında.</li>
                <li><strong>Poliçe boşluğu:</strong> Mevcut poliçenin bitimi ile yenisi arasındaki sürede.</li>
            </ul>

            <div class="info-box warning">
                <p><strong>Hatırlatma:</strong> Kısa süreli trafik sigortası, standart poliçenin alternatifi değil, geçici çözümüdür. Mümkün olan en kısa sürede yıllık poliçenize geçiş yapmanız tavsiye edilir.</p>
            </div>
        </div>
    </div>
</section>
<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Acil Trafik Sigortanızı Hemen Alın!</h2>
            <p>Dakikalar içinde kısa süreli trafik sigortanızı oluşturun.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
