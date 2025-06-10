<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$response = ['success' => false, 'message' => '', 'project' => null];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $response['message'] = 'Geçersiz proje ID.';
    echo json_encode($response);
    exit;
}

$project_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($project) {
        $response['success'] = true;
        $response['project'] = $project;
    } else {
        $response['message'] = 'Proje bulunamadı.';
    }
    
} catch (PDOException $e) {
    $response['message'] = 'Veritabanı hatası: ' . $e->getMessage();
}

echo json_encode($response);
?>
