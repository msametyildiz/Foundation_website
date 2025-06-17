<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

echo "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Direct Email Test</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>body { background: #f8f9fa; padding: 20px; }</style>
</head>
<body>
    <div class='container'>
        <div class='card'>
            <div class='card-header bg-primary text-white'>
                <h4 class='mb-0'>🔧 Direct Email Test</h4>
            </div>
            <div class='card-body'>";

echo "<h5>📧 Testing email to: samet.saray.06@gmail.com</h5>";

try {
    $emailService = new EmailService($pdo);
    
    // Test 1: Simple test email
    echo "<h6>1. Testing simple email...</h6>";
    $result = $emailService->sendTestEmail(
        'samet.saray.06@gmail.com', 
        'Test Email - ' . date('H:i:s'), 
        'This is a direct test email sent at ' . date('Y-m-d H:i:s')
    );
    
    if ($result['success']) {
        echo "<div class='alert alert-success'>✅ Test email sent successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Test email failed: " . $result['error'] . "</div>";
    }
    
    // Test 2: Volunteer notification email
    echo "<h6>2. Testing volunteer notification email...</h6>";
    
    $volunteerData = [
        'first_name' => 'Test',
        'last_name' => 'Kullanıcı',
        'name' => 'Test Kullanıcı',
        'email' => 'test@example.com',
        'phone' => '0555 123 4567',
        'age' => 25,
        'profession' => 'Yazılım Geliştirici',
        'availability' => 'flexible',
        'interests' => 'Teknoloji, Eğitim',
        'experience' => 'Test deneyimi',
        'motivation' => 'Test motivasyon mesajı'
    ];
    
    $volunteerResult = $emailService->sendVolunteerNotification($volunteerData);
    
    if ($volunteerResult['success']) {
        echo "<div class='alert alert-success'>✅ Volunteer notification sent successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Volunteer notification failed: " . $volunteerResult['error'] . "</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
}

echo "<div class='mt-4'>
        <a href='pages/volunteer.php' class='btn btn-primary'>Go to Volunteer Page</a>
        <a href='test_volunteer_system.php' class='btn btn-secondary'>Test System</a>
      </div>";

echo "</div>
        </div>
    </div>
</body>
</html>";
?>
