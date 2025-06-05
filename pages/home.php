<?php
// İçerik kataloğunu yükle
require_once 'includes/content_catalog.php';

// Ana sayfa içeriğini al
$homepage_content = getContentForPage('home');

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

// İstatistikler (örnek veriler)
$total_donations = 150000;
$total_projects = 25;
$total_volunteers = 120;
$families_helped = 500;
?>

<!-- Hero Section - Modern Gradient Design -->
<section class="hero-modern">
    <div class="hero-bg-overlay"></div>
    <div class="container">
        <div class="row min-vh-100 align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <span class="hero-badge">🌟 <?= $homepage_content['hero_slogan'] ?></span>
                    <h1 class="hero-title">
                        Gönüllü Ol,
                        <span class="text-gradient">Hayat Değiştir</span>
                    </h1>
                    <p class="hero-subtitle">
                        <?= $homepage_content['hero_description'] ?>
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
                            <span class="stat-number"><?= number_format($families_helped) ?>+</span>
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
                        <span>Bağışlarınız</span>
                        <strong>₺<?= number_format($total_donations) ?></strong>
                    </div>
                    <div class="floating-card card-2">
                        <i class="fas fa-users text-primary"></i>
                        <span>Aktif Gönüllüler</span>
                        <strong><?= $total_volunteers ?></strong>
                    </div>
                    <div class="floating-card card-3">
                        <i class="fas fa-home text-success"></i>
                        <span>Yardım Edilen Aile</span>
                        <strong><?= $families_helped ?>+</strong>
                    </div>
                    <div class="hero-image-container">
                        <img src="uploads/images/hero/hero-image.jpg" 
                             alt="Necat Derneği - Birlikte güçlü bir toplum için çalışan gönüllüler ve yardım faaliyetleri" 
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

<!-- Mission Preview Section -->
<section class="mission-preview py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="mission-content">
                    <h2 class="section-title mb-4">Misyonumuz</h2>
                    <p class="lead text-muted mb-4">
                        <?= $homepage_content['mission_preview'] ?>
                    </p>
                    <div class="cta-box p-4 bg-primary text-white rounded shadow">
                        <h4 class="mb-3"><i class="fas fa-hand-holding-heart"></i> Birlikte Güçlü</h4>
                        <p class="mb-0"><?= $homepage_content['call_to_action'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mission-image-container">
                    <img src="uploads/images/mission/mission-main.jpg" alt="Misyonumuz" class="mission-image">
                    <div class="floating-stats">
                        <div class="stat-card bg-white shadow rounded p-3">
                            <i class="fas fa-heart text-danger fa-2x mb-2"></i>
                            <h5 class="mb-0"><?= number_format($total_donations) ?> ₺</h5>
                            <small class="text-muted">Toplam Bağış</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Güvenilir</h3>
                    <p>Tüm bağışlar şeffaf şekilde takip edilir ve raporlanır.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Hızlı</h3>
                    <p>Acil durumlar için 7/24 hızlı müdahale ekibimiz hazır.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3>Kapsamlı</h3>
                    <p>Eğitimden sağlığa, barınmadan beslenmeye geniş yelpaze.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Preview Section -->
<section class="about-preview py-5">
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
                            <span>Şeffaf mali raporlama</span>
                        </div>
                        <div class="about-feature">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Profesyonel ekip</span>
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
                    Hayat Değiştiren 
                    <span class="text-gradient">Projeler</span>
                </h2>
                <p class="section-description mx-auto">
                    Her projemiz bir hayata dokunuyor, bir umut yeşertiyoruz.
                </p>
            </div>
        </div>
        <div class="row">
            <?php if (!empty($featured_projects)): ?>
                <?php foreach ($featured_projects as $index => $project): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="project-card">
                        <div class="project-image">
                            <img src="uploads/images/projects/project-<?= $index + 1 ?>.jpg" alt="<?= clean_output($project['title']) ?>">
                            <div class="project-overlay">
                                <span class="project-category">Yardım Projesi</span>
                            </div>
                        </div>
                        <div class="project-content">
                            <h3><?= clean_output($project['title']) ?></h3>
                            <p><?= substr(clean_output($project['description']), 0, 120) ?>...</p>
                            <div class="project-progress">
                                <div class="progress-info">
                                    <span>İlerleme</span>
                                    <span>75%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 75%"></div>
                                </div>
                            </div>
                            <a href="index.php?page=projects" class="btn btn-sm btn-primary">
                                Detayları Gör
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Örnek projeler -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="project-card">
                        <div class="project-image">
                            <img src="uploads/images/projects/project-1.jpg" alt="Eğitim Projesi">
                            <div class="project-overlay">
                                <span class="project-category">Eğitim</span>
                            </div>
                        </div>
                        <div class="project-content">
                            <h3>Çocuklar İçin Eğitim Desteği</h3>
                            <p>Maddi durumu iyi olmayan ailerin çocuklarına eğitim desteği sağlıyoruz.</p>
                            <div class="project-progress">
                                <div class="progress-info">
                                    <span>İlerleme</span>
                                    <span>85%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 85%"></div>
                                </div>
                            </div>
                            <a href="index.php?page=projects" class="btn btn-sm btn-primary">
                                Detayları Gör
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="project-card">
                        <div class="project-image">
                            <img src="uploads/images/projects/project-2.jpg" alt="Gıda Projesi">
                            <div class="project-overlay">
                                <span class="project-category">Beslenme</span>
                            </div>
                        </div>
                        <div class="project-content">
                            <h3>Ramazan Gıda Kolisi</h3>
                            <p>Ramazan ayında ihtiyaç sahibi ailelere gıda kolisi dağıtımı yapıyoruz.</p>
                            <div class="project-progress">
                                <div class="progress-info">
                                    <span>İlerleme</span>
                                    <span>60%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 60%"></div>
                                </div>
                            </div>
                            <a href="index.php?page=projects" class="btn btn-sm btn-primary">
                                Detayları Gör
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="project-card">
                        <div class="project-image">
                            <img src="uploads/images/projects/project-3.jpg" alt="Sağlık Projesi">
                            <div class="project-overlay">
                                <span class="project-category">Sağlık</span>
                            </div>
                        </div>
                        <div class="project-content">
                            <h3>Sağlık Tarama Hizmeti</h3>
                            <p>Düzenli sağlık taramaları ve ücretsiz muayene hizmetleri sunuyoruz.</p>
                            <div class="project-progress">
                                <div class="progress-info">
                                    <span>İlerleme</span>
                                    <span>90%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 90%"></div>
                                </div>
                            </div>
                            <a href="index.php?page=projects" class="btn btn-sm btn-primary">
                                Detayları Gör
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="index.php?page=projects" class="btn btn-primary btn-lg">
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
                <h2 class="section-title text-white">
                    Birlikte Başardıklarımız
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-lira-sign"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number" data-target="<?= $total_donations ?>">0</h3>
                        <p class="stat-label">Toplanan Bağış</p>
                        <span class="stat-unit">₺</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
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
            <div class="col-lg-3 col-md-6 mb-4">
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
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number" data-target="<?= $families_helped ?>">0</h3>
                        <p class="stat-label">Yardım Edilen Aile</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <span class="section-badge">Referanslar</span>
                <h2 class="section-title">
                    Bizimle Yolculuk Edenler 
                    <span class="text-gradient">Ne Diyor?</span>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p>"Necat Derneği sayesinde çocuğumun eğitim masraflarını karşılayabiliyoruz. Bu destek bizim için çok değerli."</p>
                        <div class="testimonial-author">
                            <img src="uploads/images/testimonials/avatar-1.jpg" alt="Ayşe Hanım">
                            <div class="author-info">
                                <h4>Ayşe Yılmaz</h4>
                                <span>Faydalanan Aile</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p>"Gönüllü olarak katıldığım projeler sayesinde hem yardım ediyor hem de çok şey öğreniyorum."</p>
                        <div class="testimonial-author">
                            <img src="uploads/images/testimonials/avatar-2.jpg" alt="Mehmet Bey">
                            <div class="author-info">
                                <h4>Mehmet Kaya</h4>
                                <span>Gönüllü</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p>"Şeffaf çalışmaları ve düzenli raporları sayesinde güvenle bağış yapabiliyorum."</p>
                        <div class="testimonial-author">
                            <img src="uploads/images/testimonials/avatar-3.jpg" alt="Fatma Hanım">
                            <div class="author-info">
                                <h4>Fatma Özkan</h4>
                                <span>Bağışçı</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="cta-content">
                    <h2 class="cta-title">Hayat Değiştiren Yolculuğa Sen de Katıl!</h2>
                    <p class="cta-description">
                        Bir bağış, bir gönüllülük, bir paylaşım... Her küçük adım büyük değişimlerin başlangıcıdır.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="cta-actions">
                    <a href="index.php?page=donate" class="btn btn-cta-primary me-2 mb-2">
                        <i class="fas fa-heart"></i>
                        Bağış Yap
                    </a>
                    <a href="index.php?page=volunteer" class="btn btn-cta-secondary mb-2">
                        <i class="fas fa-hands-helping"></i>
                        Gönüllü Ol
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- İstatistikler -->
<section class="stats-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <span class="stat-number"><?php echo number_format($total_donations); ?> ₺</span>
                    <span class="stat-label">Toplanan Bağış</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $total_projects; ?></span>
                    <span class="stat-label">Tamamlanan Proje</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $total_volunteers; ?></span>
                    <span class="stat-label">Gönüllümüz</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $families_helped; ?></span>
                    <span class="stat-label">Yardım Edilen Aile</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hakkımızda Özet -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <h2 class="mb-4">Biz Kimiz?</h2>
                <p class="lead">
                    Necat Derneği olarak, yardım eli uzatan ve umut dağıtan bir toplum inşa etmek için 
                    çalışıyoruz. Sosyal sorumluluk bilinciyle hareket ederek, muhtaç ailelere 
                    ulaşmaya devam ediyoruz.
                </p>
                <p>
                    Eğitim, sağlık, afet yardımı ve sosyal destek alanlarında yürüttüğümüz projelerle 
                    binlerce insanın hayatına dokunuyoruz. Şeffaflık ve güven ilkelerimizle 
                    her bağışın amacına uygun şekilde kullanılmasını sağlıyoruz.
                </p>
                <a href="index.php?page=about" class="btn btn-primary">Daha Fazla Bilgi</a>
            </div>
            <div class="col-lg-6">
                <img src="assets/images/about-us.jpg" alt="Hakkımızda" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Öne Çıkan Projeler -->
<?php if (!empty($featured_projects)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Öne Çıkan Projelerimiz</h2>
            <p class="lead">Hayata dokunduğumuz projelerimizden öne çıkanlar</p>
        </div>
        
        <div class="row">
            <?php foreach ($featured_projects as $project): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card project-card h-100">
                        <?php if (!empty($project['image']) && file_exists($project['image'])): ?>
                            <img src="<?php echo clean_output($project['image']); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo clean_output($project['title']); ?>">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo clean_output($project['title']); ?></h5>
                            <p class="card-text">
                                <?php echo clean_output($project['short_description'] ?? truncate_text($project['description'], 100)); ?>
                            </p>
                            
                            <?php if ($project['target_amount'] > 0): ?>
                                <div class="progress mb-3">
                                    <?php 
                                    $progress = ($project['collected_amount'] / $project['target_amount']) * 100;
                                    $progress = min(100, $progress);
                                    ?>
                                    <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                                </div>
                                <div class="d-flex justify-content-between small text-muted">
                                    <span><?php echo number_format($project['collected_amount']); ?> ₺</span>
                                    <span><?php echo number_format($project['target_amount']); ?> ₺</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <a href="index.php?page=projects&id=<?php echo $project['id']; ?>" 
                               class="btn btn-outline-primary btn-sm">Detayları Gör</a>
                            <a href="index.php?page=donate" class="btn btn-primary btn-sm">Destek Ol</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center">
            <a href="index.php?page=projects" class="btn btn-primary">Tüm Projelerimiz</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Nasıl Yardım Edebilirsiniz -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Nasıl Yardım Edebilirsiniz?</h2>
            <p class="lead">Birlikte daha güçlüyüz, sizin de desteğinizle daha çok insana ulaşabiliriz</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-heart fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Bağış Yapın</h5>
                        <p class="card-text">
                            Maddi desteğinizle projelerimize katkıda bulunun. 
                            Her bağış bir aileye umut olur.
                        </p>
                        <a href="index.php?page=donate" class="btn btn-primary">Bağış Yap</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-hands-helping fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Gönüllü Olun</h5>
                        <p class="card-text">
                            Zamanınızı ayırarak projelerimizde aktif rol alın. 
                            Birlikte daha çok işe imza atabiliriz.
                        </p>
                        <a href="index.php?page=volunteer" class="btn btn-success">Gönüllü Ol</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-share-alt fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title">Paylaşın</h5>
                        <p class="card-text">
                            Projelerimizi sosyal medyada paylaşarak 
                            farkındalık yaratmaya yardımcı olun.
                        </p>
                        <div class="social-links">
                            <a href="#" class="btn btn-outline-primary btn-sm me-2">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm me-2">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Haberler ve Duyurular -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Son Haberler</h2>
            <p class="lead">Faaliyetlerimizden son haberler</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <img src="assets/images/news1.jpg" class="card-img-top" alt="Haber 1">
                    <div class="card-body">
                        <small class="text-muted">15 Kasım 2024</small>
                        <h5 class="card-title">Kış Yardımı Kampanyası Başladı</h5>
                        <p class="card-text">
                            Soğuk kış günlerinde muhtaç ailelere ulaşmak için yeni kampanyamızı başlattık.
                        </p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Devamını Oku</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <img src="assets/images/news2.jpg" class="card-img-top" alt="Haber 2">
                    <div class="card-body">
                        <small class="text-muted">10 Kasım 2024</small>
                        <h5 class="card-title">50 Öğrenciye Burs Desteği</h5>
                        <p class="card-text">
                            Eğitim burs programımız kapsamında 50 başarılı öğrenciye destek sağladık.
                        </p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Devamını Oku</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <img src="assets/images/news3.jpg" class="card-img-top" alt="Haber 3">
                    <div class="card-body">
                        <small class="text-muted">5 Kasım 2024</small>
                        <h5 class="card-title">Sağlık Taraması Tamamlandı</h5>
                        <p class="card-text">
                            Kırsal bölgelerde gerçekleştirdiğimiz sağlık taraması projesi başarıyla tamamlandı.
                        </p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Devamını Oku</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
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
