<?php
// Veritabanı bağlantı ayarları
define('DB_HOST', 'localhost');
define('DB_NAME', 'necat_dernegi');
define('DB_USER', 'necat_user');
define('DB_PASS', 'necat_password123');
define('DB_CHARSET', 'utf8mb4');

// Site ayarları
define('SITE_URL', 'http://localhost:8080');
define('SITE_NAME', 'Necat Derneği');
define('SITE_EMAIL', 'info@necatdernegi.org');
define('ADMIN_EMAIL', 'admin@necatdernegi.org');

// Dosya yükleme ayarları
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf']);

// Güvenlik anahtarı (değiştirin)
define('SECRET_KEY', 'your_secret_key_here_change_this');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    
    // Eski kod ile uyumluluk için $db değişkeni
    $db = $pdo;
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>
