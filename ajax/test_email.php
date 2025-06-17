<?php
require_once '../config/database.php';
require_once '../includes/EmailService.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST requests allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

$action = $input['action'] ?? '';
$to = $input['to'] ?? '';
$subject = $input['subject'] ?? 'Test Email';
$message = $input['message'] ?? 'This is a test email.';

try {
    if ($action === 'test_email') {
        if (empty($to)) {
            throw new Exception('No email address provided');
        }
        
        $emailService = new EmailService($pdo);
        $result = $emailService->sendTestEmail($to, $subject, $message);
        
        echo json_encode($result);
        
    } else {
        throw new Exception('Unknown action: ' . $action);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>
    
    echo "Testing mail() function...\n";
    echo "To: {$to}\n";
    echo "Subject: {$subject}\n";
    echo "Message length: " . strlen($message) . " characters\n";
    echo "Headers: {$headers}\n";
    echo "---\n";
    
    $result = mail($to, $subject, $message, $headers);
    
    if ($result) {
        echo "SUCCESS: mail() function returned TRUE\n";
        echo "E-posta gönderildi (sunucu tarafında başarılı)\n";
        echo "Not: E-postanın gerçekte ulaşması için SMTP ayarları gerekli olabilir.\n";
    } else {
        echo "ERROR: mail() function returned FALSE\n";
        echo "Muhtemel sebepler:\n";
        echo "- SMTP sunucu ayarları eksik\n";
        echo "- PHP mail konfigürasyonu hatalı\n";
        echo "- Sunucu mail gönderim izni yok\n";
    }
    
    // PHP mail configuration info
    echo "\n--- PHP Mail Configuration ---\n";
    echo "sendmail_path: " . ini_get('sendmail_path') . "\n";
    echo "SMTP: " . ini_get('SMTP') . "\n";
    echo "smtp_port: " . ini_get('smtp_port') . "\n";
    echo "sendmail_from: " . ini_get('sendmail_from') . "\n";
}
?>
