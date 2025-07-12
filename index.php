<?php
// Basit Sayfa Sistemi - Router olmadan

// Hata raporlama ayarları
$environment = ($_SERVER['SERVER_NAME'] == 'localhost' || strpos($_SERVER['SERVER_NAME'], '.local') !== false) ? 'development' : 'production';

if ($environment == 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/logs/php_errors.log');
}

// Oturum başlat
session_start();

// Gerekli dosyaları yükle
require_once 'config/database.php';
require_once 'includes/functions.php';

// Dosya yollarını kontrol et
if (!is_dir(__DIR__ . '/uploads')) {
    if (!mkdir(__DIR__ . '/uploads', 0755, true)) {
        error_log('Uploads dizini oluşturulamadı');
    }
}

if (!is_dir(__DIR__ . '/logs')) {
    if (!mkdir(__DIR__ . '/logs', 0755, true)) {
        error_log('Logs dizini oluşturulamadı');
    }
}

// Basit sayfa belirleme sistemi
$page = isset($_GET['page']) ? htmlspecialchars(trim($_GET['page']), ENT_QUOTES, 'UTF-8') : 'home';

// Güvenlik: sadece izin verilen sayfalar
$allowed_pages = ['home', 'about', 'projects', 'donate', 'volunteer', 'faq', 'contact', 'press', 'documents', 'team', '404', '403', '500'];

// Script dosyası talebi kontrolü
if (strpos($page, 'script_') === 0 || pathinfo($page, PATHINFO_EXTENSION) === 'js') {
    // JavaScript dosyası talep ediliyor, doğrudan dosyayı göster
    $script_file = "scripts/" . basename($page);
    if (file_exists($script_file)) {
        header('Content-Type: application/javascript');
        readfile($script_file);
        exit;
    } else {
        // Dosya bulunamadı, 404 sayfasına yönlendir
        $page = '404';
    }
}

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

// Sayfa dosyasını belirle
$page_file = "pages/{$page}.php";

// Sayfa başlığı ve meta bilgileri için
try {
    $page_info = getPageInfo($page);
} catch (Exception $e) {
    error_log("getPageInfo error: " . $e->getMessage());
    $page_info = [
        'title' => 'Necat Derneği',
        'description' => 'Elinizi İyilik İçin Uzatın',
        'keywords' => 'necat derneği, yardım, bağış, sosyal sorumluluk'
    ];
}

// Header'ı ekle
try {
    include 'includes/header.php';
} catch (Exception $e) {
    error_log("Header include error: " . $e->getMessage());
    echo "<!DOCTYPE html><html lang='tr'><head><meta charset='UTF-8'><title>Necat Derneği</title></head><body>";
}

// Sayfa içeriğini yükle
if (file_exists($page_file)) {
    try {
        include $page_file;
    } catch (Exception $e) {
        error_log("Page include error ({$page}): " . $e->getMessage());
        include 'pages/404.php';
    }
} else {
    include 'pages/404.php';
}

// Footer'ı ekle
try {
    // Pass environment variable to footer
    $footer_environment = $environment;
    include 'includes/footer.php';
} catch (Exception $e) {
    error_log("Footer include error: " . $e->getMessage());
    echo "</body></html>";
}

// Output buffer'ı temizle ve gönder
?>