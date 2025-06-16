<?php
// Tarihçe Test
require_once 'config/database.php';

try {
    echo "Tarihçe Settings Testi\n";
    echo "=====================\n\n";
    
    // Tarihçe ile ilgili settings verilerini çek
    $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'history_%' ORDER BY setting_key");
    $stmt->execute();
    $history_settings = [];
    while ($row = $stmt->fetch()) {
        $history_settings[$row['setting_key']] = $row['setting_value'];
    }
    
    echo "Mevcut Tarihçe Settings Verileri:\n";
    foreach ($history_settings as $key => $value) {
        echo "- $key: " . substr($value, 0, 50) . "...\n";
    }
    echo "\n";
    
    // Tarihçe timeline'ını oluştur
    $history_timeline = [];
    $history_years = ['1995', '1998', '2005', '2010', '2020', '2024'];
    
    foreach ($history_years as $year) {
        $title_key = "history_{$year}_title";
        $desc_key = "history_{$year}_description";
        
        if (isset($history_settings[$title_key]) && isset($history_settings[$desc_key])) {
            $history_timeline[] = [
                'year' => $year,
                'title' => $history_settings[$title_key],
                'description' => $history_settings[$desc_key]
            ];
        }
    }
    
    echo "Oluşturulan Tarihçe Timeline'ı:\n";
    foreach ($history_timeline as $event) {
        echo "- {$event['year']}: {$event['title']}\n";
        echo "  " . substr($event['description'], 0, 80) . "...\n\n";
    }
    
    if (count($history_timeline) > 0) {
        echo "✓ Test başarılı! Tarihçe bölümü dinamik verilerle çalışıyor.\n";
        echo "  Toplam " . count($history_timeline) . " tarihçe olayı bulundu.\n";
    } else {
        echo "❌ Uyarı: Hiç tarihçe verisi bulunamadı!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
?>
