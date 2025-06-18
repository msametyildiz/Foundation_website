<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

echo "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Email Template Test</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>body { background: #f8f9fa; padding: 20px; }</style>
</head>
<body>
    <div class='container'>
        <div class='card'>
            <div class='card-header bg-success text-white'>
                <h4 class='mb-0'>📧 Email Template Test - Fixed Version</h4>
            </div>
            <div class='card-body'>";

try {
    $emailService = new EmailService($pdo);
    
    // Test volunteer notification with complete data
    echo "<h5>🧪 Testing Fixed Email Template</h5>";
    
    $testData = [
        'first_name' => 'Ahmet',
        'last_name' => 'Yılmaz',
        'name' => 'Ahmet Yılmaz',
        'email' => 'ahmet.yilmaz@example.com',
        'phone' => '0532 123 4567',
        'age' => 28,
        'profession' => 'Öğretmen',
        'availability' => 'evenings',
        'interests' => 'Eğitim, Çocuk Gelişimi, Sosyal Sorumluluk',
        'experience' => 'Daha önce çeşitli eğitim derneklerinde 2 yıl gönüllü olarak çalıştım. Özellikle dezavantajlı çocuklarla ders verme konusunda deneyimim var.',
        'motivation' => 'Eğitim alanındaki mesleğimi topluma hizmet etmek için kullanmak istiyorum. Özellikle eğitim imkanlarına erişemeyen çocuklara yardım etmek benim için çok anlamlı.'
    ];
    
    $result = $emailService->sendVolunteerNotification($testData);
    
    if ($result['success']) {
        echo "<div class='alert alert-success'>
                <h6>✅ Email Başarıyla Gönderildi!</h6>
                <ul>
                    <li><strong>Alıcı:</strong> samet.saray.06@gmail.com</li>
                    <li><strong>Konu:</strong> Yeni Gönüllü Başvurusu - Ahmet Yılmaz</li>
                    <li><strong>Zaman:</strong> " . date('Y-m-d H:i:s') . "</li>
                    <li><strong>Şablon:</strong> Düzeltilmiş ve optimize edilmiş</li>
                    <li><strong>Logo:</strong> Text-based logo kullanıldı (🏛️ NECAT DERNEĞİ)</li>
                </ul>
              </div>";
        
        echo "<div class='alert alert-info'>
                <h6>📬 Email Kontrolü</h6>
                <p>Lütfen <strong>samet.saray.06@gmail.com</strong> adresini kontrol edin.</p>
                <p>Bu sefer email içeriği düzgün görünmelidir:</p>
                <ul>
                    <li>✅ Temiz ve organize görünüm</li>
                    <li>✅ Tüm başvuru bilgileri</li>
                    <li>✅ Düzgün formatlanmış bölümler</li>
                    <li>✅ Email client uyumluluğu</li>
                </ul>
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <h6>❌ Email Gönderilemedi</h6>
                <p><strong>Hata:</strong> " . $result['error'] . "</p>
              </div>";
    }
    
    // Show template improvements
    echo "<div class='card mt-4'>
            <div class='card-header'>
                <h6>🔧 Template Düzeltmeleri</h6>
            </div>
            <div class='card-body'>
                <h6>Yapılan İyileştirmeler:</h6>
                <ul>
                    <li><strong>Logo Sorunu:</strong> Base64 encoded logo yerine text-based logo (🏛️ NECAT DERNEĞİ)</li>
                    <li><strong>HTML Yapısı:</strong> Daha temiz ve email client uyumlu CSS</li>
                    <li><strong>Responsive Design:</strong> Mobil cihazlar için optimize edildi</li>
                    <li><strong>Typography:</strong> Daha okunaklı font boyutları ve spacing</li>
                    <li><strong>Color Scheme:</strong> Necat Derneği yeşil teması (#4ea674)</li>
                    <li><strong>Sections:</strong> Bilgiler daha düzenli bölümlerde</li>
                </ul>
            </div>
          </div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>
            <h6>❌ Test Hatası</h6>
            <p>" . $e->getMessage() . "</p>
          </div>";
}

echo "<div class='text-center mt-4'>
        <a href='pages/volunteer.php' class='btn btn-primary me-2'>📝 Volunteer Form</a>
        <a href='test_volunteer_system.php' class='btn btn-success me-2'>🧪 System Test</a>
        <a href='index.php' class='btn btn-secondary'>🏠 Ana Sayfa</a>
      </div>";

echo "</div>
        </div>
    </div>
</body>
</html>";
?>
