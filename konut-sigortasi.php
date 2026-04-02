<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Konut Sigortası 2026 | Ev ve Eşya Sigortası Teklif Al';
$pageDescription = 'Konut sigortası ile evinizi ve eşyalarınızı yangın, hırsızlık, su hasarı, doğal afet ve daha fazlasına karşı koruyun. 2026 güncel fiyatlarla Emre Sigorta\'dan kapsamlı teklif alın.';
$pageKeywords = 'konut sigortası, ev sigortası, eşya sigortası, yangın sigortası, hırsızlık sigortası, konut sigortası fiyat 2026, ev sigortası teklif, emre sigorta konut';
$pageSchema = '<script type="application/ld+json">' . getServiceSchema('Konut Sigortası', 'Ev ve eşya sigortası. Yangın, hırsızlık, su hasarı ve doğal afet teminatı.', 'https://' . SITE_DOMAIN . '/konut-sigortasi.php') . '</script>';
$pageSchema .= "\n" . '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Ürünlerimiz', 'url' => 'https://' . SITE_DOMAIN . '/#urunler'],
    ['name' => 'Konut Sigortası']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="product-hero">
    <div class="container">
        <div class="product-hero-inner">
            <div>
                <h1>Konut Sigortası</h1>
                <p>Evinizi ve değerli eşyalarınızı yangın, hırsızlık, su hasarı ve daha fazlasına karşı koruma altına alın. Emre Sigorta ile en uygun konut sigortası teklifini alın.</p>
                <ul class="hero-features">
                    <li><i class="fa-solid fa-circle-check"></i> Yangın ve doğal afet teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Hırsızlık teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Ev eşyası koruma</li>
                    <li><i class="fa-solid fa-circle-check"></i> Su hasarı teminatı</li>
                    <li><i class="fa-solid fa-circle-check"></i> Cam kırılması teminatı</li>
                </ul>
            </div>
            <div class="product-form-card">
                <style>
                    .product-form-card .row.g-3 > [class*="col-md-3"] {
                        display: flex;
                    }
                    .product-form-card .row.g-3 > [class*="col-md-3"] > .form-group-page {
                        display: flex;
                        flex-direction: column;
                        width: 100%;
                    }
                    .product-form-card .row.g-3 > [class*="col-md-3"] > .form-group-page label {
                        min-height: 34px;
                        display: flex;
                        align-items: flex-end;
                        line-height: 1.2;
                    }
                    .product-form-card .row.g-3 > [class*="col-md-3"] > .form-group-page input,
                    .product-form-card .row.g-3 > [class*="col-md-3"] > .form-group-page select {
                        margin-top: auto;
                    }
                </style>
                <h3>Konut Sigortası Teklifi</h3>
                <p class="form-subtitle">Eviniz için kapsamlı koruma teklifi alın</p>
                <form data-form-type="konut-sigortasi">
                    <input type="hidden" name="form_type" value="konut-sigortasi">
                    <div class="row g-3">
                        <!-- Kişisel Bilgiler -->
                        <div class="col-md-3"><div class="form-group-page"><label>Ad Soyad</label><input type="text" name="ad_soyad" placeholder="Adınız ve Soyadınız" required></div></div>
                        <div class="col-md-3"><div class="form-group-page"><label>T.C. Kimlik No</label><input type="text" name="tc_kimlik" maxlength="11" placeholder="T.C. Kimlik Numaranız" required></div></div>
                        <div class="col-md-3"><div class="form-group-page"><label>Doğum Tarihi</label><input type="date" name="dogum_tarihi" required></div></div>
                        <div class="col-md-3"><div class="form-group-page"><label>Telefon</label><input type="tel" name="telefon" placeholder="05XX XXX XX XX" required></div></div>

                        <!-- Adres Bilgileri (DASK API) -->
                        <div class="col-md-3">
                            <div class="form-group-page"><label>İl</label>
                                <select id="konutIl" required>
                                    <option value="">İl Seçiniz</option>
                                </select>
                                <input type="hidden" name="il" id="konutIlText">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>İlçe</label>
                                <select id="konutIlce" required disabled>
                                    <option value="">Önce İl Seçiniz</option>
                                </select>
                                <input type="hidden" name="ilce" id="konutIlceText">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Bucak / Köy</label>
                                <select id="konutBucak" required disabled>
                                    <option value="">Önce İlçe Seçiniz</option>
                                </select>
                                <input type="hidden" name="bucak" id="konutBucakText">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Mahalle</label>
                                <select id="konutMahalle" required disabled>
                                    <option value="">Önce Bucak Seçiniz</option>
                                </select>
                                <input type="hidden" name="mahalle" id="konutMahalleText">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Cadde / Sokak</label>
                                <select id="konutCadde" required disabled>
                                    <option value="">Önce Mahalle Seçiniz</option>
                                </select>
                                <input type="hidden" name="cadde_sokak" id="konutCaddeText">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Bina No</label>
                                <select id="konutBina" required disabled>
                                    <option value="">Önce Cadde Seçiniz</option>
                                </select>
                                <input type="hidden" name="bina_no" id="konutBinaText">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>İç Kapı No</label>
                                <select id="konutIcKapi" required disabled>
                                    <option value="">Önce Bina Seçiniz</option>
                                </select>
                                <input type="hidden" name="ic_kapi_no" id="konutIcKapiText">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Adres Kodu (UAVT)</label><input type="text" name="adres_kodu" id="konutAdresKodu" placeholder="Otomatik gelecektir" readonly></div>
                        </div>

                        <!-- Bina Bilgileri -->
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Konut Tipi</label>
                                <select name="konut_tipi" required>
                                    <option value="">Seçiniz</option>
                                    <option>Apartman Dairesi</option>
                                    <option>Müstakil Ev</option>
                                    <option>Villa</option>
                                    <option>Rezidans</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Yapı Tarzı</label>
                                <select name="yapi_tarzi" required>
                                    <option value="">Seçiniz</option>
                                    <option value="Çelik/Betonarme">Çelik / Betonarme</option>
                                    <option value="Yığma Kagir">Yığma Kagir</option>
                                    <option value="Diğer">Diğer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Brüt m²</label><input type="number" name="brut_metrekare" placeholder="Örn: 120" required></div>
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Toplam Kat Sayısı</label>
                                <select name="bina_kat_sayisi" required>
                                    <option value="">Seçiniz</option>
                                    <option value="01-03 arası">01 - 03 Arası</option>
                                    <option value="04-07 arası">04 - 07 Arası</option>
                                    <option value="08-18 arası">08 - 18 Arası</option>
                                    <option value="19 ve üzeri">19 ve Üzeri</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Sigorta Sıfatı</label>
                                <select name="sigorta_sifati" required>
                                    <option value="">Seçiniz</option>
                                    <option value="Mal Sahibi">Mal Sahibi</option>
                                    <option value="Kiracı">Kiracı</option>
                                    <option value="İntifa Hakkı Sahibi">İntifa Hakkı Sahibi</option>
                                    <option value="Yönetici">Yönetici</option>
                                    <option value="Diğer">Diğer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Kullanım Şekli</label>
                                <select name="kullanim_sekli" required>
                                    <option value="">Seçiniz</option>
                                    <option value="Mesken">Mesken</option>
                                    <option value="Ticarethane">Ticarethane</option>
                                </select>
                            </div>
                        </div>

                        <!-- Değer Bilgileri -->
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Bina Değeri (₺)</label><input type="text" name="bina_degeri" class="money-input" placeholder="500.000" inputmode="numeric"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Eşya Değeri (₺)</label><input type="text" name="esya_degeri" class="money-input" placeholder="200.000" inputmode="numeric"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Cam Değeri (₺)</label><input type="text" name="cam_degeri" class="money-input" placeholder="10.000" inputmode="numeric"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Demirbaşlar (₺)</label><input type="text" name="demirbas_degeri" class="money-input" placeholder="50.000" inputmode="numeric"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-page"><label>3. Şahıs Soruml. (₺)</label><input type="text" name="ucuncu_sahis" class="money-input" placeholder="100.000" inputmode="numeric"></div>
                        </div>

                        <!-- Banka Şerhi -->
                        <div class="col-md-3">
                            <div class="form-group-page"><label>Banka Şerhi</label>
                                <select name="banka_serhi" id="konutBankaSerhi" required>
                                    <option value="">Seçiniz</option>
                                    <option value="Hayır">Hayır</option>
                                    <option value="Evet">Evet</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" id="konutBankaAdiCol" style="display:none;">
                            <div class="form-group-page"><label>Banka Adı</label><input type="text" name="banka_adi" id="konutBankaAdi" placeholder="Banka adını yazınız"></div>
                        </div>
                        <div class="col-md-3" id="konutSubeAdiCol" style="display:none;">
                            <div class="form-group-page"><label>Şube Adı</label><input type="text" name="sube_adi" id="konutSubeAdi" placeholder="Şube adını yazınız"></div>
                        </div>

                        <div class="col-12"><button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-3"><i class="fa-solid fa-bolt"></i> Teklif Al</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper">
            <h2>Konut Sigortası Nedir?</h2>
            <p>Konut sigortası, evinizi ve içindeki değerli eşyalarınızı çeşitli risklere karşı koruma altına alan kapsamlı bir sigorta ürünüdür. DASK'ın aksine konut sigortası isteğe bağlıdır ancak evinizin tam koruma altında olması için kesinlikle önerilir.</p>
            
            <p>Konut sigortası; yangın, hırsızlık, su baskını, fırtına, dolu, cam kırılması, elektrik hasarı ve daha birçok riski kapsar. Hem binanın yapısını hem de içindeki eşyaları güvence altına alır.</p>

            <h2>Konut Sigortası Teminatları</h2>
            <table class="coverage-table">
                <thead><tr><th>Teminat</th><th>Kapsam</th></tr></thead>
                <tbody>
                    <tr><td><strong>Yangın</strong></td><td>Her türlü yangın, yıldırım ve infilak hasarları</td></tr>
                    <tr><td><strong>Hırsızlık</strong></td><td>Ev eşyalarının çalınması veya zarar görmesi</td></tr>
                    <tr><td><strong>Su Hasarı</strong></td><td>Dahili su, patlak boru, tesisat arızası</td></tr>
                    <tr><td><strong>Doğal Afet</strong></td><td>Fırtına, dolu, sel-su baskını</td></tr>
                    <tr><td><strong>Cam Kırılması</strong></td><td>Pencere, kapı ve ayna camları</td></tr>
                    <tr><td><strong>Elektronik Cihaz</strong></td><td>Elektrik dalgalanmasından kaynaklanan hasarlar</td></tr>
                    <tr><td><strong>Sorumluluk</strong></td><td>Komşulara verilen zarar (su, yangın vb.)</td></tr>
                    <tr><td><strong>Kira Kaybı</strong></td><td>Hasar nedeniyle oturulamayan süre kira bedeli</td></tr>
                    <tr><td><strong>Enkaz Kaldırma</strong></td><td>Hasar sonrası enkaz kaldırma masrafları</td></tr>
                </tbody>
            </table>

            <h2>Konut Sigortası ile DASK Farkı</h2>
            <p>Konut sigortası DASK'tan çok daha geniş kapsamlıdır. DASK sadece deprem hasarlarını karşılarken, konut sigortası yangın, hırsızlık, su hasarı dahil pek çok riski kapsar. İdeal çözüm her ikisinin birlikte alınmasıdır.</p>

            <h2>Konut Sigortası Fiyatını Etkileyen Faktörler</h2>
            <ul>
                <li><strong>Konut değeri:</strong> Evinizin ve eşyalarınızın toplam değeri</li>
                <li><strong>Konum:</strong> İl, ilçe ve mahalle bazlı risk değerlendirmesi</li>
                <li><strong>Bina yaşı:</strong> Eski binalarda prim daha yüksek olabilir</li>
                <li><strong>Güvenlik önlemleri:</strong> Alarm, çelik kapı gibi önlemler indirim sağlar</li>
                <li><strong>Seçilen teminatlar:</strong> Ek teminatlar primi artırabilir</li>
            </ul>

            <div class="info-box success">
                <p><strong>Tasarruf:</strong> Konut sigortanızı DASK ile birlikte Emre Sigorta'dan alarak ek indirimlerden yararlanabilirsiniz.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Evinizi Güvence Altına Alın!</h2>
            <p>Kapsamlı konut sigortası tekliflerini karşılaştırın, eviniz güvende olsun.</p>
            <a href="#" class="btn-cta" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"><i class="fa-solid fa-bolt"></i> Teklif Al</a>
        </div>
    </div>
</section>

<!-- Banka Şerhi Toggle + Para Formatı -->
<script>
document.getElementById('konutBankaSerhi').addEventListener('change', function() {
    var show = this.value === 'Evet';
    document.getElementById('konutBankaAdiCol').style.display = show ? '' : 'none';
    document.getElementById('konutSubeAdiCol').style.display = show ? '' : 'none';
    if (!show) {
        document.getElementById('konutBankaAdi').value = '';
        document.getElementById('konutSubeAdi').value = '';
    }
});
// Para formatı: 500000 → 500.000
document.querySelectorAll('.money-input').forEach(function(el) {
    el.addEventListener('input', function() {
        var v = this.value.replace(/\D/g, '');
        if (v) {
            this.value = Number(v).toLocaleString('tr-TR');
        }
    });
});
</script>

<!-- Konut Cascading Dropdown JS (DASK API) -->
<script>
(function() {
    'use strict';
    var siteUrl = document.querySelector('meta[name="site-url"]');
    if (!siteUrl) return;
    siteUrl = siteUrl.content;

    var ilSelect = document.getElementById('konutIl');
    var ilceSelect = document.getElementById('konutIlce');
    var bucakSelect = document.getElementById('konutBucak');
    var mahalleSelect = document.getElementById('konutMahalle');
    var caddeSelect = document.getElementById('konutCadde');
    var binaSelect = document.getElementById('konutBina');
    var icKapiSelect = document.getElementById('konutIcKapi');
    var adresKodu = document.getElementById('konutAdresKodu');

    var ilText = document.getElementById('konutIlText');
    var ilceText = document.getElementById('konutIlceText');
    var bucakText = document.getElementById('konutBucakText');
    var mahalleText = document.getElementById('konutMahalleText');
    var caddeText = document.getElementById('konutCaddeText');
    var binaText = document.getElementById('konutBinaText');
    var icKapiText = document.getElementById('konutIcKapiText');

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

    fetch(API + '?step=init').then(function(r) { return r.json(); }).then(function() {
        return fetch(API + '?step=iller');
    }).then(function(r) { return r.json(); }).then(function(data) {
        if (data.error) return;
        fillSelect(ilSelect, data, 'ad', 'id', 'İl Seçiniz');
    });

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
                                if (data2.error || data2.length === 0) { resetSelect(binaSelect, 'Bina bulunamadı'); return; }
                                fillSelect(binaSelect, data2, 'ad', 'id', 'Bina Seçiniz (' + data2.length + ' adet)');
                                if (data2.length === 1) { binaSelect.value = data2[0].id; binaSelect.dispatchEvent(new Event('change')); }
                            });
                    } else { resetSelect(binaSelect, 'Bina yüklenemedi'); }
                    return;
                }
                fillSelect(binaSelect, data, 'ad', 'id', 'Bina Seçiniz (' + data.length + ' adet)');
            });
    });

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
                if (data.length === 1) { icKapiSelect.value = data[0].adres_kodu; icKapiSelect.dispatchEvent(new Event('change')); }
            });
    });

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

<?php include 'includes/footer.php'; ?>
