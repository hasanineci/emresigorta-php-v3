<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = '2. El Trafik Sigortası | İkinci El Araç Sigortası Teklifi';
$pageDescription = 'İkinci el araç aldınız mı? 2. el araç trafik sigortanızı Emre Sigorta\'dan plaka ve kimlik bilgilerinizle anında yaptırın. Hızlı poliçe, uygun fiyat, taksit imkanı.';
$pageKeywords = '2. el trafik sigortası, ikinci el araç sigortası, araç devir sigortası, ikinci el araba sigorta, araç devir trafik sigortası, şanlıurfa 2 el trafik, emre sigorta';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('2. El Trafik Sigortası', 'İkinci el araç trafik sigortası. Araç devir işlemlerinde anında poliçe.', 'https://' . SITE_DOMAIN . '/el-trafik-sigortasi.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => '2. El Trafik Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>2. El Trafik Sigortası</h1>
                <p>İkinci el araç satın aldınız mı? Emre Sigorta ile 2. el aracınız için hızlı ve uygun fiyatlı trafik sigortası yaptırın. Plaka ve kimlik bilgilerinizle anında teklif alın.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Araç devir işlemlerinde anında poliçe</li>
                    <li><i class="fa-solid fa-circle-check"></i> En uygun fiyat karşılaştırması</li>
                    <li><i class="fa-solid fa-circle-check"></i> Hasarsızlık indirimi aktarımı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Online işlem kolaylığı</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>2. El Trafik Teklifi</h3>
                <p class="form-subtitle">Araç devir sonrası hemen poliçe oluşturun</p>
                <form data-form-type="el-trafik" enctype="multipart/form-data">
                    <input type="hidden" name="form_type" value="el-trafik">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Ruhsat Sahibi Adı Soyadı</label><input type="text" name="ruhsat_sahibi" placeholder="Ruhsat sahibinin adı ve soyadı" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" placeholder="T.C. Kimlik Numaranız" maxlength="11" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Araç Plakası</label><input type="text" name="plaka" placeholder="Örn: 34 ABC 123" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page">
                                <label>Ruhsat Fotoğrafı</label>
                                <input type="file" name="ruhsat_foto" accept=".jpg,.jpeg,.png,.pdf" class="form-control">
                                <small class="text-muted">JPG, PNG veya PDF</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page">
                                <label>Engelli Aracı mı?</label>
                                <select name="arac_engeli" required>
                                    <option value="">Seçiniz</option>
                                    <option value="hayir">Hayır</option>
                                    <option value="evet">Evet</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Telefon Numarası</label><input type="tel" name="telefon" placeholder="05XX XXX XX XX" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>E-posta Adresi</label><input type="email" name="email" placeholder="ornek@email.com"></div>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-3">
                                <i class="fa-solid fa-bolt"></i> Teklif Al
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper">
            <h2>2. El Trafik Sigortası Nedir?</h2>
            <p>2. el trafik sigortası, ikinci el araç satın alan kişilerin araç devir işlemi sonrasında yaptırması gereken zorunlu trafik sigortasıdır. Araç el değiştirdiğinde, yeni araç sahibi adına yeni bir trafik sigortası poliçesi düzenlenmelidir.</p>
            
            <p>Araç satın aldığınızda, eski sahibin trafik sigortası poliçesinin süresi dolmamış olsa bile noterde devir işlemi sırasında yeni bir poliçe gerekebilir. Emre Sigorta ile bu süreci anlık olarak tamamlayabilir ve en uygun fiyatlı poliçeyi alabilirsiniz.</p>

            <h2>2. El Araç Alırken Sigorta İşlemleri</h2>
            <ol>
                <li><strong>Araç değer tespiti:</strong> Aracın güncel piyasa değerini belirleyin.</li>
                <li><strong>Ekspertiz raporu:</strong> Profesyonel ekspertiz yaptırarak aracın durumunu tespit edin.</li>
                <li><strong>Trafik sigortası:</strong> Devir öncesi veya aynı gün trafik sigortanızı yaptırın.</li>
                <li><strong>Kasko değerlendirmesi:</strong> Aracınız için kasko sigortası yaptırmayı değerlendirin.</li>
                <li><strong>Noter işlemi:</strong> Sigorta poliçenizle birlikte noter devir işlemini tamamlayın.</li>
            </ol>

            <h2>Hasarsızlık İndirimi Aktarımı</h2>
            <p>2. el araç satın aldığınızda, eski aracınızdan elde ettiğiniz hasarsızlık indirimi yeni aracınıza aktarılabilir. Bu sayede yıllardır biriktirdiğiniz hasarsızlık basamağını kaybetmeden yeni aracınızda da uygun fiyatlı poliçe alabilirsiniz.</p>
            
            <div class="info-box">
                <p><strong>Önemli:</strong> Hasarsızlık indirimi araç bazlı değil, kişi bazlıdır. Yani indiriminiz size aittir ve yeni satın aldığınız araca otomatik olarak uygulanır.</p>
            </div>

            <h2>2. El Araç Sigortasında Dikkat Edilmesi Gerekenler</h2>
            <ul>
                <li>Araç devir tarihinde sigortasız kalmamanız için önceden poliçe hazırlığı yapın</li>
                <li>Eski poliçenin iptal işlemini araç satıcısıyla koordine edin</li>
                <li>Hasarsızlık basamağınızı kontrol edin ve aktarım yapın</li>
                <li>Kasko sigortası için ekspertiz raporu alın</li>
                <li>Trafik ve kasko poliçelerini aynı anda yaptırarak zaman kazanın</li>
            </ul>

            <h2>Emre Sigorta Avantajları</h2>
            <p>Emre Sigorta ile 2. el araç trafik sigortanızı:</p>
            <ul>
                <li>20'den fazla sigorta şirketinden anlık karşılaştırma yaparak alabilirsiniz</li>
                <li>Noterden çıkmadan online poliçe oluşturabilirsiniz</li>
                <li>Hasarsızlık indiriminizi otomatik olarak aktarabilirsiniz</li>
                <li>Taksitli ödeme seçeneklerinden yararlanabilirsiniz</li>
                <li>7/24 müşteri desteği alabilirsiniz</li>
            </ul>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>2. El Aracınız İçin Hemen Teklif Alın!</h2>
            <p>Araç devir işleminizde zaman kaybetmeyin, anında poliçenizi oluşturun.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
