<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Evim Güvende Paketi | DASK + Konut Sigortası Tek Pakette';
$pageDescription = 'Evim Güvende paket sigortası ile DASK ve konut sigortasını tek pakette birleştirin. Deprem, yangın, hırsızlık teminatı. Emre Sigorta güvencesiyle kapsamlı ev koruması.';
$pageKeywords = 'evim güvende sigortası, paket ev sigortası, dask konut paketi, ev sigortası paket, konut paketi sigortası, emre sigorta evim güvende';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Evim Güvende Paketi', 'DASK ve konut sigortasını tek pakette birleştiren kapsamlı ev koruma sigortası.', 'https://' . SITE_DOMAIN . '/evim-guvende.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Evim Güvende Paketi']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>
<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Evim Güvende Paketi</h1>
                <p>DASK ve konut sigortasını tek pakette birleştiren Evim Güvende ile evinizi tüm risklere karşı kapsamlı şekilde koruma altına alın.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> DASK + Konut tek pakette</li>
                    <li><i class="fa-solid fa-circle-check"></i> Deprem, yangın, hırsızlık teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Ev eşyası koruma</li>
                    <li><i class="fa-solid fa-circle-check"></i> Çilingir, tesisatçı hizmeti</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Evim Güvende Teklifi</h3>
                <p class="form-subtitle">Eviniz için paket teklif alın</p>
                <form data-form-type="evim-guvende">
                    <input type="hidden" name="form_type" value="evim-guvende">
                    <div class="row g-3">
                        <div class="col-md-4"><div class="form-group-page"><label>Ad Soyad</label><input type="text" name="ad_soyad" placeholder="Adınız ve Soyadınız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" maxlength="11" placeholder="T.C. Kimlik Numaranız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>İl</label>
                            <select name="il" required><option value="">Seçiniz</option><option>İstanbul</option><option>Ankara</option><option>İzmir</option><option>Bursa</option><option>Antalya</option></select>
                        </div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Brüt m²</label><input type="number" name="brut_metrekare" placeholder="Örn: 120" required></div></div>
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
            <h2>Evim Güvende Nedir?</h2>
            <p>Evim Güvende, DASK (zorunlu deprem sigortası) ve kapsamlı konut sigortasını tek bir pakette sunan avantajlı bir üründür. Bu paket sayesinde evinizi deprem dahil tüm risklere karşı tek poliçe ile koruma altına alabilirsiniz.</p>
            
            <h2>Paket İçeriği</h2>
            <ul>
                <li><strong>DASK:</strong> Zorunlu deprem sigortası teminatları</li>
                <li><strong>Yangın:</strong> Her türlü yangın hasarı</li>
                <li><strong>Hırsızlık:</strong> Ev eşyası hırsızlık teminatı</li>
                <li><strong>Su Hasarı:</strong> Dahili su ve tesisat arızası</li>
                <li><strong>Doğal Afet:</strong> Fırtına, sel, dolu hasarları</li>
                <li><strong>Asistans:</strong> 7/24 çilingir, tesisatçı, elektrikçi hizmeti</li>
                <li><strong>Sorumluluk:</strong> Komşulara karşı mali sorumluluk</li>
            </ul>

            <div class="info-box success">
                <p><strong>Avantaj:</strong> DASK ve konut sigortasını ayrı ayrı almak yerine Evim Güvende paketini tercih ederek hem zamandan hem de paradan tasarruf edin.</p>
            </div>
        </div>
    </div>
</section>
<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Eviniz İçin Tam Koruma!</h2>
            <p>Evim Güvende paketi ile tek poliçede tam güvence sağlayın.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
