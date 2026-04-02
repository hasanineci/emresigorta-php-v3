<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Poliçe İptal İşlemleri | Sigorta İptal ve Cayma Hakkı';
$pageDescription = 'Sigorta poliçesi iptal işlemlerinizi Emre Sigorta üzerinden kolayca gerçekleştirin. Cayma hakkı, iade süreci ve poliçe iptal koşulları hakkında detaylı bilgi alın.';
$pageKeywords = 'poliçe iptali, sigorta iptali, poliçe iptal işlemleri, sigorta iptal başvurusu, cayma hakkı, sigorta iade, emre sigorta poliçe iptal';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Poliçe İptal']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="page-banner">
    <div class="container">
        <h1>Poliçe İptal İşlemleri</h1>
        <p>Aktif poliçenizi kolayca iptal edebilir veya cayma hakkınızı kullanabilirsiniz.</p>
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Ana Sayfa</a>
            <span>/</span>
            <span>Poliçe İptal</span>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="content-wrapper" style="max-width: 900px; margin: 0 auto;">

            <!-- Uyarı Bilgi -->
            <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 12px; padding: 20px; margin-bottom: 30px; display: flex; gap: 15px; align-items: flex-start;">
                <i class="fas fa-exclamation-triangle" style="color: #e0a800; font-size: 24px; margin-top: 3px;"></i>
                <div>
                    <strong style="color: #856404;">Önemli Bilgilendirme</strong>
                    <p style="color: #856404; margin: 5px 0 0; font-size: 14px;">Poliçe iptal işlemi geri alınamaz. İptal işlemi sonrası sigorta teminatınız sona erecektir. Lütfen işlem öncesinde cayma hakkı koşullarını dikkatlice okuyunuz.</p>
                </div>
            </div>

            <h2>Poliçe İptal Yöntemleri</h2>
            <p>Emre Sigorta üzerinden satın aldığınız poliçelerinizi aşağıdaki yöntemlerle iptal edebilirsiniz:</p>

            <!-- İptal Yöntemleri -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 30px 0;">
                <div style="background: #f8f9fa; border-radius: 12px; padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 24px;">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h4>Online İptal</h4>
                    <p style="font-size: 14px; color: var(--text-light);">Aşağıdaki formu doldurarak online iptal talebinde bulunabilirsiniz.</p>
                </div>
                <div style="background: #f8f9fa; border-radius: 12px; padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 24px;">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h4>Telefonla İptal</h4>
                    <p style="font-size: 14px; color: var(--text-light);"><?php echo SITE_PHONE; ?> numaralı hattımızı arayarak iptal talebinizi iletebilirsiniz.</p>
                </div>
                <div style="background: #f8f9fa; border-radius: 12px; padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 24px;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>E-posta ile İptal</h4>
                    <p style="font-size: 14px; color: var(--text-light);"><?php echo SITE_EMAIL; ?> adresine iptal talebinizi e-posta olarak gönderebilirsiniz.</p>
                </div>
            </div>

            <!-- İptal Formu -->
            <h2 id="iptal-formu">Online Poliçe İptal Formu</h2>
            <p>Aşağıdaki formu eksiksiz doldurarak poliçe iptal talebinizi oluşturabilirsiniz. Talebiniz en geç 2 iş günü içinde değerlendirilecektir.</p>

            <form class="contact-form" data-form-type="police-iptal" style="background: #f8f9fa; padding: 35px; border-radius: 16px; margin: 25px 0;" enctype="multipart/form-data">
                <input type="hidden" name="form_type" value="police-iptal">
                <div class="form-row">
                    <div class="form-group">
                        <label>Ad Soyad *</label>
                        <input type="text" name="ad_soyad" placeholder="Adınız ve soyadınız" required>
                    </div>
                    <div class="form-group">
                        <label>TC Kimlik No / Vergi No *</label>
                        <input type="text" name="tc_vergi_no" placeholder="TC kimlik veya vergi numaranız" required maxlength="11">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Cep Telefonu *</label>
                        <input type="tel" name="telefon" placeholder="05XX XXX XX XX" required>
                    </div>
                    <div class="form-group">
                        <label>Doğum Tarihi *</label>
                        <input type="date" name="dogum_tarihi" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>E-posta Adresi *</label>
                        <input type="email" name="email" placeholder="ornek@email.com" required>
                    </div>
                    <div class="form-group">
                        <label>Poliçe Numarası *</label>
                        <input type="text" name="police_no" placeholder="Poliçe numaranızı girin" required>
                    </div>
                    <div class="form-group">
                        <label>Poliçe Türü *</label>
                        <select name="police_turu" required>
                            <option value="">Seçiniz</option>
                            <option>Trafik Sigortası</option>
                            <option>Kasko</option>
                            <option>Tamamlayıcı Sağlık Sigortası</option>
                            <option>Özel Sağlık Sigortası</option>
                            <option>DASK</option>
                            <option>Konut Sigortası</option>
                            <option>Seyahat Sağlık Sigortası</option>
                            <option>İMM</option>
                            <option>Diğer</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>İptal Sebebi *</label>
                    <select name="iptal_sebebi" required>
                        <option value="">Seçiniz</option>
                        <option>Araç satışı</option>
                        <option>Başka bir şirketten poliçe yaptırdım</option>
                        <option>Fiyat yüksekliği</option>
                        <option>Cayma hakkımı kullanmak istiyorum</option>
                        <option>Poliçe yenilenmesini istemiyorum</option>
                        <option>Diğer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Satış Senedi</label>
                    <div id="uploadArea" style="border: 2px dashed var(--gray-300); border-radius: 12px; padding: 30px; text-align: center; background: #fff; cursor: pointer; transition: all 0.3s ease;" onclick="document.getElementById('satisSenedi').click()" onmouseover="this.style.borderColor='var(--primary)';this.style.background='var(--primary-light)'" onmouseout="this.style.borderColor='var(--gray-300)';this.style.background='#fff'">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: var(--primary); margin-bottom: 12px; display: block;"></i>
                        <p style="margin: 0 0 5px; font-weight: 600; color: var(--dark); font-size: 15px;">Satış senedini yüklemek için tıklayın</p>
                        <p style="margin: 0; font-size: 13px; color: var(--gray-500);">PDF, JPG veya PNG • Maks. 5MB</p>
                        <input type="file" id="satisSenedi" name="satis_senedi" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" onchange="if(this.files[0]){document.getElementById('satisSenediName').innerHTML='<i class=\'fas fa-file-check\' style=\'margin-right:6px\'></i>'+this.files[0].name}">
                        <span id="satisSenediName" style="display: block; margin-top: 10px; font-size: 14px; color: var(--primary); font-weight: 600;"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Açıklama</label>
                    <textarea name="aciklama" rows="4" placeholder="İptal sebebiniz hakkında ek bilgi vermek isterseniz yazabilirsiniz..."></textarea>
                </div>
                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 14px; font-weight: 400;">
                        <input type="checkbox" required style="margin-top: 3px; min-width: 18px; min-height: 18px;">
                        <span>Poliçe iptal koşullarını okudum ve kabul ediyorum. İptal işleminin ardından sigorta teminatımın sona ereceğini biliyorum.</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; border-radius: var(--radius);">
                    <i class="fas fa-paper-plane"></i> İptal Talebini Gönder
                </button>
            </form>

            <!-- Cayma Hakkı -->
            <h2>Cayma Hakkı Nedir?</h2>
            <p>6102 sayılı Türk Ticaret Kanunu ve ilgili mevzuat gereği, sigorta poliçenizi satın aldıktan sonra belirli süreler içinde cayma hakkınızı kullanabilirsiniz.</p>
            
            <div style="background: #e8f4fd; border-radius: 12px; padding: 25px; margin: 20px 0;">
                <h4 style="margin-bottom: 15px;"><i class="fas fa-info-circle" style="color: var(--primary);"></i> Cayma Hakkı Süreleri</h4>
                <ul style="line-height: 2;">
                    <li><strong>Hayat Sigortaları:</strong> Poliçe teslim tarihinden itibaren 30 gün</li>
                    <li><strong>Hayat Dışı Sigortalar:</strong> Poliçe teslim tarihinden itibaren 14 gün (mesafeli satış)</li>
                    <li><strong>Zorunlu Sigortalar:</strong> Trafik sigortası gibi zorunlu sigortalarda cayma hakkı bulunmamaktadır</li>
                </ul>
            </div>

            <h2>İptal ve İade Koşulları</h2>
            <ul>
                <li><strong>Cayma hakkı süresi içinde iptal:</strong> Ödenen primin tamamı 10 iş günü içinde iade edilir.</li>
                <li><strong>Cayma hakkı süresi dışında iptal:</strong> Kısa dönem tarifesine göre hesaplanan kullanılmamış prim tutarı iade edilir.</li>
                <li><strong>Hasar ödemesi yapılmış poliçe:</strong> Hasar ödemesi yapılmış poliçelerde iade tutarı sigorta şirketi tarafından belirlenir.</li>
                <li><strong>Zorunlu sigortalar:</strong> Trafik sigortası iptali için araç satışı veya trafikten çekme belgesi gereklidir.</li>
            </ul>

            <h2>İade Süreci</h2>
            <p>İptal talebiniz onaylandıktan sonra iade süreci aşağıdaki şekilde işler:</p>
            <ol>
                <li style="padding: 8px 0;">İptal talebiniz 1-2 iş günü içinde değerlendirilir.</li>
                <li style="padding: 8px 0;">Talep onaylanırsa iptal işleminiz gerçekleştirilir.</li>
                <li style="padding: 8px 0;">İade tutarınız hesaplanır ve tarafınıza bilgi verilir.</li>
                <li style="padding: 8px 0;">İade tutarı, ödeme yaptığınız yönteme göre 5-10 iş günü içinde hesabınıza aktarılır.</li>
            </ol>

            <h2>Sıkça Sorulan Sorular</h2>

            <div class="faq-section" style="margin: 20px 0;">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Trafik sigortamı iptal edebilir miyim?</span>
                    </div>
                    <div class="faq-answer">
                        <p>Trafik sigortası zorunlu bir sigorta olduğundan, sadece aracın satış, devir veya hurdaya ayrılması durumunda iptal edilebilir. Araç satışı halinde yeni sahibine devredilebilir veya iptal işlemi yapılarak bakiye prim iade alınabilir.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Poliçe iptali ne kadar sürer?</span>
                    </div>
                    <div class="faq-answer">
                        <p>İptal talebiniz en geç 2 iş günü içinde değerlendirilir ve sonuçlandırılır. İade işlemi ise iptal onayından sonra 5-10 iş günü sürebilir.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>İptal edilen poliçenin iadesi nasıl yapılır?</span>
                    </div>
                    <div class="faq-answer">
                        <p>İade tutarı, poliçenizi satın alırken kullandığınız ödeme yöntemine göre iade edilir. Kredi kartı ile yapılan ödemelerde iade tutarı kartınıza, havale/EFT ile yapılan ödemelerde ise belirttiğiniz banka hesabınıza aktarılır.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Online iptal formu güvenli mi?</span>
                    </div>
                    <div class="faq-answer">
                        <p>Evet, formda paylaştığınız tüm bilgiler 256-bit SSL şifreleme ile korunmaktadır. Kişisel verileriniz KVKK kapsamında güvence altındadır ve sadece iptal işleminiz için kullanılır.</p>
                    </div>
                </div>
            </div>

            <!-- İletişim -->
            <div style="background: var(--primary); color: #fff; border-radius: 16px; padding: 40px; text-align: center; margin-top: 40px;">
                <h3 style="color: #fff; margin-bottom: 10px;">Yardıma mı İhtiyacınız Var?</h3>
                <p style="color: rgba(255,255,255,0.85); margin-bottom: 20px;">Poliçe iptal işlemleriniz hakkında detaylı bilgi almak için bizi arayabilirsiniz.</p>
                <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="btn btn-light" style="font-size: 18px;">
                    <i class="fas fa-phone-alt"></i> <?php echo SITE_PHONE; ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
