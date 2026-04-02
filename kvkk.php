<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'KVKK Aydınlatma Metni | 6698 Sayılı Kanun';
$pageDescription = 'Emre Sigorta KVKK aydınlatma metni. 6698 sayılı Kişisel Verilerin Korunması Kanunu kapsamında kişisel verilerinizin işlenmesine ilişkin detaylı bilgilendirme.';
$pageKeywords = 'kvkk aydınlatma metni, kişisel verilerin korunması, kvkk, 6698 sayılı kanun, veri sorumlusu, emre sigorta kvkk';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'KVKK Aydınlatma Metni']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>KVKK Aydınlatma Metni</h1>
        <p>6698 sayılı Kişisel Verilerin Korunması Kanunu kapsamında aydınlatma metni.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>KVKK Aydınlatma Metni</span>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper" style="max-width: 900px; margin: 0 auto;">
            <p><strong>Son Güncelleme:</strong> 01 Ocak 2025</p>

            <h2>1. Giriş</h2>
            <p><?php echo SITE_NAME; ?> Sigorta Aracılık Hizmetleri A.Ş. ("Şirket") olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca, veri sorumlusu sıfatıyla kişisel verilerinizin hukuka uygun olarak işlenmesi ve korunması amacıyla azami hassasiyeti göstermekteyiz. Bu aydınlatma metni ile kişisel verilerinizin işlenme süreçleri hakkında sizi bilgilendirmeyi amaçlıyoruz.</p>

            <h2>2. Veri Sorumlusu</h2>
            <p><strong>Şirket Unvanı:</strong> <?php echo SITE_NAME; ?> Sigorta Aracılık Hizmetleri A.Ş.</p>
            <p><strong>Adres:</strong> <?php echo SITE_ADDRESS; ?></p>
            <p><strong>Telefon:</strong> <?php echo SITE_PHONE; ?></p>
            <p><strong>E-posta:</strong> kvkk@emresigorta.net</p>

            <h2>3. İşlenen Kişisel Veriler</h2>
            <p>Şirketimiz tarafından aşağıdaki kategorilerde kişisel veriler işlenmektedir:</p>

            <table class="coverage-table">
                <thead>
                    <tr>
                        <th>Veri Kategorisi</th>
                        <th>Kişisel Veri Türleri</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Kimlik Verileri</strong></td>
                        <td>Ad, soyad, TC kimlik no, doğum tarihi, cinsiyet, medeni durum</td>
                    </tr>
                    <tr>
                        <td><strong>İletişim Verileri</strong></td>
                        <td>Telefon no, e-posta, adres, KEP adresi</td>
                    </tr>
                    <tr>
                        <td><strong>Finansal Veriler</strong></td>
                        <td>Banka hesap bilgileri, kredi kartı bilgileri, fatura bilgileri</td>
                    </tr>
                    <tr>
                        <td><strong>Müşteri İşlem Verileri</strong></td>
                        <td>Poliçe bilgileri, teklif talepleri, hasar kayıtları, ödeme geçmişi</td>
                    </tr>
                    <tr>
                        <td><strong>İşlem Güvenliği Verileri</strong></td>
                        <td>IP adresi, çerez bilgileri, log kayıtları, oturum bilgileri</td>
                    </tr>
                    <tr>
                        <td><strong>Hukuki İşlem Verileri</strong></td>
                        <td>Dava ve icra dosyaları, mahkeme kararları</td>
                    </tr>
                    <tr>
                        <td><strong>Özel Nitelikli Veriler</strong></td>
                        <td>Sağlık verileri (yalnızca sağlık sigortası işlemlerinde)</td>
                    </tr>
                </tbody>
            </table>

            <h2>4. Kişisel Verilerin İşlenme Amaçları</h2>
            <ul>
                <li>Sigorta aracılık hizmetlerinin sunulması</li>
                <li>Sigorta teklifi hazırlanması ve karşılaştırılması</li>
                <li>Poliçe düzenlenmesi, yenilenmesi ve yönetimi</li>
                <li>Hasar ihbarı ve takibi</li>
                <li>Yasal yükümlülüklerin yerine getirilmesi (SBM bildirimleri, vergisel yükümlülükler vb.)</li>
                <li>Müşteri ilişkileri yönetimi ve iletişim</li>
                <li>Hizmet kalitesinin artırılması</li>
                <li>Şikayet ve talep yönetimi</li>
                <li>Bilgi güvenliği süreçlerinin yürütülmesi</li>
                <li>Yetkili kurum ve kuruluşlara bilgi verilmesi</li>
                <li>Hukuki süreçlerin takibi</li>
            </ul>

            <h2>5. Kişisel Verilerin İşlenme Hukuki Sebepleri</h2>
            <p>Kişisel verileriniz, KVKK'nın 5. ve 6. maddelerinde belirtilen aşağıdaki hukuki sebeplere dayanılarak işlenmektedir:</p>
            <ul>
                <li>Açık rızanız</li>
                <li>Kanunlarda açıkça öngörülmesi</li>
                <li>Sözleşmenin kurulması veya ifası için gerekli olması</li>
                <li>Hukuki yükümlülüğün yerine getirilmesi</li>
                <li>Meşru menfaat</li>
                <li>Bir hakkın tesisi, kullanılması veya korunması</li>
            </ul>

            <h2>6. Kişisel Verilerin Aktarılması</h2>
            <p>Kişisel verileriniz, işlenme amaçları doğrultusunda ve KVKK'nın 8. ve 9. maddelerine uygun olarak aşağıdaki taraflara aktarılabilecektir:</p>
            <ul>
                <li>Anlaşmalı sigorta şirketleri ve reasürans şirketleri</li>
                <li>Sigorta Bilgi ve Gözetim Merkezi (SBM)</li>
                <li>T.C. Hazine ve Maliye Bakanlığı</li>
                <li>Sigorta Tahkim Komisyonu</li>
                <li>Bankalar ve ödeme kuruluşları</li>
                <li>Yurt içi/yurt dışı hizmet sağlayıcılar (sunucu, yazılım, destek hizmetleri)</li>
                <li>Yetkili mahkemeler ve kamu kurum ve kuruluşları</li>
                <li>İş ortakları ve tedarikçiler</li>
            </ul>

            <h2>7. Kişisel Verilerin Toplanma Yöntemi</h2>
            <p>Kişisel verileriniz aşağıdaki yöntemlerle toplanmaktadır:</p>
            <ul>
                <li>Web sitemiz üzerinden doldurduğunuz formlar</li>
                <li>Çağrı merkezi görüşmeleri</li>
                <li>E-posta iletişimleri</li>
                <li>Sosyal medya kanalları</li>
                <li>Çerezler ve benzeri teknolojiler</li>
                <li>Sigorta şirketlerinden ve SBM'den aktarılan veriler</li>
            </ul>

            <h2>8. Kişisel Verilerin Saklanma Süresi</h2>
            <p>Kişisel verileriniz, işlenme amaçlarının gerektirdiği süre boyunca ve yasal saklama süreleri dikkate alınarak saklanmaktadır. Saklama süresi sona erdiğinde verileriniz silinir, yok edilir veya anonim hale getirilir.</p>

            <h2>9. Veri Sahibi Olarak Haklarınız</h2>
            <p>KVKK'nın 11. maddesi gereğince aşağıdaki haklara sahipsiniz:</p>
            <ul>
                <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
                <li>Kişisel verileriniz işlenmişse buna ilişkin bilgi talep etme</li>
                <li>Kişisel verilerinizin işlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme</li>
                <li>Yurt içinde veya yurt dışında kişisel verilerin aktarıldığı üçüncü kişileri bilme</li>
                <li>Kişisel verilerin eksik veya yanlış işlenmiş olması halinde bunların düzeltilmesini isteme</li>
                <li>KVKK'nın 7. maddesine göre kişisel verilerinizin silinmesini veya yok edilmesini isteme</li>
                <li>Düzeltme ve silme işlemlerinin kişisel verilerin aktarıldığı üçüncü kişilere bildirilmesini isteme</li>
                <li>İşlenen verilerin münhasıran otomatik sistemler vasıtasıyla analiz edilmesi suretiyle aleyhinize bir sonucun ortaya çıkmasına itiraz etme</li>
                <li>Kanuna aykırı olarak işlenmesi sebebiyle zarara uğramanız halinde zararın giderilmesini talep etme</li>
            </ul>

            <h2>10. Başvuru Yöntemi</h2>
            <p>Haklarınızı kullanmak için aşağıdaki yöntemlerle başvuruda bulunabilirsiniz:</p>
            <ul>
                <li><strong>E-posta:</strong> kvkk@emresigorta.net</li>
                <li><strong>Posta:</strong> <?php echo SITE_ADDRESS; ?></li>
                <li><strong>KEP:</strong> emresigorta@hs01.kep.tr</li>
            </ul>
            <p>Başvurunuz, niteliğine göre en kısa sürede ve en geç 30 gün içinde ücretsiz olarak sonuçlandırılacaktır.</p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
