<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Evcil Hayvan Sigortası | Kedi ve Köpek Sağlık Güvencesi';
$pageDescription = 'Evcil hayvan sigortası ile kedi ve köpeğinizin sağlığını güvence altına alın. Veteriner masrafları, ameliyat giderleri, aşı ve tedavi teminatları. Emre Sigorta pati sigortası.';
$pageKeywords = 'evcil hayvan sigortası, pati sigortası, kedi sigortası, köpek sigortası, hayvan sağlık sigortası, veteriner sigortası, evcil hayvan sağlık, emre sigorta';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Evcil Hayvan Sigortası', 'Kedi ve köpekler için sağlık sigortası. Veteriner masrafları, ameliyat ve tedavi teminatı.', 'https://' . SITE_DOMAIN . '/evcil-hayvan.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Evcil Hayvan Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Evcil Hayvan Sigortası</h1>
                <p>Sevimli dostunuzun sağlığını güvence altına alın. Veteriner masrafları, ameliyat giderleri ve daha fazlası evcil hayvan sigortası ile karşılansın.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Veteriner tedavi giderleri</li>
                    <li><i class="fa-solid fa-circle-check"></i> Ameliyat masrafları</li>
                    <li><i class="fa-solid fa-circle-check"></i> Kaza ve hastalık teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Sorumluluk sigortası</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Pati Sigortası Teklifi</h3>
                <p class="form-subtitle">Dostunuz için en uygun planı alın</p>
                <form data-form-type="evcil-hayvan">
                    <input type="hidden" name="form_type" value="evcil-hayvan">
                    <div class="row g-3">
                        <div class="col-md-4"><div class="form-group-page"><label>Ad Soyad</label><input type="text" name="ad_soyad" placeholder="Adınız ve Soyadınız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" maxlength="11" placeholder="T.C. Kimlik Numaranız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Hayvan Türü</label>
                            <select name="hayvan_turu" required><option value="">Seçiniz</option><option>Kedi</option><option>Köpek</option></select>
                        </div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Hayvan Yaşı</label>
                            <select name="hayvan_yasi" required><option value="">Seçiniz</option><option>0-1 Yaş</option><option>1-3 Yaş</option><option>3-5 Yaş</option><option>5-8 Yaş</option><option>8+ Yaş</option></select>
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
            <h2>Evcil Hayvan Sigortası Nedir?</h2>
            <p>Evcil hayvan sigortası (pati sigortası), kedi ve köpek sahiplerine yönelik olarak tasarlanmış, evcil hayvanınızın sağlık giderlerini karşılayan bir sigorta ürünüdür. Veteriner masrafları, ameliyat giderleri, ilaç masrafları ve üçüncü kişilere verilen zararlar bu sigorta kapsamında değerlendirilir.</p>

            <h2>Teminat Kapsamı</h2>
            <table class="coverage-table">
                <thead><tr><th>Teminat</th><th>Açıklama</th></tr></thead>
                <tbody>
                    <tr><td><strong>Veteriner Tedavi</strong></td><td>Hastalık ve kaza sonucu veteriner muayene ve tedavi giderleri</td></tr>
                    <tr><td><strong>Ameliyat</strong></td><td>Cerrahi müdahale ve anestezi masrafları</td></tr>
                    <tr><td><strong>İlaç</strong></td><td>Tedavi amaçlı reçeteli ilaç giderleri</td></tr>
                    <tr><td><strong>Görüntüleme</strong></td><td>Röntgen, ultrason gibi tanı yöntemleri</td></tr>
                    <tr><td><strong>Sorumluluk</strong></td><td>Evcil hayvanınızın üçüncü kişilere verdiği zarar</td></tr>
                    <tr><td><strong>Kayıp Arama</strong></td><td>Evcil hayvanınızın kaybolması halinde arama masrafları</td></tr>
                </tbody>
            </table>

            <h2>Kimler Yaptırabilir?</h2>
            <ul>
                <li>Mikroçipli ve aşıları tam kedi sahipleri</li>
                <li>Mikroçipli ve aşıları tam köpek sahipleri</li>
                <li>Hayvan 8 haftadan büyük ve 8 yaşından küçük olmalıdır (ilk poliçe için)</li>
            </ul>

            <div class="info-box">
                <p><strong>Biliyor muydunuz?</strong> Evcil hayvan veteriner masrafları yılda binlerce TL'yi bulabilir. Pati sigortası ile beklenmedik sağlık giderlerini kolayca karşılayabilirsiniz.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Dostunuzun Sağlığını Güvence Altına Alın!</h2>
            <p>Evcil hayvan sigortası tekliflerini hemen karşılaştırın.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
