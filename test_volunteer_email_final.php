<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Volunteer Email Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .success { color: green; background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { color: red; background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { color: blue; background: #cce7ff; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .form { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
        button { background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #218838; }
        input[type='password'] { width: 300px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🧪 Volunteer Email Test - samet.saray.06@gmail.com</h1>";

try {
    // Create EmailService instance
    $emailService = new EmailService($pdo);
    
    echo "<div class='info'>✅ EmailService initialized successfully</div>";
    
    // Check SMTP settings
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'smtp_%' OR setting_key = 'admin_email'");
    $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $hasPassword = false;
    foreach ($settings as $setting) {
        if ($setting['setting_key'] === 'smtp_password' && !empty($setting['setting_value'])) {
            $hasPassword = true;
            break;
        }
    }
    
    if ($hasPassword) {
        echo "<div class='success'>✅ SMTP password is configured</div>";
    } else {
        echo "<div class='error'>❌ SMTP password not set</div>";
        
        // Password form
        if (isset($_POST['set_password']) && !empty($_POST['password'])) {
            $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'smtp_password'");
            $stmt->execute([$_POST['password']]);
            echo "<div class='success'>✅ Password updated! Refresh page to continue.</div>";
        } else {
            echo "<div class='form'>
                    <h3>Enter Gmail App Password:</h3>
                    <form method='post'>
                        <input type='password' name='password' placeholder='16-character Gmail app password' required><br><br>
                        <button type='submit' name='set_password'>Set Password</button>
                    </form>
                  </div>";
        }
    }
    
    // Test button
    if ($hasPassword || (isset($_POST['set_password']) && !empty($_POST['password']))) {
        echo "<div class='form'>
                <h3>Test Volunteer Email:</h3>
                <form method='post'>
                    <button type='submit' name='test_email'>Send Test Volunteer Application Email</button>
                </form>
              </div>";
        
        // Perform test
        if (isset($_POST['test_email'])) {
            echo "<h3>🚀 Sending test email...</h3>";
            
            $testData = [
                'first_name' => 'Test',
                'last_name' => 'Kullanıcı',
                'name' => 'Test Kullanıcı',
                'email' => 'test@example.com',
                'phone' => '0555 123 4567',
                'age' => 25,
                'profession' => 'Yazılım Geliştirici',
                'availability' => 'flexible',
                'interests' => 'Teknoloji, Eğitim',
                'experience' => 'Daha önce çeşitli STK\'larda gönüllü olarak çalıştım.',
                'motivation' => 'Topluma faydalı olmak ve yeteneklerimi iyi bir amaç için kullanmak istiyorum.'
            ];
            
            try {
                $result = $emailService->sendVolunteerNotification($testData);
                
                if ($result['success']) {
                    echo "<div class='success'>
                            <h4>✅ Email sent successfully!</h4>
                            <p><strong>To:</strong> samet.saray.06@gmail.com</p>
                            <p><strong>Subject:</strong> Yeni Gönüllü Başvurusu - Test Kullanıcı</p>
                            <p><strong>Time:</strong> " . date('Y-m-d H:i:s') . "</p>
                            <p><strong>Message:</strong> " . $result['message'] . "</p>
                          </div>";
                    
                    echo "<div class='info'>
                            <h4>📧 Check your email inbox:</h4>
                            <p>An email should have been sent to <strong>samet.saray.06@gmail.com</strong></p>
                            <p>If you don't see it, check your spam folder.</p>
                          </div>";
                } else {
                    echo "<div class='error'>
                            <h4>❌ Email failed!</h4>
                            <p><strong>Error:</strong> " . $result['error'] . "</p>
                          </div>";
                }
            } catch (Exception $e) {
                echo "<div class='error'>
                        <h4>❌ Exception occurred!</h4>
                        <p><strong>Error:</strong> " . $e->getMessage() . "</p>
                      </div>";
            }
        }
    }
    
    // Show current settings
    echo "<h3>📋 Current SMTP Settings:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f8f9fa;'><th style='padding: 8px;'>Setting</th><th style='padding: 8px;'>Value</th></tr>";
    
    foreach ($settings as $setting) {
        $value = $setting['setting_value'];
        if ($setting['setting_key'] === 'smtp_password') {
            $value = !empty($value) ? '[CONFIGURED]' : '[NOT SET]';
        }
        echo "<tr><td style='padding: 8px;'>{$setting['setting_key']}</td><td style='padding: 8px;'>{$value}</td></tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<div class='error'>
            <h4>❌ Error initializing test:</h4>
            <p>" . $e->getMessage() . "</p>
          </div>";
}

echo "<div style='margin-top: 30px; text-align: center;'>
        <a href='pages/volunteer.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>Test Volunteer Form</a>
        <a href='email_config.php' style='background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>Email Config</a>
      </div>";

echo "</div>
</body>
</html>";
?>
