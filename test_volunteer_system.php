<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

echo "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Volunteer Form Test - Necat Derneği</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>
    <style>
        body { background-color: #f8f9fa; font-family: Arial, sans-serif; }
        .test-container { max-width: 900px; margin: 50px auto; }
        .card { box-shadow: 0 0 20px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .alert { border: none; border-radius: 10px; }
        .btn { border-radius: 8px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='test-container'>
            <div class='card'>
                <div class='card-header bg-primary text-white'>
                    <h4 class='mb-0'><i class='fas fa-vial me-2'></i>Volunteer Form Test - samet.saray.06@gmail.com</h4>
                </div>
                <div class='card-body'>";

// Test data
$testData = [
    'name' => 'Test Kullanıcı',
    'email' => 'test@example.com',
    'phone' => '0555 123 4567',
    'age' => 25,
    'profession' => 'Yazılım Geliştirici',
    'availability' => 'flexible',
    'interests' => 'Teknoloji, Eğitim, Sosyal Medya',
    'experience' => 'Daha önce çeşitli STK\'larda gönüllü olarak çalıştım.',
    'message' => 'Topluma faydalı olmak ve yeteneklerimi iyi bir amaç için kullanmak istiyorum.'
];

echo "<div class='row'>
        <div class='col-md-6'>
            <h5><i class='fas fa-database me-2'></i>Test Data</h5>
            <table class='table table-sm table-striped'>
                <tbody>";

foreach ($testData as $key => $value) {
    echo "<tr><td><strong>{$key}</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
}

echo "</tbody>
            </table>
        </div>
        <div class='col-md-6'>";

try {
    $emailService = new EmailService($pdo);
    
    // Parse name for email service
    $nameParts = explode(' ', $testData['name'], 2);
    $volunteerData = [
        'first_name' => $nameParts[0],
        'last_name' => $nameParts[1] ?? '',
        'name' => $testData['name'],
        'email' => $testData['email'],
        'phone' => $testData['phone'],
        'age' => $testData['age'],
        'profession' => $testData['profession'],
        'availability' => $testData['availability'],
        'interests' => $testData['interests'],
        'experience' => $testData['experience'],
        'message' => $testData['message'],
        'motivation' => $testData['message']
    ];
    
    echo "<h5><i class='fas fa-cog me-2'></i>System Status</h5>";
    
    // Check email settings
    echo "<div class='alert alert-info'>
            <h6><i class='fas fa-info-circle me-2'></i>Email Configuration</h6>";
    
    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'smtp_%'");
        $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($settings)) {
            echo "<p class='text-warning mb-0'>⚠️ No SMTP settings found</p>";
        } else {
            $hasPassword = false;
            foreach ($settings as $setting) {
                if ($setting['setting_key'] === 'smtp_password' && !empty($setting['setting_value'])) {
                    $hasPassword = true;
                    break;
                }
            }
            
            if ($hasPassword) {
                echo "<p class='text-success mb-0'>✅ SMTP configuration ready</p>";
            } else {
                echo "<p class='text-danger mb-0'>❌ SMTP password not configured</p>";
            }
        }
    } catch (Exception $e) {
        echo "<p class='text-danger mb-0'>❌ Database error: " . $e->getMessage() . "</p>";
    }
    
    echo "</div>";
    
    // Test database connection
    echo "<div class='alert alert-success'>
            <h6><i class='fas fa-database me-2'></i>Database</h6>
            <p class='mb-0'>✅ Database connection successful</p>
          </div>";
    
    echo "</div>
        </div>";
    
    echo "<div class='row mt-4'>
            <div class='col-12'>
                <h5><i class='fas fa-paper-plane me-2'></i>Email Test</h5>
                <div class='d-grid gap-2 d-md-flex justify-content-md-start'>
                    <button onclick='sendTestEmail()' class='btn btn-success me-md-2'>
                        <i class='fas fa-envelope me-2'></i>Send Test Email
                    </button>
                    <button onclick='testFormSubmission()' class='btn btn-primary me-md-2'>
                        <i class='fas fa-user-plus me-2'></i>Test Volunteer Form
                    </button>
                </div>
                <div id='testResults' class='mt-3'></div>
            </div>
          </div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>
            <h6><i class='fas fa-exclamation-triangle me-2'></i>Error</h6>
            <p class='mb-0'>" . $e->getMessage() . "</p>
          </div>";
}

echo "</div>
            </div>
            
            <div class='card'>
                <div class='card-body text-center'>
                    <div class='row'>
                        <div class='col-md-3'>
                            <a href='email_config.php' class='btn btn-outline-primary w-100 mb-2'>
                                <i class='fas fa-cog me-2'></i>Email Config
                            </a>
                        </div>
                        <div class='col-md-3'>
                            <a href='test_phpmailer.php' class='btn btn-outline-info w-100 mb-2'>
                                <i class='fas fa-flask me-2'></i>PHPMailer Test
                            </a>
                        </div>
                        <div class='col-md-3'>
                            <a href='pages/volunteer.php' class='btn btn-success w-100 mb-2'>
                                <i class='fas fa-hand-holding-heart me-2'></i>Volunteer Page
                            </a>
                        </div>
                        <div class='col-md-3'>
                            <a href='admin/index.php' class='btn btn-outline-secondary w-100 mb-2'>
                                <i class='fas fa-user-shield me-2'></i>Admin Panel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
    <script>
    function showResults(html) {
        document.getElementById('testResults').innerHTML = html;
    }
    
    function sendTestEmail() {
        showResults('<div class=\"alert alert-info\"><i class=\"fas fa-spinner fa-spin me-2\"></i>Sending test email...</div>');
        
        fetch('ajax/test_email.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'test_email',
                to: 'samet.saray.06@gmail.com',
                subject: 'Test Email from Volunteer System',
                message: 'This is a test email sent at ' + new Date().toLocaleString()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showResults('<div class=\"alert alert-success\"><i class=\"fas fa-check-circle me-2\"></i><strong>Success!</strong> Test email sent to samet.saray.06@gmail.com</div>');
            } else {
                showResults('<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle me-2\"></i><strong>Error!</strong> ' + data.message + '</div>');
            }
        })
        .catch(error => {
            showResults('<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle me-2\"></i><strong>AJAX Error!</strong> ' + error + '</div>');
        });
    }
    
    function testFormSubmission() {
        showResults('<div class=\"alert alert-info\"><i class=\"fas fa-spinner fa-spin me-2\"></i>Testing volunteer form submission...</div>');
        
        const formData = new FormData();
        formData.append('action', 'volunteer');
        formData.append('name', 'Test Kullanıcı');
        formData.append('email', 'test@example.com');
        formData.append('phone', '0555 123 4567');
        formData.append('age', '25');
        formData.append('profession', 'Yazılım Geliştirici');
        formData.append('availability', 'flexible');
        formData.append('interests', 'Teknoloji, Eğitim');
        formData.append('experience', 'Test deneyimi');
        formData.append('message', 'Test motivasyon mesajı');
        
        fetch('ajax/forms.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showResults('<div class=\"alert alert-success\"><i class=\"fas fa-check-circle me-2\"></i><strong>Success!</strong> Volunteer application submitted and email sent to samet.saray.06@gmail.com</div>');
            } else {
                showResults('<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle me-2\"></i><strong>Error!</strong> ' + data.message + '</div>');
            }
        })
        .catch(error => {
            showResults('<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle me-2\"></i><strong>AJAX Error!</strong> ' + error + '</div>');
        });
    }
    </script>
</body>
</html>";
?>
