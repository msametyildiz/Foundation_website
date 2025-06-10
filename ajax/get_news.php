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

$response = ['success' => false, 'message' => '', 'news' => null];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $response['message'] = 'Geçersiz haber ID.';
    echo json_encode($response);
    exit;
}

$news_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$news_id]);
    $news = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($news) {
        $response['success'] = true;
        $response['news'] = $news;
    } else {
        $response['message'] = 'Haber bulunamadı.';
    }
    
} catch (PDOException $e) {
    $response['message'] = 'Veritabanı hatası: ' . $e->getMessage();
}

echo json_encode($response);
?>
