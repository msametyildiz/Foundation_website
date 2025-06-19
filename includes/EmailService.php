<?php
require_once __DIR__ . '/../vendor/autoload.php';

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
            $mail->Host = $this->settings['smtp_host'] ?? 'smtp.gmail.com';
            $mail->SMTPAuth = ($this->settings['smtp_auth'] ?? '1') == '1';
            $mail->Username = $this->settings['smtp_username'] ?? 'samet.saray.06@gmail.com';
            $mail->Password = $this->settings['smtp_password'] ?? '';
            $mail->SMTPSecure = $this->settings['smtp_encryption'] ?? 'tls';
            $mail->Port = intval($this->settings['smtp_port'] ?? 587);
            $mail->CharSet = 'UTF-8';
            
            // Debug için SMTP bilgileri logla
            error_log("SMTP Config - Host: " . $mail->Host . ", Username: " . $mail->Username . ", Port: " . $mail->Port);
            
            // Eğer SMTP şifresi yoksa hata fırlat
            if (empty($mail->Password)) {
                throw new Exception("SMTP password is not configured. Please set smtp_password in settings table.");
            }
            
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
            
            // Send to test email address
            $volunteerEmail = 'samet.saray.06@gmail.com'; // Admin'e gönderilen adres
            $mail->addAddress($volunteerEmail);
            
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
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Yeni İletişim Mesajı</title>
            <style>
                body {
                    font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
                }
                .header {
                    background-color: #2c5aa0; /* Primary Blue */
                    color: white;
                    padding: 30px 20px;
                    text-align: center;
                    border-top-left-radius: 8px;
                    border-top-right-radius: 8px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 28px;
                    font-weight: bold;
                }
                .content {
                    padding: 30px;
                    background-color: #ffffff;
                }
                .info-box {
                    background-color: #f9f9f9;
                    padding: 20px;
                    margin-bottom: 20px;
                    border-left: 5px solid #2c5aa0; /* Primary Blue */
                    border-radius: 5px;
                    font-size: 15px;
                }
                .info-box strong {
                    color: #2c5aa0;
                }
                .message-box {
                    background-color: #f9f9f9;
                    padding: 20px;
                    border-left: 5px solid #2c5aa0;
                    border-radius: 5px;
                    font-size: 15px;
                }
                .footer {
                    padding: 20px;
                    text-align: center;
                    color: #777;
                    font-size: 12px;
                    background-color: #f0f0f0;
                    border-bottom-left-radius: 8px;
                    border-bottom-right-radius: 8px;
                }
                .footer a {
                    color: #2c5aa0;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Yeni İletişim Mesajı</h1>
                </div>
                <div class="content">
                    <p style="font-size: 16px; color: #555;">Web siteniz üzerinden yeni bir iletişim formu gönderildi.</p>
                    <div class="info-box">
                        <p><strong>Gönderen:</strong> ' . htmlspecialchars($data['name']) . '</p>
                        <p><strong>E-posta:</strong> <a href="mailto:' . htmlspecialchars($data['email']) . '" style="color: #2c5aa0; text-decoration: none;">' . htmlspecialchars($data['email']) . '</a></p>
                        <p><strong>Telefon:</strong> ' . htmlspecialchars($data['phone'] ?? 'Belirtilmemiş') . '</p>
                        <p><strong>Konu:</strong> ' . htmlspecialchars($data['subject'] ?? 'Genel') . '</p>
                        <p><strong>Tarih:</strong> ' . date('d.m.Y H:i:s') . '</p>
                    </div>
                    <div class="message-box">
                        <p style="margin-top: 0;"><strong>Mesaj:</strong></p>
                        <p style="white-space: pre-wrap;">' . htmlspecialchars($data['message']) . '</p>
                    </div>
                </div>
                <div class="footer">
                    <p>Bu mesaj Necat Derneği web sitesi iletişim formu aracılığıyla gönderilmiştir.</p>
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
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Mesajınız Alındı - Necat Derneği</title>
            <style>
                body {
                    font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
                }
                .header {
                    background-color: #2c5aa0; /* Primary Blue */
                    color: white;
                    padding: 30px 20px;
                    text-align: center;
                    border-top-left-radius: 8px;
                    border-top-right-radius: 8px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 28px;
                    font-weight: bold;
                }
                .content {
                    padding: 30px;
                    background-color: #ffffff;
                }
                .content p {
                    font-size: 16px;
                    color: #555;
                    margin-bottom: 15px;
                }
                .footer {
                    padding: 20px;
                    text-align: center;
                    color: #777;
                    font-size: 12px;
                    background-color: #f0f0f0;
                    border-bottom-left-radius: 8px;
                    border-bottom-right-radius: 8px;
                }
                .footer a {
                    color: #2c5aa0;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Mesajınız Alındı</h1>
                </div>
                <div class="content">
                    <p>Sayın <strong>' . htmlspecialchars($data['name']) . '</strong>,</p>
                    <p>Necat Derneği\'ne göndermiş olduğunuz mesajınız tarafımıza başarıyla ulaşmıştır. En kısa sürede sizinle iletişime geçeceğiz.</p>
                    <p>İlginiz ve anlayışınız için teşekkür ederiz.</p>
                    <p style="margin-top: 30px;">Saygılarımızla,<br>
                    <strong>Necat Derneği Ekibi</strong></p>
                </div>
                <div class="footer">
                    <p>Bu otomatik bir yanıttır, lütfen bu e-postayı doğrudan yanıtlamayınız.</p>
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
        
        // Form verilerindeki alan adlarını kontrol et
        $fullName = $data['name'] ?? ($data['first_name'] . ' ' . $data['last_name']);

        $emailContent = '
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Yeni Gönüllü Başvurusu - Necat Derneği</title>
            <!--[if mso]>
                <noscript>
                    <xml>
                        <o:OfficeDocumentSettings>
                            <o:PixelsPerInch>96</o:PixelsPerInch>
                        </o:OfficeDocumentSettings>
                    </xml>
                </noscript>
            <![endif]-->
            <style>
                /* Modern Email Reset */
                body, table, td, p, a, li, blockquote { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
                table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
                img { -ms-interpolation-mode: bicubic; border: 0; }
                
                /* Responsive */
                @media only screen and (max-width: 640px) {
                    .mobile-center { text-align: center !important; }
                    .mobile-padding { padding: 20px !important; }
                    .mobile-stack { display: block !important; width: 100% !important; }
                    .mobile-hide { display: none !important; }
                }
            </style>
        </head>
        <body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #2d3748; background-color: #f7fafc;">
            
            <!-- Main Container -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f7fafc; padding: 20px 0;">
                <tr>
                    <td align="center">
                        <!-- Email Content -->
                        <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 25px rgba(0,0,0,0.08); overflow: hidden;">
                            
                            <!-- Header with Gradient -->
                            <tr>
                                <td style="background: linear-gradient(135deg, #2d5a27 0%, #4ea674 100%); padding: 40px 30px; text-align: center; color: #ffffff;">
                                    <!-- Logo Section -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="text-align: center; padding-bottom: 20px;">
                                                <div style="background: rgba(255,255,255,0.15); display: inline-block; padding: 12px 24px; border-radius: 50px; backdrop-filter: blur(10px);">
                                                    <span style="font-size: 24px; font-weight: 700; color: #ffffff; letter-spacing: 1px;">🏛️ NECAT DERNEĞİ</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                                <h1 style="margin: 0; font-size: 32px; font-weight: 700; color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">Yeni Gönüllü Başvurusu</h1>
                                                <p style="margin: 12px 0 0 0; font-size: 16px; color: rgba(255,255,255,0.9); font-weight: 500;">Birlikte güçlüyüz, birlikte değişiyoruz</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
            
                            <!-- Main Content -->
                            <tr>
                                <td style="padding: 40px 30px;" class="mobile-padding">
                                    
                                    <!-- Welcome Message -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                                        <tr>
                                            <td style="background: linear-gradient(135deg, #edf2f7 0%, #e2e8f0 100%); padding: 25px; border-radius: 12px; border-left: 5px solid #4ea674;">
                                                <h2 style="margin: 0 0 12px 0; color: #2d5a27; font-size: 20px; font-weight: 700;">👋 Merhaba!</h2>
                                                <p style="margin: 0; color: #4a5568; font-size: 16px; line-height: 1.6;">
                                                    <strong>' . htmlspecialchars($fullName) . '</strong> adlı kişiden yeni bir gönüllü başvurusu alındı. 
                                                    Bu değerli başvuruyu incelerken, başvuru sahibinin motivasyonunu ve deneyimlerini dikkatlice değerlendirmenizi öneriyoruz.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Applicant Information Card -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                                        <tr>
                                            <td style="background: #ffffff; border: 2px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                                
                                                <!-- Card Header -->
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="background: linear-gradient(135deg, #4ea674 0%, #48bb78 100%); padding: 20px; color: #ffffff;">
                                                            <h3 style="margin: 0; font-size: 18px; font-weight: 700;">👤 Başvuru Sahibi Bilgileri</h3>
                                                        </td>
                                                    </tr>
                                                </table>
                                                
                                                <!-- Card Content -->
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="padding: 25px;">
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                <tr>
                                                                    <td style="padding: 8px 0; border-bottom: 1px solid #f7fafc;">
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                            <tr>
                                                                                <td width="40%" style="font-weight: 700; color: #2d5a27; font-size: 14px; vertical-align: top;">
                                                                                    📝 Ad Soyad:
                                                                                </td>
                                                                                <td style="color: #4a5568; font-size: 14px; font-weight: 600;">
                                                                                    ' . htmlspecialchars($fullName) . '
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding: 8px 0; border-bottom: 1px solid #f7fafc;">
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                            <tr>
                                                                                <td width="40%" style="font-weight: 700; color: #2d5a27; font-size: 14px; vertical-align: top;">
                                                                                    📧 E-posta:
                                                                                </td>
                                                                                <td style="color: #4a5568; font-size: 14px;">
                                                                                    <a href="mailto:' . htmlspecialchars($data['email']) . '" style="color: #3182ce; text-decoration: none; font-weight: 600;">' . htmlspecialchars($data['email']) . '</a>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding: 8px 0; border-bottom: 1px solid #f7fafc;">
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                            <tr>
                                                                                <td width="40%" style="font-weight: 700; color: #2d5a27; font-size: 14px; vertical-align: top;">
                                                                                    📱 Telefon:
                                                                                </td>
                                                                                <td style="color: #4a5568; font-size: 14px; font-weight: 600;">
                                                                                    <a href="tel:' . htmlspecialchars($data['phone']) . '" style="color: #3182ce; text-decoration: none;">' . htmlspecialchars($data['phone']) . '</a>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding: 8px 0; border-bottom: 1px solid #f7fafc;">
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                            <tr>
                                                                                <td width="40%" style="font-weight: 700; color: #2d5a27; font-size: 14px; vertical-align: top;">
                                                                                    🎂 Yaş:
                                                                                </td>
                                                                                <td style="color: #4a5568; font-size: 14px; font-weight: 600;">
                                                                                    ' . htmlspecialchars($data['age'] ?? 'Belirtilmemiş') . '
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding: 8px 0; border-bottom: 1px solid #f7fafc;">
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                            <tr>
                                                                                <td width="40%" style="font-weight: 700; color: #2d5a27; font-size: 14px; vertical-align: top;">
                                                                                    💼 Meslek:
                                                                                </td>
                                                                                <td style="color: #4a5568; font-size: 14px; font-weight: 600;">
                                                                                    ' . htmlspecialchars($data['profession'] ?? 'Belirtilmemiş') . '
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding: 8px 0; border-bottom: 1px solid #f7fafc;">
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                            <tr>
                                                                                <td width="40%" style="font-weight: 700; color: #2d5a27; font-size: 14px; vertical-align: top;">
                                                                                    ⏰ Müsaitlik:
                                                                                </td>
                                                                                <td style="color: #4a5568; font-size: 14px; font-weight: 600;">
                                                                                    ' . htmlspecialchars($availabilityText) . '
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding: 8px 0; border-bottom: 1px solid #f7fafc;">
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                            <tr>
                                                                                <td width="40%" style="font-weight: 700; color: #2d5a27; font-size: 14px; vertical-align: top;">
                                                                                    🎯 İlgi Alanları:
                                                                                </td>
                                                                                <td style="color: #4a5568; font-size: 14px; font-weight: 500;">
                                                                                    ' . htmlspecialchars($data['interests'] ?? 'Belirtilmemiş') . '
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding: 8px 0;">
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                            <tr>
                                                                                <td width="40%" style="font-weight: 700; color: #2d5a27; font-size: 14px; vertical-align: top;">
                                                                                    📅 Başvuru Tarihi:
                                                                                </td>
                                                                                <td style="color: #4a5568; font-size: 14px; font-weight: 600;">
                                                                                    ' . date('d.m.Y H:i:s') . '
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>';
            
        if (!empty($data['experience'])) {
            $emailContent .= '
                                    <!-- Experience Section -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                                        <tr>
                                            <td style="background: #ffffff; border: 2px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                                
                                                <!-- Section Header -->
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="background: linear-gradient(135deg, #ed8936 0%, #f6ad55 100%); padding: 20px; color: #ffffff;">
                                                            <h3 style="margin: 0; font-size: 18px; font-weight: 700;">🏆 Gönüllülük Deneyimi</h3>
                                                        </td>
                                                    </tr>
                                                </table>
                                                
                                                <!-- Section Content -->
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="padding: 25px;">
                                                            <div style="background: #fffaf0; padding: 20px; border-radius: 8px; border-left: 4px solid #ed8936; font-size: 15px; line-height: 1.7; color: #4a5568;">
                                                                ' . nl2br(htmlspecialchars($data['experience'])) . '
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>';
        }
        
        $emailContent .= '
                                    <!-- Motivation Section -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                                        <tr>
                                            <td style="background: #ffffff; border: 2px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                                
                                                <!-- Section Header -->
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="background: linear-gradient(135deg, #805ad5 0%, #9f7aea 100%); padding: 20px; color: #ffffff;">
                                                            <h3 style="margin: 0; font-size: 18px; font-weight: 700;">💭 Motivasyon ve Beklentiler</h3>
                                                        </td>
                                                    </tr>
                                                </table>
                                                
                                                <!-- Section Content -->
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="padding: 25px;">
                                                            <div style="background: #faf5ff; padding: 20px; border-radius: 8px; border-left: 4px solid #805ad5; font-size: 15px; line-height: 1.7; color: #4a5568;">
                                                                ' . nl2br(htmlspecialchars($data['message'] ?? $data['motivation'] ?? 'Belirtilmemiş')) . '
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Action Steps -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                                        <tr>
                                            <td style="background: #ffffff; border: 2px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                                
                                                <!-- Section Header -->
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="background: linear-gradient(135deg, #3182ce 0%, #4299e1 100%); padding: 20px; color: #ffffff;">
                                                            <h3 style="margin: 0; font-size: 18px; font-weight: 700;">📋 Önerilen Sonraki Adımlar</h3>
                                                        </td>
                                                    </tr>
                                                </table>
                                                
                                                <!-- Steps Content -->
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="padding: 25px;">
                                                            
                                                            <!-- Step 1 -->
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 20px;">
                                                                <tr>
                                                                    <td width="50" style="vertical-align: top; padding-right: 15px;">
                                                                        <div style="background: #3182ce; color: #ffffff; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; text-align: center; line-height: 35px;">1</div>
                                                                    </td>
                                                                    <td style="vertical-align: top;">
                                                                        <h4 style="margin: 0 0 8px 0; color: #2d5a27; font-size: 16px; font-weight: 700;">İlk İnceleme</h4>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 14px; line-height: 1.6;">Başvuruyu detaylı şekilde inceleyin ve başvuru sahibinin profilini değerlendirin.</p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            
                                                            <!-- Step 2 -->
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 20px;">
                                                                <tr>
                                                                    <td width="50" style="vertical-align: top; padding-right: 15px;">
                                                                        <div style="background: #3182ce; color: #ffffff; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; text-align: center; line-height: 35px;">2</div>
                                                                    </td>
                                                                    <td style="vertical-align: top;">
                                                                        <h4 style="margin: 0 0 8px 0; color: #2d5a27; font-size: 16px; font-weight: 700;">İletişime Geçin</h4>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 14px; line-height: 1.6;">Telefon veya e-posta yoluyla başvuru sahibi ile iletişim kurarak tanışma görüşmesi planlayın.</p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            
                                                            <!-- Step 3 -->
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 20px;">
                                                                <tr>
                                                                    <td width="50" style="vertical-align: top; padding-right: 15px;">
                                                                        <div style="background: #3182ce; color: #ffffff; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; text-align: center; line-height: 35px;">3</div>
                                                                    </td>
                                                                    <td style="vertical-align: top;">
                                                                        <h4 style="margin: 0 0 8px 0; color: #2d5a27; font-size: 16px; font-weight: 700;">Oryantasyon Planlayın</h4>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 14px; line-height: 1.6;">Derneğimizin çalışmaları ve gönüllülük fırsatları hakkında bilgilendirme yapın.</p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            
                                                            <!-- Step 4 -->
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                <tr>
                                                                    <td width="50" style="vertical-align: top; padding-right: 15px;">
                                                                        <div style="background: #3182ce; color: #ffffff; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; text-align: center; line-height: 35px;">4</div>
                                                                    </td>
                                                                    <td style="vertical-align: top;">
                                                                        <h4 style="margin: 0 0 8px 0; color: #2d5a27; font-size: 16px; font-weight: 700;">Görev Ataması</h4>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 14px; line-height: 1.6;">İlgi alanları ve müsaitlik durumuna göre uygun gönüllülük görevlerini belirleyin.</p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Quick Actions -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                                        <tr>
                                            <td style="text-align: center; padding: 20px; background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%); border-radius: 12px; border: 1px solid #e2e8f0;">
                                                <h3 style="margin: 0 0 20px 0; color: #2d5a27; font-size: 18px; font-weight: 700;">⚡ Hızlı İşlemler</h3>
                                                <table cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto;">
                                                    <tr>
                                                        <td style="padding: 0 10px;">
                                                            <a href="mailto:' . htmlspecialchars($data['email']) . '" style="display: inline-block; background: linear-gradient(135deg, #4ea674 0%, #48bb78 100%); color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; box-shadow: 0 2px 8px rgba(78, 166, 116, 0.3);">
                                                                📧 E-posta Gönder
                                                            </a>
                                                        </td>
                                                        <td style="padding: 0 10px;">
                                                            <a href="tel:' . htmlspecialchars($data['phone']) . '" style="display: inline-block; background: linear-gradient(135deg, #3182ce 0%, #4299e1 100%); color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; box-shadow: 0 2px 8px rgba(49, 130, 206, 0.3);">
                                                                📱 Hemen Ara
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            <!-- Professional Footer -->
                            <tr>
                                <td style="background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); padding: 30px; text-align: center; color: #ffffff;">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="text-align: center; padding-bottom: 20px;">
                                                <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #ffffff;">Necat Derneği</h3>
                                                <p style="margin: 8px 0; font-size: 14px; color: rgba(255,255,255,0.8);">Elinizi İyiliğe Uzatın</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 20px;">
                                                <p style="margin: 0 0 8px 0; font-size: 12px; color: rgba(255,255,255,0.7);">
                                                    Bu e-posta Necat Derneği gönüllü başvuru sistemi tarafından otomatik olarak gönderilmiştir.
                                                </p>
                                                <p style="margin: 0; font-size: 11px; color: rgba(255,255,255,0.5);">
                                                     • ' . date('Y') . ' © Tüm hakları saklıdır.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>';
        
        return $emailContent;
    }
    
    private function getVolunteerAutoReplyTemplate($data) {
        return '
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Gönüllü Başvurunuz Alındı - Necat Derneği</title>
            <!--[if mso]>
                <noscript>
                    <xml>
                        <o:OfficeDocumentSettings>
                            <o:PixelsPerInch>96</o:PixelsPerInch>
                        </o:OfficeDocumentSettings>
                    </xml>
                </noscript>
            <![endif]-->
            <style>
                /* Modern Email Reset */
                body, table, td, p, a, li, blockquote { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
                table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
                img { -ms-interpolation-mode: bicubic; border: 0; }
                
                /* Responsive */
                @media only screen and (max-width: 640px) {
                    .mobile-center { text-align: center !important; }
                    .mobile-padding { padding: 20px !important; }
                    .mobile-stack { display: block !important; width: 100% !important; }
                    .mobile-hide { display: none !important; }
                    .mobile-font-large { font-size: 24px !important; }
                    .mobile-font-medium { font-size: 16px !important; }
                }
            </style>
        </head>
        <body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #2d3748; background-color: #f8fafc;">
            
            <!-- Main Container -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; padding: 20px 0;">
                <tr>
                    <td align="center">
                        <!-- Email Content -->
                        <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.12); overflow: hidden;">
                            
                            <!-- Header with Enhanced Gradient -->
                            <tr>
                                <td style="background: linear-gradient(135deg, #1a5d1a 0%, #2d5a27 25%, #4ea674 75%, #68d391 100%); padding: 40px 30px; text-align: center; color: #ffffff; position: relative;">
                                    <!-- Decorative Elements -->
                                    <div style="position: absolute; top: 10px; left: 20px; opacity: 0.2; font-size: 60px;">🌟</div>
                                    <div style="position: absolute; top: 20px; right: 30px; opacity: 0.15; font-size: 40px;">✨</div>
                                    
                                    <!-- Logo Section -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="text-align: center; padding-bottom: 25px;">
                                                <div style="background: rgba(255,255,255,0.2); display: inline-block; padding: 15px 30px; border-radius: 50px; backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.3);">
                                                    <span style="font-size: 26px; font-weight: 800; color: #ffffff; letter-spacing: 2px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">🏛️ NECAT DERNEĞİ</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                               
                                                <p style="margin: 15px 0 0 0; font-size: 20px; color: rgba(255,255,255,0.95); font-weight: 600; text-shadow: 0 1px 3px rgba(0,0,0,0.2);" class="mobile-font-medium">
                                                    Gönüllülük Yolculuğunuz Başlıyor!
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            
                            
                            <!-- Main Content -->
                            <tr>
                                <td style="padding: 40px 30px;" class="mobile-padding">
                                    
                                    <!-- Welcome Message -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                                        <tr>
                                            <td style="background: linear-gradient(135deg, #e6fffa 0%, #b2f5ea 100%); padding: 30px; border-radius: 12px; border-left: 5px solid #4ea674; text-align: center;">
                                                <div style="margin-bottom: 20px;">
                                                    <span style="font-size: 48px;">🤝</span>
                                                </div>
                                                <h2 style="margin: 0 0 15px 0; color: #2d5a27; font-size: 24px; font-weight: 700;">Sayın ' . htmlspecialchars($data['first_name']) . ',</h2>
                                                <p style="margin: 0 0 15px 0; color: #2d5a27; font-size: 18px; line-height: 1.6; font-weight: 600;">
                                                    Necat Derneği gönüllü ailesine katılım başvurunuzu başarıyla aldık!
                                                </p>
                                                <p style="margin: 0; color: #4a5568; font-size: 16px; line-height: 1.6;">
                                                    Bu değerli adımınız için size içtenlikle teşekkür ederiz. Gönüllülük, sadece başkalarına yardım etmekle kalmaz, aynı zamanda topluma anlamlı bir değer katarken kişisel gelişiminize de katkıda bulunur.
                                                </p>
                                                <div style="margin-top: 20px; padding: 15px; background: rgba(255,255,255,0.7); border-radius: 8px;">
                                                    <p style="margin: 0; font-style: italic; color: #2d5a27; font-size: 16px; font-weight: 600;">
                                                        "Bir mum, diğer mumu tutuşturmakle ışığından bir şey kaybetmez."
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Next Steps Section -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                                        <tr>
                                            <td style="background: #ffffff; border: 2px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                                
                                                <!-- Section Header -->
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="background: linear-gradient(135deg, #3182ce 0%, #4299e1 100%); padding: 20px; color: #ffffff;">
                                                            <h3 style="margin: 0; font-size: 20px; font-weight: 700;">📋 Sonraki Adımlarımız</h3>
                                                        </td>
                                                    </tr>
                                                </table>
                                                
                                                <!-- Steps Content -->
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="padding: 30px;">
                                                            
                                                            <!-- Step 1 -->
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 25px;">
                                                                <tr>
                                                                    <td width="60" style="vertical-align: top; padding-right: 20px;">
                                                                        <div style="background: linear-gradient(135deg, #4ea674 0%, #48bb78 100%); color: #ffffff; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; text-align: center; line-height: 45px; box-shadow: 0 4px 12px rgba(78, 166, 116, 0.3);">1</div>
                                                                    </td>
                                                                    <td style="vertical-align: top;">
                                                                        <h4 style="margin: 0 0 10px 0; color: #2d5a27; font-size: 18px; font-weight: 700;">🔍 Değerlendirme Süreci</h4>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 15px; line-height: 1.6;">Başvurunuz <strong>3-5 iş günü</strong> içinde ekibimiz tarafından titizlikle değerlendirilecektir. Bu süreçte profiliniz, deneyimleriniz ve ilgi alanlarınız detaylı şekilde incelenecektir.</p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            
                                                            <!-- Step 2 -->
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 25px;">
                                                                <tr>
                                                                    <td width="60" style="vertical-align: top; padding-right: 20px;">
                                                                        <div style="background: linear-gradient(135deg, #ed8936 0%, #f6ad55 100%); color: #ffffff; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; text-align: center; line-height: 45px; box-shadow: 0 4px 12px rgba(237, 137, 54, 0.3);">2</div>
                                                                    </td>
                                                                    <td style="vertical-align: top;">
                                                                        <h4 style="margin: 0 0 10px 0; color: #2d5a27; font-size: 18px; font-weight: 700;">📞 İletişim ve Tanışma</h4>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 15px; line-height: 1.6;">Değerlendirme sonrası size <strong>telefon veya e-posta</strong> yoluyla ulaşarak kısa bir tanışma görüşmesi yapacağız. Bu görüşmede beklentileriniz ve sorularınız yanıtlanacaktır.</p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            
                                                            <!-- Step 3 -->
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 25px;">
                                                                <tr>
                                                                    <td width="60" style="vertical-align: top; padding-right: 20px;">
                                                                        <div style="background: linear-gradient(135deg, #805ad5 0%, #9f7aea 100%); color: #ffffff; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; text-align: center; line-height: 45px; box-shadow: 0 4px 12px rgba(128, 90, 213, 0.3);">3</div>
                                                                    </td>
                                                                    <td style="vertical-align: top;">
                                                                        <h4 style="margin: 0 0 10px 0; color: #2d5a27; font-size: 18px; font-weight: 700;">🎓 Oryantasyon Programı</h4>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 15px; line-height: 1.6;">Derneğimizin <strong>çalışmaları, projeleri ve gönüllülük fırsatları</strong> hakkında detaylı bilgi vereceğiz. Bu eğitim ile gönüllülük yolculuğunuza hazır hale geleceksiniz.</p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            
                                                            <!-- Step 4 -->
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                <tr>
                                                                    <td width="60" style="vertical-align: top; padding-right: 20px;">
                                                                        <div style="background: linear-gradient(135deg, #38a169 0%, #48bb78 100%); color: #ffffff; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; text-align: center; line-height: 45px; box-shadow: 0 4px 12px rgba(56, 161, 105, 0.3);">4</div>
                                                                    </td>
                                                                    <td style="vertical-align: top;">
                                                                        <h4 style="margin: 0 0 10px 0; color: #2d5a27; font-size: 18px; font-weight: 700;">🎯 Görev Ataması</h4>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 15px; line-height: 1.6;"><strong>İlgi alanlarınıza ve müsaitlik</strong> durumunuza en uygun gönüllülük görevlerini sizinle birlikte belirleyeceğiz. Böylece en verimli şekilde katkıda bulunabileceksiniz.</p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- What to Expect Section -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                                        <tr>
                                            <td style="background: #ffffff; border: 2px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                                
                                                <!-- Section Header -->
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="background: linear-gradient(135deg, #9f7aea 0%, #b794f6 100%); padding: 20px; color: #ffffff;">
                                                            <h3 style="margin: 0; font-size: 20px; font-weight: 700;">🌟 Neleri Bekleyebilirsiniz?</h3>
                                                        </td>
                                                    </tr>
                                                </table>
                                                
                                                <!-- What to Expect Content -->
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="padding: 25px;">
                                                            
                                                            <!-- Benefit 1 -->
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 15px;">
                                                                <tr>
                                                                    <td width="30" style="vertical-align: top; padding-right: 15px;">
                                                                        <span style="font-size: 20px;">💡</span>
                                                                    </td>
                                                                    <td style="vertical-align: top;">
                                                                        <h4 style="margin: 0 0 5px 0; color: #2d5a27; font-size: 16px; font-weight: 700;">Kişisel Gelişim Fırsatları</h4>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 14px; line-height: 1.6;">Yeni beceriler kazanın, liderlik yeteneklerinizi geliştirin ve farklı deneyimler yaşayın.</p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            
                                                            <!-- Benefit 2 -->
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 15px;">
                                                                <tr>
                                                                    <td width="30" style="vertical-align: top; padding-right: 15px;">
                                                                        <span style="font-size: 20px;">🤝</span>
                                                                    </td>
                                                                    <td style="vertical-align: top;">
                                                                        <h4 style="margin: 0 0 5px 0; color: #2d5a27; font-size: 16px; font-weight: 700;">Anlamlı Bağlantılar</h4>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 14px; line-height: 1.6;">Benzer değerleri paylaşan kişilerle tanışın ve kalıcı dostluklar kurun.</p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            
                                                            <!-- Benefit 3 -->
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 15px;">
                                                                <tr>
                                                                    <td width="30" style="vertical-align: top; padding-right: 15px;">
                                                                        <span style="font-size: 20px;">🎯</span>
                                                                    </td>
                                                                    <td style="vertical-align: top;">
                                                                        <h4 style="margin: 0 0 5px 0; color: #2d5a27; font-size: 16px; font-weight: 700;">Toplumsal Etki</h4>
                                                                        <p style="margin: 0; color: #4a5568; font-size: 14px; line-height: 1.6;">Toplumda gerçek bir fark yaratın ve çevrenizde olumlu değişimlere katkıda bulunun.</p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Contact Information -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                                        <tr>
                                            <td style="background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%); padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center;">
                                                <h3 style="margin: 0 0 20px 0; color: #2d5a27; font-size: 20px; font-weight: 700;">📞 İletişim Bilgileri</h3>
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td style="text-align: center; padding: 10px;">
                                                            <p style="margin: 0; font-size: 15px; color: #4a5568;">
                                                                <strong style="color: #2d5a27;">Gönüllü Koordinasyon:</strong><br>
                                                                <a href="mailto:gonullu@necatdernegi.org" style="color: #3182ce; text-decoration: none; font-weight: 600;">gonullu@necatdernegi.org</a>
                                                            </p>
                                                        </td>
                                                        <td style="text-align: center; padding: 10px;">
                                                            <p style="margin: 0; font-size: 15px; color: #4a5568;">
                                                                <strong style="color: #2d5a27;">Telefon:</strong><br>
                                                                <a href="tel:+903123116525" style="color: #3182ce; text-decoration: none; font-weight: 600;">+90 312 311 65 25</a>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <div style="margin-top: 20px;">
                                                    <p style="margin: 0; font-size: 14px; color: #4a5568;">
                                                        <strong style="color: #2d5a27;">Çalışma Saatleri:</strong> Pazartesi - Cuma: 09:00 - 17:00
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Motivational Quote -->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                                        <tr>
                                            <td style="background: linear-gradient(135deg, #4ea674 0%, #48bb78 100%); padding: 30px; border-radius: 12px; text-align: center; color: #ffffff;">
                                                <div style="margin-bottom: 15px;">
                                                    <span style="font-size: 40px;">💪</span>
                                                </div>
                                                <h3 style="margin: 0 0 15px 0; font-size: 22px; font-weight: 700;">Birlikte Daha Güçlüyüz!</h3>
                                                <p style="margin: 0; font-size: 16px; line-height: 1.6; color: rgba(255,255,255,0.95);">
                                                    Bu güzel yolculukta bizimle birlikte olduğunuz için tekrar teşekkür ederiz. 
                                                    Sizin gibi değerli insanlarla birlikte, daha güzel bir dünya inşa etmeye devam edeceğiz.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            <!-- Professional Footer -->
                            <tr>
                                <td style="background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); padding: 30px; text-align: center; color: #ffffff;">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="text-align: center; padding-bottom: 20px;">
                                                <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #ffffff;">Necat Derneği</h3>
                                                <p style="margin: 8px 0; font-size: 16px; color: rgba(255,255,255,0.9); font-weight: 600;">Elinizi İyiliğe Uzatın</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 20px;">
                                                <p style="margin: 0 0 8px 0; font-size: 12px; color: rgba(255,255,255,0.7);">
                                                    Bu e-posta Necat Derneği gönüllü başvuru sistemi tarafından otomatik olarak gönderilmiştir.
                                                </p>
                                                <p style="margin: 0; font-size: 11px; color: rgba(255,255,255,0.5);">
                                                     • ' . date('Y') . ' © Tüm hakları saklıdır.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>';
    }
    private function getDonationEmailTemplate($data) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Yeni Bağış Alındı</title>
            <style>
                body {
                    font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
                }
                .header {
                    background-color: #2c5aa0; /* Primary Blue */
                    color: white;
                    padding: 30px 20px;
                    text-align: center;
                    border-top-left-radius: 8px;
                    border-top-right-radius: 8px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 28px;
                    font-weight: bold;
                }
                .content {
                    padding: 30px;
                    background-color: #ffffff;
                }
                .info-box {
                    background-color: #f9f9f9;
                    padding: 20px;
                    margin-bottom: 20px;
                    border-left: 5px solid #2c5aa0; /* Primary Blue */
                    border-radius: 5px;
                    font-size: 15px;
                }
                .info-box strong {
                    color: #2c5aa0;
                }
                .info-box p {
                    margin: 5px 0;
                }
                .amount-display {
                    font-size: 32px;
                    color: #2c5aa0;
                    font-weight: bold;
                    text-align: center;
                    margin: 25px 0;
                    padding: 15px;
                    background-color: #e6f0fa; /* Light Blue */
                    border-radius: 8px;
                }
                .footer {
                    padding: 20px;
                    text-align: center;
                    color: #777;
                    font-size: 12px;
                    background-color: #f0f0f0;
                    border-bottom-left-radius: 8px;
                    border-bottom-right-radius: 8px;
                }
                .footer a {
                    color: #2c5aa0;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Yeni Bağış Alındı</h1>
                </div>
                <div class="content">
                    <p style="font-size: 16px; color: #555; text-align: center;">Necat Derneği\'ne yeni bir bağış yapıldı!</p>
                    <div class="amount-display">
                        ' . number_format($data['amount'], 2) . ' TL
                    </div>
                    <div class="info-box">
                        <p><strong>Bağışçı:</strong> ' . htmlspecialchars($data['donor_name']) . '</p>
                        <p><strong>E-posta:</strong> <a href="mailto:' . htmlspecialchars($data['email'] ?? 'Belirtilmemiş') . '" style="color: #2c5aa0; text-decoration: none;">' . htmlspecialchars($data['email'] ?? 'Belirtilmemiş') . '</a></p>
                        <p><strong>Telefon:</strong> ' . htmlspecialchars($data['phone'] ?? 'Belirtilmemiş') . '</p>
                        <p><strong>Proje:</strong> ' . htmlspecialchars($data['project_name'] ?? 'Genel Bağış') . '</p>
                        <p><strong>Ödeme Yöntemi:</strong> ' . htmlspecialchars($data['payment_method'] ?? 'Belirtilmemiş') . '</p>
                        <p><strong>Tarih:</strong> ' . date('d.m.Y H:i:s') . '</p>
                    </div>
                </div>
                <div class="footer">
                    <p>Bu bağış Necat Derneği web sitesi aracılığıyla yapılmıştır.</p>
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
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Bağışınız için Teşekkürler - Necat Derneği</title>
            <style>
                body {
                    font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
                }
                .header {
                    background-color: #2c5aa0; /* Primary Blue */
                    color: white;
                    padding: 30px 20px;
                    text-align: center;
                    border-top-left-radius: 8px;
                    border-top-right-radius: 8px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 28px;
                    font-weight: bold;
                }
                .content {
                    padding: 30px;
                    background-color: #ffffff;
                }
                .content p {
                    font-size: 16px;
                    color: #555;
                    margin-bottom: 15px;
                }
                .thank-you-message {
                    background-color: #e6f0fa; /* Light Blue */
                    padding: 25px;
                    margin-bottom: 20px;
                    border-radius: 8px;
                    border-left: 5px solid #2c5aa0;
                    text-align: center;
                }
                .thank-you-message h2 {
                    color: #2c5aa0;
                    margin-top: 0;
                    font-size: 24px;
                }
                .amount-display {
                    font-size: 32px;
                    color: #2c5aa0;
                    font-weight: bold;
                    text-align: center;
                    margin: 25px 0;
                }
                .footer {
                    padding: 20px;
                    text-align: center;
                    color: #777;
                    font-size: 12px;
                    background-color: #f0f0f0;
                    border-bottom-left-radius: 8px;
                    border-bottom-right-radius: 8px;
                }
                .footer a {
                    color: #2c5aa0;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Bağışınız İçin Teşekkürler</h1>
                </div>
                <div class="content">
                    <p>Sayın <strong>' . htmlspecialchars($data['donor_name']) . '</strong>,</p>
                    <div class="thank-you-message">
                        <h2>Değerli Bağışınız için Minnettarız!</h2>
                        <p style="font-size: 18px; margin-top: 15px;">Bağışladığınız</p>
                        <div class="amount-display">
                            ' . number_format($data['amount'], 2) . ' TL
                        </div>
                        <p style="font-size: 16px;">yardıma muhtaç insanlara ulaşmamızda büyük bir katkı sağlayacaktır.</p>
                    </div>
                    <p>Bağışınız güvenle alınmış ve kayıtlarımıza geçmiştir. Gerekirse vergi indirimi için kullanabileceğiniz bağış belgesi en kısa sürede tarafınıza iletilecektir.</p>
                    <p style="margin-top: 30px;">Desteğiniz için bir kez daha içtenlikle teşekkür eder, saygılarımızı sunarız.</p>
                    <p><strong>Necat Derneği Ekibi</strong></p>
                </div>
                <div class="footer">
                    <p>Bu otomatik bir mesajdır. Sorularınız için <a href="mailto:info@necatdernegi.org" style="color: #2c5aa0;">info@necatdernegi.org</a> adresine yazabilirsiniz.</p>
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
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Necat Derneği Bülteni</title>
            <style>
                body {
                    font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
                }
                .header {
                    background-color: #2c5aa0; /* Primary Blue */
                    color: white;
                    padding: 30px 20px;
                    text-align: center;
                    border-top-left-radius: 8px;
                    border-top-right-radius: 8px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 32px;
                    font-weight: bold;
                }
                .header p {
                    margin-top: 5px;
                    font-size: 18px;
                    opacity: 0.9;
                }
                .content {
                    padding: 30px;
                    background-color: #ffffff;
                    font-size: 16px;
                    color: #444;
                }
                .content h2 {
                    color: #2c5aa0;
                    font-size: 24px;
                    margin-top: 25px;
                    margin-bottom: 15px;
                }
                .content p {
                    margin-bottom: 15px;
                }
                .button {
                    display: inline-block;
                    background-color: #4ea674; /* Volunteer Green for CTA */
                    color: white;
                    padding: 12px 25px;
                    border-radius: 5px;
                    text-decoration: none;
                    font-weight: bold;
                    margin-top: 20px;
                }
                .footer {
                    padding: 20px;
                    text-align: center;
                    color: #777;
                    font-size: 12px;
                    background-color: #f0f0f0;
                    border-bottom-left-radius: 8px;
                    border-bottom-right-radius: 8px;
                }
                .footer a {
                    color: #2c5aa0;
                    text-decoration: none;
                }
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
                    <p style="text-align: center; margin-top: 30px;">
                        <a href="https://yourwebsite.com" class="button">Web Sitemizi Ziyaret Edin</a>
                    </p>
                </div>
                <div class="footer">
                    <p>Bu e-postayı almak istemiyorsanız <a href="#">abonelikten çıkabilirsiniz</a>.</p>
                    <p>&copy; ' . date('Y') . ' Necat Derneği. Tüm hakları saklıdır.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Send a test email to verify configuration
     */
    public function sendTestEmail($to, $subject = 'Test Email', $message = 'This is a test email.') {
        try {
            $mail = $this->createMailer();
            
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            
            $body = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Test E-postası - Necat Derneği</title>
                <style>
                    body {
                        font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                        margin: 0;
                        padding: 0;
                        background-color: #f4f4f4;
                    }
                    .container {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #ffffff;
                        border-radius: 8px;
                        overflow: hidden;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
                    }
                    .header {
                        background-color: #e74c3c; /* Red for Test */
                        color: white;
                        padding: 30px 20px;
                        text-align: center;
                        border-top-left-radius: 8px;
                        border-top-right-radius: 8px;
                    }
                    .header h2 {
                        margin: 0;
                        font-size: 28px;
                        font-weight: bold;
                    }
                    .content {
                        padding: 30px;
                        background-color: #ffffff;
                    }
                    .content p {
                        font-size: 16px;
                        color: #555;
                        margin-bottom: 15px;
                    }
                    .info-detail {
                        background-color: #f9f9f9;
                        padding: 15px;
                        border-radius: 5px;
                        margin-bottom: 10px;
                        font-size: 14px;
                    }
                    .info-detail strong {
                        color: #e74c3c;
                    }
                    .success-box {
                        background-color: #d4edda; /* Light Green */
                        color: #155724; /* Dark Green */
                        padding: 20px;
                        border-radius: 8px;
                        margin-top: 25px;
                        text-align: center;
                        font-size: 18px;
                        font-weight: bold;
                        border: 1px solid #c3e6cb;
                    }
                    .footer {
                        padding: 20px;
                        text-align: center;
                        color: #777;
                        font-size: 12px;
                        background-color: #f0f0f0;
                        border-bottom-left-radius: 8px;
                        border-bottom-right-radius: 8px;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>🧪 Test E-postası - Necat Derneği</h2>
                    </div>
                    <div class="content">
                        <p>' . $message . '</p>
                        <div class="info-detail">
                            <p><strong>Test Zamanı:</strong> ' . date('Y-m-d H:i:s') . '</p>
                            <p><strong>SMTP Sunucusu:</strong> ' . ($this->settings['smtp_host'] ?? 'N/A') . '</p>
                            <p><strong>Gönderen:</strong> ' . ($this->settings['smtp_username'] ?? 'N/A') . '</p>
                        </div>
                        <div class="success-box">
                            ✅ E-posta yapılandırmanız doğru çalışıyor!
                        </div>
                    </div>
                    <div class="footer">
                        <p>Bu otomatik bir test mesajıdır.</p>
                    </div>
                </div>
            </body>
            </html>';
            
            $mail->Body = $body;
            $mail->AltBody = strip_tags($message) . "\n\nTime: " . date('Y-m-d H:i:s') . "\nSMTP Host: " . ($this->settings['smtp_host'] ?? 'N/A');
            
            $mail->send();
            
            return ['success' => true, 'message' => 'Test email sent successfully'];
            
        } catch (Exception $e) {
            error_log("Test email error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>