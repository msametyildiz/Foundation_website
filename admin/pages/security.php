<?php
require_once '../includes/functions.php';
require_once '../includes/SecurityManager.php';
require_once '../includes/PerformanceOptimizer.php';
require_once '../includes/AdminLogger.php';

secure_session_start();
require_admin_login();

$pdo = get_db_connection();
$logger = new AdminLogger($pdo);
$security_scanner = new SecurityScanner();
$performance_optimizer = new PerformanceOptimizer();

// Güvenlik taraması yap
$scan_action = $_GET['action'] ?? '';
$scan_results = null;

if ($scan_action === 'security_scan') {
    $scan_results = $security_scanner->runScan();
    $logger->log($_SESSION['admin_id'], 'security_scan', 'Güvenlik taraması yapıldı', 'system');
}

// Cache temizleme
if ($scan_action === 'clear_cache') {
    $performance_optimizer->clear();
    $logger->log($_SESSION['admin_id'], 'cache_clear', 'Cache temizlendi', 'system');
    redirect_with_message('?page=security', 'Cache başarıyla temizlendi', 'success');
}

// Cache istatistikleri
$cache_stats = $performance_optimizer->getStats();

// Performans istatistikleri
PerformanceMonitor::start();

include '../includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">🔒 Güvenlik ve Performans</h1>
                <div class="btn-group">
                    <button id="runScanBtn" class="btn btn-warning btn-sm">
                        <i class="fas fa-shield-alt"></i> Güvenlik Taraması
                    </button>
                    <button id="clearCacheBtn" class="btn btn-info btn-sm">
                        <i class="fas fa-broom"></i> Cache Temizle
                    </button>
                    <button id="backupBtn" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Veritabanı Yedeği
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Güvenlik Durumu -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Güvenlik Durumu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php if ($scan_results): ?>
                                    <?php if ($scan_results['status'] === 'clean'): ?>
                                        <span class="text-success">Temiz</span>
                                    <?php else: ?>
                                        <span class="text-warning"><?= $scan_results['threats_found'] ?> Uyarı</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    Tarama Yapılmadı
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Cache Durumu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $cache_stats['active_files'] ?> Aktif
                            </div>
                            <div class="text-xs text-muted">
                                <?= $cache_stats['total_size_mb'] ?> MB
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                PHP Versiyonu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= PHP_VERSION ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fab fa-php fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Bellek Kullanımı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= round(memory_get_usage() / 1024 / 1024, 1) ?> MB
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-memory fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($scan_results): ?>
    <!-- Güvenlik Tarama Sonuçları -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🔍 Güvenlik Tarama Sonuçları</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-<?= $scan_results['status'] === 'clean' ? 'success' : 'warning' ?>">
                        <strong>Tarama Tamamlandı:</strong> 
                        <?php if ($scan_results['status'] === 'clean'): ?>
                            Herhangi bir güvenlik tehdidi bulunamadı.
                        <?php else: ?>
                            <?= $scan_results['threats_found'] ?> güvenlik uyarısı bulundu.
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($scan_results['results'])): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Önem Derecesi</th>
                                    <th>Kategori</th>
                                    <th>Mesaj</th>
                                    <th>Zaman</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($scan_results['results'] as $result): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-<?php
                                            echo $result['severity'] === 'high' ? 'danger' : 
                                                ($result['severity'] === 'medium' ? 'warning' : 
                                                ($result['severity'] === 'low' ? 'info' : 'secondary'));
                                        ?>">
                                            <?= ucfirst($result['severity']) ?>
                                        </span>
                                    </td>
                                    <td><?= clean_output($result['category']) ?></td>
                                    <td><?= clean_output($result['message']) ?></td>
                                    <td><?= $result['timestamp'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Sistem Bilgileri -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🖥️ Sistem Bilgileri</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Sunucu Yazılımı:</strong></td>
                            <td><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Bilinmiyor' ?></td>
                        </tr>
                        <tr>
                            <td><strong>PHP Versiyonu:</strong></td>
                            <td><?= PHP_VERSION ?></td>
                        </tr>
                        <tr>
                            <td><strong>Bellek Limiti:</strong></td>
                            <td><?= ini_get('memory_limit') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Maks. Dosya Boyutu:</strong></td>
                            <td><?= ini_get('upload_max_filesize') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Zaman Dilimi:</strong></td>
                            <td><?= date_default_timezone_get() ?></td>
                        </tr>
                        <tr>
                            <td><strong>Çalışma Zamanı:</strong></td>
                            <td><?= date('Y-m-d H:i:s') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📊 Cache İstatistikleri</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Toplam Dosya:</strong></td>
                            <td><?= $cache_stats['total_files'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Aktif Dosya:</strong></td>
                            <td class="text-success"><?= $cache_stats['active_files'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Süresi Geçmiş:</strong></td>
                            <td class="text-warning"><?= $cache_stats['expired_files'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Toplam Boyut:</strong></td>
                            <td><?= $cache_stats['total_size_mb'] ?> MB</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="progress mt-2">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: <?= $cache_stats['total_files'] > 0 ? ($cache_stats['active_files'] / $cache_stats['total_files'] * 100) : 0 ?>%">
                                    </div>
                                </div>
                                <small class="text-muted">Cache Verimliliği</small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- PHP Eklentileri -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🔧 PHP Eklentileri</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $required_extensions = [
                            'pdo', 'pdo_mysql', 'curl', 'mbstring', 'xml', 'zip', 
                            'gd', 'openssl', 'json', 'fileinfo'
                        ];
                        foreach ($required_extensions as $ext):
                        ?>
                        <div class="col-md-3 mb-2">
                            <span class="badge badge-<?= extension_loaded($ext) ? 'success' : 'danger' ?> mr-1">
                                <?= $ext ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let isRefreshing = false;

// Auto-refresh security status every 30 seconds
function refreshSecurityStatus() {
    if (isRefreshing) return;
    isRefreshing = true;
    
    fetch('../ajax/security.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_security_status'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateSecurityStatus(data.status);
        }
    })
    .catch(error => {
        console.error('Error refreshing security status:', error);
    })
    .finally(() => {
        isRefreshing = false;
    });
}

function updateSecurityStatus(status) {
    // Update PHP version display
    const phpVersionElement = document.querySelector('[data-metric="php_version"]');
    if (phpVersionElement) {
        phpVersionElement.textContent = status.php_version;
    }
    
    // Update SSL status
    const sslElement = document.querySelector('[data-metric="ssl_status"]');
    if (sslElement) {
        sslElement.innerHTML = status.ssl_enabled ? 
            '<i class="fas fa-check text-success"></i> Aktif' : 
            '<i class="fas fa-times text-danger"></i> Pasif';
    }
}

// Run security scan
function runSecurityScan() {
    const scanButton = document.getElementById('runScanBtn');
    const originalText = scanButton.innerHTML;
    
    scanButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Taranıyor...';
    scanButton.disabled = true;
    
    fetch('../ajax/security.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=run_security_scan'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload page to show new scan results
            location.reload();
        } else {
            alert('Tarama sırasında hata oluştu: ' + (data.message || 'Bilinmeyen hata'));
        }
    })
    .catch(error => {
        console.error('Security scan error:', error);
        alert('Tarama sırasında hata oluştu');
    })
    .finally(() => {
        scanButton.innerHTML = originalText;
        scanButton.disabled = false;
    });
}

// Clear cache
function clearCache() {
    if (!confirm('Önbellek temizlensin mi?')) return;
    
    fetch('../ajax/security.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=clear_cache'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Önbellek başarıyla temizlendi');
        } else {
            alert('Önbellek temizleme başarısız: ' + (data.message || 'Bilinmeyen hata'));
        }
    })
    .catch(error => {
        console.error('Cache clear error:', error);
        alert('Önbellek temizleme sırasında hata oluştu');
    });
}

// Backup database
function backupDatabase() {
    if (!confirm('Veritabanı yedeği oluşturulsun mu?')) return;
    
    const backupButton = document.getElementById('backupBtn');
    const originalText = backupButton.innerHTML;
    
    backupButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Yedekleniyor...';
    backupButton.disabled = true;
    
    fetch('../ajax/security.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=backup_database'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Veritabanı yedeği oluşturuldu: ' + data.backup_file);
        } else {
            alert('Yedekleme başarısız: ' + (data.message || 'Bilinmeyen hata'));
        }
    })
    .catch(error => {
        console.error('Backup error:', error);
        alert('Yedekleme sırasında hata oluştu');
    })
    .finally(() => {
        backupButton.innerHTML = originalText;
        backupButton.disabled = false;
    });
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Security & Performance page loaded');
    
    // Set up auto-refresh
    setInterval(refreshSecurityStatus, 30000);
    
    // Initial status check
    refreshSecurityStatus();
    
    // Add event listeners to buttons
    const runScanBtn = document.getElementById('runScanBtn');
    if (runScanBtn) {
        runScanBtn.addEventListener('click', runSecurityScan);
    }
    
    const clearCacheBtn = document.getElementById('clearCacheBtn');
    if (clearCacheBtn) {
        clearCacheBtn.addEventListener('click', clearCache);
    }
    
    const backupBtn = document.getElementById('backupBtn');
    if (backupBtn) {
        backupBtn.addEventListener('click', backupDatabase);
    }
});
</script>

<?php include '../includes/admin_footer.php'; ?>
