<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/EmailService.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Geçersiz istek yöntemi.';
    echo json_encode($response);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'contact':
        handleContactForm();
        break;
        
    case 'volunteer':
        handleVolunteerForm();
        break;
        
    case 'donation':
        handleDonationForm();
        break;
        
    case 'appointment':
        handleAppointmentForm();
        break;
        
    case 'newsletter':
        handleNewsletterForm();
        break;
        
    default:
        $response['message'] = 'Geçersiz işlem.';
}

echo json_encode($response);

function handleContactForm() {
    global $pdo, $response;
    
    try {
        $name = sanitizeInput($_POST['name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $subject = sanitizeInput($_POST['subject'] ?? '');
        $message = sanitizeInput($_POST['message'] ?? '');
        
        // Validation
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $response['message'] = 'Lütfen tüm gerekli alanları doldurun.';
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Geçerli bir e-posta adresi girin.';
            return;
        }
        
        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO contact_messages (name, email, phone, subject, message, status, created_at) 
            VALUES (?, ?, ?, ?, ?, 'pending', NOW())
        ");
        
        $stmt->execute([$name, $email, $phone, $subject, $message]);
        
        // Send notification email
        $emailService = new EmailService($pdo);
        $contactData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message
        ];
        $emailService->sendContactNotification($contactData);
        
        $response['success'] = true;
        $response['message'] = 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.';
        
    } catch (PDOException $e) {
        $response['message'] = 'Veritabanı hatası: ' . $e->getMessage();
    }
}

function handleVolunteerForm() {
    global $pdo, $response;
    
    try {
        $name = sanitizeInput($_POST['name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $age = (int)($_POST['age'] ?? 0);
        $profession = sanitizeInput($_POST['profession'] ?? '');
        $experience = sanitizeInput($_POST['experience'] ?? '');
        $availability = sanitizeInput($_POST['availability'] ?? '');
        $interests = sanitizeInput($_POST['interests'] ?? '');
        $message = sanitizeInput($_POST['message'] ?? '');
        
        // Validation
        if (empty($name) || empty($email) || empty($phone) || empty($message)) {
            $response['message'] = 'Lütfen tüm zorunlu alanları doldurun.';
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Geçerli bir e-posta adresi girin.';
            return;
        }
        
        if ($age > 0 && ($age < 16 || $age > 80)) {
            $response['message'] = 'Yaş 16-80 arasında olmalıdır.';
            return;
        }
        
        // Check if already applied
        $stmt = $pdo->prepare("SELECT id FROM volunteer_applications WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $response['message'] = 'Bu e-posta adresi ile daha önce başvuru yapılmış.';
            return;
        }
        
        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO volunteer_applications 
            (name, email, phone, age, profession, experience, availability, interests, message, status, ip_address, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'new', ?, NOW())
        ");
        
        $stmt->execute([
            $name, $email, $phone, $age, $profession, $experience, 
            $availability, $interests, $message, $_SERVER['REMOTE_ADDR'] ?? ''
        ]);
        
        // Send notification email
        $emailService = new EmailService($pdo);
        
        // Parse name for email service
        $nameParts = explode(' ', $name, 2);
        $volunteerData = [
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'email' => $email,
            'phone' => $phone,
            'age' => $age ?: 'Belirtilmemiş',
            'profession' => $profession ?: 'Belirtilmemiş',
            'availability' => $availability,
            'interests' => $interests ?: 'Belirtilmemiş',
            'experience' => $experience ?: 'Belirtilmemiş',
            'motivation' => $message
        ];
        $emailService->sendVolunteerNotification($volunteerData);
        
        $response['success'] = true;
        $response['message'] = 'Gönüllü başvurunuz başarıyla alındı. Size en kısa sürede dönüş yapacağız.';
        
    } catch (PDOException $e) {
        $response['message'] = 'Veritabanı hatası: ' . $e->getMessage();
    }
}

function handleDonationForm() {
    global $pdo, $response;
    
    try {
        $donation_type = sanitizeInput($_POST['donation_type'] ?? '');
        $amount = floatval($_POST['amount'] ?? 0);
        $donor_name = sanitizeInput($_POST['donor_name'] ?? '');
        $donor_email = sanitizeInput($_POST['donor_email'] ?? '');
        $donor_phone = sanitizeInput($_POST['donor_phone'] ?? '');
        $donor_address = sanitizeInput($_POST['donor_address'] ?? '');
        $message = sanitizeInput($_POST['message'] ?? '');
        $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;
        
        // Validation
        if (empty($donation_type) || $amount <= 0) {
            $response['message'] = 'Lütfen bağış tipini ve miktarını belirtin.';
            return;
        }
        
        if (!$is_anonymous && (empty($donor_name) || empty($donor_email))) {
            $response['message'] = 'Anonim bağış yapmıyorsanız ad soyad ve e-posta gereklidir.';
            return;
        }
        
        if ($donor_email && !filter_var($donor_email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Geçerli bir e-posta adresi girin.';
            return;
        }
        
        // Generate reference number
        $reference_number = 'BGS' . date('Y') . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        
        // Handle file upload if exists
        $receipt_file = null;
        if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
            $upload_result = handleFileUpload($_FILES['receipt'], 'receipts');
            if ($upload_result['success']) {
                $receipt_file = $upload_result['filename'];
            }
        }
        
        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO donations 
            (donation_type, amount, donor_name, donor_email, donor_phone, donor_address, 
             message, reference_number, receipt_file, is_anonymous, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");
        
        $stmt->execute([
            $donation_type, $amount, $donor_name, $donor_email, $donor_phone, 
            $donor_address, $message, $reference_number, $receipt_file, $is_anonymous
        ]);
        
        // Send confirmation email
        if ($donor_email) {
            $emailService = new EmailService($pdo);
            $donationData = [
                'amount' => $amount,
                'donor_name' => $donor_name,
                'email' => $donor_email,
                'phone' => $donor_phone,
                'project_name' => $donation_type,
                'payment_method' => 'Banka Havalesi/EFT'
            ];
            $emailService->sendDonationNotification($donationData);
        }
        
        $response['success'] = true;
        $response['message'] = 'Bağışınız kaydedildi. Referans numaranız: ' . $reference_number;
        $response['reference'] = $reference_number;
        
    } catch (PDOException $e) {
        $response['message'] = 'Veritabanı hatası: ' . $e->getMessage();
    }
}

function handleAppointmentForm() {
    global $pdo, $response;
    
    try {
        $name = sanitizeInput($_POST['name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $appointment_date = sanitizeInput($_POST['appointment_date'] ?? '');
        $appointment_time = sanitizeInput($_POST['appointment_time'] ?? '');
        $purpose = sanitizeInput($_POST['purpose'] ?? '');
        $message = sanitizeInput($_POST['message'] ?? '');
        
        // Validation
        if (empty($name) || empty($email) || empty($phone) || empty($appointment_date) || empty($appointment_time)) {
            $response['message'] = 'Lütfen tüm gerekli alanları doldurun.';
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Geçerli bir e-posta adresi girin.';
            return;
        }
        
        // Check if date is in the future
        $appointment_datetime = $appointment_date . ' ' . $appointment_time;
        if (strtotime($appointment_datetime) <= time()) {
            $response['message'] = 'Randevu tarihi gelecekte olmalıdır.';
            return;
        }
        
        // Check if time slot is available
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM appointments 
            WHERE appointment_date = ? AND appointment_time = ? AND status != 'cancelled'
        ");
        $stmt->execute([$appointment_date, $appointment_time]);
        if ($stmt->fetchColumn() > 0) {
            $response['message'] = 'Bu tarih ve saat için randevu dolu. Lütfen başka bir zaman seçin.';
            return;
        }
        
        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO appointments 
            (name, email, phone, appointment_date, appointment_time, purpose, message, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");
        
        $stmt->execute([$name, $email, $phone, $appointment_date, $appointment_time, $purpose, $message]);
        
        // Send confirmation email
        sendAppointmentConfirmation($email, $name, $appointment_date, $appointment_time);
        
        $response['success'] = true;
        $response['message'] = 'Randevu talebiniz alındı. Kısa süre içinde onay e-postası alacaksınız.';
        
    } catch (PDOException $e) {
        $response['message'] = 'Veritabanı hatası: ' . $e->getMessage();
    }
}

function handleNewsletterForm() {
    global $pdo, $response;
    
    try {
        $email = sanitizeInput($_POST['email'] ?? '');
        
        // Validation
        if (empty($email)) {
            $response['message'] = 'E-posta adresi gereklidir.';
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Geçerli bir e-posta adresi girin.';
            return;
        }
        
        // Check if already subscribed
        $stmt = $pdo->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $response['message'] = 'Bu e-posta adresi zaten bültenimize kayıtlı.';
            return;
        }
        
        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO newsletter_subscribers (email, status, created_at) 
            VALUES (?, 'active', NOW())
        ");
        $stmt->execute([$email]);
        
        $response['success'] = true;
        $response['message'] = 'Bültenimize başarıyla kaydoldunuz.';
        
    } catch (PDOException $e) {
        $response['message'] = 'Veritabanı hatası: ' . $e->getMessage();
    }
}

function sendNotificationEmail($type, $name, $email, $subject) {
    // This function would implement email sending using PHPMailer or similar
    // For now, we'll log it to a file or database
    error_log("Notification: $type - $name ($email) - $subject");
}

function sendDonationConfirmation($email, $name, $amount, $reference) {
    // Send donation confirmation email
    error_log("Donation confirmation: $email - $name - $amount - $reference");
}

function sendAppointmentConfirmation($email, $name, $date, $time) {
    // Send appointment confirmation email
    error_log("Appointment confirmation: $email - $name - $date $time");
}
?>
