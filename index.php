<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Router - basit URL yönlendirme sistemi
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Güvenlik: sadece izin verilen sayfalar
$allowed_pages = ['home', 'about', 'projects', 'donate', 'volunteer', 'faq', 'contact', 'press', 'documents', 'team'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

// Sayfa başlığı ve meta bilgileri için
$page_info = getPageInfo($page);

include 'includes/header.php';

// Sayfa içeriğini yükle
$page_file = "pages/{$page}.php";
if (file_exists($page_file)) {
    include $page_file;
} else {
    include 'pages/404.php';
}

include 'includes/footer.php';
?>
