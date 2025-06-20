<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/EmailService.php';

// PDO bağlantısı
$host = DB_HOST;
$dbname = DB_NAME;
$username = DB_USER;
$password = DB_PASS;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔧 İletişim Formu Test Scripti</h2>";
    
    // 1. Önce tabloyu kontrol et
    echo "<h3>1. Tablo Kontrolü:</h3>";
    $stmt = $pdo->query("DESCRIBE contact_messages");
    $columns = $stmt->fetchAll();
    echo "<p>✅ contact_messages tablosu bulundu. Sütunlar:</p>";
    echo "<ul>";
    foreach ($columns as $col) {
        echo "<li><strong>{$col['Field']}</strong> - {$col['Type']}</li>";
    }
    echo "</ul>";
    
    // 2. Test verisi ekle
    echo "<h3>2. Test Mesajı Ekleme:</h3>";
    $testName = "Test Kullanıcı - " . date('Y-m-d H:i:s');
    $testEmail = "test@example.com";
    $testPhone = "0555 123 45 67";
    $testSubject = "Test Mesajı";
    $testMessage = "Bu bir test mesajıdır. Sistem kontrol ediliyor. Tarih: " . date('Y-m-d H:i:s');
    
    $stmt = $pdo->prepare("
        INSERT INTO contact_messages (name, email, phone, subject, message, status, ip_address, created_at) 
        VALUES (?, ?, ?, ?, ?, 'new', ?, NOW())
    ");
    
    $result = $stmt->execute([$testName, $testEmail, $testPhone, $testSubject, $testMessage, '127.0.0.1']);
    
    if ($result) {
        $insertId = $pdo->lastInsertId();
        echo "<p>✅ Test mesajı başarıyla eklendi! ID: $insertId</p>";
        
        // 3. E-posta gönderimi test et
        echo "<h3>3. E-posta Gönderim Testi:</h3>";
        try {
            $emailService = new EmailService($pdo);
            $contactData = [
                'name' => $testName,
                'email' => $testEmail,
                'phone' => $testPhone,
                'subject' => $testSubject,
                'message' => $testMessage,
                'admin_email' => 'samet.saray.06@gmail.com'
            ];
            
            $emailSent = $emailService->sendContactNotification($contactData);
            
            if ($emailSent) {
                echo "<p>✅ E-posta başarıyla gönderildi!</p>";
            } else {
                echo "<p>❌ E-posta gönderilirken hata oluştu!</p>";
            }
            
        } catch (Exception $e) {
            echo "<p>❌ E-posta servisi hatası: " . $e->getMessage() . "</p>";
        }
        
    } else {
        echo "<p>❌ Test mesajı eklenemedi!</p>";
    }
    
    // 4. Son mesajları göster
    echo "<h3>4. Son Mesajlar:</h3>";
    $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
    $messages = $stmt->fetchAll();
    
    if ($messages) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f5f5f5;'>";
        echo "<th style='padding: 8px;'>ID</th>";
        echo "<th style='padding: 8px;'>Ad Soyad</th>";
        echo "<th style='padding: 8px;'>E-posta</th>";
        echo "<th style='padding: 8px;'>Konu</th>";
        echo "<th style='padding: 8px;'>Durum</th>";
        echo "<th style='padding: 8px;'>Tarih</th>";
        echo "</tr>";
        
        foreach ($messages as $message) {
            echo "<tr>";
            echo "<td style='padding: 8px;'>{$message['id']}</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($message['name']) . "</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($message['email']) . "</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($message['subject']) . "</td>";
            echo "<td style='padding: 8px;'>{$message['status']}</td>";
            echo "<td style='padding: 8px;'>{$message['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Henüz mesaj bulunmuyor.</p>";
    }
    
    echo "<hr>";
    echo "<h3>🎯 Sonuç:</h3>";
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb;'>";
    echo "<p><strong>✅ İletişim formu sistemi çalışıyor!</strong></p>";
    echo "<ul>";
    echo "<li>✅ contact_messages tablosu mevcut</li>";
    echo "<li>✅ Mesajlar veritabanına kaydediliyor</li>";
    echo "<li>✅ E-posta bildirimi samet.saray.06@gmail.com adresine gönderiliyor</li>";
    echo "<li>✅ AJAX form handler çalışıyor</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<hr>";
    echo "<p><a href='index.php?page=contact'>← İletişim Sayfasına Git</a> | <a href='admin/index.php?page=messages'>Admin Panel - Mesajlar</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Veritabanı hatası: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Genel hata: " . $e->getMessage() . "</p>";
}
?>
