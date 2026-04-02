<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Tamamlayıcı Sağlık Sigortası 2026 | Özel Hastane Güvencesi';
$pageDescription = 'Tamamlayıcı sağlık sigortası ile özel hastanelerde uygun fiyatlı tedavi imkanı. SGK fark ücretlerinizi sigorta karşılasın. 2026 yılı güncel fiyatlarla Emre Sigorta\'dan teklif alın.';
$pageKeywords = 'tamamlayıcı sağlık sigortası, tamamlayıcı sağlık sigortası fiyatları 2026, tss sigortası, sgk tamamlayıcı, özel hastane sigortası, şanlıurfa sağlık sigortası, emre sigorta';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Tamamlayıcı Sağlık Sigortası', 'SGK fark ücretlerini karşılayan tamamlayıcı sağlık sigortası. Özel hastanelerde uygun fiyatlı tedavi.', 'https://' . SITE_DOMAIN . '/tamamlayici-saglik.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Tamamlayıcı Sağlık Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<!-- Product Hero -->
<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Tamamlayıcı Sağlık Sigortası</h1>
                <p>SGK'nın karşılamadığı sağlık giderlerinizi tamamlayıcı sağlık sigortası ile güvence altına alın. Özel hastanelerde uygun fiyatlı tedavi imkanı.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Özel hastanelerde geçerli</li>
                    <li><i class="fa-solid fa-circle-check"></i> SGK fark ücretleri karşılanır</li>
                    <li><i class="fa-solid fa-circle-check"></i> Yatarak ve ayakta tedavi</li>
                    <li><i class="fa-solid fa-circle-check"></i> Check-up hizmeti</li>
                    <li><i class="fa-solid fa-circle-check"></i> Uygun prim seçenekleri</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Sağlık Sigortası Teklifi</h3>
                <p class="form-subtitle">Size özel sağlık sigortası teklifini alın</p>
                <form data-form-type="tamamlayici-saglik">
                    <input type="hidden" name="form_type" value="tamamlayici-saglik">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Ad Soyad</label><input type="text" name="ad_soyad" placeholder="Adınız ve Soyadınız" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" placeholder="T.C. Kimlik Numaranız" maxlength="11" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Plan Tipi</label>
                                <select name="plan_tipi" required>
                                    <option value="">Seçiniz</option>
                                    <option value="bireysel">Bireysel</option>
                                    <option value="aile">Aile</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Telefon Numarası</label><input type="tel" name="telefon" placeholder="05XX XXX XX XX" required></div>
                        </div>
                        <div class="col-md-4">
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

<div class="page-content" style="padding: 15px 0; background: var(--gray-100);">
    <div class="container">
        <div class="breadcrumb" style="justify-content: flex-start;">
            <a href="<?php echo SITE_URL; ?>" style="color: var(--primary);">Ana Sayfa</a>
            <span style="color: var(--gray-400);">/</span>
            <a href="#" style="color: var(--primary);">Ürünlerimiz</a>
            <span style="color: var(--gray-400);">/</span>
            <span style="color: var(--gray-600);">Tamamlayıcı Sağlık Sigortası</span>
        </div>
    </div>
</div>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper">
            <h2>Tamamlayıcı Sağlık Sigortası Nedir?</h2>
            <p>Tamamlayıcı sağlık sigortası (TSS), SGK (Sosyal Güvenlik Kurumu) tarafından karşılanmayan veya kısmen karşılanan sağlık hizmetlerinin giderlerini tamamlayan bir sigorta ürünüdür. Bu sigorta sayesinde özel hastanelerdeki muayene, tetkik, ameliyat, yatışlı tedavi ve diğer sağlık hizmetlerinden çok daha uygun maliyetlerle yararlanabilirsiniz.</p>
            
            <p>SGK'lı olan herkes tamamlayıcı sağlık sigortası yaptırabilir. Bu sigorta, SGK'nın belirlediği fark ücretlerini karşılayarak özel sağlık hizmetlerine erişimi kolaylaştırır ve cebinizden çıkan tutarı minimize eder.</p>

            <h2>TSS Teminatları</h2>
            <table class="coverage-table">
                <thead>
                    <tr>
                        <th>Teminat</th>
                        <th>Açıklama</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Yatarak Tedavi</strong></td>
                        <td>Hastanede yatarak yapılan tüm tedaviler, ameliyatlar ve cerrahi müdahaleler</td>
                    </tr>
                    <tr>
                        <td><strong>Ayakta Tedavi</strong></td>
                        <td>Poliklinik muayeneleri, doktor vizitleri, reçeteli ilaçlar</td>
                    </tr>
                    <tr>
                        <td><strong>Görüntüleme</strong></td>
                        <td>MR, tomografi, ultrason, röntgen gibi görüntüleme hizmetleri</td>
                    </tr>
                    <tr>
                        <td><strong>Laboratuvar</strong></td>
                        <td>Kan tahlilleri, idrar tetkikleri ve diğer laboratuvar testleri</td>
                    </tr>
                    <tr>
                        <td><strong>Fizik Tedavi</strong></td>
                        <td>Fizik tedavi ve rehabilitasyon seansları</td>
                    </tr>
                    <tr>
                        <td><strong>Küçük Müdahale</strong></td>
                        <td>Günübirlik cerrahi işlemler ve küçük müdahaleler</td>
                    </tr>
                </tbody>
            </table>

            <h2>Neden Tamamlayıcı Sağlık Sigortası?</h2>
            <ul>
                <li><strong>Uygun maliyet:</strong> Özel sağlık sigortasına göre çok daha uygun primlerle kapsamlı teminat sağlar.</li>
                <li><strong>Geniş hastane ağı:</strong> Türkiye genelinde binlerce anlaşmalı özel hastane ve tıp merkezinde geçerlidir.</li>
                <li><strong>Fark ücreti minimizasyonu:</strong> SGK fark ücretlerinin büyük bölümünü karşılayarak cebinizden çıkan tutarı azaltır.</li>
                <li><strong>Hızlı randevu:</strong> Devlet hastanelerindeki uzun bekleme sürelerinden kurtulursunuz.</li>
                <li><strong>Kaliteli hizmet:</strong> Özel hastanelerin konforlu ve kaliteli sağlık hizmetlerinden yararlanırsınız.</li>
                <li><strong>Aile planları:</strong> Eş ve çocuklarınızı da kapsayan aile paketleri mevcuttur.</li>
            </ul>

            <div class="info-box">
                <p><strong>Biliyor muydunuz?</strong> Tamamlayıcı sağlık sigortası ile özel hastanelerdeki muayene ve tedavi giderlerinizin %80'ine kadar olan kısmı karşılanabilir. Ayda sadece birkaç yüz TL'lik prim ile ailenizi sağlık güvencesi altına alabilirsiniz.</p>
            </div>

            <h2>Kimler TSS Yaptırabilir?</h2>
            <p>TSS yaptırabilmek için aşağıdaki koşullardan birini sağlamanız gerekmektedir:</p>
            <ul>
                <li>SGK'ya (4A - işçi, 4B - esnaf/serbest meslek, 4C - memur) kayıtlı olmak</li>
                <li>Emekli olarak SGK kapsamında bulunmak</li>
                <li>SGK'lı bir kişinin bakmakla yükümlü olduğu aile bireyi olmak</li>
            </ul>
            
            <p>SSS'siz kişiler için özel sağlık sigortası alternatifleri sunmaktayız. Detaylı bilgi için müşteri temsilcilerimize ulaşabilirsiniz.</p>

            <h2>TSS Fiyatlarını Etkileyen Faktörler</h2>
            <ul>
                <li><strong>Yaş:</strong> Sigortalının yaşı prim hesaplamasında en önemli kriterdir.</li>
                <li><strong>Cinsiyet:</strong> Kadın ve erkek sigortalılar için primler farklılık gösterebilir.</li>
                <li><strong>Teminat kapsamı:</strong> Seçilen teminat paketi ve ek teminatlar fiyatı belirler.</li>
                <li><strong>Hastane grubu:</strong> A, B, C grubu hastane tercihiniz primi etkiler.</li>
                <li><strong>Mevcut sağlık durumu:</strong> Kronik hastalıklar ek prim veya teminat istisnalarına neden olabilir.</li>
            </ul>

            <h2>Sıkça Sorulan Sorular</h2>
            
            <h3>TSS ile özel sağlık sigortası arasındaki fark nedir?</h3>
            <p>TSS, SGK'yı temel alarak onun üzerine eklenen bir tamamlayıcı üründür ve SGK'lı olmayı gerektirir. Özel sağlık sigortası ise SGK'dan bağımsız olarak çalışır ve genellikle daha yüksek primlidir. TSS daha uygun fiyatlı bir alternatif sunar.</p>
            
            <h3>Kronik hastalıklarda TSS geçerli mi?</h3>
            <p>Bazı sigorta şirketleri kronik hastalıkları belirli bir bekleme süresi sonrasında teminat altına alırken, bazıları ek prim uygulayabilir. Detaylı bilgi için teklifinizi aldığınızda özel koşulları incelemeniz önemlidir.</p>
            
            <h3>TSS ne zaman devreye girer?</h3>
            <p>TSS poliçeniz, genellikle satın alım tarihinden itibaren hemen devreye girer. Ancak bazı teminatlar (hamilelik, kronik hastalık tedavileri gibi) için bekleme süreleri uygulanabilir. Standart bekleme süreleri 3-6 ay arasında değişmektedir.</p>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Sağlığınızı Güvence Altına Alın!</h2>
            <p>Uygun fiyatlı tamamlayıcı sağlık sigortası tekliflerinizi hemen karşılaştırın.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                <i class="fa-solid fa-bolt"></i> Hemen Teklif Al
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
