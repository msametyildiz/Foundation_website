<?php
require_once 'config/database.php';

echo "<h2>🔧 E-posta Sistem Debug</h2>";

// 1. Veritabanı settings tablosunu kontrol et
echo "<h3>1. Settings Tablosu Kontrolü:</h3>";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'settings'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Settings tablosu mevcut</p>";
        
        $stmt = $pdo->query("SELECT * FROM settings WHERE setting_key LIKE 'smtp%' OR setting_key = 'admin_email'");
        $settings = $stmt->fetchAll();
        
        if (count($settings) > 0) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>Setting Key</th><th>Setting Value</th></tr>";
            foreach ($settings as $setting) {
                $value = $setting['setting_value'];
                // Şifre alanlarını gizle
                if (strpos($setting['setting_key'], 'password') !== false) {
                    $value = str_repeat('*', strlen($value));
                }
                echo "<tr><td>" . htmlspecialchars($setting['setting_key']) . "</td><td>" . htmlspecialchars($value) . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ SMTP ayarları bulunamadı - Default ayarlar kullanılacak</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Settings tablosu bulunamadı</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Veritabanı hatası: " . $e->getMessage() . "</p>";
}

// 2. PHPMailer kütüphanesi kontrolü
echo "<h3>2. PHPMailer Kütüphanesi Kontrolü:</h3>";
if (file_exists('vendor/autoload.php')) {
    echo "<p style='color: green;'>✅ Composer autoload mevcut</p>";
    require_once 'vendor/autoload.php';
    
    if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        echo "<p style='color: green;'>✅ PHPMailer sınıfı yüklenebiliyor</p>";
    } else {
        echo "<p style='color: red;'>❌ PHPMailer sınıfı yüklenemiyor</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Composer autoload bulunamadı</p>";
}

// 3. EmailService sınıfı testi
echo "<h3>3. EmailService Sınıfı Testi:</h3>";
try {
    require_once 'includes/EmailService.php';
    $emailService = new EmailService($pdo);
    echo "<p style='color: green;'>✅ EmailService sınıfı başarıyla oluşturuldu</p>";
    
    // Test konfigürasyonu
    echo "<h4>SMTP Konfigürasyon Testi:</h4>";
    $testResult = $emailService->testConfiguration();
    if ($testResult === true) {
        echo "<p style='color: green;'>✅ SMTP konfigürasyonu başarılı</p>";
    } else {
        echo "<p style='color: red;'>❌ SMTP hatası: " . htmlspecialchars($testResult) . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ EmailService hatası: " . $e->getMessage() . "</p>";
}

// 4. Mail fonksiyonu kontrolü
echo "<h3>4. PHP Mail Fonksiyonu Kontrolü:</h3>";
if (function_exists('mail')) {
    echo "<p style='color: green;'>✅ PHP mail() fonksiyonu mevcut</p>";
} else {
    echo "<p style='color: red;'>❌ PHP mail() fonksiyonu mevcut değil</p>";
}

// 5. Error log kontrolü
echo "<h3>5. Error Log Kontrolü:</h3>";
$errorLogFiles = [
    '/var/log/apache2/error.log',
    '/var/log/nginx/error.log',
    ini_get('error_log'),
    'error.log'
];

foreach ($errorLogFiles as $logFile) {
    if ($logFile && file_exists($logFile) && is_readable($logFile)) {
        echo "<p><strong>Log dosyası:</strong> {$logFile}</p>";
        
        // Son 20 satırı oku
        $lines = file($logFile);
        $recentLines = array_slice($lines, -20);
        
        echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 200px; overflow-y: scroll;'>";
        foreach ($recentLines as $line) {
            if (stripos($line, 'email') !== false || stripos($line, 'smtp') !== false || stripos($line, 'phpmailer') !== false) {
                echo "<span style='background: yellow;'>" . htmlspecialchars($line) . "</span>";
            } else {
                echo htmlspecialchars($line);
            }
        }
        echo "</pre>";
        break;
    }
}

// 6. Test e-postası gönderimi
echo "<h3>6. Basit Mail Testi:</h3>";
echo "<button onclick='sendSimpleTest()' style='background: #4ea674; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Basit Test E-postası Gönder</button>";

// 7. Gönüllü form testi
echo "<h3>7. Gönüllü Form Testi:</h3>";
echo "<button onclick='sendVolunteerTest()' style='background: #2196F3; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Gönüllü Form Test E-postası</button>";

echo "<hr>";
echo "<p><a href='index.php?page=volunteer'>← Gönüllü Sayfasına Dön</a></p>";
?>

<script>
function sendSimpleTest() {
    if (confirm('Basit test e-postası gönderilsin mi?')) {
        // Simple PHP mail test
        fetch('ajax/test_email.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=simple_test&email=samet.saray.06@gmail.com'
        })
        .then(response => response.text())
        .then(data => {
            alert('Test sonucu:\n' + data);
        })
        .catch(error => {
            alert('AJAX Hatası: ' + error);
        });
    }
}

function sendVolunteerTest() {
    if (confirm('Gönüllü form test e-postası gönderilsin mi?')) {
        const formData = new FormData();
        formData.append('action', 'volunteer');
        formData.append('name', 'Debug Test Kullanıcısı');
        formData.append('email', 'debug@test.com');
        formData.append('phone', '0555 999 8888');
        formData.append('age', '30');
        formData.append('profession', 'Test');
        formData.append('availability', 'flexible');
        formData.append('interests', 'Debug, Test');
        formData.append('experience', 'Debug deneyimi');
        formData.append('message', 'Bu bir debug test mesajıdır.');
        
        fetch('ajax/forms.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert('Gönüllü form test sonucu:\n' + JSON.stringify(data, null, 2));
        })
        .catch(error => {
            alert('AJAX Hatası: ' + error);
        });
    }
}
</script>
