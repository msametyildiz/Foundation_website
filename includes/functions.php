<?php
/**
 * Genel yardımcı fonksiyonlar
 */

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
        ]
    ];
    
    return $pages[$page] ?? $pages['home'];
}

// XSS koruması için çıktı temizleme
function clean_output($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
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
    $default_headers = "From: " . SITE_EMAIL . "\r\n";
    $default_headers .= "Reply-To: " . SITE_EMAIL . "\r\n";
    $default_headers .= "MIME-Version: 1.0\r\n";
    $default_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    $headers = $headers ? $headers : $default_headers;
    
    return mail($to, $subject, $message, $headers);
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

// Aktif menü kontrolü
function is_active_page($page) {
    $current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
    return $current_page === $page ? 'active' : '';
}

// Enhanced error handling and logging
function log_error($message, $file = '', $line = '') {
    $log_message = "[" . date('Y-m-d H:i:s') . "] ";
    $log_message .= "Error: " . $message;
    if ($file) $log_message .= " in " . $file;
    if ($line) $log_message .= " on line " . $line;
    $log_message .= " | IP: " . get_client_ip() . "\n";
    
    error_log($log_message, 3, "logs/error.log");
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

// Rate limiting function
function check_rate_limit($identifier, $max_attempts = 5, $time_window = 300) {
    $rate_limit_file = 'logs/rate_limit.json';
    
    if (!file_exists('logs')) {
        mkdir('logs', 0755, true);
    }
    
    $rate_data = [];
    if (file_exists($rate_limit_file)) {
        $rate_data = json_decode(file_get_contents($rate_limit_file), true) ?? [];
    }
    
    $current_time = time();
    $identifier_key = md5($identifier);
    
    // Clean old entries
    foreach ($rate_data as $key => $data) {
        if ($current_time - $data['last_attempt'] > $time_window) {
            unset($rate_data[$key]);
        }
    }
    
    // Check current identifier
    if (!isset($rate_data[$identifier_key])) {
        $rate_data[$identifier_key] = ['attempts' => 1, 'last_attempt' => $current_time];
    } else {
        $rate_data[$identifier_key]['attempts']++;
        $rate_data[$identifier_key]['last_attempt'] = $current_time;
    }
    
    // Save rate data
    file_put_contents($rate_limit_file, json_encode($rate_data));
    
    return $rate_data[$identifier_key]['attempts'] <= $max_attempts;
}

// Enhanced input validation
function validate_input($input, $type, $options = []) {
    $input = trim($input);
    
    switch ($type) {
        case 'email':
            return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
        
        case 'phone':
            return preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $input);
        
        case 'name':
            $min_length = $options['min_length'] ?? 2;
            $max_length = $options['max_length'] ?? 50;
            return strlen($input) >= $min_length && strlen($input) <= $max_length && 
                   preg_match('/^[a-zA-ZçğıöşüÇĞIİÖŞÜ\s]+$/', $input);
        
        case 'text':
            $min_length = $options['min_length'] ?? 1;
            $max_length = $options['max_length'] ?? 1000;
            return strlen($input) >= $min_length && strlen($input) <= $max_length;
        
        case 'number':
            $min = $options['min'] ?? 0;
            $max = $options['max'] ?? PHP_INT_MAX;
            $number = filter_var($input, FILTER_VALIDATE_INT);
            return $number !== false && $number >= $min && $number <= $max;
        
        case 'url':
            return filter_var($input, FILTER_VALIDATE_URL) !== false;
        
        default:
            return false;
    }
}

// Database connection with error handling
function get_db_connection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            log_error("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }
    
    return $pdo;
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

// Generate secure random token
function generate_secure_token($length = 32) {
    return bin2hex(random_bytes($length));
}

// Password hashing and verification
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Session security
function secure_session_start() {
    if (session_status() === PHP_SESSION_NONE) {
        // Configure session security
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
        ini_set('session.use_strict_mode', 1);
        
        session_start();
        
        // Regenerate session ID periodically
        if (!isset($_SESSION['last_regeneration'])) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        } elseif (time() - $_SESSION['last_regeneration'] > 300) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
}

// File upload with enhanced security
function secure_file_upload($file, $upload_dir, $allowed_types = []) {
    $errors = validate_file_upload($file);
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }
    
    // Additional security checks
    if (!empty($allowed_types)) {
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_types)) {
            return ['success' => false, 'errors' => ['Dosya türü izin verilmiyor.']];
        }
    }
    
    // Generate secure filename
    $safe_filename = generate_safe_filename($file['name']);
    $upload_path = $upload_dir . '/' . $safe_filename;
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        return ['success' => true, 'filename' => $safe_filename, 'path' => $upload_path];
    } else {
        return ['success' => false, 'errors' => ['Dosya yüklenemedi.']];
    }
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
