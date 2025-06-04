<?php
// comprehensive_test.php - Test all major functionality
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Necat Derneği Website - Comprehensive Test</h1>\n";

// Test 1: Database Connection
echo "<h2>1. Database Connection Test</h2>\n";
try {
    require_once 'config/database.php';
    echo "✅ Database connection successful<br>\n";
    echo "Database: " . DB_NAME . "<br>\n";
    echo "Host: " . DB_HOST . "<br>\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>\n";
}

// Test 2: Tables Check
echo "<h2>2. Database Tables Check</h2>\n";
try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . count($tables) . "<br>\n";
    foreach ($tables as $table) {
        echo "- " . $table . "<br>\n";
    }
} catch (Exception $e) {
    echo "❌ Tables check failed: " . $e->getMessage() . "<br>\n";
}

// Test 3: Admin User Check
echo "<h2>3. Admin User Check</h2>\n";
try {
    $stmt = $pdo->query("SELECT * FROM admins");
    $admins = $stmt->fetchAll();
    echo "Admin users found: " . count($admins) . "<br>\n";
    foreach ($admins as $admin) {
        echo "- ID: " . $admin['id'] . ", Username: " . $admin['username'] . ", Email: " . $admin['email'] . "<br>\n";
    }
} catch (Exception $e) {
    echo "❌ Admin check failed: " . $e->getMessage() . "<br>\n";
}

// Test 4: PHP Extensions
echo "<h2>4. PHP Extensions Check</h2>\n";
$required_extensions = [
    'pdo', 'pdo_mysql', 'curl', 'mbstring', 'xml', 'zip', 
    'gd', 'openssl', 'json', 'fileinfo'
];
foreach ($required_extensions as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo $status . " " . $ext . "<br>\n";
}

// Test 5: File Permissions
echo "<h2>5. File Permissions Check</h2>\n";
$important_paths = [
    'config/database.php',
    'uploads/',
    'backups/',
    'admin/',
    'ajax/'
];
foreach ($important_paths as $path) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $readable = is_readable($path) ? 'R' : '-';
        $writable = is_writable($path) ? 'W' : '-';
        $executable = is_executable($path) ? 'X' : '-';
        echo "📁 " . $path . " - Permissions: " . $perms . " (" . $readable . $writable . $executable . ")<br>\n";
    } else {
        echo "❌ " . $path . " - Not found<br>\n";
    }
}

// Test 6: Security Classes
echo "<h2>6. Security Classes Check</h2>\n";
try {
    require_once 'includes/SecurityManager.php';
    require_once 'includes/PerformanceOptimizer.php';
    require_once 'includes/AdminLogger.php';
    echo "✅ All security classes loaded successfully<br>\n";
    
    $security = new SecurityManager();
    echo "✅ SecurityManager instantiated<br>\n";
    
    $optimizer = new PerformanceOptimizer();
    echo "✅ PerformanceOptimizer instantiated<br>\n";
    
    $logger = new AdminLogger($pdo);
    echo "✅ AdminLogger instantiated<br>\n";
    
} catch (Exception $e) {
    echo "❌ Security classes test failed: " . $e->getMessage() . "<br>\n";
}

// Test 7: Email Service
echo "<h2>7. Email Service Check</h2>\n";
try {
    require_once 'includes/EmailService.php';
    echo "✅ EmailService class loaded<br>\n";
} catch (Exception $e) {
    echo "❌ EmailService test failed: " . $e->getMessage() . "<br>\n";
}

// Test 8: AJAX Endpoints
echo "<h2>8. AJAX Endpoints Check</h2>\n";
$ajax_files = [
    'ajax/forms.php',
    'ajax/security.php',
    'ajax/get_news.php',
    'ajax/get_project.php',
    'ajax/get_user.php',
    'ajax/upload_image.php'
];
foreach ($ajax_files as $file) {
    if (file_exists($file)) {
        echo "✅ " . $file . " exists<br>\n";
    } else {
        echo "❌ " . $file . " missing<br>\n";
    }
}

// Test 9: Admin Pages
echo "<h2>9. Admin Pages Check</h2>\n";
$admin_pages = [
    'admin/pages/dashboard.php',
    'admin/pages/security.php',
    'admin/pages/logs.php',
    'admin/pages/settings.php',
    'admin/pages/users.php',
    'admin/pages/donations.php',
    'admin/pages/projects.php',
    'admin/pages/news.php'
];
foreach ($admin_pages as $page) {
    if (file_exists($page)) {
        echo "✅ " . $page . " exists<br>\n";
    } else {
        echo "❌ " . $page . " missing<br>\n";
    }
}

// Test 10: Admin Logs Table
echo "<h2>10. Admin Logs Test</h2>\n";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admin_logs");
    $result = $stmt->fetch();
    echo "✅ Admin logs table accessible<br>\n";
    echo "Log entries: " . $result['count'] . "<br>\n";
    
    // Test logging functionality
    $logger = new AdminLogger($pdo);
    $test_log_id = $logger->logAction(1, 'test', 'System test performed', 'system', null, '127.0.0.1');
    if ($test_log_id) {
        echo "✅ Test log entry created (ID: " . $test_log_id . ")<br>\n";
    }
} catch (Exception $e) {
    echo "❌ Admin logs test failed: " . $e->getMessage() . "<br>\n";
}

echo "<h2>Test Completed</h2>\n";
echo "<p><strong>Admin Login Credentials:</strong><br>\n";
echo "Username: admin<br>\n";
echo "Password: admin123</p>\n";

echo "<p><a href='admin/login.php'>Go to Admin Login</a></p>\n";
?>
