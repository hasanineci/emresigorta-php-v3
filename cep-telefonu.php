<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Cep Telefonu Sigortası | Ekran Kırılma ve Hırsızlık Güvencesi';
$pageDescription = 'Cep telefonu sigortası ile telefonunuzu kırılma, düşme, sıvı teması ve hırsızlığa karşı koruyun. iPhone, Samsung, Xiaomi tüm markalar. Emre Sigorta güvencesiyle.';
$pageKeywords = 'cep telefonu sigortası, telefon sigortası, ekran kırılma sigortası, telefon hırsızlık sigortası, iphone sigortası, samsung sigortası, cep telefonu koruma planı, emre sigorta';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Cep Telefonu Sigortası', 'Cep telefonları için kırılma, düşme ve hırsızlık sigortası.', 'https://' . SITE_DOMAIN . '/cep-telefonu.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Cep Telefonu Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Cep Telefonu Sigortası</h1>
                <p>Cep telefonunuzu kırılma, düşme, sıvı teması ve hırsızlığa karşı güvence altına alın. Pahalı telefonunuzu Emre Sigorta koruma planı ile koruyun.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Ekran kırılması teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Sıvı hasarı teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Hırsızlık teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Tüm marka ve modeller</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Telefon Sigortası Teklifi</h3>
                <p class="form-subtitle">Telefonunuz için koruma planı alın</p>
                <form data-form-type="cep-telefonu">
                    <input type="hidden" name="form_type" value="cep-telefonu">
                    <div class="row g-3">
                        <div class="col-md-4"><div class="form-group-page"><label>Ad Soyad</label><input type="text" name="ad_soyad" placeholder="Adınız ve Soyadınız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" maxlength="11" placeholder="T.C. Kimlik Numaranız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Telefon Markası</label>
                            <select name="telefon_markasi" required><option value="">Seçiniz</option><option>Apple iPhone</option><option>Samsung</option><option>Xiaomi</option><option>Huawei</option><option>Oppo</option><option>Diğer</option></select>
                        </div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>IMEI Numarası</label><input type="text" name="imei" placeholder="IMEI numaranız" required></div></div>
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
            <h2>Cep Telefonu Sigortası Nedir?</h2>
            <p>Cep telefonu sigortası, akıllı telefonunuzu fiziksel hasar, ekran kırılması, sıvı teması, hırsızlık ve elektronik arıza gibi risklere karşı koruma altına alan bir sigorta ürünüdür. Günümüzde akıllı telefonların fiyatları oldukça yüksek olduğundan, beklenmedik hasarlar ciddi maddi kayıplara neden olabilir.</p>

            <h2>Teminat Kapsamı</h2>
            <ul>
                <li><strong>Ekran Kırılması:</strong> Düşme ve darbe sonucu oluşan ekran hasarları</li>
                <li><strong>Sıvı Teması:</strong> Su, kahve, çay vb. sıvılarla meydana gelen hasarlar</li>
                <li><strong>Hırsızlık:</strong> Gasp ve kapkaç yoluyla çalınma (polis raporu gerekir)</li>
                <li><strong>Elektronik Arıza:</strong> Garanti dışı elektronik arızalar</li>
                <li><strong>Pil Hasarı:</strong> Batarya şişmesi ve ilgili hasarlar</li>
            </ul>

            <h2>Nasıl Çalışır?</h2>
            <ol>
                <li>Telefonunuzun marka, model ve IMEI bilgilerini girin</li>
                <li>Size uygun koruma planını seçin</li>
                <li>Ödemenizi online yapın</li>
                <li>Hasar durumunda Emre Sigorta'ya başvurun</li>
                <li>Anlaşmalı teknik serviste onarım veya tazminat alın</li>
            </ol>

            <div class="info-box">
                <p><strong>Not:</strong> Cep telefonu sigortası, telefonun satın alınmasından itibaren belirli bir süre içinde yaptırılmalıdır. Genellikle satın almadan sonraki 30-60 gün içinde poliçe oluşturulabilir.</p>
            </div>

            <h2>Hangi Telefonlar Sigortalanabilir?</h2>
            <p>Apple iPhone, Samsung Galaxy, Xiaomi, Huawei, Oppo, Google Pixel ve diğer tüm marka/modeller sigortalanabilir. Telefonun yaşı ve değerine göre prim hesaplanır.</p>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Telefonunuzu Koruma Altına Alın!</h2>
            <p>Cep telefonu sigortası ile ekran kırılması, sıvı hasarı ve hırsızlığa karşı güvence sağlayın.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
