<?php
// Content catalog'u dahil et
require_once 'includes/content_catalog.php';

// Veritabanından projeler ve kategoriler
try {
    // Aktif projeler
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC");
    $stmt->execute();
    $activeProjects = $stmt->fetchAll();

    // Tamamlanan projeler
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE status = 'completed' ORDER BY end_date DESC LIMIT 6");
    $stmt->execute();
    $completedProjects = $stmt->fetchAll();

    // Toplam istatistikler
    $stmt = $pdo->prepare("SELECT 
        COUNT(*) as total_projects,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count,
        SUM(collected_amount) as total_collected,
        SUM(target_amount) as total_target,
        SUM(beneficiaries) as total_beneficiaries
        FROM projects WHERE status IN ('active', 'completed')");
    $stmt->execute();
    $stats = $stmt->fetch();

} catch (PDOException $e) {
    $activeProjects = [];
    $completedProjects = [];
    $stats = ['total_projects' => 0, 'active_count' => 0, 'completed_count' => 0, 'total_collected' => 0, 'total_target' => 0, 'total_beneficiaries' => 0];
}

// Kategori çevirileri
$categoryLabels = [
    'education' => 'Eğitim',
    'health' => 'Sağlık',
    'social' => 'Sosyal Yardım',
    'disaster' => 'Afet Yardımı',
    'orphan' => 'Yetim Destek'
];

// Kategori renkleri
$categoryColors = [
    'education' => 'primary',
    'health' => 'success',
    'social' => 'warning',
    'disaster' => 'danger',
    'orphan' => 'info'
];
?>

<!-- Hero Section - Simple (matching About page) -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-3">Projelerimiz</h1>
                <p class="lead mb-4">
                    Toplumun farklı kesimlerine ulaşarak hayırlı işler yapıyor, 
                    birlikte daha güzel bir dünya inşa ediyoruz.
                </p>
                
                <!-- İstatistikler -->
                <div class="row text-center mt-4">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent"><?= number_format($stats['active_count']) ?></h3>
                            <small class="stat-label-muted">Aktif Proje</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent"><?= number_format($stats['completed_count']) ?></h3>
                            <small class="stat-label-muted">Tamamlanan</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent"><?= number_format($stats['total_beneficiaries']) ?></h3>
                            <small class="stat-label-muted">Kişiye Ulaştık</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent">₺<?= number_format($stats['total_collected']) ?></h3>
                            <small class="stat-label-muted">Toplanan Bağış</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Aktif Projeler -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary px-3 py-2 mb-3">Devam Eden Projeler</span>
            <h2>Şu An Aktif Olan Projelerimiz</h2>
            <p class="text-muted">Desteğinize ihtiyaç duyan projelerimiz</p>
        </div>

        <?php if (!empty($activeProjects)): ?>
            <div class="row g-4">
                <?php foreach ($activeProjects as $project): ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 project-card-simple">
                            <div class="card-body">
                                <!-- Kategori Badge -->
                                <span class="badge bg-<?= $categoryColors[$project['category']] ?? 'secondary' ?> mb-3">
                                    <?= $categoryLabels[$project['category']] ?? $project['category'] ?>
                                </span>
                                
                                <!-- Proje Başlığı -->
                                <h4 class="card-title mb-3"><?= htmlspecialchars($project['title']) ?></h4>
                                
                                <!-- Proje Açıklaması -->
                                <p class="card-text text-muted mb-4">
                                    <?= htmlspecialchars($project['description']) ?>
                                </p>
                                
                                <!-- Butonlar -->
                                <div class="d-flex gap-2">
                                    <a href="project-detail.php?slug=<?= $project['slug'] ?>" class="btn btn-outline-primary flex-fill">
                                        <i class="fas fa-info-circle me-1"></i> Detaylar
                                    </a>
                                    <a href="donate.php?project=<?= $project['id'] ?>" class="btn btn-primary flex-fill">
                                        <i class="fas fa-heart me-1"></i> Bağış Yap
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Şu anda aktif proje bulunmuyor</h4>
                <p class="text-muted">Yeni projeler yakında duyurulacak</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Tamamlanan Projeler -->
<?php if (!empty($completedProjects)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-success px-3 py-2 mb-3">Tamamlanan Projeler</span>
            <h2>Başarıyla Tamamladığımız Projeler</h2>
            <p class="text-muted">Desteğiniz sayesinde hayata geçirdiklerimiz</p>
        </div>

        <div class="row g-4">
            <?php foreach ($completedProjects as $project): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 completed-project-card">
                        <div class="card-body">
                            <span class="badge bg-success mb-3">
                                <i class="fas fa-check me-1"></i> Tamamlandı
                            </span>
                            
                            <!-- Kategori -->
                            <span class="badge bg-<?= $categoryColors[$project['category']] ?? 'secondary' ?> mb-2">
                                <?= $categoryLabels[$project['category']] ?? $project['category'] ?>
                            </span>
                            
                            <h5 class="card-title"><?= htmlspecialchars($project['title']) ?></h5>
                            <p class="card-text text-muted mb-3">
                                <?= htmlspecialchars($project['description']) ?>
                            </p>
                            
                            <a href="project-detail.php?slug=<?= $project['slug'] ?>" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-eye me-1"></i> Detayları Gör
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action - Simple (matching About page style) -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-3">Siz de Bir Projeye Destek Olun</h2>
        <p class="lead mb-4">
            Her bağışınız, birinin hayatına dokunuyor ve toplumsal değişime katkı sağlıyor.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="index.php?page=donate" class="btn btn-accent btn-lg">
                <i class="fas fa-heart me-2"></i> Bağış Yap
            </a>
            <a href="index.php?page=volunteer" class="btn btn-outline-light btn-lg">
                <i class="fas fa-hands-helping me-2"></i> Gönüllü Ol
            </a>
        </div>
    </div>
</section>

<style>
/* ========================================
   CONSISTENT PROJECTS PAGE STYLES
   ======================================== */

/* Hero Section - Simple Design (matching About page) */
.hero-section {
    background: linear-gradient(135deg, #f8fafb 0%, #ffffff 100%);
    padding: calc(80px + 4rem) 0 4rem 0;
    position: relative;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(78, 166, 116, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(211, 217, 43, 0.03) 0%, transparent 50%);
    pointer-events: none;
}

/* Statistics Styling with Consistent Logo Color */
.stat-simple {
    text-align: center;
    padding: 1rem 0.5rem;
    transition: all 0.3s ease;
    border-radius: 8px;
}

.stat-simple:hover {
    transform: translateY(-2px);
    background: rgba(78, 166, 116, 0.05);
}

/* Consistent color for all statistics */
.stat-number-consistent {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color) !important; /* #4EA674 - Primary Green for all */
    margin-bottom: 0.5rem;
    line-height: 1;
    font-family: 'Poppins', sans-serif;
}

.stat-label-muted {
    font-size: 1rem;
    color: var(--gray-600) !important;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Clean CTA Section */
.py-5.bg-primary.text-white {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
    padding: 5rem 0;
    position: relative;
}

.py-5.bg-primary.text-white::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, 
        rgba(78, 166, 116, 0.1) 0%, 
        rgba(61, 133, 96, 0.1) 100%);
    pointer-events: none;
}

.text-center {
    position: relative;
    z-index: 2;
}

.text-center h2 {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: white;
}

.text-center p {
    font-size: 1.25rem;
    line-height: 1.6;
    margin-bottom: 2.5rem;
    opacity: 0.95;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Accent Button using Logo Yellow */
.btn-accent {
    background: var(--accent-color) !important;
    color: var(--gray-900) !important;
    border: 2px solid var(--accent-color) !important;
    font-weight: 700;
}

.btn-accent:hover {
    background: var(--secondary-color) !important;
    color: var(--gray-900) !important;
    border-color: var(--secondary-color) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(242, 229, 41, 0.3);
}

/* Clean Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    min-width: 180px;
}

.btn-outline-light {
    background: transparent;
    color: white;
    border-color: rgba(255, 255, 255, 0.3);
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 992px) {
    .hero-section {
        padding: calc(70px + 3rem) 0 3rem 0;
    }
    
    .display-4 {
        font-size: 2.5rem;
    }
    
    .text-center h2 {
        font-size: 2rem;
    }
    
    .stat-number-consistent {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: calc(60px + 2rem) 0 2rem 0;
    }
    
    .display-4 {
        font-size: 2rem;
    }
    
    .lead {
        font-size: 1.1rem;
    }
    
    .stat-number-consistent {
        font-size: 1.8rem;
    }
    
    .btn {
        width: 100%;
        max-width: 280px;
    }
    
    .py-5.bg-primary.text-white {
        padding: 3rem 0;
    }
}

@media (max-width: 576px) {
    .hero-section {
        padding: calc(50px + 1rem) 0 1rem 0;
    }
    
    .display-4 {
        font-size: 1.75rem;
    }
    
    .lead {
        font-size: 1rem;
    }
    
    .text-center h2 {
        font-size: 1.5rem;
    }
    
    .text-center p {
        font-size: 1rem;
    }
    
    .stat-number-consistent {
        font-size: 1.5rem;
    }
}

/* Basit proje kartları için stiller */
.project-card-simple {
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.project-card-simple:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.completed-project-card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.completed-project-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

.stat-item h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-item small {
    font-size: 0.9rem;
    opacity: 0.9;
}

.hero-simple .stat-item {
    background: rgba(255,255,255,0.1);
    padding: 1rem;
    border-radius: 0.5rem;
    backdrop-filter: blur(10px);
}

@media (max-width: 768px) {
    .hero-simple .stat-item h3 {
        font-size: 1.4rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
