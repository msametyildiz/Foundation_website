<?php
// AJAX Form Test
require_once 'config/database.php';

// Test POST data
$_POST = [
    'action' => 'volunteer',
    'name' => 'Test Kullanıcı',
    'email' => 'test@example.com',
    'phone' => '0555 123 4567',
    'age' => 25,
    'profession' => 'Yazılım Geliştirici',
    'availability' => 'flexible',
    'interests' => 'Teknoloji, Eğitim',
    'experience' => 'Test deneyimi',
    'message' => 'Test motivasyon mesajı'
];

$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

echo "<h2>AJAX Form Test</h2>";

try {
    // AJAX forms.php dosyasını dahil et
    ob_start();
    include 'ajax/forms.php';
    $output = ob_get_clean();
    
    echo "<h3>AJAX Response:</h3>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>";
    echo htmlspecialchars($output);
    echo "</pre>";
    
    // JSON olarak parse et
    $response = json_decode($output, true);
    
    if ($response) {
        echo "<h3>Parsed Response:</h3>";
        echo "<ul>";
        echo "<li><strong>Success:</strong> " . ($response['success'] ? 'true' : 'false') . "</li>";
        echo "<li><strong>Message:</strong> " . htmlspecialchars($response['message']) . "</li>";
        echo "</ul>";
    }
    
    // Veritabanında kaydın oluşup oluşmadığını kontrol et
    $stmt = $pdo->prepare("SELECT * FROM volunteer_applications WHERE email = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute(['test@example.com']);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($record) {
        echo "<h3>Database Record:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        foreach ($record as $key => $value) {
            echo "<tr><td><strong>{$key}</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
        }
        echo "</table>";
        
        // Test kaydını temizle
        $deleteStmt = $pdo->prepare("DELETE FROM volunteer_applications WHERE id = ?");
        $deleteStmt->execute([$record['id']]);
        echo "<p style='color: green;'>✓ Test kaydı temizlendi</p>";
    } else {
        echo "<p style='color: red;'>✗ Veritabanında kayıt bulunamadı</p>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red; padding: 10px; border: 1px solid red; background: #ffe6e6;'>";
    echo "<strong>Hata:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='index.php?page=volunteer'>← Gönüllü Sayfasına Dön</a></p>";
?>
