<?php
// Content catalog'u dahil et
require_once 'includes/content_catalog.php';

// Ana sayfa için slider verilerini çek
try {
    $stmt = $pdo->prepare("SELECT * FROM slider WHERE is_active = 1 ORDER BY sort_order ASC");
    $stmt->execute();
    $slider_items = $stmt->fetchAll();
} catch (PDOException $e) {
    $slider_items = [];
}

// Öne çıkan projeleri çek
try {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE is_featured = 1 AND status = 'active' ORDER BY sort_order ASC LIMIT 3");
    $stmt->execute();
    $featured_projects = $stmt->fetchAll();
} catch (PDOException $e) {
    $featured_projects = [];
}

// Son haberler
try {
    $stmt = $pdo->prepare("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $recent_news = $stmt->fetchAll();
} catch (PDOException $e) {
    $recent_news = [];
}

// Settings tablosundan hero ve istatistik verilerini çek
try {
    // Settings tablosundan tüm ayarları çek
    $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM settings");
    $stmt->execute();
    $settings = [];
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }

    // Hero Section verileri - settings tablosundan dinamik olarak al
    $hero_title = $settings['hero_title'] ?? 'Umut Olmaya Devam Ediyoruz';
    $hero_subtitle = $settings['hero_subtitle'] ?? 'Her bağış bir umut, her yardım bir gülümseme. Muhtaç ailelere ulaşan yardımlarınızla hayatlara dokunmaya devam ediyoruz.';
    
    // İstatistik verileri - öncelikle settings tablosundan al
    $stats_projects = (int)($settings['stats_projects'] ?? 0);
    $stats_beneficiaries = (int)($settings['stats_beneficiaries'] ?? 0);
    $stats_volunteers = (int)($settings['stats_volunteers'] ?? 0);
    $stats_donations = (int)($settings['stats_donations'] ?? 0);
    
    // Eğer settings'te veri yoksa veritabanından hesapla
    if ($stats_projects == 0) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE status IN ('active', 'completed')");
        $stmt->execute();
        $stats_projects = (int)($stmt->fetchColumn() ?: 0);
    }
    
    if ($stats_volunteers == 0) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM volunteer_applications WHERE status = 'approved'");
        $stmt->execute();
        $stats_volunteers = (int)($stmt->fetchColumn() ?: 0);
    }
    
    if ($stats_beneficiaries == 0) {
        $stmt = $pdo->prepare("SELECT SUM(beneficiaries) as total FROM projects WHERE status IN ('active', 'completed') AND beneficiaries IS NOT NULL");
        $stmt->execute();
        $stats_beneficiaries = (int)($stmt->fetchColumn() ?: 0);
    }

    // Bağış miktarı için de aynı kontrol (eğer projects tablosunda collected_amount alanı varsa)
    if ($stats_donations == 0) {
        // Projeler tablosunda collected_amount alanı yoksa sadece settings'teki değeri kullan
        $stmt = $pdo->prepare("SHOW COLUMNS FROM projects LIKE 'collected_amount'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->prepare("SELECT SUM(collected_amount) as total FROM projects WHERE status IN ('active', 'completed')");
            $stmt->execute();
            $stats_donations = (int)($stmt->fetchColumn() ?: 0);
        }
    }

    // Geriye uyumluluk için eski değişken adlarını koruyalım
    $total_donations = $stats_donations;
    $total_projects = $stats_projects;
    $total_volunteers = $stats_volunteers;
    $total_families = $stats_beneficiaries;

} catch (PDOException $e) {
    // Hata durumunda varsayılan değerler
    $hero_title = 'Umut Olmaya Devam Ediyoruz';
    $hero_subtitle = 'Her bağış bir umut, her yardım bir gülümseme. Muhtaç ailelere ulaşan yardımlarınızla hayatlara dokunmaya devam ediyoruz.';
    $total_donations = 500000;
    $total_projects = 10;
    $total_volunteers = 25;
    $total_families = 5000;
    $settings = [];
}
?>

<!-- Hero Section - Modern Gradient Design -->
<section class="hero-modern">
    <div class="hero-bg-overlay"></div>
    <div class="container">
        <div class="row min-vh-100 align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <span class="hero-badge">🌟 Birlikte Güçlüyüz</span>
                    <h1 class="hero-title">
                        <?= htmlspecialchars($hero_title) ?>
                    </h1>
                    <p class="hero-subtitle">
                        <?= htmlspecialchars($hero_subtitle) ?>
                    </p>
                    <div class="hero-actions">
                        <a href="index.php?page=donate" class="btn btn-hero-primary">
                            <i class="fas fa-heart"></i>
                            Bağış Yap
                        </a>
                        <a href="index.php?page=volunteer" class="btn btn-hero-secondary">
                            <i class="fas fa-hands-helping"></i>
                            Gönüllü Ol
                        </a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?= number_format($total_families) ?>+</span>
                            <span class="stat-label">Aile</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?= number_format($total_volunteers) ?>+</span>
                            <span class="stat-label">Gönüllü</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?= $total_projects ?>+</span>
                            <span class="stat-label">Proje</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <div class="floating-card card-1">
                        <i class="fas fa-heart text-danger"></i>
                        <span>Projelerimiz</span>
                        <strong><?= number_format($total_projects) ?>+</strong>
                    </div>
                    <div class="floating-card card-2">
                        <i class="fas fa-users text-primary"></i>
                        <span>Aktif Gönüllüler</span>
                        <strong><?= $total_volunteers ?>+</strong>
                    </div>
                    <div class="floating-card card-3">
                        <i class="fas fa-home text-success"></i>
                        <span>Yardım Edilen Aile</span>
                        <strong><?= number_format($total_families) ?>+</strong>
                    </div>
                    <div class="hero-image-container">
                        <img src="uploads/images/hero/hero-image.jpg" 
                             alt="<?= htmlspecialchars($hero_title) ?> - Necat Derneği" 
                             class="hero-main-image"
                             loading="eager"
                             decoding="async"
                             width="1536"
                             height="1024">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll-indicator">
        <span>Keşfetmeye devam et</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<!-- About Preview Section -->
<section class="about-preview py-5" id="about-preview">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-image-container">
                    <img src="uploads/images/about/about-main.jpg" alt="Hakkımızda" class="about-image">
                    <div class="about-overlay">
                        <div class="play-button">
                            <i class="fas fa-play"></i>
                        </div>
                        <span>Hikayemizi İzle</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content">
                    <span class="section-badge">Hakkımızda</span>
                    <h2 class="section-title">
                        Birlikte Daha 
                        <span class="text-gradient">Güçlüyüz</span>
                    </h2>
                    <p class="section-description">
                        Necat Derneği olarak, yardım eli uzatan ve umut dağıtan bir toplum 
                        inşa etmek için çalışıyoruz. Sosyal sorumluluk bilinciyle hareket 
                        ederek, ihtiyaç sahibi bireylere destek oluyoruz.
                    </p>
                    <div class="about-features">
                        <div class="about-feature">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>İnsana Odaklı Yaklaşım</span>
                        </div>
                        <div class="about-feature">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Kapsayıcı Toplumsal Destek</span>
                        </div>
                        <div class="about-feature">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Sürdürülebilir projeler</span>
                        </div>
                    </div>
                    <a href="index.php?page=about" class="btn btn-outline-primary">
                        Daha Fazla Bilgi
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Projects Section -->
<section class="projects-section py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <span class="section-badge">Projelerimiz</span>
                <h2 class="section-title">  
                    Öne Çıkan 
                    <span class="text-gradient">Projeler</span>
                </h2>
                <p class="section-description mx-auto">
                    Her projemiz bir hayata dokunuyor, bir umut yeşertiyoruz.
                </p>
            </div>
        </div>
        <div class="row">
            <?php if (!empty($featured_projects)): ?>
                <?php foreach ($featured_projects as $project): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="project-card">
                        <div class="project-content">
                            <!-- Kategori Badge -->
                            <span class="project-category badge bg-success mb-3">
                                <i class="fas fa-heart me-2"></i>
                                <?= clean_output($project['category']) ?>
                            </span>
                            
                            <!-- Proje Başlığı -->
                            <h3><?= clean_output($project['title']) ?></h3>
                            
                            <!-- Proje Açıklaması -->
                            <p><?= clean_output($project['description']) ?></p>
                            
                            
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Katalogdan yedek içerik -->
                <?php 
                $featured_activities = array_slice($activities, 0, 3);
                foreach ($featured_activities as $index => $activity): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="project-card">
                        <div class="project-content">
                            <!-- Kategori Badge -->
                            <span class="project-category badge bg-success mb-3">
                                <i class="<?= $activity['icon'] ?> me-2"></i>
                                <?= ucfirst(str_replace('_', ' ', $activity['category'])) ?>
                            </span>
                            
                            <!-- Proje Başlığı -->
                            <h3><?= clean_output($activity['title']) ?></h3>
                            
                            <!-- Proje Açıklaması -->
                            <p><?= clean_output($activity['description']) ?></p>
                            
                            <div class="project-footer">
                                <a href="index.php?page=projects" class="btn btn-primary btn-sm">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Detayları Gör
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="index.php?page=projects" class="btn btn-primary btn-lg projects-view-all-btn">
                Tüm Projeleri Gör
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-5">
  <div class="stats-bg-overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <span class="section-badge text-white">İstatistikler</span>
                <h2 class="section-title text-white" style="color: white !important;">
                    Birlikte Başardıklarımız
                </h2>
            </div>
        </div>
        <div class="row">
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number" data-target="<?= $total_projects ?>">0</h3>
                        <p class="stat-label">Tamamlanan Proje</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number" data-target="<?= $total_volunteers ?>">0</h3>
                        <p class="stat-label">Gönüllülerimiz</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number" data-target="<?= $total_families ?>">0</h3>
                        <p class="stat-label">Yardım Edilen Aile</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Haberler ve Duyurular
<?php if (!empty($recent_news)): ?>
<section class="news-section py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <span class="section-badge">Haberler</span>
                <h2 class="section-title">Son Haberler ve Duyurular</h2>
                <p class="section-subtitle">Faaliyetlerimiz ve projelerimizden güncel haberler</p>
            </div>
        </div>
        <div class="row">
            <?php foreach ($recent_news as $news): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <article class="news-card">
                    <div class="news-image">
                        <img src="<?= !empty($news['image']) ? $news['image'] : 'uploads/images/news/default-news.jpg' ?>" 
                             alt="<?= clean_output($news['title']) ?>">
                        <div class="news-overlay">
                            <span class="news-category">
                                <?= ucfirst($news['category']) ?>
                            </span>
                            <span class="news-date">
                                <?= date('d.m.Y', strtotime($news['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">
                            <a href="#" class="text-decoration-none">
                                <?= clean_output($news['title']) ?>
                            </a>
                        </h3>
                        <p class="news-excerpt">
                            <?= clean_output($news['summary'] ?: substr(strip_tags($news['content']), 0, 150) . '...') ?>
                        </p>
                        
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="index.php?page=press" class="btn btn-primary btn-lg">
                Tüm Haberleri Gör
                <i class="fas fa-newspaper ms-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?> -->

<!-- CTA Section
<section class="py-5 bg-gradient-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4">Birlikte Değişim Yaratın</h2>
        <p class="lead mb-4">
            Her destek, bir umudun doğmasına vesile olur. 
            Siz de bu değişimin bir parçası olun.
        </p>
        <a href="index.php?page=donate" class="btn btn-light btn-lg me-3">
            <i class="fas fa-heart"></i> Hemen Bağış Yap
        </a>
        <a href="index.php?page=contact" class="btn btn-outline-light btn-lg">
            <i class="fas fa-envelope"></i> İletişime Geçin
        </a>
    </div>
</section>
 -->