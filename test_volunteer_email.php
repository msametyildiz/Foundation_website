<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

echo "<h2>Gönüllü E-posta Test Sistemi</h2>";

// Test verileri
$testVolunteerData = [
    'first_name' => 'Test',
    'last_name' => 'Kullanıcı',
    'email' => 'test@example.com',
    'phone' => '0555 123 4567',
    'age' => 25,
    'profession' => 'Yazılım Geliştirici',
    'availability' => 'flexible',
    'interests' => 'Teknoloji, Eğitim, Sosyal Medya',
    'experience' => 'Daha önce çeşitli STK\'larda gönüllü olarak çalıştım. Web tasarımı ve sosyal medya yönetimi konularında deneyimim var.',
    'motivation' => 'Topluma faydalı olmak ve yeteneklerimi iyi bir amaç için kullanmak istiyorum. Özellikle teknoloji alanındaki bilgilerimi paylaşarak diğer insanlara yardımcı olmak beni mutlu ediyor.'
];

try {
    $emailService = new EmailService($pdo);
    
    echo "<h3>Test E-posta Şablonu:</h3>";
    echo "<div style='border: 1px solid #ddd; padding: 10px; background: #f9f9f9;'>";
    
    // E-posta şablonunu göster
    $reflection = new ReflectionClass($emailService);
    $method = $reflection->getMethod('getVolunteerEmailTemplate');
    $method->setAccessible(true);
    $template = $method->invokeArgs($emailService, [$testVolunteerData]);
    
    echo $template;
    echo "</div>";
    
    echo "<h3>Test Otomatik Yanıt Şablonu:</h3>";
    echo "<div style='border: 1px solid #ddd; padding: 10px; background: #f0f8ff;'>";
    
    // Auto-reply şablonunu göster
    $autoReplyMethod = $reflection->getMethod('getVolunteerAutoReplyTemplate');
    $autoReplyMethod->setAccessible(true);
    $autoReplyTemplate = $autoReplyMethod->invokeArgs($emailService, [$testVolunteerData]);
    
    echo $autoReplyTemplate;
    echo "</div>";
    
    echo "<hr>";
    echo "<h3>E-posta Hedef Adresleri:</h3>";
    echo "<ul>";
    echo "<li><strong>Ana Alıcı:</strong> gonullu@necatdernegi.org</li>";
    echo "<li><strong>Yedek Alıcı:</strong> admin@necatdernegi.org (eğer farklıysa)</li>";
    echo "<li><strong>Otomatik Yanıt:</strong> Başvuru sahibinin e-posta adresi</li>";
    echo "</ul>";
    
    echo "<h3>Form Alanları Eşleştirmesi:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Form Alanı</th><th>E-posta Değişkeni</th><th>Test Değeri</th></tr>";
    echo "<tr><td>name</td><td>first_name + last_name</td><td>{$testVolunteerData['first_name']} {$testVolunteerData['last_name']}</td></tr>";
    echo "<tr><td>email</td><td>email</td><td>{$testVolunteerData['email']}</td></tr>";
    echo "<tr><td>phone</td><td>phone</td><td>{$testVolunteerData['phone']}</td></tr>";
    echo "<tr><td>age</td><td>age</td><td>{$testVolunteerData['age']}</td></tr>";
    echo "<tr><td>profession</td><td>profession</td><td>{$testVolunteerData['profession']}</td></tr>";
    echo "<tr><td>availability</td><td>availability</td><td>{$testVolunteerData['availability']}</td></tr>";
    echo "<tr><td>interests</td><td>interests</td><td>{$testVolunteerData['interests']}</td></tr>";
    echo "<tr><td>experience</td><td>experience</td><td>{$testVolunteerData['experience']}</td></tr>";
    echo "<tr><td>message</td><td>motivation</td><td>{$testVolunteerData['motivation']}</td></tr>";
    echo "</table>";
    
} catch (Exception $e) {
    echo "<div style='color: red; padding: 10px; border: 1px solid red; background: #ffe6e6;'>";
    echo "<strong>Hata:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "<hr>";
echo "<p><strong>Not:</strong> Bu test sayfası e-posta şablonlarını görüntülemek içindir. Gerçek e-posta gönderimi yapmaz.</p>";
echo "<p><a href='index.php?page=volunteer'>← Gönüllü Sayfasına Dön</a></p>";
?>
