<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$page = isset($_GET['page']) ? sanitizeInput($_GET['page']) : 'dashboard';
$allowed_pages = ['dashboard', 'donations', 'volunteers', 'projects', 'news', 'messages', 'users', 'file_manager', 'logs', 'security', 'settings', 'logout'];

if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}

if ($page === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit();
}

include 'includes/admin_header.php';
include 'pages/' . $page . '.php';
include 'includes/admin_footer.php';
?>
