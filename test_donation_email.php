<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

// Test email data
$donationData = [
    'amount' => 500.00,
    'donor_name' => 'Ahmet Yılmaz',
    'email' => 'test@example.com',
    'phone' => '+90 532 123 45 67',
    'donation_type' => 'Yetim Yardımı',
    'message' => 'Bu bağışımın yetim çocuklara ulaşmasını istiyorum. Allah rızası için yapıyorum.',
    'receipt_file' => 'sample_receipt.pdf'
];

$emailService = new EmailService($pdo);

echo "<h1>Dekont Yükleme E-posta Şablonları Test</h1>";

echo "<h2>1. Admin'e Gönderilen E-posta (Yeni Bağış Bildirimi)</h2>";
echo "<iframe srcdoc='" . htmlspecialchars($emailService->getDonationEmailTemplate($donationData, true)) . "' style='width: 100%; height: 600px; border: 1px solid #ccc; margin-bottom: 20px;'></iframe>";

echo "<h2>2. Bağışçıya Gönderilen Teşekkür E-postası</h2>";
echo "<iframe srcdoc='" . htmlspecialchars($emailService->getDonationThankYouTemplate($donationData)) . "' style='width: 100%; height: 600px; border: 1px solid #ccc; margin-bottom: 20px;'></iframe>";

echo "<p><strong>Not:</strong> Bu e-postalar dekont yükleme formu gönderildiğinde otomatik olarak gönderilir.</p>";
?>
