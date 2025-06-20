<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/EmailService.php';

// PDO bağlantısı
$host = DB_HOST;
$dbname = DB_NAME;
$username = DB_USER;
$password = DB_PASS;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>📧 Profesyonel E-posta Template Test</h2>";
    
    $emailService = new EmailService($pdo);
    
    // 1. İletişim formu test e-postası
    echo "<h3>1. İletişim Formu E-posta Testi:</h3>";
    
    $contactData = [
        'name' => 'Ahmet Yılmaz',
        'email' => 'ahmet.yilmaz@example.com',
        'phone' => '+90 555 123 45 67',
        'subject' => 'Web Sitesi Hakkında Soru',
        'message' => 'Merhaba,

Web sitenizde gördüğüm projeler hakkında detaylı bilgi almak istiyorum. Özellikle eğitim projelerinize nasıl destek olabilirim?

Ayrıca gönüllü olarak çalışmalar yapmak istiyorum. Bu konuda bilgi verebilir misiniz?

Teşekkürler.',
        'admin_email' => 'samet.saray.06@gmail.com'
    ];
    
    $contactResult = $emailService->sendContactNotification($contactData);
    
    if ($contactResult) {
        echo "<p style='color: green; font-weight: bold;'>✅ İletişim formu e-postası başarıyla gönderildi!</p>";
        echo "<p>📧 Admin'e bildirim: samet.saray.06@gmail.com</p>";
        echo "<p>🔄 Otomatik yanıt: ahmet.yilmaz@example.com</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ İletişim formu e-postası gönderilirken hata oluştu!</p>";
    }
    
    echo "<hr>";
    
    // 2. Gönüllü başvuru test e-postası
    echo "<h3>2. Gönüllü Başvuru E-posta Testi:</h3>";
    
    $volunteerData = [
        'name' => 'Ayşe Demir',
        'email' => 'ayse.demir@example.com',
        'phone' => '+90 533 987 65 43',
        'age' => 28,
        'profession' => 'Öğretmen',
        'availability' => 'weekends',
        'interests' => 'Eğitim, Çocuk gelişimi, Sosyal projeler',
        'experience' => 'Daha önce 2 yıl boyunca köy okullarında gönüllü öğretmenlik yaptım. Ayrıca çeşitli sivil toplum kuruluşlarında çocuklar için etkinlikler düzenledim.',
        'message' => 'Eğitim alanında uzmanlaşmış bir öğretmen olarak, derneğinizin eğitim projelerinde aktif rol almak istiyorum. Özellikle dezavantajlı çocukların eğitimine katkıda bulunmak benim için çok değerli.'
    ];
    
    $volunteerResult = $emailService->sendVolunteerNotification($volunteerData);
    
    if ($volunteerResult['success']) {
        echo "<p style='color: green; font-weight: bold;'>✅ Gönüllü başvuru e-postası başarıyla gönderildi!</p>";
        echo "<p>📧 Admin'e bildirim: samet.saray.06@gmail.com</p>";
        echo "<p>🔄 Otomatik yanıt: ayse.demir@example.com</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Gönüllü başvuru e-postası gönderilirken hata: " . $volunteerResult['error'] . "</p>";
    }
    
    echo "<hr>";
    
    // 3. Test e-postası gönder
    echo "<h3>3. SMTP Test E-postası:</h3>";
    
    $testResult = $emailService->sendTestEmail(
        'samet.saray.06@gmail.com', 
        '🧪 Email Template Test - Necat Derneği',
        'Bu e-posta yeni profesyonel template\'lerin test edilmesi için gönderilmiştir. Tüm e-posta şablonları modern, responsive ve profesyonel görünüm ile güncellenmiştir.'
    );
    
    if ($testResult['success']) {
        echo "<p style='color: green; font-weight: bold;'>✅ Test e-postası başarıyla gönderildi!</p>";
        echo "<p>📧 Gönderildi: samet.saray.06@gmail.com</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Test e-postası gönderilirken hata: " . $testResult['error'] . "</p>";
    }
    
    echo "<hr>";
    
    // 4. E-posta özellikler özeti
    echo "<h3>🎨 Yeni E-posta Template Özellikleri:</h3>";
    echo "<div style='background: #f0f9ff; padding: 20px; border-radius: 8px; border-left: 4px solid #3b82f6;'>";
    echo "<h4>📱 Modern ve Responsive Tasarım:</h4>";
    echo "<ul>";
    echo "<li>✅ Mobil cihazlarda mükemmel görünüm</li>";
    echo "<li>✅ Outlook ve Gmail uyumluluğu</li>";
    echo "<li>✅ Retina ekran desteği</li>";
    echo "</ul>";
    
    echo "<h4>🎨 Profesyonel Görsel Tasarım:</h4>";
    echo "<ul>";
    echo "<li>✅ Modern gradyan renk geçişleri</li>";
    echo "<li>✅ İkon kullanımı ve emoji desteği</li>";
    echo "<li>✅ Temiz tipografi ve spacing</li>";
    echo "<li>✅ Gölge efektleri ve yuvarlatılmış köşeler</li>";
    echo "</ul>";
    
    echo "<h4>⚡ Gelişmiş Özellikler:</h4>";
    echo "<ul>";
    echo "<li>✅ Hızlı işlem butonları (E-posta gönder, Telefon et)</li>";
    echo "<li>✅ Adım adım süreç gösterimi</li>";
    echo "<li>✅ Bilgi kartları ve kategorize edilmiş içerik</li>";
    echo "<li>✅ Otomatik yanıt sistemi</li>";
    echo "</ul>";
    
    echo "<h4>🏷️ Template Türleri:</h4>";
    echo "<ul>";
    echo "<li>📧 <strong>İletişim Formu:</strong> Yeni mesaj bildirimleri + otomatik yanıt</li>";
    echo "<li>🤝 <strong>Gönüllü Başvuru:</strong> Başvuru bildirimleri + karşılama e-postası</li>";
    echo "<li>💰 <strong>Bağış Bildirimleri:</strong> Bağış onayları + teşekkür mesajları</li>";
    echo "<li>📰 <strong>Bülten:</strong> Haber bülteni ve duyurular</li>";
    echo "<li>🧪 <strong>Test E-postaları:</strong> SMTP konfigürasyon testleri</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<hr>";
    
    echo "<div style='background: #d1fae5; padding: 20px; border-radius: 8px; text-align: center;'>";
    echo "<h3 style='color: #065f46; margin-top: 0;'>🎉 E-posta Sistemi Hazır!</h3>";
    echo "<p style='color: #047857; margin-bottom: 0;'>";
    echo "<strong>Tüm e-posta template'leri profesyonel görünüme sahip olacak şekilde güncellendi!</strong><br>";
    echo "İletişim formu ve gönüllü başvuruları artık çok daha etkileyici ve profesyonel e-postalar gönderiyor.";
    echo "</p>";
    echo "</div>";
    
    echo "<div style='margin-top: 20px; padding: 15px; background: #fffbeb; border-radius: 6px; border-left: 4px solid #f59e0b;'>";
    echo "<p style='margin: 0; color: #92400e;'>";
    echo "<strong>💡 Not:</strong> E-posta gönderim durumunu kontrol etmek için samet.saray.06@gmail.com adresini kontrol edin.";
    echo "</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='color: red; padding: 20px; border: 2px solid red; background: #fee; border-radius: 8px;'>";
    echo "<strong>❌ Hata:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='index.php?page=contact' style='background: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;'>← İletişim Sayfasına Git</a></p>";
echo "<p><a href='index.php?page=volunteer' style='background: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin-left: 10px;'>← Gönüllü Sayfasına Git</a></p>";
?>
