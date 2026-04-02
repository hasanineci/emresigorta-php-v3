<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Trafik Sigortası Fiyatları 2026 | En Uygun Teklif Al';
$pageDescription = 'Zorunlu trafik sigortası fiyatlarını karşılaştırın, en uygun teklifi alın. 20+ sigorta şirketinden anında trafik sigortası teklifi. Taksit imkanı, 7/24 destek. Şanlıurfa Emre Sigorta güvencesiyle.';
$pageKeywords = 'trafik sigortası, zorunlu trafik sigortası, trafik sigortası fiyatları 2026, trafik sigortası hesaplama, trafik sigortası teklifi, en ucuz trafik sigortası, şanlıurfa trafik sigortası, online trafik sigortası, emre sigorta';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Zorunlu Trafik Sigortası', 'Zorunlu trafik sigortası teklifi alın. 20+ sigorta şirketinden en uygun fiyat garantisi.', 'https://' . SITE_DOMAIN . '/trafik-sigortasi.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Trafik Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<!-- Product Hero -->
<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Trafik Sigortası</h1>
                <p>Zorunlu trafik sigortanızı Emre Sigorta güvencesiyle en uygun fiyata yaptırın. 20'den fazla sigorta şirketinden anlık teklif alın, karşılaştırın ve tasarruf edin.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> 30+ sigorta şirketinden anlık teklif</li>
                    <li><i class="fa-solid fa-circle-check"></i> En uygun fiyat garantisi</li>
                    <li><i class="fa-solid fa-circle-check"></i> Dakikalar içinde dijital poliçe</li>
                    <li><i class="fa-solid fa-circle-check"></i> 7/24 hasar desteği</li>
                    <li><i class="fa-solid fa-circle-check"></i> Taksit imkanı</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Hızlı Teklif Al</h3>
                <p class="form-subtitle">Trafik sigortası teklifinizi hemen alın</p>
                <form id="trafikForm" data-form-type="trafik" enctype="multipart/form-data">
                    <input type="hidden" name="form_type" value="trafik">
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

<!-- Breadcrumb -->
<div class="page-content" style="padding: 15px 0; background: var(--gray-100);">
    <div class="container">
        <div class="breadcrumb" style="justify-content: flex-start;">
            <a href="<?php echo SITE_URL; ?>" style="color: var(--primary);">Ana Sayfa</a>
            <span style="color: var(--gray-400);">/</span>
            <a href="#" style="color: var(--primary);">Ürünlerimiz</a>
            <span style="color: var(--gray-400);">/</span>
            <span style="color: var(--gray-600);">Trafik Sigortası</span>
        </div>
    </div>
</div>

<!-- Page Content -->
<section class="page-content">
    <div class="container">
        <div class="content-wrapper">
            <h2>Trafik Sigortası Nedir?</h2>
            <p>Trafik sigortası, Türkiye'de trafiğe çıkan tüm motorlu araç sahiplerinin yaptırmak zorunda olduğu zorunlu mali sorumluluk sigortasıdır. 2918 sayılı Karayolları Trafik Kanunu'na göre, her araç sahibi bu sigortayı yaptırmakla yükümlüdür. Bu sigorta, aracınızla üçüncü kişilere verebileceğiniz maddi ve bedeni zararları karşılar.</p>
            
            <p>Trafik sigortası, trafik kazası sonucu karşı tarafın uğradığı zararları teminat altına alır. Kendi aracınızdaki hasarlar bu sigorta kapsamında değildir; kendi aracınızı koruma altına almak için kasko sigortası yaptırmanız gerekmektedir.</p>

            <h2>Trafik Sigortası Teminatları</h2>
            <p>2026 yılı itibarıyla zorunlu trafik sigortası aşağıdaki teminatları kapsamaktadır:</p>
            
            <table class="coverage-table">
                <thead>
                    <tr>
                        <th>Teminat Türü</th>
                        <th>Kişi Başına</th>
                        <th>Kaza Başına</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Maddi Hasar</td>
                        <td>-</td>
                        <td>Güncel limit</td>
                    </tr>
                    <tr>
                        <td>Bedensel Hasar (Sakatlık)</td>
                        <td>Güncel limit</td>
                        <td>Güncel limit</td>
                    </tr>
                    <tr>
                        <td>Ölüm Teminatı</td>
                        <td>Güncel limit</td>
                        <td>Güncel limit</td>
                    </tr>
                    <tr>
                        <td>Tedavi Giderleri</td>
                        <td>Güncel limit</td>
                        <td>Güncel limit</td>
                    </tr>
                </tbody>
            </table>

            <div class="info-box">
                <p><strong>Önemli:</strong> Teminat limitleri Hazine ve Maliye Bakanlığı tarafından her yıl güncellenmektedir. Güncel limitler için müşteri temsilcilerimize danışabilirsiniz.</p>
            </div>

            <h2>Trafik Sigortası Fiyatlarını Etkileyen Faktörler</h2>
            <p>Trafik sigortası fiyatları birçok faktöre bağlı olarak değişiklik gösterir. Bu faktörleri bilmek, daha uygun fiyatlı bir poliçe bulmanıza yardımcı olacaktır:</p>
            
            <ul>
                <li><strong>Araç tipi ve modeli:</strong> Aracınızın markası, modeli ve motor hacmi fiyatı doğrudan etkiler.</li>
                <li><strong>Trafik ceza puanı:</strong> Trafik ihlalleri ve ceza puanlarınız priminizi artırabilir.</li>
                <li><strong>Hasarsızlık indirimi:</strong> Geçmiş yıllarda hasar kaydınız yoksa önemli indirimler elde edebilirsiniz.</li>
                <li><strong>İl ve ilçe:</strong> Aracınızın tescilli olduğu bölge, kaza istatistiklerine göre fiyatı etkiler.</li>
                <li><strong>Araç yaşı:</strong> Yeni araçlar genellikle daha yüksek primlere sahiptir.</li>
                <li><strong>Kullanım amacı:</strong> Ticari veya hususi kullanım ayrımı fiyatta farklılık yaratır.</li>
                <li><strong>Sigorta şirketi:</strong> Her sigorta şirketinin kendi tarife yapısı ve indirimleri bulunmaktadır.</li>
            </ul>

            <h2>Hasarsızlık İndirimi Nedir?</h2>
            <p>Hasarsızlık indirimi, trafik sigortanızda hasar kaydı bulunmayan her yıl için uygulanan bir indirim sistemidir. Bu sistem, güvenli sürücüleri ödüllendirmek amacıyla oluşturulmuştur.</p>
            
            <ul>
                <li>1. yıl hasarsız: Başlangıç basamağı</li>
                <li>2. yıl hasarsız: %10 indirim</li>
                <li>3. yıl hasarsız: %15 indirim</li>
                <li>4. yıl hasarsız: %20 indirim</li>
                <li>5. yıl hasarsız: %30 indirim</li>
                <li>6. yıl hasarsız: %40 indirim</li>
                <li>7. yıl ve üzeri: %50'ye varan indirim</li>
            </ul>
            
            <div class="info-box success">
                <p><strong>Tasarruf İpucu:</strong> Hasarsızlık basamağınızı koruyarak her yıl daha uygun fiyatlı trafik sigortası alabilirsiniz. Emre Sigorta ile hasarsızlık indiriminiz otomatik olarak hesaplanır.</p>
            </div>

            <h2>Trafik Sigortası Yaptırmamanın Cezası</h2>
            <p>Zorunlu trafik sigortası yaptırmamak ciddi yaptırımlarla sonuçlanabilir:</p>
            
            <ul>
                <li><strong>Trafik cezası:</strong> Sigortasız araç kullanmak yüksek miktarda para cezası gerektirir.</li>
                <li><strong>Araç trafikten men:</strong> Sigortası olmayan araçlar trafikten men edilebilir.</li>
                <li><strong>Mali sorumluluk:</strong> Kaza durumunda tüm zararlar kişisel olarak karşılanmak zorundadır.</li>
                <li><strong>Hukuki süreç:</strong> Sigortasız araçla yapılan kazalarda hukuki sorumluluk tamamen araç sahibine aittir.</li>
            </ul>

            <h2>Emre Sigorta ile Trafik Sigortası Nasıl Alınır?</h2>
            <ol>
                <li><strong>Bilgi girin:</strong> T.C. kimlik numaranızı ve araç plakasını girin.</li>
                <li><strong>Teklifleri karşılaştırın:</strong> 20'den fazla sigorta şirketinin tekliflerini anlık olarak görüntüleyin.</li>
                <li><strong>En uygununu seçin:</strong> Fiyat ve teminat karşılaştırması yaparak size en uygun teklifi seçin.</li>
                <li><strong>Ödeme yapın:</strong> Kredi kartı veya havale ile güvenli ödeme yapın.</li>
                <li><strong>Poliçenizi alın:</strong> Dijital poliçeniz anında e-posta ve SMS ile tarafınıza iletilir.</li>
            </ol>

            <h2>Sıkça Sorulan Sorular</h2>
            
            <h3>Trafik sigortası ne zaman yenilenir?</h3>
            <p>Trafik sigortası yıllık olarak yenilenir. Poliçe bitiş tarihinden önce yenileme işleminizi yapmanız gerekmektedir. Emre Sigorta, poliçenizin bitmesine yakın size hatırlatma bildirimi gönderir.</p>
            
            <h3>Plaka değişikliğinde trafik sigortası ne olur?</h3>
            <p>Araç satışı veya plaka değişikliğinde mevcut trafik sigortası poliçesi yeni araç sahibine devredilir. Kalan süre için prim iadesi de talep edilebilir.</p>
            
            <h3>Trafik sigortası online alınabilir mi?</h3>
            <p>Evet, Emre Sigorta üzerinden trafik sigortanızı tamamen online olarak alabilirsiniz. T.C. kimlik numaranız ve araç plakanız ile dakikalar içinde poliçenizi oluşturabilirsiniz. Güvenli ödeme altyapımız ve dijital poliçe sistemi ile tüm işleminizi evden çıkmadan tamamlayabilirsiniz.</p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>En Uygun Trafik Sigortasını Bulun!</h2>
            <p>30+ sigorta şirketinden anlık teklif alın, karşılaştırın ve %40'a varan indirimlerle tasarruf edin.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                <i class="fa-solid fa-bolt"></i> Hemen Teklif Al
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
