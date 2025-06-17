<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

echo "<h2>Gönüllü Form Test - samet.saray.06@gmail.com</h2>";

// Test için örnek veri
$testData = [
    'name' => 'Test Kullanıcı',
    'email' => 'test@example.com',
    'phone' => '0555 123 4567',
    'age' => 25,
    'profession' => 'Yazılım Geliştirici',
    'availability' => 'flexible',
    'interests' => 'Teknoloji, Eğitim, Sosyal Medya',
    'experience' => 'Daha önce çeşitli STK\'larda gönüllü olarak çalıştım.',
    'message' => 'Topluma faydalı olmak ve yeteneklerimi iyi bir amaç için kullanmak istiyorum.'
];

echo "<h3>Test Veriler:</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
foreach ($testData as $key => $value) {
    echo "<tr><td><strong>{$key}</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
}
echo "</table>";

try {
    $emailService = new EmailService($pdo);
    
    // E-posta şablonunu test et
    echo "<h3>E-posta Şablonu Test:</h3>";
    
    // Parse name for email service
    $nameParts = explode(' ', $testData['name'], 2);
    $volunteerData = [
        'first_name' => $nameParts[0],
        'last_name' => $nameParts[1] ?? '',
        'name' => $testData['name'],
        'email' => $testData['email'],
        'phone' => $testData['phone'],
        'age' => $testData['age'],
        'profession' => $testData['profession'],
        'availability' => $testData['availability'],
        'interests' => $testData['interests'],
        'experience' => $testData['experience'],
        'message' => $testData['message'],
        'motivation' => $testData['message']
    ];
    
    echo "<div style='border: 1px solid #ddd; padding: 10px; background: #f9f9f9; max-height: 400px; overflow-y: scroll;'>";
    
    // Test e-posta şablonu
    $reflection = new ReflectionClass($emailService);
    $method = $reflection->getMethod('getVolunteerEmailTemplate');
    $method->setAccessible(true);
    $template = $method->invokeArgs($emailService, [$volunteerData]);
    
    echo $template;
    echo "</div>";
    
    echo "<h3>Hedef E-posta Adresi:</h3>";
    echo "<p><strong>Test Adresi:</strong> samet.saray.06@gmail.com</p>";
    
    echo "<h3>Form Alanları ve E-posta Eşleştirme:</h3>";
    echo "<ul>";
    echo "<li><strong>name:</strong> " . $volunteerData['name'] . "</li>";
    echo "<li><strong>first_name:</strong> " . $volunteerData['first_name'] . "</li>";
    echo "<li><strong>last_name:</strong> " . $volunteerData['last_name'] . "</li>";
    echo "<li><strong>email:</strong> " . $volunteerData['email'] . "</li>";
    echo "<li><strong>phone:</strong> " . $volunteerData['phone'] . "</li>";
    echo "<li><strong>age:</strong> " . $volunteerData['age'] . "</li>";
    echo "<li><strong>profession:</strong> " . $volunteerData['profession'] . "</li>";
    echo "<li><strong>availability:</strong> " . $volunteerData['availability'] . "</li>";
    echo "<li><strong>interests:</strong> " . $volunteerData['interests'] . "</li>";
    echo "<li><strong>experience:</strong> " . $volunteerData['experience'] . "</li>";
    echo "<li><strong>message/motivation:</strong> " . $volunteerData['message'] . "</li>";
    echo "</ul>";
    
    // SMTP test
    echo "<h3>SMTP Konfigürasyon Testi:</h3>";
    $testResult = $emailService->testConfiguration();
    if ($testResult === true) {
        echo "<p style='color: green;'>✅ SMTP konfigürasyonu çalışıyor!</p>";
    } else {
        echo "<p style='color: red;'>❌ SMTP hatası: " . htmlspecialchars($testResult) . "</p>";
    }
    
    echo "<h3>Gerçek E-posta Gönderimi Test:</h3>";
    echo "<p><em>Not: Bu test gerçek e-posta gönderir. Dikkatli kullanın!</em></p>";
    echo "<button onclick='sendTestEmail()' style='background: #4ea674; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Test E-postası Gönder</button>";
    
} catch (Exception $e) {
    echo "<div style='color: red; padding: 10px; border: 1px solid red; background: #ffe6e6;'>";
    echo "<strong>Hata:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='index.php?page=volunteer'>← Gönüllü Sayfasına Dön</a></p>";
?>

<script>
function sendTestEmail() {
    if (confirm('Gerçek test e-postası gönderilsin mi?')) {
        // AJAX ile test e-postası gönder
        const formData = new FormData();
        formData.append('action', 'volunteer');
        formData.append('name', 'Test Kullanıcı');
        formData.append('email', 'test@example.com');
        formData.append('phone', '0555 123 4567');
        formData.append('age', '25');
        formData.append('profession', 'Yazılım Geliştirici');
        formData.append('availability', 'flexible');
        formData.append('interests', 'Teknoloji, Eğitim');
        formData.append('experience', 'Test deneyimi');
        formData.append('message', 'Test motivasyon mesajı');
        
        fetch('ajax/forms.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Test e-postası başarıyla gönderildi!\n\nMesaj: ' + data.message);
            } else {
                alert('❌ Hata: ' + data.message);
            }
        })
        .catch(error => {
            alert('❌ AJAX Hatası: ' + error);
        });
    }
}
</script>
