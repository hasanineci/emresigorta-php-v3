<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Ferdi Kaza Sigortası | Geniş Asistanslı Kaza Güvencesi';
$pageDescription = 'Geniş asistanslı ferdi kaza sigortası ile kendinizi ve ailenizi güvence altına alın. Kaza sonucu ölüm, sürekli sakatlık, tedavi masrafları teminatı. Emre Sigorta\'dan uygun fiyatlı teklif.';
$pageKeywords = 'ferdi kaza sigortası, kişisel kaza sigortası, kaza sigortası, asistanslı sigorta, ölüm ve sakatlık teminatı, ferdi kaza fiyat, emre sigorta ferdi kaza';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Ferdi Kaza Sigortası', 'Geniş asistanslı ferdi kaza sigortası. Ölüm, sürekli sakatlık ve tedavi masrafları teminatı.', 'https://' . SITE_DOMAIN . '/ferdi-kaza.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Ferdi Kaza Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>
<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Geniş Asistanslı Ferdi Kaza Sigortası</h1>
                <p>Günlük hayatta karşılaşabileceğiniz kazalara karşı güvence sağlayan ferdi kaza sigortası ile kendinizi ve ailenizi koruma altına alın.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Kaza sonucu ölüm teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Sürekli sakatlık teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Tedavi masrafları</li>
                    <li><i class="fa-solid fa-circle-check"></i> 7/24 asistans hizmeti</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Ferdi Kaza Teklifi</h3>
                <p class="form-subtitle">Kaza risklerine karşı güvence alın</p>
                <form data-form-type="ferdi-kaza">
                    <input type="hidden" name="form_type" value="ferdi-kaza">
                    <div class="row g-3">
                        <div class="col-md-4"><div class="form-group-page"><label>Ad Soyad</label><input type="text" name="ad_soyad" placeholder="Adınız ve Soyadınız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" maxlength="11" placeholder="T.C. Kimlik Numaranız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Meslek</label><input type="text" name="meslek" placeholder="Mesleğiniz" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Telefon</label><input type="tel" name="telefon" placeholder="05XX XXX XX XX" required></div></div>
                        <div class="col-md-4"><button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-3"><i class="fa-solid fa-bolt"></i> Teklif Al</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<section class="page-content">
    <div class="container">
        <div class="content-wrapper">
            <h2>Ferdi Kaza Sigortası Nedir?</h2>
            <p>Ferdi kaza sigortası, günlük hayatta, işte veya seyahatte karşılaşabileceğiniz kazalar sonucu oluşan ölüm, sürekli sakatlık ve geçici iş göremezlik durumlarında mali güvence sağlayan bir sigorta ürünüdür.</p>
            
            <h2>Teminat Kapsamı</h2>
            <table class="coverage-table">
                <thead><tr><th>Teminat</th><th>Açıklama</th></tr></thead>
                <tbody>
                    <tr><td><strong>Kaza Sonucu Ölüm</strong></td><td>Kaza sonucu vefat halinde yasal mirasçılara ödenen tazminat</td></tr>
                    <tr><td><strong>Sürekli Sakatlık</strong></td><td>Kaza sonucu kalıcı sakatlık durumunda tazminat</td></tr>
                    <tr><td><strong>Tedavi Masrafları</strong></td><td>Kaza sonucu tedavi giderleri</td></tr>
                    <tr><td><strong>Geçici İş Göremezlik</strong></td><td>Kaza sonucu çalışamama süresinde günlük tazminat</td></tr>
                    <tr><td><strong>Ambulans</strong></td><td>Acil müdahale ve ambulans hizmetleri</td></tr>
                    <tr><td><strong>Asistans</strong></td><td>7/24 tıbbi danışmanlık ve yönlendirme</td></tr>
                </tbody>
            </table>

            <h2>Geniş Asistans Hizmetleri</h2>
            <ul>
                <li>7/24 tıbbi danışmanlık hattı</li>
                <li>Acil ambulans koordinasyonu</li>
                <li>İkinci tıbbi görüş hizmeti</li>
                <li>Evde bakım ve hemşire hizmeti</li>
                <li>İlaç tedarik desteği</li>
                <li>Rehabilitasyon yönlendirmesi</li>
            </ul>

            <div class="info-box">
                <p><strong>Bilgi:</strong> Ferdi kaza sigortası özellikle serbest meslek sahipleri, esnalar ve riskli işlerde çalışanlar için oldukça önemlidir. Uygun primlerle geniş kapsamlı güvence sağlar.</p>
            </div>
        </div>
    </div>
</section>
<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Kendinizi Güvence Altına Alın!</h2>
            <p>Ferdi kaza sigortası tekliflerini hemen karşılaştırın.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
