<?php
require_once 'config/database.php';

echo "<h2>🔧 Email Debug & Setup</h2>";

try {
    // Create settings table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    echo "<p>✅ Settings table ready</p>";
    
    // Insert SMTP settings
    $settings = [
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => '587',
        'smtp_auth' => '1',
        'smtp_encryption' => 'tls',
        'smtp_username' => 'samet.saray.06@gmail.com',
        'smtp_password' => '', // Will be set manually
        'email_from_name' => 'Necat Derneği',
        'admin_email' => 'samet.saray.06@gmail.com'
    ];
    
    foreach ($settings as $key => $value) {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$key, $value, $value]);
    }
    
    echo "<p>✅ SMTP settings configured</p>";
    
    // Show current settings
    echo "<h3>Current Settings:</h3>";
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'smtp_%' OR setting_key LIKE '%email%'");
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Key</th><th>Value</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $value = ($row['setting_key'] === 'smtp_password' && !empty($row['setting_value'])) ? '[SET]' : $row['setting_value'];
        echo "<tr><td>{$row['setting_key']}</td><td>{$value}</td></tr>";
    }
    echo "</table>";
    
    // Gmail password form
    if (isset($_POST['set_password'])) {
        $password = $_POST['gmail_password'];
        if (!empty($password)) {
            $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'smtp_password'");
            $stmt->execute([$password]);
            echo "<p style='color: green;'>✅ Gmail password updated!</p>";
            
            // Test email
            require_once 'includes/EmailService.php';
            $emailService = new EmailService($pdo);
            
            $testResult = $emailService->sendTestEmail('samet.saray.06@gmail.com', 'Test from Debug Script', 'This is a test email from the debug script.');
            
            if ($testResult['success']) {
                echo "<p style='color: green;'>✅ Test email sent successfully!</p>";
            } else {
                echo "<p style='color: red;'>❌ Test email failed: " . $testResult['error'] . "</p>";
            }
        }
    }
    
    echo "<form method='post' style='margin: 20px 0; padding: 20px; background: #f9f9f9; border: 1px solid #ddd;'>";
    echo "<h4>Set Gmail App Password:</h4>";
    echo "<input type='password' name='gmail_password' placeholder='16-character Gmail app password' style='width: 300px; padding: 8px;'><br><br>";
    echo "<button type='submit' name='set_password' style='background: #4ea674; color: white; padding: 10px 20px; border: none; border-radius: 5px;'>Set Password & Test</button>";
    echo "</form>";
    
    // Test volunteer form submission
    if (isset($_POST['test_volunteer'])) {
        require_once 'includes/EmailService.php';
        $emailService = new EmailService($pdo);
        
        $testData = [
            'first_name' => 'Test',
            'last_name' => 'Kullanıcı',
            'name' => 'Test Kullanıcı',
            'email' => 'test@example.com',
            'phone' => '0555 123 4567',
            'age' => 25,
            'profession' => 'Yazılım Geliştirici',
            'availability' => 'flexible',
            'interests' => 'Teknoloji',
            'experience' => 'Test deneyimi',
            'motivation' => 'Test motivasyon'
        ];
        
        $result = $emailService->sendVolunteerNotification($testData);
        
        if ($result) {
            echo "<p style='color: green;'>✅ Volunteer notification sent to samet.saray.06@gmail.com!</p>";
        } else {
            echo "<p style='color: red;'>❌ Volunteer notification failed!</p>";
        }
    }
    
    echo "<form method='post' style='margin: 20px 0; padding: 20px; background: #e8f5e8; border: 1px solid #4ea674;'>";
    echo "<h4>Test Volunteer Email:</h4>";
    echo "<button type='submit' name='test_volunteer' style='background: #4ea674; color: white; padding: 10px 20px; border: none; border-radius: 5px;'>Send Test Volunteer Email</button>";
    echo "</form>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='pages/volunteer.php'>Test Volunteer Form</a> | <a href='test_volunteer_system.php'>Volunteer System Test</a></p>";
?>
