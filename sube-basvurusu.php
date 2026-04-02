<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Sigorta Acentelik Başvurusu | Şube Açın';
$pageDescription = 'Emre Sigorta acentelik başvurusu yapın. Kendi bölgenizde sigorta acentesi olarak faaliyet gösterin. Kazançlı bir iş modeli, eğitim ve destek programı.';
$pageKeywords = 'sigorta acentelik başvurusu, emre sigorta acente, sigorta şube başvurusu, sigorta acentesi olmak, sigorta bayiliği, şanlıurfa sigorta acente';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Şube Başvurusu']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>Şube Başvurusu</h1>
        <p>Emre Sigorta ailesine katılın! Kendi bölgenizde sigorta acentesi olarak faaliyet gösterin.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>Şube Başvurusu</span>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper" style="max-width: 1000px; margin: 0 auto;">

            <!-- Giriş -->
            <div style="text-align: center; margin-bottom: 50px;">
                <h2>Neden Emre Sigorta Acentesi Olmalısınız?</h2>
                <p style="color: var(--text-light); max-width: 700px; margin: 0 auto;">Emre Sigorta'nın güçlü markası ve teknoloji altyapısıyla kendi işinizi kurun. 30+ sigorta şirketi, geniş ürün yelpazesi ve sürekli destek ile gelirlerinizi artırın.</p>
            </div>

            <!-- Avantajlar -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px; margin-bottom: 50px;">
                <div style="background: #f8f9fa; border-radius: 16px; padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #0066cc, #004499); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 24px;">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4>Güçlü Marka</h4>
                    <p style="font-size: 14px; color: var(--text-light);">Emre Sigorta'nın tanınmış markası altında faaliyet gösterin.</p>
                </div>
                <div style="background: #f8f9fa; border-radius: 16px; padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #28a745, #1e7e34); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 24px;">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h4>Teknoloji Desteği</h4>
                    <p style="font-size: 14px; color: var(--text-light);">Gelişmiş dijital altyapı ve online satış araçları.</p>
                </div>
                <div style="background: #f8f9fa; border-radius: 16px; padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff6600, #cc5200); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 24px;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4>Eğitim Programları</h4>
                    <p style="font-size: 14px; color: var(--text-light);">Sürekli mesleki gelişim ve satış eğitimleri.</p>
                </div>
                <div style="background: #f8f9fa; border-radius: 16px; padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #6f42c1, #5a32a3); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 24px;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Yüksek Komisyon</h4>
                    <p style="font-size: 14px; color: var(--text-light);">Sektörün en rekabetçi komisyon oranları.</p>
                </div>
            </div>

            <!-- Başvuru Koşulları -->
            <h2>Başvuru Koşulları</h2>
            <ul>
                <li>T.C. vatandaşı olmak veya Türkiye'de çalışma izni bulunmak</li>
                <li>En az lise mezunu olmak</li>
                <li>Sigorta acenteliği sertifikasına sahip olmak veya tamamlamayı taahhüt etmek</li>
                <li>SBM (Sigorta Bilgi ve Gözetim Merkezi) levha kaydı taahhüdü</li>
                <li>En az 50 m² ofis alanı sağlayabilmek</li>
                <li>Adli sicil kaydı temiz olmak</li>
                <li>Tabela, dekorasyon ve kurumsal kimlik standartlarına uyum</li>
            </ul>

            <!-- Başvuru Formu -->
            <h2 id="basvuru-formu" style="margin-top: 40px;">Başvuru Formu</h2>
            <p>Aşağıdaki formu doldurarak şube başvurunuzu yapabilirsiniz. Başvurunuz değerlendirildikten sonra sizinle iletişime geçeceğiz.</p>

            <form class="contact-form" data-form-type="sube-basvurusu" style="background: #f8f9fa; padding: 35px; border-radius: 16px; margin: 25px 0;">
                <input type="hidden" name="form_type" value="sube-basvurusu">
                <h3 style="margin-bottom: 20px; font-size: 18px;"><i class="fas fa-user" style="color: var(--primary); margin-right: 8px;"></i>Kişisel Bilgiler</h3>
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Ad Soyad *</label>
                        <input type="text" name="ad_soyad" placeholder="Adınız ve soyadınız" required>
                    </div>
                    <div class="form-group">
                        <label>TC Kimlik No *</label>
                        <input type="text" name="tc_kimlik" placeholder="11 haneli TC kimlik numaranız" required maxlength="11">
                    </div>
                </div>
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Doğum Tarihi *</label>
                        <input type="date" name="dogum_tarihi" required>
                    </div>
                    <div class="form-group">
                        <label>Eğitim Durumu *</label>
                        <select name="egitim" required>
                            <option value="">Seçiniz</option>
                            <option>Lise</option>
                            <option>Ön Lisans</option>
                            <option>Lisans</option>
                            <option>Yüksek Lisans</option>
                            <option>Doktora</option>
                        </select>
                    </div>
                </div>
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Cep Telefonu *</label>
                        <input type="tel" name="cep_telefonu" placeholder="05XX XXX XX XX" required>
                    </div>
                    <div class="form-group">
                        <label>E-posta Adresi *</label>
                        <input type="email" name="email" placeholder="ornek@email.com" required>
                    </div>
                </div>

                <h3 style="margin: 30px 0 20px; font-size: 18px;"><i class="fas fa-building" style="color: var(--primary); margin-right: 8px;"></i>İş Bilgileri</h3>
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Başvuru İli *</label>
                        <select name="il" required>
                            <option value="">İl seçiniz</option>
                            <option>İstanbul</option>
                            <option>Ankara</option>
                            <option>İzmir</option>
                            <option>Antalya</option>
                            <option>Bursa</option>
                            <option>Adana</option>
                            <option>Konya</option>
                            <option>Gaziantep</option>
                            <option>Kocaeli</option>
                            <option>Mersin</option>
                            <option>Diğer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Başvuru İlçesi *</label>
                        <input type="text" name="ilce" placeholder="İlçe adını yazın" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Sigorta Sektöründe Deneyiminiz Var mı? *</label>
                    <select name="deneyim" required>
                        <option value="">Seçiniz</option>
                        <option>Hayır, deneyimim yok</option>
                        <option>Evet, 1-3 yıl</option>
                        <option>Evet, 3-5 yıl</option>
                        <option>Evet, 5-10 yıl</option>
                        <option>Evet, 10 yıldan fazla</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Mevcut Ofisiniz Var mı?</label>
                    <select name="ofis">
                        <option value="">Seçiniz</option>
                        <option>Evet, sigorta ofisim var</option>
                        <option>Evet, farklı sektörde ofisim var</option>
                        <option>Hayır, kiralayacağım</option>
                        <option>Uygun mekan araştırıyorum</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Motivasyon Mektubunuz</label>
                    <textarea name="motivasyon" rows="5" placeholder="Neden Emre Sigorta acentesi olmak istediğinizi, sektörle ilgili tecrübelerinizi ve hedeflerinizi kısaca anlatınız..."></textarea>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 14px;">
                        <input type="checkbox" name="kvkk" value="evet" required style="margin-top: 3px;">
                        <span>Kişisel verilerimin <a href="kvkk.php" style="color: var(--primary);">KVKK Aydınlatma Metni</a> kapsamında işlenmesini kabul ediyorum.</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px;">
                    <i class="fas fa-paper-plane"></i> Başvurumu Gönder
                </button>
            </form>

            <!-- Süreç -->
            <h2>Başvuru Süreci</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 25px 0;">
                <div style="text-align: center; padding: 20px;">
                    <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 20px; font-weight: 700;">1</div>
                    <h4 style="font-size: 15px;">Form Doldurun</h4>
                    <p style="font-size: 13px; color: var(--text-light);">Online başvuru formunu eksiksiz doldurun.</p>
                </div>
                <div style="text-align: center; padding: 20px;">
                    <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 20px; font-weight: 700;">2</div>
                    <h4 style="font-size: 15px;">Değerlendirme</h4>
                    <p style="font-size: 13px; color: var(--text-light);">Başvurunuz ekibimiz tarafından değerlendirilir.</p>
                </div>
                <div style="text-align: center; padding: 20px;">
                    <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 20px; font-weight: 700;">3</div>
                    <h4 style="font-size: 15px;">Görüşme</h4>
                    <p style="font-size: 13px; color: var(--text-light);">Uygun adaylarla yüz yüze görüşme yapılır.</p>
                </div>
                <div style="text-align: center; padding: 20px;">
                    <div style="width: 50px; height: 50px; background: var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 20px; font-weight: 700;">4</div>
                    <h4 style="font-size: 15px;">Şube Açılışı</h4>
                    <p style="font-size: 13px; color: var(--text-light);">Anlaşma sonrası şubenizi açın!</p>
                </div>
            </div>

            <!-- İletişim -->
            <div style="background: var(--primary); color: #fff; border-radius: 16px; padding: 40px; text-align: center; margin-top: 40px;">
                <h3 style="color: #fff; margin-bottom: 10px;">Sorularınız mı Var?</h3>
                <p style="color: rgba(255,255,255,0.85); margin-bottom: 20px;">Şube başvurusu hakkında detaylı bilgi almak için bizimle iletişime geçin.</p>
                <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                    <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="btn btn-light">
                        <i class="fas fa-phone-alt"></i> <?php echo SITE_PHONE; ?>
                    </a>
                    <a href="iletisim.php" class="btn" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3);">Bize Yazın</a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
