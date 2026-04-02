<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Pembe Kurdele Sigortası | Kadın Sağlık Güvencesi';
$pageDescription = 'Pembe Kurdele kadın sağlık sigortası ile meme kanseri ve kadına özgü hastalıklara karşı kapsamlı güvence. Erken teşhis teminatı, özel tedavi imkanı. Emre Sigorta\'dan teklif alın.';
$pageKeywords = 'pembe kurdele sigortası, kadın sağlık sigortası, meme kanseri sigortası, kadın hastalıkları sigortası, kadınlara özel sigorta, emre sigorta pembe kurdele';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Pembe Kurdele Sigortası', 'Kadın sağlığına özel tasarlanan Pembe Kurdele sigortası. Meme kanseri ve kadına özgü hastalık teminatı.', 'https://' . SITE_DOMAIN . '/pembe-kurdele.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Pembe Kurdele Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>
<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Pembe Kurdele Sigortası</h1>
                <p>Kadın sağlığına özel tasarlanan Pembe Kurdele sigortası ile meme kanseri ve kadına özgü hastalıklara karşı kapsamlı güvence sağlayın.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Meme kanseri teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Kadın hastalıkları teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Check-up hizmeti</li>
                    <li><i class="fa-solid fa-circle-check"></i> Psikolojik destek</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Pembe Kurdele Teklifi</h3>
                <p class="form-subtitle">Kadın sağlığınız için özel teklif alın</p>
                <form data-form-type="pembe-kurdele">
                    <input type="hidden" name="form_type" value="pembe-kurdele">
                    <div class="row g-3">
                        <div class="col-md-4"><div class="form-group-page"><label>Ad Soyad</label><input type="text" name="ad_soyad" placeholder="Adınız ve Soyadınız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" maxlength="11" placeholder="T.C. Kimlik Numaranız" required></div></div>
                        <div class="col-md-4"><div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div></div>
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
            <h2>Pembe Kurdele Sigortası Nedir?</h2>
            <p>Pembe Kurdele Sigortası, kadın sağlığına yönelik özel olarak tasarlanmış bir sigorta ürünüdür. Meme kanseri başta olmak üzere kadına özgü hastalıkların tanısında, tedavisinde ve takip sürecinde mali güvence sağlar. Erken teşhisin hayat kurtardığı bilinciyle oluşturulan bu ürün, düzenli tarama ve kontrol imkanları sunmaktadır.</p>
            
            <h2>Teminat Kapsamı</h2>
            <ul>
                <li><strong>Meme kanseri tanı ve tedavisi:</strong> Mamografi, biyopsi, kemoterapi, radyoterapi ve cerrahi</li>
                <li><strong>Rahim ağzı kanseri:</strong> Pap smear testi ve tedavi süreçleri</li>
                <li><strong>Kadın hastalıkları:</strong> Jinekolojik muayene ve tedaviler</li>
                <li><strong>Yıllık check-up:</strong> Kapsamlı kadın sağlığı taraması</li>
                <li><strong>Psikolojik destek:</strong> Tanı sonrası psikolojik danışmanlık</li>
                <li><strong>İkinci görüş:</strong> Farklı uzman doktorlardan ikinci görüş hakkı</li>
            </ul>

            <div class="info-box" style="border-left-color: #e91e63; background: #fce4ec;">
                <p><strong>Önemli:</strong> Düzenli mamografi ve tarama, meme kanserinin erken teşhisinde hayati önem taşır. Pembe Kurdele sigortası ile yıllık taramalarınızı aksatmadan yaptırabilirsiniz.</p>
            </div>
        </div>
    </div>
</section>
<section class="cta-section">
    <div class="container">
        <div class="cta-box" style="background: linear-gradient(135deg, #e91e63, #c2185b);">
            <h2>Sağlığınız İçin Harekete Geçin!</h2>
            <p>Pembe Kurdele sigortası ile kendinizi güvence altına alın.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
