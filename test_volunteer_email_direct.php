<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

echo "<h2>🧪 Gönüllü Form E-posta Test</h2>";

// Test verisi
$testData = [
    'name' => 'Debug Test Kullanıcı',
    'email' => 'debug@test.com',
    'phone' => '0555 999 8888',
    'age' => 28,
    'profession' => 'Test Engineer',
    'availability' => 'flexible',
    'interests' => 'Debug, Test, Email',
    'experience' => 'PHPMailer debug deneyimi',
    'message' => 'Bu bir e-posta sistemi debug testidir.'
];

echo "<h3>1. Test Verisi:</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
foreach ($testData as $key => $value) {
    echo "<tr><td><strong>{$key}</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
}
echo "</table>";

try {
    echo "<h3>2. EmailService Yükleme:</h3>";
    $emailService = new EmailService($pdo);
    echo "<p style='color: green;'>✅ EmailService başarıyla oluşturuldu</p>";
    
    echo "<h3>3. Settings Kontrolü:</h3>";
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'smtp%'");
    $settings = $stmt->fetchAll();
    
    if (count($settings) > 0) {
        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
        echo "<tr><th>Setting</th><th>Value</th></tr>";
        foreach ($settings as $setting) {
            $value = $setting['setting_value'];
            if ($setting['setting_key'] === 'smtp_password') {
                $value = empty($value) ? '<span style="color: red;">BOŞ</span>' : '<span style="color: green;">DOLU (' . strlen($value) . ' karakter)</span>';
            }
            echo "<tr><td>" . htmlspecialchars($setting['setting_key']) . "</td><td>{$value}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>❌ SMTP ayarları bulunamadı</p>";
    }
    
    echo "<h3>4. E-posta Gönderim Testi:</h3>";
    
    if (isset($_POST['send_test'])) {
        // Parse name for email service
        $nameParts = explode(' ', $testData['name'], 2);
        $volunteerData = [
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'name' => $testData['name'],
            'email' => $testData['email'],
            'phone' => $testData['phone'],
            'age' => $testData['age'],
            'profession' => $testData['profession'],
            'availability' => $testData['availability'],
            'interests' => $testData['interests'],
            'experience' => $testData['experience'],
            'message' => $testData['message'],
            'motivation' => $testData['message']
        ];
        
        echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>E-posta Gönderiliyor...</h4>";
        
        try {
            $result = $emailService->sendVolunteerNotification($volunteerData);
            
            if ($result) {
                echo "<p style='color: green;'>✅ E-posta başarıyla gönderildi!</p>";
                echo "<p><strong>Hedef:</strong> samet.saray.06@gmail.com</p>";
                echo "<p>Gmail hesabınızı kontrol edin.</p>";
            } else {
                echo "<p style='color: red;'>❌ E-posta gönderilemedi (fonksiyon false döndü)</p>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ E-posta hatası: " . htmlspecialchars($e->getMessage()) . "</p>";
            
            // Detaylı hata analizi
            if (strpos($e->getMessage(), 'Password') !== false) {
                echo "<p><strong>Çözüm:</strong> SMTP şifresi eksik veya hatalı. <a href='test_phpmailer.php'>PHPMailer test sayfasından</a> şifreyi ayarlayın.</p>";
            } elseif (strpos($e->getMessage(), 'Connection') !== false) {
                echo "<p><strong>Çözüm:</strong> İnternet bağlantısı veya SMTP sunucu sorunu.</p>";
            } else {
                echo "<p><strong>Genel Çözümler:</strong></p>";
                echo "<ul>";
                echo "<li>Gmail uygulama şifresini kontrol edin</li>";
                echo "<li>2FA'nın aktif olduğunu doğrulayın</li>";
                echo "<li>SMTP ayarlarını kontrol edin</li>";
                echo "</ul>";
            }
        }
        echo "</div>";
        
    } else {
        echo "<form method='post'>";
        echo "<button type='submit' name='send_test' style='background: #007bff; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>📧 Test E-postası Gönder</button>";
        echo "</form>";
        echo "<p><em>Bu test, gönüllü başvuru formunun aynı yöntemini kullanır.</em></p>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px;'>";
    echo "<h4 style='color: #721c24;'>❌ EmailService Hatası!</h4>";
    echo "<p><strong>Hata:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
    
    echo "<h4>Muhtemel Çözümler:</h4>";
    echo "<ul>";
    echo "<li>PHPMailer kütüphanesinin yüklü olduğunu kontrol edin</li>";
    echo "<li>vendor/autoload.php dosyasının var olduğunu doğrulayın</li>";
    echo "<li>settings tablosunun mevcut olduğunu kontrol edin</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p>";
echo "<a href='test_phpmailer.php'>🔧 PHPMailer Test</a> | ";
echo "<a href='setup_email_settings.php'>⚙️ E-posta Ayarları</a> | ";
echo "<a href='index.php?page=volunteer'>← Gönüllü Sayfası</a>";
echo "</p>";
?>
