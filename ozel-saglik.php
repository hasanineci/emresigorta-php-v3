<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Özel Sağlık Sigortası 2026 | Bireysel Sağlık Güvencesi';
$pageDescription = 'Özel sağlık sigortası ile Türkiye\'nin en iyi özel hastanelerinde ayrıcalıklı sağlık hizmeti alın. Geniş hastane ağı, kapsamlı teminatlar. Emre Sigorta\'dan kişiye özel teklif.';
$pageKeywords = 'özel sağlık sigortası, bireysel sağlık sigortası, sağlık sigortası fiyatları 2026, özel hastane sigortası, kapsamlı sağlık sigortası, emre sigorta sağlık';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Özel Sağlık Sigortası', 'Bireysel özel sağlık sigortası. Geniş hastane ağı, kapsamlı teminatlar.', 'https://' . SITE_DOMAIN . '/ozel-saglik.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Özel Sağlık Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Özel Sağlık Sigortası</h1>
                <p>SGK'dan bağımsız, kapsamlı özel sağlık sigortası ile sağlığınızı en üst düzeyde koruma altına alın. Türkiye'nin en iyi özel hastanelerinde ayrıcalıklı sağlık hizmeti.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Geniş hastane ağı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Yatarak ve ayakta tedavi</li>
                    <li><i class="fa-solid fa-circle-check"></i> Check-up hizmeti</li>
                    <li><i class="fa-solid fa-circle-check"></i> Doğum teminatı</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Özel Sağlık Teklifi</h3>
                <p class="form-subtitle">Size özel sağlık planınızı oluşturun</p>
                <form data-form-type="ozel-saglik">
                    <input type="hidden" name="form_type" value="ozel-saglik">
                    <div class="row g-3">
                        <div class="col-md-4"><div class="form-group-page"><label>Ad Soyad</label><input type="text" name="ad_soyad" placeholder="Adınız ve Soyadınız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" maxlength="11" placeholder="T.C. Kimlik Numaranız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Plan</label>
                            <select name="plan" required><option value="">Seçiniz</option><option>Bireysel</option><option>Aile</option><option>Çocuk</option></select>
                        </div></div>
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
            <h2>Özel Sağlık Sigortası Nedir?</h2>
            <p>Özel sağlık sigortası, SGK'dan bağımsız olarak çalışan ve özel sağlık kuruluşlarında kapsamlı sağlık hizmeti almanızı sağlayan bir sigorta ürünüdür. Tamamlayıcı sağlık sigortasından farklı olarak, SGK'lı olma zorunluluğu yoktur.</p>
            
            <p>Özel sağlık sigortası; yatarak tedavi, ayakta tedavi, check-up, laboratuvar, görüntüleme, fizik tedavi ve doğum gibi geniş kapsamlı teminatlar sunar. Bireysel, aile ve çocuk planları mevcuttur.</p>

            <h2>Özel Sağlık Sigortası Teminatları</h2>
            <table class="coverage-table">
                <thead><tr><th>Teminat</th><th>Açıklama</th></tr></thead>
                <tbody>
                    <tr><td><strong>Yatarak Tedavi</strong></td><td>Ameliyat, yoğun bakım, hastane yatış masrafları</td></tr>
                    <tr><td><strong>Ayakta Tedavi</strong></td><td>Doktor muayenesi, reçete, tetkik ve tahliller</td></tr>
                    <tr><td><strong>Doğum</strong></td><td>Normal doğum ve sezaryen (bekleme süresi uygulanır)</td></tr>
                    <tr><td><strong>Check-up</strong></td><td>Yıllık kapsamlı sağlık taraması</td></tr>
                    <tr><td><strong>Diş Tedavisi</strong></td><td>Temel diş tedavileri (ek teminat)</td></tr>
                    <tr><td><strong>Göz</strong></td><td>Göz muayenesi ve tedavisi</td></tr>
                    <tr><td><strong>Fizik Tedavi</strong></td><td>Fizik tedavi ve rehabilitasyon</td></tr>
                    <tr><td><strong>Psikolojik Destek</strong></td><td>Psikolog ve psikiyatrist konsültasyonları</td></tr>
                </tbody>
            </table>

            <h2>Özel Sağlık Sigortası vs TSS</h2>
            <ul>
                <li><strong>SGK Şartı:</strong> Özel sağlık sigortasında SGK zorunluluğu yoktur, TSS'de SGK'lı olmak gerekir.</li>
                <li><strong>Kapsam:</strong> Özel sağlık sigortası daha geniş kapsamlıdır.</li>
                <li><strong>Fiyat:</strong> TSS daha uygun fiyatlıdır; özel sağlık sigortası daha kapsamlı ancak yüksek primlidir.</li>
                <li><strong>Hastane Grubu:</strong> Özel sağlık sigortasında A grubu hastanelere erişim mümkündür.</li>
            </ul>

            <div class="info-box">
                <p><strong>Tavsiye:</strong> SGK'lı iseniz ve uygun fiyat arıyorsanız TSS, SGK'sız iseniz veya premium hizmet istiyorsanız özel sağlık sigortası tercih edebilirsiniz.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Sağlığınız Paha Biçilemez!</h2>
            <p>Size özel sağlık sigortası planını hemen keşfedin.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
