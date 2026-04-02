<?php
require_once __DIR__ . '/includes/config.php'; 
$pageTitle = 'Giriş Yap - Müşteri Paneli';
$pageDescription = 'Emre Sigorta müşteri paneline giriş yapın. Poliçelerinizi görüntüleyin, teklif alın ve sigorta işlemlerinizi online yönetin.';
$pageKeywords = 'emre sigorta giriş, sigorta hesabı, müşteri paneli, online sigorta giriş';
$pageSchema = '<script type="application/ld+json">' . getBreadcrumbSchema([
    ['name' => 'Ana Sayfa', 'url' => 'https://' . SITE_DOMAIN . '/'],
    ['name' => 'Giriş Yap']
]) . '</script>';
?>
<?php include 'includes/header.php'; ?>

<section class="page-content" style="background: linear-gradient(135deg, #f0f4f8, #e8edf2); min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div style="max-width: 480px; margin: 40px auto;">

            <!-- Giriş Formu -->
            <div style="background: #fff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <div style="text-align: center; margin-bottom: 25px;">
                    <i class="fas fa-sign-in-alt" style="font-size: 40px; color: var(--primary); margin-bottom: 10px;"></i>
                    <h2 style="margin-bottom: 8px; font-size: 22px;">Hoş Geldiniz</h2>
                    <p style="color: var(--text-light); margin-bottom: 0; font-size: 14px;">Hesabınıza giriş yaparak poliçelerinizi yönetin.</p>
                </div>

                <form method="post" action="">
                    <?php echo getCSRFTokenField(); ?>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px;">TC Kimlik No / E-posta</label>
                        <div style="position: relative;">
                            <i class="fas fa-user" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa;"></i>
                            <input type="text" name="username" placeholder="TC Kimlik No veya E-posta" required autocomplete="username" style="width: 100%; padding: 14px 14px 14px 42px; border: 1px solid #ddd; border-radius: 10px; font-size: 15px; outline: none; transition: border-color 0.3s;">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px;">Şifre</label>
                        <div style="position: relative;">
                            <i class="fas fa-lock" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa;"></i>
                            <input type="password" name="password" placeholder="Şifrenizi girin" required autocomplete="current-password" style="width: 100%; padding: 14px 14px 14px 42px; border: 1px solid #ddd; border-radius: 10px; font-size: 15px; outline: none; transition: border-color 0.3s;">
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; font-size: 14px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="remember"> Beni hatırla
                        </label>
                        <a href="#" style="color: var(--primary); text-decoration: none;">Şifremi Unuttum</a>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; border-radius: 10px;">
                        <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
                    </button>
                </form>

                <div style="position: relative; text-align: center; margin: 25px 0;">
                    <span style="background: #fff; padding: 0 15px; position: relative; z-index: 1; color: var(--text-light); font-size: 13px;">veya</span>
                    <hr style="position: absolute; top: 50%; left: 0; right: 0; border: none; border-top: 1px solid #eee; margin: 0;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <button style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 13px; border: 1px solid #ddd; border-radius: 10px; background: #fff; font-size: 14px; cursor: pointer; transition: background 0.3s;">
                        <i class="fab fa-google" style="color: #4285f4; font-size: 18px;"></i> Google ile Giriş Yap
                    </button>
                    <button style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 13px; border: 1px solid #ddd; border-radius: 10px; background: #fff; font-size: 14px; cursor: pointer; transition: background 0.3s;">
                        <i class="fas fa-id-card" style="color: var(--primary); font-size: 18px;"></i> E-Devlet ile Giriş Yap
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
