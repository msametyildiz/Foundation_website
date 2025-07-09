<?php
// Sunucu Debug - Router sorunu tespiti
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Sunucu Debug</title><meta charset='UTF-8'>";
echo "<style>body{font-family:Arial;margin:20px;background:#f5f5f5;} .ok{color:#28a745;} .error{color:#dc3545;} .box{background:white;padding:15px;margin:10px 0;border-radius:8px;}</style>";
echo "</head><body>";

echo "<h1>🔍 Sunucu Debug Raporu</h1>";

// 1. Temel sunucu bilgileri
echo "<div class='box'>";
echo "<h3>1. Sunucu Bilgileri</h3>";
echo "<p><strong>PHP Sürümü:</strong> " . phpversion() . "</p>";
echo "<p><strong>Sunucu:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Bilinmiyor') . "</p>";
echo "<p><strong>SERVER_NAME:</strong> " . ($_SERVER['SERVER_NAME'] ?? 'Bilinmiyor') . "</p>";
echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'Bilinmiyor') . "</p>";
echo "<p><strong>DOCUMENT_ROOT:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Bilinmiyor') . "</p>";
echo "</div>";

// 2. mod_rewrite testi
echo "<div class='box'>";
echo "<h3>2. mod_rewrite Kontrolü</h3>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "<p class='" . (in_array('mod_rewrite', $modules) ? 'ok' : 'error') . "'>";
    echo "<strong>mod_rewrite:</strong> " . (in_array('mod_rewrite', $modules) ? '✅ Aktif' : '❌ Kapalı');
    echo "</p>";
} else {
    echo "<p><strong>mod_rewrite:</strong> ❓ Tespit edilemiyor</p>";
}

echo "<p><strong>.htaccess dosyası:</strong> " . (file_exists('.htaccess') ? '✅ Mevcut' : '❌ Yok') . "</p>";
echo "</div>";

// 3. Dosya kontrolü
echo "<div class='box'>";
echo "<h3>3. Dosya Kontrolü</h3>";
$files = [
    'config/database.php',
    'includes/functions.php', 
    'includes/Router.php',
    'includes/header.php',
    'includes/footer.php',
    'pages/home.php',
    'pages/about.php',
    'pages/contact.php'
];

foreach ($files as $file) {
    $exists = file_exists($file);
    echo "<p class='" . ($exists ? 'ok' : 'error') . "'>";
    echo "<strong>$file:</strong> " . ($exists ? '✅ Mevcut' : '❌ Yok');
    echo "</p>";
}
echo "</div>";

// 4. Database bağlantı testi
echo "<div class='box'>";
echo "<h3>4. Veritabanı Testi</h3>";
try {
    require_once 'config/database.php';
    echo "<p class='ok'>✅ Database config yüklendi</p>";
    
    if (isset($pdo)) {
        echo "<p class='ok'>✅ PDO bağlantısı var</p>";
        
        // Settings tablosu kontrolü
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM settings");
        $result = $stmt->fetch();
        echo "<p class='ok'>✅ Settings tablosunda " . $result['count'] . " kayıt</p>";
    } else {
        echo "<p class='error'>❌ PDO bağlantısı yok</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Database hatası: " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo "</div>";

// 5. Functions test
echo "<div class='box'>";
echo "<h3>5. Functions Testi</h3>";
try {
    if (!function_exists('site_url')) {
        require_once 'includes/functions.php';
    }
    echo "<p class='ok'>✅ functions.php yüklendi</p>";
    
    echo "<p><strong>site_url():</strong> " . site_url() . "</p>";
    echo "<p><strong>site_url('about'):</strong> " . site_url('about') . "</p>";
    echo "<p><strong>get_current_page():</strong> " . get_current_page() . "</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Functions hatası: " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo "</div>";

// 6. Router test
echo "<div class='box'>";
echo "<h3>6. Router Testi</h3>";
try {
    if (!class_exists('Router')) {
        require_once 'includes/Router.php';
    }
    
    $router = new Router();
    $router->add('home', 'pages/home.php')
           ->add('about', 'pages/about.php')
           ->add('contact', 'pages/contact.php');
    
    echo "<p class='ok'>✅ Router sınıfı yüklendi</p>";
    echo "<p><strong>dispatch('home'):</strong> " . $router->dispatch('home') . "</p>";
    echo "<p><strong>dispatch('about'):</strong> " . $router->dispatch('about') . "</p>";
    echo "<p><strong>dispatch('xyz'):</strong> " . $router->dispatch('xyz') . "</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Router hatası: " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo "</div>";

// 7. Test linkleri
echo "<div class='box'>";
echo "<h3>7. Test Linkleri</h3>";
echo "<p>Bu linkleri tıklayarak test edin:</p>";
echo "<ul>";
echo "<li><a href='/'>Ana Sayfa (/)</a></li>";
echo "<li><a href='/about'>Hakkımızda (/about)</a></li>";
echo "<li><a href='/contact'>İletişim (/contact)</a></li>";
echo "<li><a href='/projects'>Projeler (/projects)</a></li>";
echo "<li><a href='index.php'>index.php (eski yöntem)</a></li>";
echo "<li><a href='index.php?page=about'>index.php?page=about (eski yöntem)</a></li>";
echo "</ul>";
echo "</div>";

echo "<div class='box'>";
echo "<h3>8. Çözüm Önerileri</h3>";
echo "<p><strong>Eğer Router çalışmıyorsa:</strong></p>";
echo "<ol>";
echo "<li>mod_rewrite aktif değilse hosting sağlayıcısından aktif etmesini isteyin</li>";
echo "<li>.htaccess kuralları çalışmıyorsa basit yönlendirme sistemine geri dönün</li>";
echo "<li>Footer görünmüyorsa database bağlantısı ve include dosyalarını kontrol edin</li>";
echo "</ol>";
echo "</div>";

echo "</body></html>";
?> 