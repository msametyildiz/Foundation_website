<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/AdminLogger.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$page_title = 'Admin Logları';
include '../includes/admin_header.php';

$logger = new AdminLogger($pdo);

// Handle actions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'clean_logs') {
        $days = intval($_POST['days'] ?? 90);
        $deletedCount = $logger->cleanOldLogs($days);
        $message = "{$deletedCount} eski log kaydı silindi.";
        $messageType = 'success';
        
        // Log this action
        $logger->logSystemAction("Cleaned {$deletedCount} old log entries older than {$days} days");
    }
}

// Get filters
$filters = [
    'user_id' => $_GET['user_id'] ?? '',
    'action' => $_GET['action'] ?? '',
    'entity_type' => $_GET['entity_type'] ?? '',
    'date_from' => $_GET['date_from'] ?? '',
    'date_to' => $_GET['date_to'] ?? ''
];

// Pagination
$page = intval($_GET['p'] ?? 1);
$limit = 50;
$offset = ($page - 1) * $limit;

// Get logs
$logs = $logger->getLogs($limit, $offset, $filters);

// Get total count for pagination
$countQuery = "SELECT COUNT(*) FROM admin_logs al WHERE 1=1";
$countParams = [];

if (!empty($filters['user_id'])) {
    $countQuery .= " AND al.user_id = ?";
    $countParams[] = $filters['user_id'];
}

if (!empty($filters['action'])) {
    $countQuery .= " AND al.action = ?";
    $countParams[] = $filters['action'];
}

if (!empty($filters['entity_type'])) {
    $countQuery .= " AND al.entity_type = ?";
    $countParams[] = $filters['entity_type'];
}

if (!empty($filters['date_from'])) {
    $countQuery .= " AND DATE(al.created_at) >= ?";
    $countParams[] = $filters['date_from'];
}

if (!empty($filters['date_to'])) {
    $countQuery .= " AND DATE(al.created_at) <= ?";
    $countParams[] = $filters['date_to'];
}

$stmt = $pdo->prepare($countQuery);
$stmt->execute($countParams);
$totalRecords = $stmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Get users for filter
$users = $pdo->query("SELECT id, username, full_name FROM users ORDER BY username")->fetchAll(PDO::FETCH_ASSOC);

// Get action types
$actions = $pdo->query("SELECT DISTINCT action FROM admin_logs ORDER BY action")->fetchAll(PDO::FETCH_COLUMN);

// Get entity types
$entityTypes = $pdo->query("SELECT DISTINCT entity_type FROM admin_logs WHERE entity_type IS NOT NULL ORDER BY entity_type")->fetchAll(PDO::FETCH_COLUMN);

// Get stats for last 30 days
$stats = $logger->getLogStats(30);
?>

<div class="admin-content">
    <div class="content-header">
        <h1><i class="fas fa-clipboard-list"></i> Admin Logları</h1>
        <div class="header-actions">
            <button type="button" class="btn btn-danger" onclick="showCleanLogsModal()">
                <i class="fas fa-trash-alt"></i> Eski Logları Temizle
            </button>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-primary">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stats-content">
                    <h3><?php echo number_format($totalRecords); ?></h3>
                    <p>Toplam Log</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-success">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-content">
                    <?php
                    $todayLogs = $pdo->query("SELECT COUNT(*) FROM admin_logs WHERE DATE(created_at) = CURDATE()")->fetchColumn();
                    ?>
                    <h3><?php echo number_format($todayLogs); ?></h3>
                    <p>Bugünkü Loglar</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-content">
                    <?php
                    $activeUsers = $pdo->query("SELECT COUNT(DISTINCT user_id) FROM admin_logs WHERE DATE(created_at) = CURDATE() AND user_id IS NOT NULL")->fetchColumn();
                    ?>
                    <h3><?php echo number_format($activeUsers); ?></h3>
                    <p>Aktif Kullanıcı</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-danger">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="stats-content">
                    <?php
                    $securityEvents = $pdo->query("SELECT COUNT(*) FROM admin_logs WHERE action = 'security' AND DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetchColumn();
                    ?>
                    <h3><?php echo number_format($securityEvents); ?></h3>
                    <p>Güvenlik Olayı (7 gün)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-filter"></i> Filtreler</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="page" value="logs">
                
                <div class="col-md-3">
                    <label for="user_id" class="form-label">Kullanıcı</label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">Tüm Kullanıcılar</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user['id']; ?>" <?php echo $filters['user_id'] == $user['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($user['username'] . ' (' . $user['full_name'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="action" class="form-label">İşlem</label>
                    <select name="action" id="action" class="form-select">
                        <option value="">Tüm İşlemler</option>
                        <?php foreach ($actions as $action): ?>
                            <option value="<?php echo $action; ?>" <?php echo $filters['action'] == $action ? 'selected' : ''; ?>>
                                <?php echo ucfirst($action); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="entity_type" class="form-label">Varlık Tipi</label>
                    <select name="entity_type" id="entity_type" class="form-select">
                        <option value="">Tüm Tipler</option>
                        <?php foreach ($entityTypes as $type): ?>
                            <option value="<?php echo $type; ?>" <?php echo $filters['entity_type'] == $type ? 'selected' : ''; ?>>
                                <?php echo ucfirst($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="date_from" class="form-label">Başlangıç</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo $filters['date_from']; ?>">
                </div>
                
                <div class="col-md-2">
                    <label for="date_to" class="form-label">Bitiş</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="<?php echo $filters['date_to']; ?>">
                </div>
                
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-list"></i> Log Kayıtları</h5>
            <span class="text-muted"><?php echo number_format($totalRecords); ?> kayıt</span>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($logs)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tarih/Saat</th>
                                <th>Kullanıcı</th>
                                <th>İşlem</th>
                                <th>Açıklama</th>
                                <th>Varlık</th>
                                <th>IP Adresi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('d.m.Y H:i:s', strtotime($log['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($log['username']): ?>
                                            <strong><?php echo htmlspecialchars($log['username']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($log['full_name']); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">System</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $actionBadges = [
                                            'login' => 'success',
                                            'logout' => 'secondary',
                                            'create' => 'primary',
                                            'update' => 'warning',
                                            'delete' => 'danger',
                                            'view' => 'info',
                                            'export' => 'dark',
                                            'system' => 'secondary',
                                            'security' => 'danger'
                                        ];
                                        $badgeClass = $actionBadges[$log['action']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $badgeClass; ?>">
                                            <?php echo ucfirst($log['action']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span title="<?php echo htmlspecialchars($log['description']); ?>">
                                            <?php echo htmlspecialchars(substr($log['description'], 0, 60) . (strlen($log['description']) > 60 ? '...' : '')); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($log['entity_type']): ?>
                                            <small class="text-muted">
                                                <?php echo ucfirst($log['entity_type']); ?>
                                                <?php if ($log['entity_id']): ?>
                                                    #<?php echo $log['entity_id']; ?>
                                                <?php endif; ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars($log['ip_address']); ?>
                                        </small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Kayıt bulunamadı</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Log pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=logs&p=<?php echo $page - 1; ?>&<?php echo http_build_query($filters); ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                <?php endif; ?>

                <?php
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                
                if ($start > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=logs&p=1&<?php echo http_build_query($filters); ?>">1</a>
                    </li>
                    <?php if ($start > 2): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=logs&p=<?php echo $i; ?>&<?php echo http_build_query($filters); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($end < $totalPages): ?>
                    <?php if ($end < $totalPages - 1): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=logs&p=<?php echo $totalPages; ?>&<?php echo http_build_query($filters); ?>">
                            <?php echo $totalPages; ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=logs&p=<?php echo $page + 1; ?>&<?php echo http_build_query($filters); ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Clean Logs Modal -->
<div class="modal fade" id="cleanLogsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eski Logları Temizle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Bu işlem geri alınamaz. Belirtilen günden eski tüm log kayıtları silinecektir.
                    </div>
                    <div class="mb-3">
                        <label for="cleanDays" class="form-label">Kaç günden eski kayıtlar silinsin?</label>
                        <select name="days" id="cleanDays" class="form-select">
                            <option value="30">30 gün</option>
                            <option value="60">60 gün</option>
                            <option value="90" selected>90 gün</option>
                            <option value="180">180 gün</option>
                            <option value="365">1 yıl</option>
                        </select>
                    </div>
                    <input type="hidden" name="action" value="clean_logs">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-danger">Temizle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showCleanLogsModal() {
    new bootstrap.Modal(document.getElementById('cleanLogsModal')).show();
}
</script>

<?php include '../includes/admin_footer.php'; ?>
