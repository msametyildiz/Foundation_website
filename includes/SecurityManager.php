<?php
/**
 * Security Scanner and Backup System
 * Necat Derneği Website
 */

class SecurityScanner {
    private $scan_results = [];
    private $threats_found = 0;
    
    /**
     * Run comprehensive security scan
     */
    public function runScan() {
        $this->scan_results = [];
        $this->threats_found = 0;
        
        $this->checkFilePermissions();
        $this->checkSuspiciousFiles();
        $this->checkDatabaseSecurity();
        $this->checkConfigSecurity();
        $this->checkServerSecurity();
        
        return [
            'results' => $this->scan_results,
            'threats_found' => $this->threats_found,
            'status' => $this->threats_found > 0 ? 'warning' : 'clean'
        ];
    }
    
    /**
     * Check file permissions
     */
    private function checkFilePermissions() {
        $files_to_check = [
            'config/database.php' => 0644,
            '.htaccess' => 0644,
            'uploads/' => 0755
        ];
        
        foreach ($files_to_check as $file => $expected_perm) {
            if (file_exists($file)) {
                $current_perm = fileperms($file) & 0777;
                if ($current_perm != $expected_perm) {
                    $this->addResult('warning', "File permissions", 
                        "File $file has permissions " . decoct($current_perm) . 
                        " but should be " . decoct($expected_perm));
                    $this->threats_found++;
                }
            }
        }
    }
    
    /**
     * Check for suspicious files
     */
    private function checkSuspiciousFiles() {
        $suspicious_patterns = [
            '*.php.suspected',
            '*.php.bak',
            '*shell*',
            '*backdoor*',
            '*eval*',
            '*base64*'
        ];
        
        $scan_dirs = ['.', 'uploads/', 'includes/', 'admin/'];
        
        foreach ($scan_dirs as $dir) {
            if (is_dir($dir)) {
                $this->scanDirectoryForThreats($dir, $suspicious_patterns);
            }
        }
    }
    
    /**
     * Scan directory for malicious files
     */
    private function scanDirectoryForThreats($dir, $patterns) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            $filename = $file->getFilename();
            $filepath = $file->getPathname();
            
            // Check filename patterns
            foreach ($patterns as $pattern) {
                if (fnmatch($pattern, $filename)) {
                    $this->addResult('high', "Suspicious file", 
                        "Found suspicious file: $filepath");
                    $this->threats_found++;
                }
            }
            
            // Check file content for malicious code
            if ($file->isFile() && $file->getExtension() === 'php') {
                $this->scanFileContent($filepath);
            }
        }
    }
    
    /**
     * Scan file content for malicious code
     */
    private function scanFileContent($filepath) {
        $content = file_get_contents($filepath);
        
        $malicious_patterns = [
            '/eval\s*\(/i',
            '/base64_decode\s*\(/i',
            '/system\s*\(/i',
            '/exec\s*\(/i',
            '/shell_exec\s*\(/i',
            '/passthru\s*\(/i',
            '/file_get_contents\s*\(\s*["\']http/i',
            '/curl_exec\s*\(/i'
        ];
        
        foreach ($malicious_patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $this->addResult('high', "Malicious code", 
                    "Found suspicious code pattern in: $filepath");
                $this->threats_found++;
            }
        }
    }
    
    /**
     * Check database security
     */
    private function checkDatabaseSecurity() {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS
            );
            
            // Check for default passwords
            if (DB_PASS === 'password' || DB_PASS === '123456' || DB_PASS === 'admin') {
                $this->addResult('high', "Database security", 
                    "Database using weak default password");
                $this->threats_found++;
            }
            
            // Check user privileges
            $stmt = $pdo->query("SHOW GRANTS FOR CURRENT_USER()");
            $grants = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($grants as $grant) {
                if (strpos($grant, 'ALL PRIVILEGES') !== false) {
                    $this->addResult('medium', "Database security", 
                        "Database user has excessive privileges");
                }
            }
            
        } catch (Exception $e) {
            $this->addResult('info', "Database security", 
                "Could not check database security: " . $e->getMessage());
        }
    }
    
    /**
     * Check configuration security
     */
    private function checkConfigSecurity() {
        // Check if display_errors is off
        if (ini_get('display_errors')) {
            $this->addResult('medium', "PHP Configuration", 
                "display_errors is enabled - should be disabled in production");
        }
        
        // Check if expose_php is off
        if (ini_get('expose_php')) {
            $this->addResult('low', "PHP Configuration", 
                "expose_php is enabled - consider disabling");
        }
        
        // Check secret key
        if (defined('SECRET_KEY') && (SECRET_KEY === 'your_secret_key_here_change_this' || 
            strlen(SECRET_KEY) < 32)) {
            $this->addResult('high', "Configuration", 
                "Weak or default secret key detected");
            $this->threats_found++;
        }
    }
    
    /**
     * Check server security headers
     */
    private function checkServerSecurity() {
        $headers_to_check = [
            'X-Content-Type-Options',
            'X-Frame-Options',
            'X-XSS-Protection',
            'Strict-Transport-Security'
        ];
        
        foreach ($headers_to_check as $header) {
            if (!$this->hasSecurityHeader($header)) {
                $this->addResult('medium', "Security headers", 
                    "Missing security header: $header");
            }
        }
    }
    
    /**
     * Check if security header is set
     */
    private function hasSecurityHeader($header_name) {
        $headers = apache_response_headers();
        return isset($headers[$header_name]);
    }
    
    /**
     * Add scan result
     */
    private function addResult($severity, $category, $message) {
        $this->scan_results[] = [
            'severity' => $severity,
            'category' => $category,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}

class BackupManager {
    private $backup_dir;
    private $max_backups;
    
    public function __construct($backup_dir = 'backups', $max_backups = 10) {
        $this->backup_dir = $backup_dir;
        $this->max_backups = $max_backups;
        
        if (!is_dir($this->backup_dir)) {
            mkdir($this->backup_dir, 0755, true);
        }
    }
    
    /**
     * Create full backup
     */
    public function createFullBackup() {
        $timestamp = date('Y-m-d_H-i-s');
        $backup_name = "full_backup_$timestamp";
        
        $results = [
            'database' => $this->backupDatabase($backup_name),
            'files' => $this->backupFiles($backup_name),
            'timestamp' => $timestamp
        ];
        
        if ($results['database']['success'] && $results['files']['success']) {
            $this->cleanOldBackups();
            return ['success' => true, 'backup_name' => $backup_name, 'details' => $results];
        }
        
        return ['success' => false, 'details' => $results];
    }
    
    /**
     * Backup database
     */
    private function backupDatabase($backup_name) {
        try {
            $filename = $this->backup_dir . '/' . $backup_name . '_database.sql';
            
            $command = sprintf(
                'mysqldump -h%s -u%s -p%s %s > %s',
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME,
                $filename
            );
            
            exec($command, $output, $return_code);
            
            if ($return_code === 0 && file_exists($filename)) {
                return [
                    'success' => true,
                    'filename' => $filename,
                    'size' => filesize($filename)
                ];
            } else {
                return ['success' => false, 'error' => 'mysqldump failed'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Backup files
     */
    private function backupFiles($backup_name) {
        try {
            $filename = $this->backup_dir . '/' . $backup_name . '_files.tar.gz';
            
            // Exclude backup directory and logs
            $exclude_dirs = [
                '--exclude=' . $this->backup_dir,
                '--exclude=logs',
                '--exclude=cache',
                '--exclude=vendor'
            ];
            
            $command = sprintf(
                'tar -czf %s %s .',
                $filename,
                implode(' ', $exclude_dirs)
            );
            
            exec($command, $output, $return_code);
            
            if ($return_code === 0 && file_exists($filename)) {
                return [
                    'success' => true,
                    'filename' => $filename,
                    'size' => filesize($filename)
                ];
            } else {
                return ['success' => false, 'error' => 'tar command failed'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Clean old backups
     */
    private function cleanOldBackups() {
        $backups = glob($this->backup_dir . '/full_backup_*');
        
        if (count($backups) > $this->max_backups) {
            // Sort by modification time
            usort($backups, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            // Remove oldest backups
            $to_remove = array_slice($backups, 0, count($backups) - $this->max_backups);
            foreach ($to_remove as $file) {
                unlink($file);
            }
        }
    }
    
    /**
     * List available backups
     */
    public function listBackups() {
        $backups = [];
        $files = glob($this->backup_dir . '/full_backup_*');
        
        foreach ($files as $file) {
            $basename = basename($file);
            if (preg_match('/full_backup_(\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2})/', $basename, $matches)) {
                $timestamp = $matches[1];
                $type = strpos($basename, '_database.sql') !== false ? 'database' : 'files';
                
                if (!isset($backups[$timestamp])) {
                    $backups[$timestamp] = ['timestamp' => $timestamp, 'files' => []];
                }
                
                $backups[$timestamp]['files'][$type] = [
                    'filename' => $basename,
                    'size' => filesize($file),
                    'path' => $file
                ];
            }
        }
        
        // Sort by timestamp descending
        krsort($backups);
        return array_values($backups);
    }
    
    /**
     * Restore from backup
     */
    public function restoreBackup($backup_name) {
        $database_file = $this->backup_dir . '/' . $backup_name . '_database.sql';
        $files_archive = $this->backup_dir . '/' . $backup_name . '_files.tar.gz';
        
        $results = ['database' => false, 'files' => false];
        
        // Restore database
        if (file_exists($database_file)) {
            $command = sprintf(
                'mysql -h%s -u%s -p%s %s < %s',
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME,
                $database_file
            );
            
            exec($command, $output, $return_code);
            $results['database'] = $return_code === 0;
        }
        
        // Restore files
        if (file_exists($files_archive)) {
            $command = "tar -xzf $files_archive";
            exec($command, $output, $return_code);
            $results['files'] = $return_code === 0;
        }
        
        return $results;
    }
}

// Security headers helper
class SecurityHeaders {
    public static function setSecurityHeaders() {
        // Prevent clickjacking
        header('X-Frame-Options: DENY');
        
        // Prevent MIME type sniffing
        header('X-Content-Type-Options: nosniff');
        
        // Enable XSS protection
        header('X-XSS-Protection: 1; mode=block');
        
        // Content Security Policy
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'");
        
        // HTTPS enforcement (uncomment for production with SSL)
        // header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        
        // Referrer policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Feature policy
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    }
}
?>
