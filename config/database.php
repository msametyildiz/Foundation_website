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
    error_log("Server name detection: " . $serverName);
    
    // Web ortamında sunucu adına göre tespit et - cPanel uyumluluğu için sadeleştirildi
    $environment = ($serverName == 'localhost' || $serverName == '127.0.0.1') 
                  ? 'development' : 'production';
    
    // Ortam bilgisini logla
    error_log("Detected environment: " . $environment . " (SERVER_NAME: " . $serverName . ")");
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

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_FOUND_ROWS => true  // cPanel/MySQL uyumluluğu için
        ]
    );
    
    // Eski kod ile uyumluluk için $db değişkeni
    $db = $pdo;
    
    // Bağlantı başarılı olduğunu logla
    error_log("Database connection successful in " . $environment . " environment");
} catch (PDOException $e) {
    // Hatayı logla ama kullanıcıya detay gösterme (güvenlik için)
    error_log("Veritabanı bağlantı hatası (" . $environment . " environment): " . $e->getMessage());
    
    if ($environment == 'development') {
        die("Veritabanı bağlantı hatası: " . $e->getMessage());
    } else {
        // Üretim ortamında daha güvenli hata mesajı ve hata sayfasına yönlendirme
        error_log("Veritabanı bağlantı hatası (cPanel): " . $e->getMessage());
        if (file_exists('pages/500.php')) {
            include 'pages/500.php';
            exit;
        } else {
            die("Veritabanına bağlanırken bir sorun oluştu. Lütfen daha sonra tekrar deneyin.");
        }
    }
}
?>
