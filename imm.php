<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'İMM Sigortası | İhtiyari Mali Mesuliyet Teklifi Al';
$pageDescription = 'İhtiyari mali mesuliyet (İMM) sigortası ile trafik sigortası teminat limitlerini artırın. Büyük kazalarda yüksek tazminat risklerini minimize edin. Emre Sigorta\'dan uygun İMM teklifi alın.';
$pageKeywords = 'imm sigortası, ihtiyari mali mesuliyet sigortası, imm fiyat, imm teklif, ek trafik sigortası, teminat artırma, emre sigorta imm';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('İMM - İhtiyari Mali Mesuliyet Sigortası', 'Trafik sigortası teminat limitlerini artıran İMM sigortası teklifi.', 'https://' . SITE_DOMAIN . '/imm.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'İMM Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>İMM Sigortası</h1>
                <p>İhtiyari Mali Mesuliyet (İMM) sigortası ile zorunlu trafik sigortası limitlerinin üzerinde güvence sağlayın. Büyük kazalarda oluşabilecek yüksek tazminat risklerini minimize edin.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Trafik sigortası üzeri teminat</li>
                    <li><i class="fa-solid fa-circle-check"></i> Yüksek maddi hasar teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Yüksek bedeni hasar teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Uygun prim</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>İMM Teklif Al</h3>
                <p class="form-subtitle">Ek güvence için hemen teklif alın</p>
                <form data-form-type="imm">
                    <input type="hidden" name="form_type" value="imm">
                    <div class="row g-3">
                        <div class="col-md-4"><div class="form-group-page"><label>Ruhsat Sahibi Adı Soyadı</label><input type="text" name="ruhsat_sahibi" placeholder="Ruhsat sahibinin adı ve soyadı" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" maxlength="11" placeholder="T.C. Kimlik Numaranız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Araç Plakası</label><input type="text" name="plaka" placeholder="Örn: 34 ABC 123" required></div></div>
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
            <h2>İMM Sigortası Nedir?</h2>
            <p>İhtiyari Mali Mesuliyet (İMM) sigortası, zorunlu trafik sigortası teminat limitlerinin üzerinde kalan zararları karşılayan isteğe bağlı bir sigorta türüdür. Büyük kazalarda zorunlu trafik sigortası limitleri yeterli olmayabilir ve bu durumda aradaki fark araç sahibinin cebinden çıkmak zorunda kalır. İMM sigortası bu riski ortadan kaldırır.</p>

            <p>Özellikle lüks araçlarla karışan kazalarda, çoklu araç kazalarında veya ciddi bedensel yaralanmalarda tazminat tutarları zorunlu trafik sigortası limitlerinin çok üzerine çıkabilmektedir. İMM sigortası, bu tür durumlar için ek mali güvence sağlar.</p>

            <h2>İMM Sigortası Neden Gerekli?</h2>
            <ul>
                <li><strong>Yüksek tazminat riskleri:</strong> Ciddi kazalarda milyonlarca TL'lik tazminat talepleri oluşabilir</li>
                <li><strong>Trafik sigortası limitleri:</strong> Zorunlu trafik sigortası limitleri her zaman yeterli olmayabilir</li>
                <li><strong>Mali koruma:</strong> Kişisel mal varlığınızı yüksek tazminat taleplerine karşı korur</li>
                <li><strong>Hukuki güvence:</strong> Dava masraflarını ve hukuki süreç giderlerini karşılar</li>
                <li><strong>Uygun prim:</strong> Sağladığı yüksek teminata göre oldukça uygun fiyatlıdır</li>
            </ul>

            <h2>İMM Teminat Kapsamı</h2>
            <table class="coverage-table">
                <thead><tr><th>Teminat</th><th>Açıklama</th></tr></thead>
                <tbody>
                    <tr><td><strong>Maddi Hasar</strong></td><td>Trafik sigortası limitinin üzerindeki maddi hasarlar</td></tr>
                    <tr><td><strong>Bedensel Hasar</strong></td><td>Yaralanma kaynaklı tedavi ve tazminat giderleri</td></tr>
                    <tr><td><strong>Ölüm Tazminatı</strong></td><td>Ölüm halinde limit üstü tazminat ödemeleri</td></tr>
                    <tr><td><strong>Manevi Tazminat</strong></td><td>Manevi tazminat talepleri (seçimlik)</td></tr>
                </tbody>
            </table>

            <div class="info-box">
                <p><strong>Öneri:</strong> İMM sigortasını trafik sigortanız ve kaskonuzla birlikte alarak tam koruma sağlayabilirsiniz. Özellikle yoğun trafikte sık kullanılan araçlar için şiddetle tavsiye edilir.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>İMM ile Tam Güvence Altına Girin!</h2>
            <p>Uygun fiyatlı İMM sigortası teklifinizi hemen alın.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
