<?php
require_once __DIR__ . '/../config/database.php';

class AdminLogger {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function log($action, $description, $userId = null, $entityType = null, $entityId = null) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO admin_logs (user_id, action, description, entity_type, entity_id, ip_address, user_agent, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $userId ?? $_SESSION['user_id'] ?? null,
                $action,
                $description,
                $entityType,
                $entityId,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
            
            return true;
        } catch (Exception $e) {
            error_log("Admin log error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getLogs($limit = 100, $offset = 0, $filters = []) {
        try {
            $query = "SELECT al.*, u.username, u.full_name 
                     FROM admin_logs al 
                     LEFT JOIN users u ON al.user_id = u.id 
                     WHERE 1=1";
            $params = [];
            
            if (!empty($filters['user_id'])) {
                $query .= " AND al.user_id = ?";
                $params[] = $filters['user_id'];
            }
            
            if (!empty($filters['action'])) {
                $query .= " AND al.action = ?";
                $params[] = $filters['action'];
            }
            
            if (!empty($filters['entity_type'])) {
                $query .= " AND al.entity_type = ?";
                $params[] = $filters['entity_type'];
            }
            
            if (!empty($filters['date_from'])) {
                $query .= " AND DATE(al.created_at) >= ?";
                $params[] = $filters['date_from'];
            }
            
            if (!empty($filters['date_to'])) {
                $query .= " AND DATE(al.created_at) <= ?";
                $params[] = $filters['date_to'];
            }
            
            $query .= " ORDER BY al.created_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get logs error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getLogStats($days = 30) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    action,
                    COUNT(*) as count,
                    DATE(created_at) as date
                FROM admin_logs 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY action, DATE(created_at)
                ORDER BY date DESC, count DESC
            ");
            
            $stmt->execute([$days]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get log stats error: " . $e->getMessage());
            return [];
        }
    }
    
    public function cleanOldLogs($days = 90) {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM admin_logs 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
            ");
            
            $stmt->execute([$days]);
            return $stmt->rowCount();
        } catch (Exception $e) {
            error_log("Clean logs error: " . $e->getMessage());
            return 0;
        }
    }
    
    // Common log actions
    public function logLogin($userId, $username) {
        return $this->log('login', "User {$username} logged in", $userId, 'user', $userId);
    }
    
    public function logLogout($userId, $username) {
        return $this->log('logout', "User {$username} logged out", $userId, 'user', $userId);
    }
    
    public function logCreate($entityType, $entityId, $description) {
        return $this->log('create', $description, null, $entityType, $entityId);
    }
    
    public function logUpdate($entityType, $entityId, $description) {
        return $this->log('update', $description, null, $entityType, $entityId);
    }
    
    public function logDelete($entityType, $entityId, $description) {
        return $this->log('delete', $description, null, $entityType, $entityId);
    }
    
    public function logView($entityType, $entityId, $description) {
        return $this->log('view', $description, null, $entityType, $entityId);
    }
    
    public function logExport($entityType, $description) {
        return $this->log('export', $description, null, $entityType);
    }
    
    public function logSystemAction($description) {
        return $this->log('system', $description);
    }
    
    public function logSecurityEvent($description) {
        return $this->log('security', $description);
    }
}
?>
