<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'DASK - Zorunlu Deprem Sigortası 2026 | Fiyat Hesaplama';
$pageDescription = 'DASK zorunlu deprem sigortanızı Emre Sigorta\'dan kolay ve hızlıca yaptırın. 2026 güncel DASK fiyatları, online başvuru, anında poliçe. Evinizi depreme karşı güvence altına alın.';
$pageKeywords = 'dask, zorunlu deprem sigortası, dask fiyatı 2026, dask hesaplama, dask sigortası yaptırma, deprem sigortası, şanlıurfa dask, ev sigortası, emre sigorta dask';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('DASK - Zorunlu Deprem Sigortası', 'Zorunlu deprem sigortası DASK. Online başvuru, anında poliçe.', 'https://' . SITE_DOMAIN . '/dask.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'DASK - Zorunlu Deprem Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>DASK - Zorunlu Deprem Sigortası</h1>
                <p>Evinizi deprem riskine karşı güvence altına alın. Zorunlu deprem sigortanızı Emre Sigorta ile hızlı ve kolay şekilde yaptırın.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Zorunlu deprem sigortası</li>
                    <li><i class="fa-solid fa-circle-check"></i> Uygun fiyat</li>
                    <li><i class="fa-solid fa-circle-check"></i> Hızlı poliçe oluşturma</li>
                    <li><i class="fa-solid fa-circle-check"></i> Yangın ve patlama teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Tsunami teminatı</li>
                </ul>
            </div>
            <div class="product-form-card">
                <h3>DASK Teklif Al</h3>
                <p class="form-subtitle">Zorunlu deprem sigortanızı hemen yaptırın</p>
                <form data-form-type="dask">
                    <input type="hidden" name="form_type" value="dask">
                    <div class="row g-3">
                        <!-- Kişisel Bilgiler -->
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Ad Soyad</label><input type="text" name="ad_soyad" placeholder="Adınız ve Soyadınız" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" placeholder="T.C. Kimlik Numaranız" maxlength="11" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Telefon</label><input type="tel" name="telefon" placeholder="05XX XXX XX XX" required></div>
                        </div>

                        <!-- Adres Bilgileri -->
                        <div class="col-md-4">
                            <div class="form-group-page"><label>İl</label>
                                <select id="daskIl" required>
                                    <option value="">İl Seçiniz</option>
                                </select>
                                <input type="hidden" name="il" id="daskIlText">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>İlçe</label>
                                <select id="daskIlce" required disabled>
                                    <option value="">Önce İl Seçiniz</option>
                                </select>
                                <input type="hidden" name="ilce" id="daskIlceText">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Bucak / Köy</label>
                                <select id="daskBucak" required disabled>
                                    <option value="">Önce İlçe Seçiniz</option>
                                </select>
                                <input type="hidden" name="bucak" id="daskBucakText">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Mahalle</label>
                                <select id="daskMahalle" required disabled>
                                    <option value="">Önce Bucak Seçiniz</option>
                                </select>
                                <input type="hidden" name="mahalle" id="daskMahalleText">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Cadde / Sokak</label>
                                <select id="daskCadde" required disabled>
                                    <option value="">Önce Mahalle Seçiniz</option>
                                </select>
                                <input type="hidden" name="cadde_sokak" id="daskCaddeText">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Bina No</label>
                                <select id="daskBina" required disabled>
                                    <option value="">Önce Cadde Seçiniz</option>
                                </select>
                                <input type="hidden" name="bina_no" id="daskBinaText">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>İç Kapı No</label>
                                <select id="daskIcKapi" required disabled>
                                    <option value="">Önce Bina Seçiniz</option>
                                </select>
                                <input type="hidden" name="ic_kapi_no" id="daskIcKapiText">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Adres Kodu (UAVT)</label><input type="text" name="adres_kodu" id="daskAdresKodu" placeholder="Otomatik gelecektir" readonly></div>
                        </div>

                        <!-- Bina Bilgileri -->
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Yapı Tarzı</label>
                                <select name="yapi_tarzi" required>
                                    <option value="">Seçiniz</option>
                                    <option value="Çelik/Betonarme">Çelik / Betonarme</option>
                                    <option value="Yığma Kagir">Yığma Kagir</option>
                                    <option value="Diğer">Diğer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Brüt Metrekare</label><input type="number" name="brut_metrekare" placeholder="Örn: 120" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Bina İnşa Yılı</label>
                                <select name="bina_insa_yili" required>
                                    <option value="">Seçiniz</option>
                                    <option value="1975 ve öncesi">1975 ve Öncesi</option>
                                    <option value="1976-1999">1976 - 1999</option>
                                    <option value="2000-2006">2000 - 2006</option>
                                    <option value="2007-2019">2007 - 2019</option>
                                    <option value="2020 ve sonrası">2020 ve Sonrası</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Bina Toplam Kat Sayısı</label>
                                <select name="bina_kat_sayisi" required>
                                    <option value="">Seçiniz</option>
                                    <option value="01-03 arası">01 - 03 Arası</option>
                                    <option value="04-07 arası">04 - 07 Arası</option>
                                    <option value="08-18 arası">08 - 18 Arası</option>
                                    <option value="19 ve üzeri">19 ve Üzeri</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Bina Hasar Durumu</label>
                                <select name="bina_hasar_durumu" required>
                                    <option value="">Seçiniz</option>
                                    <option value="Hasarsız">Hasarsız</option>
                                    <option value="Az Hasarlı">Az Hasarlı</option>
                                    <option value="Hasarlı">Hasarlı</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Bulunduğu Kat</label>
                                <select name="bulundugu_kat" required>
                                    <option value="">Seçiniz</option>
                                    <option value="-4 ve altı">-4 ve Altı Katlar</option>
                                    <option value="-3. kat">-3. Kat</option>
                                    <option value="-2. kat">-2. Kat</option>
                                    <option value="-1. kat">-1. Kat</option>
                                    <option value="Zemin">Zemin</option>
                                    <option value="1. kat">1. Kat</option>
                                    <option value="2. kat">2. Kat</option>
                                    <option value="3. kat">3. Kat</option>
                                    <option value="4. kat">4. Kat</option>
                                    <option value="5. kat">5. Kat</option>
                                    <option value="6. kat">6. Kat</option>
                                    <option value="7. kat">7. Kat</option>
                                    <option value="8. kat">8. Kat</option>
                                    <option value="9. kat">9. Kat</option>
                                    <option value="10. kat">10. Kat</option>
                                    <option value="11 ve üzeri">11 ve Üzeri Katlar</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sigorta Bilgileri -->
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Sigorta Ettiren Sıfatı</label>
                                <select name="sigorta_sifati" required>
                                    <option value="">Seçiniz</option>
                                    <option value="Mal Sahibi">Mal Sahibi</option>
                                    <option value="Kiracı">Kiracı</option>
                                    <option value="İntifa Hakkı Sahibi">İntifa Hakkı Sahibi</option>
                                    <option value="Yönetici">Yönetici</option>
                                    <option value="Akraba">Akraba</option>
                                    <option value="Daini Mürtehin">Daini Mürtehin</option>
                                    <option value="Diğer">Diğer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-page"><label>Kullanım Şekli</label>
                                <select name="kullanim_sekli" required>
                                    <option value="">Seçiniz</option>
                                    <option value="Mesken">Mesken</option>
                                    <option value="Ticarethane">Ticarethane</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
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

<div class="page-content" style="padding: 15px 0; background: var(--gray-100);">
    <div class="container">
        <div class="breadcrumb" style="justify-content: flex-start;">
            <a href="<?php echo SITE_URL; ?>" style="color: var(--primary);">Ana Sayfa</a>
            <span style="color: var(--gray-400);">/</span>
            <span style="color: var(--gray-600);">DASK</span>
        </div>
    </div>
</div>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper">
            <h2>DASK Nedir?</h2>
            <p>DASK (Doğal Afet Sigortaları Kurumu), Türkiye'de zorunlu deprem sigortası uygulamasını yürüten kurumdur. 1999 Marmara Depremi sonrasında kurulan DASK, tüm mesken nitelikli taşınmazlar için zorunlu deprem sigortası poliçesi düzenlemektedir.</p>
            
            <p>DASK poliçesi; deprem, deprem sonucu oluşan yangın, patlama, tsunami ve yer kayması gibi afetlerin neden olduğu maddi hasarları karşılar. Türkiye'de tapu kaydı bulunan tüm mesken nitelikli yapılar için DASK poliçesi zorunludur.</p>

            <h2>DASK Teminat Kapsamı</h2>
            <table class="coverage-table">
                <thead>
                    <tr>
                        <th>Kapsam</th>
                        <th>Açıklama</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><strong>Deprem</strong></td><td>Deprem sonucu oluşan yapısal hasarlar</td></tr>
                    <tr><td><strong>Yangın</strong></td><td>Deprem sonucu çıkan yangın hasarları</td></tr>
                    <tr><td><strong>Patlama</strong></td><td>Deprem kaynaklı patlama hasarları</td></tr>
                    <tr><td><strong>Tsunami</strong></td><td>Deprem kaynaklı tsunami hasarları</td></tr>
                    <tr><td><strong>Yer Kayması</strong></td><td>Deprem sonucu oluşan yer kayması hasarları</td></tr>
                </tbody>
            </table>

            <div class="info-box warning">
                <p><strong>Dikkat:</strong> DASK yalnızca binanın yapısını (duvarlar, döşemeler, tavan vb.) kapsar. Ev eşyaları, dekorasyon ve taşınır mallar DASK kapsamında değildir. Bunlar için ayrıca konut sigortası yaptırmanız önerilir.</p>
            </div>

            <h2>DASK Fiyatı Nasıl Hesaplanır?</h2>
            <p>DASK prim tutarı aşağıdaki kriterlere göre hesaplanır:</p>
            <ul>
                <li><strong>Deprem bölgesi:</strong> Konutun bulunduğu bölgenin deprem risk grubu</li>
                <li><strong>Yapı tarzı:</strong> Çelik/betonarme, yığma kagir veya diğer yapı türleri</li>
                <li><strong>Brüt yüzölçümü:</strong> Konutun toplam brüt metrekaresi</li>
                <li><strong>Bina yaşı:</strong> Binanın inşa edildiği yıl</li>
                <li><strong>Kat sayısı:</strong> Binanın toplam kat adedi</li>
            </ul>

            <h2>DASK Zorunlu mu?</h2>
            <p>Evet, DASK Türkiye'de tapusu olan tüm mesken nitelikli bağımsız bölümler için zorunludur. DASK poliçesi olmadan:</p>
            <ul>
                <li>Konut satış işlemi yapılamaz</li>
                <li>Tapu devir işlemleri gerçekleştirilemez</li>
                <li>Banka kredisi kullanılamaz</li>
                <li>Kiralama sözleşmesi onaylanamaz</li>
                <li>Deprem sonrası devlet yardımlarından yararlanamazsınız</li>
            </ul>

            <h2>DASK ile Konut Sigortası Arasındaki Fark</h2>
            <p>DASK yalnızca deprem ve deprem kaynaklı hasarları karşılarken, konut sigortası çok daha geniş kapsamlı bir koruma sağlar. İdeal olan her iki sigortanın birlikte yaptırılmasıdır.</p>
            
            <table class="coverage-table">
                <thead>
                    <tr>
                        <th>Özellik</th>
                        <th>DASK</th>
                        <th>Konut Sigortası</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Deprem hasarı</td><td>✅ Karşılanır</td><td>✅ Karşılanır</td></tr>
                    <tr><td>Yangın</td><td>⚠️ Sadece deprem kaynaklı</td><td>✅ Tüm yangınlar</td></tr>
                    <tr><td>Hırsızlık</td><td>❌ Karşılanmaz</td><td>✅ Karşılanır</td></tr>
                    <tr><td>Su hasarı</td><td>❌ Karşılanmaz</td><td>✅ Karşılanır</td></tr>
                    <tr><td>Ev eşyaları</td><td>❌ Karşılanmaz</td><td>✅ Karşılanır</td></tr>
                    <tr><td>Cam kırılması</td><td>❌ Karşılanmaz</td><td>✅ Karşılanır</td></tr>
                    <tr><td>Zorunluluk</td><td>✅ Zorunlu</td><td>❌ İsteğe bağlı</td></tr>
                </tbody>
            </table>

            <h2>Emre Sigorta ile DASK Nasıl Alınır?</h2>
            <ol>
                <li>T.C. kimlik numaranızı ve adres bilgilerinizi girin</li>
                <li>Yapı tarzı ve metrekare bilgilerinizi seçin</li>
                <li>Otomatik hesaplanan DASK primini görüntüleyin</li>
                <li>Online ödemenizi yapın</li>
                <li>DASK poliçeniz anında e-postanıza gönderilir</li>
            </ol>

            <div class="info-box success">
                <p><strong>İpucu:</strong> DASK poliçenizi konut sigortası ile birlikte yaptırarak evinizi hem depreme hem de diğer tüm risklere karşı tam koruma altına alabilirsiniz.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Evinizi Depreme Karşı Koruyun!</h2>
            <p>Zorunlu deprem sigortanızı hemen online olarak yaptırın, eviniz güvende olsun.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                <i class="fa-solid fa-bolt"></i> Hemen DASK Al
            </a>
        </div>
    </div>
</section>

<!-- DASK Cascading Dropdown JS -->
<script>
(function() {
    'use strict';
    var siteUrl = document.querySelector('meta[name="site-url"]');
    if (!siteUrl) return;
    siteUrl = siteUrl.content;

    var ilSelect = document.getElementById('daskIl');
    var ilceSelect = document.getElementById('daskIlce');
    var bucakSelect = document.getElementById('daskBucak');
    var mahalleSelect = document.getElementById('daskMahalle');
    var caddeSelect = document.getElementById('daskCadde');
    var binaSelect = document.getElementById('daskBina');
    var icKapiSelect = document.getElementById('daskIcKapi');
    var adresKodu = document.getElementById('daskAdresKodu');

    // Hidden text fields
    var ilText = document.getElementById('daskIlText');
    var ilceText = document.getElementById('daskIlceText');
    var bucakText = document.getElementById('daskBucakText');
    var mahalleText = document.getElementById('daskMahalleText');
    var caddeText = document.getElementById('daskCaddeText');
    var binaText = document.getElementById('daskBinaText');
    var icKapiText = document.getElementById('daskIcKapiText');

    if (!ilSelect) return;

    var API = siteUrl + '/api/dask-adres.php';

    function getSelectedText(sel) {
        return sel.selectedIndex > 0 ? sel.options[sel.selectedIndex].textContent : '';
    }

    function resetSelect(el, text) {
        el.innerHTML = '<option value="">' + text + '</option>';
        el.disabled = true;
    }

    function fillSelect(el, items, labelKey, valueKey, placeholder) {
        el.innerHTML = '<option value="">' + placeholder + '</option>';
        items.forEach(function(item) {
            var opt = document.createElement('option');
            opt.value = item[valueKey];
            opt.textContent = item[labelKey];
            if (item.tur) opt.dataset.tur = item.tur;
            el.appendChild(opt);
        });
        el.disabled = false;
    }

    function resetFrom(level) {
        if (level <= 1) { resetSelect(ilceSelect, 'Önce İl Seçiniz'); ilceText.value = ''; }
        if (level <= 2) { resetSelect(bucakSelect, 'Önce İlçe Seçiniz'); bucakText.value = ''; }
        if (level <= 3) { resetSelect(mahalleSelect, 'Önce Bucak Seçiniz'); mahalleText.value = ''; }
        if (level <= 4) { resetSelect(caddeSelect, 'Önce Mahalle Seçiniz'); caddeText.value = ''; }
        if (level <= 5) { resetSelect(binaSelect, 'Önce Cadde Seçiniz'); binaText.value = ''; }
        if (level <= 6) { resetSelect(icKapiSelect, 'Önce Bina Seçiniz'); icKapiText.value = ''; }
        adresKodu.value = '';
    }

    // DASK oturumunu başlat
    fetch(API + '?step=init').then(function(r) { return r.json(); }).then(function() {
        return fetch(API + '?step=iller');
    }).then(function(r) { return r.json(); }).then(function(data) {
        if (data.error) return;
        fillSelect(ilSelect, data, 'ad', 'id', 'İl Seçiniz');
    });

    // İl → İlçe
    ilSelect.addEventListener('change', function() {
        ilText.value = getSelectedText(this);
        resetFrom(1);
        if (!this.value) { ilText.value = ''; return; }
        ilceSelect.innerHTML = '<option value="">Yükleniyor...</option>';
        fetch(API + '?step=ilceler&il=' + this.value)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.error) { resetSelect(ilceSelect, 'Hata oluştu'); return; }
                fillSelect(ilceSelect, data, 'ad', 'id', 'İlçe Seçiniz');
            });
    });

    // İlçe → Bucak
    ilceSelect.addEventListener('change', function() {
        ilceText.value = getSelectedText(this);
        resetFrom(2);
        if (!this.value) { ilceText.value = ''; return; }
        bucakSelect.innerHTML = '<option value="">Yükleniyor...</option>';
        fetch(API + '?step=bucaklar&ilce=' + this.value)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.error) { resetSelect(bucakSelect, 'Hata oluştu'); return; }
                if (data.length === 1) {
                    fillSelect(bucakSelect, data, 'ad', 'id', 'Bucak Seçiniz');
                    bucakSelect.value = data[0].id;
                    bucakSelect.dispatchEvent(new Event('change'));
                } else {
                    fillSelect(bucakSelect, data, 'ad', 'id', 'Bucak Seçiniz');
                }
            });
    });

    // Bucak → Mahalle
    bucakSelect.addEventListener('change', function() {
        bucakText.value = getSelectedText(this);
        resetFrom(3);
        if (!this.value) { bucakText.value = ''; return; }
        mahalleSelect.innerHTML = '<option value="">Yükleniyor...</option>';
        fetch(API + '?step=mahalleler&bucak=' + this.value)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.error) { resetSelect(mahalleSelect, 'Hata oluştu'); return; }
                fillSelect(mahalleSelect, data, 'ad', 'id', 'Mahalle Seçiniz');
            });
    });

    // Mahalle → Cadde/Sokak listesini yükle
    mahalleSelect.addEventListener('change', function() {
        mahalleText.value = getSelectedText(this);
        resetFrom(4);
        if (!this.value) { mahalleText.value = ''; return; }
        caddeSelect.innerHTML = '<option value="">Yükleniyor...</option>';
        fetch(API + '?step=caddeler&mahalle=' + this.value + '&term=')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.error) { resetSelect(caddeSelect, 'Hata oluştu'); return; }
                data.forEach(function(item) {
                    item.label = item.ad + ' (' + item.tur + ')';
                });
                fillSelect(caddeSelect, data, 'label', 'id', 'Cadde / Sokak Seçiniz (' + data.length + ' adet)');
            });
    });

    // Cadde → Bina listesini yükle
    caddeSelect.addEventListener('change', function() {
        caddeText.value = getSelectedText(this);
        resetFrom(5);
        if (!this.value) { caddeText.value = ''; return; }
        var sokakId = this.value;
        binaSelect.innerHTML = '<option value="">Yükleniyor...</option>';
        fetch(API + '?step=binalar&sokak=' + sokakId + '&term=')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.error) {
                    var binaNo = prompt('Bu sokakta çok fazla bina var.\nLütfen bina numaranızı yazınız:');
                    if (binaNo && binaNo.trim()) {
                        binaSelect.innerHTML = '<option value="">Aranıyor...</option>';
                        fetch(API + '?step=binalar&sokak=' + sokakId + '&term=' + encodeURIComponent(binaNo.trim()))
                            .then(function(r2) { return r2.json(); })
                            .then(function(data2) {
                                if (data2.error || data2.length === 0) {
                                    resetSelect(binaSelect, 'Bina bulunamadı');
                                    return;
                                }
                                fillSelect(binaSelect, data2, 'ad', 'id', 'Bina Seçiniz (' + data2.length + ' adet)');
                                if (data2.length === 1) {
                                    binaSelect.value = data2[0].id;
                                    binaSelect.dispatchEvent(new Event('change'));
                                }
                            });
                    } else {
                        resetSelect(binaSelect, 'Bina yüklenemedi');
                    }
                    return;
                }
                fillSelect(binaSelect, data, 'ad', 'id', 'Bina Seçiniz (' + data.length + ' adet)');
            });
    });

    // Bina → İç Kapı + Adres Kodu
    binaSelect.addEventListener('change', function() {
        binaText.value = getSelectedText(this);
        resetSelect(icKapiSelect, 'Önce Bina Seçiniz');
        icKapiText.value = '';
        adresKodu.value = '';
        if (!this.value) { binaText.value = ''; return; }
        icKapiSelect.innerHTML = '<option value="">Yükleniyor...</option>';
        fetch(API + '?step=ickapilar&bina=' + this.value)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.error) { resetSelect(icKapiSelect, 'Hata oluştu'); return; }
                icKapiSelect.innerHTML = '<option value="">İç Kapı Seçiniz (' + data.length + ' adet)</option>';
                data.forEach(function(item) {
                    var opt = document.createElement('option');
                    opt.value = item.adres_kodu;
                    opt.textContent = item.ic_kapi;
                    opt.dataset.adresKodu = item.adres_kodu;
                    icKapiSelect.appendChild(opt);
                });
                icKapiSelect.disabled = false;
                if (data.length === 1) {
                    icKapiSelect.value = data[0].adres_kodu;
                    icKapiSelect.dispatchEvent(new Event('change'));
                }
            });
    });

    // İç Kapı → Adres Kodu
    icKapiSelect.addEventListener('change', function() {
        var opt = this.options[this.selectedIndex];
        if (opt && opt.dataset.adresKodu) {
            adresKodu.value = opt.dataset.adresKodu;
            icKapiText.value = opt.textContent;
        } else {
            adresKodu.value = '';
            icKapiText.value = '';
        }
    });
})();
</script>
        }
    });
})();
</script>

<?php include 'includes/footer.php'; ?>
