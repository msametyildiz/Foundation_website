<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>İletişim Mesajları Test Raporu</h2>";
    
    // Son 10 mesajı göster
    $stmt = $pdo->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 10");
    $stmt->execute();
    $messages = $stmt->fetchAll();
    
    echo "<h3>Son 10 İletişim Mesajı:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f5f5f5;'>";
    echo "<th style='padding: 8px;'>ID</th>";
    echo "<th style='padding: 8px;'>Ad Soyad</th>";
    echo "<th style='padding: 8px;'>E-posta</th>";
    echo "<th style='padding: 8px;'>Telefon</th>";
    echo "<th style='padding: 8px;'>Konu</th>";
    echo "<th style='padding: 8px;'>Durum</th>";
    echo "<th style='padding: 8px;'>Tarih</th>";
    echo "</tr>";
    
    foreach ($messages as $message) {
        $statusColor = match($message['status']) {
            'new' => '#28a745',
            'read' => '#ffc107',
            'replied' => '#6c757d',
            default => '#007bff'
        };
        
        echo "<tr>";
        echo "<td style='padding: 8px;'>{$message['id']}</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($message['name']) . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($message['email']) . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($message['phone'] ?? 'Yok') . "</td>";
        echo "<td style='padding: 8px;'>" . htmlspecialchars($message['subject'] ?? 'Belirtilmemiş') . "</td>";
        echo "<td style='padding: 8px; color: $statusColor; font-weight: bold;'>{$message['status']}</td>";
        echo "<td style='padding: 8px;'>{$message['created_at']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // İstatistikler
    echo "<hr>";
    echo "<h3>İstatistikler:</h3>";
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM contact_messages");
    $stmt->execute();
    $total = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM contact_messages GROUP BY status");
    $stmt->execute();
    $stats = $stmt->fetchAll();
    
    echo "<p><strong>Toplam Mesaj:</strong> $total</p>";
    echo "<ul>";
    foreach ($stats as $stat) {
        $statusText = match($stat['status']) {
            'new' => 'Yeni',
            'read' => 'Okundu',
            'replied' => 'Yanıtlandı',
            default => $stat['status']
        };
        echo "<li><strong>$statusText:</strong> {$stat['count']}</li>";
    }
    echo "</ul>";
    
    // Test formu
    echo "<hr>";
    echo "<h3>Test Formu:</h3>";
    echo "<form method='post' action='ajax/forms.php' style='max-width: 500px;'>";
    echo "<input type='hidden' name='action' value='contact'>";
    echo "<p><label>Ad Soyad: <input type='text' name='name' value='Test Kullanıcı' required style='width: 100%; padding: 5px;'></label></p>";
    echo "<p><label>E-posta: <input type='email' name='email' value='test@example.com' required style='width: 100%; padding: 5px;'></label></p>";
    echo "<p><label>Telefon: <input type='tel' name='phone' value='0555 123 45 67' style='width: 100%; padding: 5px;'></label></p>";
    echo "<p><label>Konu: <select name='subject' required style='width: 100%; padding: 5px;'>";
    echo "<option value=''>Seçiniz</option>";
    echo "<option value='Genel Bilgi' selected>Genel Bilgi</option>";
    echo "<option value='Bağış Konuları'>Bağış Konuları</option>";
    echo "<option value='Gönüllülük'>Gönüllülük</option>";
    echo "</select></label></p>";
    echo "<p><label>Mesaj: <textarea name='message' required style='width: 100%; padding: 5px; height: 80px;'>Test mesajı - " . date('Y-m-d H:i:s') . "</textarea></label></p>";
    echo "<p><button type='submit' style='background: #4ea674; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Test Mesajı Gönder</button></p>";
    echo "</form>";
    
    // AJAX Test Butonu
    echo "<hr>";
    echo "<button onclick='testAjaxForm()' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>AJAX Test</button>";
    echo "<div id='ajax-result' style='margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 5px; display: none;'></div>";

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>

<script>
function testAjaxForm() {
    const resultDiv = document.getElementById('ajax-result');
    resultDiv.style.display = 'block';
    resultDiv.innerHTML = '<i>AJAX testi yapılıyor...</i>';
    
    const formData = new FormData();
    formData.append('action', 'contact');
    formData.append('name', 'AJAX Test Kullanıcı');
    formData.append('email', 'ajax-test@example.com');
    formData.append('phone', '0555 999 88 77');
    formData.append('subject', 'AJAX Test');
    formData.append('message', 'Bu bir AJAX test mesajıdır. Tarih: ' + new Date().toLocaleString());
    
    fetch('ajax/forms.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = '<div style="color: green; font-weight: bold;">✅ Başarılı: ' + data.message + '</div>';
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            resultDiv.innerHTML = '<div style="color: red; font-weight: bold;">❌ Hata: ' + data.message + '</div>';
        }
    })
    .catch(error => {
        resultDiv.innerHTML = '<div style="color: red; font-weight: bold;">❌ AJAX Hatası: ' + error + '</div>';
    });
}
</script>

<p><a href="index.php?page=contact">← İletişim Sayfasına Dön</a></p>
