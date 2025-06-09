<?php
// İçerik kataloğunu yükle
require_once 'includes/content_catalog.php';

// Hakkımızda sayfası içeriğini al
$about_content = getContentForPage('about');
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
                    <div class="col-md-4">
                        <div class="stat-simple">
                            <h3 class="text-primary mb-1">6+</h3>
                            <small class="text-muted">Yıl Deneyim</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-simple">
                            <h3 class="text-primary mb-1">500+</h3>
                            <small class="text-muted">Aile</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-simple">
                            <h3 class="text-primary mb-1">25</h3>
                            <small class="text-muted">Proje</small>
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
                            <?= nl2br($about_content['mission']) ?>
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
                            <?= $about_content['additional']['vision'] ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kuruluş İlkelerimiz -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Kuruluş İlkelerimiz</h2>
            <p class="lead">Derneğimizin temelini oluşturan değerler</p>
        </div>
        
        <div class="row">
            <?php foreach ($about_content['principles'] as $key => $principle): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="principle-card">
                    <div class="principle-icon">
                        <i class="<?= $principle['icon'] ?> fa-2x text-primary"></i>
                    </div>
                    <h5 class="principle-title"><?= $principle['title'] ?></h5>
                    <p class="principle-description"><?= $principle['description'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Simple Values -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="h3 fw-light">Değerlerimiz</h2>
            <p class="text-muted">Çalışmalarımızı yönlendiren temel değerlerimiz</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="icon-circle bg-light text-primary mx-auto mb-3">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h5 class="h6">Şefkat</h5>
                    <p class="small text-muted">Her bireye eşit mesafede, sevgi ve şefkatle yaklaşırız.</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="icon-circle bg-light text-info mx-auto mb-3">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h5 class="h6">Şeffaflık</h5>
                    <p class="small text-muted">Tüm faaliyetlerimizi açık ve şeffaf şekilde yürütürüz.</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="icon-circle bg-light text-success mx-auto mb-3">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="h6">Güvenilirlik</h5>
                    <p class="small text-muted">Verilen sözlerin tutulduğu, güvene dayalı ilişkiler kurarız.</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="icon-circle bg-light text-warning mx-auto mb-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5 class="h6">Dayanışma</h5>
                    <p class="small text-muted">Birlikte daha güçlü olduğumuzun bilinciyle hareket ederiz.</p>
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

<!-- Simple Activity Areas -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="h3 fw-light">Faaliyet Alanlarımız</h2>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <div class="icon-circle bg-primary text-white mx-auto mb-2">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h6 class="small fw-bold">Eğitim</h6>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <div class="icon-circle bg-danger text-white mx-auto mb-2">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h6 class="small fw-bold">Sağlık</h6>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <div class="icon-circle bg-warning text-white mx-auto mb-2">
                        <i class="fas fa-bread-slice"></i>
                    </div>
                    <h6 class="small fw-bold">Gıda Yardımı</h6>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <div class="icon-circle bg-info text-white mx-auto mb-2">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h6 class="small fw-bold">Afet Yardımı</h6>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <div class="icon-circle bg-success text-white mx-auto mb-2">
                        <i class="fas fa-home"></i>
                    </div>
                    <h6 class="small fw-bold">Sosyal Destek</h6>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <div class="icon-circle bg-secondary text-white mx-auto mb-2">
                        <i class="fas fa-female"></i>
                    </div>
                    <h6 class="small fw-bold">Kadın Destekleme</h6>
                </div>
            </div>
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
