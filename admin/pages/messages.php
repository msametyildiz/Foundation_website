<?php
$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle actions
if ($_POST) {
    switch ($action) {
        case 'reply':
            $id = (int)$_POST['id'];
            $reply_message = sanitizeInput($_POST['reply_message']);
            try {
                $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'replied', admin_reply = ?, replied_at = NOW(), replied_by = ? WHERE id = ?");
                $stmt->execute([$reply_message, $_SESSION['admin_id'], $id]);
                
                // Send email reply (implement with PHPMailer)
                $message = 'Mesaja cevap gönderildi.';
            } catch (PDOException $e) {
                $error = 'Hata: ' . $e->getMessage();
            }
            break;
            
        case 'mark_read':
            $id = (int)$_POST['id'];
            try {
                $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'read', read_at = NOW() WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Mesaj okundu olarak işaretlendi.';
            } catch (PDOException $e) {
                $error = 'Hata: ' . $e->getMessage();
            }
            break;
            
        case 'delete':
            $id = (int)$_POST['id'];
            try {
                $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Mesaj silindi.';
            } catch (PDOException $e) {
                $error = 'Hata: ' . $e->getMessage();
            }
            break;
    }
}

// Get messages
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$page_num = (int)($_GET['page_num'] ?? 1);
$per_page = 20;
$offset = ($page_num - 1) * $per_page;

$where_conditions = [];
$params = [];

if ($search) {
    $where_conditions[] = "(name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
    $params[] = "%$search%";
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
    $count_query = "SELECT COUNT(*) FROM contact_messages $where_clause";
    $stmt = $pdo->prepare($count_query);
    $stmt->execute($params);
    $total_records = $stmt->fetchColumn();
    $total_pages = ceil($total_records / $per_page);
    
    // Get messages
    $query = "SELECT cm.*, a.username as replied_by_username 
              FROM contact_messages cm 
              LEFT JOIN admins a ON cm.replied_by = a.id 
              $where_clause 
              ORDER BY cm.created_at DESC 
              LIMIT $per_page OFFSET $offset";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get statistics
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM contact_messages GROUP BY status");
    $stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
} catch (PDOException $e) {
    $error = 'Veritabanı hatası: ' . $e->getMessage();
    $messages = [];
    $stats = [];
}
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-envelope me-2"></i>Mesaj Yönetimi</h1>
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
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="icon"><i class="fas fa-envelope"></i></div>
            <h3><?php echo $stats['pending'] ?? 0; ?></h3>
            <p class="text-muted">Yeni Mesaj</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="icon"><i class="fas fa-envelope-open"></i></div>
            <h3><?php echo $stats['read'] ?? 0; ?></h3>
            <p class="text-muted">Okundu</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="icon"><i class="fas fa-reply"></i></div>
            <h3><?php echo $stats['replied'] ?? 0; ?></h3>
            <p class="text-muted">Cevaplanmış</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card primary">
            <div class="icon"><i class="fas fa-envelope-square"></i></div>
            <h3><?php echo array_sum($stats); ?></h3>
            <p class="text-muted">Toplam</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="page" value="messages">
            
            <div class="col-md-4">
                <label for="search" class="form-label">Arama</label>
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="İsim, e-posta, konu veya mesaj..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            
            <div class="col-md-3">
                <label for="status" class="form-label">Durum</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tümü</option>
                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Yeni</option>
                    <option value="read" <?php echo $status === 'read' ? 'selected' : ''; ?>>Okundu</option>
                    <option value="replied" <?php echo $status === 'replied' ? 'selected' : ''; ?>>Cevaplanmış</option>
                </select>
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Ara
                </button>
            </div>
            
            <div class="col-md-3 d-flex align-items-end">
                <a href="?page=messages" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times me-2"></i>Temizle
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Messages Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Mesajlar (<?php echo number_format($total_records); ?> kayıt)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gönderen</th>
                        <th>Konu</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $msg): ?>
                        <tr class="<?php echo $msg['status'] === 'pending' ? 'table-warning' : ''; ?>">
                            <td><strong>#<?php echo $msg['id']; ?></strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <?php echo strtoupper(substr($msg['name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?php echo htmlspecialchars($msg['name']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($msg['email']); ?></small>
                                        <?php if ($msg['phone']): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($msg['phone']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($msg['subject']); ?></div>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars(substr($msg['message'], 0, 100)) . (strlen($msg['message']) > 100 ? '...' : ''); ?>
                                </small>
                            </td>
                            <td>
                                <?php
                                $statusClass = $msg['status'] === 'replied' ? 'success' : 
                                              ($msg['status'] === 'read' ? 'info' : 'warning');
                                $statusText = $msg['status'] === 'replied' ? 'Cevaplanmış' : 
                                             ($msg['status'] === 'read' ? 'Okundu' : 'Yeni');
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                <?php if ($msg['status'] === 'pending'): ?>
                                    <br><small class="text-danger">
                                        <i class="fas fa-bell"></i> Acil
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small>
                                    <?php echo date('d.m.Y H:i', strtotime($msg['created_at'])); ?>
                                    <?php if ($msg['replied_at']): ?>
                                        <br><span class="text-success">
                                            Cevap: <?php echo date('d.m.Y H:i', strtotime($msg['replied_at'])); ?>
                                        </span>
                                    <?php endif; ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary btn-sm" 
                                            data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $msg['id']; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <?php if ($msg['status'] !== 'replied'): ?>
                                        <button type="button" class="btn btn-outline-success btn-sm" 
                                                data-bs-toggle="modal" data-bs-target="#replyModal<?php echo $msg['id']; ?>">
                                            <i class="fas fa-reply"></i>
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if ($msg['status'] === 'pending'): ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                                            <button type="submit" name="action" value="mark_read" 
                                                    class="btn btn-outline-info btn-sm" 
                                                    title="Okundu İşaretle">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
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
                        <div class="modal fade" id="viewModal<?php echo $msg['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Mesaj Detayları - #<?php echo $msg['id']; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Gönderen</h6>
                                                <p><strong>Ad Soyad:</strong> <?php echo htmlspecialchars($msg['name']); ?></p>
                                                <p><strong>E-posta:</strong> <?php echo htmlspecialchars($msg['email']); ?></p>
                                                <p><strong>Telefon:</strong> <?php echo htmlspecialchars($msg['phone'] ?: '-'); ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Mesaj Bilgileri</h6>
                                                <p><strong>Konu:</strong> <?php echo htmlspecialchars($msg['subject']); ?></p>
                                                <p><strong>Durum:</strong> <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span></p>
                                                <p><strong>Tarih:</strong> <?php echo date('d.m.Y H:i', strtotime($msg['created_at'])); ?></p>
                                                <?php if ($msg['replied_at']): ?>
                                                    <p><strong>Cevap Tarihi:</strong> <?php echo date('d.m.Y H:i', strtotime($msg['replied_at'])); ?></p>
                                                    <p><strong>Cevaplayan:</strong> <?php echo htmlspecialchars($msg['replied_by_username'] ?: '-'); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        <h6>Mesaj</h6>
                                        <div class="bg-light p-3 rounded">
                                            <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                                        </div>
                                        
                                        <?php if ($msg['admin_reply']): ?>
                                            <hr>
                                            <h6>Admin Cevabı</h6>
                                            <div class="bg-success bg-opacity-10 p-3 rounded border-start border-success border-3">
                                                <?php echo nl2br(htmlspecialchars($msg['admin_reply'])); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <?php if ($msg['status'] !== 'replied'): ?>
                                            <button type="button" class="btn btn-success" 
                                                    data-bs-toggle="modal" data-bs-target="#replyModal<?php echo $msg['id']; ?>" 
                                                    data-bs-dismiss="modal">
                                                <i class="fas fa-reply me-2"></i>Cevapla
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reply Modal -->
                        <?php if ($msg['status'] !== 'replied'): ?>
                        <div class="modal fade" id="replyModal<?php echo $msg['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Mesaja Cevap Ver - #<?php echo $msg['id']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Alıcı:</strong></label>
                                                <p><?php echo htmlspecialchars($msg['name'] . ' (' . $msg['email'] . ')'); ?></p>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Konu:</strong></label>
                                                <p>Re: <?php echo htmlspecialchars($msg['subject']); ?></p>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Orijinal Mesaj:</strong></label>
                                                <div class="bg-light p-3 rounded small">
                                                    <?php echo nl2br(htmlspecialchars(substr($msg['message'], 0, 300)) . (strlen($msg['message']) > 300 ? '...' : '')); ?>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="reply_message" class="form-label">Cevabınız *</label>
                                                <textarea class="form-control" id="reply_message" name="reply_message" 
                                                          rows="8" required placeholder="Cevabınızı buraya yazın..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                                            <button type="submit" name="action" value="reply" class="btn btn-success">
                                                <i class="fas fa-paper-plane me-2"></i>Cevabı Gönder
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                <h5>Mesaj bulunamadı</h5>
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
                            <a class="page-link" href="?page=messages&page_num=<?php echo $page_num - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page_num - 2); $i <= min($total_pages, $page_num + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page_num ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=messages&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page_num < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=messages&page_num=<?php echo $page_num + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>
