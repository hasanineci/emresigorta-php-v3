<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Elektrikli Araç Kasko Sigortası | Batarya Teminatlı Kasko';
$pageDescription = 'Elektrikli araç kaskonuzu Emre Sigorta\'dan yaptırın. Batarya hasarı, şarj ünitesi ve elektrik sistemi dahil kapsamlı kasko teminatı. TOGG, Tesla ve tüm elektrikli araçlar için özel teklifler.';
$pageKeywords = 'elektrikli araç kasko, elektrikli araba sigortası, togg kasko, tesla kasko, batarya teminatı sigortası, elektrikli araç sigortası fiyat, emre sigorta';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Elektrikli Araç Kasko Sigortası', 'Elektrikli araçlar için batarya teminatlı özel kasko sigortası.', 'https://' . SITE_DOMAIN . '/elektrikli-arac-kasko.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Elektrikli Araç Kasko']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Elektrikli Araç Kasko</h1>
                <p>Elektrikli aracınız için özel olarak tasarlanmış kasko sigortası. Batarya, şarj ünitesi ve elektrik sistemi dahil kapsamlı koruma.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Batarya hasarı teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Şarj ünitesi koruma</li>
                    <li><i class="fa-solid fa-circle-check"></i> Elektrik arızası teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Yol yardım hizmeti</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Elektrikli Araç Kasko Teklifi</h3>
                <p class="form-subtitle">Elektrikli aracınız için özel teklif alın</p>
                <form data-form-type="elektrikli-arac-kasko" enctype="multipart/form-data">
                    <input type="hidden" name="form_type" value="elektrikli-arac-kasko">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Ruhsat Sahibi Adı Soyadı</label><input type="text" name="ruhsat_sahibi" placeholder="Ruhsat sahibinin adı ve soyadı" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" placeholder="T.C. Kimlik Numaranız" maxlength="11" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Meslek</label><input type="text" name="meslek" placeholder="Mesleğiniz" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Araç Plakası</label><input type="text" name="plaka" placeholder="Örn: 34 ABC 123" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page">
                                <label>Ruhsat Fotoğrafı</label>
                                <input type="file" name="ruhsat_foto" accept=".jpg,.jpeg,.png,.pdf" class="form-control">
                                <small class="text-muted">JPG, PNG veya PDF</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page">
                                <label>Kasko Tipi</label>
                                <select name="kasko_tipi" required>
                                    <option value="">Seçiniz</option>
                                    <option value="tam">Tam Kasko</option>
                                    <option value="mini">Mini Kasko</option>
                                    <option value="genisletilmis">Genişletilmiş Kasko</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page">
                                <label>Engelli Aracı mı?</label>
                                <select name="arac_engeli" required>
                                    <option value="">Seçiniz</option>
                                    <option value="hayir">Hayır</option>
                                    <option value="evet">Evet</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page">
                                <label>İMM Teminat Tutarı</label>
                                <select name="imm_teminat">
                                    <option value="">İMM İstemiyorum</option>
                                    <option value="1_milyon">1 Milyon TL</option>
                                    <option value="2_milyon">2 Milyon TL</option>
                                    <option value="3_milyon">3 Milyon TL</option>
                                    <option value="4_milyon">4 Milyon TL</option>
                                    <option value="5_milyon">5 Milyon TL</option>
                                    <option value="6_milyon">6 Milyon TL</option>
                                    <option value="7_milyon">7 Milyon TL</option>
                                    <option value="8_milyon">8 Milyon TL</option>
                                    <option value="9_milyon">9 Milyon TL</option>
                                    <option value="10_milyon">10 Milyon TL</option>
                                    <option value="sinirsiz">Sınırsız İMM</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page">
                                <label>İkame Araç</label>
                                <select name="ikame_arac">
                                    <option value="">İkame Araç İstemiyorum</option>
                                    <option value="2x7">2x7 Gün</option>
                                    <option value="2x10">2x10 Gün</option>
                                    <option value="2x14">2x14 Gün</option>
                                </select>
                                <small class="text-muted">2x7: 2 kez 7 gün &bull; 2x10: 2 kez 10 gün &bull; 2x14: 2 kez 14 gün</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Telefon Numarası</label><input type="tel" name="telefon" placeholder="05XX XXX XX XX" required></div>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-3">
                                <i class="fa-solid fa-bolt"></i> Teklif Al
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper">
            <h2>Elektrikli Araç Kasko Sigortası Nedir?</h2>
            <p>Elektrikli araç kasko sigortası, elektrikli ve hibrit araçlar için özel olarak tasarlanmış bir kasko poliçesidir. Standart kasko teminatlarına ek olarak, elektrikli araçlara özgü riskleri de kapsamaktadır. Batarya hasarı, şarj ünitesi arızası, elektrik sistemi sorunları ve yazılım kaynaklı arızalar gibi durumlar bu özel kasko kapsamında değerlendirilir.</p>

            <p>Elektrikli araçların yaygınlaşmasıyla birlikte, bu araçlara özel sigorta ihtiyacı da artmıştır. Elektrikli araçların batarya ve elektrik sistemi maliyetleri yüksek olduğundan, kapsamlı bir kasko poliçesi oldukça önemlidir.</p>

            <h2>Ek Teminatlar</h2>
            <ul>
                <li><strong>Batarya Koruma:</strong> Elektrikli aracın en önemli bileşeni olan bataryanın hasar görmesi, kapasite kaybı veya arızalanması durumunda taminat sağlar.</li>
                <li><strong>Şarj Ekipmanı:</strong> Ev tipi veya portatif şarj ünitelerinin hasar görmesi veya çalınması.</li>
                <li><strong>Yazılım Güncellemesi:</strong> Siber saldırı veya yazılım hatası kaynaklı sorunlar.</li>
                <li><strong>Çekici Hizmeti:</strong> Elektrikli araca uygun özel çekici hizmeti (düz platform).</li>
                <li><strong>Şarj İstasyonu:</strong> Şarj sırasında oluşabilecek hasarlar.</li>
            </ul>

            <h2>Neden Elektrikli Araç Kaskosu Farklıdır?</h2>
            <p>Elektrikli araçlar, içten yanmalı motorlu araçlardan farklı teknolojilere sahiptir. Bu nedenle:</p>
            <ul>
                <li>Batarya maliyeti aracın toplam değerinin %30-50'sini oluşturabilir</li>
                <li>Onarım ve yedek parça süreçleri farklıdır</li>
                <li>Özel eğitimli teknisyen gerektiren servis ihtiyacı vardır</li>
                <li>Şarj altyapısı ile ilgili ek riskler bulunur</li>
                <li>Yazılım ve elektronik sistem riskleri mevcuttur</li>
            </ul>

            <div class="info-box">
                <p><strong>Bilgi:</strong> Emre Sigorta, Togg dahil tüm elektrikli araç markaları için özel kasko teklifleri sunmaktadır. Aracınızın marka ve modeline göre en uygun poliçeyi bulun.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Elektrikli Aracınızı Koruma Altına Alın!</h2>
            <p>Elektrikli aracınıza özel kasko tekliflerini hemen karşılaştırın.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
