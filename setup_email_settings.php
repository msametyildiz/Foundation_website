<?php
require_once 'config/database.php';

echo "<h2>🛠️ E-posta Ayarları Kurulumu</h2>";

// Settings tablosunu kontrol et
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'settings'");
    if ($stmt->rowCount() == 0) {
        echo "<p>Settings tablosu oluşturuluyor...</p>";
        $pdo->exec("
            CREATE TABLE `settings` (
                `id` int NOT NULL AUTO_INCREMENT,
                `setting_key` varchar(255) NOT NULL,
                `setting_value` text,
                `description` text,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `setting_key` (`setting_key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "<p style='color: green;'>✅ Settings tablosu oluşturuldu</p>";
    }

    // SMTP ayarlarını ekle/güncelle
    $smtpSettings = [
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => '587',
        'smtp_auth' => '1',
        'smtp_encryption' => 'tls',
        'smtp_username' => 'samet.saray.06@gmail.com',
        'smtp_password' => '', // Bu boş bırakılmalı - gerçek şifre admin panelinden girilecek
        'smtp_from_email' => 'noreply@necatdernegi.org',
        'smtp_from_name' => 'Necat Derneği',
        'admin_email' => 'samet.saray.06@gmail.com'
    ];

    foreach ($smtpSettings as $key => $value) {
        $stmt = $pdo->prepare("
            INSERT INTO settings (setting_key, setting_value, description) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
        ");
        
        $description = '';
        switch ($key) {
            case 'smtp_host':
                $description = 'SMTP sunucu adresi';
                break;
            case 'smtp_port':
                $description = 'SMTP port numarası';
                break;
            case 'smtp_auth':
                $description = 'SMTP doğrulama (1=aktif, 0=pasif)';
                break;
            case 'smtp_encryption':
                $description = 'SMTP şifreleme türü (tls, ssl)';
                break;
            case 'smtp_username':
                $description = 'SMTP kullanıcı adı';
                break;
            case 'smtp_password':
                $description = 'SMTP şifresi';
                break;
            case 'smtp_from_email':
                $description = 'Gönderen e-posta adresi';
                break;
            case 'smtp_from_name':
                $description = 'Gönderen adı';
                break;
            case 'admin_email':
                $description = 'Admin e-posta adresi';
                break;
        }
        
        $stmt->execute([$key, $value, $description]);
    }

    echo "<p style='color: green;'>✅ SMTP ayarları güncellendi</p>";

    // Mevcut ayarları göster
    echo "<h3>Mevcut E-posta Ayarları:</h3>";
    $stmt = $pdo->query("SELECT setting_key, setting_value, description FROM settings WHERE setting_key LIKE 'smtp%' OR setting_key = 'admin_email'");
    $settings = $stmt->fetchAll();

    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
    echo "<tr><th>Ayar</th><th>Değer</th><th>Açıklama</th></tr>";
    foreach ($settings as $setting) {
        $value = $setting['setting_value'];
        if ($setting['setting_key'] === 'smtp_password') {
            $value = empty($value) ? '<span style="color: red;">BOŞ - GİRİLMESİ GEREKİR</span>' : str_repeat('*', 8);
        }
        echo "<tr>";
        echo "<td><strong>" . htmlspecialchars($setting['setting_key']) . "</strong></td>";
        echo "<td>" . $value . "</td>";
        echo "<td>" . htmlspecialchars($setting['description']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h4>⚠️ Önemli Notlar:</h4>";
    echo "<ul>";
    echo "<li><strong>SMTP Şifresi:</strong> Gmail için uygulama şifresi gereklidir. Normal Gmail şifresi çalışmaz.</li>";
    echo "<li><strong>Gmail Uygulama Şifresi:</strong> Google hesabında 2FA aktif olmalı ve uygulama şifresi oluşturulmalı.</li>";
    echo "<li><strong>Test:</strong> Önce basit mail() fonksiyonunu test edin, sonra SMTP ayarlarını.</li>";
    echo "</ul>";
    echo "</div>";

    echo "<h3>Gmail Uygulama Şifresi Oluşturma:</h3>";
    echo "<ol>";
    echo "<li>Google hesabınıza giriş yapın</li>";
    echo "<li>Hesap ayarları > Güvenlik > 2 adımlı doğrulama (aktif olmalı)</li>";
    echo "<li>Uygulama şifreleri > E-posta için yeni şifre oluştur</li>";
    echo "<li>Oluşturulan 16 haneli şifreyi smtp_password alanına girin</li>";
    echo "</ol>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Hata: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='debug_email_system.php'>🔧 E-posta Debug Sayfası</a> | <a href='index.php?page=volunteer'>← Gönüllü Sayfası</a></p>";
?>
