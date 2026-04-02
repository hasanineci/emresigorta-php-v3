<?php
/**
 * DASK Adres Kodu Sorgulama Proxy API
 * adreskodu.dask.gov.tr API'sini proxy'ler
 */
header('Content-Type: application/json; charset=utf-8');

session_start();

$step = isset($_GET['step']) ? $_GET['step'] : '';
$DASK_BASE = 'https://adreskodu.dask.gov.tr/site-element/control/';

// DASK session bilgilerini al/oluştur
function getDaskSession() {
    global $DASK_BASE;
    $cookieFile = sys_get_temp_dir() . '/dask_cookies_' . session_id() . '.txt';
    
    // Token yoksa veya 10 dakikadan eskiyse yenile
    if (empty($_SESSION['dask_token']) || empty($_SESSION['dask_token_time']) || (time() - $_SESSION['dask_token_time']) > 500) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://adreskodu.dask.gov.tr/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        curl_exec($ch);
        curl_close($ch);
        
        // Token al
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $DASK_BASE . 'y.ashx',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_HTTPHEADER => ['Referer: https://adreskodu.dask.gov.tr/'],
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $token = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200 || empty($token)) {
            return false;
        }
        
        $_SESSION['dask_token'] = $token;
        $_SESSION['dask_token_time'] = time();
        
        // Adresli modunu başlat
        daskPost('load.ashx', $token . '&t=adresli', $cookieFile);
    }
    
    return ['token' => $_SESSION['dask_token'], 'cookieFile' => $cookieFile];
}

function daskPost($endpoint, $postData, $cookieFile) {
    global $DASK_BASE;
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $DASK_BASE . $endpoint,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => $cookieFile,
        CURLOPT_COOKIEFILE => $cookieFile,
        CURLOPT_HTTPHEADER => [
            'Referer: https://adreskodu.dask.gov.tr/',
            'Content-Type: application/x-www-form-urlencoded',
        ],
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        return false;
    }
    
    // DASK windows-1254 kodlaması kullanıyor, UTF-8'e çevir
    $result = mb_convert_encoding($result, 'UTF-8', 'Windows-1254');
    
    return $result;
}

// JSON tipinde yanıt dönen adımlar (il, ilce, bucak, mahalle)
function getJsonStep($type, $id) {
    $session = getDaskSession();
    if (!$session) {
        echo json_encode(['error' => 'DASK bağlantısı kurulamadı']);
        return;
    }
    
    $result = daskPost('load.ashx', $session['token'] . '&t=' . $type . '&u=' . intval($id), $session['cookieFile']);
    if (!$result) {
        echo json_encode(['error' => 'Veri alınamadı']);
        return;
    }
    
    $data = json_decode($result, true);
    if (!$data || !isset($data['yt'])) {
        echo json_encode(['error' => 'Geçersiz yanıt']);
        return;
    }
    
    // İlk "SEÇİNİZ" öğesini çıkar
    $items = [];
    foreach ($data['yt'] as $item) {
        if (!empty($item['value'])) {
            $items[] = ['id' => $item['value'], 'ad' => $item['text']];
        }
    }
    
    echo json_encode($items);
}

// HTML table tipinde yanıt dönen adımlar (cadde/sokak, bina, ic kapi)
function getHtmlStep($type, $id, $term = '') {
    $session = getDaskSession();
    if (!$session) {
        echo json_encode(['error' => 'DASK bağlantısı kurulamadı']);
        return;
    }
    
    // Step 3 cadde/sokak yükleme (HTML form döner)
    if ($type === 'cad_init') {
        daskPost('load.ashx', $session['token'] . '&t=cad', $session['cookieFile']);
    }
    // Step 4 bina yükleme (HTML form döner)
    if ($type === 'bin_init') {
        daskPost('load.ashx', $session['token'] . '&t=bin', $session['cookieFile']);
    }
    // Step 5 iç kapı yükleme
    if ($type === 'bini_init') {
        daskPost('load.ashx', $session['token'] . '&t=bini&u=' . intval($id), $session['cookieFile']);
    }
    
    $postData = $session['token'] . '&t=' . $type . '&u=' . intval($id);
    if ($term !== '') {
        $postData .= '&term=' . urlencode($term);
    }
    
    $result = daskPost('load.ashx', $postData, $session['cookieFile']);
    if (!$result) {
        echo json_encode(['error' => 'Veri alınamadı']);
        return;
    }
    
    return $result;
}

// Cadde/sokak HTML parse
function parseCaddeHtml($html) {
    $items = [];
    // <tr id="s329902"><td class="f">CADDE</td><td>AÇIKSU</td><td class="s">...
    if (preg_match_all('/<tr id="s(\d+)"[^>]*><td[^>]*>([^<]*)<\/td><td>([^<]*)<\/td>/i', $html, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $items[] = ['id' => $m[1], 'tur' => trim($m[2]), 'ad' => trim($m[3])];
        }
    }
    return $items;
}

// Bina HTML parse
function parseBinaHtml($html) {
    $items = [];
    // <tr id="d19465660"><td class="f">100</td><td class="bc">19465660</td><td>SITE</td><td>APT</td>
    if (preg_match_all('/<tr id="d(\d+)"[^>]*><td[^>]*>([^<]*)<\/td><td[^>]*>[^<]*<\/td><td>([^<]*)<\/td><td>([^<]*)<\/td>/i', $html, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $site = trim(str_replace('&nbsp;', '', $m[3]));
            $apt = trim(str_replace('&nbsp;', '', $m[4]));
            $label = trim($m[2]);
            if ($site) $label .= ' - ' . $site;
            if ($apt) $label .= ' (' . $apt . ')';
            $items[] = ['id' => $m[1], 'no' => trim($m[2]), 'ad' => $label];
        }
    }
    return $items;
}

// İç kapı HTML parse - adres kodunu da içerir
function parseIcKapiHtml($html) {
    $items = [];
    // <tr id="i2219539877"><td class="fx">1</td><td class="s"><a ... onclick="showKodWithParam('2219539877');">
    if (preg_match_all('/<tr id="i(\d+)"[^>]*><td[^>]*>([^<]*)<\/td>/i', $html, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $items[] = ['adres_kodu' => $m[1], 'ic_kapi' => trim($m[2])];
        }
    }
    return $items;
}

// --------------- ROUTING ---------------
switch ($step) {
    case 'init':
        $session = getDaskSession();
        echo json_encode(['ok' => $session ? true : false]);
        break;
        
    case 'iller':
        getJsonStep('il', 0);
        break;
        
    case 'ilceler':
        $il = isset($_GET['il']) ? intval($_GET['il']) : 0;
        if (!$il) { echo json_encode(['error' => 'İl belirtilmedi']); break; }
        getJsonStep('ce', $il);
        break;
        
    case 'bucaklar':
        $ilce = isset($_GET['ilce']) ? intval($_GET['ilce']) : 0;
        if (!$ilce) { echo json_encode(['error' => 'İlçe belirtilmedi']); break; }
        getJsonStep('vl', $ilce);
        break;
        
    case 'mahalleler':
        $bucak = isset($_GET['bucak']) ? intval($_GET['bucak']) : 0;
        if (!$bucak) { echo json_encode(['error' => 'Bucak belirtilmedi']); break; }
        getJsonStep('mh', $bucak);
        break;
        
    case 'caddeler':
        $mahalle = isset($_GET['mahalle']) ? intval($_GET['mahalle']) : 0;
        $term = isset($_GET['term']) ? $_GET['term'] : '';
        if (!$mahalle) { echo json_encode(['error' => 'Mahalle belirtilmedi']); break; }
        
        // Önce cadde adımını başlat, sonra arama yap
        $session = getDaskSession();
        if (!$session) { echo json_encode(['error' => 'Bağlantı hatası']); break; }
        
        daskPost('load.ashx', $session['token'] . '&t=cad', $session['cookieFile']);
        $html = daskPost('load.ashx', $session['token'] . '&t=sf&u=' . intval($mahalle) . '&term=' . urlencode($term), $session['cookieFile']);
        
        if (!$html) { echo json_encode(['error' => 'Veri alınamadı']); break; }
        echo json_encode(parseCaddeHtml($html));
        break;
        
    case 'binalar':
        $sokak = isset($_GET['sokak']) ? intval($_GET['sokak']) : 0;
        $term = isset($_GET['term']) ? $_GET['term'] : '';
        if (!$sokak) { echo json_encode(['error' => 'Sokak belirtilmedi']); break; }
        
        $session = getDaskSession();
        if (!$session) { echo json_encode(['error' => 'Bağlantı hatası']); break; }
        
        daskPost('load.ashx', $session['token'] . '&t=bin', $session['cookieFile']);
        $html = daskPost('load.ashx', $session['token'] . '&t=dk&u=' . intval($sokak) . '&term=' . urlencode($term), $session['cookieFile']);
        
        if (!$html) { echo json_encode(['error' => 'Veri alınamadı']); break; }
        echo json_encode(parseBinaHtml($html));
        break;
        
    case 'ickapilar':
        $bina = isset($_GET['bina']) ? intval($_GET['bina']) : 0;
        if (!$bina) { echo json_encode(['error' => 'Bina belirtilmedi']); break; }
        
        $session = getDaskSession();
        if (!$session) { echo json_encode(['error' => 'Bağlantı hatası']); break; }
        
        daskPost('load.ashx', $session['token'] . '&t=bini&u=' . intval($bina), $session['cookieFile']);
        $html = daskPost('load.ashx', $session['token'] . '&t=ick&u=' . intval($bina) . '&term=', $session['cookieFile']);
        
        if (!$html) { echo json_encode(['error' => 'Veri alınamadı']); break; }
        echo json_encode(parseIcKapiHtml($html));
        break;
        
    default:
        echo json_encode(['error' => 'Geçersiz adım']);
}
