<?php
require_once '../vendor/autoload.php';
require_once '../config/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $pdo;
    private $settings;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->loadSettings();
    }
    
    private function loadSettings() {
        try {
            $stmt = $this->pdo->query("SELECT setting_key, setting_value FROM settings");
            $this->settings = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->settings[$row['setting_key']] = $row['setting_value'];
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
            $mail->Host = $this->settings['smtp_host'] ?? 'localhost';
            $mail->SMTPAuth = ($this->settings['smtp_auth'] ?? '1') == '1';
            $mail->Username = $this->settings['smtp_username'] ?? '';
            $mail->Password = $this->settings['smtp_password'] ?? '';
            $mail->SMTPSecure = $this->settings['smtp_encryption'] ?? 'tls';
            $mail->Port = intval($this->settings['smtp_port'] ?? 587);
            $mail->CharSet = 'UTF-8';
            
            // Default sender
            $mail->setFrom(
                $this->settings['smtp_from_email'] ?? 'noreply@necatdernegi.org',
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
            
            // To admin
            $adminEmail = $this->settings['admin_email'] ?? 'admin@necatdernegi.org';
            $mail->addAddress($adminEmail);
            
            $mail->isHTML(true);
            $mail->Subject = 'Yeni Gönüllü Başvurusu - ' . $volunteerData['first_name'] . ' ' . $volunteerData['last_name'];
            
            $body = $this->getVolunteerEmailTemplate($volunteerData);
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);
            
            $mail->send();
            
            // Auto-reply to applicant
            $this->sendVolunteerAutoReply($volunteerData);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Volunteer notification error: " . $e->getMessage());
            return false;
        }
    }
    
    private function sendVolunteerAutoReply($volunteerData) {
        try {
            $mail = $this->createMailer();
            $mail->addAddress($volunteerData['email'], $volunteerData['first_name'] . ' ' . $volunteerData['last_name']);
            
            $mail->isHTML(true);
            $mail->Subject = 'Gönüllü Başvurunuz Alındı - Necat Derneği';
            
            $body = $this->getVolunteerAutoReplyTemplate($volunteerData);
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
            
            $mail->isHTML(true);
            $mail->Subject = 'Yeni Bağış - ' . number_format($donationData['amount'], 2) . ' TL';
            
            $body = $this->getDonationEmailTemplate($donationData);
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
                          <p>Test Tarihi: ' . date('d.m.Y H:i:s') . '</p>';
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("SMTP test error: " . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    // Email Templates
    private function getContactEmailTemplate($data) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2c5aa0; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 10px; text-align: center; color: #666; font-size: 12px; }
                .info-box { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #2c5aa0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Yeni İletişim Mesajı</h1>
                </div>
                <div class="content">
                    <div class="info-box">
                        <strong>Gönderen:</strong> ' . htmlspecialchars($data['name']) . '<br>
                        <strong>E-posta:</strong> ' . htmlspecialchars($data['email']) . '<br>
                        <strong>Telefon:</strong> ' . htmlspecialchars($data['phone'] ?? 'Belirtilmemiş') . '<br>
                        <strong>Konu:</strong> ' . htmlspecialchars($data['subject'] ?? 'Genel') . '<br>
                        <strong>Tarih:</strong> ' . date('d.m.Y H:i:s') . '
                    </div>
                    <div class="info-box">
                        <strong>Mesaj:</strong><br>
                        ' . nl2br(htmlspecialchars($data['message'])) . '
                    </div>
                </div>
                <div class="footer">
                    Bu mesaj Necat Derneği web sitesi iletişim formu aracılığıyla gönderilmiştir.
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getAutoReplyTemplate($data) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2c5aa0; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 10px; text-align: center; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Mesajınız Alındı</h1>
                </div>
                <div class="content">
                    <p>Sayın ' . htmlspecialchars($data['name']) . ',</p>
                    <p>Necat Derneği\'ne gönderdiğiniz mesaj tarafımıza ulaşmıştır. En kısa sürede size dönüş yapacağız.</p>
                    <p>İlginiz için teşekkür ederiz.</p>
                    <br>
                    <p>Saygılarımızla,<br>
                    <strong>Necat Derneği</strong></p>
                </div>
                <div class="footer">
                    Bu otomatik bir mesajdır. Lütfen yanıtlamayın.
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getVolunteerEmailTemplate($data) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2c5aa0; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 10px; text-align: center; color: #666; font-size: 12px; }
                .info-box { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #2c5aa0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Yeni Gönüllü Başvurusu</h1>
                </div>
                <div class="content">
                    <div class="info-box">
                        <strong>Ad Soyad:</strong> ' . htmlspecialchars($data['first_name'] . ' ' . $data['last_name']) . '<br>
                        <strong>E-posta:</strong> ' . htmlspecialchars($data['email']) . '<br>
                        <strong>Telefon:</strong> ' . htmlspecialchars($data['phone']) . '<br>
                        <strong>Yaş:</strong> ' . htmlspecialchars($data['age']) . '<br>
                        <strong>Şehir:</strong> ' . htmlspecialchars($data['city']) . '<br>
                        <strong>İlgi Alanı:</strong> ' . htmlspecialchars($data['interest_area']) . '<br>
                        <strong>Deneyim:</strong> ' . htmlspecialchars($data['experience']) . '<br>
                        <strong>Tarih:</strong> ' . date('d.m.Y H:i:s') . '
                    </div>
                    <div class="info-box">
                        <strong>Motivasyon:</strong><br>
                        ' . nl2br(htmlspecialchars($data['motivation'])) . '
                    </div>
                </div>
                <div class="footer">
                    Bu başvuru Necat Derneği web sitesi gönüllü formu aracılığıyla gönderilmiştir.
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getVolunteerAutoReplyTemplate($data) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2c5aa0; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 10px; text-align: center; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Gönüllü Başvurunuz Alındı</h1>
                </div>
                <div class="content">
                    <p>Sayın ' . htmlspecialchars($data['first_name']) . ',</p>
                    <p>Necat Derneği gönüllü başvurunuz tarafımıza ulaşmıştır. Başvurunuz değerlendirildikten sonra size dönüş yapacağız.</p>
                    <p>Derneğimize gösterdiğiniz ilgi için teşekkür ederiz.</p>
                    <br>
                    <p>Saygılarımızla,<br>
                    <strong>Necat Derneği</strong></p>
                </div>
                <div class="footer">
                    Bu otomatik bir mesajdır. Lütfen yanıtlamayın.
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getDonationEmailTemplate($data) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2c5aa0; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 10px; text-align: center; color: #666; font-size: 12px; }
                .info-box { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #2c5aa0; }
                .amount { font-size: 24px; color: #2c5aa0; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Yeni Bağış Alındı</h1>
                </div>
                <div class="content">
                    <div class="info-box">
                        <div class="amount">' . number_format($data['amount'], 2) . ' TL</div>
                        <strong>Bağışçı:</strong> ' . htmlspecialchars($data['donor_name']) . '<br>
                        <strong>E-posta:</strong> ' . htmlspecialchars($data['email'] ?? 'Belirtilmemiş') . '<br>
                        <strong>Telefon:</strong> ' . htmlspecialchars($data['phone'] ?? 'Belirtilmemiş') . '<br>
                        <strong>Proje:</strong> ' . htmlspecialchars($data['project_name'] ?? 'Genel Bağış') . '<br>
                        <strong>Ödeme Yöntemi:</strong> ' . htmlspecialchars($data['payment_method'] ?? 'Belirtilmemiş') . '<br>
                        <strong>Tarih:</strong> ' . date('d.m.Y H:i:s') . '
                    </div>
                </div>
                <div class="footer">
                    Bu bağış Necat Derneği web sitesi aracılığıyla yapılmıştır.
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getDonationThankYouTemplate($data) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2c5aa0; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 10px; text-align: center; color: #666; font-size: 12px; }
                .amount { font-size: 24px; color: #2c5aa0; font-weight: bold; text-align: center; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Bağışınız için Teşekkürler</h1>
                </div>
                <div class="content">
                    <p>Sayın ' . htmlspecialchars($data['donor_name']) . ',</p>
                    <div class="amount">' . number_format($data['amount'], 2) . ' TL</div>
                    <p>Derneğimize yapmış olduğunuz değerli bağış için çok teşekkür ederiz. Bu bağış, yardıma muhtaç insanlara ulaşmamızda büyük bir katkı sağlayacaktır.</p>
                    <p>Bağışınız güvenle alınmış ve kayıtlarımıza geçmiştir. Gerekirse vergi indirimi için kullanabileceğiniz bağış belgesi en kısa sürede tarafınıza iletilecektir.</p>
                    <br>
                    <p>Bir kez daha teşekkür eder, saygılarımızı sunarız.</p>
                    <p><strong>Necat Derneği</strong></p>
                </div>
                <div class="footer">
                    Bu otomatik bir mesajdır. Sorularınız için info@necatdernegi.org adresine yazabilirsiniz.
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function getNewsletterTemplate($content) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2c5aa0; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 10px; text-align: center; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Necat Derneği</h1>
                    <p>Bülten</p>
                </div>
                <div class="content">
                    ' . $content . '
                </div>
                <div class="footer">
                    <p>Bu e-postayı almak istemiyorsanız <a href="#">abonelikten çıkabilirsiniz</a>.</p>
                    <p>© ' . date('Y') . ' Necat Derneği. Tüm hakları saklıdır.</p>
                </div>
            </div>
        </body>
        </html>';
    }
}
?>
