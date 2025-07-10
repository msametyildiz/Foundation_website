<?php
// Ortam tespiti (development veya production)
$environment = 'development'; // Varsayılan değer

// Komut satırından çalıştırılıyor mu kontrol et
if (php_sapi_name() === 'cli') {
    // CLI ortamında development olarak kabul et
    $environment = 'development';
} else {
    // Server name değerini al ve ekstra log ekle
    $serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'undefined';
    $httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'undefined';
    error_log("Server detection - SERVER_NAME: $serverName, HTTP_HOST: $httpHost");
    
    // Web ortamında sunucu adına göre tespit et - cPanel uyumluluğu için genişletildi
    if ($serverName == 'localhost' || $serverName == '127.0.0.1' || 
        strpos($serverName, 'local.') === 0 || strpos($httpHost, 'localhost') !== false) {
        $environment = 'development';
    } else {
        $environment = 'production';
    }
    
    // Ortam bilgisini logla
    error_log("Detected environment: " . $environment . " (SERVER_NAME: " . $serverName . ", HTTP_HOST: " . $httpHost . ")");
}

// Ortama göre bağlantı ayarları
if ($environment == 'development') {
    // Yerel geliştirme ortamı ayarları
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'necat_dernegi');
    define('DB_USER', 'necat_user');
    define('DB_PASS', 'necat_password123');
    define('DB_CHARSET', 'utf8mb4');
    
    // Site ayarları - Geliştirme ortamı
    define('SITE_URL', 'http://localhost:8080');
    define('SITE_NAME', 'Necat Derneği');
    define('SITE_EMAIL', 'info@necatdernegi.org');
    define('ADMIN_EMAIL', 'admin@necatdernegi.org');
} else {
    // cPanel/Üretim ortamı ayarları
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'necatderneki_necat_dernegi');
    define('DB_USER', 'necatderneki_necat_user');
    define('DB_PASS', 'ND.NecatDerneki.user');
    define('DB_CHARSET', 'utf8mb4');
    
    // Site ayarları - Üretim ortamı
    define('SITE_URL', 'https://www.necatdernegi.org');
    define('SITE_NAME', 'Necat Derneği');
    define('SITE_EMAIL', 'info@necatdernegi.org');
    define('ADMIN_EMAIL', 'admin@necatdernegi.org');
}

// Dosya yükleme ayarları
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf']);

// Güvenlik anahtarı - Rastgele ve güçlü bir anahtar oluşturun
define('SECRET_KEY', 'necat_dernegi_secure_key_2025_XmNp8zQ4fT9cB3vA');

// Varsayılan istatistik değerleri
define('DEFAULT_PROJECTS_COUNT', 100);
define('DEFAULT_VOLUNTEERS_COUNT', 26);
define('DEFAULT_FAMILIES_COUNT', 5001);
define('DEFAULT_DONATIONS_AMOUNT', 500000);

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_FOUND_ROWS => true,  // cPanel/MySQL uyumluluğu için
            PDO::ATTR_TIMEOUT => 5, // Bağlantı zaman aşımını 5 saniye olarak ayarla
            PDO::ATTR_PERSISTENT => false // Kalıcı bağlantıları devre dışı bırak
        ]
    );
    
    // Eski kod ile uyumluluk için $db değişkeni
    $db = $pdo;
    
    // Bağlantı başarılı olduğunu logla
    error_log("Database connection successful in " . $environment . " environment");
    
    // Bağlantıyı test et
    $testStmt = $pdo->query("SELECT 1");
    if (!$testStmt) {
        throw new PDOException("Database connection test failed");
    }
} catch (PDOException $e) {
    // Hatayı logla ama kullanıcıya detay gösterme (güvenlik için)
    error_log("Veritabanı bağlantı hatası (" . $environment . " environment): " . $e->getMessage());
    
    // Veritabanı bağlantısı başarısız olduğunda global değişkeni ayarla
    $GLOBALS['db_connection_failed'] = true;
    
    if ($environment == 'development') {
        // Geliştirme ortamında hata detayını göster
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb; border-radius: 4px;'>";
        echo "<strong>Veritabanı bağlantı hatası:</strong> " . $e->getMessage();
        echo "</div>";
    } else {
        // Üretim ortamında daha güvenli hata mesajı
        error_log("Veritabanı bağlantı hatası (cPanel): " . $e->getMessage());
        
        // Varsayılan değerlerle devam et, hata sayfasına yönlendirme
        $pdo = null;
        $db = null;
        
        // İstatistik değerlerini varsayılan olarak ayarla
        $GLOBALS['default_stats'] = [
            'projects' => DEFAULT_PROJECTS_COUNT,
            'volunteers' => DEFAULT_VOLUNTEERS_COUNT,
            'families' => DEFAULT_FAMILIES_COUNT,
            'donations' => DEFAULT_DONATIONS_AMOUNT
        ];
    }
}

// Veritabanı bağlantısı başarısız olduğunda kullanmak için yardımcı fonksiyon
function db_failed() {
    return isset($GLOBALS['db_connection_failed']) && $GLOBALS['db_connection_failed'] === true;
}

// Varsayılan istatistik değerlerini almak için yardımcı fonksiyon
function get_default_stats($key = null) {
    $defaults = $GLOBALS['default_stats'] ?? [
        'projects' => DEFAULT_PROJECTS_COUNT,
        'volunteers' => DEFAULT_VOLUNTEERS_COUNT,
        'families' => DEFAULT_FAMILIES_COUNT,
        'donations' => DEFAULT_DONATIONS_AMOUNT
    ];
    
    if ($key !== null) {
        return $defaults[$key] ?? 0;
    }
    
    return $defaults;
}
?>
