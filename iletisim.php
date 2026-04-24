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
<?php
// Tüm şubeleri al (aktif olanlar)
$branches = getAllBranches(true);
$hqBranch = null;
foreach ($branches as $b) {
    if ($b['is_headquarters']) { $hqBranch = $b; break; }
}
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
        <!-- İletişim Kartları -->
        <div class="contact-cards" style="margin-bottom: 50px;">
            <?php if (SITE_PHONE): ?>
            <div class="ccard ccard-blue">
                <div class="ccard-body">
                    <div class="ccard-info">
                        <span class="ccard-label">Telefon</span>
                        <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="ccard-value"><?php echo SITE_PHONE; ?></a>
                        <?php if (SITE_PHONE_ALT && SITE_PHONE_ALT !== SITE_PHONE): ?>
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', SITE_PHONE_ALT); ?>" class="ccard-sub"><?php echo SITE_PHONE_ALT; ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="ccard-icon"><i class="fas fa-phone-alt"></i></div>
                </div>
                <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="ccard-footer">Hemen Ara <i class="fas fa-arrow-right"></i></a>
            </div>
            <?php endif; ?>

            <?php if (SITE_EMAIL): ?>
            <div class="ccard ccard-green">
                <div class="ccard-body">
                    <div class="ccard-info">
                        <span class="ccard-label">E-posta</span>
                        <a href="mailto:<?php echo SITE_EMAIL; ?>" class="ccard-value"><?php echo SITE_EMAIL; ?></a>
                        <?php if (SITE_EMAIL_ALT && SITE_EMAIL_ALT !== SITE_EMAIL): ?>
                        <a href="mailto:<?php echo SITE_EMAIL_ALT; ?>" class="ccard-sub"><?php echo SITE_EMAIL_ALT; ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="ccard-icon"><i class="fas fa-envelope"></i></div>
                </div>
                <a href="mailto:<?php echo SITE_EMAIL; ?>" class="ccard-footer">E-posta Gönder <i class="fas fa-arrow-right"></i></a>
            </div>
            <?php endif; ?>

            <div class="ccard ccard-orange">
                <div class="ccard-body">
                    <div class="ccard-info">
                        <span class="ccard-label">WhatsApp</span>
                        <span class="ccard-value" style="font-size: 17px;">Hızlı İletişim</span>
                        <span class="ccard-sub">7/24 yazabilirsiniz</span>
                    </div>
                    <div class="ccard-icon"><i class="fab fa-whatsapp"></i></div>
                </div>
                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', SITE_PHONE_RAW); ?>?text=<?php echo urlencode(WHATSAPP_MESSAGE); ?>" target="_blank" rel="noopener" class="ccard-footer">Mesaj Gönder <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- İletişim Formu + Genel Merkez Haritası -->
        <div class="contact-form-map-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 60px; align-items: stretch;">

            <!-- Form -->
            <div style="background: #fff; border-radius: 18px; padding: 36px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.05); display: flex; flex-direction: column;">
                <h2 style="margin-bottom: 5px; font-size: 22px;">Bize Yazın</h2>
                <p style="color: var(--text-light); margin-bottom: 25px; font-size: 14px;">Formu doldurarak bize mesaj gönderebilirsiniz. En kısa sürede size dönüş yapacağız.</p>

                <form class="contact-form" data-form-type="iletisim" style="flex: 1; display: flex; flex-direction: column;">
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
                    <div class="form-group" style="flex: 1;">
                        <label>Mesajınız *</label>
                        <textarea name="mesaj" rows="4" placeholder="Mesajınızı buraya yazabilirsiniz..." required style="height: 100%; min-height: 120px;"></textarea>
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

            <!-- Genel Merkez Haritası -->
            <div style="border-radius: 18px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.05); min-height: 400px;">
                <?php if ($hqBranch && !empty($hqBranch['maps_embed'])): ?>
                <iframe src="<?php echo htmlspecialchars($hqBranch['maps_embed'], ENT_QUOTES, 'UTF-8'); ?>" width="100%" height="100%" style="border:0; display: block; min-height: 400px;" allowfullscreen="" loading="eager" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php elseif (GOOGLE_MAPS_EMBED): ?>
                <iframe src="<?php echo htmlspecialchars(GOOGLE_MAPS_EMBED, ENT_QUOTES, 'UTF-8'); ?>" width="100%" height="100%" style="border:0; display: block; min-height: 400px;" allowfullscreen="" loading="eager" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php else: ?>
                <div style="height: 100%; min-height: 400px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                    <div style="text-align: center; color: #aaa;">
                        <i class="fas fa-map-marked-alt" style="font-size: 48px; margin-bottom: 10px;"></i>
                        <p style="margin: 0; font-size: 14px;">Harita bilgisi eklenmemiş</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Şubelerimiz -->
        <?php if (count($branches) > 0): ?>
        <div style="margin-bottom: 60px;">
            <div style="text-align: center; margin-bottom: 45px;">
                <span style="display: inline-block; background: linear-gradient(135deg, #0066cc, #004499); color: #fff; font-size: 11px; font-weight: 700; padding: 6px 18px; border-radius: 20px; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 14px;">OFİSLERİMİZ</span>
                <h2 style="font-size: 32px; margin-bottom: 10px; font-weight: 800; color: var(--dark);">Şubelerimiz</h2>
                <p style="color: var(--text-light); font-size: 15px; max-width: 480px; margin: 0 auto;">Türkiye genelindeki ofislerimiz ile her zaman yanınızdayız.</p>
            </div>

            <?php foreach ($branches as $idx => $branch): ?>
            <?php $isEven = ($idx % 2 === 0); ?>
            <?php $branchColor = $branch['is_headquarters'] ? '#0066cc' : '#6366f1'; ?>
            <div class="branch-card" style="background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 32px rgba(0,0,0,0.06); margin-bottom: 28px; border: 1px solid rgba(0,0,0,0.04); transition: box-shadow 0.3s, transform 0.3s;" onmouseover="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.08), 0 16px 48px rgba(0,0,0,0.1)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.06), 0 8px 32px rgba(0,0,0,0.06)'; this.style.transform='';">
                <div style="display: grid; grid-template-columns: 1fr 1fr; min-height: 340px;" class="branch-row">
                    <!-- Bilgi tarafı -->
                    <div style="padding: 36px 40px; display: flex; flex-direction: column; justify-content: center; order: <?php echo $isEven ? '1' : '2'; ?>;">
                        <!-- Üst: İkon + İsim -->
                        <div style="display: flex; align-items: flex-start; gap: 14px; margin-bottom: 24px;">
                            <div style="width: 44px; height: 44px; background: <?php echo $branchColor; ?>; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 18px; flex-shrink: 0;">
                                <i class="fas fa-<?php echo $branch['is_headquarters'] ? 'building' : 'store'; ?>"></i>
                            </div>
                            <div style="flex: 1;">
                                <h3 style="font-size: 19px; margin: 0 0 4px; font-weight: 700; color: var(--dark); line-height: 1.3;"><?php echo htmlspecialchars($branch['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <?php if ($branch['city']): ?>
                                    <span style="font-size: 13px; color: var(--text-light);"><i class="fas fa-map-pin" style="margin-right: 3px; font-size: 11px;"></i><?php echo htmlspecialchars($branch['city'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php endif; ?>
                                    <?php if ($branch['is_headquarters']): ?>
                                    <span style="background: <?php echo $branchColor; ?>; color: #fff; font-size: 10px; padding: 2px 10px; border-radius: 20px; font-weight: 700; letter-spacing: 0.5px;">MERKEZ</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Detay satırları -->
                        <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                            <?php if ($branch['address']): ?>
                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #ff6b6b, #ee5a24); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 3px 8px rgba(238,90,36,0.25);">
                                    <i class="fas fa-map-marker-alt" style="color: #fff; font-size: 14px;"></i>
                                </div>
                                <span style="font-size: 14px; color: #444; line-height: 1.6; padding-top: 6px;"><?php echo htmlspecialchars($branch['address'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($branch['phone']): ?>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #4facfe, #0066cc); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 3px 8px rgba(0,102,204,0.25);">
                                    <i class="fas fa-phone-alt" style="color: #fff; font-size: 14px;"></i>
                                </div>
                                <span style="font-size: 14px; color: #444;">
                                    <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $branch['phone']); ?>" style="color: #222; text-decoration: none; font-weight: 600;"><?php echo htmlspecialchars($branch['phone'], ENT_QUOTES, 'UTF-8'); ?></a>
                                    <?php if ($branch['phone_alt']): ?>
                                    <span style="color: #bbb; margin: 0 4px;">|</span>
                                    <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $branch['phone_alt']); ?>" style="color: #666; text-decoration: none; font-size: 13px;"><?php echo htmlspecialchars($branch['phone_alt'], ENT_QUOTES, 'UTF-8'); ?></a>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <?php endif; ?>
                            <?php if ($branch['email']): ?>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #43e97b, #38f9d7); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 3px 8px rgba(67,233,123,0.25);">
                                    <i class="fas fa-envelope" style="color: #fff; font-size: 14px;"></i>
                                </div>
                                <a href="mailto:<?php echo htmlspecialchars($branch['email'], ENT_QUOTES, 'UTF-8'); ?>" style="color: #444; text-decoration: none; font-size: 14px;"><?php echo htmlspecialchars($branch['email'], ENT_QUOTES, 'UTF-8'); ?></a>
                            </div>
                            <?php endif; ?>
                            <?php if ($branch['working_hours']): ?>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #a18cd1, #7c3aed); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 3px 8px rgba(124,58,237,0.25);">
                                    <i class="fas fa-clock" style="color: #fff; font-size: 14px;"></i>
                                </div>
                                <span style="font-size: 14px; color: #444;"><?php echo htmlspecialchars($branch['working_hours'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Yol Tarifi Butonu -->
                        <?php if (!empty($branch['maps_link'])): ?>
                        <div>
                            <a href="<?php echo htmlspecialchars($branch['maps_link'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 8px; background: <?php echo $branchColor; ?>; color: #fff; padding: 10px 22px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: opacity 0.2s, transform 0.2s; box-shadow: 0 4px 12px <?php echo $branchColor; ?>33;" onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-1px)';" onmouseout="this.style.opacity='1'; this.style.transform='';">
                                <i class="fas fa-directions"></i> Yol Tarifi Al
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Harita tarafı -->
                    <div style="order: <?php echo $isEven ? '2' : '1'; ?>; min-height: 340px; position: relative;">
                        <?php if (!empty($branch['maps_embed'])): ?>
                        <iframe src="<?php echo htmlspecialchars($branch['maps_embed'], ENT_QUOTES, 'UTF-8'); ?>" width="100%" height="100%" style="border:0; display:block; min-height: 340px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        <?php else: ?>
                        <div style="height: 100%; min-height: 340px; display: flex; align-items: center; justify-content: center; background: linear-gradient(145deg, #f1f3f5, #e4e7eb);">
                            <div style="text-align: center; color: #b0b8c1;">
                                <i class="fas fa-map-marked-alt" style="font-size: 44px; margin-bottom: 12px;"></i>
                                <p style="margin: 0; font-size: 13px; font-weight: 500;">Harita bilgisi eklenmemiş</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Sosyal Medya -->
        <?php $socialLinks = getAllSocialMedia(true); ?>
        <?php if (!empty($socialLinks)): ?>
        <div style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 16px; padding: 50px; text-align: center;">
            <h2 style="margin-bottom: 10px;">Sosyal Medyada Bizi Takip Edin</h2>
            <p style="color: var(--text-light); margin-bottom: 25px;">Kampanyalardan ve güncel haberlerden ilk siz haberdar olun.</p>
            <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                <?php foreach ($socialLinks as $sl):
                    $slUrl = !empty($sl['url']) ? htmlspecialchars($sl['url'], ENT_QUOTES, 'UTF-8') : '#';
                ?>
                <a href="<?php echo $slUrl; ?>" <?php echo $slUrl !== '#' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?> style="width: 50px; height: 50px; background: <?php echo htmlspecialchars($sl['color'], ENT_QUOTES, 'UTF-8'); ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 22px; text-decoration: none; transition: transform 0.2s, opacity 0.2s;" onmouseover="this.style.transform='scale(1.15)'; this.style.opacity='0.85';" onmouseout="this.style.transform='scale(1)'; this.style.opacity='1';" title="<?php echo htmlspecialchars($sl['label'], ENT_QUOTES, 'UTF-8'); ?>"><i class="<?php echo htmlspecialchars($sl['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>

<style>
/* Contact Info Cards */
.contact-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0;
    align-items: stretch;
}
.ccard {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: transform 0.25s, box-shadow 0.25s;
    margin: 0;
    background: #f1f3f5;
    display: flex;
    flex-direction: column;
}
.ccard:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}
.ccard-body {
    padding: 24px 24px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    flex: 1;
    margin: 0;
}
.ccard-body + .ccard-footer {
    margin-top: 0;
    border-top: none;
}
.ccard-info {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.ccard-label {
    font-size: 13px;
    font-weight: 500;
    opacity: 0.85;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.ccard-value {
    font-size: 19px;
    font-weight: 800;
    color: #fff;
    text-decoration: none;
    display: block;
    line-height: 1.3;
    word-break: break-all;
    overflow-wrap: break-word;
}
.ccard-value:hover { opacity: 0.9; }
.ccard-sub {
    font-size: 14px;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    display: block;
    margin-top: 2px;
}
.ccard-sub:hover { color: #fff; }
.ccard-icon {
    font-size: 56px;
    color: rgba(255,255,255,0.2);
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    line-height: 1;
}
.ccard-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 11px;
    font-size: 13px;
    font-weight: 600;
    color: #fff;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}
.ccard-footer:hover { opacity: 0.9; }
.ccard-footer i { font-size: 11px; transition: transform 0.2s; }
.ccard-footer:hover i { transform: translateX(3px); }
/* Card Colors */
.ccard-blue .ccard-body { background: linear-gradient(135deg, #0077cc, #004da8); }
.ccard-blue .ccard-footer { background: #003d7a; }
.ccard-green .ccard-body { background: linear-gradient(135deg, #28a745, #1a7d35); }
.ccard-green .ccard-footer { background: #155d27; }
.ccard-orange .ccard-body { background: linear-gradient(135deg, #fd7e14, #e05a00); }
.ccard-orange .ccard-footer { background: #b84a00; }

@media (max-width: 991px) {
    .contact-form-map-row {
        grid-template-columns: 1fr !important;
    }
    .contact-cards {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 14px !important;
    }
}
@media (max-width: 768px) {
    .contact-cards {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
    }
    .branch-row {
        grid-template-columns: 1fr !important;
    }
    .branch-row > div {
        order: unset !important;
    }
    .branch-row > div:last-child {
        min-height: 220px !important;
    }
}</style>

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
