<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $emailService = new EmailService($pdo);
    
    // Test verisi
    $testData = [
        'name' => 'Test Kullanıcı',
        'email' => 'test@example.com',
        'phone' => '0555 123 45 67',
        'subject' => 'Test Mesajı',
        'message' => 'Bu bir test mesajıdır. İletişim formu çalışıp çalışmadığını kontrol ediyoruz.',
        'admin_email' => 'samet.saray.06@gmail.com'
    ];
    
    echo "<h2>İletişim Formu E-posta Testi</h2>";
    echo "<p>Test verisi gönderiliyor...</p>";
    
    $result = $emailService->sendContactNotification($testData);
    
    if ($result) {
        echo "<div style='color: green; font-weight: bold;'>✅ E-posta başarıyla gönderildi!</div>";
        echo "<p>samet.saray.06@gmail.com adresini kontrol edin.</p>";
    } else {
        echo "<div style='color: red; font-weight: bold;'>❌ E-posta gönderilirken hata oluştu!</div>";
        echo "<p>Hata detayları sunucu loglarında bulunabilir.</p>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red; font-weight: bold;'>❌ Hata: " . $e->getMessage() . "</div>";
}
?>
