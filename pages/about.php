<?php
// İçerik kataloğunu yükle
require_once 'includes/content_catalog.php';

// Hakkımızda sayfası içeriğini al
$about_content = getContentForPage('about');
?>

<?php
// İçerik kataloğunu yükle
require_once 'includes/content_catalog.php';

// Hakkımızda sayfası içeriğini al
$about_content = getContentForPage('about');
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold text-shadow mb-4">Hakkımızda</h1>
                <p class="lead mb-4">
                    Yardım eli uzatan, umut dağıtan bir toplum için birlikte çalışıyoruz. 
                    Necat Derneği olarak, sosyal sorumluluk bilinciyle hareket ediyoruz.
                </p>
            </div>
            <div class="col-lg-6">
                <img src="assets/images/about-hero.jpg" alt="Hakkımızda" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Misyon Vizyon -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-bullseye fa-3x text-primary"></i>
                        </div>
                        <h3 class="card-title text-primary">Misyonumuz</h3>
                        <div class="card-text">
                            <?= nl2br($about_content['mission']) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-eye fa-3x text-success"></i>
                        </div>
                        <h3 class="card-title text-success">Vizyonumuz</h3>
                        <p class="card-text">
                            <?= $about_content['additional']['vision'] ?>
                        </p>
                        <div class="mt-3">
                            <span class="badge bg-success"><?= $about_content['additional']['motto'] ?></span>
                        </div>
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

<!-- Değerlerimiz -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Değerlerimiz</h2>
            <p class="lead">Çalışmalarımızı yönlendiren temel değerlerimiz</p>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-hand-holding-heart fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Şefkat</h5>
                        <p class="card-text">
                            Her bireye eşit mesafede, sevgi ve şefkatle yaklaşırız.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-eye fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title">Şeffaflık</h5>
                        <p class="card-text">
                            Tüm faaliyetlerimizi açık ve şeffaf şekilde yürütürüz.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-shield-alt fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Güvenilirlik</h5>
                        <p class="card-text">
                            Verilen sözlerin tutulduğu, güvene dayalı ilişkiler kurarız.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-users fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title">Dayanışma</h5>
                        <p class="card-text">
                            Birlikte daha güçlü olduğumuzun bilinciyle hareket ederiz.
                        </p>
                    </div>
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

<!-- Faaliyet Alanları -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Faaliyet Alanlarımız</h2>
            <p class="lead">Topluma değer katmak için çalıştığımız alanlar</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <img src="assets/images/education.jpg" class="card-img-top" alt="Eğitim">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-graduation-cap text-primary me-2"></i>
                            Eğitim
                        </h5>
                        <p class="card-text">
                            Burs programları, okul öncesi eğitim desteği, kırtasiye yardımı 
                            ve eğitim materyali sağlama projeleri yürütüyoruz.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <img src="assets/images/health.jpg" class="card-img-top" alt="Sağlık">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-heartbeat text-danger me-2"></i>
                            Sağlık
                        </h5>
                        <p class="card-text">
                            Ücretsiz sağlık taramaları, ilaç yardımı ve sağlık eğitimi 
                            programları ile toplum sağlığına katkıda bulunuyoruz.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <img src="assets/images/food-aid.jpg" class="card-img-top" alt="Gıda Yardımı">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-bread-slice text-warning me-2"></i>
                            Gıda Yardımı
                        </h5>
                        <p class="card-text">
                            Gıda kolileri, sıcak yemek dağıtımı ve ramazan ayında 
                            iftar programları düzenliyoruz.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <img src="assets/images/disaster.jpg" class="card-img-top" alt="Afet Yardımı">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-hands-helping text-info me-2"></i>
                            Afet Yardımı
                        </h5>
                        <p class="card-text">
                            Doğal afetler ve acil durumlarda hızlı müdahale ekipleri 
                            ile ihtiyaç sahiplerine ulaşıyoruz.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <img src="assets/images/social.jpg" class="card-img-top" alt="Sosyal Destek">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-home text-success me-2"></i>
                            Sosyal Destek
                        </h5>
                        <p class="card-text">
                            Yaşlı bakımı, engelli destek programları ve sosyal 
                            rehabilitasyon faaliyetleri gerçekleştiriyoruz.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <img src="assets/images/women.jpg" class="card-img-top" alt="Kadın Destekleme">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-female text-purple me-2"></i>
                            Kadın Destekleme
                        </h5>
                        <p class="card-text">
                            Kadın istihdamı, meslek edindirme kursları ve 
                            girişimcilik destekleme programları yürütüyoruz.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- İstatistikler -->
<section class="stats-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Rakamlarla Necat Derneği</h2>
            <p class="lead">Bugüne kadar gerçekleştirdiklerimiz</p>
        </div>
        
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <span class="stat-number">25</span>
                    <span class="stat-label">Proje Tamamlandı</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Aileye Ulaştık</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <span class="stat-number">120</span>
                    <span class="stat-label">Aktif Gönüllü</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-item">
                    <span class="stat-number">150K ₺</span>
                    <span class="stat-label">Toplanan Bağış</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kurumsal Ortaklar -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Kurumsal Ortaklarımız</h2>
            <p class="lead">Birlikte güç oluşturduğumuz kurumlar</p>
        </div>
        
        <div class="row align-items-center">
            <div class="col-lg-2 col-md-4 col-6 mb-4 text-center">
                <img src="assets/images/partners/partner1.png" alt="Ortak 1" class="img-fluid" style="max-height: 80px;">
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-4 text-center">
                <img src="assets/images/partners/partner2.png" alt="Ortak 2" class="img-fluid" style="max-height: 80px;">
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-4 text-center">
                <img src="assets/images/partners/partner3.png" alt="Ortak 3" class="img-fluid" style="max-height: 80px;">
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-4 text-center">
                <img src="assets/images/partners/partner4.png" alt="Ortak 4" class="img-fluid" style="max-height: 80px;">
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-4 text-center">
                <img src="assets/images/partners/partner5.png" alt="Ortak 5" class="img-fluid" style="max-height: 80px;">
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-4 text-center">
                <img src="assets/images/partners/partner6.png" alt="Ortak 6" class="img-fluid" style="max-height: 80px;">
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
