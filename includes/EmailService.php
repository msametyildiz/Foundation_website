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

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Yeni Gönüllü Başvurusu</title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4;">
            
            <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                
                <!-- Header -->
                <div style="background: #4ea674; color: white; padding: 30px 20px; text-align: center;">
                    <div style="margin-bottom: 15px;">
                        <img src="https://necatdernegi.org/assets/images/logo.png" alt="Necat Derneği" style="height: 40px; max-width: 200px; vertical-align: middle;" onerror="this.style.display=\'none\'">
                    </div>
                    <h1 style="margin: 0; font-size: 24px;">Yeni Gönüllü Başvurusu</h1>
                    <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">Necat Derneği Gönüllü Başvuru Sistemi</p>
                </div>
                
                <!-- Content -->
                <div style="padding: 30px 25px;">
                    
                    <!-- Applicant Info -->
                    <div style="background: #e8f5e8; color: #2d5016; padding: 12px 15px; margin: 0 0 15px 0; border-radius: 5px; font-weight: bold; font-size: 16px;">
                        👤 Başvuru Sahibi Bilgileri
                    </div>
                    
                    <div style="background: #f9f9f9; padding: 20px; margin: 15px 0; border-left: 4px solid #4ea674; border-radius: 0 5px 5px 0;">
                        <div style="margin: 8px 0; font-size: 14px;">
                            <span style="font-weight: bold; color: #2d5016; display: inline-block; min-width: 120px;">📝 Ad Soyad:</span> ' . htmlspecialchars($fullName) . '
                        </div>
                        <div style="margin: 8px 0; font-size: 14px;">
                            <span style="font-weight: bold; color: #2d5016; display: inline-block; min-width: 120px;">📧 E-posta:</span> ' . htmlspecialchars($data['email']) . '
                        </div>
                        <div style="margin: 8px 0; font-size: 14px;">
                            <span style="font-weight: bold; color: #2d5016; display: inline-block; min-width: 120px;">📱 Telefon:</span> ' . htmlspecialchars($data['phone']) . '
                        </div>
                        <div style="margin: 8px 0; font-size: 14px;">
                            <span style="font-weight: bold; color: #2d5016; display: inline-block; min-width: 120px;">🎂 Yaş:</span> ' . htmlspecialchars($data['age'] ?? 'Belirtilmemiş') . '
                        </div>
                        <div style="margin: 8px 0; font-size: 14px;">
                            <span style="font-weight: bold; color: #2d5016; display: inline-block; min-width: 120px;">💼 Meslek:</span> ' . htmlspecialchars($data['profession'] ?? 'Belirtilmemiş') . '
                        </div>
                        <div style="margin: 8px 0; font-size: 14px;">
                            <span style="font-weight: bold; color: #2d5016; display: inline-block; min-width: 120px;">⏰ Müsaitlik:</span> ' . htmlspecialchars($availabilityText) . '
                        </div>
                        <div style="margin: 8px 0; font-size: 14px;">
                            <span style="font-weight: bold; color: #2d5016; display: inline-block; min-width: 120px;">🎯 İlgi Alanları:</span> ' . htmlspecialchars($data['interests'] ?? 'Belirtilmemiş') . '
                        </div>
                        <div style="margin: 8px 0; font-size: 14px;">
                            <span style="font-weight: bold; color: #2d5016; display: inline-block; min-width: 120px;">📅 Başvuru Tarihi:</span> ' . date('d.m.Y H:i:s') . '
                        </div>
                    </div>';
            
        if (!empty($data['experience'])) {
            $emailContent .= '
            <div style="background: #e8f5e8; color: #2d5016; padding: 12px 15px; margin: 20px 0 15px 0; border-radius: 5px; font-weight: bold; font-size: 16px;">
                🏆 Gönüllülük Deneyimi
            </div>
            <div style="background: #f9f9f9; padding: 20px; margin: 15px 0; border-left: 4px solid #4ea674; border-radius: 0 5px 5px 0;">
                ' . nl2br(htmlspecialchars($data['experience'])) . '
            </div>';
        }
        
        $emailContent .= '
                    <div style="background: #e8f5e8; color: #2d5016; padding: 12px 15px; margin: 20px 0 15px 0; border-radius: 5px; font-weight: bold; font-size: 16px;">
                        💭 Motivasyon ve Beklentiler
                    </div>
                    <div style="background: #f9f9f9; padding: 20px; margin: 15px 0; border-left: 4px solid #4ea674; border-radius: 0 5px 5px 0;">
                        ' . nl2br(htmlspecialchars($data['message'] ?? $data['motivation'] ?? 'Belirtilmemiş')) . '
                    </div>
                    
                    <div style="background: #e8f5e8; color: #2d5016; padding: 12px 15px; margin: 20px 0 15px 0; border-radius: 5px; font-weight: bold; font-size: 16px;">
                        📋 Sonraki Adımlar
                    </div>
                    <div style="background: #f8fffe; padding: 25px; margin: 10px 0; border-radius: 8px; border: 1px solid #e0f0e0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: flex-start; margin-bottom: 18px; padding-bottom: 15px; border-bottom: 1px solid #f0f8f0;">
                            <div style="background: #4ea674; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-right: 15px; flex-shrink: 0;">1</div>
                            <div>
                                <div style="font-weight: bold; color: #2d5016; margin-bottom: 4px; font-size: 15px;">Değerlendirme Süreci</div>
                                <div style="color: #555; font-size: 14px; line-height: 1.5;">Başvurunuz 3-5 iş günü içinde ekibimiz tarafından titizlikle değerlendirilecektir.</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; margin-bottom: 18px; padding-bottom: 15px; border-bottom: 1px solid #f0f8f0;">
                            <div style="background: #4ea674; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-right: 15px; flex-shrink: 0;">2</div>
                            <div>
                                <div style="font-weight: bold; color: #2d5016; margin-bottom: 4px; font-size: 15px;">İletişim ve Tanışma</div>
                                <div style="color: #555; font-size: 14px; line-height: 1.5;">Değerlendirme sonrası size telefon veya e-posta yoluyla ulaşarak kısa bir tanışma görüşmesi yapacağız.</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; margin-bottom: 18px; padding-bottom: 15px; border-bottom: 1px solid #f0f8f0;">
                            <div style="background: #4ea674; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-right: 15px; flex-shrink: 0;">3</div>
                            <div>
                                <div style="font-weight: bold; color: #2d5016; margin-bottom: 4px; font-size: 15px;">Oryantasyon Programı</div>
                                <div style="color: #555; font-size: 14px; line-height: 1.5;">Derneğimizin çalışmaları, projeleri ve gönüllülük fırsatları hakkında detaylı bilgi vereceğiz.</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start;">
                            <div style="background: #4ea674; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-right: 15px; flex-shrink: 0;">4</div>
                            <div>
                                <div style="font-weight: bold; color: #2d5016; margin-bottom: 4px; font-size: 15px;">Görev Atama</div>
                                <div style="color: #555; font-size: 14px; line-height: 1.5;">İlgi alanlarınıza ve müsaitlik durumunuza en uygun gönüllülük görevlerini sizinle birlikte belirleyeceğiz.</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div style="padding: 20px; text-align: center; color: #666; font-size: 12px; background: #f8f8f8; border-top: 1px solid #eee;">
                    <p style="margin: 0;"><strong>Bu başvuru Necat Derneği web sitesi gönüllü formu aracılığıyla gönderilmiştir.</strong></p>
                    <p style="margin: 5px 0 0 0;">Test Ortamı: samet.saray.06@gmail.com</p>
                    <p style="margin: 15px 0 0 0; color: #4ea674; font-weight: bold;">🤝 Birlikte Daha Güçlüyüz</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $emailContent;
    }
    
    private function getVolunteerAutoReplyTemplate($data) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Gönüllü Başvurunuz Alındı - Necat Derneği</title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4;">
            
            <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                
                <!-- Header -->
                <div style="background: #4ea674; color: white; padding: 30px 20px; text-align: center;">
                    <div style="margin-bottom: 15px;">
                        <img src="https://necatdernegi.org/assets/images/logo.png" alt="Necat Derneği" style="height: 40px; max-width: 200px; vertical-align: middle;" onerror="this.style.display=\'none\'">
                    </div>
                    <h1 style="margin: 0; font-size: 24px;">Hoş Geldiniz!</h1>
                    <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">Necat Derneği Gönüllü Ailesi</p>
                </div>
                
                <!-- Content -->
                <div style="padding: 30px 25px;">
                    
                    <div style="background: #e8f5e8; padding: 25px; margin-bottom: 20px; border-radius: 8px; border-left: 5px solid #4ea674;">
                        <p style="margin: 0 0 10px 0; font-size: 16px; color: #333;">Sayın <strong>' . htmlspecialchars($data['first_name']) . '</strong>,</p>
                        <p style="margin: 0 0 10px 0; font-size: 16px; color: #333;">Necat Derneği gönüllü ailesine katılım başvurunuzu başarıyla aldık. Bu değerli adımınız için size içtenlikle teşekkür ederiz! 🙏</p>
                        <p style="margin: 0; font-size: 16px; color: #333;">Gönüllülük, sadece başkalarına yardım etmekle kalmaz, aynı zamanda topluma anlamlı bir değer katarken kişisel gelişiminize de katkıda bulunur. Bu anlamlı yolculukta sizinle birlikte olacağımız için çok mutluyuz.</p>
                    </div>
                    
                    <div style="background: #f9f9f9; padding: 20px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #eee;">
                        <h3 style="color: #4ea674; margin-top: 0; font-size: 18px; margin-bottom: 15px; border-bottom: 2px solid #4ea674; padding-bottom: 8px;">📋 Sonraki Adımlarımız</h3>
                        <div style="margin-bottom: 15px; padding: 12px; background: white; border-radius: 6px; border-left: 4px solid #4ea674;">
                            <div style="font-weight: bold; color: #2d5016; margin-bottom: 5px; display: flex; align-items: center;">
                                <span style="background: #4ea674; color: white; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; margin-right: 10px;">1</span>
                                Değerlendirme Süreci
                            </div>
                            <div style="color: #555; font-size: 14px; margin-left: 30px;">Başvurunuz 3-5 iş günü içinde ekibimiz tarafından titizlikle değerlendirilecektir.</div>
                        </div>
                        <div style="margin-bottom: 15px; padding: 12px; background: white; border-radius: 6px; border-left: 4px solid #4ea674;">
                            <div style="font-weight: bold; color: #2d5016; margin-bottom: 5px; display: flex; align-items: center;">
                                <span style="background: #4ea674; color: white; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; margin-right: 10px;">2</span>
                                İletişim ve Tanışma
                            </div>
                            <div style="color: #555; font-size: 14px; margin-left: 30px;">Değerlendirme sonrası size telefon veya e-posta yoluyla ulaşarak kısa bir tanışma görüşmesi yapacağız.</div>
                        </div>
                        <div style="margin-bottom: 15px; padding: 12px; background: white; border-radius: 6px; border-left: 4px solid #4ea674;">
                            <div style="font-weight: bold; color: #2d5016; margin-bottom: 5px; display: flex; align-items: center;">
                                <span style="background: #4ea674; color: white; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; margin-right: 10px;">3</span>
                                Oryantasyon Programı
                            </div>
                            <div style="color: #555; font-size: 14px; margin-left: 30px;">Derneğimizin çalışmaları, projeleri ve gönüllülük fırsatları hakkında detaylı bilgi vereceğiz.</div>
                        </div>
                        <div style="padding: 12px; background: white; border-radius: 6px; border-left: 4px solid #4ea674;">
                            <div style="font-weight: bold; color: #2d5016; margin-bottom: 5px; display: flex; align-items: center;">
                                <span style="background: #4ea674; color: white; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; margin-right: 10px;">4</span>
                                Görev Atama
                            </div>
                            <div style="color: #555; font-size: 14px; margin-left: 30px;">İlgi alanlarınıza ve müsaitlik durumunuza en uygun gönüllülük görevlerini sizinle birlikte belirleyeceğiz.</div>
                        </div>
                    </div>
                    
                    <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                        <h3 style="color: #4ea674; margin-bottom: 15px;">📞 İletişim Bilgileri</h3>
                        <p style="margin: 5px 0; font-size: 14px; color: #555;"><strong>Gönüllü Koordinasyon:</strong> <a href="mailto:gonullu@necatdernegi.org" style="color: #4ea674; text-decoration: none;">gonullu@necatdernegi.org</a></p>
                        <p style="margin: 5px 0; font-size: 14px; color: #555;"><strong>Telefon:</strong> +90 312 311 65 25</p>
                    </div>
                    
                    <p style="font-style: italic; color: #666; text-align: center; margin-top: 30px; font-size: 15px;">"Bir mum, diğer mumu tutuşturmakla ışığından bir şey kaybetmez."</p>
                    <p style="text-align: center; font-size: 16px; color: #555; margin-top: 25px;">Bu güzel yolculukta bizimle birlikte olduğunuz için tekrar teşekkür ederiz. Birlikte daha güçlü olacağız! 💪</p>
                </div>
                
                <!-- Footer -->
                <div style="padding: 20px; text-align: center; color: #666; font-size: 12px; background: #f8f8f8; border-top: 1px solid #eee;">
                    <p style="margin: 0;">Bu e-posta Necat Derneği tarafından otomatik olarak gönderilmiştir.</p>
                    <p style="margin: 5px 0 0 0;"><strong>Elinizi İyiliğe Uzatın</strong></p>
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