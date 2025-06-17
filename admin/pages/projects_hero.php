<?php
require_once '../includes/AdminLogger.php';
require_once '../includes/SecurityManager.php';

// Admin oturum kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$logger = new AdminLogger($pdo);
$security = new SecurityManager($pdo);

// POST işlemleri
if ($_POST) {
    $security->validateCSRF($_POST['csrf_token'] ?? '');

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_hero_settings':
                try {
                    $stmt = $pdo->prepare("UPDATE projects_hero_settings SET 
                        hero_title = ?, 
                        hero_subtitle = ?, 
                        show_stats = ?, 
                        stats_title_1 = ?, 
                        stats_title_2 = ?, 
                        stats_title_3 = ?, 
                        stats_title_4 = ?,
                        custom_stat_1 = ?,
                        custom_stat_2 = ?,
                        custom_stat_3 = ?,
                        custom_stat_4 = ?,
                        use_custom_stats = ?
                        WHERE is_active = 1");
                    
                    $result = $stmt->execute([
                        $_POST['hero_title'],
                        $_POST['hero_subtitle'],
                        isset($_POST['show_stats']) ? 1 : 0,
                        $_POST['stats_title_1'],
                        $_POST['stats_title_2'],
                        $_POST['stats_title_3'],
                        $_POST['stats_title_4'],
                        !empty($_POST['custom_stat_1']) ? (int)$_POST['custom_stat_1'] : null,
                        !empty($_POST['custom_stat_2']) ? (int)$_POST['custom_stat_2'] : null,
                        !empty($_POST['custom_stat_3']) ? (int)$_POST['custom_stat_3'] : null,
                        !empty($_POST['custom_stat_4']) ? (int)$_POST['custom_stat_4'] : null,
                        isset($_POST['use_custom_stats']) ? 1 : 0
                    ]);

                    if ($result) {
                        $logger->log('UPDATE', 'projects_hero_settings', null, 'Hero ayarları güncellendi');
                        $success = "Hero ayarları başarıyla güncellendi.";
                    } else {
                        $error = "Hero ayarları güncellenirken hata oluştu.";
                    }
                } catch (PDOException $e) {
                    $error = "Veritabanı hatası: " . $e->getMessage();
                    $logger->log('ERROR', 'projects_hero_settings', null, 'Hero ayarları güncelleme hatası: ' . $e->getMessage());
                }
                break;
        }
    }
}

// Mevcut hero ayarlarını getir
try {
    $stmt = $pdo->prepare("SELECT * FROM projects_hero_settings WHERE is_active = 1 ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $heroSettings = $stmt->fetch();
    
    if (!$heroSettings) {
        // Varsayılan ayarları ekle
        $stmt = $pdo->prepare("INSERT INTO projects_hero_settings 
            (hero_title, hero_subtitle, show_stats, stats_title_1, stats_title_2, stats_title_3, stats_title_4, is_active) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            'Projelerimiz',
            'Toplumun farklı kesimlerine ulaşarak hayırlı işler yapıyor, birlikte daha güzel bir dünya inşa ediyoruz.',
            1,
            'Aktif Proje',
            'Tamamlanan',
            'Kişiye Ulaştık',
            'Toplam Proje',
            1
        ]);
        
        // Yeni eklenen ayarları getir
        $stmt = $pdo->prepare("SELECT * FROM projects_hero_settings WHERE is_active = 1 ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $heroSettings = $stmt->fetch();
    }
} catch (PDOException $e) {
    $error = "Veritabanı hatası: " . $e->getMessage();
}

// Proje istatistiklerini getir (preview için)
try {
    $stmt = $pdo->prepare("SELECT 
        COUNT(*) as total_projects,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count,
        SUM(CASE WHEN beneficiaries IS NOT NULL THEN beneficiaries ELSE 0 END) as total_beneficiaries
        FROM projects WHERE status IN ('active', 'completed', 'paused')");
    $stmt->execute();
    $stats = $stmt->fetch();
} catch (PDOException $e) {
    $stats = ['total_projects' => 0, 'active_count' => 0, 'completed_count' => 0, 'total_beneficiaries' => 0];
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Projeler Sayfası Hero Ayarları</h1>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Hero Bölümü Ayarları</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $security->generateCSRFToken() ?>">
                    <input type="hidden" name="action" value="update_hero_settings">
                    
                    <div class="mb-3">
                        <label for="hero_title" class="form-label">Ana Başlık</label>
                        <input type="text" class="form-control" id="hero_title" name="hero_title" 
                               value="<?= htmlspecialchars($heroSettings['hero_title'] ?? '') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="hero_subtitle" class="form-label">Alt Başlık</label>
                        <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="3" required><?= htmlspecialchars($heroSettings['hero_subtitle'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="show_stats" name="show_stats" 
                                   <?= ($heroSettings['show_stats'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="show_stats">
                                İstatistikleri Göster
                            </label>
                        </div>
                    </div>
                    
                    <hr>
                    <h6>İstatistik Başlıkları</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stats_title_1" class="form-label">İstatistik 1 Başlığı</label>
                            <input type="text" class="form-control" id="stats_title_1" name="stats_title_1" 
                                   value="<?= htmlspecialchars($heroSettings['stats_title_1'] ?? 'Aktif Proje') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stats_title_2" class="form-label">İstatistik 2 Başlığı</label>
                            <input type="text" class="form-control" id="stats_title_2" name="stats_title_2" 
                                   value="<?= htmlspecialchars($heroSettings['stats_title_2'] ?? 'Tamamlanan') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stats_title_3" class="form-label">İstatistik 3 Başlığı</label>
                            <input type="text" class="form-control" id="stats_title_3" name="stats_title_3" 
                                   value="<?= htmlspecialchars($heroSettings['stats_title_3'] ?? 'Kişiye Ulaştık') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stats_title_4" class="form-label">İstatistik 4 Başlığı</label>
                            <input type="text" class="form-control" id="stats_title_4" name="stats_title_4" 
                                   value="<?= htmlspecialchars($heroSettings['stats_title_4'] ?? 'Toplam Proje') ?>">
                        </div>
                    </div>
                    
                    <hr>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="use_custom_stats" name="use_custom_stats" 
                                   <?= ($heroSettings['use_custom_stats'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="use_custom_stats">
                                Özel İstatistik Değerleri Kullan (Otomatik hesaplamalar yerine)
                            </label>
                        </div>
                    </div>
                    
                    <div class="row" id="custom_stats_section" style="<?= ($heroSettings['use_custom_stats'] ?? 0) ? '' : 'display: none;' ?>">
                        <div class="col-md-6 mb-3">
                            <label for="custom_stat_1" class="form-label">Özel İstatistik 1 Değeri</label>
                            <input type="number" class="form-control" id="custom_stat_1" name="custom_stat_1" 
                                   value="<?= $heroSettings['custom_stat_1'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="custom_stat_2" class="form-label">Özel İstatistik 2 Değeri</label>
                            <input type="number" class="form-control" id="custom_stat_2" name="custom_stat_2" 
                                   value="<?= $heroSettings['custom_stat_2'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="custom_stat_3" class="form-label">Özel İstatistik 3 Değeri</label>
                            <input type="number" class="form-control" id="custom_stat_3" name="custom_stat_3" 
                                   value="<?= $heroSettings['custom_stat_3'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="custom_stat_4" class="form-label">Özel İstatistik 4 Değeri</label>
                            <input type="number" class="form-control" id="custom_stat_4" name="custom_stat_4" 
                                   value="<?= $heroSettings['custom_stat_4'] ?? '' ?>">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Ayarları Kaydet</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Mevcut İstatistikler</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border rounded p-2">
                            <h4 class="text-primary mb-1"><?= number_format($stats['active_count']) ?></h4>
                            <small class="text-muted">Aktif Proje</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-2">
                            <h4 class="text-success mb-1"><?= number_format($stats['completed_count']) ?></h4>
                            <small class="text-muted">Tamamlanan</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-2">
                            <h4 class="text-info mb-1"><?= number_format($stats['total_beneficiaries']) ?></h4>
                            <small class="text-muted">Kişiye Ulaştık</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-2">
                            <h4 class="text-warning mb-1"><?= number_format($stats['total_projects']) ?></h4>
                            <small class="text-muted">Toplam Proje</small>
                        </div>
                    </div>
                </div>
                
                <hr>
                <small class="text-muted">
                    Bu değerler otomatik olarak projeler tablosundan hesaplanmaktadır. 
                    Özel değerler kullanmak istiyorsanız yukarıdaki seçeneği işaretleyin.
                </small>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('use_custom_stats').addEventListener('change', function() {
    const customStatsSection = document.getElementById('custom_stats_section');
    if (this.checked) {
        customStatsSection.style.display = '';
    } else {
        customStatsSection.style.display = 'none';
    }
});
</script>
