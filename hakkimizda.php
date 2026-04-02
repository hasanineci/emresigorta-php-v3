<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Hakkımızda | Şanlıurfa Sigorta Acentesi';
$pageDescription = 'Emre Sigorta Aracılık Hizmetleri - 2022 yılından bu yana Şanlıurfa\'da güvenilir sigorta aracılık hizmetleri sunuyoruz. 5.000+ mutlu müşteri, 20+ sigorta şirketi iş birliği.';
$pageKeywords = 'emre sigorta hakkımızda, emre sigorta aracılık hizmetleri, şanlıurfa sigorta acentesi, güvenilir sigorta, sigorta danışmanı, sigorta acentesi şanlıurfa, haliliye sigorta';
$ogType = 'article';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Hakkımızda']
]) . '</script>';
$pageSchema .= '
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "AboutPage",
    "name": "Emre Sigorta Hakkımızda",
    "description": "Emre Sigorta Aracılık Hizmetleri hakkında bilgi.",
    "url": "https://' . SITE_DOMAIN . '/hakkimizda.php",
    "mainEntity": {
        "@type": "InsuranceAgency",
        "name": "Emre Sigorta Aracılık Hizmetleri",
        "foundingDate": "2022",
        "numberOfEmployees": {"@type": "QuantitativeValue", "value": "10+"},
        "areaServed": "' . SITE_ADDRESS . '"
    }
}
</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>Hakkımızda</h1>
        <p>Şanlıurfa'nın güvenilir sigorta acentesi Emre Sigorta Aracılık Hizmetleri'ni yakından tanıyın.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>Hakkımızda</span>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper">
            <h2>Biz Kimiz?</h2>
            <p>Emre Sigorta, 2022 yılında Şanlıurfa'da kurulmuş güvenilir bir sigorta aracılık şirketidir. Müşterilerimizin sigorta ürünlerine en kolay, en hızlı ve en uygun fiyatla ulaşmasını sağlamak amacıyla faaliyet göstermekteyiz. T.C. Hazine ve Maliye Bakanlığı tarafından yetkilendirilmiş bir sigorta acentesi olarak hizmet vermekteyiz.</p>
            
            <p>Kurulduğumuz günden bu yana binlerce müşteriye hizmet verdik ve onların en doğru sigorta ürünlerini en uygun fiyatlarla bulmasına yardımcı olduk. 20'den fazla sigorta şirketiyle kurduğumuz güçlü iş birlikleri sayesinde müşterilerimize geniş bir ürün yelpazesi ve rekabetçi fiyatlar sunabiliyoruz.</p>

            <h2 id="misyon">Misyonumuz</h2>
            <p>Sigorta satın alma sürecini herkes için kolay, anlaşılır ve erişilebilir hale getirmek. Teknolojinin gücünü kullanarak, müşterilerimizin en uygun sigorta ürünlerine dakikalar içinde ulaşmasını sağlamak ve sigorta bilincini artırmaya katkıda bulunmak.</p>
            
            <p>Her bireyin ve her ailenin güvenli bir gelecek için doğru sigorta korumasına sahip olması gerektiğine inanıyoruz. Bu nedenle, karmaşık sigorta süreçlerini basitleştiriyor, şeffaf bilgi sunuyor ve her bütçeye uygun çözümler üretiyoruz.</p>

            <h2 id="vizyon">Vizyonumuz</h2>
            <p>Türkiye'nin ve bölgenin lider dijital sigorta platformu olmak. Yapay zeka ve ileri teknolojiler ile kişiselleştirilmiş sigorta deneyimleri sunarak sektörde öncü role sahip olmak. Her vatandaşın sigorta bilincine sahip olduğu ve doğru sigorta korumasıyla güvende olduğu bir Türkiye hedefliyoruz.</p>

            <h2>Değerlerimiz</h2>
            <ul>
                <li><strong>Güvenilirlik:</strong> KVKK uyumlu altyapımız ve SSL şifrelemesiyle müşteri verilerimizi en üst düzeyde koruyoruz.</li>
                <li><strong>Şeffaflık:</strong> Fiyatlarımızda, teminatlarımızda ve işlemlerimizde tam şeffaflık prensibini benimsiyoruz.</li>
                <li><strong>Müşteri Odaklılık:</strong> Her kararımızda müşterilerimizin çıkarlarını ön planda tutuyoruz.</li>
                <li><strong>İnovasyon:</strong> Sürekli kendimizi yenileyerek en son teknolojileri hizmetimize entegre ediyoruz.</li>
                <li><strong>Sosyal Sorumluluk:</strong> Topluma karşı sorumluluğumuzun bilincinde, sigorta bilincini artırmak için çalışıyoruz.</li>
            </ul>

            <h2>Rakamlarla Emre Sigorta</h2>
            <div class="stats" style="border-radius: var(--radius); margin: 30px 0;">
                <div class="container">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <h3>5.000+</h3>
                            <p>Mutlu Müşteri</p>
                        </div>
                        <div class="stat-item">
                            <h3>20+</h3>
                            <p>Sigorta Şirketi</p>
                        </div>
                        <div class="stat-item">
                            <h3>2022</h3>
                            <p>Kuruluş Yılı</p>
                        </div>
                        <div class="stat-item">
                            <h3>%98</h3>
                            <p>Müşteri Memnuniyeti</p>
                        </div>
                    </div>
                </div>
            </div>

            <h2>Anlaşmalı Sigorta Şirketlerimiz</h2>
            <p>Emre Sigorta olarak Türkiye'nin en büyük ve en güvenilir sigorta şirketleriyle iş birliği yapıyoruz. Allianz, Axa, Anadolu Sigorta, Mapfre, HDI, Zurich, Aksigorta, Sompo Sigorta, Quick Sigorta, Türk Nippon ve daha birçok sigorta şirketinin ürünlerini platformumuzda bulabilirsiniz.</p>
            
            <p>Bu geniş iş ortağı ağımız sayesinde müşterilerimize en rekabetçi fiyatları ve en kapsamlı teminatları sunabiliyoruz. Her sigorta ürünü için birden fazla şirketin teklifini karşılaştırarak en uygun seçeneği bulmanızı kolaylaştırıyoruz.</p>

            <h2>Neden Emre Sigorta?</h2>
            <ul>
                <li><strong>Hız:</strong> Dakikalar içinde teklif alın, karşılaştırın ve poliçenizi oluşturun.</li>
                <li><strong>Kolaylık:</strong> 7/24 online işlem yapabilme imkanı.</li>
                <li><strong>Tasarruf:</strong> 30+ sigorta şirketinden en uygun fiyatı bulun.</li>
                <li><strong>Güvenlik:</strong> 256-bit SSL ve KVKK uyumlu güvenli altyapı.</li>
                <li><strong>Destek:</strong> Uzman müşteri temsilcileri ile 7/24 destek.</li>
            </ul>

            <h2>İletişim</h2>
            <p>Bizimle iletişime geçmek için aşağıdaki kanalları kullanabilirsiniz:</p>
            <ul>
                <li><strong>Telefon:</strong> <?php echo SITE_PHONE; ?></li>
                <li><strong>E-posta:</strong> <?php echo SITE_EMAIL; ?></li>
                <li><strong>Adres:</strong> <?php echo SITE_ADDRESS; ?></li>
            </ul>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
