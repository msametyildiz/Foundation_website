<?php
// Hızlı Test - Eski Sistem
echo "<!DOCTYPE html><html><head><title>Hızlı Test</title><meta charset='UTF-8'></head><body>";
echo "<h1>🧪 Hızlı Test</h1>";

// Functions test
echo "<h2>1. Functions Test</h2>";
require_once 'includes/functions.php';
echo "<p>✅ functions.php yüklendi</p>";
echo "<p><strong>site_url():</strong> " . site_url() . "</p>";
echo "<p><strong>site_url('about'):</strong> " . site_url('about') . "</p>";

// Database test
echo "<h2>2. Database Test</h2>";
try {
    require_once 'config/database.php';
    if (isset($pdo)) {
        echo "<p>✅ Database bağlantısı var</p>";
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM settings");
        $result = $stmt->fetch();
        echo "<p>✅ Settings: " . $result['count'] . " kayıt</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Database hatası: " . $e->getMessage() . "</p>";
}

// Test linkleri
echo "<h2>3. Test Linkleri</h2>";
echo "<ul>";
echo "<li><a href='index.php'>Ana Sayfa</a></li>";
echo "<li><a href='index.php?page=about'>Hakkımızda</a></li>";
echo "<li><a href='index.php?page=contact'>İletişim</a></li>";
echo "<li><a href='index.php?page=projects'>Projeler</a></li>";
echo "</ul>";

echo "</body></html>";
?> 