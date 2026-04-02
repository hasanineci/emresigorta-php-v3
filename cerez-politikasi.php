<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Çerez Politikası (Cookie Policy)';
$pageDescription = 'Emre Sigorta çerez politikası. Web sitemizde kullanılan çerezler, türleri, amaçları ve yönetimi hakkında detaylı bilgilendirme.';
$pageKeywords = 'çerez politikası, cookie politikası, çerez kullanımı, web çerezleri, emre sigorta çerez, çerez yönetimi';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Çerez Politikası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>Çerez Politikası</h1>
        <p>Web sitemizde kullanılan çerezler hakkında detaylı bilgilendirme.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>Çerez Politikası</span>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper" style="max-width: 900px; margin: 0 auto;">
            <p><strong>Son Güncelleme:</strong> 01 Ocak 2025</p>

            <h2>1. Çerez Nedir?</h2>
            <p>Çerezler (cookies), web sitemizi ziyaret ettiğinizde tarayıcınız aracılığıyla cihazınıza (bilgisayar, tablet, telefon) yerleştirilen küçük metin dosyalarıdır. Çerezler, sitemizin düzgün çalışması, kullanıcı deneyiminin iyileştirilmesi ve istatistiksel analizler yapılması amacıyla kullanılmaktadır.</p>

            <h2>2. Çerez Türleri</h2>

            <h3>2.1. Zorunlu Çerezler</h3>
            <p>Bu çerezler, web sitemizin temel işlevlerinin çalışması için gereklidir. Bu çerezler olmadan site düzgün çalışamaz ve devre dışı bırakılamazlar.</p>
            <table class="coverage-table">
                <thead>
                    <tr>
                        <th>Çerez Adı</th>
                        <th>Amacı</th>
                        <th>Süresi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>session_id</td>
                        <td>Oturum yönetimi</td>
                        <td>Oturum</td>
                    </tr>
                    <tr>
                        <td>csrf_token</td>
                        <td>Güvenlik (CSRF koruması)</td>
                        <td>Oturum</td>
                    </tr>
                    <tr>
                        <td>cookie_consent</td>
                        <td>Çerez tercihlerinizi saklar</td>
                        <td>1 yıl</td>
                    </tr>
                </tbody>
            </table>

            <h3>2.2. İşlevsellik Çerezleri</h3>
            <p>Bu çerezler, size daha gelişmiş ve kişiselleştirilmiş bir deneyim sunmamıza yardımcı olur.</p>
            <table class="coverage-table">
                <thead>
                    <tr>
                        <th>Çerez Adı</th>
                        <th>Amacı</th>
                        <th>Süresi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>user_preferences</td>
                        <td>Kullanıcı tercihlerini saklar</td>
                        <td>6 ay</td>
                    </tr>
                    <tr>
                        <td>recent_searches</td>
                        <td>Son aramaları saklar</td>
                        <td>30 gün</td>
                    </tr>
                    <tr>
                        <td>language</td>
                        <td>Dil tercihinizi saklar</td>
                        <td>1 yıl</td>
                    </tr>
                </tbody>
            </table>

            <h3>2.3. Analitik/Performans Çerezleri</h3>
            <p>Bu çerezler, ziyaretçilerin sitemizi nasıl kullandığını anlamamıza ve sitemizi iyileştirmemize yardımcı olur.</p>
            <table class="coverage-table">
                <thead>
                    <tr>
                        <th>Çerez Adı</th>
                        <th>Sağlayıcı</th>
                        <th>Amacı</th>
                        <th>Süresi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>_ga</td>
                        <td>Google Analytics</td>
                        <td>Kullanıcıları ayırt etmek</td>
                        <td>2 yıl</td>
                    </tr>
                    <tr>
                        <td>_ga_*</td>
                        <td>Google Analytics</td>
                        <td>Oturum durumunu saklar</td>
                        <td>2 yıl</td>
                    </tr>
                    <tr>
                        <td>_gid</td>
                        <td>Google Analytics</td>
                        <td>Kullanıcıları ayırt etmek</td>
                        <td>24 saat</td>
                    </tr>
                </tbody>
            </table>

            <h3>2.4. Pazarlama/Hedefleme Çerezleri</h3>
            <p>Bu çerezler, ilgi alanlarınıza uygun reklamlar göstermek ve pazarlama faaliyetlerinin etkinliğini ölçmek için kullanılır.</p>
            <table class="coverage-table">
                <thead>
                    <tr>
                        <th>Çerez Adı</th>
                        <th>Sağlayıcı</th>
                        <th>Amacı</th>
                        <th>Süresi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>_fbp</td>
                        <td>Facebook</td>
                        <td>Reklam hedefleme</td>
                        <td>3 ay</td>
                    </tr>
                    <tr>
                        <td>_gcl_au</td>
                        <td>Google Ads</td>
                        <td>Reklam dönüşüm takibi</td>
                        <td>3 ay</td>
                    </tr>
                    <tr>
                        <td>ads_prefs</td>
                        <td>Twitter</td>
                        <td>Reklam tercihleri</td>
                        <td>5 yıl</td>
                    </tr>
                </tbody>
            </table>

            <h2>3. Çerezleri Yönetme</h2>
            <p>Tarayıcı ayarlarınızı değiştirerek çerez tercihlerinizi yönetebilirsiniz. Çerezleri devre dışı bırakabilir veya silebilirsiniz. Ancak bazı çerezleri devre dışı bırakmak sitemizin bazı özelliklerinin çalışmamasına neden olabilir.</p>
            
            <h3>Popüler Tarayıcılarda Çerez Ayarları:</h3>
            <ul>
                <li><strong>Google Chrome:</strong> Ayarlar &gt; Gizlilik ve Güvenlik &gt; Çerezler ve diğer site verileri</li>
                <li><strong>Mozilla Firefox:</strong> Ayarlar &gt; Gizlilik ve Güvenlik &gt; Çerezler ve Site Verileri</li>
                <li><strong>Microsoft Edge:</strong> Ayarlar &gt; Çerezler ve site izinleri</li>
                <li><strong>Safari:</strong> Tercihler &gt; Gizlilik &gt; Çerezler</li>
                <li><strong>Opera:</strong> Ayarlar &gt; Gelişmiş &gt; Gizlilik ve Güvenlik &gt; Çerezler</li>
            </ul>

            <h2>4. Google Analytics</h2>
            <p>Web sitemizde Google Analytics kullanmaktayız. Google Analytics, çerezler aracılığıyla toplanan verileri ABD'deki sunucularda saklayabilir. Google'ın gizlilik politikası hakkında bilgi almak için: <a href="https://policies.google.com/privacy" target="_blank" style="color: var(--primary);">https://policies.google.com/privacy</a></p>
            <p>Google Analytics takibini devre dışı bırakmak için: <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" style="color: var(--primary);">Google Analytics Opt-out Browser Add-on</a> kullanabilirsiniz.</p>

            <h2>5. Çerez Onayı</h2>
            <p>Sitemizi ilk ziyaretinizde çerez onay bildirimi gösterilir. Bu bildirim aracılığıyla çerez tercihlerinizi belirleyebilirsiniz. Tercihlerinizi istediğiniz zaman değiştirmek için tarayıcınızın çerez ayarlarını kullanabilirsiniz.</p>

            <h2>6. Politika Güncellemeleri</h2>
            <p>Bu Çerez Politikası'nı zaman zaman güncelleyebiliriz. Güncellemeler bu sayfada yayınlanır ve yayın tarihinden itibaren geçerli olur.</p>

            <h2>7. İletişim</h2>
            <p>Çerez politikamız hakkında sorularınız için:</p>
            <ul>
                <li><strong>E-posta:</strong> <?php echo SITE_EMAIL; ?></li>
                <li><strong>Telefon:</strong> <?php echo SITE_PHONE; ?></li>
            </ul>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
