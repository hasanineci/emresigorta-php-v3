<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Kullanım Şartları | Web Sitesi Kullanım Koşulları';
$pageDescription = 'Emre Sigorta web sitesi kullanım şartları ve koşulları. Siteyi kullanmadan önce lütfen bu koşulları dikkatlice okuyunuz.';
$pageKeywords = 'kullanım şartları, kullanım koşulları, web sitesi kuralları, emre sigorta kullanım sözleşmesi';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Kullanım Şartları']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>Kullanım Şartları</h1>
        <p>Web sitemizi kullanırken geçerli olan koşullar ve kurallar.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>Kullanım Şartları</span>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper" style="max-width: 900px; margin: 0 auto;">
            <p><strong>Son Güncelleme:</strong> 01 Ocak 2025</p>

            <h2>1. Genel Hükümler</h2>
            <p>Bu Kullanım Şartları, <?php echo SITE_NAME; ?> Sigorta Aracılık Hizmetleri ("Şirket") tarafından işletilen <?php echo SITE_DOMAIN; ?> web sitesinin ("Site") kullanım koşullarını belirlemektedir. Siteyi kullanarak bu şartları kabul etmiş sayılırsınız.</p>

            <h2>2. Hizmet Tanımı</h2>
            <p><?php echo SITE_NAME; ?>, T.C. Hazine ve Maliye Bakanlığı Sigortacılık Genel Müdürlüğü tarafından yetkilendirilmiş bir sigorta aracılık kuruluşudur. Sitemiz aracılığıyla sigorta ürünleri hakkında bilgi edinebilir, teklif talep edebilir ve poliçe işlemlerinizi gerçekleştirebilirsiniz.</p>

            <h2>3. Kullanıcı Yükümlülükleri</h2>
            <p>Siteyi kullanırken:</p>
            <ul>
                <li>Doğru, eksiksiz ve güncel bilgi vermekle yükümlüsünüz.</li>
                <li>Siteyi yalnızca yasal amaçlarla kullanmalısınız.</li>
                <li>Başkalarının kişisel bilgilerini izinsiz paylaşmamalısınız.</li>
                <li>Sitenin güvenliğini tehlikeye atacak davranışlarda bulunmamalısınız.</li>
                <li>Sitenin normal çalışmasını engelleyecek veya bozacak girişimlerde bulunmamalısınız.</li>
            </ul>

            <h2>4. Fikri Mülkiyet Hakları</h2>
            <p>Sitedeki tüm içerik, tasarım, logo, grafik, metin, yazılım ve diğer materyaller <?php echo SITE_NAME; ?>'nin veya ilgili lisans sahiplerinin mülkiyetindedir. Bu içeriklerin izinsiz kopyalanması, çoğaltılması, dağıtılması veya kullanılması yasaktır.</p>

            <h2>5. Sigorta Bilgilendirmesi</h2>
            <p>Sitede yer alan sigorta ürün bilgileri ve fiyatlar bilgilendirme amaçlıdır. Kesin teminat kapsamı ve prim tutarları poliçe düzenleme aşamasında belirlenir. Fiyatlar ve koşullar sigorta şirketlerinin güncel tarifelerine göre değişiklik gösterebilir.</p>

            <h2>6. Sorumluluk Sınırlaması</h2>
            <ul>
                <li>Sitedeki bilgilerin doğruluğu konusunda azami özen gösterilmekle birlikte, bilgilerin eksiksiz ve hatasız olduğu garanti edilmez.</li>
                <li>Site üzerinden verilen linkler aracılığıyla ulaşılan üçüncü taraf sitelerin içeriğinden <?php echo SITE_NAME; ?> sorumlu değildir.</li>
                <li>Teknik aksaklıklar, bakım çalışmaları veya mücbir sebeplerden kaynaklanan hizmet kesintilerinden sorumluluk kabul edilmez.</li>
            </ul>

            <h2>7. Kişisel Verilerin Korunması</h2>
            <p>Kişisel verilerinizin işlenmesi hakkında detaylı bilgi için <a href="<?php echo SITE_URL; ?>/kvkk.php">KVKK Aydınlatma Metni</a> ve <a href="<?php echo SITE_URL; ?>/gizlilik.php">Gizlilik Politikası</a> sayfalarımızı inceleyebilirsiniz.</p>

            <h2>8. Çerezler</h2>
            <p>Sitemiz, kullanıcı deneyimini iyileştirmek amacıyla çerezler kullanmaktadır. Çerez kullanımımız hakkında detaylı bilgi için <a href="<?php echo SITE_URL; ?>/cerez-politikasi.php">Çerez Politikası</a> sayfamızı ziyaret edebilirsiniz.</p>

            <h2>9. Değişiklikler</h2>
            <p><?php echo SITE_NAME; ?>, bu Kullanım Şartları'nı önceden haber vermeksizin güncelleme hakkını saklı tutar. Güncellenmiş şartlar, sitede yayınlandığı tarihten itibaren geçerlidir. Siteyi kullanmaya devam etmeniz, güncellenmiş şartları kabul ettiğiniz anlamına gelir.</p>

            <h2>10. Uygulanacak Hukuk ve Yetki</h2>
            <p>Bu Kullanım Şartları Türkiye Cumhuriyeti kanunlarına tabidir. Uyuşmazlıklarda Şanlıurfa Mahkemeleri ve İcra Daireleri yetkilidir.</p>

            <h2>11. İletişim</h2>
            <p>Kullanım şartlarımız hakkında sorularınız için:</p>
            <ul>
                <li><strong>E-posta:</strong> <?php echo SITE_EMAIL; ?></li>
                <li><strong>Telefon:</strong> <?php echo SITE_PHONE; ?></li>
                <li><strong>Adres:</strong> <?php echo SITE_ADDRESS; ?></li>
            </ul>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
