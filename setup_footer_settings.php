<?php
// Footer için gerekli ayarları veritabanına ekleyen script
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';

echo "<h1>Footer Settings Kurulum</h1>";
echo "<hr>";

// Mevcut ayarları kontrol et
echo "<h2>Mevcut Ayarlar</h2>";

try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE '%contact%' OR setting_key LIKE '%social%' OR setting_key LIKE '%site_%'");
    $existing_settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    if (empty($existing_settings)) {
        echo "<p style='color: orange;'>⚠ Hiç ayar bulunamadı. Varsayılan ayarlar eklenecek.</p>";
    } else {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Key</th><th>Value</th></tr>";
        foreach ($existing_settings as $key => $value) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($key) . "</td>";
            echo "<td>" . htmlspecialchars($value) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Veritabanı hatası: " . $e->getMessage() . "</p>";
    exit;
}

// Varsayılan ayarlar
$default_settings = [
    'site_title' => ['value' => 'Necat Derneği', 'description' => 'Site başlığı'],
    'site_description' => ['value' => 'Yardım eli uzatan, umut dağıtan bir toplum için birlikte çalışıyoruz.', 'description' => 'Site açıklaması'],
    'contact_email' => ['value' => 'info@necatdernegi.org', 'description' => 'İletişim e-posta adresi'],
    'contact_phone' => ['value' => '+90 312 444 56 78', 'description' => 'İletişim telefon numarası'],
    'contact_address' => ['value' => 'Kızılay Mahallesi, Atatürk Bulvarı No: 125/7, Çankaya/ANKARA', 'description' => 'İletişim adresi'],
    'social_instagram' => ['value' => 'necatdernegi', 'description' => 'Instagram kullanıcı adı'],
    'social_twitter' => ['value' => 'necatdernegi', 'description' => 'Twitter kullanıcı adı'],
    'social_linkedin' => ['value' => 'necat-dernegi', 'description' => 'LinkedIn profil adı'],
    'social_youtube' => ['value' => 'necatdernegi', 'description' => 'YouTube kanal adı'],
    'social_facebook' => ['value' => 'necatdernegi', 'description' => 'Facebook sayfa adı']
];

// Ayarları ekle veya güncelle
echo "<h2>Ayarlar Ekleniyor/Güncelleniyor</h2>";

$added_count = 0;
$updated_count = 0;
$error_count = 0;

foreach ($default_settings as $key => $data) {
    try {
        // Önce var mı kontrol et
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
        $check_stmt->execute([$key]);
        $exists = $check_stmt->fetchColumn() > 0;
        
        if ($exists) {
            // Eğer boşsa güncelle
            $check_empty = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
            $check_empty->execute([$key]);
            $current_value = $check_empty->fetchColumn();
            
            if (empty($current_value) || $current_value === '#') {
                $update_stmt = $pdo->prepare("UPDATE settings SET setting_value = ?, description = ? WHERE setting_key = ?");
                $update_stmt->execute([$data['value'], $data['description'], $key]);
                echo "<p style='color: blue;'>📝 $key güncellendi: " . htmlspecialchars($data['value']) . "</p>";
                $updated_count++;
            } else {
                echo "<p style='color: gray;'>⏩ $key zaten dolu, atlandı: " . htmlspecialchars($current_value) . "</p>";
            }
        } else {
            // Yeni ekle
            $insert_stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value, description) VALUES (?, ?, ?)");
            $insert_stmt->execute([$key, $data['value'], $data['description']]);
            echo "<p style='color: green;'>✅ $key eklendi: " . htmlspecialchars($data['value']) . "</p>";
            $added_count++;
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>❌ $key eklenirken hata: " . $e->getMessage() . "</p>";
        $error_count++;
    }
}

echo "<hr>";
echo "<h2>Özet</h2>";
echo "<p>✅ Eklenen: $added_count</p>";
echo "<p>📝 Güncellenen: $updated_count</p>";
echo "<p>❌ Hata: $error_count</p>";

// Son durumu göster
echo "<h2>Son Durum</h2>";
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE '%contact%' OR setting_key LIKE '%social%' OR setting_key LIKE '%site_%' ORDER BY setting_key");
    $final_settings = $stmt->fetchAll();
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Key</th><th>Value</th></tr>";
    foreach ($final_settings as $setting) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($setting['setting_key']) . "</td>";
        echo "<td>" . htmlspecialchars($setting['setting_value']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Son durum alınamadı: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Not:</strong> Bu script footer için gerekli tüm ayarları veritabanına eklemiştir. Artık footer düzgün görünmelidir.</p>";
?> 