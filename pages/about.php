<?php
// Veritabanından gerçek verileri çek
try {
    // Site ayarlarını çek
    $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM site_settings");
    $stmt->execute();
    $site_settings = [];
    while ($row = $stmt->fetch()) {
        $site_settings[$row['setting_key']] = $row['setting_value'];
    }

    // İstatistikleri çek
    $stmt = $pdo->prepare("SELECT 
        (SELECT COUNT(*) FROM projects WHERE status IN ('active', 'completed')) as total_projects,
        (SELECT SUM(beneficiaries) FROM projects WHERE status IN ('active', 'completed') AND beneficiaries IS NOT NULL) as total_families,
        (SELECT COUNT(*) FROM volunteer_applications WHERE status = 'approved') as total_volunteers,
        (SELECT COUNT(*) FROM team_members WHERE is_active = 1) as total_team,
        (SELECT SUM(collected_amount) FROM projects WHERE status IN ('active', 'completed')) as total_donations
    ");
    $stmt->execute();
    $stats = $stmt->fetch();

    // Yönetim kurulu üyelerini çek
    $stmt = $pdo->prepare("SELECT * FROM team_members WHERE category = 'yonetim' AND is_active = 1 ORDER BY sort_order ASC");
    $stmt->execute();
    $team_members = $stmt->fetchAll();

    // Başarılı projeleri çek
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE status = 'completed' ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $completed_projects = $stmt->fetchAll();

} catch (PDOException $e) {
    $stats = ['total_projects' => 0, 'total_families' => 0, 'total_volunteers' => 0, 'total_team' => 0, 'total_donations' => 0];
    $team_members = [];
    $completed_projects = [];
    $site_settings = [];
}
?>



<!-- Simple Hero Section -->
<section class="hero-section bg-white">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-light text-dark mb-4">Hakkımızda</h1>
                <p class="lead text-muted mb-5">
                    2018'den beri toplumsal değişim için çalışan, sosyal sorumluluk bilinciyle hareket eden bir derneğiz.
                </p>
                <div class="row g-3 mb-5">
                    <div class="col-md-3">
                        <div class="stat-simple">
                            <h3 class="text-primary mb-1"><?= date('Y') - 2018 ?>+</h3>
                            <small class="text-muted">Yıl Deneyim</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-simple">
                            <h3 class="text-primary mb-1"><?= number_format($stats['total_families'] ?? 0) ?>+</h3>
                            <small class="text-muted">Aile</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-simple">
                            <h3 class="text-primary mb-1"><?= $stats['total_projects'] ?? 0 ?></h3>
                            <small class="text-muted">Proje</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-simple">
                            <h3 class="text-primary mb-1"><?= $stats['total_volunteers'] ?? 0 ?>+</h3>
                            <small class="text-muted">Gönüllü</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Simple Mission & Vision -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="simple-card h-100">
                    <div class="simple-card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary text-white me-3">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h3 class="h4 mb-0">Misyonumuz</h3>
                        </div>
                        <p class="text-muted">
                            <?= nl2br(htmlspecialchars($site_settings['mission'] ?? 'Misyon bilgisi yükleniyor...')) ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="simple-card h-100">
                    <div class="simple-card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-success text-white me-3">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3 class="h4 mb-0">Vizyonumuz</h3>
                        </div>
                        <p class="text-muted">
                            <?= nl2br(htmlspecialchars($site_settings['vision'] ?? 'Vizyon bilgisi yükleniyor...')) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kuruluş İlkelerimiz -->
<section class="py-5" style="background-color: #fafafa;">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h1 fw-light text-dark mb-3">Kuruluş İlkelerimiz</h2>
                <p class="lead text-muted mb-0" style="font-weight: 400;">
                    Derneğimizin temelini oluşturan değerler
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php foreach ($about_content['principles'] as $key => $principle): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm" style="transition: transform 0.3s ease;">
                    <div class="card-body p-4 text-center">
                        <!-- Icon -->
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light" 
                                 style="width: 80px; height: 80px;">
                                <i class="<?= $principle['icon'] ?> fa-2x text-primary"></i>
                            </div>
                        </div>
                        
                        <!-- Title -->
                        <h5 class="fw-semibold text-dark mb-3" style="line-height: 1.4;">
                            <?= $principle['title'] ?>
                        </h5>
                        
                        <!-- Description -->
                        <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                            <?= $principle['description'] ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Simple Values -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h1 fw-light text-dark mb-3">Değerlerimiz</h2>
                <p class="lead text-muted mb-0" style="font-weight: 400;">
                    Çalışmalarımızı yönlendiren temel değerlerimiz
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-4" 
                         style="width: 70px; height: 70px;">
                        <i class="fas fa-hand-holding-heart fa-lg text-primary"></i>
                    </div>
                    <h5 class="fw-semibold text-dark mb-3">Şefkat</h5>
                    <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                        Her bireye eşit mesafede, sevgi ve şefkatle yaklaşırız.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-4" 
                         style="width: 70px; height: 70px;">
                        <i class="fas fa-eye fa-lg text-primary"></i>
                    </div>
                    <h5 class="fw-semibold text-dark mb-3">Şeffaflık</h5>
                    <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                        Tüm faaliyetlerimizi açık ve şeffaf şekilde yürütürüz.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-4" 
                         style="width: 70px; height: 70px;">
                        <i class="fas fa-shield-alt fa-lg text-primary"></i>
                    </div>
                    <h5 class="fw-semibold text-dark mb-3">Güvenilirlik</h5>
                    <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                        Verilen sözlerin tutulduğu, güvene dayalı ilişkiler kurarız.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-4" 
                         style="width: 70px; height: 70px;">
                        <i class="fas fa-users fa-lg text-primary"></i>
                    </div>
                    <h5 class="fw-semibold text-dark mb-3">Dayanışma</h5>
                    <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                        Birlikte daha güçlü olduğumuzun bilinciyle hareket ederiz.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tarihçe -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <h2>Tarihçemiz</h2>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-date">2018</div>
                        <div class="timeline-content">
                            <h5>Kuruluş</h5>
                            <p>Necat Derneği, sosyal yardımlaşma amacıyla kuruldu.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-date">2019</div>
                        <div class="timeline-content">
                            <h5>İlk Projeler</h5>
                            <p>Eğitim burs programı ve gıda yardımı projeleri başlatıldı.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-date">2020</div>
                        <div class="timeline-content">
                            <h5>Pandemi Desteği</h5>
                            <p>COVID-19 salgını döneminde acil yardım programları hayata geçirildi.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-date">2021-2024</div>
                        <div class="timeline-content">
                            <h5>Büyüme</h5>
                            <p>Sağlık, eğitim ve afet yardımı alanlarında projeler genişletildi.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <img src="assets/images/history.jpg" alt="Tarihçemiz" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Faaliyet Alanlarımız -->
<section class="py-5" style="background-color: #fafafa;">
    <div class="container">
        <!-- Section Header -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h1 fw-light text-dark mb-3">Faaliyet Alanlarımız</h2>
                <p class="lead text-muted mb-0" style="font-weight: 400;">
                    Toplumun farklı kesimlerine ulaşarak geniş bir yelpazede hizmet sunuyoruz
                </p>
            </div>
        </div>
        
        <!-- Activity Cards Grid -->
        <div class="row g-4">
            <?php 
            // Faaliyet alanları verisi
            $activities_data = [
                [
                    'title' => 'Yetim ve Kimsesiz Aile Yardımları',
                    'description' => 'Yetim, yoksul ve kimsesiz ailelere temel ihtiyaçlarını karşılayarak destek oluyoruz.',
                    'icon' => 'fas fa-home',
                    'stats' => '500+ Aile'
                ],
                [
                    'title' => 'Eğitim Desteği',
                    'description' => 'Yetim ve yoksul öğrencilere burs ve kırtasiye yardımı yaparak eğitimlerini destekliyoruz.',
                    'icon' => 'fas fa-graduation-cap',
                    'stats' => '250+ Öğrenci'
                ],
                [
                    'title' => 'Ramazan Organizasyonları',
                    'description' => 'Ramazan ayında yoksul ailelere iftar yemeği organizasyonları düzenliyoruz.',
                    'icon' => 'fas fa-moon',
                    'stats' => '2000+ İftar'
                ],
                [
                    'title' => 'Evlilik Yardımları',
                    'description' => 'Maddi imkânsızlıktan evlenemeyen gençlere evlilik yardımları yapıyoruz.',
                    'icon' => 'fas fa-rings-wedding',
                    'stats' => '75+ Çift'
                ],
                [
                    'title' => 'Sağlık Hizmetleri',
                    'description' => 'Kurban kesimleri, dağıtımı ve hastalara kan temin etme gibi sağlık destekleri sağlıyoruz.',
                    'icon' => 'fas fa-heartbeat',
                    'stats' => '300+ Hasta'
                ],
                [
                    'title' => 'Sosyal Aktiviteler',
                    'description' => 'Yoksul aileler için çeşitli organizasyonlar düzenleyerek manevi eğitimler veriyoruz.',
                    'icon' => 'fas fa-users',
                    'stats' => '150+ Etkinlik'
                ]
            ];
            
            foreach ($activities_data as $index => $activity): 
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm" style="transition: transform 0.3s ease;">
                    <div class="card-body p-4 text-center">
                        <!-- Icon -->
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light" 
                                 style="width: 80px; height: 80px;">
                                <i class="<?= $activity['icon'] ?> fa-2x text-primary"></i>
                            </div>
                        </div>
                        
                        <!-- Title -->
                        <h5 class="fw-semibold text-dark mb-3" style="line-height: 1.4;">
                            <?= $activity['title'] ?>
                        </h5>
                        
                        <!-- Description -->
                        <p class="text-muted mb-4" style="font-size: 0.95rem; line-height: 1.6;">
                            <?= $activity['description'] ?>
                        </p>
                        
                        <!-- Stats -->
                        <div class="mt-auto">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                <?= $activity['stats'] ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Simple Statistics -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="h3 fw-light">Rakamlarla Başarılarımız</h2>
        </div>
        
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-3">
                    <h3 class="text-primary mb-1">25</h3>
                    <small class="text-muted">Tamamlanan Proje</small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-3">
                    <h3 class="text-primary mb-1">500+</h3>
                    <small class="text-muted">Yardım Ulaştırılan Aile</small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-3">
                    <h3 class="text-primary mb-1">120</h3>
                    <small class="text-muted">Aktif Gönüllü</small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-3">
                    <h3 class="text-primary mb-1">150K ₺</h3>
                    <small class="text-muted">Toplam Bağış</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Simple CTA -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h3 class="fw-light mb-4">Birlikte Daha Güçlü Olalım</h3>
                <p class="mb-4">Sen de bu anlamlı yolculuğumuzda yer al.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="index.php?page=volunteer" class="btn btn-light">Gönüllü Ol</a>
                    <a href="index.php?page=donate" class="btn btn-outline-light">Bağış Yap</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CSS for Timeline -->
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--primary-color);
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -23px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: var(--primary-color);
    border: 3px solid white;
    box-shadow: 0 0 0 3px var(--primary-color);
}

.timeline-date {
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 5px;
}

.timeline-content h5 {
    margin-bottom: 10px;
    color: var(--dark-color);
}

.timeline-content p {
    margin-bottom: 0;
    color: var(--secondary-color);
}
</style>
