<?php
// Users management page for admin panel
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? 0;
    
    try {
        if ($action === 'update_status') {
            $status = $_POST['status'] ?? '';
            $stmt = $pdo->prepare("UPDATE admin_users SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $success = "Kullanıcı durumu güncellendi.";
        }
        
        if ($action === 'update_role') {
            $role = $_POST['role'] ?? '';
            $stmt = $pdo->prepare("UPDATE admin_users SET role = ? WHERE id = ?");
            $stmt->execute([$role, $id]);
            $success = "Kullanıcı rolü güncellendi.";
        }
        
        if ($action === 'delete') {
            // Prevent deleting own account
            if ($id == $_SESSION['admin_id']) {
                $error = "Kendi hesabınızı silemezsiniz.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM admin_users WHERE id = ?");
                $stmt->execute([$id]);
                $success = "Kullanıcı silindi.";
            }
        }
        
        if ($action === 'reset_password') {
            $new_password = $_POST['new_password'] ?? '';
            if (strlen($new_password) >= 6) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $id]);
                $success = "Şifre sıfırlandı.";
            } else {
                $error = "Şifre en az 6 karakter olmalıdır.";
            }
        }
        
        if ($action === 'add') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'editor';
            
            // Check if username exists
            $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = "Bu kullanıcı adı zaten kullanılıyor.";
            } 
            // Check if email exists
            else {
                $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = "Bu e-posta adresi zaten kullanılıyor.";
                } 
                // Add user
                else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO admin_users (username, email, first_name, last_name, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())");
                    $stmt->execute([$username, $email, $first_name, $last_name, $hashed_password, $role]);
                    $success = "Yeni kullanıcı eklendi.";
                }
            }
        }
        
        if ($action === 'edit') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $role = $_POST['role'] ?? 'editor';
            
            // Check if username exists (except current user)
            $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = ? AND id != ?");
            $stmt->execute([$username, $id]);
            if ($stmt->fetch()) {
                $error = "Bu kullanıcı adı zaten kullanılıyor.";
            } 
            // Check if email exists (except current user)
            else {
                $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE email = ? AND id != ?");
                $stmt->execute([$email, $id]);
                if ($stmt->fetch()) {
                    $error = "Bu e-posta adresi zaten kullanılıyor.";
                } 
                // Update user
                else {
                    $stmt = $pdo->prepare("UPDATE admin_users SET username = ?, email = ?, first_name = ?, last_name = ?, role = ?, updated_at = NOW() WHERE id = ?");
                    $stmt->execute([$username, $email, $first_name, $last_name, $role, $id]);
                    $success = "Kullanıcı bilgileri güncellendi.";
                }
            }
        }
        
    } catch (PDOException $e) {
        $error = "Veritabanı hatası: " . $e->getMessage();
    }
}

// Get filters
$search = $_GET['search'] ?? '';
$role = $_GET['role'] ?? '';
$status = $_GET['status'] ?? '';
$page_num = max(1, $_GET['page_num'] ?? 1);
$per_page = 15;
$offset = ($page_num - 1) * $per_page;

// Build query
$where_conditions = [];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "(username LIKE ? OR email LIKE ? OR CONCAT(first_name, ' ', last_name) LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if (!empty($role)) {
    $where_conditions[] = "role = ?";
    $params[] = $role;
}

if (!empty($status)) {
    $where_conditions[] = "status = ?";
    $params[] = $status;
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count
$count_sql = "SELECT COUNT(*) FROM admin_users {$where_clause}";
$stmt = $pdo->prepare($count_sql);
$stmt->execute($params);
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $per_page);

// Get users
$sql = "SELECT * FROM admin_users {$where_clause} ORDER BY created_at DESC LIMIT {$per_page} OFFSET {$offset}";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user statistics
$stats_sql = "SELECT 
    COUNT(*) as total_users,
    SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admin_count,
    SUM(CASE WHEN role = 'editor' THEN 1 ELSE 0 END) as editor_count,
    SUM(CASE WHEN role = 'moderator' THEN 1 ELSE 0 END) as moderator_count,
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,
    SUM(CASE WHEN last_login IS NOT NULL AND last_login > DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as recent_login_count
    FROM admin_users";
$stmt = $pdo->query($stats_sql);
$user_stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-user-cog me-2"></i>Kullanıcı Yönetimi</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus me-2"></i>Yeni Kullanıcı Ekle
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
                <i class="fas fa-users"></i>
            </div>
            <h3><?php echo number_format($user_stats['total_users'] ?? 0); ?></h3>
            <p class="text-muted">Toplam Kullanıcı</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
            <h3><?php echo number_format($user_stats['active_count'] ?? 0); ?></h3>
            <p class="text-muted">Aktif</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="icon">
                <i class="fas fa-crown"></i>
            </div>
            <h3><?php echo number_format($user_stats['admin_count'] ?? 0); ?></h3>
            <p class="text-muted">Admin</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <h3><?php echo number_format($user_stats['recent_login_count'] ?? 0); ?></h3>
            <p class="text-muted">Son 30 Gün Aktif</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="page" value="users">
            
            <div class="col-md-4">
                <label class="form-label">Arama</label>
                <input type="text" name="search" class="form-control" value="<?php echo htmlspecialchars($search); ?>" placeholder="Kullanıcı ara...">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Rol</label>
                <select name="role" class="form-select">
                    <option value="">Tümü</option>
                    <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="editor" <?php echo $role === 'editor' ? 'selected' : ''; ?>>Editör</option>
                    <option value="moderator" <?php echo $role === 'moderator' ? 'selected' : ''; ?>>Moderatör</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Durum</label>
                <select name="status" class="form-select">
                    <option value="">Tümü</option>
                    <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Pasif</option>
                    <option value="suspended" <?php echo $status === 'suspended' ? 'selected' : ''; ?>>Askıya Alındı</option>
                </select>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrele
                    </button>
                    <a href="?page=users" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Temizle
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Kullanıcılar 
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
                        <th>Kullanıcı</th>
                        <th>E-posta</th>
                        <th>Rol</th>
                        <th>Durum</th>
                        <th>Son Giriş</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                                            <small class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($user['email']); ?>
                                    </a>
                                </td>
                                <td>
                                    <?php
                                    $roleClass = [
                                        'admin' => 'danger',
                                        'editor' => 'primary',
                                        'moderator' => 'warning'
                                    ];
                                    $roleText = [
                                        'admin' => 'Admin',
                                        'editor' => 'Editör',
                                        'moderator' => 'Moderatör'
                                    ];
                                    ?>
                                    <span class="badge bg-<?php echo $roleClass[$user['role']] ?? 'secondary'; ?>">
                                        <?php echo $roleText[$user['role']] ?? $user['role']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'active' => 'success',
                                        'inactive' => 'secondary',
                                        'suspended' => 'danger'
                                    ];
                                    $statusText = [
                                        'active' => 'Aktif',
                                        'inactive' => 'Pasif',
                                        'suspended' => 'Askıda'
                                    ];
                                    ?>
                                    <span class="badge bg-<?php echo $statusClass[$user['status']] ?? 'secondary'; ?>">
                                        <?php echo $statusText[$user['status']] ?? $user['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user['last_login']): ?>
                                        <small title="<?php echo date('d.m.Y H:i:s', strtotime($user['last_login'])); ?>">
                                            <?php echo timeAgo($user['last_login']); ?>
                                        </small>
                                    <?php else: ?>
                                        <small class="text-muted">Hiç giriş yapmadı</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo date('d.m.Y', strtotime($user['created_at'])); ?></small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-outline-primary btn-sm" 
                                                onclick="editUser(<?php echo $user['id']; ?>)" 
                                                title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <button class="btn btn-outline-warning btn-sm" 
                                                onclick="resetPassword(<?php echo $user['id']; ?>)" 
                                                title="Şifre Sıfırla">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                    data-bs-toggle="dropdown" title="Durum">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                        <input type="hidden" name="status" value="active">
                                                        <button type="submit" name="action" value="update_status" class="dropdown-item">
                                                            <i class="fas fa-check text-success me-2"></i>Aktif
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                        <input type="hidden" name="status" value="inactive">
                                                        <button type="submit" name="action" value="update_status" class="dropdown-item">
                                                            <i class="fas fa-pause text-secondary me-2"></i>Pasif
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                        <input type="hidden" name="status" value="suspended">
                                                        <button type="submit" name="action" value="update_status" class="dropdown-item">
                                                            <i class="fas fa-ban text-danger me-2"></i>Askıya Al
                                                        </button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li class="dropdown-header">Roller</li>
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                        <input type="hidden" name="role" value="admin">
                                                        <button type="submit" name="action" value="update_role" class="dropdown-item">
                                                            <i class="fas fa-crown text-danger me-2"></i>Admin
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                        <input type="hidden" name="role" value="editor">
                                                        <button type="submit" name="action" value="update_role" class="dropdown-item">
                                                            <i class="fas fa-edit text-primary me-2"></i>Editör
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                        <input type="hidden" name="role" value="moderator">
                                                        <button type="submit" name="action" value="update_role" class="dropdown-item">
                                                            <i class="fas fa-shield text-warning me-2"></i>Moderatör
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <?php if ($user['id'] != $_SESSION['admin_id']): ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" name="action" value="delete" 
                                                        class="btn btn-outline-danger btn-sm btn-delete" 
                                                        title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                Henüz kullanıcı bulunmuyor
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
                            <a class="page-link" href="?page=users&page_num=<?php echo $page_num - 1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo urlencode($role); ?>&status=<?php echo urlencode($status); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page_num - 2); $i <= min($total_pages, $page_num + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page_num ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=users&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo urlencode($role); ?>&status=<?php echo urlencode($status); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page_num < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=users&page_num=<?php echo $page_num + 1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo urlencode($role); ?>&status=<?php echo urlencode($status); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yeni Kullanıcı Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ad</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Soyad</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Kullanıcı Adı</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Şifre</label>
                            <input type="password" name="password" class="form-control" minlength="6" required>
                            <small class="form-text text-muted">En az 6 karakter</small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Rol</label>
                            <select name="role" class="form-select" required>
                                <option value="editor">Editör</option>
                                <option value="moderator">Moderatör</option>
                                <option value="admin">Admin</option>
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

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kullanıcı Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editUserForm">
                <input type="hidden" name="id" id="editUserId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ad</label>
                            <input type="text" name="first_name" id="editUserFirstName" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Soyad</label>
                            <input type="text" name="last_name" id="editUserLastName" class="form-control" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Kullanıcı Adı</label>
                            <input type="text" name="username" id="editUserUsername" class="form-control" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="email" id="editUserEmail" class="form-control" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Rol</label>
                            <select name="role" id="editUserRole" class="form-select" required>
                                <option value="editor">Editör</option>
                                <option value="moderator">Moderatör</option>
                                <option value="admin">Admin</option>
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

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Şifre Sıfırla</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="resetPasswordForm">
                <input type="hidden" name="id" id="resetPasswordUserId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Yeni Şifre</label>
                        <input type="password" name="new_password" class="form-control" minlength="6" required>
                        <small class="form-text text-muted">En az 6 karakter</small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Bu kullanıcının şifresi sıfırlanacak ve yeni şifre ile giriş yapması gerekecek.
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" name="action" value="reset_password" class="btn btn-warning">
                        <i class="fas fa-key me-2"></i>Şifreyi Sıfırla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUser(id) {
    // AJAX call to get user details
    fetch(`../ajax/get_user.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('editUserId').value = data.user.id;
                document.getElementById('editUserFirstName').value = data.user.first_name;
                document.getElementById('editUserLastName').value = data.user.last_name;
                document.getElementById('editUserUsername').value = data.user.username;
                document.getElementById('editUserEmail').value = data.user.email;
                document.getElementById('editUserRole').value = data.user.role;
                
                new bootstrap.Modal(document.getElementById('editUserModal')).show();
            } else {
                alert('Kullanıcı bilgileri yüklenirken hata oluştu.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu.');
        });
}

function resetPassword(id) {
    document.getElementById('resetPasswordUserId').value = id;
    new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
}

function exportData(format) {
    const currentParams = new URLSearchParams(window.location.search);
    currentParams.set('export', format);
    window.open(`export_users.php?${currentParams.toString()}`, '_blank');
}

// Time ago function
function timeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'Az önce';
    if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' dk önce';
    if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' sa önce';
    if (diffInSeconds < 2592000) return Math.floor(diffInSeconds / 86400) + ' gün önce';
    return Math.floor(diffInSeconds / 2592000) + ' ay önce';
}

<?php
// PHP timeAgo function for server-side use
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'Az önce';
    if ($time < 3600) return floor($time / 60) . ' dk önce';
    if ($time < 86400) return floor($time / 3600) . ' sa önce';
    if ($time < 2592000) return floor($time / 86400) . ' gün önce';
    return floor($time / 2592000) . ' ay önce';
}
?>
</script>
