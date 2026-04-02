<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Online Sigorta Teklifi Al | En Uygun Fiyat Garantisi';
$pageDescription = 'Emre Sigorta - Şanlıurfa\'nın güvenilir sigorta acentesi. Trafik sigortası, kasko, DASK, sağlık sigortası, konut sigortası ve daha fazlası için 20+ şirketten online teklif alın. En uygun fiyatlar, hızlı poliçe.';
$pageKeywords = 'sigorta, online sigorta, trafik sigortası, kasko, dask, sağlık sigortası, konut sigortası, şanlıurfa sigorta, emre sigorta, sigorta teklif, sigorta fiyat, en ucuz sigorta, sigorta karşılaştırma';

// WebSite Schema for sitelinks search box
$pageSchema = '<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "' . SITE_NAME . '",
    "alternateName": "Emre Sigorta Aracılık Hizmetleri",
    "url": "https://' . SITE_DOMAIN . '/",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "https://' . SITE_DOMAIN . '/?s={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>';

require_once 'includes/header.php';
?>

<!-- ==================== HERO SECTION ==================== -->
<section class="hero-section" style="background-image: url('assets/images/hero-bg.jpg');">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-7" data-aos="fade-right" data-aos-duration="1000">
                <div class="hero-badge text-white mb-3">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Şanlıurfa'nın Güvenilir Sigorta Acentesi</span>
                </div>
                <h1 class="hero-title text-white mb-4">
                    Geleceğinizi <br>
                    <span class="typing-text" data-words='["Güvence Altına Alın","Koruma Altına Alın","Sigortalayın"]'></span>
                    <span class="text-warning">|</span>
                </h1>
                <p class="hero-subtitle text-white mb-4">
                    Trafik sigortasından kaskoye, sağlık sigortasından DASK'a kadar tüm sigorta ihtiyaçlarınızı en uygun fiyatlarla karşılıyoruz.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#urunler" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold text-dark">
                        <i class="fa-solid fa-bolt me-2"></i>Hemen Teklif Al
                    </a>
                    <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">
                        <i class="fa-solid fa-phone me-2"></i>Bizi Arayın
                    </a>
                </div>
                
                <!-- Trust badges -->
                <div class="hero-trust-strip mt-5 pt-3">
                    <div class="hero-trust-item">
                        <div class="hero-trust-number">5.000+</div>
                        <div class="hero-trust-label">Mutlu Müşteri</div>
                    </div>
                    <div class="hero-trust-divider"></div>
                    <div class="hero-trust-item">
                        <div class="hero-trust-number">20+</div>
                        <div class="hero-trust-label">Sigorta Şirketi</div>
                    </div>
                    <div class="hero-trust-divider"></div>
                    <div class="hero-trust-item">
                        <div class="hero-trust-number">%98</div>
                        <div class="hero-trust-label">Memnuniyet</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <img src="assets/images/hero-side.jpg" alt="Sigorta Danışmanlık" class="img-fluid rounded-4 shadow-lg animate-float" style="border: 4px solid rgba(255,255,255,.1);">
            </div>
        </div>
    </div>
</section>

<!-- ==================== PRODUCTS SECTION ==================== -->
<section class="py-5 bg-light" id="urunler" style="position:relative; z-index:10;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge bg-primary bg-opacity-10 text-primary mb-3 d-inline-block">
                <i class="fa-solid fa-layer-group"></i> SİGORTA ÜRÜNLERİ
            </span>
            <h2 class="section-title">Sigorta Ürünlerimiz</h2>
            <p class="section-subtitle mx-auto mt-2">İhtiyacınıza en uygun sigorta ürününü seçin, hemen online teklif alın.</p>
        </div>
        
        <div class="row g-4">
            <!-- Trafik Sigortası -->
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="0">
                <div class="card product-card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="product-icon icon-blue mx-auto"><i class="fa-solid fa-car"></i></div>
                        <h5 class="card-title">Trafik Sigortası</h5>
                        <p class="card-text">Zorunlu trafik sigortanızı en uygun fiyatlarla hemen yaptırın.</p>
                        <a href="trafik-sigortasi.php" class="product-link">İncele <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Kasko -->
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="50">
                <div class="card product-card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="product-icon icon-green mx-auto"><i class="fa-solid fa-shield-halved"></i></div>
                        <h5 class="card-title">Kasko</h5>
                        <p class="card-text">Aracınızı kapsamlı kasko güvencesiyle koruma altına alın.</p>
                        <a href="kasko.php" class="product-link">İncele <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- DASK -->
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card product-card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="product-icon icon-orange mx-auto"><i class="fa-solid fa-house-crack"></i></div>
                        <h5 class="card-title">DASK</h5>
                        <p class="card-text">Zorunlu deprem sigortanızı hızlı ve kolay şekilde yaptırın.</p>
                        <a href="dask.php" class="product-link">İncele <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Tamamlayıcı Sağlık -->
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="150">
                <div class="card product-card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="product-icon icon-red mx-auto"><i class="fa-solid fa-heart-pulse"></i></div>
                        <h5 class="card-title">Tamamlayıcı Sağlık</h5>
                        <p class="card-text">SGK'ya ek özel sağlık güvencesiyle tedavi masraflarını minimuma indirin.</p>
                        <a href="tamamlayici-saglik.php" class="product-link">İncele <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Konut Sigortası -->
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card product-card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="product-icon icon-teal mx-auto"><i class="fa-solid fa-house"></i></div>
                        <h5 class="card-title">Konut Sigortası</h5>
                        <p class="card-text">Evinizi ve eşyalarınızı doğal afet, hırsızlık gibi risklere karşı koruyun.</p>
                        <a href="konut-sigortasi.php" class="product-link">İncele <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Seyahat Sağlık -->
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="250">
                <div class="card product-card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="product-icon icon-purple mx-auto"><i class="fa-solid fa-plane"></i></div>
                        <h5 class="card-title">Seyahat Sağlık</h5>
                        <p class="card-text">Yurt dışı seyahatlerinizde sağlığınızı güvence altına alın.</p>
                        <a href="seyahat-saglik.php" class="product-link">İncele <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Özel Sağlık -->
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card product-card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="product-icon icon-pink mx-auto"><i class="fa-solid fa-stethoscope"></i></div>
                        <h5 class="card-title">Özel Sağlık</h5>
                        <p class="card-text">Özel hastanelerde fark ödemeden muayene ve tedavi imkanı.</p>
                        <a href="ozel-saglik.php" class="product-link">İncele <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Ferdi Kaza -->
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="350">
                <div class="card product-card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="product-icon icon-orange mx-auto"><i class="fa-solid fa-user-shield"></i></div>
                        <h5 class="card-title">Ferdi Kaza</h5>
                        <p class="card-text">Beklenmedik kaza ve yaralanmalara karşı finansal güvence sağlayın.</p>
                        <a href="ferdi-kaza.php" class="product-link">İncele <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- More products link -->
        <div class="text-center mt-5" data-aos="fade-up">
            <div class="dropdown d-inline-block">
                <a href="#" class="btn btn-primary rounded-pill px-4 py-2" data-bs-toggle="dropdown" aria-expanded="false" id="allProductsBtn">
                    <i class="fa-solid fa-grid-2 me-2"></i>Tüm Ürünleri Gör
                </a>
                <div class="dropdown-menu all-products-menu shadow-lg border-0 p-4 mt-2" aria-labelledby="allProductsBtn">
                    <div class="all-products-grid">
                        <a class="all-products-item" href="yesil-kart.php">
                            <span class="all-products-icon icon-green"><i class="fa-solid fa-id-card"></i></span>
                            <span>Yeşil Kart</span>
                        </a>
                        <a class="all-products-item" href="el-trafik-sigortasi.php">
                            <span class="all-products-icon icon-blue"><i class="fa-solid fa-car"></i></span>
                            <span>2. El Trafik</span>
                        </a>
                        <a class="all-products-item" href="elektrikli-arac-kasko.php">
                            <span class="all-products-icon icon-teal"><i class="fa-solid fa-bolt"></i></span>
                            <span>Elektrikli Araç</span>
                        </a>
                        <a class="all-products-item" href="kisa-sureli-trafik.php">
                            <span class="all-products-icon icon-orange"><i class="fa-solid fa-clock"></i></span>
                            <span>Kısa Süreli Trafik</span>
                        </a>
                        <a class="all-products-item" href="imm.php">
                            <span class="all-products-icon icon-purple"><i class="fa-solid fa-file-contract"></i></span>
                            <span>İMM</span>
                        </a>
                        <a class="all-products-item" href="pembe-kurdele.php">
                            <span class="all-products-icon icon-pink"><i class="fa-solid fa-ribbon"></i></span>
                            <span>Pembe Kurdele</span>
                        </a>
                        <a class="all-products-item" href="cep-telefonu.php">
                            <span class="all-products-icon icon-blue"><i class="fa-solid fa-mobile"></i></span>
                            <span>Cep Telefonu</span>
                        </a>
                        <a class="all-products-item" href="evcil-hayvan.php">
                            <span class="all-products-icon icon-orange"><i class="fa-solid fa-paw"></i></span>
                            <span>Evcil Hayvan</span>
                        </a>
                        <a class="all-products-item" href="evim-guvende.php">
                            <span class="all-products-icon icon-green"><i class="fa-solid fa-house-lock"></i></span>
                            <span>Evim Güvende</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== QUICK QUOTE SECTION ==================== -->
<section class="quote-section py-5">
    <div class="container position-relative" style="z-index:2;">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="text-center mb-4">
                    <span class="section-badge bg-white bg-opacity-10 text-white mb-3 d-inline-block border border-white border-opacity-25">
                        <i class="fa-solid fa-bolt"></i> HIZLI TEKLİF
                    </span>
                    <h2 class="text-white fw-bold fs-2">Hemen Online Teklif Alın</h2>
                    <p class="text-white-50">Bilgilerinizi girin, saniyeler içinde en uygun fiyat teklifini alın.</p>
                </div>
                
                <div class="quote-card p-4">
                    <!-- Tabs -->
                    <ul class="nav quote-tabs justify-content-center gap-2 mb-4 flex-wrap">
                        <li><a href="#" class="nav-link active" data-target="quoteTraffic"><i class="fa-solid fa-car me-1"></i> Trafik</a></li>
                        <li><a href="#" class="nav-link" data-target="quoteKasko"><i class="fa-solid fa-shield-halved me-1"></i> Kasko</a></li>
                        <li><a href="#" class="nav-link" data-target="quoteDask"><i class="fa-solid fa-house me-1"></i> DASK</a></li>
                        <li><a href="#" class="nav-link" data-target="quoteSaglik"><i class="fa-solid fa-heart-pulse me-1"></i> Sağlık</a></li>
                    </ul>
                    
                    <!-- Trafik Form -->
                    <div class="quote-form-content" id="quoteTraffic">
                        <form data-form-type="anasayfa-trafik" class="needs-validation" novalidate>
                            <input type="hidden" name="form_type" value="anasayfa-trafik">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" name="ad_soyad" class="form-control" placeholder="Ad Soyad" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="tc_kimlik" class="form-control" placeholder="TC Kimlik No" maxlength="11" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" name="dogum_tarihi" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="plaka" class="form-control" placeholder="Plaka (ör: 63 ABC 123)" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="tel" name="telefon" class="form-control" placeholder="Telefon Numarası" required>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-warning w-100 py-3 fw-bold rounded-3">
                                        <i class="fa-solid fa-bolt me-2"></i>Teklif Al
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Kasko Form -->
                    <div class="quote-form-content" id="quoteKasko" style="display:none">
                        <form data-form-type="anasayfa-kasko" class="needs-validation" novalidate>
                            <input type="hidden" name="form_type" value="anasayfa-kasko">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" name="ad_soyad" class="form-control" placeholder="Ad Soyad" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="tc_kimlik" class="form-control" placeholder="TC Kimlik No" maxlength="11" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" name="dogum_tarihi" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="plaka" class="form-control" placeholder="Plaka (ör: 63 ABC 123)" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="tel" name="telefon" class="form-control" placeholder="Telefon Numarası" required>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-warning w-100 py-3 fw-bold rounded-3">
                                        <i class="fa-solid fa-bolt me-2"></i>Teklif Al
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- DASK Form -->
                    <div class="quote-form-content" id="quoteDask" style="display:none">
                        <form data-form-type="anasayfa-dask" class="needs-validation" novalidate>
                            <input type="hidden" name="form_type" value="anasayfa-dask">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" name="ad_soyad" class="form-control" placeholder="Ad Soyad" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="tc_kimlik" class="form-control" placeholder="TC Kimlik No" maxlength="11" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" name="dogum_tarihi" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="adres" class="form-control" placeholder="Adres / İl / İlçe" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="tel" name="telefon" class="form-control" placeholder="Telefon Numarası" required>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-warning w-100 py-3 fw-bold rounded-3">
                                        <i class="fa-solid fa-bolt me-2"></i>Teklif Al
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Sağlık Form -->
                    <div class="quote-form-content" id="quoteSaglik" style="display:none">
                        <form data-form-type="anasayfa-saglik" class="needs-validation" novalidate>
                            <input type="hidden" name="form_type" value="anasayfa-saglik">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="ad_soyad" class="form-control" placeholder="Ad Soyad" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="tc_kimlik" class="form-control" placeholder="TC Kimlik No" maxlength="11" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="dogum_tarihi" class="form-control" placeholder="Doğum Tarihi" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="tel" name="telefon" class="form-control" placeholder="Telefon" required>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-warning w-100 py-3 fw-bold rounded-3">
                                        <i class="fa-solid fa-bolt me-2"></i>Sağlık Sigortası Teklifi Al
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== WHY US SECTION ==================== -->
<section class="py-5 bg-pattern">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5" data-aos="fade-right">
                <span class="section-badge bg-primary bg-opacity-10 text-primary mb-3 d-inline-block">
                    <i class="fa-solid fa-star"></i> NEDEN BİZ?
                </span>
                <h2 class="section-title mb-3">Neden Emre Sigorta'yı <br>Tercih Etmelisiniz?</h2>
                <p class="text-muted mb-4">Şanlıurfa'nın güvenilir sigorta acentesi olarak, <?php echo SITE_FOUNDED; ?> yılından bu yana sektördeki deneyimimizle müşterilerimize en iyi hizmeti sunuyoruz.</p>
                <img src="assets/images/team.jpg" alt="Emre Sigorta Ekibi" class="img-fluid rounded-4 shadow-lg img-section">
            </div>
            <div class="col-lg-7">
                <div class="row g-4">
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="0">
                        <div class="card why-card shadow-sm p-4 h-100">
                            <div class="why-icon icon-blue">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </div>
                            <h5 class="fw-bold mb-2">En Uygun Fiyatlar</h5>
                            <p class="text-muted small mb-0">20'den fazla sigorta şirketiyle çalışarak size en uygun fiyatlı teklifi sunuyoruz.</p>
                        </div>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="card why-card shadow-sm p-4 h-100">
                            <div class="why-icon icon-green">
                                <i class="fa-solid fa-headset"></i>
                            </div>
                            <h5 class="fw-bold mb-2">7/24 Destek</h5>
                            <p class="text-muted small mb-0">Hasar anında veya herhangi bir sorunuzda 7/24 yanınızdayız.</p>
                        </div>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="card why-card shadow-sm p-4 h-100">
                            <div class="why-icon icon-orange">
                                <i class="fa-solid fa-bolt"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Hızlı Poliçe</h5>
                            <p class="text-muted small mb-0">Online başvuru ile dakikalar içinde poliçenizi oluşturup teslim ediyoruz.</p>
                        </div>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="card why-card shadow-sm p-4 h-100">
                            <div class="why-icon icon-purple">
                                <i class="fa-solid fa-shield-check"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Güvenli İşlem</h5>
                            <p class="text-muted small mb-0">256-bit SSL şifreleme ve KVKK uyumlu altyapımızla verileriniz güvende.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== STATS SECTION ==================== -->
<section class="stats-section py-5">
    <div class="container position-relative">
        <div class="row g-4 text-center">
            <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-delay="0">
                <div class="stat-item">
                    <div class="stat-number counter" data-target="5000" data-suffix="+">0</div>
                    <div class="stat-label">Mutlu Müşteri</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-delay="100">
                <div class="stat-item">
                    <div class="stat-number counter" data-target="20" data-suffix="+">0</div>
                    <div class="stat-label">Sigorta Şirketi</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-delay="200">
                <div class="stat-item">
                    <div class="stat-number counter" data-target="2022" data-suffix="">0</div>
                    <div class="stat-label">Kuruluş Yılı</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-delay="300">
                <div class="stat-item">
                    <div class="stat-number counter" data-target="98" data-prefix="%" data-suffix="">0</div>
                    <div class="stat-label">Müşteri Memnuniyeti</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== POPULAR PRODUCTS DETAIL ==================== -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge bg-primary bg-opacity-10 text-primary mb-3 d-inline-block">
                <i class="fa-solid fa-fire"></i> POPÜLER
            </span>
            <h2 class="section-title">En Çok Tercih Edilen Ürünlerimiz</h2>
        </div>
        
        <!-- Trafik Sigortası Detail -->
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <img src="assets/images/trafik.jpg" alt="Trafik Sigortası" class="img-fluid rounded-4 shadow-lg img-section">
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <span class="badge bg-primary bg-opacity-10 text-primary fs-6 mb-3 px-3 py-2 rounded-pill">
                    <i class="fa-solid fa-car me-1"></i> Trafik Sigortası
                </span>
                <h3 class="fw-bold mb-3">Zorunlu Trafik Sigortası</h3>
                <p class="text-muted">Aracınızla yolda güvenle seyahat edin. Zorunlu trafik sigortası ile olası kazalarda karşı tarafın maddi ve bedeni zararlarını güvence altına alın.</p>
                <ul class="feature-list mb-4">
                    <li>Maddi ve bedeni hasar güvencesi</li>
                    <li>Yasal zorunluluk - cezasız trafik</li>
                    <li>20+ şirket karşılaştırma</li>
                    <li>Anında poliçe teslimi</li>
                </ul>
                <a href="trafik-sigortasi.php" class="btn btn-primary rounded-pill px-4">
                    <i class="fa-solid fa-arrow-right me-2"></i>Teklif Al
                </a>
            </div>
        </div>
        
        <!-- Kasko Detail -->
        <div class="row align-items-center g-5 mb-5 flex-lg-row-reverse">
            <div class="col-lg-6" data-aos="fade-left">
                <img src="assets/images/kasko.jpg" alt="Kasko Sigortası" class="img-fluid rounded-4 shadow-lg img-section">
            </div>
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge bg-success bg-opacity-10 text-success fs-6 mb-3 px-3 py-2 rounded-pill">
                    <i class="fa-solid fa-shield-halved me-1"></i> Kasko
                </span>
                <h3 class="fw-bold mb-3">Kapsamlı Kasko Sigortası</h3>
                <p class="text-muted">Aracınızı her türlü riske karşı koruyun. Çarpma, çarpılma, doğal afet, hırsızlık ve çok daha fazlası kasko güvencesi altında.</p>
                <ul class="feature-list mb-4">
                    <li>Tam kapsamlı araç koruması</li>
                    <li>Doğal afet ve hırsızlık güvencesi</li>
                    <li>İkame araç hizmeti</li>
                    <li>Anlaşmalı servis ağı</li>
                </ul>
                <a href="kasko.php" class="btn btn-success rounded-pill px-4">
                    <i class="fa-solid fa-arrow-right me-2"></i>Teklif Al
                </a>
            </div>
        </div>
        
        <!-- Sağlık Sigortası Detail -->
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <img src="assets/images/saglik.jpg" alt="Sağlık Sigortası" class="img-fluid rounded-4 shadow-lg img-section">
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <span class="badge bg-danger bg-opacity-10 text-danger fs-6 mb-3 px-3 py-2 rounded-pill">
                    <i class="fa-solid fa-heart-pulse me-1"></i> Sağlık Sigortası
                </span>
                <h3 class="fw-bold mb-3">Tamamlayıcı Sağlık Sigortası</h3>
                <p class="text-muted">SGK'ya tamamlayıcı olarak özel hastanelerde fark ödemeden muayene ve tedavi imkanı. Sağlığınız güvence altında.</p>
                <ul class="feature-list mb-4">
                    <li>Özel hastanede fark ödemeden tedavi</li>
                    <li>Geniş anlaşmalı hastane ağı</li>
                    <li>Yatarak ve ayakta tedavi</li>
                    <li>Uygun aylık taksit imkanı</li>
                </ul>
                <a href="tamamlayici-saglik.php" class="btn btn-danger rounded-pill px-4">
                    <i class="fa-solid fa-arrow-right me-2"></i>Teklif Al
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PARTNERS SECTION ==================== -->
<section class="partners-section">
    <div class="partners-section-bg"></div>
    <div class="container position-relative" style="z-index:2;">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="partners-badge">
                <i class="fa-solid fa-handshake"></i> İŞ ORTAKLARI
            </span>
            <h2 class="partners-title">Anlaşmalı Sigorta Şirketlerimiz</h2>
            <p class="partners-desc mx-auto">Türkiye'nin en büyük sigorta şirketleriyle çalışarak size en uygun teklifleri sunuyoruz.</p>
        </div>
        
        <div class="partners-grid" data-aos="fade-up" data-aos-delay="100">
            <?php
            $partners = getAllPartners(true);
            foreach ($partners as $partner): ?>
            <div class="partners-grid-item">
                <?php if (!empty($partner['logo'])): ?>
                    <img src="<?php echo SITE_URL . '/' . htmlspecialchars($partner['logo'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($partner['name'], ENT_QUOTES, 'UTF-8'); ?>" class="partner-logo" loading="lazy">
                <?php else: ?>
                    <span class="partner-name-text"><?php echo htmlspecialchars($partner['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ==================== TESTIMONIALS ==================== -->
<?php $testimonials = getAllTestimonials(true); ?>
<?php if (!empty($testimonials)): ?>
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge bg-warning bg-opacity-10 text-warning mb-3 d-inline-block">
                <i class="fa-solid fa-quote-left"></i> YORUMLAR
            </span>
            <h2 class="section-title">Müşterilerimiz Ne Diyor?</h2>
        </div>
        
        <div class="position-relative" data-aos="fade-up">
            <div class="swiper testimonialSwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $testimonial): ?>
                    <div class="swiper-slide">
                        <div class="card testimonial-card shadow-sm p-4 h-100">
                            <div class="stars mb-3">
                                <?php for ($si = 0; $si < $testimonial['rating']; $si++): ?>
                                <i class="fa-solid fa-star"></i>
                                <?php endfor; ?>
                                <?php for ($si = $testimonial['rating']; $si < 5; $si++): ?>
                                <i class="fa-regular fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="text-muted small mb-4">"<?php echo htmlspecialchars($testimonial['comment'], ENT_QUOTES, 'UTF-8'); ?>"</p>
                            <div class="d-flex align-items-center gap-3 mt-auto">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width:45px;height:45px;font-size:18px;font-weight:700;background:<?php echo htmlspecialchars($testimonial['avatar_color'], ENT_QUOTES, 'UTF-8'); ?>;"><?php echo mb_strtoupper(mb_substr($testimonial['author_name'], 0, 1, 'UTF-8'), 'UTF-8'); ?></div>
                                <div>
                                    <div class="fw-bold small"><?php echo htmlspecialchars($testimonial['author_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="text-muted" style="font-size:12px"><?php echo htmlspecialchars($testimonial['author_title'], ENT_QUOTES, 'UTF-8'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination mt-4"></div>
            </div>
            <div class="swiper-button-prev testimonial-nav-btn"><i class="fa-solid fa-chevron-left"></i></div>
            <div class="swiper-button-next testimonial-nav-btn"><i class="fa-solid fa-chevron-right"></i></div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <style>
    .testimonialSwiper { padding-bottom: 50px; }
    .testimonialSwiper .swiper-slide { height: auto; }
    .testimonialSwiper .swiper-pagination-bullet-active { background: var(--secondary); }
    .testimonial-nav-btn { width: 44px !important; height: 44px !important; border-radius: 50%; background: #fff; box-shadow: var(--shadow-sm); transition: var(--transition); }
    .testimonial-nav-btn:hover { background: var(--primary); box-shadow: var(--shadow-primary); }
    .testimonial-nav-btn::after { display: none; }
    .testimonial-nav-btn i { font-size: 16px; color: var(--dark); transition: color 0.3s; }
    .testimonial-nav-btn:hover i { color: #fff; }
    @media (max-width: 767px) { .testimonial-nav-btn { display: none !important; } }
    </style>
    <script>
    new Swiper('.testimonialSwiper', {
        slidesPerView: 1,
        spaceBetween: 24,
        loop: <?php echo count($testimonials) > 3 ? 'true' : 'false'; ?>,
        autoplay: { delay: 5000, disableOnInteraction: false },
        pagination: { el: '.testimonialSwiper .swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        breakpoints: {
            768: { slidesPerView: 2 },
            992: { slidesPerView: 3 }
        }
    });
    </script>
</section>
<?php endif; ?>

<!-- ==================== FAQ SECTION ==================== -->
<?php
$homepageFaqs = getAllFaqs(['show_on_homepage' => 1, 'is_active' => 1]);
if (!empty($homepageFaqs)):
?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-4" data-aos="fade-right">
                <span class="section-badge bg-primary bg-opacity-10 text-primary mb-3 d-inline-block">
                    <i class="fa-solid fa-circle-question"></i> SSS
                </span>
                <h2 class="section-title mb-3">Sıkça Sorulan Sorular</h2>
                <p class="text-muted mb-4">Sigorta hakkında merak ettiğiniz tüm soruların cevapları burada. Bulamazsanız bize ulaşın!</p>
                <a href="sss.php" class="btn btn-primary rounded-pill px-4">
                    <i class="fa-solid fa-list me-2"></i>Tüm Sorular
                </a>
            </div>
            <div class="col-lg-8" data-aos="fade-left">
                <div class="accordion faq-accordion" id="faqAccordion">
                    <?php foreach ($homepageFaqs as $fIdx => $faq): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?php echo $fIdx > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $faq['id']; ?>" <?php echo $fIdx === 0 ? 'aria-expanded="true"' : ''; ?>>
                                <?php echo htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8'); ?>
                            </button>
                        </h2>
                        <div id="faq<?php echo $faq['id']; ?>" class="accordion-collapse collapse <?php echo $fIdx === 0 ? 'show' : ''; ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo nl2br(htmlspecialchars($faq['answer'], ENT_QUOTES, 'UTF-8')); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ==================== CTA SECTION ==================== -->
<section class="cta-section py-5">
    <div class="container position-relative" style="z-index:2;">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-aos="zoom-in">
                <div class="mb-4">
                    <i class="fa-solid fa-headset text-white" style="font-size: 3rem; opacity: .8;"></i>
                </div>
                <h2 class="text-white fw-bold display-6 mb-3">Hemen Sigorta Teklifinizi Alın</h2>
                <p class="text-white-50 mb-4 fs-5">En uygun sigorta fiyatlarını karşılaştırın, dakikalar içinde poliçenizi oluşturun.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold">
                        <i class="fa-solid fa-phone me-2"></i><?php echo SITE_PHONE; ?>
                    </a>
                    <a href="https://wa.me/<?php echo str_replace('+', '', SITE_PHONE_RAW); ?>?text=Merhaba,%20sigorta%20hakkında%20bilgi%20almak%20istiyorum." class="btn btn-outline-light btn-lg rounded-pill px-5" target="_blank">
                        <i class="fa-brands fa-whatsapp me-2"></i>WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== SEO CONTENT ==================== -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10" data-aos="fade-up">
                <div class="text-center mb-4">
                    <span class="section-badge bg-primary bg-opacity-10 text-primary mb-3 d-inline-block">
                        <i class="fa-solid fa-building"></i> HAKKIMIZDA
                    </span>
                    <h2 class="section-title"><?php echo SITE_NAME; ?> - Şanlıurfa Sigorta Acentesi</h2>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <p class="text-muted" style="line-height:1.8"><?php echo SITE_NAME; ?> Aracılık Hizmetleri olarak, Şanlıurfa'da sigorta sektöründe güvenilir ve profesyonel hizmet sunuyoruz. Trafik sigortası, kasko, DASK, konut sigortası, sağlık sigortası ve daha birçok ürünümüzle müşterilerimizin her türlü sigorta ihtiyacını karşılıyoruz.</p>
                        <p class="text-muted" style="line-height:1.8">Türkiye'nin önde gelen sigorta şirketleriyle olan güçlü iş ortaklıklarımız sayesinde, müşterilerimize en uygun fiyatları ve en kapsamlı güvenceleri sunabiliyoruz.</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted" style="line-height:1.8">Online sigorta tekliflerimiz ile zamandan tasarruf ederken, uzman sigorta danışmanlarımız sayesinde ihtiyacınıza en uygun poliçeyi bulmak artık çok kolay. Müşteri memnuniyetini ön planda tutan yaklaşımımızla, sigorta süreçlerinizi hızlı ve sorunsuz bir şekilde yönetiyoruz.</p>
                        <p class="text-muted" style="line-height:1.8">Şanlıurfa ve çevresinde sigorta hizmeti almak için bizi arayabilir, ofisimizi ziyaret edebilir veya online teklif formlarımızı kullanabilirsiniz.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
