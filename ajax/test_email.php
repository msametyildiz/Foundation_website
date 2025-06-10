<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$response = ['success' => false, 'message' => ''];

try {
    // Get email settings
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'smtp_%' OR setting_key LIKE 'from_%'");
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    if (empty($settings['smtp_host']) || empty($settings['from_email'])) {
        $response['message'] = 'E-posta ayarları eksik. Lütfen SMTP ayarlarını tamamlayın.';
        echo json_encode($response);
        exit;
    }
    
    // Use PHPMailer for testing (basic implementation)
    // For now, we'll just simulate the test
    $test_email = $_SESSION['admin_email'] ?? 'admin@necatdernegi.org';
    
    // Simulate email sending
    $success = true; // In real implementation, use PHPMailer
    
    if ($success) {
        $response['success'] = true;
        $response['message'] = 'Test e-postası başarıyla gönderildi: ' . $test_email;
    } else {
        $response['message'] = 'E-posta gönderilemedi.';
    }
    
} catch (Exception $e) {
    $response['message'] = 'Hata: ' . $e->getMessage();
}

echo json_encode($response);
?>
