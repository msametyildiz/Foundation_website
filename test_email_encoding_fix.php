<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

echo "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Email Encoding Fix Test - Necat Derneği</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>body { background: #f8f9fa; padding: 20px; }</style>
</head>
<body>
    <div class='container'>
        <div class='card'>
            <div class='card-header bg-success text-white'>
                <h4 class='mb-0'>🔧 Email Encoding Fix Test - Footer Sorunu</h4>
            </div>
            <div class='card-body'>";

try {
    $emailService = new EmailService($pdo);
    
    echo "<h5>1. İletişim Formu Auto-Reply Test</h5>";
    
    // Test contact auto-reply
    $contactData = [
        'name' => 'Test Kullanıcı',
        'email' => 'test@example.com',
        'phone' => '0555 123 45 67',
        'subject' => 'Test Mesajı - Encoding Fix',
        'message' => 'Bu test mesajı footer encoding sorununu kontrol etmek için gönderilmiştir.',
        'admin_email' => 'samet.saray.06@gmail.com'
    ];
    
    $contactResult = $emailService->sendContactNotification($contactData);
    
    if ($contactResult) {
        echo "<div class='alert alert-success'>
                <h6>✅ İletişim formu e-postası gönderildi!</h6>
                <p class='mb-0'>Admin bildirim + otomatik yanıt gönderildi.</p>
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <h6>❌ İletişim formu e-postası gönderilemedi!</h6>
              </div>";
    }
    
    echo "<hr>";
    echo "<h5>2. Gönüllü Formu Auto-Reply Test</h5>";
    
    // Test volunteer auto-reply
    $volunteerData = [
        'first_name' => 'Ahmet',
        'last_name' => 'Test',
        'name' => 'Ahmet Test',
        'email' => 'volunteer@example.com',
        'phone' => '0555 987 65 43',
        'age' => 25,
        'profession' => 'Test Engineer',
        'availability' => 'flexible',
        'interests' => 'Test, Debug, Email',
        'experience' => 'Email test deneyimi',
        'motivation' => 'Footer encoding sorununu test etmek istiyorum.'
    ];
    
    $volunteerResult = $emailService->sendVolunteerNotification($volunteerData);
    
    if ($volunteerResult['success']) {
        echo "<div class='alert alert-success'>
                <h6>✅ Gönüllü başvuru e-postası gönderildi!</h6>
                <p class='mb-0'>Admin bildirim + otomatik yanıt gönderildi.</p>
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <h6>❌ Gönüllü başvuru e-postası gönderilemedi!</h6>
                <p class='mb-0'>Hata: " . $volunteerResult['error'] . "</p>
              </div>";
    }
    
    echo "<hr>";
    echo "<div class='alert alert-info'>
            <h6>📧 Test Sonucu</h6>
            <p>Lütfen aşağıdaki e-posta adreslerini kontrol edin:</p>
            <ul class='mb-0'>
                <li><strong>Admin bildirimleri:</strong> samet.saray.06@gmail.com</li>
                <li><strong>İletişim otomatik yanıt:</strong> test@example.com</li>
                <li><strong>Gönüllü otomatik yanıt:</strong> volunteer@example.com</li>
            </ul>
            <p class='mt-2'><strong>Kontrol edilecek:</strong> Footer'daki 'Bu otomatik bir yanıttır' metni düzgün görünüyor mu?</p>
          </div>";
    
    echo "<hr>";
    echo "<h5>3. Template Preview</h5>";
    
    // Show auto-reply template content for debugging
    $reflection = new ReflectionClass($emailService);
    $autoReplyMethod = $reflection->getMethod('getAutoReplyTemplate');
    $autoReplyMethod->setAccessible(true);
    $autoReplyContent = $autoReplyMethod->invoke($emailService, $contactData);
    
    echo "<div class='alert alert-warning'>
            <h6>🔍 Auto-Reply Template Debug</h6>
            <p>Footer kısmını kontrol edelim:</p>
          </div>";
    
    // Check for encoding issues in footer
    if (strpos($autoReplyContent, 'Bu otomatik bir yanıttır') !== false) {
        echo "<div class='alert alert-success'>
                <h6>✅ Footer metni template'de doğru!</h6>
                <p class='mb-0'>'Bu otomatik bir yanıttır' metni template'de düzgün bulundu.</p>
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <h6>❌ Footer metni template'de bulunamadı!</h6>
                <p class='mb-0'>Template içeriği kontrol edilmeli.</p>
              </div>";
    }
    
    // Check charset settings
    echo "<div class='card mt-3'>
            <div class='card-header'>
                <h6>🔤 Charset Ayarları</h6>
            </div>
            <div class='card-body'>
                <p><strong>PHP mbstring:</strong> " . (extension_loaded('mbstring') ? 'Yüklü ✅' : 'Yüklü değil ❌') . "</p>
                <p><strong>Default charset:</strong> " . ini_get('default_charset') . "</p>
                <p><strong>Email charset:</strong> UTF-8 (PHPMailer'da ayarlandı)</p>
            </div>
          </div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>
            <h6>❌ Hata</h6>
            <p class='mb-0'>" . $e->getMessage() . "</p>
          </div>";
}

echo "</div>
        </div>
    </div>
</body>
</html>";
?>
