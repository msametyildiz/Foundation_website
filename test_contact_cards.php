<?php
require_once 'config/database.php';

try {
    // Önce tabloyu kontrol et
    $stmt = $pdo->query("SHOW TABLES LIKE 'contact_info_cards'");
    $table_exists = $stmt ->fetchColumn();
    
    if (!$table_exists) {
        echo "contact_info_cards tablosu mevcut değil!\n";
        exit;
    }
    
    echo "Tablo mevcut. Kayıt sayısını kontrol ediyorum...\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM contact_info_cards");
    $count = $stmt->fetchColumn();
    echo "Toplam kayıt sayısı: " . $count . "\n\n";
    
    if ($count > 0) {
        $stmt = $pdo->query("SELECT * FROM contact_info_cards ORDER BY sort_order");
        $cards = $stmt->fetchAll();
        
        echo "İletişim Kartları:\n";
        echo str_repeat("=", 50) . "\n";
        
        foreach ($cards as $card) {
            echo "ID: " . $card['id'] . "\n";
            echo "Başlık: " . $card['title'] . "\n";
            echo "İçerik: " . substr($card['content'], 0, 50) . "...\n";
            echo "İkon: " . $card['icon'] . "\n";
            echo "Buton Metni: " . ($card['button_text'] ?? 'Yok') . "\n";
            echo "Buton URL: " . ($card['button_url'] ?? 'Yok') . "\n";
            echo "Buton Tipi: " . $card['button_type'] . "\n";
            echo "Sıra: " . $card['sort_order'] . "\n";
            echo "Aktif: " . ($card['is_active'] ? 'Evet' : 'Hayır') . "\n";
            echo str_repeat("-", 30) . "\n";
        }
    } else {
        echo "Tabloda kayıt bulunamadı.\n";
    }
    
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}
?>
