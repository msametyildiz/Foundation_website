<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

echo "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Email Test - Logo ve Profesyonel Tasarım</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>body { background: #f8f9fa; padding: 20px; }</style>
</head>
<body>
    <div class='container'>
        <div class='card'>
            <div class='card-header bg-success text-white'>
                <h4 class='mb-0'>📧 Email Test - Logo ve Profesyonel Tasarım</h4>
            </div>
            <div class='card-body'>";

try {
    $emailService = new EmailService($pdo);
    
    // Test volunteer notification
    echo "<h5>🎯 Gönüllü Bildirim Email Testi</h5>";
    
    $volunteerData = [
        'first_name' => 'Ahmet',
        'last_name' => 'Yılmaz',
        'name' => 'Ahmet Yılmaz',
        'email' => 'test@example.com',
        'phone' => '0555 123 4567',
        'age' => 28,
        'profession' => 'Öğretmen',
        'availability' => 'flexible',
        'interests' => 'Eğitim, Sosyal Projeler, Çevre',
        'experience' => 'Daha önce 2 yıl boyunca yerel bir eğitim derneğinde gönüllü olarak çalıştım. Çocuklara matematik ve fen dersleri verdim.',
        'motivation' => 'Topluma daha fazla fayda sağlamak ve deneyimlerimi paylaşarak genç nesillerin gelişimine katkıda bulunmak istiyorum.'
    ];
    
    $result = $emailService->sendVolunteerNotification($volunteerData);
    
    if ($result['success']) {
        echo "<div class='alert alert-success'>
                <h6>✅ Gönüllü bildirim email'i başarıyla gönderildi!</h6>
                <p><strong>Gönderildi:</strong> samet.saray.06@gmail.com</p>
                <p><strong>Konu:</strong> Yeni Gönüllü Başvurusu - Ahmet Yılmaz</p>
                <p><strong>Zaman:</strong> " . date('Y-m-d H:i:s') . "</p>
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <h6>❌ Email gönderimi başarısız!</h6>
                <p><strong>Hata:</strong> " . $result['error'] . "</p>
              </div>";
    }
    
    echo "<h5>📧 Email İçeriği Özellikleri</h5>";
    echo "<div class='alert alert-info'>
            <h6>🎨 Yeni Özellikler:</h6>
            <ul class='mb-0'>
                <li><strong>Logo:</strong> Header kısmında Necat Derneği logosu eklendi</li>
                <li><strong>Profesyonel Tasarım:</strong> Sonraki Adımlar kısmı numaralı kartlar halinde düzenlendi</li>
                <li><strong>Görsel Iyileştirmeler:</strong> Daha iyi padding, border-radius ve gölgeler</li>
                <li><strong>Gmail Uyumlu:</strong> Karmaşık CSS yerine inline style kullanıldı</li>
                <li><strong>Responsive:</strong> Tüm email istemcilerinde uyumlu görünüm</li>
            </ul>
          </div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>
            <h6>❌ Test Hatası:</h6>
            <p>" . $e->getMessage() . "</p>
          </div>";
}

echo "<div class='mt-4'>
        <a href='pages/volunteer.php' class='btn btn-primary'>Gönüllü Formunu Test Et</a>
        <a href='test_volunteer_system.php' class='btn btn-secondary'>Sistem Testleri</a>
        <a href='email_config.php' class='btn btn-info'>Email Ayarları</a>
      </div>";

echo "</div>
        </div>
        
        <div class='card mt-4'>
            <div class='card-header'>
                <h5 class='mb-0'>📋 Email Değişiklikleri</h5>
            </div>
            <div class='card-body'>
                <div class='row'>
                    <div class='col-md-6'>
                        <h6>🎨 Header Değişiklikleri:</h6>
                        <ul>
                            <li>Necat Derneği logosu eklendi</li>
                            <li>Logo için fallback (hata durumunda gizlenme)</li>
                            <li>Merkezi hizalama</li>
                            <li>40px yükseklik optimizasyonu</li>
                        </ul>
                    </div>
                    <div class='col-md-6'>
                        <h6>📋 Sonraki Adımlar Değişiklikleri:</h6>
                        <ul>
                            <li>Numaralı yuvarlak ikonlar</li>
                            <li>Kart benzeri tasarım</li>
                            <li>Daha iyi spacing ve typography</li>
                            <li>Gölge efektleri</li>
                            <li>Gradient renkler</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
?>
