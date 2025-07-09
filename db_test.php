<?php
// Hata gösterimini aktifleştir
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Veritabanı Bağlantı Testi</h1>";

// Database.php dosyasını dahil et
require_once 'config/database.php';

// Bağlantı bilgilerini göster
echo "<h2>Bağlantı Bilgileri:</h2>";
echo "<p>Host: " . DB_HOST . "</p>";
echo "<p>Database: " . DB_NAME . "</p>";
echo "<p>User: " . DB_USER . "</p>";
echo "<p>Password: " . str_repeat('*', strlen(DB_PASS)) . "</p>";

// Bağlantı durumunu kontrol et
if (isset($pdo) && $pdo !== null) {
    echo "<p style='color:green;font-weight:bold;'>✓ PDO bağlantısı başarılı!</p>";
    
    // Settings tablosunu sorgula
    try {
        echo "<h2>Settings Tablosu Testi:</h2>";
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM settings");
        $stmt->execute();
        $result = $stmt->fetch();
        echo "<p>Settings tablosunda toplam " . $result['total'] . " kayıt bulundu.</p>";
        
        // Örnek kayıtları göster
        echo "<h3>Örnek Kayıtlar:</h3>";
        $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM settings LIMIT 10");
        $stmt->execute();
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Key</th><th>Value</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['setting_key']) . "</td>";
            echo "<td>" . htmlspecialchars(substr($row['setting_value'], 0, 100)) . (strlen($row['setting_value']) > 100 ? '...' : '') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ Settings tablosu sorgulanamadı: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:red;font-weight:bold;'>❌ PDO bağlantısı başarısız!</p>";
}

// Footer dosyasını dahil et ve içeriğini göster
echo "<h2>Footer İçeriği Test:</h2>";
echo "<div style='border:1px solid #ccc; padding:10px;'>";
ob_start();
include 'includes/footer.php';
$footer_content = ob_get_clean();
echo htmlspecialchars($footer_content);
echo "</div>";

// Error log dosyasının içeriğini göster (varsa)
$error_log_path = __DIR__ . '/logs/error.log';
if (file_exists($error_log_path)) {
    echo "<h2>Error Log İçeriği:</h2>";
    echo "<pre style='background:#f5f5f5;padding:10px;max-height:300px;overflow:auto;'>";
    echo htmlspecialchars(file_get_contents($error_log_path));
    echo "</pre>";
}
?> 