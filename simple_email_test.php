<?php
require_once 'config/database.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "<h2>📧 Basit E-posta Test</h2>";

// Gmail SMTP ayarları için test
$testEmail = 'samet.saray.06@gmail.com';

echo "<h3>1. PHP mail() Fonksiyon Testi:</h3>";

$subject = "Test E-posta - " . date('H:i:s');
$message = "Bu bir test e-postasıdır.\n\nGönderim zamanı: " . date('Y-m-d H:i:s');
$headers = "From: test@necatdernegi.org\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

echo "<p><strong>Gönderiliyor:</strong> {$testEmail}</p>";
echo "<p><strong>Konu:</strong> {$subject}</p>";

$result = mail($testEmail, $subject, $message, $headers);

if ($result) {
    echo "<p style='color: green;'>✅ mail() fonksiyonu TRUE döndü</p>";
} else {
    echo "<p style='color: red;'>❌ mail() fonksiyonu FALSE döndü</p>";
}

echo "<h3>2. PHPMailer ile Gmail SMTP Testi:</h3>";

try {
    require_once 'vendor/autoload.php';
    
    $mail = new PHPMailer(true);
    
    // Gmail SMTP ayarları
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'samet.saray.06@gmail.com'; // Gmail adresiniz
    $mail->Password = ''; // Gmail uygulama şifreniz - BOŞ BIRAKILI!
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    
    // Gönderen
    $mail->setFrom('noreply@necatdernegi.org', 'Necat Derneği Test');
    
    // Alıcı
    $mail->addAddress($testEmail);
    
    // İçerik
    $mail->isHTML(true);
    $mail->Subject = 'PHPMailer Test - ' . date('H:i:s');
    $mail->Body = '
    <h2>PHPMailer Test E-postası</h2>
    <p>Bu e-posta PHPMailer ile Gmail SMTP üzerinden gönderilmiştir.</p>
    <p><strong>Tarih:</strong> ' . date('Y-m-d H:i:s') . '</p>
    <p><strong>Test adresi:</strong> ' . $testEmail . '</p>
    ';
    
    if (empty($mail->Password)) {
        echo "<p style='color: orange;'>⚠️ Gmail uygulama şifresi girilmemiş!</p>";
        echo "<p>SMTP şifresi olmadan test yapılamaz.</p>";
        
        // Şifre girme formu
        echo '<form method="post" style="background: #f9f9f9; padding: 20px; border: 1px solid #ddd; margin: 20px 0;">';
        echo '<h4>Gmail Uygulama Şifresi Test:</h4>';
        echo '<p><input type="password" name="gmail_password" placeholder="Gmail uygulama şifrenizi girin" style="width: 300px; padding: 8px;"></p>';
        echo '<p><button type="submit" name="test_smtp" style="background: #4ea674; color: white; padding: 10px 20px; border: none; border-radius: 5px;">SMTP Test Et</button></p>';
        echo '</form>';
        
        // Şifre ile test
        if (isset($_POST['test_smtp']) && !empty($_POST['gmail_password'])) {
            $mail->Password = $_POST['gmail_password'];
            
            try {
                $mail->send();
                echo "<p style='color: green;'>✅ PHPMailer ile e-posta başarıyla gönderildi!</p>";
                echo "<p>Gmail adresinizi kontrol edin: {$testEmail}</p>";
                
                // Başarılıysa şifreyi veritabanına kaydet
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'smtp_password'");
                $stmt->execute([$_POST['gmail_password']]);
                echo "<p style='color: green;'>✅ SMTP şifresi veritabanına kaydedildi</p>";
                
            } catch (Exception $e) {
                echo "<p style='color: red;'>❌ PHPMailer hatası: " . $e->getMessage() . "</p>";
                echo "<p><strong>Muhtemel nedenler:</strong></p>";
                echo "<ul>";
                echo "<li>Yanlış uygulama şifresi</li>";
                echo "<li>Gmail hesabında 2FA aktif değil</li>";
                echo "<li>Uygulama şifresi oluşturulmamış</li>";
                echo "<li>İnternet bağlantısı sorunu</li>";
                echo "</ul>";
            }
        }
    } else {
        // Şifre varsa direkt test et
        $mail->send();
        echo "<p style='color: green;'>✅ PHPMailer ile e-posta başarıyla gönderildi!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ PHPMailer hatası: " . $e->getMessage() . "</p>";
}

echo "<h3>3. Gmail Uygulama Şifresi Nasıl Oluşturulur:</h3>";
echo "<div style='background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3;'>";
echo "<ol>";
echo "<li>Gmail hesabınıza giriş yapın</li>";
echo "<li><a href='https://myaccount.google.com/security' target='_blank'>Google Hesap Güvenlik</a> sayfasına gidin</li>";
echo "<li><strong>2 Adımlı Doğrulama</strong> bölümünü bulun ve aktif edin</li>";
echo "<li><strong>Uygulama şifreleri</strong> seçeneğine tıklayın</li>";
echo "<li><strong>E-posta</strong> uygulamasını seçin</li>";
echo "<li>Oluşturulan 16 haneli şifreyi kopyalayın</li>";
echo "<li>Bu şifreyi yukarıdaki forma girin</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<p><a href='debug_email_system.php'>🔧 Debug Sayfası</a> | <a href='index.php?page=volunteer'>← Gönüllü Sayfası</a></p>";
?>
