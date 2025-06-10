<?php
require_once '../config/database.php';

// Takım üyelerini getir
$stmt = $db->prepare("SELECT * FROM team_members WHERE status = 'active' ORDER BY position_order ASC, name ASC");
$stmt->execute();
$team_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Takımı pozisyonlara göre grupla
$team_by_position = [];
foreach ($team_members as $member) {
    $position = $member['position'];
    if (!isset($team_by_position[$position])) {
        $team_by_position[$position] = [];
    }
    $team_by_position[$position][] = $member;
}

// Pozisyon sıralaması
$position_order = [
    'Yönetim Kurulu' => ['icon' => 'fas fa-users-cog', 'color' => 'primary'],
    'Üst Yönetim' => ['icon' => 'fas fa-user-tie', 'color' => 'success'],
    'Departman Yöneticileri' => ['icon' => 'fas fa-user-friends', 'color' => 'info'],
    'Uzmanlar' => ['icon' => 'fas fa-user-graduate', 'color' => 'warning'],
    'Koordinatörler' => ['icon' => 'fas fa-user-check', 'color' => 'secondary'],
    'Gönüllüler' => ['icon' => 'fas fa-hands-helping', 'color' => 'danger']
];

// Takım istatistikleri
$stmt = $db->prepare("SELECT 
    COUNT(*) as total_members,
    SUM(CASE WHEN position LIKE '%Yönetim%' OR position = 'Genel Koordinatör' THEN 1 ELSE 0 END) as management_count,
    SUM(CASE WHEN position LIKE '%Uzman%' OR position LIKE '%Koordinatör%' THEN 1 ELSE 0 END) as specialist_count,
    COUNT(DISTINCT department) as departments
    FROM team_members WHERE status = 'active'");
$stmt->execute();
$team_stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-3">Takımımız</h1>
                <p class="lead mb-0">İyiliği yaymak için bir araya gelen değerli ekibimizle tanışın.</p>
            </div>
            <div class="col-lg-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent justify-content-lg-end">
                        <li class="breadcrumb-item"><a href="/" class="text-white">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Takım</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Takım İstatistikleri -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h3 class="stats-number" data-target="<?= $team_stats['total_members'] ?? 0 ?>">0</h3>
                    <p class="stats-label mb-0">Takım Üyesi</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-user-tie fa-3x text-success"></i>
                    </div>
                    <h3 class="stats-number" data-target="<?= $team_stats['management_count'] ?? 0 ?>">0</h3>
                    <p class="stats-label mb-0">Yönetici</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-user-graduate fa-3x text-info"></i>
                    </div>
                    <h3 class="stats-number" data-target="<?= $team_stats['specialist_count'] ?? 0 ?>">0</h3>
                    <p class="stats-label mb-0">Uzman</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-building fa-3x text-warning"></i>
                    </div>
                    <h3 class="stats-number" data-target="<?= $team_stats['departments'] ?? 0 ?>">0</h3>
                    <p class="stats-label mb-0">Departman</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Takım Üyeleri -->
<?php foreach ($position_order as $position => $config): ?>
    <?php if (isset($team_by_position[$position]) && !empty($team_by_position[$position])): ?>
        <section class="py-5 <?= array_search($position, array_keys($position_order)) % 2 === 0 ? '' : 'bg-light' ?>">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-8 mx-auto text-center">
                        <h2 class="section-title">
                            <i class="<?= $config['icon'] ?> me-3 text-<?= $config['color'] ?>"></i>
                            <?= $position ?>
                        </h2>
                        <div class="position-badge">
                            <span class="badge bg-<?= $config['color'] ?> badge-lg">
                                <?= count($team_by_position[$position]) ?> Üye
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <?php foreach ($team_by_position[$position] as $member): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="team-member-card bg-white rounded shadow-sm h-100">
                                <div class="member-photo">
                                    <img src="<?= !empty($member['photo']) ? '../uploads/team/' . $member['photo'] : '../assets/images/default-avatar.jpg' ?>" 
                                         alt="<?= htmlspecialchars($member['name']) ?>" 
                                         class="img-fluid">
                                    <?php if (!empty($member['social_linkedin']) || !empty($member['social_twitter']) || !empty($member['email'])): ?>
                                        <div class="member-social">
                                            <?php if (!empty($member['email'])): ?>
                                                <a href="mailto:<?= $member['email'] ?>" class="social-link">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($member['social_linkedin'])): ?>
                                                <a href="<?= $member['social_linkedin'] ?>" target="_blank" class="social-link">
                                                    <i class="fab fa-linkedin-in"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($member['social_twitter'])): ?>
                                                <a href="<?= $member['social_twitter'] ?>" target="_blank" class="social-link">
                                                    <i class="fab fa-twitter"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="member-info p-4">
                                    <h5 class="member-name"><?= htmlspecialchars($member['name']) ?></h5>
                                    <p class="member-position text-<?= $config['color'] ?> mb-2">
                                        <?= htmlspecialchars($member['position']) ?>
                                    </p>
                                    <?php if (!empty($member['department'])): ?>
                                        <p class="member-department text-muted mb-3">
                                            <i class="fas fa-building me-1"></i>
                                            <?= htmlspecialchars($member['department']) ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if (!empty($member['bio'])): ?>
                                        <p class="member-bio text-muted">
                                            <?= htmlspecialchars(substr($member['bio'], 0, 120)) ?>
                                            <?= strlen($member['bio']) > 120 ? '...' : '' ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if (!empty($member['expertise'])): ?>
                                        <div class="member-expertise mt-3">
                                            <?php 
                                            $expertise_list = explode(',', $member['expertise']);
                                            foreach (array_slice($expertise_list, 0, 3) as $skill): 
                                            ?>
                                                <span class="badge bg-light text-dark me-1 mb-1">
                                                    <?= trim(htmlspecialchars($skill)) ?>
                                                </span>
                                            <?php endforeach; ?>
                                            <?php if (count($expertise_list) > 3): ?>
                                                <span class="badge bg-secondary">+<?= count($expertise_list) - 3 ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php endforeach; ?>

<!-- Takıma Katıl -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="join-team-section">
                    <div class="join-icon mb-4">
                        <i class="fas fa-user-plus fa-4x"></i>
                    </div>
                    <h2 class="join-title mb-3">Siz de Takımımıza Katılın!</h2>
                    <p class="join-description mb-4">
                        İyiliği yaymak için bizimle birlikte çalışmak ister misiniz? 
                        Yeteneklerinizi toplumsal fayda için kullanma fırsatı sizi bekliyor.
                    </p>
                    <div class="join-actions">
                        <a href="/volunteer" class="btn btn-light btn-lg me-3">
                            <i class="fas fa-hands-helping me-2"></i>
                            Gönüllü Ol
                        </a>
                        <a href="/contact" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-envelope me-2"></i>
                            İletişime Geç
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kurumsal Değerler -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Kurumsal Değerlerimiz</h2>
                <p class="section-subtitle">Ekibimizi bir arada tutan temel değerler</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="value-card text-center p-4 bg-white rounded shadow-sm h-100">
                    <div class="value-icon mb-3">
                        <i class="fas fa-heart fa-3x text-danger"></i>
                    </div>
                    <h5 class="value-title">Merhamet</h5>
                    <p class="value-description">İnsanlara karşı derin bir anlayış ve şefkat gösteririz.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="value-card text-center p-4 bg-white rounded shadow-sm h-100">
                    <div class="value-icon mb-3">
                        <i class="fas fa-handshake fa-3x text-primary"></i>
                    </div>
                    <h5 class="value-title">Güvenilirlik</h5>
                    <p class="value-description">Verdiğimiz sözleri tutmanın ve güvenilir olmanın önemini biliriz.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="value-card text-center p-4 bg-white rounded shadow-sm h-100">
                    <div class="value-icon mb-3">
                        <i class="fas fa-balance-scale fa-3x text-success"></i>
                    </div>
                    <h5 class="value-title">Adalet</h5>
                    <p class="value-description">Herkesi eşit görür, ayrım yapmadan yardım ederiz.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="value-card text-center p-4 bg-white rounded shadow-sm h-100">
                    <div class="value-icon mb-3">
                        <i class="fas fa-lightbulb fa-3x text-warning"></i>
                    </div>
                    <h5 class="value-title">İnovasyon</h5>
                    <p class="value-description">Sürekli gelişim ve yenilikçi çözümler üretme prensibimiz.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Takım Başarıları -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Takım Başarılarımız</h2>
                <p class="section-subtitle">Birlikte elde ettiğimiz önemli başarılar</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="achievement-card text-center p-4 bg-gradient-primary text-white rounded">
                    <div class="achievement-icon mb-3">
                        <i class="fas fa-trophy fa-3x"></i>
                    </div>
                    <h4 class="achievement-title">Yılın Derneği Ödülü</h4>
                    <p class="achievement-description">2023 yılında en başarılı sivil toplum kuruluşu seçildik.</p>
                    <small class="achievement-date">2023</small>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="achievement-card text-center p-4 bg-gradient-success text-white rounded">
                    <div class="achievement-icon mb-3">
                        <i class="fas fa-medal fa-3x"></i>
                    </div>
                    <h4 class="achievement-title">Sosyal Sorumluluk Ödülü</h4>
                    <p class="achievement-description">Toplumsal etkimiz nedeniyle sosyal sorumluluk ödülü aldık.</p>
                    <small class="achievement-date">2022</small>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mx-auto">
                <div class="achievement-card text-center p-4 bg-gradient-info text-white rounded">
                    <div class="achievement-icon mb-3">
                        <i class="fas fa-star fa-3x"></i>
                    </div>
                    <h4 class="achievement-title">Şeffaflık Sertifikası</h4>
                    <p class="achievement-description">Mali şeffaflığımız nedeniyle güven sertifikası aldık.</p>
                    <small class="achievement-date">2021</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- İletişim Çağrısı -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="contact-cta p-5 bg-white rounded shadow">
                    <h3 class="cta-title mb-3">Bizimle Çalışmak İster misiniz?</h3>
                    <p class="cta-description mb-4">
                        Açık pozisyonlarımız için başvuru yapabilir veya 
                        gönüllü olarak ekibimize katılabilirsiniz.
                    </p>
                    <div class="cta-buttons">
                        <a href="mailto:kariyer@necatdernegi.org" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-briefcase me-2"></i>
                            Kariyer Fırsatları
                        </a>
                        <a href="/volunteer" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-hand-heart me-2"></i>
                            Gönüllü Başvurusu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Üye kartlarına hover efektleri
document.addEventListener('DOMContentLoaded', function() {
    const memberCards = document.querySelectorAll('.team-member-card');
    
    memberCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Sosyal medya linklerine hover efektleri
    const socialLinks = document.querySelectorAll('.social-link');
    
    socialLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.2)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
