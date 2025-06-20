<?php
echo "PHP Test - İletişim kartları kontrol ediliyor...\n";

require_once 'config/database.php';

echo "Veritabanı bağlantısı başarılı\n";

try {
    $result = $pdo->query("SELECT COUNT(*) FROM contact_info_cards");
    echo "Sorgu çalıştırıldı\n";
    
    $count = $result->fetchColumn();
    echo "Kayıt sayısı: " . $count . "\n";
    
} catch (PDOException $e) {
    echo "PDO Hatası: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Genel Hata: " . $e->getMessage() . "\n";
}
?>
