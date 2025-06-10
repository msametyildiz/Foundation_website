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

$response = ['success' => false, 'message' => '', 'user' => null];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $response['message'] = 'Geçersiz kullanıcı ID.';
    echo json_encode($response);
    exit;
}

$user_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT id, username, email, first_name, last_name, role, status, created_at, last_login FROM admin_users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $response['success'] = true;
        $response['user'] = $user;
    } else {
        $response['message'] = 'Kullanıcı bulunamadı.';
    }
    
} catch (PDOException $e) {
    $response['message'] = 'Veritabanı hatası: ' . $e->getMessage();
}

echo json_encode($response);
?>
