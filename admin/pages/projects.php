<?php
// Projects management page for admin panel
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? 0;
    
    try {
        if ($action === 'update_status') {
            $status = $_POST['status'] ?? '';
            $stmt = $pdo->prepare("UPDATE projects SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $success = "Proje durumu güncellendi.";
        }
        
        if ($action === 'toggle_featured') {
            $stmt = $pdo->prepare("UPDATE projects SET is_featured = NOT is_featured WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Öne çıkarma durumu güncellendi.";
        }
        
        if ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Proje silindi.";
        }
        
        if ($action === 'add') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $target_amount = $_POST['target_amount'] ?? 0;
            $category = $_POST['category'] ?? '';
            $status = $_POST['status'] ?? 'planning';
            
            $stmt = $pdo->prepare("INSERT INTO projects (title, description, target_amount, current_amount, category, status, created_at) VALUES (?, ?, ?, 0, ?, ?, NOW())");
            $stmt->execute([$title, $description, $target_amount, $category, $status]);
            $success = "Yeni proje eklendi.";
        }
        
        if ($action === 'edit') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $target_amount = $_POST['target_amount'] ?? 0;
            $category = $_POST['category'] ?? '';
            $status = $_POST['status'] ?? 'planning';
            
            $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, target_amount = ?, category = ?, status = ? WHERE id = ?");
            $stmt->execute([$title, $description, $target_amount, $category, $status, $id]);
            $success = "Proje güncellendi.";
        }
        
    } catch (PDOException $e) {
        $error = "Veritabanı hatası: " . $e->getMessage();
    }
}

// Get filters
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$category = $_GET['category'] ?? '';
$page_num = max(1, $_GET['page_num'] ?? 1);
$per_page = 15;
$offset = ($page_num - 1) * $per_page;

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

// Get total count
$count_sql = "SELECT COUNT(*) FROM projects {$where_clause}";
$stmt = $pdo->prepare($count_sql);
$stmt->execute($params);
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $per_page);

// Get projects
$sql = "SELECT * FROM projects {$where_clause} ORDER BY created_at DESC LIMIT {$per_page} OFFSET {$offset}";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get project statistics
$stats_sql = "SELECT 
    COUNT(*) as total_projects,
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count,
    SUM(CASE WHEN status = 'planning' THEN 1 ELSE 0 END) as planning_count,
    SUM(target_amount) as total_target,
    SUM(current_amount) as total_raised
    FROM projects";
$stmt = $pdo->query($stats_sql);
$project_stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-project-diagram me-2"></i>Proje Yönetimi</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                <i class="fas fa-plus me-2"></i>Yeni Proje Ekle
            </button>
        </div>
    </div>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card primary">
            <div class="icon">
                <i class="fas fa-project-diagram"></i>
            </div>
            <h3><?php echo number_format($project_stats['total_projects'] ?? 0); ?></h3>
            <p class="text-muted">Toplam Proje</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="icon">
                <i class="fas fa-play-circle"></i>
            </div>
            <h3><?php echo number_format($project_stats['active_count'] ?? 0); ?></h3>
            <p class="text-muted">Aktif Proje</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3><?php echo number_format($project_stats['completed_count'] ?? 0); ?></h3>
            <p class="text-muted">Tamamlanan</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="icon">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <h3><?php echo number_format($project_stats['total_raised'] ?? 0); ?> ₺</h3>
            <p class="text-muted">Toplanan Bağış</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="page" value="projects">
            
            <div class="col-md-3">
                <label class="form-label">Arama</label>
                <input type="text" name="search" class="form-control" value="<?php echo htmlspecialchars($search); ?>" placeholder="Proje ara...">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Durum</label>
                <select name="status" class="form-select">
                    <option value="">Tümü</option>
                    <option value="planning" <?php echo $status === 'planning' ? 'selected' : ''; ?>>Planlama</option>
                    <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>Tamamlandı</option>
                    <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>İptal</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select">
                    <option value="">Tümü</option>
                    <option value="education" <?php echo $category === 'education' ? 'selected' : ''; ?>>Eğitim</option>
                    <option value="health" <?php echo $category === 'health' ? 'selected' : ''; ?>>Sağlık</option>
                    <option value="disaster" <?php echo $category === 'disaster' ? 'selected' : ''; ?>>Afet</option>
                    <option value="social" <?php echo $category === 'social' ? 'selected' : ''; ?>>Sosyal</option>
                    <option value="orphan" <?php echo $category === 'orphan' ? 'selected' : ''; ?>>Yetim</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrele
                    </button>
                    <a href="?page=projects" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Temizle
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Projects Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Projeler 
            <span class="badge bg-secondary"><?php echo $total_records; ?></span>
        </h5>
        <div class="d-flex gap-2">
            <button class="btn btn-success btn-sm" onclick="exportData('excel')">
                <i class="fas fa-file-excel me-1"></i>Excel
            </button>
            <button class="btn btn-info btn-sm" onclick="exportData('pdf')">
                <i class="fas fa-file-pdf me-1"></i>PDF
            </button>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Proje</th>
                        <th>Kategori</th>
                        <th>Hedef/Toplanan</th>
                        <th>İlerleme</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($projects)): ?>
                        <?php foreach ($projects as $project): ?>
                            <?php
                            $progress = $project['target_amount'] > 0 ? 
                                       ($project['current_amount'] / $project['target_amount']) * 100 : 0;
                            $progress = min($progress, 100);
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="project-thumb me-3">
                                            <?php if ($project['is_featured']): ?>
                                                <span class="badge bg-warning position-absolute" style="top: -5px; right: -5px; z-index: 10;">
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            <?php endif; ?>
                                            <img src="<?php echo !empty($project['image']) ? '../uploads/projects/' . $project['image'] : '../assets/images/default-project.jpg'; ?>" 
                                                 alt="<?php echo htmlspecialchars($project['title']); ?>" 
                                                 class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($project['title']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars(substr($project['description'], 0, 60)); ?>...</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $categories = [
                                        'education' => 'Eğitim',
                                        'health' => 'Sağlık',
                                        'disaster' => 'Afet',
                                        'social' => 'Sosyal',
                                        'orphan' => 'Yetim'
                                    ];
                                    echo $categories[$project['category']] ?? $project['category'];
                                    ?>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong class="text-primary"><?php echo number_format($project['current_amount']); ?> ₺</strong><br>
                                        <small class="text-muted"><?php echo number_format($project['target_amount']); ?> ₺</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress" style="width: 80px;">
                                        <div class="progress-bar bg-primary" style="width: <?php echo $progress; ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?php echo number_format($progress, 1); ?>%</small>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'planning' => 'warning',
                                        'active' => 'success',
                                        'completed' => 'info',
                                        'cancelled' => 'danger'
                                    ];
                                    $statusText = [
                                        'planning' => 'Planlama',
                                        'active' => 'Aktif',
                                        'completed' => 'Tamamlandı',
                                        'cancelled' => 'İptal'
                                    ];
                                    ?>
                                    <span class="badge bg-<?php echo $statusClass[$project['status']] ?? 'secondary'; ?>">
                                        <?php echo $statusText[$project['status']] ?? $project['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <small><?php echo date('d.m.Y', strtotime($project['created_at'])); ?></small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-outline-primary btn-sm" 
                                                onclick="editProject(<?php echo $project['id']; ?>)" 
                                                title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                            <button type="submit" name="action" value="toggle_featured" 
                                                    class="btn btn-outline-warning btn-sm" 
                                                    title="<?php echo $project['is_featured'] ? 'Öne Çıkarmayı Kaldır' : 'Öne Çıkar'; ?>">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                        
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                    data-bs-toggle="dropdown" title="Durum">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                                        <input type="hidden" name="status" value="planning">
                                                        <button type="submit" name="action" value="update_status" class="dropdown-item">
                                                            <i class="fas fa-clock text-warning me-2"></i>Planlama
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                                        <input type="hidden" name="status" value="active">
                                                        <button type="submit" name="action" value="update_status" class="dropdown-item">
                                                            <i class="fas fa-play text-success me-2"></i>Aktif
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" name="action" value="update_status" class="dropdown-item">
                                                            <i class="fas fa-check text-info me-2"></i>Tamamlandı
                                                        </button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" name="action" value="update_status" class="dropdown-item text-danger">
                                                            <i class="fas fa-times text-danger me-2"></i>İptal
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                            <button type="submit" name="action" value="delete" 
                                                    class="btn btn-outline-danger btn-sm btn-delete" 
                                                    title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                Henüz proje bulunmuyor
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="card-footer">
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <?php if ($page_num > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=projects&page_num=<?php echo $page_num - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&category=<?php echo urlencode($category); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page_num - 2); $i <= min($total_pages, $page_num + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page_num ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=projects&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&category=<?php echo urlencode($category); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page_num < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=projects&page_num=<?php echo $page_num + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&category=<?php echo urlencode($category); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yeni Proje Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Proje Başlığı</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-select" required>
                                <option value="">Seçiniz</option>
                                <option value="education">Eğitim</option>
                                <option value="health">Sağlık</option>
                                <option value="disaster">Afet</option>
                                <option value="social">Sosyal</option>
                                <option value="orphan">Yetim</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Hedef Miktar (₺)</label>
                            <input type="number" name="target_amount" class="form-control" min="0" step="0.01" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Açıklama</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Durum</label>
                            <select name="status" class="form-select" required>
                                <option value="planning">Planlama</option>
                                <option value="active">Aktif</option>
                                <option value="completed">Tamamlandı</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" name="action" value="add" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proje Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editProjectForm">
                <input type="hidden" name="id" id="editProjectId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Proje Başlığı</label>
                            <input type="text" name="title" id="editProjectTitle" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="category" id="editProjectCategory" class="form-select" required>
                                <option value="">Seçiniz</option>
                                <option value="education">Eğitim</option>
                                <option value="health">Sağlık</option>
                                <option value="disaster">Afet</option>
                                <option value="social">Sosyal</option>
                                <option value="orphan">Yetim</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Hedef Miktar (₺)</label>
                            <input type="number" name="target_amount" id="editProjectTarget" class="form-control" min="0" step="0.01" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Açıklama</label>
                            <textarea name="description" id="editProjectDescription" class="form-control" rows="4" required></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Durum</label>
                            <select name="status" id="editProjectStatus" class="form-select" required>
                                <option value="planning">Planlama</option>
                                <option value="active">Aktif</option>
                                <option value="completed">Tamamlandı</option>
                                <option value="cancelled">İptal</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" name="action" value="edit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editProject(id) {
    // AJAX call to get project details
    fetch(`../ajax/get_project.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('editProjectId').value = data.project.id;
                document.getElementById('editProjectTitle').value = data.project.title;
                document.getElementById('editProjectCategory').value = data.project.category;
                document.getElementById('editProjectTarget').value = data.project.target_amount;
                document.getElementById('editProjectDescription').value = data.project.description;
                document.getElementById('editProjectStatus').value = data.project.status;
                
                new bootstrap.Modal(document.getElementById('editProjectModal')).show();
            } else {
                alert('Proje bilgileri yüklenirken hata oluştu.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu.');
        });
}

function exportData(format) {
    const currentParams = new URLSearchParams(window.location.search);
    currentParams.set('export', format);
    window.open(`export_projects.php?${currentParams.toString()}`, '_blank');
}
</script>
