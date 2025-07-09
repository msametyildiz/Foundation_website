<?php
// URL Debug - Sunucuda URL routing testi
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><title>URL Debug</title><meta charset='UTF-8'>";
echo "<style>body{font-family:Arial;margin:20px;} .test{background:#f5f5f5;padding:15px;margin:10px 0;border-radius:5px;} .success{border-left:4px solid #28a745;} .error{border-left:4px solid #dc3545;}</style>";
echo "</head><body>";

echo "<h1>🔍 URL Routing Debug</h1>";

// Temel bilgiler
echo "<div class='test success'>";
echo "<h3>1. Sunucu Bilgileri</h3>";
echo "<p><strong>HTTP_HOST:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'Tanımsız') . "</p>";
echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'Tanımsız') . "</p>";
echo "<p><strong>SERVER_NAME:</strong> " . ($_SERVER['SERVER_NAME'] ?? 'Tanımsız') . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'Tanımsız') . "</p>";
echo "<p><strong>PHP_SELF:</strong> " . ($_SERVER['PHP_SELF'] ?? 'Tanımsız') . "</p>";
echo "</div>";

// mod_rewrite kontrolü
echo "<div class='test " . (getenv('HTTP_MOD_REWRITE') == 'On' ? 'success' : 'error') . "'>";
echo "<h3>2. mod_rewrite Kontrolü</h3>";
echo "<p><strong>mod_rewrite:</strong> " . (getenv('HTTP_MOD_REWRITE') == 'On' ? '✅ Aktif' : '❓ Bilinmiyor/Kapalı') . "</p>";
echo "<p><strong>.htaccess:</strong> " . (file_exists('.htaccess') ? '✅ Mevcut' : '❌ Yok') . "</p>";
echo "</div>";

// Functions.php test
echo "<div class='test'>";
echo "<h3>3. Functions.php Test</h3>";
if (file_exists('includes/functions.php')) {
    require_once 'includes/functions.php';
    echo "<p>✅ functions.php yüklendi</p>";
    
    if (function_exists('site_url')) {
        echo "<p>✅ site_url() fonksiyonu mevcut</p>";
        echo "<p><strong>site_url() =</strong> " . site_url() . "</p>";
        echo "<p><strong>site_url('about') =</strong> " . site_url('about') . "</p>";
        echo "<p><strong>site_url('contact') =</strong> " . site_url('contact') . "</p>";
    } else {
        echo "<p>❌ site_url() fonksiyonu bulunamadı</p>";
    }
    
    if (function_exists('get_current_page')) {
        echo "<p>✅ get_current_page() fonksiyonu mevcut</p>";
        echo "<p><strong>get_current_page() =</strong> " . get_current_page() . "</p>";
    } else {
        echo "<p>❌ get_current_page() fonksiyonu bulunamadı</p>";
    }
} else {
    echo "<p>❌ functions.php bulunamadı</p>";
}
echo "</div>";

// Router test
echo "<div class='test'>";
echo "<h3>4. Router Test</h3>";
if (file_exists('includes/Router.php')) {
    require_once 'includes/Router.php';
    echo "<p>✅ Router.php yüklendi</p>";
    
    $router = new Router();
    $router->add('home', 'pages/home.php')
           ->add('about', 'pages/about.php')
           ->add('contact', 'pages/contact.php')
           ->add('projects', 'pages/projects.php');
    
    echo "<p><strong>Router dispatch('home') =</strong> " . $router->dispatch('home') . "</p>";
    echo "<p><strong>Router dispatch('about') =</strong> " . $router->dispatch('about') . "</p>";
    echo "<p><strong>Router dispatch('contact') =</strong> " . $router->dispatch('contact') . "</p>";
    echo "<p><strong>Router dispatch('xyz') =</strong> " . $router->dispatch('xyz') . "</p>";
} else {
    echo "<p>❌ Router.php bulunamadı</p>";
}
echo "</div>";

// Test URL'leri oluştur
echo "<div class='test'>";
echo "<h3>5. Test URL'leri</h3>";
echo "<p>Bu linkler temiz URL'ler ile çalışmalı:</p>";
echo "<ul>";
echo "<li><a href='" . site_url() . "'>Ana Sayfa (site_url())</a></li>";
echo "<li><a href='" . site_url('about') . "'>Hakkımızda (site_url('about'))</a></li>";
echo "<li><a href='" . site_url('contact') . "'>İletişim (site_url('contact'))</a></li>";
echo "<li><a href='" . site_url('projects') . "'>Projeler (site_url('projects'))</a></li>";
echo "</ul>";
echo "</div>";

// Var olan sayfaları kontrol et
echo "<div class='test'>";
echo "<h3>6. Sayfa Dosyaları Kontrolü</h3>";
$pages = ['home', 'about', 'contact', 'projects', 'volunteer', 'faq', 'donate'];
foreach ($pages as $page) {
    $file = "pages/{$page}.php";
    $exists = file_exists($file);
    echo "<p " . ($exists ? "class='success'" : "class='error'") . "><strong>{$page}.php:</strong> " . ($exists ? '✅ Mevcut' : '❌ Yok') . "</p>";
}
echo "</div>";

// Gerçek test
echo "<div class='test'>";
echo "<h3>7. Gerçek URL Testi</h3>";
echo "<p>Aşağıdaki URL'leri tarayıcıda test edin:</p>";
echo "<ul>";
echo "<li><code>https://necatdernegi.org.tr/</code> (Ana sayfa)</li>";
echo "<li><code>https://necatdernegi.org.tr/about</code> (Hakkımızda)</li>";
echo "<li><code>https://necatdernegi.org.tr/contact</code> (İletişim)</li>";
echo "<li><code>https://necatdernegi.org.tr/projects</code> (Projeler)</li>";
echo "</ul>";
echo "</div>";

echo "<h2>✅ Debug Tamamlandı</h2>";
echo "<p>Bu dosyayı sunucuya yükleyip test edin. Hatalar varsa yukarıdaki bilgileri kontrol edin.</p>";

echo "</body></html>";
?> 