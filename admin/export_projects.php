<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

$export_type = $_GET['export'] ?? 'excel';
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$category = $_GET['category'] ?? '';

// Build query
$where_conditions = [];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "(title LIKE ? OR description LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if (!empty($status)) {
    $where_conditions[] = "status = ?";
    $params[] = $status;
}

if (!empty($category)) {
    $where_conditions[] = "category = ?";
    $params[] = $category;
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

try {
    $sql = "SELECT id, title, description, category, status, target_amount, current_amount, 
            is_featured, created_at FROM projects {$where_clause} ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($export_type === 'excel') {
        // Excel export
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="projeler_' . date('Y-m-d') . '.xls"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo '<table border="1">';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Başlık</th>';
        echo '<th>Kategori</th>';
        echo '<th>Durum</th>';
        echo '<th>Hedef Miktar</th>';
        echo '<th>Toplanan Miktar</th>';
        echo '<th>İlerleme (%)</th>';
        echo '<th>Öne Çıkan</th>';
        echo '<th>Oluşturma Tarihi</th>';
        echo '</tr>';

        foreach ($projects as $project) {
            $progress = $project['target_amount'] > 0 ? 
                       ($project['current_amount'] / $project['target_amount']) * 100 : 0;
            
            $categories = [
                'education' => 'Eğitim',
                'health' => 'Sağlık',
                'disaster' => 'Afet',
                'social' => 'Sosyal',
                'orphan' => 'Yetim'
            ];
            
            $statuses = [
                'planning' => 'Planlama',
                'active' => 'Aktif',
                'completed' => 'Tamamlandı',
                'cancelled' => 'İptal'
            ];

            echo '<tr>';
            echo '<td>' . $project['id'] . '</td>';
            echo '<td>' . htmlspecialchars($project['title']) . '</td>';
            echo '<td>' . ($categories[$project['category']] ?? $project['category']) . '</td>';
            echo '<td>' . ($statuses[$project['status']] ?? $project['status']) . '</td>';
            echo '<td>' . number_format($project['target_amount'], 2) . ' ₺</td>';
            echo '<td>' . number_format($project['current_amount'], 2) . ' ₺</td>';
            echo '<td>' . number_format($progress, 1) . '%</td>';
            echo '<td>' . ($project['is_featured'] ? 'Evet' : 'Hayır') . '</td>';
            echo '<td>' . date('d.m.Y H:i', strtotime($project['created_at'])) . '</td>';
            echo '</tr>';
        }
        echo '</table>';

    } elseif ($export_type === 'pdf') {
        // PDF export (basic HTML to PDF)
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="projeler_' . date('Y-m-d') . '.pdf"');
        
        // For a real implementation, use libraries like TCPDF or mPDF
        echo "PDF export functionality requires a PDF library like TCPDF or mPDF.";
    }

} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    exit('Database error: ' . $e->getMessage());
}
?>
