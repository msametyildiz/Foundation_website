<?php
require_once 'config/database.php';

try {
    // Check if admin_logs table exists
    $result = $pdo->query("SHOW TABLES LIKE 'admin_logs'");
    
    if ($result->rowCount() > 0) {
        echo "admin_logs table already exists\n";
    } else {
        // Create admin_logs table
        $sql = "
        CREATE TABLE admin_logs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            action VARCHAR(50) NOT NULL,
            description TEXT,
            entity_type VARCHAR(50),
            entity_id INT,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            INDEX idx_user_id (user_id),
            INDEX idx_action (action),
            INDEX idx_entity (entity_type, entity_id),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($sql);
        echo "admin_logs table created successfully\n";
        
        // Insert some sample data
        $sampleData = "
        INSERT INTO admin_logs (user_id, action, description, entity_type, entity_id, ip_address, created_at) VALUES
        (1, 'login', 'Admin user logged in', 'user', 1, '127.0.0.1', '2024-12-03 10:00:00'),
        (1, 'create', 'Created new project: Yetim Çocuklara Yardım', 'project', 1, '127.0.0.1', '2024-12-03 10:15:00'),
        (1, 'update', 'Updated project status to active', 'project', 1, '127.0.0.1', '2024-12-03 10:20:00'),
        (1, 'create', 'Created news article: Ramazan Kampanyası', 'news', 1, '127.0.0.1', '2024-12-03 11:00:00'),
        (1, 'export', 'Exported donations report', 'donation', NULL, '127.0.0.1', '2024-12-03 11:30:00'),
        (1, 'view', 'Viewed volunteer applications', 'volunteer', NULL, '127.0.0.1', '2024-12-03 12:00:00'),
        (1, 'update', 'Updated system settings', 'setting', NULL, '127.0.0.1', '2024-12-03 12:15:00'),
        (1, 'delete', 'Deleted spam message', 'message', 5, '127.0.0.1', '2024-12-03 13:00:00'),
        (1, 'system', 'Database backup completed', NULL, NULL, '127.0.0.1', '2024-12-03 14:00:00'),
        (1, 'security', 'Failed login attempt blocked', 'user', NULL, '192.168.1.100', '2024-12-03 15:00:00');
        ";
        
        $pdo->exec($sampleData);
        echo "Sample data inserted\n";
    }
    
    echo "Database setup completed successfully\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
