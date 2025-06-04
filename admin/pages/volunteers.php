<?php
$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle actions
if ($_POST) {
    switch ($action) {
        case 'approve':
            $id = (int)$_POST['id'];
            try {
                $stmt = $pdo->prepare("UPDATE volunteer_applications SET status = 'approved', updated_at = NOW() WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Gönüllü başvurusu onaylandı.';
            } catch (PDOException $e) {
                $error = 'Hata: ' . $e->getMessage();
            }
            break;
            
        case 'reject':
            $id = (int)$_POST['id'];
            try {
                $stmt = $pdo->prepare("UPDATE volunteer_applications SET status = 'rejected', updated_at = NOW() WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Gönüllü başvurusu reddedildi.';
            } catch (PDOException $e) {
                $error = 'Hata: ' . $e->getMessage();
            }
            break;
            
        case 'delete':
            $id = (int)$_POST['id'];
            try {
                $stmt = $pdo->prepare("DELETE FROM volunteer_applications WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Gönüllü başvurusu silindi.';
            } catch (PDOException $e) {
                $error = 'Hata: ' . $e->getMessage();
            }
            break;
    }
}

// Get volunteers
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$position = $_GET['position'] ?? '';
$page_num = (int)($_GET['page_num'] ?? 1);
$per_page = 20;
$offset = ($page_num - 1) * $per_page;

$where_conditions = [];
$params = [];

if ($search) {
    $where_conditions[] = "(full_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status) {
    $where_conditions[] = "status = ?";
    $params[] = $status;
}

if ($position) {
    $where_conditions[] = "position = ?";
    $params[] = $position;
}

$where_clause = empty($where_conditions) ? '' : 'WHERE ' . implode(' AND ', $where_conditions);

try {
    // Count total
    $count_query = "SELECT COUNT(*) FROM volunteer_applications $where_clause";
    $stmt = $pdo->prepare($count_query);
    $stmt->execute($params);
    $total_records = $stmt->fetchColumn();
    $total_pages = ceil($total_records / $per_page);
    
    // Get volunteers
    $query = "SELECT * FROM volunteer_applications $where_clause ORDER BY created_at DESC LIMIT $per_page OFFSET $offset";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $volunteers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get unique positions for filter
    $stmt = $pdo->query("SELECT DISTINCT position FROM volunteer_applications WHERE position IS NOT NULL ORDER BY position");
    $positions = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
} catch (PDOException $e) {
    $error = 'Veritabanı hatası: ' . $e->getMessage();
    $volunteers = [];
    $positions = [];
}
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-users me-2"></i>Gönüllü Yönetimi</h1>
            <div>
                <a href="?page=volunteers&action=export" class="btn btn-success me-2">
                    <i class="fas fa-download me-2"></i>Excel'e Aktar
                </a>
            </div>
        </div>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <?php
    try {
        $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM volunteer_applications GROUP BY status");
        $stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    } catch (PDOException $e) {
        $stats = [];
    }
    ?>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="icon"><i class="fas fa-clock"></i></div>
            <h3><?php echo $stats['pending'] ?? 0; ?></h3>
            <p class="text-muted">Bekleyen</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="icon"><i class="fas fa-check"></i></div>
            <h3><?php echo $stats['approved'] ?? 0; ?></h3>
            <p class="text-muted">Onaylanan</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card danger">
            <div class="icon"><i class="fas fa-times"></i></div>
            <h3><?php echo $stats['rejected'] ?? 0; ?></h3>
            <p class="text-muted">Reddedilen</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card primary">
            <div class="icon"><i class="fas fa-users"></i></div>
            <h3><?php echo array_sum($stats); ?></h3>
            <p class="text-muted">Toplam</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="page" value="volunteers">
            
            <div class="col-md-3">
                <label for="search" class="form-label">Arama</label>
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="İsim, e-posta veya telefon..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            
            <div class="col-md-2">
                <label for="status" class="form-label">Durum</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tümü</option>
                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Bekliyor</option>
                    <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Onaylandı</option>
                    <option value="rejected" <?php echo $status === 'rejected' ? 'selected' : ''; ?>>Reddedildi</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="position" class="form-label">Pozisyon</label>
                <select class="form-select" id="position" name="position">
                    <option value="">Tümü</option>
                    <?php foreach ($positions as $pos): ?>
                        <option value="<?php echo htmlspecialchars($pos); ?>" 
                                <?php echo $position === $pos ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($pos); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Ara
                </button>
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <a href="?page=volunteers" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times me-2"></i>Temizle
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Volunteers Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Gönüllü Başvuruları (<?php echo number_format($total_records); ?> kayıt)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ad Soyad</th>
                        <th>İletişim</th>
                        <th>Pozisyon</th>
                        <th>Durum</th>
                        <th>Başvuru Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($volunteers)): ?>
                        <?php foreach ($volunteers as $volunteer): ?>
                        <tr>
                            <td><strong>#<?php echo $volunteer['id']; ?></strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-info text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <?php echo strtoupper(substr($volunteer['full_name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?php echo htmlspecialchars($volunteer['full_name']); ?></div>
                                        <?php if ($volunteer['birth_date']): ?>
                                            <small class="text-muted">
                                                <?php echo date('Y') - date('Y', strtotime($volunteer['birth_date'])); ?> yaş
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($volunteer['email']); ?></div>
                                <?php if ($volunteer['phone']): ?>
                                    <div><i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($volunteer['phone']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    <?php echo htmlspecialchars($volunteer['position']); ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $statusClass = $volunteer['status'] === 'approved' ? 'success' : 
                                              ($volunteer['status'] === 'rejected' ? 'danger' : 'warning');
                                $statusText = $volunteer['status'] === 'approved' ? 'Onaylandı' : 
                                             ($volunteer['status'] === 'rejected' ? 'Reddedildi' : 'Bekliyor');
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </td>
                            <td>
                                <small>
                                    <?php echo date('d.m.Y H:i', strtotime($volunteer['created_at'])); ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary btn-sm" 
                                            data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $volunteer['id']; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <?php if ($volunteer['status'] === 'pending'): ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $volunteer['id']; ?>">
                                            <button type="submit" name="action" value="approve" 
                                                    class="btn btn-outline-success btn-sm" 
                                                    title="Onayla">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $volunteer['id']; ?>">
                                            <button type="submit" name="action" value="reject" 
                                                    class="btn btn-outline-warning btn-sm" 
                                                    title="Reddet">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $volunteer['id']; ?>">
                                        <button type="submit" name="action" value="delete" 
                                                class="btn btn-outline-danger btn-sm btn-delete" 
                                                title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- View Modal -->
                        <div class="modal fade" id="viewModal<?php echo $volunteer['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Gönüllü Detayları - <?php echo htmlspecialchars($volunteer['full_name']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Kişisel Bilgiler</h6>
                                                <p><strong>Ad Soyad:</strong> <?php echo htmlspecialchars($volunteer['full_name']); ?></p>
                                                <p><strong>E-posta:</strong> <?php echo htmlspecialchars($volunteer['email']); ?></p>
                                                <p><strong>Telefon:</strong> <?php echo htmlspecialchars($volunteer['phone'] ?: '-'); ?></p>
                                                <p><strong>Doğum Tarihi:</strong> 
                                                    <?php echo $volunteer['birth_date'] ? date('d.m.Y', strtotime($volunteer['birth_date'])) : '-'; ?>
                                                </p>
                                                <p><strong>Adres:</strong> <?php echo htmlspecialchars($volunteer['address'] ?: '-'); ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Başvuru Bilgileri</h6>
                                                <p><strong>Pozisyon:</strong> <?php echo htmlspecialchars($volunteer['position']); ?></p>
                                                <p><strong>Deneyim:</strong> <?php echo htmlspecialchars($volunteer['experience'] ?: '-'); ?></p>
                                                <p><strong>Müsaitlik:</strong> <?php echo htmlspecialchars($volunteer['availability'] ?: '-'); ?></p>
                                                <p><strong>Durum:</strong> <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span></p>
                                                <p><strong>Başvuru Tarihi:</strong> <?php echo date('d.m.Y H:i', strtotime($volunteer['created_at'])); ?></p>
                                            </div>
                                        </div>
                                        
                                        <?php if ($volunteer['skills']): ?>
                                            <hr>
                                            <h6>Yetenekler</h6>
                                            <p><?php echo nl2br(htmlspecialchars($volunteer['skills'])); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if ($volunteer['motivation']): ?>
                                            <hr>
                                            <h6>Motivasyon</h6>
                                            <p><?php echo nl2br(htmlspecialchars($volunteer['motivation'])); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <?php if ($volunteer['status'] === 'pending'): ?>
                                            <form method="POST" class="d-inline me-2">
                                                <input type="hidden" name="id" value="<?php echo $volunteer['id']; ?>">
                                                <button type="submit" name="action" value="approve" class="btn btn-success">
                                                    <i class="fas fa-check me-2"></i>Onayla
                                                </button>
                                            </form>
                                            <form method="POST" class="d-inline me-2">
                                                <input type="hidden" name="id" value="<?php echo $volunteer['id']; ?>">
                                                <button type="submit" name="action" value="reject" class="btn btn-warning">
                                                    <i class="fas fa-times me-2"></i>Reddet
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                <h5>Gönüllü başvurusu bulunamadı</h5>
                                <p>Arama kriterlerinizi değiştirip tekrar deneyin.</p>
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
                            <a class="page-link" href="?page=volunteers&page_num=<?php echo $page_num - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&position=<?php echo urlencode($position); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page_num - 2); $i <= min($total_pages, $page_num + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page_num ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=volunteers&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&position=<?php echo urlencode($position); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page_num < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=volunteers&page_num=<?php echo $page_num + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&position=<?php echo urlencode($position); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>
