<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Site ayarlarını çek
    $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'social_%'");
    $stmt->execute();
    $social_settings = [];
    while ($row = $stmt->fetch()) {
        $social_settings[$row['setting_key']] = $row['setting_value'];
    }
    
    echo "<h2>Mevcut Sosyal Medya Ayarları:</h2>";
    foreach ($social_settings as $key => $value) {
        echo "<strong>$key:</strong> $value<br>";
    }
    
    echo "<hr>";
    echo "<h2>Formatlanmış Sosyal Medya Linkleri:</h2>";
    
    // Sosyal medya linklerini formatla
    $social_platforms = [
        'Facebook' => ['key' => 'social_facebook', 'icon' => 'fab fa-facebook-f', 'color' => '#1877f2', 'base_url' => 'https://facebook.com/'],
        'Instagram' => ['key' => 'social_instagram', 'icon' => 'fab fa-instagram', 'color' => '#e4405f', 'base_url' => ''],
        'Twitter' => ['key' => 'social_twitter', 'icon' => 'fab fa-twitter', 'color' => '#1da1f2', 'base_url' => 'https://twitter.com/'],
        'LinkedIn' => ['key' => 'social_linkedin', 'icon' => 'fab fa-linkedin-in', 'color' => '#0077b5', 'base_url' => 'https://linkedin.com/in/'],
        'YouTube' => ['key' => 'social_youtube', 'icon' => 'fab fa-youtube', 'color' => '#ff0000', 'base_url' => 'https://youtube.com/@']
    ];
    
    $social_media = [];
    foreach ($social_platforms as $platform => $data) {
        $url = $social_settings[$data['key']] ?? '';
        
        if (!empty($url) && $url !== '#') {
            // URL formatını düzenle
            $final_url = $url;
            if (!str_starts_with($url, 'http') && !empty($data['base_url'])) {
                $final_url = $data['base_url'] . ltrim($url, '@');
            }
            
            $social_media[] = [
                'platform' => $platform,
                'icon' => $data['icon'],
                'url' => $final_url,
                'color' => $data['color'],
                'original_value' => $url
            ];
            
            echo "<strong>$platform:</strong><br>";
            echo "&nbsp;&nbsp;Orijinal değer: $url<br>";
            echo "&nbsp;&nbsp;Final URL: $final_url<br>";
            echo "&nbsp;&nbsp;İkon: {$data['icon']}<br><br>";
        }
    }
    
    echo "<hr>";
    echo "<h2>Toplam Görüntülenecek Sosyal Medya Sayısı: " . count($social_media) . "</h2>";
    
    if (empty($social_media)) {
        echo "<div style='color: red; font-weight: bold;'>UYARI: Hiçbir sosyal medya hesabı gösterilecek durumda değil!</div>";
        echo "<p>Bunun nedenleri:</p>";
        echo "<ul>";
        echo "<li>Tüm değerler boş veya '#' karakteri</li>";
        echo "<li>Veritabanından veri çekilemedi</li>";
        echo "</ul>";
    }

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>
