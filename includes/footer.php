<!-- Footer -->
<footer class="footer bg-dark text-white pt-5 pb-3">
    <div class="container">
        <div class="row g-4 mb-4">
            <!-- Logo & About -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                <a href="<?php echo SITE_URL; ?>/" class="d-inline-block mb-3">
                    <picture>
                        <source srcset="<?php echo SITE_URL; ?>/assets/images/logo/logo-beyaz-opt.webp" type="image/webp">
                        <img src="<?php echo SITE_URL . SITE_LOGO_WHITE; ?>" alt="<?php echo SITE_NAME; ?>" width="150" height="40" class="footer-logo" loading="lazy" decoding="async">
                    </picture>
                </a>
                <p class="text-white-50 small mb-3">Şanlıurfa'nın güvenilir sigorta acentesi. <?php echo SITE_FOUNDED; ?> yılından bu yana müşterilerimize en iyi sigorta çözümlerini sunuyoruz.</p>
                <div class="d-flex gap-2">
                    <?php
                    $socialLinks = getAllSocialMedia(true);
                    foreach ($socialLinks as $sl):
                        $slUrl = !empty($sl['url']) ? htmlspecialchars($sl['url'], ENT_QUOTES, 'UTF-8') : '#';
                    ?>
                    <a href="<?php echo $slUrl; ?>" <?php echo $slUrl !== '#' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?> class="btn btn-outline-light btn-sm rounded-circle social-btn" title="<?php echo htmlspecialchars($sl['label'], ENT_QUOTES, 'UTF-8'); ?>" aria-label="Emre Sigorta <?php echo htmlspecialchars($sl['label'], ENT_QUOTES, 'UTF-8'); ?> sayfası"><i class="<?php echo htmlspecialchars($sl['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i></a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Ürünlerimiz -->
            <div class="col-lg-2 col-md-6 col-6" data-aos="fade-up" data-aos-delay="100">
                <h6 class="fw-bold text-white mb-3">Ürünlerimiz</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="<?php echo SITE_URL; ?>/trafik-sigortasi.php">Trafik Sigortası</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/kasko.php">Kasko</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/dask.php">DASK</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/konut-sigortasi.php">Konut Sigortası</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/tamamlayici-saglik.php">Tamamlayıcı Sağlık</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/ozel-saglik.php">Özel Sağlık</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/seyahat-saglik.php">Seyahat Sağlık</a></li>
                </ul>
            </div>
            
            <!-- Kurumsal -->
            <div class="col-lg-2 col-md-6 col-6" data-aos="fade-up" data-aos-delay="200">
                <h6 class="fw-bold text-white mb-3">Kurumsal</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="<?php echo SITE_URL; ?>/hakkimizda.php">Hakkımızda</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/blog.php">Sigorta Blog</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/kampanyalar.php">Kampanyalar</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/sss.php">Sıkça Sorulan Sorular</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/iletisim.php">İletişim</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/police-iptal.php">Poliçe İptal</a></li>
                </ul>
            </div>
            
            <!-- Yasal -->
            <div class="col-lg-2 col-md-6 col-6" data-aos="fade-up" data-aos-delay="300">
                <h6 class="fw-bold text-white mb-3">Yasal Bilgilendirme</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="<?php echo SITE_URL; ?>/kvkk.php">KVKK Aydınlatma</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/cerez-politikasi.php">Çerez Politikası</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/gizlilik.php">Gizlilik Politikası</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/kullanim-sartlari.php">Kullanım Şartları</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/acik-riza.php">Açık Rıza Metni</a></li>
                </ul>
            </div>
            
            <!-- İletişim -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <h6 class="fw-bold text-white mb-3">İletişim</h6>
                <ul class="list-unstyled footer-contact">
                    <li class="mb-2">
                        <i class="fa-solid fa-location-dot text-primary me-2"></i>
                        <span class="text-white-50 small"><?php echo SITE_ADDRESS; ?></span>
                    </li>
                    <li class="mb-2">
                        <i class="fa-solid fa-phone text-primary me-2"></i>
                        <a href="tel:<?php echo SITE_PHONE_RAW; ?>" class="text-white-50 small text-decoration-none"><?php echo SITE_PHONE; ?></a>
                    </li>
                    <li class="mb-2">
                        <i class="fa-solid fa-envelope text-primary me-2"></i>
                        <a href="mailto:<?php echo SITE_EMAIL; ?>" class="text-white-50 small text-decoration-none"><?php echo SITE_EMAIL; ?></a>
                    </li>
                    <li class="mb-2">
                        <i class="fa-solid fa-envelope text-primary me-2"></i>
                        <a href="mailto:<?php echo SITE_EMAIL_ALT; ?>" class="text-white-50 small text-decoration-none"><?php echo SITE_EMAIL_ALT; ?></a>
                    </li>
                    <li class="mb-2">
                        <i class="fa-solid fa-clock text-primary me-2"></i>
                        <span class="text-white-50 small">Pzt-Cum: 09:00 - 18:00</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <hr class="border-secondary">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-white-50 small mb-0">&copy; <?php echo SITE_YEAR; ?> <?php echo SITE_NAME; ?> Aracılık Hizmetleri. Tüm hakları saklıdır.</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <div class="d-flex justify-content-center justify-content-md-end gap-3 align-items-center">
                    <span class="badge bg-secondary bg-opacity-25 text-white-50 px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> SSL Güvenli</span>
                    <span class="badge bg-secondary bg-opacity-25 text-white-50 px-3 py-2"><i class="fa-solid fa-lock me-1"></i> 256-Bit Şifreleme</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Float Button -->
<a href="https://wa.me/<?php echo str_replace('+', '', SITE_PHONE_RAW); ?>?text=<?php echo rawurlencode(WHATSAPP_MESSAGE); ?>" class="whatsapp-float" target="_blank" rel="noopener noreferrer" title="WhatsApp ile iletişime geçin" aria-label="WhatsApp üzerinden Emre Sigorta ile iletişime geçin">
    <i class="fa-brands fa-whatsapp"></i>
</a>

<!-- Back to Top -->
<button class="btn btn-primary btn-back-top rounded-circle shadow" id="backToTop" title="Yukarı Çık" aria-label="Sayfanın üstüne çık">
    <i class="fa-solid fa-chevron-up"></i>
</button>

<!-- Noscript Fallback -->
<noscript>
    <style>
        .whatsapp-float { display: block !important; }
        [data-aos] { opacity: 1 !important; transform: none !important; }
    </style>
</noscript>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

<!-- AOS JS (jsdelivr - daha hızlı) -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js" defer></script>

<!-- Custom JS -->
<script src="<?php echo SITE_URL; ?>/assets/js/main.js" defer></script>

<!-- Form Gönderim JS -->
<script>
(function() {
    'use strict';
    var csrfToken = document.querySelector('meta[name="csrf-token"]');
    var siteUrl = document.querySelector('meta[name="site-url"]');
    if (!csrfToken || !siteUrl) return;
    csrfToken = csrfToken.content;
    siteUrl = siteUrl.content;

    // data-form-type olan tüm formları yakala
    document.querySelectorAll('form[data-form-type]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitEmreForm(form);
        });
        // Buton tipli olanlar için
        var btn = form.querySelector('button[type="button"]');
        if (btn) {
            btn.removeAttribute('onclick');
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                submitEmreForm(form);
            });
        }
    });

    function submitEmreForm(form) {
        // Validasyon
        var requiredFields = form.querySelectorAll('[required]');
        var isValid = true;
        requiredFields.forEach(function(field) {
            var empty = field.type === 'file' ? !field.files.length : !field.value.trim();
            if (empty) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        if (!isValid) {
            showFormAlert(form, 'Lütfen tüm zorunlu alanları doldurun.', 'danger');
            return;
        }

        var btn = form.querySelector('button[type="submit"], button[type="button"]');
        var originalHtml = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Gönderiliyor...';
        }

        var formData = new FormData(form);
        formData.append('<?php echo CSRF_TOKEN_NAME; ?>', csrfToken);

        fetch(siteUrl + '/api/form-submit.php', {
            method: 'POST',
            body: formData
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                showFormAlert(form, data.message, 'success');
                form.reset();
                // WhatsApp'ı yeni sekmede aç (iletişim formu hariç)
                if (data.whatsapp_url && form.getAttribute('data-form-type') !== 'iletisim') {
                    setTimeout(function() {
                        window.open(data.whatsapp_url, '_blank');
                    }, 500);
                }
            } else {
                showFormAlert(form, data.message || 'Bir hata oluştu.', 'danger');
            }
        })
        .catch(function() {
            showFormAlert(form, 'Bağlantı hatası. Lütfen tekrar deneyin.', 'danger');
        })
        .finally(function() {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        });
    }

    function showFormAlert(form, message, type) {
        var existing = form.querySelector('.form-alert');
        if (existing) existing.remove();
        var div = document.createElement('div');
        div.className = 'form-alert alert alert-' + type + ' py-2 px-3 mt-2 mb-0';
        div.style.fontSize = '14px';
        div.style.borderRadius = '8px';
        div.textContent = message;
        var btn = form.querySelector('button');
        if (btn) btn.parentNode.insertBefore(div, btn);
        else form.appendChild(div);
        setTimeout(function() { div.remove(); }, 5000);
    }
})();
</script>

</body>
</html>
