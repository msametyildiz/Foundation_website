<?php
require_once 'config/database.php';

// Handle form submission
if ($_POST && isset($_POST['setup_email'])) {
    try {
        // Insert/Update SMTP settings
        $settings = [
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => '587',
            'smtp_auth' => '1',
            'smtp_encryption' => 'tls',
            'smtp_username' => 'samet.saray.06@gmail.com',
            'smtp_password' => $_POST['gmail_password'] ?? '',
            'email_from_name' => 'Necat Derneği',
            'admin_email' => 'samet.saray.06@gmail.com'
        ];
        
        foreach ($settings as $key => $value) {
            $stmt = $pdo->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
            $stmt->execute([$key, $value]);
        }
        
        $success = "Email settings configured successfully!";
        
        // Test email sending
        if (!empty($_POST['gmail_password'])) {
            require_once 'includes/EmailService.php';
            $emailService = new EmailService($pdo);
            
            $testResult = $emailService->sendTestEmail('samet.saray.06@gmail.com', 'Test Email', 'This is a test email from the volunteer system.');
            
            if ($testResult['success']) {
                $success .= " Test email sent successfully!";
            } else {
                $error = "Settings saved but test email failed: " . $testResult['error'];
            }
        }
        
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Get current settings
$currentSettings = [];
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'smtp_%' OR setting_key LIKE '%email%'");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $currentSettings[$row['setting_key']] = $row['setting_value'];
    }
} catch (Exception $e) {
    // Settings table might not exist yet
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Configuration - Necat Derneği</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .config-container { max-width: 800px; margin: 50px auto; }
        .card { box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .form-control:focus { border-color: #e74c3c; box-shadow: 0 0 0 0.2rem rgba(231,76,60,0.25); }
        .btn-primary { background-color: #e74c3c; border-color: #e74c3c; }
        .btn-primary:hover { background-color: #c0392b; border-color: #c0392b; }
        .alert { border: none; border-radius: 10px; }
        .setting-item { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="config-container">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i>Email Configuration</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fas fa-cog me-2"></i>Current SMTP Settings</h5>
                                <div class="setting-item">
                                    <strong>Host:</strong> <?php echo $currentSettings['smtp_host'] ?? 'smtp.gmail.com'; ?>
                                </div>
                                <div class="setting-item">
                                    <strong>Port:</strong> <?php echo $currentSettings['smtp_port'] ?? '587'; ?>
                                </div>
                                <div class="setting-item">
                                    <strong>Username:</strong> <?php echo $currentSettings['smtp_username'] ?? 'samet.saray.06@gmail.com'; ?>
                                </div>
                                <div class="setting-item">
                                    <strong>Password:</strong> 
                                    <?php echo !empty($currentSettings['smtp_password']) ? '<span class="text-success">Set</span>' : '<span class="text-danger">Not Set</span>'; ?>
                                </div>
                                <div class="setting-item">
                                    <strong>Admin Email:</strong> <?php echo $currentSettings['admin_email'] ?? 'samet.saray.06@gmail.com'; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5><i class="fas fa-key me-2"></i>Gmail App Password Setup</h5>
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>How to get Gmail App Password:</h6>
                                    <ol class="mb-0">
                                        <li>Go to Google Account settings</li>
                                        <li>Security → 2-Step Verification</li>
                                        <li>App passwords → Generate</li>
                                        <li>Select "Mail" and your device</li>
                                        <li>Copy the 16-character password</li>
                                    </ol>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="gmail_password" class="form-label">Gmail App Password</label>
                                    <input type="password" class="form-control" id="gmail_password" name="gmail_password" 
                                           placeholder="Enter 16-character app password"
                                           value="<?php echo $currentSettings['smtp_password'] ?? ''; ?>">
                                    <div class="form-text">This will be encrypted and stored securely</div>
                                </div>
                                
                                <button type="submit" name="setup_email" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-save me-2"></i>Save & Test Email
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-flask me-2"></i>Quick Tests</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="test_volunteer_form.php" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-users me-2"></i>Test Volunteer Form
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="test_phpmailer.php" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-envelope me-2"></i>Test PHPMailer
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="pages/volunteer.php" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-hand-holding-heart me-2"></i>Volunteer Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
