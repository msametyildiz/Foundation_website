<?php
require_once '../config/database.php';

// Aktif projeleri getir
$stmt = $db->prepare("SELECT * FROM projects WHERE status = 'active' ORDER BY created_at DESC");
$stmt->execute();
$activeProjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tamamlanan projeleri getir
$stmt = $db->prepare("SELECT * FROM projects WHERE status = 'completed' ORDER BY created_at DESC");
$stmt->execute();
$completedProjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Proje sayıları için istatistikler
$stmt = $db->prepare("SELECT 
    COUNT(*) as total_projects,
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count,
    SUM(target_amount) as total_target,
    SUM(current_amount) as total_raised
    FROM projects");
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-3">Projelerimiz</h1>
                <p class="lead mb-0">İnsani yardım alanında gerçekleştirdiğimiz ve devam eden projelerimizi keşfedin.</p>
            </div>
            <div class="col-lg-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent justify-content-lg-end">
                        <li class="breadcrumb-item"><a href="/" class="text-white">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Projeler</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Proje İstatistikleri -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-project-diagram fa-3x text-primary"></i>
                    </div>
                    <h3 class="stats-number" data-target="<?= $stats['total_projects'] ?? 0 ?>">0</h3>
                    <p class="stats-label mb-0">Toplam Proje</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-play-circle fa-3x text-success"></i>
                    </div>
                    <h3 class="stats-number" data-target="<?= $stats['active_count'] ?? 0 ?>">0</h3>
                    <p class="stats-label mb-0">Aktif Proje</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-check-circle fa-3x text-info"></i>
                    </div>
                    <h3 class="stats-number" data-target="<?= $stats['completed_count'] ?? 0 ?>">0</h3>
                    <p class="stats-label mb-0">Tamamlanan Proje</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-hand-holding-heart fa-3x text-warning"></i>
                    </div>
                    <h3 class="stats-number" data-target="<?= number_format($stats['total_raised'] ?? 0, 0, ',', '.') ?>">0</h3>
                    <p class="stats-label mb-0">Toplanan Bağış (₺)</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Aktif Projeler -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Aktif Projeler</h2>
                <p class="section-subtitle">Şu anda devam eden ve desteğinize ihtiyaç duyan projelerimiz</p>
            </div>
        </div>

        <?php if (!empty($activeProjects)): ?>
            <div class="row g-4">
                <?php foreach ($activeProjects as $project): 
                    $progress = $project['target_amount'] > 0 ? 
                        ($project['current_amount'] / $project['target_amount']) * 100 : 0;
                    $progress = min($progress, 100);
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="project-card h-100">
                            <div class="project-image">
                                <img src="<?= !empty($project['image']) ? '../uploads/projects/' . $project['image'] : '../assets/images/default-project.jpg' ?>" 
                                     alt="<?= htmlspecialchars($project['title']) ?>" class="img-fluid">
                                <div class="project-status">
                                    <span class="badge bg-success">Aktif</span>
                                </div>
                            </div>
                            <div class="project-content p-4">
                                <h5 class="project-title mb-3"><?= htmlspecialchars($project['title']) ?></h5>
                                <p class="project-description text-muted"><?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...</p>
                                
                                <div class="project-progress mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">İlerleme</span>
                                        <span class="fw-bold"><?= number_format($progress, 1) ?>%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" style="width: <?= $progress ?>%"></div>
                                    </div>
                                </div>

                                <div class="project-meta">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="meta-item">
                                                <strong class="d-block text-primary"><?= number_format($project['current_amount'], 0, ',', '.') ?> ₺</strong>
                                                <small class="text-muted">Toplanan</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="meta-item">
                                                <strong class="d-block text-dark"><?= number_format($project['target_amount'], 0, ',', '.') ?> ₺</strong>
                                                <small class="text-muted">Hedef</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="project-actions mt-4">
                                    <a href="/donate" class="btn btn-primary btn-sm me-2">
                                        <i class="fas fa-heart me-1"></i> Bağış Yap
                                    </a>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="showProjectDetails(<?= $project['id'] ?>)">
                                        <i class="fas fa-info-circle me-1"></i> Detaylar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-project-diagram fa-5x text-muted mb-4"></i>
                <h4 class="text-muted">Henüz aktif proje bulunmuyor</h4>
                <p class="text-muted">Yakında yeni projelerimizi duyuracağız.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Tamamlanan Projeler -->
<?php if (!empty($completedProjects)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Tamamlanan Projeler</h2>
                <p class="section-subtitle">Başarıyla tamamladığımız ve fark yarattığımız projelerimiz</p>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($completedProjects as $project): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="project-card h-100 completed">
                        <div class="project-image">
                            <img src="<?= !empty($project['image']) ? '../uploads/projects/' . $project['image'] : '../assets/images/default-project.jpg' ?>" 
                                 alt="<?= htmlspecialchars($project['title']) ?>" class="img-fluid">
                            <div class="project-status">
                                <span class="badge bg-success">Tamamlandı</span>
                            </div>
                        </div>
                        <div class="project-content p-4">
                            <h5 class="project-title mb-3"><?= htmlspecialchars($project['title']) ?></h5>
                            <p class="project-description text-muted"><?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...</p>
                            
                            <div class="project-meta">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="meta-item">
                                            <strong class="d-block text-success"><?= number_format($project['current_amount'], 0, ',', '.') ?> ₺</strong>
                                            <small class="text-muted">Toplanan</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="meta-item">
                                            <strong class="d-block text-dark"><?= date('Y', strtotime($project['end_date'])) ?></strong>
                                            <small class="text-muted">Tamamlanma</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="project-actions mt-4">
                                <button class="btn btn-outline-primary btn-sm" onclick="showProjectDetails(<?= $project['id'] ?>)">
                                    <i class="fas fa-eye me-1"></i> Detayları Gör
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Proje Detay Modal -->
<div class="modal fade" id="projectDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proje Detayları</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="projectDetailContent">
                <!-- AJAX ile yüklenecek -->
            </div>
        </div>
    </div>
</div>

<script>
function showProjectDetails(projectId) {
    fetch('/ajax/get_project_details.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?= generateCSRFToken() ?>'
        },
        body: JSON.stringify({project_id: projectId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('projectDetailContent').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('projectDetailModal')).show();
        } else {
            alert('Proje detayları yüklenirken bir hata oluştu.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu.');
    });
}
</script>
