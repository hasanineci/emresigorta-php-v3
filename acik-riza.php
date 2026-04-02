<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Açık Rıza Metni | KVKK';
$pageDescription = 'Emre Sigorta Aracılık Hizmetleri açık rıza metni. Kişisel verilerin işlenmesine ilişkin 6698 sayılı KVKK kapsamında açık rıza beyanı.';
$pageKeywords = 'açık rıza metni, kvkk açık rıza, kişisel veri rıza, emre sigorta kvkk, veri işleme rızası';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Açık Rıza Metni']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>Açık Rıza Metni</h1>
        <p>Kişisel verilerinizin işlenmesine ilişkin açık rıza beyanı.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>Açık Rıza Metni</span>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper" style="max-width: 900px; margin: 0 auto;">
            <p><strong>Son Güncelleme:</strong> 01 Ocak 2025</p>

            <h2>1. Veri Sorumlusu</h2>
            <p><?php echo SITE_NAME; ?> Sigorta Aracılık Hizmetleri A.Ş. ("Şirket") olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") kapsamında veri sorumlusu sıfatıyla aşağıdaki açıklamalar çerçevesinde açık rızanızı talep etmekteyiz.</p>

            <h2>2. İşlenen Kişisel Veriler</h2>
            <p>Aşağıdaki kişisel verileriniz, açık rızanız dahilinde işlenecektir:</p>
            <ul>
                <li><strong>Kimlik Bilgileri:</strong> Ad, soyad, TC kimlik numarası, doğum tarihi, cinsiyet</li>
                <li><strong>İletişim Bilgileri:</strong> Telefon numarası, e-posta adresi, adres bilgileri</li>
                <li><strong>Finansal Bilgiler:</strong> Ödeme bilgileri, banka hesap bilgileri, fatura bilgileri</li>
                <li><strong>Araç Bilgileri:</strong> Plaka, ruhsat bilgileri, araç özellikleri</li>
                <li><strong>Sağlık Bilgileri:</strong> Sağlık sigortası kapsamında talep edilen sağlık verileri</li>
                <li><strong>Konut Bilgileri:</strong> Tapu bilgileri, adres, bina özellikleri</li>
                <li><strong>Dijital İz Bilgileri:</strong> IP adresi, çerez verileri, oturum bilgileri</li>
            </ul>

            <h2>3. Kişisel Verilerin İşlenme Amaçları</h2>
            <p>Kişisel verileriniz aşağıdaki amaçlarla işlenecektir:</p>
            <ul>
                <li>Sigorta ürünleri için teklif hazırlanması ve poliçe düzenlenmesi</li>
                <li>Sigorta şirketlerine teklif taleplerinin iletilmesi</li>
                <li>Müşteriye özel kampanya ve fırsatların sunulması</li>
                <li>Pazarlama ve iletişim faaliyetlerinin yürütülmesi (SMS, e-posta, arama)</li>
                <li>Müşteri memnuniyeti araştırmaları ve anketlerin yapılması</li>
                <li>İstatistiksel analizler ve raporlama</li>
                <li>Profilleme ve kişiselleştirilmiş hizmet sunumu</li>
                <li>Yasal yükümlülüklerin yerine getirilmesi</li>
            </ul>

            <h2>4. Kişisel Verilerin Aktarılması</h2>
            <p>Kişisel verileriniz, yukarıda belirtilen amaçlar doğrultusunda aşağıdaki taraflara aktarılabilecektir:</p>
            <ul>
                <li>Anlaşmalı sigorta şirketleri</li>
                <li>Sigorta Bilgi ve Gözetim Merkezi (SBM)</li>
                <li>Hazine ve Maliye Bakanlığı</li>
                <li>Hizmet aldığımız iş ortakları ve tedarikçiler</li>
                <li>Yasal zorunluluk halinde yetkili kamu kurum ve kuruluşları</li>
            </ul>

            <h2>5. Açık Rıza Beyanı</h2>
            <p>6698 sayılı Kişisel Verilerin Korunması Kanunu kapsamında, yukarıda belirtilen kişisel verilerimin, yukarıda açıklanan amaçlarla işlenmesine ve aktarılmasına özgürce, bilgilendirilmiş olarak açık rızam bulunmaktadır.</p>

            <p>Açık rızamın geri alınmasının, geri alma beyanımın Şirket'e ulaştığı tarihten itibaren geçerli olacağını ve geriye dönük sonuç doğurmayacağını biliyorum.</p>

            <h2>6. Haklarınız</h2>
            <p>KVKK'nın 11. maddesi gereğince aşağıdaki haklara sahipsiniz:</p>
            <ul>
                <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
                <li>İşlenmişse buna ilişkin bilgi talep etme</li>
                <li>İşlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme</li>
                <li>Yurt içinde veya yurt dışında aktarıldığı üçüncü kişileri bilme</li>
                <li>Eksik veya yanlış işlenmiş olması halinde düzeltilmesini isteme</li>
                <li>KVKK'nın 7. maddesinde öngörülen şartlar çerçevesinde silinmesini/yok edilmesini isteme</li>
                <li>İşlenen verilerin münhasıran otomatik sistemler vasıtasıyla analiz edilmesi suretiyle kişinin kendisi aleyhine bir sonucun ortaya çıkmasına itiraz etme</li>
                <li>Kanuna aykırı olarak işlenmesi sebebiyle zarara uğranması halinde zararın giderilmesini talep etme</li>
            </ul>

            <h2>7. İletişim</h2>
            <p>Haklarınızı kullanmak veya sorularınız için aşağıdaki kanallardan bize ulaşabilirsiniz:</p>
            <ul>
                <li><strong>E-posta:</strong> kvkk@emresigorta.net</li>
                <li><strong>Adres:</strong> <?php echo SITE_ADDRESS; ?></li>
                <li><strong>Telefon:</strong> <?php echo SITE_PHONE; ?></li>
            </ul>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
