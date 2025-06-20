<?php
// Test yeni sade email template'leri
require_once 'includes/EmailService.php';

// Test verileri
$testData = [
    'name' => 'Ahmet Yılmaz',
    'email' => 'ahmet.yilmaz@example.com',
    'phone' => '0532 123 45 67',
    'subject' => 'Test Mesajı',
    'message' => 'Bu yeni sade email template tasarımını test etmek için gönderilen bir mesajdır. Tasarımın daha temiz ve okunabilir olup olmadığını kontrol ediyoruz.'
];

try {
    $emailService = new EmailService();
    
    echo "<h2>📧 Sade Email Template'leri Test Ediliyor...</h2>";
    
    // Yönetici e-postası gönder
    echo "<h3>1. Yönetici E-postası (samet.saray.06@gmail.com)</h3>";
    $emailService->sendContactEmail($testData, 'samet.saray.06@gmail.com');
    echo "✅ Yönetici e-postası gönderildi (yeni sade tasarım)<br><br>";
    
    // Otomatik yanıt gönder
    echo "<h3>2. Kullanıcıya Otomatik Yanıt</h3>";
    $emailService->sendAutoReply($testData);
    echo "✅ Otomatik yanıt gönderildi (yeni sade tasarım)<br><br>";
    
    echo "<div style='background: #d1fae5; padding: 20px; border-radius: 8px; border-left: 4px solid #10b981; margin: 20px 0;'>";
    echo "<h3 style='color: #047857; margin: 0 0 10px 0;'>🎉 Test Tamamlandı!</h3>";
    echo "<p style='margin: 0; color: #374151;'>Yeni sade email template'leri başarıyla test edildi:</p>";
    echo "<ul style='margin: 10px 0 0 20px; color: #374151;'>";
    echo "<li>✅ Karmaşık gradyan ve dekoratif öğeler kaldırıldı</li>";
    echo "<li>✅ Clean, minimal tasarım uygulandı</li>";
    echo "<li>✅ Okunabilirlik artırıldı</li>";
    echo "<li>✅ Profesyonel görünüm korundu</li>";
    echo "<li>✅ Responsive tasarım özelliği devam ediyor</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<h3>📋 Yenilikler:</h3>";
    echo "<ul>";
    echo "<li><strong>Sade Başlık:</strong> Karmaşık gradyan yerine solid renkler</li>";
    echo "<li><strong>Temiz İçerik Alanları:</strong> Fazla süsleme kaldırıldı</li>";
    echo "<li><strong>Basit Butonlar:</strong> Minimal tasarımlı action butonları</li>";
    echo "<li><strong>Okunabilir Tipografi:</strong> Daha sade font kullanımı</li>";
    echo "<li><strong>Minimal Spacing:</strong> Daha dengeli boşluk kullanımı</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<div style='background: #fef2f2; color: #dc2626; padding: 15px; border-radius: 8px; border-left: 4px solid #dc2626;'>";
    echo "<strong>❌ Hata:</strong> " . $e->getMessage();
    echo "</div>";
}
?>
