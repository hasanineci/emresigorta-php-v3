<?php
/**
 * Emre Sigorta - Admin Giriş Sayfası (Split-Screen)
 */
require_once __DIR__ . '/includes/auth.php';

if (isAdminLoggedIn()) {
    header('Location: ' . ADMIN_URL . '/dashboard.php');
    exit;
}

$error = '';

// CAPTCHA oluşturma fonksiyonu
function generateCaptcha() {
    $ops = ['+', '-', '×'];
    $op = $ops[array_rand($ops)];
    if ($op === '+') {
        $a = rand(2, 15); $b = rand(1, 10); $ans = $a + $b;
    } elseif ($op === '-') {
        $a = rand(6, 20); $b = rand(1, $a - 1); $ans = $a - $b;
    } else {
        $a = rand(2, 9); $b = rand(2, 9); $ans = $a * $b;
    }
    $_SESSION['_captcha_answer'] = $ans;
    return $a . ' ' . $op . ' ' . $b . ' = ?';
}

if (isset($_GET['timeout'])) {
    $error = 'Oturumunuz zaman aşımına uğradı. Lütfen tekrar giriş yapın.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCSRF();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // CAPTCHA doğrulama (session'daki eski cevapla karşılaştır)
    $captchaInput = trim($_POST['captcha_answer'] ?? '');
    $captchaExpected = $_SESSION['_captcha_answer'] ?? null;
    $captchaValid = ($captchaExpected !== null && (int)$captchaInput === (int)$captchaExpected);
    
    // Cloudflare Turnstile doğrulama (ek güvenlik, anahtarlar varsa)
    $turnstileValid = true;
    if (TURNSTILE_SECRET_KEY) {
        $turnstileValid = false;
        $turnstileToken = $_POST['cf-turnstile-response'] ?? '';
        if ($turnstileToken) {
            $verifyData = [
                'secret' => TURNSTILE_SECRET_KEY,
                'response' => $turnstileToken,
                'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
            ];
            $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($verifyData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $verifyResponse = curl_exec($ch);
            curl_close($ch);
            if ($verifyResponse) {
                $verifyResult = json_decode($verifyResponse, true);
                $turnstileValid = !empty($verifyResult['success']);
            }
        }
    }
    
    if (!$captchaValid) {
        $error = 'Güvenlik sorusu yanlış. Lütfen tekrar deneyin.';
    } elseif (!$turnstileValid) {
        $error = 'Güvenlik doğrulaması başarısız. Lütfen tekrar deneyin.';
    } elseif (empty($username) || empty($password)) {
        $error = 'Kullanıcı adı ve şifre gereklidir.';
    } else {
        $result = adminLogin($username, $password);
        if ($result['success']) {
            header('Location: ' . ADMIN_URL . '/dashboard.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

// POST işlemi bitti, yeni CAPTCHA oluştur (sonraki gösterim için)
$captchaQuestion = generateCaptcha();

$sliders = getLoginSliders();
?>
<!DOCTYPE html>
<html lang="tr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Yönetim Paneli | <?php echo SITE_NAME; ?></title>
    <link rel="icon" type="image/png" href="<?php echo SITE_URL; ?>/assets/images/logo/logo-siyah.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <?php if (TURNSTILE_SITE_KEY): ?>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <?php endif; ?>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            background: #0f172a;
            position: relative; overflow: hidden;
        }

        /* Background effects */
        .bg-layer {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
        }
        .bg-layer::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 80%, rgba(79,70,229,0.15) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 80% 20%, rgba(99,102,241,0.1) 0%, transparent 60%);
        }
        .grid-bg {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-size: 60px 60px;
            background-image:
                linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
        }
        /* Animated orbs */
        .orb {
            position: fixed; border-radius: 50%; pointer-events: none; z-index: 0;
            filter: blur(80px);
        }
        .orb-1 {
            width: 400px; height: 400px; top: -100px; right: -100px;
            background: rgba(79,70,229,0.15);
            animation: orbFloat 15s ease-in-out infinite;
        }
        .orb-2 {
            width: 300px; height: 300px; bottom: -80px; left: -80px;
            background: rgba(99,102,241,0.1);
            animation: orbFloat 20s ease-in-out infinite reverse;
        }
        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0); }
            33% { transform: translate(30px, -20px); }
            66% { transform: translate(-20px, 30px); }
        }

        .login-wrapper {
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; padding: 40px 20px; position: relative; z-index: 1;
        }

        .login-inner {
            display: flex; align-items: stretch; gap: 40px;
            max-width: 920px; width: 100%;
        }

        .login-left { flex: 1; min-width: 0; max-width: 420px; }

        .back-link { margin-bottom: 24px; }
        .back-link a {
            color: rgba(255,255,255,0.5); text-decoration: none; font-size: 13px;
            transition: color 0.2s; display: inline-flex; align-items: center; gap: 6px;
        }
        .back-link a:hover { color: rgba(255,255,255,0.9); }

        .login-card {
            background: #fff;
            border-radius: 20px; padding: 44px 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.05);
            position: relative;
        }

        .login-logo { text-align: center; margin-bottom: 32px; }
        .login-logo img { height: 42px; }
        .login-title {
            font-size: 22px; font-weight: 700; color: #1e293b;
            margin-bottom: 6px; text-align: center; letter-spacing: -0.3px;
        }
        .login-subtitle { font-size: 14px; color: #64748b; margin-bottom: 32px; text-align: center; }
        .form-label { font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px; }
        .form-control {
            padding: 12px 16px 12px 44px; border-radius: 12px;
            border: 1.5px solid #e2e8f0; font-size: 14px; transition: all 0.2s;
            background: #f8fafc; color: #1e293b;
        }
        .form-control::placeholder { color: #94a3b8; }
        .form-control:focus {
            border-color: #4f46e5; box-shadow: 0 0 0 4px rgba(79,70,229,0.1); background: #fff;
        }
        .input-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 15px; }
        .captcha-box {
            display: flex; align-items: center; gap: 12px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            border: 1.5px solid #c7d2fe; border-radius: 12px; padding: 12px 16px;
        }
        .captcha-question {
            font-size: 18px; font-weight: 700; color: #4338ca; white-space: nowrap;
            letter-spacing: 2px; font-family: 'Courier New', monospace;
            user-select: none; -webkit-user-select: none;
        }
        .captcha-input {
            max-width: 100px; text-align: center; font-weight: 600; font-size: 16px !important;
            border-radius: 8px !important; padding: 8px 12px !important;
        }
        .btn-login {
            width: 100%; padding: 13px; border-radius: 12px; font-size: 15px; font-weight: 600;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border: none; color: #fff; transition: all 0.3s;
            position: relative; overflow: hidden;
        }
        .btn-login::after {
            content: ''; position: absolute; top: 0; left: -100%; width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: none;
        }
        .btn-login:hover::after { left: 120%; transition: left 0.6s ease; }
        .btn-login:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            transform: translateY(-1px); box-shadow: 0 8px 24px rgba(79,70,229,0.35); color: #fff;
        }
        .form-check-input:checked { background-color: #4f46e5; border-color: #4f46e5; }

        .login-footer {
            text-align: center; margin-top: 24px;
            font-size: 12px; color: rgba(255,255,255,0.35);
        }

        /* Right Panel - Slider */
        .login-right {
            flex: 1; min-width: 0; max-width: 420px;
            display: flex; flex-direction: column; justify-content: center;
        }
        .slider-header { color: #fff; text-align: center; margin-bottom: 28px; }
        .slider-header h2 { font-size: 22px; font-weight: 700; margin-bottom: 6px; letter-spacing: -0.3px; }
        .slider-header p { font-size: 13px; color: rgba(255,255,255,0.5); }

        .slider-card {
            background: rgba(255,255,255,0.04); backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.06); border-radius: 16px;
            padding: 32px 28px; color: #fff;
        }
        .slider-card .quote-icon {
            font-size: 28px; color: rgba(99,102,241,0.5); margin-bottom: 14px; line-height: 1;
        }
        .slider-card .quote-text { font-size: 15px; line-height: 1.7; margin-bottom: 20px; font-style: italic; color: rgba(255,255,255,0.85); }
        .slider-card .author-row { display: flex; align-items: center; gap: 12px; }
        .slider-card .author-avatar {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, rgba(79,70,229,0.3), rgba(99,102,241,0.2));
            border: 1px solid rgba(99,102,241,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; font-weight: 700; color: #a5b4fc;
        }
        .slider-card .author-name { font-weight: 600; font-size: 14px; }
        .slider-card .author-title { font-size: 12px; color: rgba(255,255,255,0.5); }

        .slider-controls {
            display: flex; justify-content: center; align-items: center;
            gap: 10px; margin-top: 22px;
        }
        .slider-btn {
            width: 36px; height: 36px; border-radius: 10px;
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.6); cursor: pointer; display: flex;
            align-items: center; justify-content: center; transition: all 0.2s; font-size: 13px;
        }
        .slider-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .slider-dots { display: flex; gap: 6px; }
        .slider-dot {
            width: 7px; height: 7px; border-radius: 50%; cursor: pointer;
            background: rgba(255,255,255,0.2); transition: all 0.3s;
        }
        .slider-dot.active { background: #818cf8; width: 22px; border-radius: 4px; }

        .slider-stats {
            display: flex; justify-content: center; gap: 32px;
            margin-top: 28px; padding-top: 22px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .slider-stats .stat { text-align: center; color: #fff; }
        .slider-stats .stat-value { font-size: 20px; font-weight: 700; }
        .slider-stats .stat-label { font-size: 11px; color: rgba(255,255,255,0.45); margin-top: 2px; }

        /* Slide animation */
        .slide-item { display: none; animation: fadeSlideIn 0.5s ease; }
        .slide-item.active { display: block; }
        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Error alert */
        .login-error {
            font-size: 13px; border-radius: 12px; border: none;
            background: #fef2f2; color: #dc2626; padding: 12px 16px;
            display: flex; align-items: center; gap: 8px; margin-bottom: 20px;
        }

        @media (max-width: 860px) {
            .login-right { display: none; }
            .login-inner { justify-content: center; }
        }
        @media (max-width: 576px) {
            .login-wrapper { padding: 20px 16px; }
            .login-card { padding: 32px 24px; border-radius: 16px; }
        }
    </style>
</head>
<body>
    <!-- Background -->
    <div class="bg-layer"></div>
    <div class="grid-bg"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="login-wrapper">
        <div class="login-inner">
            <div class="login-left">
            <div class="back-link">
                <a href="<?php echo SITE_URL; ?>/"><i class="fas fa-arrow-left me-1"></i> Siteye Dön</a>
            </div>
            
            <div class="login-card">
                <div class="login-logo">
                    <img src="<?php echo SITE_URL; ?>/assets/images/logo/logo-siyah.png" alt="<?php echo SITE_NAME; ?>">
                </div>
                
                <?php
                    $hour = (int)date('H');
                    if ($hour >= 6 && $hour < 12) {
                        $greeting = 'Günaydın';
                        $greetIcon = 'fas fa-sun';
                    } elseif ($hour >= 12 && $hour < 18) {
                        $greeting = 'İyi Günler';
                        $greetIcon = 'fas fa-cloud-sun';
                    } elseif ($hour >= 18 && $hour < 22) {
                        $greeting = 'İyi Akşamlar';
                        $greetIcon = 'fas fa-moon';
                    } else {
                        $greeting = 'İyi Geceler';
                        $greetIcon = 'fas fa-star';
                    }
                ?>
                <h1 class="login-title"><i class="<?php echo $greetIcon; ?>" style="font-size: 20px; color: #3b82f6; margin-right: 6px;"></i><?php echo $greeting; ?></h1>
                <p class="login-subtitle">Yönetim paneline erişmek için giriş yapın.</p>
                
                <?php if ($error): ?>
                    <div class="login-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="" autocomplete="off">
                    <?php echo getCSRFTokenField(); ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Kullanıcı Adı</label>
                        <div class="position-relative">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" name="username" class="form-control" placeholder="Kullanıcı adınızı girin" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Şifre</label>
                        <div class="position-relative">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password" class="form-control" placeholder="Şifrenizi girin" required>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember" style="font-size: 13px; color: #667085;">Beni Hatırla</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-shield-alt me-1" style="color: #4f46e5;"></i>Güvenlik Doğrulaması</label>
                        <div class="captcha-box">
                            <span class="captcha-question"><?php echo $captchaQuestion; ?></span>
                            <input type="number" name="captcha_answer" class="form-control captcha-input" placeholder="Cevap" required autocomplete="off">
                        </div>
                    </div>
                    
                    <?php if (TURNSTILE_SITE_KEY): ?>
                    <div class="mb-3">
                        <div class="cf-turnstile" data-sitekey="<?php echo htmlspecialchars(TURNSTILE_SITE_KEY, ENT_QUOTES, 'UTF-8'); ?>" data-theme="light"></div>
                    </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn btn-login">
                        Giriş Yap <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </form>
            </div>
            
            <div class="login-footer">
                <i class="fas fa-shield-alt me-1"></i> Güvenli bağlantı ile korunmaktadır
            </div>
            </div>

            <!-- Sağ Panel: Bildirim Slider -->
            <div class="login-right">
                <div class="slider-header">
                    <h2><i class="fas fa-shield-alt me-2"></i><?php echo SITE_NAME; ?></h2>
                    <p>Sigortada güvenin adresi</p>
                </div>
                
                <div id="sliderContent">
                    <?php if (!empty($sliders)): ?>
                        <?php foreach ($sliders as $idx => $slide): ?>
                            <div class="slide-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                                <div class="slider-card">
                                    <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                                    <p class="quote-text"><?php echo htmlspecialchars($slide['quote_text'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <div class="author-row">
                                        <div class="author-avatar"><?php echo mb_strtoupper(mb_substr($slide['author_name'], 0, 1, 'UTF-8'), 'UTF-8'); ?></div>
                                        <div>
                                            <div class="author-name"><?php echo htmlspecialchars($slide['author_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                                            <div class="author-title"><?php echo htmlspecialchars($slide['author_title'], ENT_QUOTES, 'UTF-8'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="slide-item active">
                            <div class="slider-card">
                                <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                                <p class="quote-text">Müşterilerimize en iyi sigorta çözümlerini sunarak güvenli bir gelecek inşa ediyoruz.</p>
                                <div class="author-row">
                                    <div class="author-avatar">E</div>
                                    <div>
                                        <div class="author-name">Emre Sigorta</div>
                                        <div class="author-title">Yönetim Paneli</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (count($sliders) > 1): ?>
                <div class="slider-controls">
                    <button class="slider-btn" id="sliderPrev"><i class="fas fa-chevron-left"></i></button>
                    <div class="slider-dots" id="sliderDots">
                        <?php foreach ($sliders as $idx => $s): ?>
                            <div class="slider-dot <?php echo $idx === 0 ? 'active' : ''; ?>" data-index="<?php echo $idx; ?>"></div>
                        <?php endforeach; ?>
                    </div>
                    <button class="slider-btn" id="sliderNext"><i class="fas fa-chevron-right"></i></button>
                </div>
                <?php endif; ?>
                
                <div class="slider-stats">
                    <div class="stat">
                        <div class="stat-value">18+</div>
                        <div class="stat-label">Sigorta Ürünü</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">1000+</div>
                        <div class="stat-label">Mutlu Müşteri</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">7/24</div>
                        <div class="stat-label">Destek</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.slide-item');
        const dots = document.querySelectorAll('.slider-dot');
        if (slides.length <= 1) return;
        
        let current = 0;
        let timer;
        
        function showSlide(idx) {
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            current = (idx + slides.length) % slides.length;
            slides[current].classList.add('active');
            dots[current].classList.add('active');
        }
        
        function startAuto() { timer = setInterval(() => showSlide(current + 1), 5000); }
        function resetAuto() { clearInterval(timer); startAuto(); }
        
        const prev = document.getElementById('sliderPrev');
        const next = document.getElementById('sliderNext');
        if (prev) prev.addEventListener('click', () => { showSlide(current - 1); resetAuto(); });
        if (next) next.addEventListener('click', () => { showSlide(current + 1); resetAuto(); });
        dots.forEach(d => d.addEventListener('click', function() { showSlide(+this.dataset.index); resetAuto(); }));
        
        startAuto();
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
