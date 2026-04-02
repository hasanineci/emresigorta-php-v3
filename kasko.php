<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Kasko Sigortası Fiyatları 2026 | Kasko Teklifi Al';
$pageDescription = 'Kasko sigortası ile aracınızı tüm risklere karşı koruyun. 20+ sigorta şirketinden kasko teklifi karşılaştırın. Tam kasko, mini kasko, muafiyetli kasko seçenekleri. Emre Sigorta güvencesi.';
$pageKeywords = 'kasko sigortası, kasko fiyatları 2026, kasko hesaplama, kasko teklifi, tam kasko, mini kasko, araç kasko, en uygun kasko, şanlıurfa kasko, muafiyetli kasko, emre sigorta';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Kasko Sigortası', 'Kasko sigortası teklifi alın. Tam kasko, mini kasko, muafiyetli kasko seçenekleri.', 'https://' . SITE_DOMAIN . '/kasko.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Kasko Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<!-- Product Hero -->
<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Kasko Sigortası</h1>
                <p>Aracınızı tüm risklere karşı kapsamlı şekilde koruma altına alın. Emre Sigorta ile en uygun kasko tekliflerini karşılaştırın ve hemen poliçenizi oluşturun.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Kaza, hırsızlık ve doğal afet teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Anlaşmalı servis ağı</li>
                    <li><i class="fa-solid fa-circle-check"></i> 7/24 çekici hizmeti</li>
                    <li><i class="fa-solid fa-circle-check"></i> İkame araç imkanı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Mini onarım hizmeti</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>Kasko Teklif Al</h3>
                <p class="form-subtitle">Aracınız için en uygun kasko teklifini alın</p>
                <form id="kaskoForm" data-form-type="kasko" enctype="multipart/form-data">
                    <input type="hidden" name="form_type" value="kasko">
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

<!-- Breadcrumb -->
<div class="page-content" style="padding: 15px 0; background: var(--gray-100);">
    <div class="container">
        <div class="breadcrumb" style="justify-content: flex-start;">
            <a href="<?php echo SITE_URL; ?>" style="color: var(--primary);">Ana Sayfa</a>
            <span style="color: var(--gray-400);">/</span>
            <a href="#" style="color: var(--primary);">Ürünlerimiz</a>
            <span style="color: var(--gray-400);">/</span>
            <span style="color: var(--gray-600);">Kasko Sigortası</span>
        </div>
    </div>
</div>

<!-- Page Content -->
<section class="page-content">
    <div class="container">
        <div class="content-wrapper">
            <h2>Kasko Sigortası Nedir?</h2>
            <p>Kasko sigortası, aracınızı tüm risklere karşı kapsamlı şekilde koruma altına alan ihtiyari (isteğe bağlı) bir sigorta türüdür. Trafik sigortasından farklı olarak, kasko hem kendi aracınızdaki hasarları hem de yaşanabilecek diğer birçok riski teminat altına alır.</p>
            
            <p>Kasko sigortası, aracınızın kaza, hırsızlık, doğal afet, vandalizm, cam kırılması ve daha birçok riske karşı korunmasını sağlar. Modern kasko poliçeleri ayrıca çekici hizmeti, ikame araç, mini onarım gibi ek hizmetler de sunmaktadır.</p>

            <h2>Kasko Sigortası Teminatları</h2>
            
            <table class="coverage-table">
                <thead>
                    <tr>
                        <th>Teminat</th>
                        <th>Açıklama</th>
                        <th>Kapsam</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Çarpma/Çarpışma</strong></td>
                        <td>Aracın başka araç veya nesneyle çarpışması</td>
                        <td>Tam Teminat</td>
                    </tr>
                    <tr>
                        <td><strong>Hırsızlık</strong></td>
                        <td>Aracın çalınması veya çalınmaya teşebbüs</td>
                        <td>Tam Teminat</td>
                    </tr>
                    <tr>
                        <td><strong>Doğal Afet</strong></td>
                        <td>Sel, dolu, fırtına, deprem hasarları</td>
                        <td>Tam Teminat</td>
                    </tr>
                    <tr>
                        <td><strong>Yangın</strong></td>
                        <td>Araçta çıkan veya dışarıdan sirayet eden yangın</td>
                        <td>Tam Teminat</td>
                    </tr>
                    <tr>
                        <td><strong>Cam Kırılması</strong></td>
                        <td>Ön cam, yan cam ve arka cam hasarları</td>
                        <td>Sınırsız/Sınırlı</td>
                    </tr>
                    <tr>
                        <td><strong>İkame Araç</strong></td>
                        <td>Kaza sonrası geçici araç tahsisi</td>
                        <td>15-30 Gün</td>
                    </tr>
                    <tr>
                        <td><strong>Kişisel Eşya</strong></td>
                        <td>Araç içindeki kişisel eşyaların hasarı</td>
                        <td>Limte Bağlı</td>
                    </tr>
                </tbody>
            </table>

            <h2>Kasko Türleri</h2>
            
            <h3>1. Tam Kasko (Dar Kapsamlı)</h3>
            <p>Tam kasko, aracınızın karşılaşabileceği hemen hemen tüm riskleri kapsayan en geniş teminatlı kasko türüdür. Kaza, hırsızlık, doğal afet, yangın ve terör gibi risklerin tamamını içerir. Aracınızın tam değeri üzerinden tazminat ödenir.</p>
            
            <h3>2. Mini Kasko</h3>
            <p>Mini kasko, tam kaskoya göre daha sınırlı teminat sunan ve bu nedenle daha uygun fiyatlı olan bir kasko türüdür. Genellikle sadece belirli riskleri (örneğin hırsızlık ve doğal afet) kapsar, kaza teminatı bulunmayabilir. Bütçe dostu bir seçenek arayanlar için idealdir.</p>
            
            <h3>3. Genişletilmiş Kasko</h3>
            <p>Genişletilmiş kasko, standart kasko teminatlarına ek olarak özel teminatlar sunan bir poliçe türüdür. Avukat masrafları, kilit değişikliği, çocuk koltuğu hasarı gibi ek teminatlar içerebilir. Premium koruma isteyen araç sahipleri için tasarlanmıştır.</p>

            <h2>Kasko Fiyatlarını Etkileyen Faktörler</h2>
            <ul>
                <li><strong>Araç değeri:</strong> Aracınızın güncel piyasa değeri kasko primini doğrudan etkiler.</li>
                <li><strong>Marka ve model:</strong> Yedek parça fiyatları ve tamiri pahalı araçlarda prim daha yüksektir.</li>
                <li><strong>Araç yaşı:</strong> Yeni araçlar genellikle daha yüksek kasko primine sahiptir.</li>
                <li><strong>Sürücü profili:</strong> Yaş, cinsiyet, ehliyet süresi ve hasar geçmişi fiyatı etkiler.</li>
                <li><strong>Kullanım bölgesi:</strong> Trafik yoğunluğu yüksek bölgelerde primler daha yüksek olabilir.</li>
                <li><strong>Seçilen teminatlar:</strong> Eklenen ek teminatlar ve servis paketleri fiyatı artırır.</li>
                <li><strong>Muafiyet oranı:</strong> Daha yüksek muafiyet seçmek primi düşürür.</li>
            </ul>

            <div class="info-box">
                <p><strong>Tasarruf İpucu:</strong> Emre Sigorta'da 20'den fazla sigorta şirketinin kasko tekliflerini saniyeler içinde karşılaştırabilirsiniz. Aynı teminatlarda %30'a varan fiyat farkları olabilmektedir!</p>
            </div>

            <h2>Kasko Hasarında Ne Yapmalı?</h2>
            <ol>
                <li><strong>Güvenliğinizi sağlayın:</strong> Kaza sonrası önce kendinizin ve yolcuların güvenliğini kontrol edin.</li>
                <li><strong>Kaza tespit tutanağı doldurun:</strong> Karşı tarafla birlikte kaza tespit tutanağı doldurun veya polisi arayın.</li>
                <li><strong>Fotoğraf çekin:</strong> Hasar bölgelerinin ve kaza yerinin fotoğraflarını çekin.</li>
                <li><strong>Emre Sigorta'yı arayın:</strong> 7/24 hasar hattımızı arayarak hasar bildiriminde bulunun.</li>
                <li><strong>Anlaşmalı servise gidin:</strong> Aracınızı anlaşmalı servislerimizden birine teslim edin.</li>
                <li><strong>Takip edin:</strong> Hasar sürecinizi online olarak takip edin.</li>
            </ol>
            
            <h2>Sıkça Sorulan Sorular</h2>
            
            <h3>Kasko zorunlu mu?</h3>
            <p>Hayır, kasko sigortası ihtiyari (isteğe bağlı) bir sigortadır. Ancak özellikle yeni ve değerli araçlar için kesinlikle tavsiye edilir. Kredi ile alınan araçlarda banka genellikle kasko sigortası şart koşmaktadır.</p>
            
            <h3>Kasko primi nasıl hesaplanır?</h3>
            <p>Kasko primi; aracınızın markası, modeli, yaşı, değeri, kullanım bölgesi, sürücü profili ve seçilen teminat kapsamına göre hesaplanır. Emre Sigorta'da T.C. kimlik numaranız ve plaka bilgilerinizle anlık hesaplama yapabilirsiniz.</p>
            
            <h3>Kasko ile trafik sigortası birlikte alınmalı mı?</h3>
            <p>Evet, kasko ve trafik sigortasının birlikte alınması tavsiye edilir. Trafik sigortası sadece karşı tarafa verdiğiniz zararları karşılarken, kasko kendi aracınızın hasarlarını da kapsar. İkisinin birlikte alınması tam koruma sağlar.</p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Aracınız İçin En Uygun Kaskoyu Bulun!</h2>
            <p>30+ sigorta şirketinden kasko tekliflerini karşılaştırın, en iyi fiyatı yakalayın.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                <i class="fa-solid fa-bolt"></i> Hemen Teklif Al
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
