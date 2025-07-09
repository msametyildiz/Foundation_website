<?php
// Footer Debug Script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Footer Debug - Veritabanı ve Ayarlar Kontrolü</h1>";
echo "<hr>";

// 1. Veritabanı bağlantısını test et
echo "<h2>1. Veritabanı Bağlantı Testi</h2>";

$db_configs = [
    'Footer.php Bağlantısı' => [
        'host' => 'localhost',
        'name' => 'necatdernegi_necat_dernegi',
        'user' => 'necatdernegi_necat_user',
        'pass' => 'ND.NecatDernegi.user'
    ],
    'Config/Database.php Bağlantısı' => [
        'host' => DB_HOST ?? 'tanımsız',
        'name' => DB_NAME ?? 'tanımsız', 
        'user' => DB_USER ?? 'tanımsız',
        'pass' => DB_PASS ?? 'tanımsız'
    ]
];

foreach ($db_configs as $config_name => $config) {
    echo "<h3>$config_name:</h3>";
    echo "<pre>";
    echo "Host: " . $config['host'] . "\n";
    echo "Database: " . $config['name'] . "\n";
    echo "User: " . $config['user'] . "\n";
    echo "Password: " . str_repeat('*', strlen($config['pass'])) . "\n";
    echo "</pre>";
    
    try {
        $test_pdo = new PDO(
            "mysql:host={$config['host']};dbname={$config['name']};charset=utf8mb4",
            $config['user'],
            $config['pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        echo "<p style='color:green;'>✅ Bağlantı başarılı!</p>";
        
        // Settings tablosunu kontrol et
        try {
            $stmt = $test_pdo->query("SELECT COUNT(*) as count FROM settings");
            $result = $stmt->fetch();
            echo "<p>Settings tablosu: " . $result['count'] . " kayıt bulundu</p>";
            
            // Örnek kayıtları göster
            $stmt = $test_pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE '%social%' OR setting_key LIKE '%contact%' LIMIT 10");
            $settings = $stmt->fetchAll();
            
            if ($settings) {
                echo "<h4>Örnek Ayarlar:</h4>";
                echo "<table border='1' cellpadding='5'>";
                echo "<tr><th>Key</th><th>Value</th></tr>";
                foreach ($settings as $setting) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($setting['setting_key']) . "</td>";
                    echo "<td>" . htmlspecialchars($setting['setting_value']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
        } catch (PDOException $e) {
            echo "<p style='color:red;'>❌ Settings tablosu hatası: " . $e->getMessage() . "</p>";
        }
        
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ Bağlantı hatası: " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
}

// 2. Config dosyasını kontrol et
echo "<h2>2. Config/Database.php Kontrolü</h2>";
if (file_exists('config/database.php')) {
    echo "<p style='color:green;'>✅ config/database.php dosyası mevcut</p>";
    
    // Constants tanımlı mı kontrol et
    $constants = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];
    foreach ($constants as $const) {
        if (defined($const)) {
            echo "<p>✅ $const tanımlı</p>";
        } else {
            echo "<p style='color:red;'>❌ $const tanımlı değil!</p>";
        }
    }
} else {
    echo "<p style='color:red;'>❌ config/database.php dosyası bulunamadı!</p>";
}

// 3. Footer dosyasını include etmeyi dene
echo "<h2>3. Footer Include Testi</h2>";
echo "<div style='border: 2px solid #ccc; padding: 20px; margin: 20px 0;'>";
echo "<h3>Footer Çıktısı:</h3>";

// Buffer kullanarak footer'ı yükle
ob_start();
$footer_error = null;
try {
    include 'includes/footer.php';
} catch (Exception $e) {
    $footer_error = $e->getMessage();
}
$footer_content = ob_get_clean();

if ($footer_error) {
    echo "<p style='color:red;'>❌ Footer yükleme hatası: " . $footer_error . "</p>";
} else {
    echo "<p style='color:green;'>✅ Footer başarıyla yüklendi</p>";
    echo "<h4>Footer HTML uzunluğu: " . strlen($footer_content) . " karakter</h4>";
    
    // Footer'da beklenen elemanları kontrol et
    $expected_elements = [
        'footer-modern' => 'Footer container',
        'footer-brand' => 'Logo ve marka bölümü',
        'footer-links' => 'Footer linkleri',
        'footer-social' => 'Sosyal medya linkleri',
        'footer-contact-section' => 'İletişim bölümü'
    ];
    
    echo "<h4>Footer Elemanları Kontrolü:</h4>";
    foreach ($expected_elements as $class => $description) {
        if (strpos($footer_content, $class) !== false) {
            echo "<p style='color:green;'>✅ $description ($class) bulundu</p>";
        } else {
            echo "<p style='color:red;'>❌ $description ($class) bulunamadı!</p>";
        }
    }
}
echo "</div>";

// 4. Dosya yollarını kontrol et
echo "<h2>4. Dosya Yolları Kontrolü</h2>";
$paths_to_check = [
    'includes/functions.php',
    'includes/logo-base64-helper.php',
    'assets/images/logo.png',
    'assets/css/style.css',
    'assets/js/main.js'
];

foreach ($paths_to_check as $path) {
    if (file_exists($path)) {
        echo "<p style='color:green;'>✅ $path mevcut</p>";
    } else {
        echo "<p style='color:red;'>❌ $path bulunamadı!</p>";
    }
}

// 5. Logo kontrolü
echo "<h2>5. Logo Kontrolü</h2>";
if (class_exists('LogoBase64Helper')) {
    echo "<p style='color:green;'>✅ LogoBase64Helper sınıfı yüklendi</p>";
    try {
        if (method_exists('LogoBase64Helper', 'isLogoAvailable')) {
            $logo_available = LogoBase64Helper::isLogoAvailable();
            echo "<p>Logo durumu: " . ($logo_available ? "✅ Mevcut" : "❌ Mevcut değil") . "</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color:red;'>Logo kontrolü hatası: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:red;'>❌ LogoBase64Helper sınıfı bulunamadı</p>";
}

echo "<hr>";
echo "<p><strong>Debug tamamlandı.</strong> Yukarıdaki sonuçları kontrol ederek sorunu tespit edebilirsiniz.</p>";
?> 