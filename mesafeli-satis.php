<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Mesafeli Satış Sözleşmesi | Online Sigorta';
$pageDescription = 'Emre Sigorta mesafeli satış sözleşmesi. Online sigorta satın alma işlemlerinize ilişkin yasal haklarınız ve sözleşme koşulları hakkında bilgilendirme.';
$pageKeywords = 'mesafeli satış sözleşmesi, online sigorta satış, yasal bilgilendirme, tüketici hakları, emre sigorta sözleşme';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Mesafeli Satış Sözleşmesi']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>Mesafeli Satış Sözleşmesi</h1>
        <p>Online sigorta satın alma işlemlerinize ilişkin mesafeli satış sözleşmesi.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>Mesafeli Satış Sözleşmesi</span>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper" style="max-width: 900px; margin: 0 auto;">
            <p><strong>Son Güncelleme:</strong> 01 Ocak 2025</p>

            <h2>Madde 1 - Taraflar</h2>
            <h3>1.1. Satıcı (Hizmet Sağlayıcı)</h3>
            <ul>
                <li><strong>Unvan:</strong> <?php echo SITE_NAME; ?> Sigorta Aracılık Hizmetleri A.Ş.</li>
                <li><strong>Adres:</strong> <?php echo SITE_ADDRESS; ?></li>
                <li><strong>Telefon:</strong> <?php echo SITE_PHONE; ?></li>
                <li><strong>E-posta:</strong> <?php echo SITE_EMAIL; ?></li>
                <li><strong>Mersis No:</strong> 0123456789012345</li>
            </ul>

            <h3>1.2. Alıcı (Tüketici)</h3>
            <p>Web sitesi üzerinden sigorta ürünü satın alan gerçek veya tüzel kişidir. Alıcının kimlik ve iletişim bilgileri, satın alma işlemi sırasında kaydedilmektedir.</p>

            <h2>Madde 2 - Sözleşmenin Konusu</h2>
            <p>İşbu Mesafeli Satış Sözleşmesi ("Sözleşme"), 6502 sayılı Tüketicinin Korunması Hakkında Kanun ve Mesafeli Sözleşmeler Yönetmeliği hükümleri gereğince tarafların hak ve yükümlülüklerini düzenlemektedir.</p>
            <p>Sözleşmenin konusu, Alıcı'nın <?php echo SITE_DOMAIN; ?> web sitesi üzerinden elektronik ortamda satın aldığı sigorta ürünlerinin satışı ve hizmet sunumuna ilişkin tarafların karşılıklı hak ve yükümlülüklerinin belirlenmesidir.</p>

            <h2>Madde 3 - Sözleşme Konusu Hizmet</h2>
            <p>Satıcı, sigorta aracılık hizmeti kapsamında aşağıdaki ürünlerin online satışını gerçekleştirmektedir:</p>
            <ul>
                <li>Zorunlu Trafik Sigortası</li>
                <li>Kasko Sigortası</li>
                <li>Tamamlayıcı Sağlık Sigortası</li>
                <li>Özel Sağlık Sigortası</li>
                <li>DASK (Zorunlu Deprem Sigortası)</li>
                <li>Konut Sigortası</li>
                <li>Seyahat Sağlık Sigortası</li>
                <li>İhtiyari Mali Mesuliyet Sigortası (İMM)</li>
                <li>Ferdi Kaza Sigortası</li>
                <li>Diğer sigorta ürünleri</li>
            </ul>
            <p>Ürünün tüm vergiler dahil satış fiyatı, teminat kapsamı ve ödeme bilgileri, satın alma öncesinde Alıcı'ya açıkça gösterilmektedir.</p>

            <h2>Madde 4 - Ödeme ve Teslimat</h2>
            <h3>4.1. Ödeme</h3>
            <ul>
                <li>Ödeme, kredi kartı, banka kartı veya havale/EFT ile yapılabilir.</li>
                <li>Taksitli ödeme seçeneği, banka ve sigorta şirketi koşullarına bağlıdır.</li>
                <li>Tüm ödemeler 256-bit SSL şifreleme ile güvence altındadır.</li>
                <li>Ödeme bilgileri PCI DSS standartlarına uygun olarak işlenmektedir.</li>
            </ul>

            <h3>4.2. Teslimat</h3>
            <ul>
                <li>Sigorta poliçesi, ödemenin onaylanmasının ardından elektronik ortamda Alıcı'nın e-posta adresine gönderilir.</li>
                <li>Poliçe, Alıcı'nın hesabı üzerinden de görüntülenebilir ve indirilebilir.</li>
                <li>Poliçe teslim süresi, ödeme onayından itibaren en geç 24 saat içindedir.</li>
                <li>Zorunlu sigortalar (trafik, DASK) anlık olarak düzenlenmektedir.</li>
            </ul>

            <h2>Madde 5 - Cayma Hakkı</h2>
            <h3>5.1. Hayat Dışı Sigortalar</h3>
            <p>Mesafeli Sözleşmeler Yönetmeliği gereğince, Alıcı'nın poliçeyi aldığı tarihten itibaren 14 (on dört) gün içinde herhangi bir gerekçe göstermeksizin ve cezai şart ödemeksizin cayma hakkı bulunmaktadır.</p>

            <h3>5.2. Hayat Sigortaları</h3>
            <p>6102 sayılı Türk Ticaret Kanunu gereğince, hayat sigortalarında cayma hakkı süresi poliçe teslim tarihinden itibaren 30 (otuz) gündür.</p>

            <h3>5.3. Cayma Hakkının Kullanılamayacağı Durumlar</h3>
            <ul>
                <li>Zorunlu sigortalar (cayma yerine iptal koşulları geçerlidir)</li>
                <li>Cayma süresi içinde hasar tazminatı ödenmiş poliçeler</li>
                <li>Süresi dolmuş poliçeler</li>
            </ul>

            <h3>5.4. Cayma Hakkının Kullanımı</h3>
            <p>Cayma hakkını kullanmak için aşağıdaki kanallardan birine başvurabilirsiniz:</p>
            <ul>
                <li>E-posta: <?php echo SITE_EMAIL; ?></li>
                <li>Telefon: <?php echo SITE_PHONE; ?></li>
                <li>Web: <a href="police-iptal.php" style="color: var(--primary);">Poliçe İptal Formu</a></li>
            </ul>

            <h2>Madde 6 - İade Koşulları</h2>
            <ul>
                <li>Cayma hakkı süresi içinde yapılan iptallerde prim tutarının tamamı iade edilir.</li>
                <li>İade, ödeme yaptığınız yönteme göre 10 iş günü içinde gerçekleştirilir.</li>
                <li>Cayma hakkı süresi dışındaki iptallerde kısa dönem tarifesi uygulanır.</li>
                <li>Hasar ödemesi yapılmış poliçelerde iade tutarı sigorta şirketi tarafından hesaplanır.</li>
            </ul>

            <h2>Madde 7 - Tarafların Yükümlülükleri</h2>
            <h3>7.1. Satıcı'nın Yükümlülükleri</h3>
            <ul>
                <li>Sigorta ürünleri hakkında doğru, eksiksiz ve güncel bilgi sunmak</li>
                <li>Poliçeyi onaylanan koşullarda ve sürede düzenlemek</li>
                <li>Kişisel verilerin gizliliğini ve güvenliğini sağlamak</li>
                <li>Cayma ve iptal taleplerini yasal süreler içinde işleme almak</li>
                <li>Müşteri şikayetlerini ele almak ve çözmek</li>
            </ul>

            <h3>7.2. Alıcı'nın Yükümlülükleri</h3>
            <ul>
                <li>Doğru ve eksiksiz beyan vermek (yanlış beyan poliçeyi geçersiz kılabilir)</li>
                <li>Poliçe primini zamanında ödemek</li>
                <li>Poliçe koşullarını ve teminat kapsamını incelemek</li>
                <li>Hasar durumunda derhal bildirim yapmak</li>
            </ul>

            <h2>Madde 8 - Uyuşmazlık Çözümü</h2>
            <p>Bu sözleşmeden doğan uyuşmazlıklarda:</p>
            <ul>
                <li>Tüketici Hakem Heyetleri (yasal limit altındaki uyuşmazlıklarda)</li>
                <li>Tüketici Mahkemeleri</li>
                <li>Sigorta Tahkim Komisyonu</li>
            </ul>
            <p>yetkilidir. Alıcı, uyuşmazlık tutarına göre ilgili merciye başvurabilir.</p>

            <h2>Madde 9 - Yürürlük</h2>
            <p>İşbu Sözleşme, Alıcı tarafından elektronik ortamda onaylandığı tarihte yürürlüğe girer ve poliçe süresinin sona ermesi veya taraflardan birinin fesih hakkını kullanması ile sona erer.</p>

            <p>Alıcı, satın alma işlemi esnasında bu sözleşmenin tüm koşullarını okuduğunu, anladığını ve kabul ettiğini beyan eder.</p>

            <h2>Madde 10 - İletişim</h2>
            <p>Sözleşme ile ilgili sorularınız için:</p>
            <ul>
                <li><strong>Telefon:</strong> <?php echo SITE_PHONE; ?></li>
                <li><strong>E-posta:</strong> <?php echo SITE_EMAIL; ?></li>
                <li><strong>Adres:</strong> <?php echo SITE_ADDRESS; ?></li>
            </ul>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
