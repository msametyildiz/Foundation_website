<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

echo "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Logo Fix Test - Necat Derneği</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>body { background: #f8f9fa; padding: 20px; }</style>
</head>
<body>
    <div class='container'>
        <div class='card'>
            <div class='card-header bg-success text-white'>
                <h4 class='mb-0'>🎯 Logo Fix Test - Gönüllü Email Şablonu</h4>
            </div>
            <div class='card-body'>";

try {
    $emailService = new EmailService($pdo);
    
    echo "<h5>📧 Test Email Template Preview</h5>";
    
    $testVolunteerData = [
        'first_name' => 'Ahmet',
        'last_name' => 'Yılmaz',
        'name' => 'Ahmet Yılmaz',
        'email' => 'test@example.com',
        'phone' => '0555 123 4567',
        'age' => 28,
        'profession' => 'Öğretmen',
        'availability' => 'flexible',
        'interests' => 'Eğitim, Sosyal Projeler, Çevre',
        'experience' => 'Daha önce 2 yıl boyunca yerel bir eğitim derneğinde gönüllü olarak çalıştım.',
        'motivation' => 'Topluma daha fazla fayda sağlamak ve deneyimlerimi paylaşmak istiyorum.'
    ];
    
    // Get email template using reflection to access private method
    $reflection = new ReflectionClass($emailService);
    $method = $reflection->getMethod('getVolunteerEmailTemplate');
    $method->setAccessible(true);
    $template = $method->invokeArgs($emailService, [$testVolunteerData]);
    
    echo "<div class='alert alert-info'>
            <h6>✅ Template İyileştirmeleri:</h6>
            <ul class='mb-0'>
                <li><strong>Logo Sorunu Çözüldü:</strong> Harici resim yerine text-based logo (🏛️ NECAT DERNEĞİ)</li>
                <li><strong>Email Client Uyumluluğu:</strong> Tüm email istemcilerinde görünür</li>
                <li><strong>Profesyonel Tasarım:</strong> Yeşil tema ve temiz görünüm</li>
                <li><strong>Responsive:</strong> Mobil cihazlarda düzgün görünüm</li>
            </ul>
          </div>";
    
    echo "<h6>📋 Template Preview:</h6>";
    echo "<div style='border: 2px solid #ddd; padding: 10px; background: #f9f9f9; max-height: 400px; overflow-y: scroll;'>";
    echo $template;
    echo "</div>";
    
    echo "<div class='mt-4'>
            <div class='alert alert-success'>
                <h6>🎯 Test Gönderim</h6>
                <p>Logo artık düzgün görünmeli. Test etmek için aşağıdaki butona tıklayın:</p>
                <a href='test_volunteer_system.php' class='btn btn-success'>Test Email Gönder</a>
                <a href='pages/volunteer.php' class='btn btn-primary'>Gönüllü Formu</a>
            </div>
          </div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>
            <h6>❌ Test Hatası:</h6>
            <p>" . $e->getMessage() . "</p>
          </div>";
}

echo "</div>
        </div>
    </div>
</body>
</html>";
?>
