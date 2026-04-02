<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Yeşil Kart Sigortası | Yurt Dışı Araç Sigortası Teklifi';
$pageDescription = 'Yeşil kart sigortası ile yurt dışında aracınızı güvence altına alın. 46+ ülkede geçerli uluslararası trafik sigortası. Emre Sigorta\'dan hızlı ve uygun fiyatlı teklif alın.';
$pageKeywords = 'yeşil kart sigortası, yurt dışı araç sigortası, uluslararası trafik sigortası, yeşil kart fiyatı, avrupa araç sigortası, yurt dışı seyahat araç, emre sigorta';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Yeşil Kart Sigortası', 'Yurt dışına çıkacak araçlar için uluslararası geçerliliğe sahip yeşil kart sigortası.', 'https://' . SITE_DOMAIN . '/yesil-kart.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Yeşil Kart Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Yeşil Kart Sigortası</h1>
                <p>Yurt dışına aracınızla mı çıkacaksınız? Yeşil Kart Sigortası ile yurt dışında da güvende olun. Uluslararası geçerliliğe sahip zorunlu trafik sigortanızı Emre Sigorta'dan alın.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> 46+ ülkede geçerli</li>
                    <li><i class="fa-solid fa-circle-check"></i> Uluslararası teminat</li>
                    <li><i class="fa-solid fa-circle-check"></i> Hızlı poliçe oluşturma</li>
                    <li><i class="fa-solid fa-circle-check"></i> Gümrükte kabul garantisi</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Yeşil Kart Teklifi</h3>
                <p class="form-subtitle">Yurt dışı seyahatiniz için hemen teklif alın</p>
                <form data-form-type="yesil-kart">
                    <input type="hidden" name="form_type" value="yesil-kart">
                    <div class="row g-3">
                        <div class="col-md-4"><div class="form-group-page"><label>Ruhsat Sahibi Adı Soyadı</label><input type="text" name="ruhsat_sahibi" placeholder="Ruhsat sahibinin adı ve soyadı" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" placeholder="T.C. Kimlik Numaranız" maxlength="11" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Araç Plakası</label><input type="text" name="plaka" placeholder="Örn: 34 ABC 123" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Gidilecek Ülke</label>
                            <select name="ulke" required>
                                <option value="">Ülke Seçiniz</option>
                                <option>Almanya</option><option>Fransa</option><option>İtalya</option>
                                <option>Yunanistan</option><option>Bulgaristan</option><option>Avusturya</option>
                            </select>
                        </div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Süre</label>
                            <select name="sure" required>
                                <option value="">Süre Seçiniz</option>
                                <option>15 Gün</option><option>1 Ay</option><option>3 Ay</option><option>6 Ay</option><option>1 Yıl</option>
                            </select>
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
            <h2>Yeşil Kart Sigortası Nedir?</h2>
            <p>Yeşil Kart Sigortası (Green Card), aracınızla yurt dışına çıktığınızda geçerli olan uluslararası motorlu taşıt mali sorumluluk sigortasıdır. Bu sigorta, yurt dışında üçüncü kişilere verebileceğiniz maddi ve bedeni zararları teminat altına alır.</p>
            
            <p>Yeşil Kart Sistemi, 1953 yılında Birleşmiş Milletler Avrupa Ekonomik Komisyonu tarafından kurulmuştur ve günümüzde 46'dan fazla ülkede geçerlidir. Türkiye'den aracıyla yurt dışına çıkan her sürücü, Yeşil Kart poliçesi yaptırmak zorundadır.</p>

            <h2>Yeşil Kart Nerede Geçerli?</h2>
            <p>Yeşil Kart Sigortası aşağıdaki ülkelerde geçerlidir:</p>
            <ul>
                <li><strong>Avrupa Birliği ülkeleri:</strong> Almanya, Fransa, İtalya, İspanya, Hollanda, Belçika, Avusturya, vb.</li>
                <li><strong>Balkan ülkeleri:</strong> Yunanistan, Bulgaristan, Romanya, Sırbistan, Kuzey Makedonya, vb.</li>
                <li><strong>Diğer Avrupa ülkeleri:</strong> İngiltere, İsviçre, Norveç, İsveç, vb.</li>
                <li><strong>Kuzey Afrika ve Orta Doğu:</strong> Tunus, Fas, İran, vb.</li>
            </ul>

            <h2>Yeşil Kart Sigortası Teminatları</h2>
            <ul>
                <li>Yurt dışında üçüncü kişilere verilecek maddi zararlar</li>
                <li>Yurt dışında üçüncü kişilere verilecek bedeni zararlar</li>
                <li>Gittiğiniz ülkenin zorunlu trafik sigortası teminatları</li>
                <li>Yasal savunma masrafları</li>
            </ul>

            <div class="info-box warning">
                <p><strong>Uyarı:</strong> Yeşil Kart Sigortası sadece üçüncü şahıslara verilen zararları karşılar. Kendi aracınızdaki hasarlar için yurt dışı kasko teminatı eklemeniz gerekir.</p>
            </div>

            <h2>Yeşil Kart Fiyatları</h2>
            <p>Fiyatlar; gidilecek ülkeye, süreye ve araç tipine göre değişir. Emre Sigorta ile en uygun teklifi anında alabilirsiniz.</p>

            <h2>Yurt Dışı Seyahatte Dikkat Edilmesi Gerekenler</h2>
            <ol>
                <li>Seyahatten en az 1 hafta önce Yeşil Kart poliçenizi hazırlayın</li>
                <li>Poliçenizin geçerlilik tarihlerini kontrol edin</li>
                <li>Gittiğiniz ülkenin trafik kurallarını öğrenin</li>
                <li>Araç ruhsatı ve ehliyet belgenizin uluslararası geçerliliğini kontrol edin</li>
                <li>Olası kaza durumunda gidilecek ülkedeki acil numaraları öğrenin</li>
            </ol>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Yurt Dışı Seyahatiniz İçin Hemen Güvence Alın!</h2>
            <p>Yeşil Kart Sigortanızı online olarak dakikalar içinde yaptırın.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
