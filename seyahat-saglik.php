<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Seyahat Sağlık Sigortası | Vize İçin Seyahat Sigortası';
$pageDescription = 'Seyahat sağlık sigortası ile yurt dışında güvende olun. Schengen vize başvurularında geçerli poliçe. Acil sağlık, bagaj kaybı, uçuş gecikme teminatları. Emre Sigorta\'dan hemen alın.';
$pageKeywords = 'seyahat sağlık sigortası, seyahat sigortası, vize sigortası, schengen sigortası, yurt dışı sağlık sigortası, seyahat sağlık sigortası fiyat, emre sigorta';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Seyahat Sağlık Sigortası', 'Yurt dışı seyahatler için sağlık sigortası. Schengen vize başvurularında geçerli.', 'https://' . SITE_DOMAIN . '/seyahat-saglik.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Seyahat Sağlık Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Seyahat Sağlık Sigortası</h1>
                <p>Yurt dışı seyahatlerinizde sağlığınızı güvence altına alın. Vize başvuruları için geçerli, kapsamlı seyahat sigortanızı Emre Sigorta'dan alın.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Schengen vize başvurularında geçerli</li>
                    <li><i class="fa-solid fa-circle-check"></i> 30.000€ ve üzeri teminat</li>
                    <li><i class="fa-solid fa-circle-check"></i> Acil tıbbi müdahale</li>
                    <li><i class="fa-solid fa-circle-check"></i> Tıbbi tahliye ve nakil</li>
                    <li><i class="fa-solid fa-circle-check"></i> Bagaj kaybı teminatı</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Seyahat Sigortası Teklifi</h3>
                <p class="form-subtitle">Seyahatiniz için en uygun sigortayı alın</p>
                <form data-form-type="seyahat-saglik">
                    <input type="hidden" name="form_type" value="seyahat-saglik">
                    <div class="row g-3">
                        <div class="col-md-4"><div class="form-group-page"><label>Ad Soyad</label><input type="text" name="ad_soyad" placeholder="Adınız ve Soyadınız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" maxlength="11" placeholder="T.C. Kimlik Numaranız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Gidilecek Bölge</label>
                            <select name="bolge" required>
                                <option value="">Seçiniz</option>
                                <option>Avrupa (Schengen)</option>
                                <option>ABD / Kanada</option>
                                <option>Dünya Geneli</option>
                            </select>
                        </div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Seyahat Başlangıç</label><input type="date" name="seyahat_baslangic" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Seyahat Bitiş</label><input type="date" name="seyahat_bitis" required></div></div>
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
            <h2>Seyahat Sağlık Sigortası Nedir?</h2>
            <p>Seyahat sağlık sigortası, yurt dışı seyahatleriniz sırasında karşılaşabileceğiniz sağlık sorunlarının tedavi masraflarını karşılayan bir sigorta ürünüdür. Özellikle Schengen vizesi başvurularında minimum 30.000 Euro teminatlı seyahat sigortası zorunludur.</p>

            <h2>Teminat Kapsamı</h2>
            <table class="coverage-table">
                <thead><tr><th>Teminat</th><th>Açıklama</th><th>Limit</th></tr></thead>
                <tbody>
                    <tr><td><strong>Acil Tedavi</strong></td><td>Yurt dışında acil sağlık hizmetleri</td><td>30.000€ - 100.000€</td></tr>
                    <tr><td><strong>Hastane Yatışı</strong></td><td>Yatarak tedavi ve ameliyat masrafları</td><td>Poliçe limiti</td></tr>
                    <tr><td><strong>Tıbbi Tahliye</strong></td><td>Ambulans uçak ile nakil</td><td>Sınırsız</td></tr>
                    <tr><td><strong>Cenaze Nakli</strong></td><td>Vefat halinde cenaze nakil masrafları</td><td>Sınırsız</td></tr>
                    <tr><td><strong>Bagaj Kaybı</strong></td><td>Kaybolmuş veya hasarlı bagaj tazminatı</td><td>500€ - 2.000€</td></tr>
                    <tr><td><strong>Uçuş İptali</strong></td><td>Sağlık nedeniyle uçuş iptali</td><td>500€ - 1.000€</td></tr>
                    <tr><td><strong>Hukuki Danışmanlık</strong></td><td>Yurt dışında hukuki destek</td><td>5.000€</td></tr>
                </tbody>
            </table>

            <h2>Schengen Vizesi İçin Seyahat Sigortası</h2>
            <p>Schengen bölgesi ülkelerine vize başvurusunda bulunurken, aşağıdaki koşulları sağlayan seyahat sağlık sigortası sunmanız gerekmektedir:</p>
            <ul>
                <li>Minimum 30.000 Euro teminat limiti</li>
                <li>Tüm Schengen bölgesi ülkelerinde geçerli olması</li>
                <li>Acil tıbbi müdahale ve hastane masraflarını kapsaması</li>
                <li>Tıbbi tahliye (medikal repatriasyon) teminatı içermesi</li>
                <li>Seyahat tarihlerini tam olarak kapsaması</li>
            </ul>
            
            <div class="info-box">
                <p><strong>Bilgi:</strong> Emre Sigorta'dan aldığınız seyahat sigortası poliçesi tüm Schengen ülkeleri konsoloslukları tarafından kabul edilmektedir. Poliçeniz İngilizce olarak düzenlenir.</p>
            </div>

            <h2>Hangi Durumlarda Seyahat Sigortası Gerekli?</h2>
            <ul>
                <li><strong>Tatil seyahatleri:</strong> Yurt dışı tatillerde beklenmedik sağlık sorunlarına karşı</li>
                <li><strong>İş seyahatleri:</strong> Profesyonel seyahatlerde güvence</li>
                <li><strong>Öğrenci değişimi:</strong> Erasmus ve diğer değişim programları</li>
                <li><strong>Vize başvurusu:</strong> Schengen ve diğer ülke vize başvuruları</li>
                <li><strong>Sportif faaliyetler:</strong> Kayak, dalış gibi riskli sporlar için ek teminat</li>
            </ul>

            <h2>Seyahat Sigortası Fiyatları</h2>
            <p>Fiyatlar; seyahat süresine, gidilecek bölgeye, teminat limitine ve sigortalı yaşına göre değişmektedir. Emre Sigorta'da birden fazla sigorta şirketinin tekliflerini anlık olarak karşılaştırabilirsiniz.</p>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Seyahatinizde Güvende Olun!</h2>
            <p>Uygun fiyatlı seyahat sigortanızı hemen online olarak alın.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
