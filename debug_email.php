<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== EMAIL DEBUG SCRIPT ===\n\n";

// Step 1: Check database connection
echo "1. Database Connection Test:\n";
try {
    require_once 'config/database.php';
    echo "✅ Database connected successfully\n\n";
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n\n";
    exit;
}

// Step 2: Check settings table
echo "2. SMTP Settings Check:\n";
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'smtp_%'");
    $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($settings as $setting) {
        if ($setting['setting_key'] === 'smtp_password') {
            echo $setting['setting_key'] . ": " . (empty($setting['setting_value']) ? '[NOT SET]' : '[SET]') . "\n";
        } else {
            echo $setting['setting_key'] . ": " . $setting['setting_value'] . "\n";
        }
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Settings error: " . $e->getMessage() . "\n\n";
}

// Step 3: Check PHPMailer
echo "3. PHPMailer Test:\n";
try {
    require_once 'vendor/autoload.php';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    echo "✅ PHPMailer loaded successfully\n\n";
    
    // Step 4: Create mailer
    echo "4. Creating PHPMailer instance:\n";
    $mail = new PHPMailer(true);
    
    // Get settings from database
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'smtp_%'");
    $dbSettings = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dbSettings[$row['setting_key']] = $row['setting_value'];
    }
    
    // Configure SMTP
    $mail->isSMTP();
    $mail->Host = $dbSettings['smtp_host'] ?? 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $dbSettings['smtp_username'] ?? 'samet.saray.06@gmail.com';
    $mail->Password = $dbSettings['smtp_password'] ?? '';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    
    echo "Host: " . $mail->Host . "\n";
    echo "Username: " . $mail->Username . "\n";
    echo "Password: " . (empty($mail->Password) ? '[NOT SET]' : '[SET]') . "\n";
    echo "Port: " . $mail->Port . "\n\n";
    
    if (empty($mail->Password)) {
        echo "❌ SMTP password is not configured!\n";
        exit;
    }
    
    // Step 5: Test email sending
    echo "5. Sending test email:\n";
    
    $mail->setFrom('noreply@necatdernegi.org', 'Necat Derneği Test');
    $mail->addAddress('samet.saray.06@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = 'Debug Test - ' . date('H:i:s');
    $mail->Body = '<h3>Test Email</h3><p>This is a debug test email sent at ' . date('Y-m-d H:i:s') . '</p>';
    
    $mail->send();
    echo "✅ Email sent successfully!\n\n";
    
} catch (Exception $e) {
    echo "❌ Email error: " . $e->getMessage() . "\n\n";
}

echo "=== DEBUG COMPLETE ===\n";
?>
