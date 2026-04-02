<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Gizlilik Politikası | Veri Koruma';
$pageDescription = 'Emre Sigorta gizlilik politikası. Kişisel verilerinizin nasıl toplandığı, işlendiği, korunduğu ve haklarınız hakkında kapsamlı bilgilendirme.';
$pageKeywords = 'gizlilik politikası, gizlilik sözleşmesi, veri gizliliği, kişisel veri koruma, emre sigorta gizlilik';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Gizlilik Politikası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>Gizlilik Politikası</h1>
        <p>Kişisel verilerinizin nasıl toplandığı, kullanıldığı ve korunduğu hakkında bilgilendirme.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>Gizlilik Politikası</span>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper" style="max-width: 900px; margin: 0 auto;">
            <p><strong>Son Güncelleme:</strong> 01 Ocak 2025</p>

            <h2>1. Giriş</h2>
            <p><?php echo SITE_NAME; ?> Sigorta Aracılık Hizmetleri A.Ş. ("Şirket", "biz") olarak, gizliliğinize büyük önem veriyoruz. Bu Gizlilik Politikası, <?php echo SITE_DOMAIN; ?> web sitesini ("Site") kullanırken kişisel verilerinizin nasıl toplandığını, kullanıldığını, paylaşıldığını ve korunduğunu açıklamaktadır.</p>
            <p>Sitemizi kullanarak bu Gizlilik Politikası'nı kabul etmiş sayılırsınız.</p>

            <h2>2. Topladığımız Bilgiler</h2>
            <h3>2.1. Doğrudan Sağladığınız Bilgiler</h3>
            <ul>
                <li>Üyelik oluştururken: Ad, soyad, TC kimlik no, e-posta, telefon</li>
                <li>Teklif alırken: Araç bilgileri, adres bilgileri, doğum tarihi</li>
                <li>Poliçe satın alırken: Ödeme ve fatura bilgileri</li>
                <li>İletişim formlarında: İsim, iletişim bilgileri, mesaj içeriği</li>
            </ul>

            <h3>2.2. Otomatik Olarak Toplanan Bilgiler</h3>
            <ul>
                <li>IP adresi ve konum bilgisi (il/ilçe düzeyinde)</li>
                <li>Tarayıcı türü ve sürümü</li>
                <li>İşletim sistemi</li>
                <li>Ziyaret edilen sayfalar ve tıklanan bağlantılar</li>
                <li>Site üzerindeki oturum süresi</li>
                <li>Yönlendirme kaynağı (hangi siteden geldiniz)</li>
                <li>Çerez verileri</li>
            </ul>

            <h2>3. Bilgilerin Kullanım Amaçları</h2>
            <p>Topladığımız bilgiler aşağıdaki amaçlarla kullanılmaktadır:</p>
            <ul>
                <li>Sigorta teklifi hazırlamak ve poliçe düzenlemek</li>
                <li>Müşteri hesaplarını oluşturmak ve yönetmek</li>
                <li>Müşteri destek hizmeti sunmak</li>
                <li>İşlem güvenliğini sağlamak ve dolandırıcılığı önlemek</li>
                <li>Yasal yükümlülükleri yerine getirmek</li>
                <li>Site performansını analiz etmek ve iyileştirmek</li>
                <li>Kişiselleştirilmiş deneyim sunmak</li>
                <li>İzin vermeniz halinde pazarlama iletişimleri göndermek</li>
            </ul>

            <h2>4. Bilgilerin Paylaşılması</h2>
            <p>Kişisel bilgileriniz aşağıdaki durumlarda üçüncü taraflarla paylaşılabilir:</p>
            <ul>
                <li><strong>Sigorta Şirketleri:</strong> Teklif ve poliçe işlemleri için anlaşmalı sigorta şirketlerine</li>
                <li><strong>Yasal Gereklilikler:</strong> Mahkeme kararları, yasal düzenlemeler veya kamu otoritelerinin talebi üzerine</li>
                <li><strong>Hizmet Sağlayıcılar:</strong> Ödeme işlemleri, sunucu hizmetleri, analitik araçlar gibi teknik hizmet sağlayıcılarına</li>
                <li><strong>SBM:</strong> Sigorta Bilgi ve Gözetim Merkezi'ne yasal bildirimler</li>
            </ul>
            <p>Kişisel verilerinizi hiçbir koşulda üçüncü taraflara satmayız.</p>

            <h2>5. Veri Güvenliği</h2>
            <p>Kişisel verilerinizin güvenliğini sağlamak için aşağıdaki önlemleri alıyoruz:</p>
            <ul>
                <li>256-bit SSL/TLS şifreleme ile veri aktarımı</li>
                <li>PCI DSS uyumlu ödeme altyapısı</li>
                <li>Güvenlik duvarı ve izinsiz giriş tespit sistemleri</li>
                <li>Düzenli güvenlik denetimleri ve sızma testleri</li>
                <li>Erişim kontrolleri ve yetkilendirme mekanizmaları</li>
                <li>Veri şifreleme (at rest ve in transit)</li>
                <li>Çalışan gizlilik sözleşmeleri ve eğitimleri</li>
            </ul>

            <h2>6. Çerezler</h2>
            <p>Sitemizde çerezler kullanılmaktadır. Çerezler hakkında detaylı bilgi için <a href="cerez-politikasi.php" style="color: var(--primary);">Çerez Politikası</a> sayfamızı ziyaret edebilirsiniz.</p>

            <h2>7. Üçüncü Taraf Bağlantıları</h2>
            <p>Sitemiz, üçüncü taraf web sitelerine bağlantılar içerebilir. Bu sitelerin gizlilik uygulamalarından sorumlu değiliz. Üçüncü taraf sitelerine geçmeden önce o sitelerin gizlilik politikalarını incelemenizi öneriyoruz.</p>

            <h2>8. Çocukların Gizliliği</h2>
            <p>Sitemiz 18 yaşın altındaki bireylere yönelik değildir. 18 yaşın altındaki kişilerden bilerek kişisel veri toplamıyoruz. Eğer 18 yaşından küçük bir kullanıcının veri paylaştığını fark edersek, bu verileri derhal silmek için gerekli adımları atacağız.</p>

            <h2>9. Haklarınız</h2>
            <p>KVKK kapsamındaki haklarınız hakkında detaylı bilgi için <a href="kvkk.php" style="color: var(--primary);">KVKK Aydınlatma Metni</a> sayfamızı inceleyebilirsiniz.</p>

            <h2>10. Politika Değişiklikleri</h2>
            <p>Bu Gizlilik Politikası'nı zaman zaman güncelleyebiliriz. Değişiklikler, bu sayfada yayınlandığı tarihten itibaren geçerli olacaktır. Önemli değişikliklerde e-posta ile bilgilendirme yapılacaktır.</p>

            <h2>11. İletişim</h2>
            <p>Gizlilik politikamız hakkında sorularınız için:</p>
            <ul>
                <li><strong>E-posta:</strong> kvkk@emresigorta.net</li>
                <li><strong>Telefon:</strong> <?php echo SITE_PHONE; ?></li>
                <li><strong>Adres:</strong> <?php echo SITE_ADDRESS; ?></li>
            </ul>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
