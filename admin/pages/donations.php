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
                $stmt = $pdo->prepare("UPDATE donations SET status = 'confirmed', confirmed_at = NOW() WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Bağış onaylandı.';
            } catch (PDOException $e) {
                $error = 'Hata: ' . $e->getMessage();
            }
            break;
            
        case 'reject':
            $id = (int)$_POST['id'];
            try {
                $stmt = $pdo->prepare("UPDATE donations SET status = 'rejected' WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Bağış reddedildi.';
            } catch (PDOException $e) {
                $error = 'Hata: ' . $e->getMessage();
            }
            break;
            
        case 'delete':
            $id = (int)$_POST['id'];
            try {
                $stmt = $pdo->prepare("DELETE FROM donations WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Bağış silindi.';
            } catch (PDOException $e) {
                $error = 'Hata: ' . $e->getMessage();
            }
            break;
    }
}

// Get donations
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$page_num = (int)($_GET['page_num'] ?? 1);
$per_page = 20;
$offset = ($page_num - 1) * $per_page;

$where_conditions = [];
$params = [];

if ($search) {
    $where_conditions[] = "(donor_name LIKE ? OR donor_email LIKE ? OR reference_number LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status) {
    $where_conditions[] = "status = ?";
    $params[] = $status;
}

$where_clause = empty($where_conditions) ? '' : 'WHERE ' . implode(' AND ', $where_conditions);

try {
    // Count total
    $count_query = "SELECT COUNT(*) FROM donations $where_clause";
    $stmt = $pdo->prepare($count_query);
    $stmt->execute($params);
    $total_records = $stmt->fetchColumn();
    $total_pages = ceil($total_records / $per_page);
    
    // Get donations
    $query = "SELECT * FROM donations $where_clause ORDER BY created_at DESC LIMIT $per_page OFFSET $offset";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = 'Veritabanı hatası: ' . $e->getMessage();
    $donations = [];
}
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-hand-holding-heart me-2"></i>Bağış Yönetimi</h1>
            <div>
                <a href="?page=donations&action=export" class="btn btn-success me-2">
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

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="page" value="donations">
            
            <div class="col-md-4">
                <label for="search" class="form-label">Arama</label>
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="İsim, e-posta veya referans no..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            
            <div class="col-md-3">
                <label for="status" class="form-label">Durum</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tümü</option>
                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Bekliyor</option>
                    <option value="confirmed" <?php echo $status === 'confirmed' ? 'selected' : ''; ?>>Onaylandı</option>
                    <option value="rejected" <?php echo $status === 'rejected' ? 'selected' : ''; ?>>Reddedildi</option>
                </select>
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Ara
                </button>
            </div>
            
            <div class="col-md-3 d-flex align-items-end">
                <a href="?page=donations" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times me-2"></i>Temizle
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Donations Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Bağış Listesi (<?php echo number_format($total_records); ?> kayıt)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bağışçı</th>
                        <th>İletişim</th>
                        <th>Miktar</th>
                        <th>Tip</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($donations)): ?>
                        <?php foreach ($donations as $donation): ?>
                        <tr>
                            <td><strong>#<?php echo $donation['id']; ?></strong></td>
                            <td>
                                <div>
                                    <div class="fw-semibold"><?php echo htmlspecialchars($donation['donor_name'] ?: 'Anonim'); ?></div>
                                    <?php if ($donation['reference_number']): ?>
                                        <small class="text-muted">Ref: <?php echo htmlspecialchars($donation['reference_number']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($donation['donor_email']): ?>
                                    <div><i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($donation['donor_email']); ?></div>
                                <?php endif; ?>
                                <?php if ($donation['donor_phone']): ?>
                                    <div><i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($donation['donor_phone']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-bold text-success">₺<?php echo number_format($donation['amount'], 2); ?></span>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <?php echo $donation['donation_type'] === 'money' ? 'Para' : 'Malzeme'; ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $statusClass = $donation['status'] === 'confirmed' ? 'success' : 
                                              ($donation['status'] === 'rejected' ? 'danger' : 'warning');
                                $statusText = $donation['status'] === 'confirmed' ? 'Onaylandı' : 
                                             ($donation['status'] === 'rejected' ? 'Reddedildi' : 'Bekliyor');
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </td>
                            <td>
                                <small>
                                    <?php echo date('d.m.Y H:i', strtotime($donation['created_at'])); ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary btn-sm" 
                                            data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $donation['id']; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <?php if ($donation['status'] === 'pending'): ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $donation['id']; ?>">
                                            <button type="submit" name="action" value="approve" 
                                                    class="btn btn-outline-success btn-sm" 
                                                    title="Onayla">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $donation['id']; ?>">
                                            <button type="submit" name="action" value="reject" 
                                                    class="btn btn-outline-warning btn-sm" 
                                                    title="Reddet">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $donation['id']; ?>">
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
                        <div class="modal fade" id="viewModal<?php echo $donation['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Bağış Detayları - #<?php echo $donation['id']; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Bağışçı Bilgileri</h6>
                                                <p><strong>Ad Soyad:</strong> <?php echo htmlspecialchars($donation['donor_name'] ?: 'Anonim'); ?></p>
                                                <p><strong>E-posta:</strong> <?php echo htmlspecialchars($donation['donor_email'] ?: '-'); ?></p>
                                                <p><strong>Telefon:</strong> <?php echo htmlspecialchars($donation['donor_phone'] ?: '-'); ?></p>
                                                <p><strong>Adres:</strong> <?php echo htmlspecialchars($donation['donor_address'] ?: '-'); ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Bağış Bilgileri</h6>
                                                <p><strong>Miktar:</strong> ₺<?php echo number_format($donation['amount'], 2); ?></p>
                                                <p><strong>Tip:</strong> <?php echo $donation['donation_type'] === 'money' ? 'Para' : 'Malzeme'; ?></p>
                                                <p><strong>Durum:</strong> <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span></p>
                                                <p><strong>Tarih:</strong> <?php echo date('d.m.Y H:i', strtotime($donation['created_at'])); ?></p>
                                                <?php if ($donation['confirmed_at']): ?>
                                                    <p><strong>Onay Tarihi:</strong> <?php echo date('d.m.Y H:i', strtotime($donation['confirmed_at'])); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <?php if ($donation['message']): ?>
                                            <hr>
                                            <h6>Mesaj</h6>
                                            <p><?php echo nl2br(htmlspecialchars($donation['message'])); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if ($donation['receipt_file']): ?>
                                            <hr>
                                            <h6>Makbuz</h6>
                                            <p>
                                                <a href="../uploads/receipts/<?php echo htmlspecialchars($donation['receipt_file']); ?>" 
                                                   target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-file-alt me-2"></i>Makbuzu Görüntüle
                                                </a>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                <h5>Bağış bulunamadı</h5>
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
                            <a class="page-link" href="?page=donations&page_num=<?php echo $page_num - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page_num - 2); $i <= min($total_pages, $page_num + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page_num ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=donations&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page_num < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=donations&page_num=<?php echo $page_num + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>
