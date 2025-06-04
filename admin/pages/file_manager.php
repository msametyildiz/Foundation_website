<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$page_title = 'Dosya Yöneticisi';
include 'includes/admin_header.php';

// Handle file operations
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'delete_file':
            $file = $_POST['file'] ?? '';
            $filePath = '../uploads/' . $file;
            if (file_exists($filePath) && unlink($filePath)) {
                $message = 'Dosya başarıyla silindi.';
                $messageType = 'success';
            } else {
                $message = 'Dosya silinirken hata oluştu.';
                $messageType = 'error';
            }
            break;
            
        case 'create_folder':
            $folderName = sanitizeInput($_POST['folder_name'] ?? '');
            if ($folderName) {
                $folderPath = '../uploads/' . $folderName;
                if (!file_exists($folderPath)) {
                    if (mkdir($folderPath, 0755, true)) {
                        $message = 'Klasör başarıyla oluşturuldu.';
                        $messageType = 'success';
                    } else {
                        $message = 'Klasör oluşturulurken hata oluştu.';
                        $messageType = 'error';
                    }
                } else {
                    $message = 'Bu isimde bir klasör zaten var.';
                    $messageType = 'error';
                }
            }
            break;
    }
}

// Get files and folders
$uploadsPath = '../uploads/';
$currentPath = $_GET['path'] ?? '';
$fullPath = $uploadsPath . $currentPath;

// Security check
if (strpos($fullPath, $uploadsPath) !== 0) {
    $fullPath = $uploadsPath;
    $currentPath = '';
}

$files = [];
$folders = [];

if (is_dir($fullPath)) {
    $items = scandir($fullPath);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $itemPath = $fullPath . '/' . $item;
        $relativePath = $currentPath ? $currentPath . '/' . $item : $item;
        
        if (is_dir($itemPath)) {
            $folders[] = [
                'name' => $item,
                'path' => $relativePath,
                'size' => count(scandir($itemPath)) - 2,
                'modified' => filemtime($itemPath)
            ];
        } else {
            $files[] = [
                'name' => $item,
                'path' => $relativePath,
                'size' => filesize($itemPath),
                'modified' => filemtime($itemPath),
                'extension' => pathinfo($item, PATHINFO_EXTENSION),
                'is_image' => in_array(strtolower(pathinfo($item, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'])
            ];
        }
    }
}

// Sort items
usort($folders, function($a, $b) { return strcasecmp($a['name'], $b['name']); });
usort($files, function($a, $b) { return strcasecmp($a['name'], $b['name']); });

function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return round($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return round($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return round($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' B';
    }
}
?>

<div class="admin-content">
    <div class="content-header">
        <h1><i class="fas fa-folder-open"></i> Dosya Yöneticisi</h1>
        <div class="header-actions">
            <button type="button" class="btn btn-primary" onclick="showUploadModal()">
                <i class="fas fa-upload"></i> Dosya Yükle
            </button>
            <button type="button" class="btn btn-secondary" onclick="showCreateFolderModal()">
                <i class="fas fa-folder-plus"></i> Klasör Oluştur
            </button>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Breadcrumb -->
    <div class="breadcrumb-container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="?page=file_manager"><i class="fas fa-home"></i> Ana Dizin</a>
                </li>
                <?php if ($currentPath): ?>
                    <?php 
                    $pathParts = explode('/', $currentPath);
                    $buildPath = '';
                    foreach ($pathParts as $part): 
                        $buildPath .= ($buildPath ? '/' : '') . $part;
                    ?>
                        <li class="breadcrumb-item">
                            <a href="?page=file_manager&path=<?php echo urlencode($buildPath); ?>"><?php echo htmlspecialchars($part); ?></a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ol>
        </nav>
    </div>

    <!-- File Grid -->
    <div class="file-grid">
        <!-- Folders -->
        <?php foreach ($folders as $folder): ?>
            <div class="file-item folder-item">
                <div class="file-icon">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="file-info">
                    <div class="file-name">
                        <a href="?page=file_manager&path=<?php echo urlencode($folder['path']); ?>">
                            <?php echo htmlspecialchars($folder['name']); ?>
                        </a>
                    </div>
                    <div class="file-meta">
                        <?php echo $folder['size']; ?> öğe • <?php echo date('d.m.Y H:i', $folder['modified']); ?>
                    </div>
                </div>
                <div class="file-actions">
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteFolder('<?php echo htmlspecialchars($folder['path']); ?>')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Files -->
        <?php foreach ($files as $file): ?>
            <div class="file-item">
                <div class="file-icon">
                    <?php if ($file['is_image']): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($file['path']); ?>" alt="Preview" class="file-thumbnail">
                    <?php else: ?>
                        <i class="fas fa-file"></i>
                    <?php endif; ?>
                </div>
                <div class="file-info">
                    <div class="file-name" title="<?php echo htmlspecialchars($file['name']); ?>">
                        <?php echo htmlspecialchars($file['name']); ?>
                    </div>
                    <div class="file-meta">
                        <?php echo formatFileSize($file['size']); ?> • <?php echo date('d.m.Y H:i', $file['modified']); ?>
                    </div>
                </div>
                <div class="file-actions">
                    <?php if ($file['is_image']): ?>
                        <button type="button" class="btn btn-sm btn-info" onclick="previewImage('../uploads/<?php echo htmlspecialchars($file['path']); ?>', '<?php echo htmlspecialchars($file['name']); ?>')">
                            <i class="fas fa-eye"></i>
                        </button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-sm btn-success" onclick="copyPath('../uploads/<?php echo htmlspecialchars($file['path']); ?>')">
                        <i class="fas fa-copy"></i>
                    </button>
                    <a href="../uploads/<?php echo htmlspecialchars($file['path']); ?>" class="btn btn-sm btn-primary" download>
                        <i class="fas fa-download"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteFile('<?php echo htmlspecialchars($file['path']); ?>')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($folders) && empty($files)): ?>
            <div class="empty-folder">
                <i class="fas fa-folder-open"></i>
                <p>Bu klasör boş</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dosya Yükle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fileInput" class="form-label">Dosya Seç</label>
                        <input type="file" class="form-control" id="fileInput" name="file" multiple accept="image/*,.pdf,.doc,.docx,.txt">
                        <div class="form-text">Maksimum dosya boyutu: 5MB</div>
                    </div>
                    <div class="upload-progress" style="display: none;">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Yükle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yeni Klasör</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="folderName" class="form-label">Klasör Adı</label>
                        <input type="text" class="form-control" id="folderName" name="folder_name" required>
                    </div>
                    <input type="hidden" name="action" value="create_folder">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Oluştur</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imagePreview" src="" alt="Preview" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<style>
.file-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.file-item {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    transition: all 0.3s ease;
}

.file-item:hover {
    border-color: #2c5aa0;
    box-shadow: 0 2px 8px rgba(44, 90, 160, 0.1);
}

.file-item.folder-item {
    background: #f8f9ff;
}

.file-icon {
    text-align: center;
    margin-bottom: 10px;
}

.file-icon i {
    font-size: 48px;
    color: #2c5aa0;
}

.file-thumbnail {
    max-width: 80px;
    max-height: 80px;
    border-radius: 4px;
}

.file-info {
    text-align: center;
    margin-bottom: 10px;
}

.file-name {
    font-weight: 600;
    margin-bottom: 5px;
    word-break: break-word;
}

.file-name a {
    text-decoration: none;
    color: #2c5aa0;
}

.file-meta {
    font-size: 12px;
    color: #666;
}

.file-actions {
    display: flex;
    justify-content: center;
    gap: 5px;
}

.empty-folder {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.empty-folder i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.breadcrumb-container {
    margin: 20px 0;
}

.breadcrumb {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 5px;
}

.upload-progress {
    margin-top: 15px;
}
</style>

<script>
function showUploadModal() {
    new bootstrap.Modal(document.getElementById('uploadModal')).show();
}

function showCreateFolderModal() {
    new bootstrap.Modal(document.getElementById('createFolderModal')).show();
}

function previewImage(src, title) {
    document.getElementById('imagePreview').src = src;
    document.getElementById('imagePreviewTitle').textContent = title;
    new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
}

function copyPath(path) {
    navigator.clipboard.writeText(path).then(() => {
        showAlert('Dosya yolu panoya kopyalandı', 'success');
    });
}

function deleteFile(file) {
    if (confirm('Bu dosyayı silmek istediğinizden emin misiniz?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete_file">
            <input type="hidden" name="file" value="${file}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// File upload handling
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const fileInput = document.getElementById('fileInput');
    const files = fileInput.files;
    
    if (files.length === 0) {
        showAlert('Lütfen en az bir dosya seçin', 'error');
        return;
    }
    
    const progressContainer = document.querySelector('.upload-progress');
    const progressBar = document.querySelector('.progress-bar');
    
    progressContainer.style.display = 'block';
    
    let uploadedCount = 0;
    const totalFiles = files.length;
    
    Array.from(files).forEach((file, index) => {
        const fileFormData = new FormData();
        fileFormData.append('image', file);
        
        fetch('../ajax/upload_image.php', {
            method: 'POST',
            body: fileFormData
        })
        .then(response => response.json())
        .then(data => {
            uploadedCount++;
            const progress = (uploadedCount / totalFiles) * 100;
            progressBar.style.width = progress + '%';
            
            if (uploadedCount === totalFiles) {
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            uploadedCount++;
            
            if (uploadedCount === totalFiles) {
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        });
    });
});
</script>

<?php include 'includes/admin_footer.php'; ?>
