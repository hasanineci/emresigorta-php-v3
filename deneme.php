<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Trafik Sigortası Fiyatları 2026 | En Uygun Teklif Al';
$pageDescription = 'Zorunlu trafik sigortası fiyatlarını karşılaştırın, en uygun teklifi alın.';
$pageKeywords = 'trafik sigortası, zorunlu trafik sigortası, trafik sigortası fiyatları 2026';
?>
<?php include 'includes/header.php'; ?>

<style>
/* === Minimalist Hero === */
.min-hero {
    padding: 80px 0 60px;
    background: #fff;
}
.min-hero-grid {
    display: grid;
    grid-template-columns: 1fr 480px;
    gap: 60px;
    align-items: start;
}
.min-hero-content h1 {
    font-size: 2.4rem;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.25;
    margin-bottom: 16px;
}
.min-hero-content > p {
    font-size: 1rem;
    color: #666;
    line-height: 1.7;
    margin-bottom: 32px;
}
.min-features {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.min-features li {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    color: #444;
}
.min-features li i {
    color: #0066cc;
    font-size: 13px;
    flex-shrink: 0;
}

/* === Minimalist Form Card === */
.min-form-card {
    background: #fafafa;
    border: 1px solid #eee;
    border-radius: 16px;
    padding: 32px;
}
.min-form-card h3 {
    font-size: 1.15rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 4px;
}
.min-form-card .form-desc {
    font-size: 13px;
    color: #999;
    margin-bottom: 24px;
}
.min-form-card label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    color: #888;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.min-form-card input,
.min-form-card select {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    color: #333;
    background: #fff;
    transition: border-color .2s ease;
    box-sizing: border-box;
}
.min-form-card input:focus,
.min-form-card select:focus {
    outline: none;
    border-color: #0066cc;
    box-shadow: 0 0 0 3px rgba(0,102,204,.08);
}
.min-form-card input::placeholder {
    color: #bbb;
}
.min-form-card small {
    font-size: 11px;
    color: #aaa;
    margin-top: 4px;
    display: block;
}
.min-form-card .form-group-min {
    margin-bottom: 16px;
}
.min-submit-btn {
    width: 100%;
    padding: 13px;
    border: none;
    border-radius: 10px;
    background: #0066cc;
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s ease;
    margin-top: 8px;
}
.min-submit-btn:hover {
    background: #0052a3;
}

/* === Separator === */
.min-separator {
    height: 1px;
    background: #eee;
}

/* === Breadcrumb Minimal === */
.min-breadcrumb {
    padding: 16px 0;
    font-size: 13px;
    color: #999;
}
.min-breadcrumb a {
    color: #666;
    text-decoration: none;
}
.min-breadcrumb a:hover {
    color: #0066cc;
}
.min-breadcrumb span {
    margin: 0 8px;
    color: #ccc;
}

/* === Minimal Content === */
.min-content {
    padding: 48px 0 60px;
    max-width: 720px;
}
.min-content h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-top: 40px;
    margin-bottom: 12px;
}
.min-content h2:first-child {
    margin-top: 0;
}
.min-content p {
    font-size: 15px;
    color: #555;
    line-height: 1.75;
}
.min-content ul {
    padding-left: 18px;
    color: #555;
}
.min-content ul li {
    font-size: 15px;
    line-height: 1.75;
    margin-bottom: 4px;
}

/* Minimal Table */
.min-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 14px;
}
.min-table th {
    text-align: left;
    padding: 10px 16px;
    font-weight: 600;
    color: #444;
    border-bottom: 2px solid #e0e0e0;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.min-table td {
    padding: 10px 16px;
    color: #555;
    border-bottom: 1px solid #f0f0f0;
}
.min-table tr:last-child td {
    border-bottom: none;
}

/* Minimal Info Box */
.min-info-box {
    background: #f5f8ff;
    border-left: 3px solid #0066cc;
    padding: 16px 20px;
    border-radius: 0 8px 8px 0;
    margin: 20px 0;
}
.min-info-box p {
    margin: 0;
    font-size: 14px;
    color: #444;
}

/* === Responsive === */
@media (max-width: 992px) {
    .min-hero-grid {
        grid-template-columns: 1fr;
        gap: 32px;
    }
    .min-hero-content h1 {
        font-size: 1.8rem;
    }
}
</style>

<!-- Hero -->
<section class="min-hero">
    <div class="container">
        <div class="min-hero-grid">
            <div class="min-hero-content">
                <h1>Trafik Sigortası</h1>
                <p>Zorunlu trafik sigortanızı en uygun fiyata yaptırın. 20'den fazla sigorta şirketinden anlık teklif alın, karşılaştırın ve tasarruf edin.</p>
                <ul class="min-features">
                    <li><i class="fa-solid fa-check"></i> 30+ sigorta şirketinden anlık teklif</li>
                    <li><i class="fa-solid fa-check"></i> En uygun fiyat garantisi</li>
                    <li><i class="fa-solid fa-check"></i> Dakikalar içinde dijital poliçe</li>
                    <li><i class="fa-solid fa-check"></i> 7/24 hasar desteği</li>
                    <li><i class="fa-solid fa-check"></i> Taksit imkanı</li>
                </ul>
            </div>

            <div class="min-form-card">
                <h3>Teklif Al</h3>
                <p class="form-desc">Bilgilerinizi girin, en uygun teklifi alın</p>
                <form id="trafikForm" data-form-type="trafik" enctype="multipart/form-data">
                    <input type="hidden" name="form_type" value="trafik">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group-min"><label>Ad Soyad</label><input type="text" name="ad_soyad" placeholder="Adınız Soyadınız" required></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-min"><label>Ruhsat Sahibi</label><input type="text" name="ruhsat_sahibi" placeholder="Ruhsat sahibi adı soyadı" required></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-min"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" placeholder="T.C. Kimlik Numarası" maxlength="11" required></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-min"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-min"><label>Araç Plakası</label><input type="text" name="plaka" placeholder="34 ABC 123" required></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-min">
                                <label>Ruhsat Fotoğrafı</label>
                                <input type="file" name="ruhsat_foto" accept=".jpg,.jpeg,.png,.pdf" class="form-control" style="padding:7px 10px;font-size:13px;">
                                <small>JPG, PNG veya PDF</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-min">
                                <label>Araç Engeli / Rehin</label>
                                <select name="arac_engeli" required>
                                    <option value="">Seçiniz</option>
                                    <option value="yok">Hayır, engel yok</option>
                                    <option value="var">Evet, engel var</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-min">
                                <label>İMM Teminat</label>
                                <select name="imm_teminat">
                                    <option value="">İMM İstemiyorum</option>
                                    <option value="1_milyon">1 Milyon TL</option>
                                    <option value="2_milyon">2 Milyon TL</option>
                                    <option value="3_milyon">3 Milyon TL</option>
                                    <option value="5_milyon">5 Milyon TL</option>
                                    <option value="10_milyon">10 Milyon TL</option>
                                    <option value="sinirsiz">Sınırsız İMM</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-min">
                                <label>İkame Araç</label>
                                <select name="ikame_arac">
                                    <option value="">İstemiyorum</option>
                                    <option value="2x7">2x7 Gün</option>
                                    <option value="2x10">2x10 Gün</option>
                                    <option value="2x14">2x14 Gün</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-min"><label>Telefon</label><input type="tel" name="telefon" placeholder="05XX XXX XX XX" required></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-min"><label>E-posta</label><input type="email" name="email" placeholder="ornek@email.com"></div>
                        </div>
                        <div class="col-12">
                            <button type="button" class="min-submit-btn">Teklif Al</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="min-separator"></div>

<!-- Breadcrumb -->
<div class="container">
    <div class="min-breadcrumb">
        <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
        <span>/</span>
        <a href="#">Ürünlerimiz</a>
        <span>/</span>
        Trafik Sigortası
    </div>
</div>

<!-- Content -->
<section style="padding: 0;">
    <div class="container">
        <div class="min-content">
            <h2>Trafik Sigortası Nedir?</h2>
            <p>Trafik sigortası, Türkiye'de trafiğe çıkan tüm motorlu araç sahiplerinin yaptırmak zorunda olduğu zorunlu mali sorumluluk sigortasıdır. Aracınızla üçüncü kişilere verebileceğiniz maddi ve bedeni zararları karşılar.</p>

            <h2>Teminatlar</h2>
            <table class="min-table">
                <thead>
                    <tr>
                        <th>Teminat Türü</th>
                        <th>Kişi Başına</th>
                        <th>Kaza Başına</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Maddi Hasar</td><td>—</td><td>Güncel limit</td></tr>
                    <tr><td>Bedensel Hasar</td><td>Güncel limit</td><td>Güncel limit</td></tr>
                    <tr><td>Ölüm Teminatı</td><td>Güncel limit</td><td>Güncel limit</td></tr>
                    <tr><td>Tedavi Giderleri</td><td>Güncel limit</td><td>Güncel limit</td></tr>
                </tbody>
            </table>

            <div class="min-info-box">
                <p><strong>Not:</strong> Teminat limitleri Hazine ve Maliye Bakanlığı tarafından her yıl güncellenmektedir.</p>
            </div>

            <h2>Fiyatı Etkileyen Faktörler</h2>
            <ul>
                <li><strong>Araç tipi ve modeli</strong> — marka, model ve motor hacmi fiyatı etkiler</li>
                <li><strong>Trafik ceza puanı</strong> — ihlaller priminizi artırabilir</li>
                <li><strong>Hasarsızlık indirimi</strong> — geçmişte hasar yoksa önemli indirimler</li>
                <li><strong>İl ve ilçe</strong> — tescil bölgesi kaza istatistiklerine göre değişir</li>
                <li><strong>Araç yaşı</strong> — yeni araçlar genelde daha yüksek primlidir</li>
                <li><strong>Kullanım amacı</strong> — ticari veya hususi ayrımı fark yaratır</li>
            </ul>

            <h2>Hasarsızlık İndirimi</h2>
            <p>Hasarsızlık indirimi, trafik sigortanızda hasar kaydı bulunmayan her yıl için uygulanan bir indirim sistemidir. Güvenli sürücüleri ödüllendirmek amacıyla oluşturulmuştur.</p>
            <ul>
                <li>1. yıl hasarsız — Başlangıç basamağı</li>
                <li>2. yıl — %10 indirim</li>
                <li>3. yıl — %15 indirim</li>
                <li>4. yıl — %20 indirim</li>
                <li>5. yıl — %30 indirim</li>
                <li>6. yıl — %40 indirim</li>
            </ul>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>