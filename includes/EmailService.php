<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $pdo;
    private $settings;
    private $environment;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        // Ortam tespiti (development veya production)
        $this->environment = ($_SERVER['SERVER_NAME'] == 'localhost' || strpos($_SERVER['SERVER_NAME'], '.local') !== false) ? 'development' : 'production';
        $this->loadSettings();
    }
    
    private function loadSettings() {
        try {
            $stmt = $this->pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_auth', 'smtp_encryption', 'smtp_from_email', 'smtp_from_name', 'admin_email')");
            $this->settings = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->settings[$row['setting_key']] = $row['setting_value'];
            }
            
            // cPanel SMTP yapılandırması (üretim ortamında)
            if ($this->environment == 'production') {
                // cPanel SMTP ayarları
                if (!isset($this->settings['smtp_host']) || empty($this->settings['smtp_host'])) {
                    $this->settings['smtp_host'] = 'localhost'; // cPanel SMTP sunucusu genellikle localhost
                    $this->settings['smtp_port'] = '587';
                    $this->settings['smtp_auth'] = '1';
                    $this->settings['smtp_encryption'] = 'tls';
                    $this->settings['smtp_from_email'] = 'info@necatdernegi.org';
                    $this->settings['smtp_from_name'] = 'Necat Derneği';
                }
            }
        } catch (Exception $e) {
            error_log("Failed to load email settings: " . $e->getMessage());
            $this->settings = [];
        }
    }
    
    private function createMailer() {
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            if ($this->environment == 'production') {
                // cPanel üzerinde PHPMailer ayarları
                $mail->Host = $this->settings['smtp_host'] ?? 'localhost';
                $mail->SMTPAuth = ($this->settings['smtp_auth'] ?? '1') == '1';
                $mail->Username = $this->settings['smtp_username'] ?? 'info@necatdernegi.org';
                $mail->Password = $this->settings['smtp_password'] ?? '';
                $mail->SMTPSecure = $this->settings['smtp_encryption'] ?? 'tls';
                $mail->Port = intval($this->settings['smtp_port'] ?? 587);
            } else {
                // Geliştirme ortamında SMTP ayarları
                $mail->Host = $this->settings['smtp_host'] ?? 'smtp.gmail.com';
                $mail->SMTPAuth = ($this->settings['smtp_auth'] ?? '1') == '1';
                $mail->Username = $this->settings['smtp_username'] ?? 'samet.saray.06@gmail.com';
                $mail->Password = $this->settings['smtp_password'] ?? '';
                $mail->SMTPSecure = $this->settings['smtp_encryption'] ?? 'tls';
                $mail->Port = intval($this->settings['smtp_port'] ?? 587);
            }
            $mail->CharSet = 'UTF-8';
            
            // Debug için SMTP bilgileri logla
            if ($this->environment == 'development') {
                error_log("SMTP Config - Host: " . $mail->Host . ", Username: " . $mail->Username . ", Port: " . $mail->Port);
            }
            
            // Eğer SMTP şifresi yoksa hata fırlat
            if ($mail->SMTPAuth && empty($mail->Password)) {
                throw new Exception("SMTP password is not configured. Please set smtp_password in settings table.");
            }
            
            // Default sender
            $mail->setFrom(
                $this->settings['smtp_from_email'] ?? 'info@necatdernegi.org',
                $this->settings['smtp_from_name'] ?? 'Necat Derneği'
            );
            
            return $mail;
            
        } catch (Exception $e) {
            error_log("PHPMailer configuration error: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function sendContactNotification($contactData) {
        try {
            $mail = $this->createMailer();
            
            // To admin
            $adminEmail = $this->settings['admin_email'] ?? 'admin@necatdernegi.org';
            $mail->addAddress($adminEmail);
            
            $mail->isHTML(true);
            $mail->Subject = 'Yeni İletişim Mesajı - ' . $contactData['name'];
            
            $body = $this->getContactEmailTemplate($contactData);
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);
            
            $mail->send();
            
            // Auto-reply to sender
            $this->sendContactAutoReply($contactData);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Contact notification error: " . $e->getMessage());
            return false;
        }
    }
    
    private function sendContactAutoReply($contactData) {
        try {
            $mail = $this->createMailer();
            $mail->addAddress($contactData['email'], $contactData['name']);
            
            $mail->isHTML(true);
            $mail->Subject = 'Mesajınız Alındı - Necat Derneği';
            
            $body = $this->getAutoReplyTemplate($contactData);
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);
            
            $mail->send();
            
        } catch (Exception $e) {
            error_log("Auto-reply error: " . $e->getMessage());
        }
    }
    
    public function sendVolunteerNotification($volunteerData) {
        try {
            $mail = $this->createMailer();
            
            // Admin email adresine gönder
            $adminEmail = $this->settings['admin_email'] ?? 'admin@necatdernegi.org'; 
            $mail->addAddress($adminEmail);
            
            $mail->isHTML(true);
            $mail->Subject = 'Yeni Gönüllü Başvurusu - ' . ($volunteerData['name'] ?? $volunteerData['first_name'] . ' ' . $volunteerData['last_name']);
            
            $body = $this->getVolunteerEmailTemplate($volunteerData);
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);
            
            $mail->send();
            
            // Auto-reply to applicant
            $this->sendVolunteerAutoReply($volunteerData);
            
            return ['success' => true, 'message' => 'Volunteer notification sent successfully'];
            
        } catch (Exception $e) {
            error_log("Volunteer notification error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    private function sendVolunteerAutoReply($volunteerData) {
        try {
            $mail = $this->createMailer();
            
            // Form verilerindeki ad alanını kontrol et
            $fullName = $volunteerData['name'] ?? ($volunteerData['first_name'] . ' ' . $volunteerData['last_name']);
            $firstName = $volunteerData['first_name'] ?? explode(' ', $fullName)[0];
            
            $mail->addAddress($volunteerData['email'], $fullName);
            
            $mail->isHTML(true);
            $mail->Subject = 'Gönüllü Başvurunuz Alındı - Necat Derneği';
            
            $body = $this->getVolunteerAutoReplyTemplate(['first_name' => $firstName]);
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);
            
            $mail->send();
            
        } catch (Exception $e) {
            error_log("Volunteer auto-reply error: " . $e->getMessage());
        }
    }
    
    public function sendDonationNotification($donationData) {
        try {
            $mail = $this->createMailer();
            
            // To admin
            $adminEmail = $this->settings['admin_email'] ?? 'admin@necatdernegi.org';
            $mail->addAddress($adminEmail);
            
            // Eğer dekont dosyası varsa ekle
            if (!empty($donationData['receipt_file'])) {
                $receiptPath = dirname(__DIR__) . '/uploads/receipts/' . $donationData['receipt_file'];
                if (file_exists($receiptPath)) {
                    $mail->addAttachment($receiptPath, $donationData['receipt_file']);
                }
            }
            $mail->isHTML(true);
            $mail->Subject = 'Yeni Bağış - ' . number_format($donationData['amount'], 2) . ' TL';
            
            $body = $this->getDonationEmailTemplate($donationData, true); // true: ekli gönderim
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);
            
            $mail->send();
            
            // Send thank you email to donor
            if (!empty($donationData['email'])) {
                $this->sendDonationThankYou($donationData);
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("Donation notification error: " . $e->getMessage());
            return false;
        }
    }
    
    private function sendDonationThankYou($donationData) {
        try {
            $mail = $this->createMailer();
            $mail->addAddress($donationData['email'], $donationData['donor_name']);
            
            $mail->isHTML(true);
            $mail->Subject = 'Bağışınız için Teşekkürler - Necat Derneği';
            
            $body = $this->getDonationThankYouTemplate($donationData);
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);
            
            $mail->send();
            
        } catch (Exception $e) {
            error_log("Donation thank you error: " . $e->getMessage());
        }
    }
    
    public function sendNewsletter($subject, $content, $recipients) {
        $sent = 0;
        $failed = 0;
        
        foreach ($recipients as $recipient) {
            try {
                $mail = $this->createMailer();
                $mail->addAddress($recipient['email'], $recipient['name'] ?? '');
                
                $mail->isHTML(true);
                $mail->Subject = $subject;
                
                $personalizedContent = str_replace(
                    ['{{name}}', '{{email}}'],
                    [$recipient['name'] ?? '', $recipient['email']],
                    $content
                );
                
                $body = $this->getNewsletterTemplate($personalizedContent);
                $mail->Body = $body;
                $mail->AltBody = strip_tags($personalizedContent);
                
                $mail->send();
                $sent++;
                
                // Small delay to avoid overwhelming the server
                usleep(100000); // 0.1 second
                
            } catch (Exception $e) {
                error_log("Newsletter send error for {$recipient['email']}: " . $e->getMessage());
                $failed++;
            }
        }
        
        return ['sent' => $sent, 'failed' => $failed];
    }
    
    public function testConfiguration() {
        try {
            $mail = $this->createMailer();
            $testEmail = $this->settings['admin_email'] ?? 'admin@necatdernegi.org';
            
            $mail->addAddress($testEmail);
            $mail->isHTML(true);
            $mail->Subject = 'SMTP Test - Necat Derneği';
            $mail->Body = '<h2>SMTP Konfigürasyonu Test Edildi</h2>
                          <p>Bu e-posta SMTP ayarlarınızın doğru çalıştığını gösterir.</p>
                          <p>Test Tarihi: ' . date('d.m.Y H:i:s') . '</p>
                          <p>Ortam: ' . $this->environment . '</p>
                          <p>Host: ' . ($this->settings['smtp_host'] ?? 'Ayarlanmamış') . '</p>';
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("SMTP test error: " . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    // Email Templates - Modern & Clean Design
    private function getContactEmailTemplate($data) {
        return '
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Yeni İletişim Mesajı - Necat Derneği</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; }
                .header { background-color: #4ea674; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { background-color: #f5f5f5; padding: 10px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Yeni İletişim Mesajı</h1>
                </div>
                <div class="content">
                    <p><strong>Gönderen:</strong> ' . htmlspecialchars($data['name']) . '</p>
                    <p><strong>E-posta:</strong> ' . htmlspecialchars($data['email']) . '</p>
                    ' . (!empty($data['phone']) ? '<p><strong>Telefon:</strong> ' . htmlspecialchars($data['phone']) . '</p>' : '') . '
                    <p><strong>Konu:</strong> ' . htmlspecialchars($data['subject'] ?? 'Belirtilmemiş') . '</p>
                    <p><strong>Mesaj:</strong></p>
                    <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #4ea674;">
                        ' . nl2br(htmlspecialchars($data['message'])) . '
                    </div>
                    <p><strong>Tarih:</strong> ' . date('d.m.Y H:i:s') . '</p>
                </div>
                <div class="footer">
                    <p>Bu e-posta Necat Derneği web sitesi üzerinden gönderilmiştir.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getAutoReplyTemplate($data) {
        return '
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Mesajınız Alındı - Necat Derneği</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; }
                .header { background-color: #4ea674; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { background-color: #f5f5f5; padding: 10px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Mesajınız Alındı</h1>
                </div>
                <div class="content">
                    <p>Sayın ' . htmlspecialchars($data['name']) . ',</p>
                    <p>İletişim formu aracılığıyla gönderdiğiniz mesajınızı aldık. En kısa sürede size geri dönüş yapacağız.</p>
                    <p>İletişime geçtiğiniz için teşekkür ederiz.</p>
                    <p>Saygılarımızla,<br>Necat Derneği</p>
                </div>
                <div class="footer">
                    <p>Bu e-posta otomatik olarak gönderilmiştir, lütfen cevaplamayınız.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getVolunteerEmailTemplate($data) {
        return '
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Yeni Gönüllü Başvurusu - Necat Derneği</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; }
                .header { background-color: #4ea674; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { background-color: #f5f5f5; padding: 10px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Yeni Gönüllü Başvurusu</h1>
                </div>
                <div class="content">
                    <p><strong>Ad Soyad:</strong> ' . htmlspecialchars($data['name'] ?? '') . '</p>
                    <p><strong>E-posta:</strong> ' . htmlspecialchars($data['email'] ?? '') . '</p>
                    <p><strong>Telefon:</strong> ' . htmlspecialchars($data['phone'] ?? '') . '</p>
                    ' . (!empty($data['age']) ? '<p><strong>Yaş:</strong> ' . htmlspecialchars($data['age']) . '</p>' : '') . '
                    ' . (!empty($data['profession']) ? '<p><strong>Meslek:</strong> ' . htmlspecialchars($data['profession']) . '</p>' : '') . '
                    ' . (!empty($data['availability']) ? '<p><strong>Müsaitlik:</strong> ' . htmlspecialchars($data['availability']) . '</p>' : '') . '
                    ' . (!empty($data['interests']) ? '<p><strong>İlgi Alanları:</strong> ' . htmlspecialchars($data['interests']) . '</p>' : '') . '
                    ' . (!empty($data['experience']) ? '<p><strong>Gönüllülük Deneyimi:</strong><br>' . nl2br(htmlspecialchars($data['experience'])) . '</p>' : '') . '
                    <p><strong>Motivasyon:</strong></p>
                    <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #4ea674;">
                        ' . nl2br(htmlspecialchars($data['message'] ?? '')) . '
                    </div>
                    <p><strong>Başvuru Tarihi:</strong> ' . date('d.m.Y H:i:s') . '</p>
                </div>
                <div class="footer">
                    <p>Bu e-posta Necat Derneği web sitesi üzerinden gönderilmiştir.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getVolunteerAutoReplyTemplate($data) {
        return '
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Gönüllü Başvurunuz Alındı - Necat Derneği</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; }
                .header { background-color: #4ea674; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { background-color: #f5f5f5; padding: 10px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Gönüllü Başvurunuz Alındı</h1>
                </div>
                <div class="content">
                    <p>Sevgili ' . htmlspecialchars($data['first_name']) . ',</p>
                    <p>Necat Derneği\'ne gönüllü başvurunuzu aldık. İlginiz için teşekkür ederiz.</p>
                    <p>Başvurunuz incelenecek ve en kısa sürede sizinle iletişime geçilecektir.</p>
                    <p>Saygılarımızla,<br>Necat Derneği Gönüllü Koordinasyon Ekibi</p>
                </div>
                <div class="footer">
                    <p>Bu e-posta otomatik olarak gönderilmiştir, lütfen cevaplamayınız.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getDonationEmailTemplate($data, $asAttachment = false) {
        return '
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Yeni Bağış - Necat Derneği</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; }
                .header { background-color: #4ea674; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { background-color: #f5f5f5; padding: 10px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Yeni Bağış Bildirimi</h1>
                </div>
                <div class="content">
                    <p><strong>Bağışçı:</strong> ' . htmlspecialchars($data['donor_name']) . '</p>
                    ' . (!empty($data['email']) ? '<p><strong>E-posta:</strong> ' . htmlspecialchars($data['email']) . '</p>' : '') . '
                    ' . (!empty($data['phone']) ? '<p><strong>Telefon:</strong> ' . htmlspecialchars($data['phone']) . '</p>' : '') . '
                    <p><strong>Bağış Türü:</strong> ' . htmlspecialchars($data['donation_type'] ?? '') . '</p>
                    <p><strong>Miktar:</strong> ' . number_format($data['amount'], 2) . ' TL</p>
                    ' . (!empty($data['message']) ? '<p><strong>Mesaj:</strong><br>' . nl2br(htmlspecialchars($data['message'])) . '</p>' : '') . '
                    <p><strong>Tarih:</strong> ' . date('d.m.Y H:i:s') . '</p>
                    ' . ($asAttachment ? '<p><strong>Not:</strong> Bağış dekontu ektedir.</p>' : '') . '
                </div>
                <div class="footer">
                    <p>Bu e-posta Necat Derneği web sitesi üzerinden gönderilmiştir.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getDonationThankYouTemplate($data) {
        return '
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Bağışınız için Teşekkürler - Necat Derneği</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; }
                .header { background-color: #4ea674; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { background-color: #f5f5f5; padding: 10px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Bağışınız için Teşekkürler</h1>
                </div>
                <div class="content">
                    <p>Sayın ' . htmlspecialchars($data['donor_name']) . ',</p>
                    <p>' . number_format($data['amount'], 2) . ' TL tutarındaki bağışınız için teşekkür ederiz.</p>
                    <p>Bağışınız, ihtiyaç sahiplerine destek olmak için kullanılacaktır.</p>
                    <p>Saygılarımızla,<br>Necat Derneği</p>
                </div>
                <div class="footer">
                    <p>Bu e-posta otomatik olarak gönderilmiştir. Sorularınız için lütfen bizimle iletişime geçin.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getNewsletterTemplate($content) {
        return '
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Necat Derneği Bülteni</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; }
                .header { background-color: #4ea674; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { background-color: #f5f5f5; padding: 10px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Necat Derneği Bülteni</h1>
                </div>
                <div class="content">
                    ' . $content . '
                </div>
                <div class="footer">
                    <p>Bu bülten, Necat Derneği tarafından gönderilmiştir.</p>
                    <p>Bülteni almak istemiyorsanız, lütfen tıklayın: [Abonelikten Çık]</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    public function sendTestEmail($to, $subject = 'Test Email', $message = 'This is a test email.') {
        try {
            $mail = $this->createMailer();
            $mail->addAddress($to);
            
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = '<h1>' . $subject . '</h1><p>' . $message . '</p>';
            $mail->AltBody = $message;
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Test email error: " . $e->getMessage());
            return $e->getMessage();
        }
    }
} 