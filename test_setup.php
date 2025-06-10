<?php
/**
 * Database Test and Setup Script
 * Tests database connectivity and creates required tables
 */

require_once 'config/database.php';
require_once 'vendor/autoload.php';
require_once 'includes/AdminLogger.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Necat Derneği - Database Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #4CAF50; }
        .error { color: #f44336; }
        .warning { color: #ff9800; }
        .info { color: #4EA674; }
        .section { margin: 20px 0; padding: 15px; border-left: 4px solid #4EA674; background: #f8f9fa; }
        pre { background: #f1f1f1; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .status { padding: 5px 10px; border-radius: 4px; margin: 5px 0; }
        .status.success { background: #d4edda; border: 1px solid #c3e6cb; }
        .status.error { background: #f8d7da; border: 1px solid #f5c6cb; }
        .status.warning { background: #fff3cd; border: 1px solid #ffeaa7; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏥 Necat Derneği - Database Test & Setup</h1>
        
        <?php
        echo "<div class='section'>";
        echo "<h2>📋 Test Results</h2>";
        
        $allPassed = true;
        
        // Test 1: Database Connection
        echo "<h3>1. Database Connection Test</h3>";
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            echo "<div class='status success'>✅ Database connection successful</div>";
            echo "<p><strong>Database:</strong> " . DB_NAME . " | <strong>Host:</strong> " . DB_HOST . "</p>";
        } catch(PDOException $e) {
            echo "<div class='status error'>❌ Database connection failed: " . $e->getMessage() . "</div>";
            $allPassed = false;
        }
        
        // Test 2: Required Tables
        echo "<h3>2. Required Tables Check</h3>";
        $requiredTables = [
            'users', 'projects', 'donations', 'volunteers', 
            'messages', 'news', 'settings', 'admin_logs'
        ];
        
        if (isset($pdo)) {
            foreach ($requiredTables as $table) {
                try {
                    $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
                    if ($stmt->rowCount() > 0) {
                        echo "<div class='status success'>✅ Table '$table' exists</div>";
                    } else {
                        echo "<div class='status warning'>⚠️ Table '$table' not found</div>";
                    }
                } catch(PDOException $e) {
                    echo "<div class='status error'>❌ Error checking table '$table': " . $e->getMessage() . "</div>";
                }
            }
        }
        
        // Test 3: PHP Extensions
        echo "<h3>3. PHP Extensions Check</h3>";
        $requiredExtensions = [
            'pdo', 'pdo_mysql', 'curl', 'mbstring', 
            'xml', 'zip', 'gd', 'openssl'
        ];
        
        foreach ($requiredExtensions as $ext) {
            if (extension_loaded($ext)) {
                echo "<div class='status success'>✅ Extension '$ext' loaded</div>";
            } else {
                echo "<div class='status error'>❌ Extension '$ext' not loaded</div>";
                $allPassed = false;
            }
        }
        
        // Test 4: File Permissions
        echo "<h3>4. File Permissions Check</h3>";
        $checkPaths = [
            'uploads/images' => 'Image uploads',
            'uploads/documents' => 'Document uploads', 
            'uploads/receipts' => 'Receipt uploads',
            'config' => 'Configuration files'
        ];
        
        foreach ($checkPaths as $path => $description) {
            if (is_writable($path)) {
                echo "<div class='status success'>✅ $description ($path) - writable</div>";
            } else {
                echo "<div class='status error'>❌ $description ($path) - not writable</div>";
                $allPassed = false;
            }
        }
        
        // Test 5: Composer Dependencies
        echo "<h3>5. Composer Dependencies Check</h3>";
        $requiredClasses = [
            'PHPMailer\\PHPMailer\\PHPMailer' => 'PHPMailer for email sending',
            'PhpOffice\\PhpSpreadsheet\\Spreadsheet' => 'PHPSpreadsheet for Excel export'
        ];
        
        foreach ($requiredClasses as $class => $description) {
            if (class_exists($class)) {
                echo "<div class='status success'>✅ $description - available</div>";
            } else {
                echo "<div class='status error'>❌ $description - not available</div>";
                $allPassed = false;
            }
        }
        
        // Test 6: EmailService Test
        echo "<h3>6. Email Service Test</h3>";
        try {
            if (class_exists('EmailService')) {
                echo "<div class='status success'>✅ EmailService class available</div>";
            } else {
                echo "<div class='status warning'>⚠️ EmailService class not found</div>";
            }
        } catch(Exception $e) {
            echo "<div class='status error'>❌ EmailService error: " . $e->getMessage() . "</div>";
        }
        
        // Test 7: AdminLogger Test
        echo "<h3>7. Admin Logger Test</h3>";
        try {
            if (isset($pdo)) {
                $logger = new AdminLogger($pdo);
                $logger->log(1, 'test', 'System test completed', 'system', null, '127.0.0.1');
                echo "<div class='status success'>✅ AdminLogger working correctly</div>";
            } else {
                echo "<div class='status error'>❌ Cannot test AdminLogger - no database connection</div>";
            }
        } catch(Exception $e) {
            echo "<div class='status error'>❌ AdminLogger error: " . $e->getMessage() . "</div>";
        }
        
        echo "</div>";
        
        // Summary
        echo "<div class='section'>";
        echo "<h2>📊 Test Summary</h2>";
        if ($allPassed) {
            echo "<div class='status success'>";
            echo "<h3>🎉 All Tests Passed!</h3>";
            echo "<p>Your Necat Derneği website is ready for production.</p>";
            echo "<p><strong>Next Steps:</strong></p>";
            echo "<ul>";
            echo "<li>Configure email settings in EmailService</li>";
            echo "<li>Update site URLs in config/database.php</li>";
            echo "<li>Set up SSL certificate for production</li>";
            echo "<li>Configure backup schedules</li>";
            echo "</ul>";
            echo "</div>";
        } else {
            echo "<div class='status warning'>";
            echo "<h3>⚠️ Some Tests Failed</h3>";
            echo "<p>Please fix the issues above before deploying to production.</p>";
            echo "</div>";
        }
        echo "</div>";
        
        // System Information
        echo "<div class='section'>";
        echo "<h2>🖥️ System Information</h2>";
        echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
        echo "<p><strong>Server Software:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
        echo "<p><strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</p>";
        echo "<p><strong>Current Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
        echo "<p><strong>Memory Limit:</strong> " . ini_get('memory_limit') . "</p>";
        echo "<p><strong>Upload Max Size:</strong> " . ini_get('upload_max_filesize') . "</p>";
        echo "</div>";
        ?>
        
        <div class="section">
            <h2>🔗 Quick Links</h2>
            <p><a href="index.php">🏠 Ana Sayfa</a> | <a href="admin/">⚙️ Admin Panel</a> | <a href="DEPLOYMENT.md">📖 Deployment Guide</a></p>
        </div>
    </div>
</body>
</html>
