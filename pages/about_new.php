<?php
/**
 * Necat Derneği - Hakkımızda Sayfası
 * Modern ve kapsamlı tasarım
 */

// İçerik katalogunu dahil et
require_once __DIR__ . '/../includes/content_catalog.php';

// Sayfa içeriğini al
$about_content = getContentForPage('about');
$principles = $about_content['principles'];
$islamic_values = $about_content['islamic_values'];
$timeline = $about_content['timeline'];
$team = $about_content['team'];
$stats = $about_content['stats'];
$mission = $about_content['mission'];
$additional = $about_content['additional'];
?>

<div class="about-page">
    <!-- Hero Bölümü -->
    <section class="about-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="container">
                <div class="hero-text">
                    <h1 class="hero-title">
                        <span class="title-main">Necat Derneği</span>
                        <span class="title-sub">Hakkımızda</span>
                    </h1>
                    <p class="hero-description">
                        <?= $additional['vision'] ?>
                    </p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?= $additional['established'] ?></span>
                            <span class="stat-label">Kuruluş Yılı</span>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <span class="stat-number"><?= count($stats) ?>+</span>
                            <span class="stat-label">Başarı Alanı</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    <!-- Misyon ve Vizyon -->
    <section class="mission-vision-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Misyonumuz</h2>
                <div class="title-decoration"></div>
            </div>
            
            <div class="mission-content">
                <div class="mission-text">
                    <p class="mission-main"><?= nl2br($mission) ?></p>
                    <div class="mission-motto">
                        <i class="fas fa-quote-left"></i>
                        <span><?= $additional['motto'] ?></span>
                        <i class="fas fa-quote-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- İslami Değerler -->
    <section class="islamic-values-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">İslami Değerlerimiz</h2>
                <p class="section-subtitle">Faaliyetlerimizin temelini oluşturan değerler</p>
                <div class="title-decoration"></div>
            </div>
            
            <div class="values-grid">
                <?php foreach($islamic_values as $key => $value): ?>
                <div class="value-card" data-aos="fade-up" data-aos-delay="<?= array_search($key, array_keys($islamic_values)) * 100 ?>">
                    <div class="value-icon <?= $value['color'] ?>">
                        <i class="<?= $value['icon'] ?>"></i>
                    </div>
                    <div class="value-content">
                        <h3 class="value-title"><?= $value['title'] ?></h3>
                        <p class="value-description"><?= $value['description'] ?></p>
                        <div class="value-verse">
                            <i class="fas fa-quote-left"></i>
                            <span><?= $value['verse'] ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Kuruluş İlkeleri -->
    <section class="principles-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Kuruluş İlkelerimiz</h2>
                <p class="section-subtitle">Hareket noktamızı oluşturan temel prensipler</p>
                <div class="title-decoration"></div>
            </div>
            
            <div class="principles-grid">
                <?php foreach($principles as $key => $principle): ?>
                <div class="principle-card" data-aos="zoom-in" data-aos-delay="<?= array_search($key, array_keys($principles)) * 50 ?>">
                    <div class="principle-icon">
                        <i class="<?= $principle['icon'] ?>"></i>
                    </div>
                    <h3 class="principle-title"><?= $principle['title'] ?></h3>
                    <p class="principle-description"><?= $principle['description'] ?></p>
                    <div class="principle-border"></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Tarihçe ve Zaman Çizelgesi -->
    <section class="timeline-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tarihçemiz</h2>
                <p class="section-subtitle">Kuruluşumuzdan bugüne kadar olan yolculuğumuz</p>
                <div class="title-decoration"></div>
            </div>
            
            <div class="timeline-container">
                <div class="timeline-line"></div>
                <?php foreach($timeline as $index => $event): ?>
                <div class="timeline-item <?= $index % 2 == 0 ? 'left' : 'right' ?>" data-aos="fade-<?= $index % 2 == 0 ? 'right' : 'left' ?>" data-aos-delay="<?= $index * 200 ?>">
                    <div class="timeline-content">
                        <div class="timeline-year"><?= $event['year'] ?></div>
                        <div class="timeline-icon">
                            <i class="<?= $event['icon'] ?>"></i>
                        </div>
                        <h3 class="timeline-title"><?= $event['title'] ?></h3>
                        <p class="timeline-description"><?= $event['description'] ?></p>
                    </div>
                    <div class="timeline-dot"></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- İstatistikler ve Başarılar -->
    <section class="achievements-section">
        <div class="achievements-overlay"></div>
        <div class="container">
            <div class="section-header">
                <h2 class="section-title white">Başarılarımız</h2>
                <p class="section-subtitle white">Sayılarla faaliyetlerimiz</p>
                <div class="title-decoration white"></div>
            </div>
            
            <div class="stats-grid">
                <?php foreach($stats as $index => $stat): ?>
                <div class="stat-card" data-aos="flip-up" data-aos-delay="<?= $index * 100 ?>">
                    <div class="stat-icon">
                        <i class="<?= $stat['icon'] ?>"></i>
                    </div>
                    <div class="stat-number" data-target="<?= str_replace(',', '', $stat['number']) ?>">0</div>
                    <div class="stat-label"><?= $stat['label'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Yönetim Kurulu -->
    <section class="team-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Yönetim Kurulumuz</h2>
                <p class="section-subtitle">Deneyimli ve alanında uzman ekibimiz</p>
                <div class="title-decoration"></div>
            </div>
            
            <div class="team-grid">
                <?php foreach($team as $index => $member): ?>
                <div class="team-card" data-aos="fade-up" data-aos-delay="<?= $index * 150 ?>">
                    <div class="team-image">
                        <img src="<?= $member['image'] ?>" alt="<?= $member['name'] ?>" onerror="this.src='assets/images/placeholder-team.jpg'">
                        <div class="team-overlay">
                            <div class="team-social">
                                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="social-link"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="team-info">
                        <h3 class="team-name"><?= $member['name'] ?></h3>
                        <p class="team-position"><?= $member['position'] ?></p>
                        <p class="team-description"><?= $member['description'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content" data-aos="zoom-in">
                <h2 class="cta-title">Bizimle İletişime Geçin</h2>
                <p class="cta-description">
                    Sorularınız için bize ulaşın veya gönüllü ekibimize katılın
                </p>
                <div class="cta-buttons">
                    <a href="index.php?page=contact" class="btn btn-primary">
                        <i class="fas fa-phone"></i>
                        İletişime Geç
                    </a>
                    <a href="index.php?page=volunteer" class="btn btn-outline">
                        <i class="fas fa-hands-helping"></i>
                        Gönüllü Ol
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Sayfa animasyonları
document.addEventListener('DOMContentLoaded', function() {
    // AOS kütüphanesi başlat
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    }
    
    // Sayaç animasyonu
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000;
        let start = 0;
        const increment = target / (duration / 16);
        
        function updateCounter() {
            start += increment;
            if (start < target) {
                element.textContent = Math.floor(start).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target.toLocaleString();
            }
        }
        
        updateCounter();
    }
    
    // Intersection Observer ile sayaçları tetikle
    const counterElements = document.querySelectorAll('.stat-number[data-target]');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                entry.target.classList.add('animated');
                animateCounter(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    counterElements.forEach(el => counterObserver.observe(el));
    
    // Paralaks efekti
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.about-hero');
        const achievements = document.querySelector('.achievements-section');
        
        if (hero) {
            const rate = scrolled * -0.5;
            hero.style.transform = `translateY(${rate}px)`;
        }
        
        if (achievements) {
            const achievementsTop = achievements.offsetTop;
            const achievementsHeight = achievements.offsetHeight;
            const windowHeight = window.innerHeight;
            
            if (scrolled + windowHeight > achievementsTop && scrolled < achievementsTop + achievementsHeight) {
                const rate = (scrolled - achievementsTop + windowHeight) * -0.3;
                achievements.style.backgroundPosition = `center ${rate}px`;
            }
        }
    });
});
</script>
