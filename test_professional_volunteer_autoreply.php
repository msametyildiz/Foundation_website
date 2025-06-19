<?php
// Test dosyası - Gönüllü otomatik yanıt e-postasının profesyonel versiyonunu test eder

require_once 'includes/EmailService.php';
require_once 'config/database.php';

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    
    $emailService = new EmailService($pdo);
    
    // Test verisi - gönüllü başvurusu
    $volunteerData = [
        'first_name' => 'Ahmet',
        'last_name' => 'Yılmaz',
        'email' => 'ahmet.yilmaz@example.com',
        'phone' => '+90 555 123 4567',
        'age' => '28',
        'profession' => 'Yazılım Geliştirici',
        'availability' => 'evenings',
        'interests' => 'Eğitim, Teknoloji, Çevre',
        'experience' => 'Daha önce yerel bir hayır kurumunda web sitesi geliştirme konusunda gönüllü olarak çalıştım.',
        'motivation' => 'Topluma faydalı olmak ve deneyimlerimi paylaşarak başkalarına yardım etmek istiyorum.'
    ];
    
    // Reflection kullanarak private metoda erişim
    $reflection = new ReflectionClass($emailService);
    $autoReplyMethod = $reflection->getMethod('getVolunteerAutoReplyTemplate');
    $autoReplyMethod->setAccessible(true);
    
    // E-posta şablonunu al
    $emailContent = $autoReplyMethod->invoke($emailService, ['first_name' => $volunteerData['first_name']]);
    
    // HTML dosyası olarak çıktı ver
    header('Content-Type: text/html; charset=utf-8');
    echo $emailContent;
    
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
?>
