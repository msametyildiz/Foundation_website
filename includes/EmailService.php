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
            
            // Send to volunteer department
            $volunteerEmail = 'samet.saray.06@gmail.com';
            $mail->addAddress($volunteerEmail);
            
            // Also send to admin as backup
            $adminEmail = $this->settings['admin_email'] ?? 'samet.saray.06@gmail.com';
            if ($adminEmail !== $volunteerEmail) {
                $mail->addAddress($adminEmail);
            }
            
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
        // Convert availability codes to Turkish
        $availabilityText = '';
        switch($data['availability'] ?? '') {
            case 'weekdays':
                $availabilityText = 'Hafta içi (Pazartesi-Cuma)';
                break;
            case 'weekends':
                $availabilityText = 'Hafta sonu (Cumartesi-Pazar)';
                break;
            case 'evenings':
                $availabilityText = 'Akşam saatleri (18:00 sonrası)';
                break;
            case 'flexible':
                $availabilityText = 'Esnek (Her zaman müsait)';
                break;
            case 'mornings':
                $availabilityText = 'Sabah saatleri (09:00-12:00)';
                break;
            case 'afternoons':
                $availabilityText = 'Öğleden sonra (13:00-17:00)';
                break;
            default:
                $availabilityText = $data['availability'] ?? 'Belirtilmemiş';
        }

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #4ea674; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 10px; text-align: center; color: #666; font-size: 12px; }
                .info-box { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #4ea674; }
                .highlight { background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🌟 Yeni Gönüllü Başvurusu</h1>
                    <p>Necat Derneği Gönüllü Başvuru Sistemi</p>
                </div>
                <div class="content">
                    <div class="highlight">
                        <h3>👤 Başvuru Sahibi Bilgileri</h3>
                    </div>
                    
                    <div class="info-box">
                        <strong>📝 Ad Soyad:</strong> ' . htmlspecialchars($data['first_name'] . ' ' . $data['last_name']) . '<br>
                        <strong>📧 E-posta:</strong> ' . htmlspecialchars($data['email']) . '<br>
                        <strong>📱 Telefon:</strong> ' . htmlspecialchars($data['phone']) . '<br>
                        <strong>🎂 Yaş:</strong> ' . htmlspecialchars($data['age']) . '<br>
                        <strong>💼 Meslek:</strong> ' . htmlspecialchars($data['profession'] ?? 'Belirtilmemiş') . '<br>
                        <strong>⏰ Müsaitlik:</strong> ' . htmlspecialchars($availabilityText) . '<br>
                        <strong>🎯 İlgi Alanları:</strong> ' . htmlspecialchars($data['interests'] ?? 'Belirtilmemiş') . '<br>
                        <strong>📅 Başvuru Tarihi:</strong> ' . date('d.m.Y H:i:s') . '
                    </div>
                    
                    ' . ((!empty($data['experience'])) ? '
                    <div class="highlight">
                        <h3>🏆 Gönüllülük Deneyimi</h3>
                    </div>
                    <div class="info-box">
                        ' . nl2br(htmlspecialchars($data['experience'])) . '
                    </div>
                    ' : '') . '
                    
                    <div class="highlight">
                        <h3>💭 Motivasyon ve Beklentiler</h3>
                    </div>
                    <div class="info-box">
                        ' . nl2br(htmlspecialchars($data['motivation'])) . '
                    </div>
                    
                    <div class="highlight">
                        <h3>📋 Sonraki Adımlar</h3>
                    </div>
                    <div class="info-box">
                        <p>• Başvuru sahibi ile 3-5 iş günü içinde iletişime geçilecek</p>
                        <p>• Kısa bir telefon görüşmesi yapılacak</p>
                        <p>• Uygun gönüllülük alanları belirlenecek</p>
                        <p>• Oryantasyon sürecine dahil edilecek</p>
                    </div>
                </div>
                <div class="footer">
                    Bu başvuru Necat Derneği web sitesi gönüllü formu aracılığıyla gönderilmiştir.<br>
                    Gönüllü Departmanı: gonullu@necatdernegi.org
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
                .header { background: #4ea674; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 15px; text-align: center; color: #666; font-size: 12px; }
                .welcome-box { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; border-left: 4px solid #4ea674; }
                .next-steps { background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 15px 0; }
                .contact-info { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🌟 Hoş Geldiniz!</h1>
                    <p>Necat Derneği Gönüllü Ailesi</p>
                </div>
                <div class="content">
                    <div class="welcome-box">
                        <p>Sayın <strong>' . htmlspecialchars($data['first_name']) . '</strong>,</p>
                        
                        <p>Necat Derneği gönüllü ailesine katılım başvurunuz başarıyla alınmıştır. Bu güzel adımınız için teşekkür ederiz! 🙏</p>
                        
                        <p>Gönüllülük, sadece başkalarına yardım etmek değil, aynı zamanda kendi ruhunuzu beslemek ve topluma değer katmaktır. Siz de bu anlamlı yolculukta bizlerle birlikte olacağınız için çok mutluyuz.</p>
                    </div>
                    
                    <div class="next-steps">
                        <h3>📋 Sonraki Adımlar</h3>
                        <ul>
                            <li><strong>Değerlendirme:</strong> Başvurunuz 3-5 iş günü içinde değerlendirilecek</li>
                            <li><strong>İletişim:</strong> Size telefon ile ulaşarak kısa bir görüşme yapacağız</li>
                            <li><strong>Oryantasyon:</strong> Derneğimiz hakkında detaylı bilgi vereceğiz</li>
                            <li><strong>Görev Atama:</strong> İlgi alanlarınıza uygun gönüllülük fırsatları sunacağız</li>
                        </ul>
                    </div>
                    
                    <div class="welcome-box">
                        <h3>🤝 Neden Gönüllü Olmak Önemli?</h3>
                        <p>Gönüllülük sayesinde:</p>
                        <ul>
                            <li>Toplumda gerçek bir fark yaratacaksınız</li>
                            <li>Yeni insanlarla tanışıp kalıcı dostluklar kuracaksınız</li>
                            <li>Kişisel gelişiminizi destekleyeceksiniz</li>
                            <li>Anlamlı deneyimler edineceksiniz</li>
                        </ul>
                    </div>
                    
                    <div class="contact-info">
                        <h3>📞 İletişim Bilgileri</h3>
                        <p><strong>Gönüllü Koordinasyon:</strong> gonullu@necatdernegi.org</p>
                        <p><strong>Telefon:</strong> +90 312 311 65 25</p>
                        <p><strong>Adres:</strong> Fevzipaşa Mahallesi Rüzgarlı Caddesi Plevne Sokak No:14/1 Ulus Altındağ Ankara</p>
                    </div>
                    
                    <div class="welcome-box">
                        <p><em>"Bir mum, diğer mumu tutuşturmakla ışığından bir şey kaybetmez."</em></p>
                        <p>Bu güzel yolculukta bizimle birlikte olduğunuz için tekrar teşekkür ederiz. Birlikte daha güçlü olacağız! 💪</p>
                    </div>
                </div>
                <div class="footer">
                    Bu e-posta Necat Derneği tarafından otomatik olarak gönderilmiştir.<br>
                    Gönüllü Departmanı: gonullu@necatdernegi.org<br>
                    <strong>Elinizi İyiliğe Uzatın</strong>
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
