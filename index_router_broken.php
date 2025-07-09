<?php
// Output buffering başlat (header sorunları için)
ob_start();

// Hata raporlama ayarları - Üretim ortamında kapalı, geliştirme ortamında açık
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
require_once 'includes/Router.php';

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

// Router ayarları - SEO dostu URL'ler için
$router = new Router();
$router->add('home', 'pages/home.php')
       ->add('about', 'pages/about.php')
       ->add('projects', 'pages/projects.php')
       ->add('donate', 'pages/donate.php')
       ->add('volunteer', 'pages/volunteer.php')
       ->add('faq', 'pages/faq.php')
       ->add('contact', 'pages/contact.php')
       ->add('press', 'pages/press.php')
       ->add('documents', 'pages/documents.php')
       ->add('team', 'pages/team.php')
       ->add('404', 'pages/404.php')
       ->add('403', 'pages/403.php')
       ->add('500', 'pages/500.php')
       ->setNotFound('pages/404.php');

// URL'den sayfa yolunu al
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// index.php'yi kaldır
$path = str_replace('index.php', '', $path);
$path = trim($path, '/');

// Sayfa adını belirle
$page = get_current_page();

// Eğer path boşsa home sayfası
if (empty($path) || $path === 'index.php') {
    $page = 'home';
}

// Güvenlik: sadece izin verilen sayfalar
$allowed_pages = ['home', 'about', 'projects', 'donate', 'volunteer', 'faq', 'contact', 'press', 'documents', 'team', '404', '403', '500'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

// Router ile sayfa dosyasını belirle
$page_file = $router->dispatch($page);

// Debug için (üretimde kaldırılacak)
if ($environment == 'development') {
    error_log("Current page: $page, Page file: $page_file, REQUEST_URI: " . $_SERVER['REQUEST_URI']);
}

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
    include 'includes/footer.php';
} catch (Exception $e) {
    error_log("Footer include error: " . $e->getMessage());
    echo "</body></html>";
}

// Output buffer'ı temizle ve gönder
ob_end_flush();
?>
