<?php
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "<h2>🔧 PHPMailer Test ve Debug</h2>";

// Test Gmail SMTP
$testEmail = 'samet.saray.06@gmail.com';

echo "<h3>1. PHPMailer Sınıf Kontrolü:</h3>";
if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    echo "<p style='color: green;'>✅ PHPMailer sınıfı yüklenmiş</p>";
} else {
    echo "<p style='color: red;'>❌ PHPMailer sınıfı yüklenemedi</p>";
    exit;
}

echo "<h3>2. PHP Uzantıları Kontrolü:</h3>";
$extensions = ['openssl', 'curl', 'mbstring', 'ctype'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p style='color: green;'>✅ {$ext} uzantısı yüklü</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ {$ext} uzantısı bulunamadı</p>";
    }
}

echo "<h3>3. Gmail SMTP Test (Şifre Gerekli):</h3>";

if (isset($_POST['test_gmail']) && !empty($_POST['gmail_password'])) {
    $password = $_POST['gmail_password'];
    
    try {
        $mail = new PHPMailer(true);
        
        // SMTP ayarları
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'samet.saray.06@gmail.com';
        $mail->Password = $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        // Debug mode
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = function($str, $level) {
            echo "<pre style='background: #f0f0f0; padding: 5px; margin: 2px 0; font-size: 12px;'>$str</pre>";
        };
        
        // Gönderen ve alıcı
        $mail->setFrom('noreply@necatdernegi.org', 'Necat Derneği Test');
        $mail->addAddress($testEmail);
        
        // İçerik
        $mail->isHTML(true);
        $mail->Subject = 'Başarılı SMTP Test - ' . date('H:i:s');
        $mail->Body = '<h2>🎉 PHPMailer Gmail SMTP Test Başarılı!</h2>
                       <p>Bu e-posta başarıyla Gmail SMTP ile gönderilmiştir.</p>
                       <p><strong>Tarih:</strong> ' . date('Y-m-d H:i:s') . '</p>
                       <p><strong>Test Adresi:</strong> ' . $testEmail . '</p>';
        
        echo "<h4>SMTP Debug Çıktısı:</h4>";
        $mail->send();
        
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h4 style='color: #155724;'>✅ E-posta Başarıyla Gönderildi!</h4>";
        echo "<p>Gmail adresinizi kontrol edin: <strong>{$testEmail}</strong></p>";
        echo "<p>Şifre doğru ve SMTP bağlantısı çalışıyor.</p>";
        echo "</div>";
        
        // Başarılıysa şifreyi bir dosyaya kaydet (geçici)
        file_put_contents('smtp_password.txt', $password);
        echo "<p><em>SMTP şifresi geçici olarak kaydedildi.</em></p>";
        
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h4 style='color: #721c24;'>❌ E-posta Gönderilemedi!</h4>";
        echo "<p><strong>Hata:</strong> " . $e->getMessage() . "</p>";
        echo "</div>";
        
        echo "<h4>Muhtemel Çözümler:</h4>";
        echo "<ul>";
        echo "<li>Gmail uygulama şifresinin doğru olduğundan emin olun</li>";
        echo "<li>Google hesabında 2FA'nın aktif olduğunu kontrol edin</li>";
        echo "<li>Uygulama şifresini yeniden oluşturun</li>";
        echo "<li>Gmail hesabında 'Güvenliği düşük uygulamalara izin ver' kapalı olmalı</li>";
        echo "</ul>";
    }
    
} else {
    echo "<form method='post' style='background: #f8f9fa; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px;'>";
    echo "<h4>Gmail Uygulama Şifresi ile Test:</h4>";
    echo "<p><strong>Gmail:</strong> samet.saray.06@gmail.com</p>";
    echo "<p><input type='password' name='gmail_password' placeholder='Gmail uygulama şifrenizi girin' style='width: 300px; padding: 10px; border: 1px solid #ccc; border-radius: 3px;' required></p>";
    echo "<p><button type='submit' name='test_gmail' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>📧 SMTP Test Et</button></p>";
    echo "</form>";
}

echo "<h3>4. Gmail Uygulama Şifresi Oluşturma:</h3>";
echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px;'>";
echo "<ol>";
echo "<li><a href='https://myaccount.google.com/security' target='_blank'>Google Hesap Güvenlik</a> sayfasına gidin</li>";
echo "<li><strong>2 Adımlı Doğrulama</strong> aktif edin</li>";
echo "<li><strong>Uygulama şifreleri</strong> bölümüne gidin</li>";
echo "<li><strong>E-posta</strong> uygulaması seçin</li>";
echo "<li>Oluşturulan 16 haneli şifreyi yukarıdaki forma girin</li>";
echo "</ol>";
echo "</div>";

// Eğer şifre dosyası varsa, EmailService'e ekle
if (file_exists('smtp_password.txt')) {
    $savedPassword = trim(file_get_contents('smtp_password.txt'));
    if (!empty($savedPassword)) {
        echo "<h3>5. Şifreyi Veritabanına Kaydet:</h3>";
        echo "<button onclick='saveToDatabase()' style='background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>💾 Şifreyi Veritabanına Kaydet</button>";
        echo "<p><em>Bu işlem SMTP şifresini settings tablosuna kaydedecek.</em></p>";
    }
}

echo "<hr>";
echo "<p><a href='debug_email_system.php'>🔧 Debug Sayfası</a> | <a href='index.php?page=volunteer'>← Gönüllü Sayfası</a></p>";
?>

<script>
function saveToDatabase() {
    if (confirm('SMTP şifresi veritabanına kaydedilsin mi?')) {
        fetch('ajax/save_smtp_password.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ SMTP şifresi başarıyla kaydedildi!');
                location.reload();
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
