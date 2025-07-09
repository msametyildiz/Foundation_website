<?php
// Hata gösterimini aktifleştir
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Doğrudan Veritabanı Bağlantı Testi</h1>";

// Sunucu ortamı için veritabanı bilgileri
$db_host = 'localhost';
$db_name = 'necatderneki_necat_dernegi';
$db_user = 'necatderneki_necat_user';
$db_pass = 'ND.NecatDerneki.user';
$db_charset = 'utf8mb4';

echo "<h2>Bağlantı Bilgileri:</h2>";
echo "<p>Host: $db_host</p>";
echo "<p>Database: $db_name</p>";
echo "<p>User: $db_user</p>";
echo "<p>Password: " . str_repeat('*', strlen($db_pass)) . "</p>";

try {
    // PDO bağlantısını doğrudan oluştur
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=$db_charset",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    
    echo "<p style='color:green;font-weight:bold;'>✓ PDO bağlantısı başarılı!</p>";
    
    // Settings tablosunu sorgula
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
    
    // Veritabanındaki tüm tabloları listele
    echo "<h3>Veritabanındaki Tablolar:</h3>";
    $stmt = $pdo->query("SHOW TABLES");
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "<li>" . htmlspecialchars($row[0]) . "</li>";
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color:red;font-weight:bold;'>❌ PDO bağlantısı başarısız!</p>";
    echo "<p>Hata: " . $e->getMessage() . "</p>";
}
?> 