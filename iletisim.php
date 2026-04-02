<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'İletişim | Şanlıurfa Emre Sigorta';
$pageDescription = 'Emre Sigorta ile iletişime geçin. Şanlıurfa Haliliye ofisimiz, telefon: 0541 514 85 15, e-posta: info@emresigorta.net. Hafta içi 09:00-18:00 hizmetinizdeyiz.';
$pageKeywords = 'emre sigorta iletişim, şanlıurfa sigorta acentesi iletişim, emre sigorta telefon, emre sigorta adres, sigorta danışma hattı, haliliye sigorta';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'İletişim']
]) . '</script>';
$pageSchema .= '
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ContactPage",
    "name": "Emre Sigorta İletişim",
    "description": "Emre Sigorta ile iletişime geçin.",
    "url": "https://' . SITE_DOMAIN . '/iletisim.php"
}
</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>İletişim</h1>
        <p>Bizimle iletişime geçin. Tüm soru ve talepleriniz için buradayız.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>İletişim</span>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">

        <!-- İletişim Kartları -->
        <div class="contact-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 50px;">
            <div style="background: #fff; border-radius: 16px; padding: 35px; text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eee;">
                <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #0066cc, #004499); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #fff; font-size: 28px;">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h3 style="font-size: 18px; margin-bottom: 10px;">Telefon</h3>
                <p style="color: var(--text-light); font-size: 14px; margin-bottom: 8px;">Hafta içi 09:00 - 18:00</p>
                <a href="tel:<?php echo SITE_PHONE_RAW; ?>" style="color: var(--primary); font-weight: 700; font-size: 18px; text-decoration: none;"><?php echo SITE_PHONE; ?></a>
                <br>
                <a href="tel:4442400" style="color: var(--primary); font-weight: 700; font-size: 18px; text-decoration: none;"><?php echo SITE_PHONE_ALT; ?></a>
            </div>

            <div style="background: #fff; border-radius: 16px; padding: 35px; text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eee;">
                <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #28a745, #1e7e34); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #fff; font-size: 28px;">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 style="font-size: 18px; margin-bottom: 10px;">E-posta</h3>
                <p style="color: var(--text-light); font-size: 14px; margin-bottom: 8px;">7/24 yazabilirsiniz</p>
                <a href="mailto:<?php echo SITE_EMAIL; ?>" style="color: var(--primary); font-weight: 600; text-decoration: none;"><?php echo SITE_EMAIL; ?></a>
            </div>

            <div style="background: #fff; border-radius: 16px; padding: 35px; text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eee;">
                <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #e83e8c, #c2185b); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #fff; font-size: 28px;">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 style="font-size: 18px; margin-bottom: 10px;">Adres</h3>
                <p style="color: var(--text-light); font-size: 14px; margin-bottom: 8px;">Genel Merkez</p>
                <p style="color: var(--dark); font-size: 14px; line-height: 1.6;"><?php echo SITE_ADDRESS; ?></p>
            </div>

            <div style="background: #fff; border-radius: 16px; padding: 35px; text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eee;">
                <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #fd7e14, #e36209); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #fff; font-size: 28px;">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 style="font-size: 18px; margin-bottom: 10px;">Canlı Destek</h3>
                <p style="color: var(--text-light); font-size: 14px; margin-bottom: 8px;">Hafta içi 09:00 - 22:00</p>
                <a href="#" style="color: var(--primary); font-weight: 600; text-decoration: none;">Sohbeti Başlat</a>
            </div>
        </div>

        <!-- İletişim Formu + Harita -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 50px; align-items: start;">

            <!-- Form -->
            <div style="background: #fff; border-radius: 16px; padding: 35px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eee;">
                <h2 style="margin-bottom: 5px; font-size: 22px;">Bize Yazın</h2>
                <p style="color: var(--text-light); margin-bottom: 25px; font-size: 14px;">Formu doldurarak bize mesaj gönderebilirsiniz. En kısa sürede size dönüş yapacağız.</p>

                <form class="contact-form" data-form-type="iletisim">
                    <input type="hidden" name="form_type" value="iletisim">
                    <div class="form-group">
                        <label>Ad Soyad *</label>
                        <input type="text" name="ad_soyad" placeholder="Adınız ve soyadınız" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Telefon *</label>
                            <input type="tel" name="telefon" placeholder="05XX XXX XX XX" required>
                        </div>
                        <div class="form-group">
                            <label>E-posta *</label>
                            <input type="email" name="email" placeholder="ornek@email.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Konu *</label>
                        <select name="konu" required>
                            <option value="">Konu seçiniz</option>
                            <option>Teklif almak istiyorum</option>
                            <option>Poliçe hakkında bilgi</option>
                            <option>Hasar bildirimi</option>
                            <option>Poliçe iptal</option>
                            <option>Şikayet</option>
                            <option>Öneri</option>
                            <option>İş ortaklığı</option>
                            <option>Diğer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mesajınız *</label>
                        <textarea name="mesaj" rows="4" placeholder="Mesajınızı buraya yazabilirsiniz..." required></textarea>
                    </div>
                    <div class="form-group" style="margin-bottom: 25px;">
                        <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 14px; font-weight: 400;">
                            <input type="checkbox" name="kvkk" value="evet" required style="margin-top: 3px; min-width: 18px; min-height: 18px;">
                            <span><a href="javascript:void(0)" onclick="document.getElementById('kvkkModal').style.display='flex'" style="color: var(--primary);">KVKK Aydınlatma Metni</a>'ni okudum ve kabul ediyorum.</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px;">
                        <i class="fas fa-paper-plane"></i> Mesajı Gönder
                    </button>
                </form>
            </div>

            <!-- Harita + Bilgiler -->
            <div>
                <div style="border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eee;">
                    <?php if (GOOGLE_MAPS_EMBED): ?>
                    <iframe src="<?php echo htmlspecialchars(GOOGLE_MAPS_EMBED, ENT_QUOTES, 'UTF-8'); ?>" width="100%" height="350" style="border:0; display: block;" allowfullscreen="" loading="eager" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <?php else: ?>
                    <div style="height: 350px; display:flex; align-items:center; justify-content:center; background: #f0f0f0;"><span class="text-muted">Harita yüklenmedi</span></div>
                    <?php endif; ?>
                </div>

                <div style="background: #fff; border-radius: 16px; padding: 25px; margin-top: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eee;">
                    <h3 style="font-size: 16px; margin-bottom: 15px;"><i class="fas fa-clock" style="color: var(--primary); margin-right: 8px;"></i>Çalışma Saatleri</h3>
                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 10px 0; font-weight: 600;">Pazartesi - Cuma</td>
                            <td style="padding: 10px 0; text-align: right; color: var(--text-light);">09:00 - 18:00</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 10px 0; font-weight: 600;">Cumartesi</td>
                            <td style="padding: 10px 0; text-align: right; color: var(--text-light);">10:00 - 14:00</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; font-weight: 600;">Pazar</td>
                            <td style="padding: 10px 0; text-align: right; color: #dc3545; font-weight: 600;">Kapalı</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sosyal Medya -->
        <div style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 16px; padding: 50px; text-align: center;">
            <h2 style="margin-bottom: 10px;">Sosyal Medyada Bizi Takip Edin</h2>
            <p style="color: var(--text-light); margin-bottom: 25px;">Kampanyalardan ve güncel haberlerden ilk siz haberdar olun.</p>
            <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                <?php
                $socialLinks = getAllSocialMedia(true);
                foreach ($socialLinks as $sl):
                    $slUrl = !empty($sl['url']) ? htmlspecialchars($sl['url'], ENT_QUOTES, 'UTF-8') : '#';
                ?>
                <a href="<?php echo $slUrl; ?>" <?php echo $slUrl !== '#' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?> style="width: 50px; height: 50px; background: <?php echo htmlspecialchars($sl['color'], ENT_QUOTES, 'UTF-8'); ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 22px; text-decoration: none; transition: transform 0.2s, opacity 0.2s;" onmouseover="this.style.transform='scale(1.15)'; this.style.opacity='0.85';" onmouseout="this.style.transform='scale(1)'; this.style.opacity='1';" title="<?php echo htmlspecialchars($sl['label'], ENT_QUOTES, 'UTF-8'); ?>"><i class="<?php echo htmlspecialchars($sl['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i></a>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>

<!-- KVKK Modal -->
<div id="kvkkModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:16px; max-width:700px; width:100%; max-height:80vh; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div style="padding:20px 25px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin:0; font-size:18px;"><i class="fas fa-shield-alt" style="color:var(--primary); margin-right:8px;"></i>KVKK Aydınlatma Metni</h3>
            <button onclick="document.getElementById('kvkkModal').style.display='none'" style="background:none; border:none; font-size:24px; cursor:pointer; color:#999; line-height:1;">&times;</button>
        </div>
        <div style="padding:25px; overflow-y:auto; font-size:14px; line-height:1.7; color:#555;">
            <p><strong>1. Veri Sorumlusu</strong></p>
            <p><?php echo SITE_NAME; ?> Sigorta Aracılık Hizmetleri A.Ş. olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") kapsamında kişisel verilerinizin korunmasına azami hassasiyet göstermekteyiz.</p>
            <p><strong>2. İşlenen Kişisel Veriler</strong></p>
            <p>İletişim formu aracılığıyla ad soyad, telefon numarası, e-posta adresi ve mesaj içeriği bilgileriniz işlenmektedir.</p>
            <p><strong>3. İşleme Amaçları</strong></p>
            <p>Kişisel verileriniz; iletişim taleplerinizin yanıtlanması, sigorta hizmetlerine ilişkin bilgilendirme yapılması ve yasal yükümlülüklerin yerine getirilmesi amacıyla işlenmektedir.</p>
            <p><strong>4. Haklarınız</strong></p>
            <p>KVKK'nın 11. maddesi gereği; kişisel verilerinizin işlenip işlenmediğini öğrenme, düzeltilmesini veya silinmesini isteme haklarına sahipsiniz.</p>
        </div>
        <div style="padding:15px 25px; border-top:1px solid #eee; text-align:right;">
            <a href="<?php echo SITE_URL; ?>/kvkk.php" class="btn btn-outline-primary btn-sm" style="margin-right:10px;">
                <i class="fas fa-external-link-alt me-1"></i> Tamamını Oku
            </a>
            <button onclick="document.getElementById('kvkkModal').style.display='none'" class="btn btn-primary btn-sm">Kapat</button>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
