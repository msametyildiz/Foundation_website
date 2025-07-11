<?php
// Content catalog'u dahil et
require_once 'includes/content_catalog.php';

// Veritabanı bağlantısı başarısız olup olmadığını kontrol et
$db_failed = function_exists('db_failed') && db_failed();

// Ana sayfa için slider verilerini çek
try {
    if (!$db_failed && isset($pdo)) {
        $stmt = $pdo->prepare("SELECT * FROM slider WHERE is_active = 1 ORDER BY sort_order ASC");
        $stmt->execute();
        $slider_items = $stmt->fetchAll();
    } else {
        $slider_items = [];
    }
} catch (PDOException $e) {
    $slider_items = [];
    error_log("Home page slider error: " . $e->getMessage());
}

// Öne çıkan projeleri çek
try {
    if (!$db_failed && isset($pdo)) {
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE is_featured = 1 AND status = 'active' ORDER BY sort_order ASC LIMIT 3");
        $stmt->execute();
        $featured_projects = $stmt->fetchAll();
    } else {
        $featured_projects = [];
    }
} catch (PDOException $e) {
    $featured_projects = [];
    error_log("Home page featured projects error: " . $e->getMessage());
}

// Son haberler
try {
    if (!$db_failed && isset($pdo)) {
        $stmt = $pdo->prepare("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
        $stmt->execute();
        $recent_news = $stmt->fetchAll();
    } else {
        $recent_news = [];
    }
} catch (PDOException $e) {
    $recent_news = [];
    error_log("Home page news error: " . $e->getMessage());
}

// Settings tablosundan hero ve istatistik verilerini çek
try {
    // Varsayılan değerler
    $default_projects = function_exists('get_default_stats') ? get_default_stats('projects') : 100;
    $default_volunteers = function_exists('get_default_stats') ? get_default_stats('volunteers') : 26;
    $default_families = function_exists('get_default_stats') ? get_default_stats('families') : 5001;
    $default_donations = function_exists('get_default_stats') ? get_default_stats('donations') : 500000;
    
    // Hero Section varsayılan verileri
    $hero_title = 'Umut Olmaya Devam Ediyoruz';
    $hero_subtitle = 'Her bağış bir umut, her yardım bir gülümseme. Muhtaç ailelere ulaşan yardımlarınızla hayatlara dokunmaya devam ediyoruz.';
    
    // İstatistik verileri - varsayılan değerler
    $total_projects = $default_projects;
    $total_volunteers = $default_volunteers;
    $total_families = $default_families;
    $total_donations = $default_donations;
    
    // Veritabanı bağlantısı varsa verileri çek
    if (!$db_failed && isset($pdo)) {
        // Settings tablosundan tüm ayarları çek
        $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM settings");
        $stmt->execute();
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        // Hero Section verileri - settings tablosundan dinamik olarak al
        $hero_title = $settings['hero_title'] ?? $hero_title;
        $hero_subtitle = $settings['hero_subtitle'] ?? $hero_subtitle;
        
        // İstatistik verileri - öncelikle settings tablosundan al
        $stats_projects = (int)($settings['stats_projects'] ?? 0);
        $stats_beneficiaries = (int)($settings['stats_beneficiaries'] ?? 0);
        $stats_volunteers = (int)($settings['stats_volunteers'] ?? 0);
        $stats_donations = (int)($settings['stats_donations'] ?? 0);
        
        // Eğer settings'te veri yoksa veritabanından hesapla
        if ($stats_projects <= 0) {
            try {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE status IN ('active', 'completed')");
                $stmt->execute();
                $count = (int)$stmt->fetchColumn();
                $stats_projects = ($count > 0) ? $count : $default_projects;
            } catch (PDOException $e) {
                $stats_projects = $default_projects;
                error_log("Home page projects count error: " . $e->getMessage());
            }
        }
        
        if ($stats_volunteers <= 0) {
            try {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM volunteer_applications WHERE status = 'approved'");
                $stmt->execute();
                $count = (int)$stmt->fetchColumn();
                $stats_volunteers = ($count > 0) ? $count : $default_volunteers;
            } catch (PDOException $e) {
                $stats_volunteers = $default_volunteers;
                error_log("Home page volunteers count error: " . $e->getMessage());
            }
        }
        
        if ($stats_beneficiaries <= 0) {
            try {
                $stmt = $pdo->prepare("SELECT SUM(beneficiaries) as total FROM projects WHERE status IN ('active', 'completed') AND beneficiaries IS NOT NULL");
                $stmt->execute();
                $count = (int)$stmt->fetchColumn();
                $stats_beneficiaries = ($count > 0) ? $count : $default_families;
            } catch (PDOException $e) {
                $stats_beneficiaries = $default_families;
                error_log("Home page beneficiaries count error: " . $e->getMessage());
            }
        }

        // Bağış miktarı için de aynı kontrol (eğer projects tablosunda collected_amount alanı varsa)
        if ($stats_donations <= 0) {
            try {
                // Projeler tablosunda collected_amount alanı yoksa sadece settings'teki değeri kullan
                $stmt = $pdo->prepare("SHOW COLUMNS FROM projects LIKE 'collected_amount'");
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $stmt = $pdo->prepare("SELECT SUM(collected_amount) as total FROM projects WHERE status IN ('active', 'completed')");
                    $stmt->execute();
                    $count = (int)$stmt->fetchColumn();
                    $stats_donations = ($count > 0) ? $count : $default_donations;
                } else {
                    $stats_donations = $default_donations;
                }
            } catch (PDOException $e) {
                $stats_donations = $default_donations;
                error_log("Home page donations count error: " . $e->getMessage());
            }
        }

        // Geriye uyumluluk için eski değişken adlarını koruyalım
        $total_donations = $stats_donations;
        $total_projects = $stats_projects;
        $total_volunteers = $stats_volunteers;
        $total_families = $stats_beneficiaries;
    }
} catch (PDOException $e) {
    // Hata durumunda varsayılan değerler
    $hero_title = 'Umut Olmaya Devam Ediyoruz';
    $hero_subtitle = 'Her bağış bir umut, her yardım bir gülümseme. Muhtaç ailelere ulaşan yardımlarınızla hayatlara dokunmaya devam ediyoruz.';
    $total_donations = $default_donations;
    $total_projects = $default_projects;
    $total_volunteers = $default_volunteers;
    $total_families = $default_families;
    $settings = [];
    error_log("Home page settings error: " . $e->getMessage());
}

// İstatistikler için varsayılan değerleri kontrol et (0 değerleri engelle)
$total_projects = ($total_projects <= 0) ? $default_projects : $total_projects;
$total_volunteers = ($total_volunteers <= 0) ? $default_volunteers : $total_volunteers;
$total_families = ($total_families <= 0) ? $default_families : $total_families;
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
                        <a href="<?= site_url('donate') ?>" class="btn btn-hero-primary">
                            <i class="fas fa-heart"></i>
                            Bağış Yap
                        </a>
                        <a href="<?= site_url('volunteer') ?>" class="btn btn-hero-secondary">
                            <i class="fas fa-hands-helping"></i>
                            Gönüllü Ol
                        </a>
                    </div>
                    <!-- hero-static -->
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
            <!-- Hero-photo -->
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
                    <a href="<?= site_url('about') ?>" class="btn btn-outline-primary">
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
                                <a href="<?= site_url('projects') ?>" class="btn btn-primary btn-sm">
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
            <a href="<?= site_url('projects') ?>" class="btn btn-primary btn-lg projects-view-all-btn">
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
                        <h3 class="stat-number" data-target="<?= $total_projects > 0 ? $total_projects : 100 ?>">0</h3>
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
                        <h3 class="stat-number" data-target="<?= $total_volunteers > 0 ? $total_volunteers : 26 ?>">0</h3>
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
                        <h3 class="stat-number" data-target="<?= $total_families > 0 ? $total_families : 5001 ?>">0</h3>
                        <p class="stat-label">Yardım Edilen Aile</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Initialize stats counter with animation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate stats counter
    const animateStatsCounter = function() {
        const statNumbers = document.querySelectorAll('.stat-number[data-target]');
        
        statNumbers.forEach(function(element) {
            const target = parseInt(element.getAttribute('data-target')) || 0;
            
            // Ensure we have a valid target value
            if (target <= 0) {
                // Fallback values based on label
                const label = element.nextElementSibling?.textContent.toLowerCase() || '';
                let value = 100;
                
                if (label.includes('proje')) {
                    value = 100;
                } else if (label.includes('gönüllü')) {
                    value = 26;
                } else if (label.includes('aile')) {
                    value = 5001;
                }
                
                element.setAttribute('data-target', value);
            }
            
            // Get the final target value
            const finalTarget = parseInt(element.getAttribute('data-target'));
            
            // Start from zero
            let current = 0;
            element.textContent = '0';
            
            // Calculate animation duration and step
            const duration = 2000; // 2 seconds
            const step = finalTarget / (duration / 16); // ~60fps
            
            // Animate the counter
            const timer = setInterval(function() {
                current += step;
                
                if (current >= finalTarget) {
                    current = finalTarget;
                    clearInterval(timer);
                }
                
                // Format with thousands separator
                element.textContent = Math.floor(current).toLocaleString('tr-TR');
            }, 16);
        });
    };
    
    // Use Intersection Observer to trigger animation when stats are visible
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                animateStatsCounter();
                observer.disconnect(); // Only run once
            }
        });
    }, { threshold: 0.1 });
    
    // Observe the stats section
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    } else {
        // Fallback if section not found
        animateStatsCounter();
    }
});
</script>

