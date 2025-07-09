<?php
// Basit Router Test
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Router Test</title><meta charset='UTF-8'></head><body>";

echo "<h1>🧪 Router Test</h1>";

// Temel bilgiler
echo "<h2>1. Temel Bilgiler</h2>";
echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";

// Functions test
echo "<h2>2. Functions Test</h2>";
try {
    require_once 'includes/functions.php';
    echo "<p>✅ functions.php yüklendi</p>";
    echo "<p><strong>site_url() =</strong> " . site_url() . "</p>";
    echo "<p><strong>site_url('about') =</strong> " . site_url('about') . "</p>";
} catch (Exception $e) {
    echo "<p>❌ Hata: " . $e->getMessage() . "</p>";
}

// Router test
echo "<h2>3. Router Test</h2>";
try {
    require_once 'includes/Router.php';
    $router = new Router();
    $router->add('home', 'pages/home.php')
           ->add('about', 'pages/about.php');
    
    echo "<p>✅ Router yüklendi</p>";
    echo "<p><strong>dispatch('home') =</strong> " . $router->dispatch('home') . "</p>";
    echo "<p><strong>dispatch('about') =</strong> " . $router->dispatch('about') . "</p>";
} catch (Exception $e) {
    echo "<p>❌ Router Hatası: " . $e->getMessage() . "</p>";
}

// Link testleri
echo "<h2>4. Link Testleri</h2>";
echo "<ul>";
echo "<li><a href='" . site_url() . "'>Ana Sayfa</a></li>";
echo "<li><a href='" . site_url('about') . "'>Hakkımızda</a></li>";
echo "<li><a href='" . site_url('contact') . "'>İletişim</a></li>";
echo "</ul>";

echo "</body></html>";
ob_end_flush();
?> 