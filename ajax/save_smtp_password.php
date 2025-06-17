<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    // Geçici şifre dosyasını kontrol et
    if (file_exists('../smtp_password.txt')) {
        $password = trim(file_get_contents('../smtp_password.txt'));
        
        if (!empty($password)) {
            // Veritabanına kaydet
            $stmt = $pdo->prepare("
                INSERT INTO settings (setting_key, setting_value, description) 
                VALUES ('smtp_password', ?, 'Gmail SMTP uygulama şifresi')
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
            ");
            $stmt->execute([$password]);
            
            // Geçici dosyayı sil
            unlink('../smtp_password.txt');
            
            $response['success'] = true;
            $response['message'] = 'SMTP şifresi başarıyla veritabanına kaydedildi.';
        } else {
            $response['message'] = 'Geçici şifre dosyası boş.';
        }
    } else {
        $response['message'] = 'Geçici şifre dosyası bulunamadı.';
    }
    
} catch (Exception $e) {
    $response['message'] = 'Veritabanı hatası: ' . $e->getMessage();
}

echo json_encode($response);
?>
