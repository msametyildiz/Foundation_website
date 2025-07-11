<?php
/**
 * Genel yardımcı fonksiyonlar
 */

// Site URL'si oluştur (SEO dostu URL sistemi)
function site_url($path = '', $full = false) {
    // Base URL'i belirle
    if ($full) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $base = $protocol . $_SERVER['HTTP_HOST'];
    } else {
        $base = '';
    }
    
    // Path boşsa ana sayfa
    if (empty($path) || $path === 'index.php') {
        return $base . '/';
    }
    
    // index.php?page= formatını temizle
    if (strpos($path, 'index.php?page=') === 0) {
        $path = substr($path, 15);
    }
    
    // İlk / karakterini ekle
    $path = '/' . ltrim($path, '/');
    
    // URL'i oluştur ve döndür
    return $base . $path;
}

// Aktif sayfa kontrolü (SEO dostu URL'ler için)
function is_active_page($page) {
    $current_page = get_current_page();
    return $current_page === $page ? 'active' : '';
}

// Mevcut sayfa adını al
function get_current_page() {
    // Önce GET parametresini kontrol et (geriye dönük uyumluluk)
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        return htmlspecialchars(trim($_GET['page']), ENT_QUOTES, 'UTF-8');
    }
    
    // URL path'ini al
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    
    // index.php'yi kaldır
    $path = str_replace('index.php', '', $path);
    $path = trim($path, '/');
    
    // Boş ise ana sayfa
    if (empty($path)) {
        return 'home';
    }
    
    // Alt sayfa (id) varsa ana sayfayı dön
    if (strpos($path, '/') !== false) {
        list($path) = explode('/', $path);
    }
    
    // Path'i return et
    return $path;
}

// Sayfa bilgilerini getir
function getPageInfo($page) {
    $pages = [
        'home' => [
            'title' => 'Ana Sayfa - Necat Derneği',
            'description' => 'Necat Derneği ile yardım eli uzatın. Bağış yapın, gönüllü olun.',
            'keywords' => 'necat derneği, bağış, yardım, sosyal sorumluluk'
        ],
        'about' => [
            'title' => 'Hakkımızda - Necat Derneği',
            'description' => 'Necat Derneği\'nin misyonu, vizyonu ve faaliyetleri hakkında bilgi edinin.',
            'keywords' => 'hakkımızda, misyon, vizyon, necat derneği'
        ],
        'projects' => [
            'title' => 'Projelerimiz - Necat Derneği',
            'description' => 'Gerçekleştirdiğimiz sosyal sorumluluk projeleri ve faaliyetlerimiz.',
            'keywords' => 'projeler, sosyal sorumluluk, yardım projeleri'
        ],
        'donate' => [
            'title' => 'Bağış Yap - Necat Derneği',
            'description' => 'Necat Derneği\'ne bağış yapın, yardım eli uzatın.',
            'keywords' => 'bağış, destek, yardım, dekont'
        ],
        'volunteer' => [
            'title' => 'Gönüllü Ol - Necat Derneği',
            'description' => 'Necat Derneği gönüllüsü olun, sosyal sorumluluğa katkıda bulunun.',
            'keywords' => 'gönüllü, gönüllülük, sosyal sorumluluk'
        ],
        'faq' => [
            'title' => 'Sıkça Sorulan Sorular - Necat Derneği',
            'description' => 'Necat Derneği hakkında merak ettikleriniz ve sık sorulan sorular.',
            'keywords' => 'sss, sorular, yardım'
        ],
        'contact' => [
            'title' => 'İletişim - Necat Derneği',
            'description' => 'Necat Derneği ile iletişime geçin. Adres, telefon ve e-posta bilgileri.',
            'keywords' => 'iletişim, adres, telefon, e-posta'
        ],
        'press' => [
            'title' => 'Basında Biz - Necat Derneği',
            'description' => 'Necat Derneği basın bültenleri ve medya kiti.',
            'keywords' => 'basın, medya, haberler'
        ],
        'documents' => [
            'title' => 'Belgelerimiz - Necat Derneği',
            'description' => 'Necat Derneği faaliyet raporları ve resmi belgeleri.',
            'keywords' => 'belgeler, faaliyet raporu, şeffaflık'
        ],
        'team' => [
            'title' => 'Yönetim Kurulu - Necat Derneği',
            'description' => 'Necat Derneği yönetim kurulu ve ekip üyeleri.',
            'keywords' => 'yönetim kurulu, ekip, kurucu'
        ],
        '404' => [
            'title' => 'Sayfa Bulunamadı - Necat Derneği',
            'description' => 'Aradığınız sayfa bulunamadı.',
            'keywords' => 'hata, 404, bulunamadı'
        ],
        '403' => [
            'title' => 'Erişim Engellendi - Necat Derneği',
            'description' => 'Bu sayfaya erişim izniniz bulunmuyor.',
            'keywords' => 'hata, 403, izin yok'
        ],
        '500' => [
            'title' => 'Sunucu Hatası - Necat Derneği',
            'description' => 'Bir sunucu hatası oluştu. Lütfen daha sonra tekrar deneyin.',
            'keywords' => 'hata, 500, sunucu hatası'
        ]
    ];
    
    return $pages[$page] ?? $pages['home'];
}

// XSS koruması için çıktı temizleme
function clean_output($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// URL güvenli temizleme
function clean_url($url) {
    return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
}

// CSRF token oluştur
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF token doğrula
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Dosya yükleme güvenlik kontrolü
function validate_file_upload($file) {
    $errors = [];
    
    // Dosya var mı kontrol et
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Dosya yükleme hatası.';
        return $errors;
    }
    
    // Dosya boyutu kontrolü
    if ($file['size'] > MAX_FILE_SIZE) {
        $errors[] = 'Dosya boyutu çok büyük. Maksimum ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB olmalıdır.';
    }
    
    // Dosya uzantısı kontrolü
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, ALLOWED_EXTENSIONS)) {
        $errors[] = 'İzin verilmeyen dosya türü. İzin verilen türler: ' . implode(', ', ALLOWED_EXTENSIONS);
    }
    
    // MIME type kontrolü
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowed_mimes = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'
    ];
    
    if (!in_array($mime_type, $allowed_mimes)) {
        $errors[] = 'Geçersiz dosya türü.';
    }
    
    return $errors;
}

// Güvenli dosya adı oluştur
function generate_safe_filename($original_name) {
    $extension = pathinfo($original_name, PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    return $filename;
}

// E-posta gönderme fonksiyonu
function send_email($to, $subject, $message, $headers = '') {
    // Global PDO bağlantısını kullan
    global $pdo;
    
    // PHPMailer sınıfını kullanmak için EmailService'i çağır
    require_once __DIR__ . '/EmailService.php';
    $emailService = new EmailService($pdo);
    
    // Test e-postası gönder
    return $emailService->sendTestEmail($to, $subject, $message);
}

// Admin giriş kontrolü
function is_admin_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Admin girişi zorunlu kıl
function require_admin_login() {
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

// Tarih formatla
function format_date($date, $format = 'd.m.Y') {
    return date($format, strtotime($date));
}

// Metin kısalt
function truncate_text($text, $length = 100) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

// Enhanced error handling and logging
function log_error($message, $file = '', $line = '') {
    $log_dir = __DIR__ . '/../logs/';
    
    // Log dizini yoksa oluştur
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $log_message = "[" . date('Y-m-d H:i:s') . "] ";
    $log_message .= "Error: " . $message;
    if ($file) $log_message .= " in " . $file;
    if ($line) $log_message .= " on line " . $line;
    $log_message .= " | IP: " . get_client_ip() . "\n";
    
    error_log($log_message, 3, $log_dir . "error.log");
}

// Get client IP address
function get_client_ip() {
    $ip_keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

// Database connection with error handling
function get_db_connection() {
    // Global PDO bağlantısını kullan
    global $pdo;
    
    if ($pdo !== null) {
        return $pdo;
    }
    
    try {
        // Veritabanı yapılandırması zaten config/database.php içinde tanımlanmıştır
        return $pdo;
    } catch(PDOException $e) {
        log_error("Database connection failed: " . $e->getMessage());
        throw new Exception("Database connection failed");
    }
}

// Safe database query execution
function execute_query($sql, $params = []) {
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        log_error("Database query failed: " . $e->getMessage() . " | SQL: " . $sql);
        throw new Exception("Database query failed");
    }
}

// Dosya yükleme için absolute path döndürür
function getUploadsPath($subFolder = '') {
    $basePath = dirname(__DIR__) . '/uploads/';
    
    // Upload dizini yoksa oluştur
    if (!is_dir($basePath)) {
        mkdir($basePath, 0755, true);
    }
    
    if ($subFolder) {
        $subFolder = trim($subFolder, '/');
        $fullPath = $basePath . $subFolder . '/';
        
        // Alt klasör yoksa oluştur
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
        
        return $fullPath;
    }
    
    return $basePath;
}

/**
 * Güvenli dosya upload işlemi
 */
function handleFileUpload($file, $subFolder = '', $allowedTypes = []) {
    $result = ['success' => false, 'filename' => '', 'error' => ''];
    
    // Default allowed types
    if (empty($allowedTypes)) {
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    }
    
    // File validation
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $result['error'] = 'Dosya yükleme hatası: ' . $file['error'];
        return $result;
    }
    
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedTypes)) {
        $result['error'] = 'Bu dosya türü desteklenmiyor.';
        return $result;
    }
    
    // Generate unique filename
    $filename = uniqid() . '_' . time() . '.' . $fileExtension;
    $uploadPath = getUploadsPath($subFolder);
    
    $fullPath = $uploadPath . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $fullPath)) {
        $result['success'] = true;
        $result['filename'] = $filename;
    } else {
        $result['error'] = 'Dosya kaydedilemedi.';
    }
    
    return $result;
}

// Response helpers
function json_response($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function redirect_with_message($url, $message, $type = 'success') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
    header("Location: $url");
    exit;
}

function get_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}

// Security functions
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validatePhone($phone) {
    // Basit telefon validasyonu
    $phone = preg_replace('/[^0-9+]/', '', $phone);
    return strlen($phone) >= 10;
}

function generateCSRFToken() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

function validateCSRFToken($token) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Performance monitoring
function start_timer($name) {
    $GLOBALS['timers'][$name] = microtime(true);
}

function end_timer($name) {
    if (isset($GLOBALS['timers'][$name])) {
        $duration = microtime(true) - $GLOBALS['timers'][$name];
        return round($duration * 1000, 2); // Return in milliseconds
    }
    return null;
}
?>
