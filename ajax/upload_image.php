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

$response = ['success' => false, 'message' => '', 'filename' => ''];

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $response['message'] = 'Dosya yükleme hatası.';
    echo json_encode($response);
    exit;
}

$file = $_FILES['image'];
$upload_type = $_POST['type'] ?? 'general'; // projects, news, general

// Validate file type
$allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowed_types)) {
    $response['message'] = 'Geçersiz dosya türü. Sadece JPEG, PNG, GIF ve WebP dosyaları kabul edilir.';
    echo json_encode($response);
    exit;
}

// Validate file size (max 5MB)
$max_size = 5 * 1024 * 1024; // 5MB
if ($file['size'] > $max_size) {
    $response['message'] = 'Dosya boyutu çok büyük. Maksimum 5MB olmalıdır.';
    echo json_encode($response);
    exit;
}

try {
    // Create upload directory if it doesn't exist
    $upload_dir = '../uploads/' . $upload_type . '/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '_' . time() . '.' . $file_extension;
    $upload_path = $upload_dir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Resize image if needed
        $resized = resizeImage($upload_path, 800, 600); // Max 800x600
        
        $response['success'] = true;
        $response['filename'] = $filename;
        $response['path'] = '/uploads/' . $upload_type . '/' . $filename;
        $response['message'] = 'Dosya başarıyla yüklendi.';
    } else {
        $response['message'] = 'Dosya yüklenirken hata oluştu.';
    }
    
} catch (Exception $e) {
    $response['message'] = 'Hata: ' . $e->getMessage();
}

echo json_encode($response);

/**
 * Resize image to maximum dimensions while maintaining aspect ratio
 */
function resizeImage($source, $max_width, $max_height) {
    $image_info = getimagesize($source);
    if (!$image_info) {
        return false;
    }
    
    $width = $image_info[0];
    $height = $image_info[1];
    $type = $image_info[2];
    
    // Check if resize is needed
    if ($width <= $max_width && $height <= $max_height) {
        return true; // No resize needed
    }
    
    // Calculate new dimensions
    $ratio = min($max_width / $width, $max_height / $height);
    $new_width = round($width * $ratio);
    $new_height = round($height * $ratio);
    
    // Create source image
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source);
            break;
        case IMAGETYPE_GIF:
            $source_image = imagecreatefromgif($source);
            break;
        case IMAGETYPE_WEBP:
            $source_image = imagecreatefromwebp($source);
            break;
        default:
            return false;
    }
    
    if (!$source_image) {
        return false;
    }
    
    // Create new image
    $new_image = imagecreatetruecolor($new_width, $new_height);
    
    // Preserve transparency for PNG and GIF
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
        imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
    }
    
    // Resize image
    imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    // Save resized image
    switch ($type) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($new_image, $source, 85);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($new_image, $source, 8);
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($new_image, $source);
            break;
        case IMAGETYPE_WEBP:
            $result = imagewebp($new_image, $source, 85);
            break;
        default:
            $result = false;
    }
    
    // Clean up
    imagedestroy($source_image);
    imagedestroy($new_image);
    
    return $result;
}
?>
