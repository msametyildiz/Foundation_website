<?php
// ajax/security.php - Security AJAX handlers
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/SecurityManager.php';
require_once '../includes/PerformanceOptimizer.php';
require_once '../includes/AdminLogger.php';

// Check if user is admin
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'run_security_scan':
            $security = new SecurityManager();
            $scan_results = $security->runSecurityScan();
            
            // Log the security scan
            $logger = new AdminLogger($pdo);
            $logger->logAction(
                $_SESSION['admin_id'],
                'security_scan',
                'Manual security scan performed',
                'system',
                null,
                $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
            );
            
            echo json_encode([
                'success' => true,
                'results' => $scan_results,
                'message' => 'Güvenlik taraması tamamlandı'
            ]);
            break;
            
        case 'get_security_status':
            $security = new SecurityManager();
            $status = [
                'php_version' => PHP_VERSION,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'ssl_enabled' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'session_secure' => ini_get('session.cookie_secure'),
                'session_httponly' => ini_get('session.cookie_httponly'),
                'error_reporting' => error_reporting(),
                'extensions' => []
            ];
            
            $required_extensions = [
                'pdo', 'pdo_mysql', 'curl', 'mbstring', 'xml', 'zip', 
                'gd', 'openssl', 'json', 'fileinfo'
            ];
            
            foreach ($required_extensions as $ext) {
                $status['extensions'][$ext] = extension_loaded($ext);
            }
            
            echo json_encode([
                'success' => true,
                'status' => $status
            ]);
            break;
            
        case 'get_performance_metrics':
            $optimizer = new PerformanceOptimizer();
            $metrics = [
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true),
                'memory_limit' => ini_get('memory_limit'),
                'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
                'cache_status' => $optimizer->getCacheStatus(),
                'disk_usage' => disk_free_space('.'),
                'server_load' => sys_getloadavg()
            ];
            
            echo json_encode([
                'success' => true,
                'metrics' => $metrics
            ]);
            break;
            
        case 'clear_cache':
            $optimizer = new PerformanceOptimizer();
            $cleared = $optimizer->clearCache();
            
            // Log cache clear action
            $logger = new AdminLogger($pdo);
            $logger->logAction(
                $_SESSION['admin_id'],
                'cache_clear',
                'System cache cleared',
                'system',
                null,
                $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
            );
            
            echo json_encode([
                'success' => true,
                'message' => 'Önbellek temizlendi',
                'cleared_files' => $cleared
            ]);
            break;
            
        case 'backup_database':
            // Simple database backup functionality
            $backup_file = '../backups/backup_' . date('Y-m-d_H-i-s') . '.sql';
            
            // Create backups directory if it doesn't exist
            if (!is_dir('../backups')) {
                mkdir('../backups', 0755, true);
            }
            
            $command = sprintf(
                'mysqldump -u %s -p%s %s > %s',
                DB_USER,
                DB_PASS,
                DB_NAME,
                $backup_file
            );
            
            $output = [];
            $return_var = 0;
            exec($command, $output, $return_var);
            
            if ($return_var === 0) {
                // Log backup action
                $logger = new AdminLogger($pdo);
                $logger->logAction(
                    $_SESSION['admin_id'],
                    'database_backup',
                    'Database backup created: ' . basename($backup_file),
                    'system',
                    null,
                    $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
                );
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Veritabanı yedegi oluşturuldu',
                    'backup_file' => basename($backup_file)
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Yedekleme işlemi başarısız oldu'
                ]);
            }
            break;
            
        case 'get_recent_logs':
            $limit = (int)($_POST['limit'] ?? 50);
            $stmt = $pdo->prepare("
                SELECT al.*, a.username 
                FROM admin_logs al 
                LEFT JOIN admins a ON al.user_id = a.id 
                ORDER BY al.created_at DESC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            $logs = $stmt->fetchAll();
            
            echo json_encode([
                'success' => true,
                'logs' => $logs
            ]);
            break;
            
        case 'get_file_permissions':
            $important_files = [
                '../config/database.php',
                '../includes/functions.php',
                '../admin/',
                '../uploads/',
                '../.htaccess'
            ];
            
            $permissions = [];
            foreach ($important_files as $file) {
                if (file_exists($file)) {
                    $permissions[$file] = [
                        'permissions' => substr(sprintf('%o', fileperms($file)), -4),
                        'owner' => fileowner($file),
                        'group' => filegroup($file),
                        'readable' => is_readable($file),
                        'writable' => is_writable($file),
                        'executable' => is_executable($file)
                    ];
                }
            }
            
            echo json_encode([
                'success' => true,
                'permissions' => $permissions
            ]);
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
    
} catch (Exception $e) {
    error_log("Security AJAX Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => $e->getMessage()
    ]);
}
?>
