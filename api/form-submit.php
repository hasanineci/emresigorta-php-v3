<?php
/**
 * Emre Sigorta - Form Gönderim API
 * Tüm site formlarından gelen verileri alır, DB'ye kaydeder ve WhatsApp URL döner
 */
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json; charset=utf-8');

// Sadece POST kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek metodu.']);
    exit;
}

// CSRF kontrolü
$csrfToken = $_POST[CSRF_TOKEN_NAME] ?? '';
if (!validateCSRFToken($csrfToken)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Güvenlik doğrulaması başarısız. Sayfayı yenileyip tekrar deneyin.']);
    exit;
}

// Rate limiting (dakikada max 5 form gönderimi)
$rateCheck = checkRateLimit('form_submit');
if ($rateCheck['blocked']) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Çok fazla talep gönderdiniz. Lütfen biraz bekleyin.']);
    exit;
}

// Form tipi zorunlu
$formType = trim($_POST['form_type'] ?? '');
if (empty($formType)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Form tipi belirtilmedi.']);
    exit;
}

// Geçerli form tipleri
$validFormTypes = [
    'trafik', 'kasko', 'dask', 'el-trafik', 'elektrikli-arac-kasko',
    'kisa-sureli-trafik', 'imm', 'yesil-kart', 'tamamlayici-saglik',
    'ozel-saglik', 'seyahat-saglik', 'pembe-kurdele', 'konut-sigortasi',
    'evim-guvende', 'cep-telefonu', 'evcil-hayvan', 'ferdi-kaza',
    'iletisim', 'sube-basvurusu', 'police-iptal',
    'anasayfa-trafik', 'anasayfa-kasko', 'anasayfa-dask', 'anasayfa-saglik',
    'kampanya-basvuru'
];

if (!in_array($formType, $validFormTypes, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Geçersiz form tipi.']);
    exit;
}

// Form verilerini topla (CSRF token ve form_type hariç)
$excludeKeys = [CSRF_TOKEN_NAME, 'form_type'];
$formData = [];
foreach ($_POST as $key => $value) {
    if (in_array($key, $excludeKeys, true)) continue;
    $formData[sanitizeInput($key)] = sanitizeInput($value);
}

// Dosya yükleme işlemi (ruhsat_foto)
if (!empty($_FILES['ruhsat_foto']) && $_FILES['ruhsat_foto']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['ruhsat_foto'];
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (in_array($mimeType, $allowedTypes, true) && $file['size'] <= $maxSize) {
        $uploadDir = __DIR__ . '/../uploads/ruhsat/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $ext = match($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'application/pdf' => 'pdf',
            default => 'jpg'
        };
        $fileName = uniqid('ruhsat_', true) . '.' . $ext;
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $formData['ruhsat_foto'] = 'uploads/ruhsat/' . $fileName;
        }
    }
}

// En az bir alan dolu olmalı
if (empty($formData)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Form verileri boş.']);
    exit;
}

// Ziyaretçi adı ve telefonu çıkar
$visitorName = $formData['ad_soyad'] ?? $formData['adsoyad'] ?? null;
$visitorPhone = $formData['telefon'] ?? $formData['cep_telefonu'] ?? null;

// Rate limit artır
incrementRateLimit('form_submit');

// DB'ye kaydet
try {
    $submissionId = createFormSubmission($formType, $formData, $visitorName, $visitorPhone);
} catch (Exception $e) {
    error_log('Form submission error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Bir hata oluştu. Lütfen tekrar deneyin.']);
    exit;
}

// Form tipi etiketleri (WhatsApp mesajı için)
$formTypeLabels = [
    'trafik' => 'Trafik Sigortası',
    'kasko' => 'Kasko Sigortası',
    'dask' => 'DASK',
    'el-trafik' => 'El Değiştiren Araç Trafik',
    'elektrikli-arac-kasko' => 'Elektrikli Araç Kasko',
    'kisa-sureli-trafik' => 'Kısa Süreli Trafik',
    'imm' => 'İMM Sigortası',
    'yesil-kart' => 'Yeşil Kart',
    'tamamlayici-saglik' => 'Tamamlayıcı Sağlık',
    'ozel-saglik' => 'Özel Sağlık',
    'seyahat-saglik' => 'Seyahat Sağlık',
    'pembe-kurdele' => 'Pembe Kurdele',
    'konut-sigortasi' => 'Konut Sigortası',
    'evim-guvende' => 'Evim Güvende',
    'cep-telefonu' => 'Cep Telefonu Sigortası',
    'evcil-hayvan' => 'Evcil Hayvan Sigortası',
    'ferdi-kaza' => 'Ferdi Kaza',
    'iletisim' => 'İletişim Formu',
    'sube-basvurusu' => 'Şube Başvurusu',
    'police-iptal' => 'Poliçe İptal',
    'anasayfa-trafik' => 'Trafik Sigortası (Hızlı)',
    'anasayfa-kasko' => 'Kasko Sigortası (Hızlı)',
    'anasayfa-dask' => 'DASK (Hızlı)',
    'anasayfa-saglik' => 'Sağlık Sigortası (Hızlı)',
    'kampanya-basvuru' => 'Kampanya Başvurusu',
];

// Alan etiketleri
$fieldLabels = [
    'tc_kimlik' => 'TC Kimlik',
    'plaka' => 'Plaka',
    'telefon' => 'Telefon',
    'email' => 'E-posta',
    'ad_soyad' => 'Ad Soyad',
    'ruhsat_sahibi' => 'Ruhsat Sahibi',
    'dogum_tarihi' => 'Doğum Tarihi',
    'ruhsat_foto' => 'Ruhsat Fotoğrafı',
    'arac_engeli' => 'Araç Engeli',
    'imm_teminat' => 'İMM Teminat',
    'ikame_arac' => 'İkame Araç',
    'kasko_tipi' => 'Kasko Tipi',
    'il' => 'İl',
    'yapi_tarzi' => 'Yapı Tarzı',
    'brut_metrekare' => 'Brüt m²',
    'arac_markasi' => 'Araç Markası',
    'sure' => 'Süre',
    'ulke' => 'Ülke',
    'seyahat_baslangic' => 'Seyahat Başlangıç',
    'seyahat_bitis' => 'Seyahat Bitiş',
    'bolge' => 'Bölge',
    'plan_tipi' => 'Plan Tipi',
    'plan' => 'Plan',
    'konut_tipi' => 'Konut Tipi',
    'telefon_markasi' => 'Telefon Markası',
    'imei' => 'IMEI',
    'hayvan_turu' => 'Hayvan Türü',
    'hayvan_yasi' => 'Hayvan Yaşı',
    'meslek' => 'Meslek',
    'konu' => 'Konu',
    'mesaj' => 'Mesaj',
    'egitim' => 'Eğitim',
    'ilce' => 'İlçe',
    'deneyim' => 'Sigorta Deneyimi',
    'ofis' => 'Mevcut Ofis',
    'motivasyon' => 'Motivasyon',
    'police_no' => 'Poliçe No',
    'police_turu' => 'Poliçe Türü',
    'iptal_sebebi' => 'İptal Sebebi',
    'aciklama' => 'Açıklama',
    'tc_vergi_no' => 'TC/Vergi No',
    'cep_telefonu' => 'Telefon',
    'adres' => 'Adres',
    'adsoyad' => 'Ad Soyad',
    'kvkk' => 'KVKK Onay',
    'mahalle' => 'Mahalle',
    'bucak' => 'Bucak/Köy',
    'cadde_sokak' => 'Cadde/Sokak',
    'bina_no' => 'Bina No',
    'ic_kapi_no' => 'İç Kapı No',
    'adres_kodu' => 'Adres Kodu (UAVT)',
    'bina_insa_yili' => 'Bina İnşa Yılı',
    'bina_kat_sayisi' => 'Bina Toplam Kat',
    'bina_hasar_durumu' => 'Bina Hasar Durumu',
    'bulundugu_kat' => 'Bulunduğu Kat',
    'sigorta_sifati' => 'Sigorta Ettiren Sıfatı',
    'kullanim_sekli' => 'Kullanım Şekli',
    'bina_degeri' => 'Bina Değeri',
    'esya_degeri' => 'Eşya Değeri',
    'cam_degeri' => 'Cam Değeri',
    'demirbas_degeri' => 'Demirbaşlar',
    'ucuncu_sahis' => '3. Şahıs Sorumluluk',
    'banka_serhi' => 'Banka/Kurum Şerhi',
    'banka_adi' => 'Banka Adı',
    'sube_adi' => 'Şube Adı',
];

// WhatsApp mesajı oluştur
$label = $formTypeLabels[$formType] ?? $formType;
$waMessage = "Merhaba, *{$label}* hakkında bilgi almak istiyorum.\n\n";

foreach ($formData as $key => $value) {
    if (empty($value) || $key === 'kvkk' || $key === 'ruhsat_foto') continue;
    $fieldLabel = $fieldLabels[$key] ?? ucfirst(str_replace('_', ' ', $key));
    $waMessage .= "*{$fieldLabel}:* {$value}\n";
}

$waMessage .= "\nTeşekkürler.";

// WhatsApp URL
$phone = str_replace('+', '', SITE_PHONE_RAW);
$waUrl = 'https://wa.me/' . $phone . '?text=' . rawurlencode($waMessage);

echo json_encode([
    'success' => true,
    'message' => 'Talebiniz başarıyla alındı. En kısa sürede size dönüş yapacağız.',
    'submission_id' => $submissionId,
    'whatsapp_url' => $waUrl
], JSON_UNESCAPED_UNICODE);
