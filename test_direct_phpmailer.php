<?php
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "Testing PHPMailer with Gmail SMTP...\n\n";

try {
    $mail = new PHPMailer(true);
    
    // Enable verbose debug output
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = function($str, $level) {
        echo "Debug level $level; message: $str\n";
    };
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'samet.saray.06@gmail.com';
    $mail->Password = 'zuqwspmnxkjkmakm';  // Your Gmail app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    
    // Recipients
    $mail->setFrom('noreply@necatdernegi.org', 'Necat Derneği');
    $mail->addAddress('samet.saray.06@gmail.com', 'Test User');
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email - ' . date('H:i:s');
    $mail->Body = '<h3>Test Email</h3><p>This is a test email sent at ' . date('Y-m-d H:i:s') . '</p>';
    $mail->AltBody = 'This is a test email sent at ' . date('Y-m-d H:i:s');
    
    $mail->send();
    echo "\n✅ Message has been sent successfully!\n";
    
} catch (Exception $e) {
    echo "\n❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
    echo "Exception: " . $e->getMessage() . "\n";
}
?>
