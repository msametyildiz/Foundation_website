<?php
// Basit teşhis scripti - Veritabanı bağlantısını test et

// Hata raporlama ayarları
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Başlık
echo "<h1>Veritabanı Bağlantı Testi</h1>";

// Ortam tespiti
echo "<h2>Sunucu Ortamı Tespiti</h2>";
echo "<pre>";
echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'tanımsız') . "\n";
echo "SERVER_ADDR: " . ($_SERVER['SERVER_ADDR'] ?? 'tanımsız') . "\n";
echo "PHP_VERSION: " . phpversion() . "\n";
echo "OS: " . PHP_OS . "\n";
echo "</pre>";

// Ortam tespiti kodu
$environment = (isset($_SERVER['SERVER_NAME']) && 
             ($_SERVER['SERVER_NAME'] == 'localhost' || 
              $_SERVER['SERVER_NAME'] == '127.0.0.1' ||
              strpos($_SERVER['SERVER_NAME'] ?? '', '.local') !== false)) 
             ? 'development' : 'production';

echo "<p>Tespit edilen ortam: <strong>" . $environment . "</strong></p>";

// Veritabanı ayarları
echo "<h2>Veritabanı Ayarları</h2>";
if ($environment == 'development') {
    $db_host = 'localhost';
    $db_name = 'necat_dernegi';
    $db_user = 'necat_user';
    $db_pass = 'necat_password123'; // Gerçek bir teşhis aracında şifreyi gizlemek daha iyidir
} else {
    $db_host = 'localhost';
    $db_name = 'necatder_necat';  
    $db_user = 'necatder_admin';  
    $db_pass = '12zY*56&opQ';     // Gerçek bir teşhis aracında şifreyi gizlemek daha iyidir
}

echo "<pre>";
echo "DB_HOST: $db_host\n";
echo "DB_NAME: $db_name\n";
echo "DB_USER: $db_user\n";
echo "DB_PASS: " . str_repeat('*', strlen($db_pass)) . "\n";
echo "</pre>";

// PDO bağlantısını test et
echo "<h2>PDO Bağlantı Testi</h2>";
try {
    $start_time = microtime(true);
    
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_FOUND_ROWS => true
        ]
    );
    
    $end_time = microtime(true);
    $connection_time = round(($end_time - $start_time) * 1000, 2);
    
    echo "<div style='color: green; font-weight: bold;'>";
    echo "✅ PDO Bağlantısı başarılı! ($connection_time ms)";
    echo "</div>";
    
    // MySQL sürümünü göster
    $stmt = $pdo->query('SELECT VERSION() as version');
    $row = $stmt->fetch();
    echo "<p>MySQL Sürümü: " . $row['version'] . "</p>";
    
    // Tabloları listele
    echo "<h3>Veritabanı Tabloları:</h3>";
    $stmt = $pdo->query('SHOW TABLES');
    echo "<ul>";
    while ($row = $stmt->fetch()) {
        $table = reset($row); // İlk değeri al
        echo "<li>" . htmlspecialchars($table) . "</li>";
    }
    echo "</ul>";
    
    // Settings tablosunu kontrol et
    echo "<h3>Settings Tablosu Kontrolü:</h3>";
    try {
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM settings');
        $row = $stmt->fetch();
        echo "<p>Settings tablosundaki kayıt sayısı: " . $row['count'] . "</p>";
        
        $stmt = $pdo->query('SELECT setting_key, setting_value FROM settings LIMIT 5');
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Key</th><th>Value</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['setting_key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['setting_value']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "<div style='color: red;'>";
        echo "❌ Settings tablosu kontrolü başarısız: " . $e->getMessage();
        echo "</div>";
    }
    
    // contact_info_cards tablosunu kontrol et
    echo "<h3>contact_info_cards Tablosu Kontrolü:</h3>";
    try {
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM contact_info_cards');
        $row = $stmt->fetch();
        echo "<p>contact_info_cards tablosundaki kayıt sayısı: " . $row['count'] . "</p>";
        
        if ($row['count'] > 0) {
            $stmt = $pdo->query('SELECT title, icon FROM contact_info_cards LIMIT 3');
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Title</th><th>Icon</th></tr>";
            while ($row = $stmt->fetch()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>" . htmlspecialchars($row['icon']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Tabloda hiç kayıt yok.</p>";
        }
    } catch (PDOException $e) {
        echo "<div style='color: red;'>";
        echo "❌ contact_info_cards tablosu kontrolü başarısız: " . $e->getMessage();
        echo "</div>";
        
        // Tablo yapısını kontrol et
        echo "<p>Tablo yapısı kontrol ediliyor...</p>";
        try {
            $stmt = $pdo->query('SHOW CREATE TABLE contact_info_cards');
            $row = $stmt->fetch();
            echo "<pre>" . htmlspecialchars($row['Create Table'] ?? $row[1]) . "</pre>";
        } catch (PDOException $e) {
            echo "<p>Tablo mevcut değil veya erişim hatası: " . $e->getMessage() . "</p>";
            
            // Tablo oluştur önerisi
            echo "<h4>Tablo Oluşturma Önerisi:</h4>";
            echo "<pre>
CREATE TABLE `contact_info_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fas fa-info-circle',
  `button_text` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'external',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
</pre>";
        }
    }
    
} catch (PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>";
    echo "❌ PDO Bağlantısı başarısız: " . $e->getMessage();
    echo "</div>";
    
    echo "<h3>Veritabanı Sorun Giderme İpuçları:</h3>";
    echo "<ol>";
    echo "<li>Veritabanı kullanıcı adı ve şifresinin doğru olduğundan emin olun.</li>";
    echo "<li>Veritabanının mevcut olduğunu kontrol edin.</li>";
    echo "<li>Veritabanı kullanıcısının yeterli izinlere sahip olduğunu kontrol edin.</li>";
    echo "<li>MySQL servisinin çalıştığını kontrol edin.</li>";
    echo "<li>Hosting kontrol panelinden veritabanı ayarlarını gözden geçirin.</li>";
    echo "</ol>";
}

// PDO bağlantısı olmadan direkt MySQL bağlantısı deneyin
echo "<h2>mysqli Bağlantı Testi (Alternatif)</h2>";
try {
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    // Bağlantı hatasını kontrol et
    if ($mysqli->connect_error) {
        throw new Exception("Bağlantı hatası: " . $mysqli->connect_error);
    }
    
    echo "<div style='color: green; font-weight: bold;'>";
    echo "✅ mysqli Bağlantısı başarılı!";
    echo "</div>";
    
    // mysqli'yi kapat
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<div style='color: red; font-weight: bold;'>";
    echo "❌ mysqli Bağlantısı başarısız: " . $e->getMessage();
    echo "</div>";
}

// phpinfo() bilgisi
echo "<h2>PHP Yapılandırma Bilgileri</h2>";
echo "<p>Aşağıdaki butona tıklayarak PHP yapılandırma bilgilerini görüntüleyebilirsiniz.</p>";
?>
<button onclick="document.getElementById('phpinfo').style.display='block';">PHP Bilgilerini Göster</button>
<div id="phpinfo" style="display:none;">
    <?php phpinfo(); ?>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        line-height: 1.6;
    }
    h1 {
        color: #4ea674;
        border-bottom: 2px solid #4ea674;
        padding-bottom: 10px;
    }
    h2 {
        color: #3d8760;
        margin-top: 30px;
        border-left: 5px solid #3d8760;
        padding-left: 10px;
    }
    h3 {
        color: #666;
    }
    pre {
        background: #f5f5f5;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        overflow: auto;
    }
    div[style*="green"] {
        background: #e6ffe6;
        padding: 10px;
        border-radius: 5px;
        margin: 10px 0;
    }
    div[style*="red"] {
        background: #ffe6e6;
        padding: 10px;
        border-radius: 5px;
        margin: 10px 0;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin: 15px 0;
    }
    th, td {
        text-align: left;
        padding: 12px;
        border: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }
    button {
        background: #4ea674;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    button:hover {
        background: #3d8760;
    }
    #phpinfo {
        margin-top: 20px;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 5px;
    }
</style> 