<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

echo "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>İletişim Email Şablonları - Önizleme</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .preview-card { 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            margin-bottom: 30px;
        }
        .preview-header {
            background: linear-gradient(135deg, #4ea674 0%, #059669 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 20px;
            text-align: center;
        }
        .email-frame {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            margin: 20px 0;
            max-height: 600px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class='container py-4'>";

try {
    $emailService = new EmailService($pdo);
    
    echo "<div class='row justify-content-center'>
            <div class='col-12'>
                <div class='preview-card'>
                    <div class='preview-header'>
                        <h2><i class='fas fa-envelope me-2'></i>İletişim Formu Email Şablonları</h2>
                        <p class='mb-0'>Yenilenmiş Profesyonel Tasarım Önizlemesi</p>
                    </div>
                    <div class='card-body p-4'>";
    
    // Test verisi
    $testContactData = [
        'name' => 'Ahmet Yılmaz',
        'email' => 'ahmet.yilmaz@example.com',
        'phone' => '+90 555 123 45 67',
        'subject' => 'Eğitim Projeleri Hakkında Bilgi Talebi',
        'message' => 'Merhaba,

Web sitenizde gördüğüm eğitim projeleriniz hakkında detaylı bilgi almak istiyorum. Özellikle çocuklar için düzenlediğiniz kurslar ve bu kurslara nasıl destek olabileceğim konusunda bilgi verebilir misiniz?

Ayrıca gönüllü olarak çalışmak istiyorum. Bu konuda hangi alanlarınızda desteğe ihtiyacınız var?

İlginiz için teşekkür ederim.',
        'admin_email' => 'admin@necatdernegi.org'
    ];
    
    echo "<div class='row'>
            <div class='col-md-6'>
                <h4><i class='fas fa-user-tie text-primary me-2'></i>Yönetici Bildirimi</h4>
                <p class='text-muted'>İletişim formundan gelen mesajlar için yöneticiye gönderilen email şablonu:</p>
                <div class='email-frame'>";
    
    // Reflection kullanarak private metoda erişim
    $reflection = new ReflectionClass($emailService);
    $method = $reflection->getMethod('getContactEmailTemplate');
    $method->setAccessible(true);
    $adminTemplate = $method->invoke($emailService, $testContactData);
    
    echo $adminTemplate;
    
    echo "</div>
            </div>
            <div class='col-md-6'>
                <h4><i class='fas fa-reply text-success me-2'></i>Otomatik Yanıt</h4>
                <p class='text-muted'>Kullanıcıya gönderilen otomatik yanıt email şablonu:</p>
                <div class='email-frame'>";
    
    // Auto reply template
    $autoReplyMethod = $reflection->getMethod('getAutoReplyTemplate');
    $autoReplyMethod->setAccessible(true);
    $autoReplyTemplate = $autoReplyMethod->invoke($emailService, $testContactData);
    
    echo $autoReplyTemplate;
    
    echo "</div>
            </div>
          </div>";
    
    echo "<div class='alert alert-success mt-4'>
            <h5><i class='fas fa-check-circle me-2'></i>Tasarım Özellikleri</h5>
            <ul class='mb-0'>
                <li><strong>Responsive Tasarım:</strong> Mobil ve masaüstü cihazlarda mükemmel görünüm</li>
                <li><strong>Modern Tipografi:</strong> Sistem fontları ile temiz ve okunabilir metin</li>
                <li><strong>Profesyonel Renkler:</strong> Necat Derneği marka renkleri (#4ea674, #059669)</li>
                <li><strong>MSO Uyumluluğu:</strong> Outlook ve diğer email istemcileri için optimizasyon</li>
                <li><strong>Gradient Başlıklar:</strong> Modern ve çekici görsel tasarım</li>
                <li><strong>Temiz Layout:</strong> Kartlar ve grid sistemi ile düzenli yerleşim</li>
            </ul>
          </div>";
    
    echo "</div>
                </div>
            </div>
          </div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>
            <h5><i class='fas fa-exclamation-triangle me-2'></i>Hata</h5>
            <p class='mb-0'>Email şablonları yüklenirken hata oluştu: " . htmlspecialchars($e->getMessage()) . "</p>
          </div>";
}

echo "</div>
</body>
</html>";
?>
