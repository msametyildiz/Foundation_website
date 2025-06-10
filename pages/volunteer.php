<?php
// Veritabanından gönüllü verileri
try {
    // Gönüllü başvuru formu işleme
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'volunteer_apply') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $age = (int)($_POST['age'] ?? 0);
        $profession = trim($_POST['profession'] ?? '');
        $experience = trim($_POST['experience'] ?? '');
        $availability = trim($_POST['availability'] ?? '');
        $interests = trim($_POST['interests'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (!empty($name) && !empty($email) && !empty($phone)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO volunteer_applications (name, email, phone, age, profession, experience, availability, interests, message, status, ip_address, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'new', ?, NOW())");
                $stmt->execute([$name, $email, $phone, $age, $profession, $experience, $availability, $interests, $message, $_SERVER['REMOTE_ADDR'] ?? '']);
                
                $success_message = "Gönüllü başvurunuz başarıyla alınmıştır. En kısa sürede sizinle iletişime geçeceğiz.";
            } catch (PDOException $e) {
                $error_message = "Başvuru gönderilirken bir hata oluştu. Lütfen tekrar deneyin.";
            }
        } else {
            $error_message = "Lütfen tüm zorunlu alanları doldurunuz.";
        }
    }

    // Gönüllü istatistikleri
    $stmt = $pdo->prepare("SELECT 
        COUNT(*) as total_volunteers,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as active_volunteers,
        SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as pending_applications
        FROM volunteer_applications");
    $stmt->execute();
    $volunteer_stats = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $volunteer_stats = ['total_volunteers' => 0, 'active_volunteers' => 0, 'pending_applications' => 0];
}
?>

// Gönüllü alanları
$volunteer_areas = [
    [
        'icon' => 'fas fa-hands-helping',
        'title' => 'Acil Yardım',
        'description' => 'Acil durumlarda hızla müdahale eden ekibimizin bir parçası olun.',
        'skills' => ['İlk yardım', 'Kriz yönetimi', 'Hızlı karar verme']
    ],
    [
        'icon' => 'fas fa-graduation-cap',
        'title' => 'Eğitim',
        'description' => 'Çocuklar ve gençlere eğitim desteği sağlayarak geleceği şekillendirin.',
        'skills' => ['Öğretmenlik', 'Ders verme', 'Sabır']
    ],
    [
        'icon' => 'fas fa-laptop-code',
        'title' => 'Teknoloji',
        'description' => 'Dijital dönüşümümüzde bize destek olun ve teknik becerilerinizi paylaşın.',
        'skills' => ['Web tasarım', 'Programlama', 'Sosyal medya']
    ],
    [
        'icon' => 'fas fa-camera',
        'title' => 'Medya ve İletişim',
        'description' => 'Hikayelerimizi anlatan içerikler üretin ve sesimizi duyurun.',
        'skills' => ['Fotoğrafçılık', 'Video editing', 'Yazma']
    ],
    [
        'icon' => 'fas fa-heartbeat',
        'title' => 'Sağlık',
        'description' => 'Sağlık profesyoneli olarak ihtiyaç sahiplerine ulaşın.',
        'skills' => ['Tıp', 'Hemşirelik', 'Psikoloji']
    ],
    [
        'icon' => 'fas fa-chart-line',
        'title' => 'Proje Yönetimi',
        'description' => 'Projelerimizin etkin yürütülmesinde organizasyon becerilerinizi kullanın.',
        'skills' => ['Planlama', 'Koordinasyon', 'Liderlik']
    ]
];
?>

<div class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-3">Gönüllü Ol</h1>
                <p class="lead mb-0">İyiliğin yayılmasında bize katılın. Her el, her kalp değerlidir.</p>
            </div>
            <div class="col-lg-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent justify-content-lg-end">
                        <li class="breadcrumb-item"><a href="/" class="text-white">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Gönüllü Ol</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Gönüllü İstatistikleri -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h3 class="stats-number" data-target="<?= $volunteer_stats['total_volunteers'] ?? 0 ?>">0</h3>
                    <p class="stats-label mb-0">Toplam Gönüllü</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-user-check fa-3x text-success"></i>
                    </div>
                    <h3 class="stats-number" data-target="<?= $volunteer_stats['active_volunteers'] ?? 0 ?>">0</h3>
                    <p class="stats-label mb-0">Aktif Gönüllü</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mx-auto">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-clock fa-3x text-warning"></i>
                    </div>
                    <h3 class="stats-number" data-target="<?= $volunteer_stats['pending_applications'] ?? 0 ?>">0</h3>
                    <p class="stats-label mb-0">Bekleyen Başvuru</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Motivasyon Soruları -->
<section class="py-5 bg-gradient">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-10 mx-auto text-center">
                <h2 class="section-title text-white mb-4">Size Soru Sormak İstiyoruz</h2>
                <p class="section-subtitle text-white opacity-75">Kalbinizin sesini dinleyin ve bu sorulara samimi cevaplar verin</p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php foreach ($volunteer_questions as $index => $question): ?>
            <div class="col-lg-6">
                <div class="motivation-card bg-white rounded shadow p-4 h-100">
                    <div class="d-flex align-items-start">
                        <div class="motivation-icon me-3">
                            <i class="<?= $question['icon'] ?> fa-2x text-primary"></i>
                        </div>
                        <div class="motivation-content">
                            <span class="question-number badge bg-primary mb-2"><?= $index + 1 ?></span>
                            <p class="question-text mb-0"><?= $question['question'] ?></p>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-tag me-1"></i>
                                <?= ucfirst(str_replace('_', ' ', $question['category'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto text-center">
                <div class="cta-box bg-white rounded shadow p-4">
                    <h4 class="text-primary mb-3">Bu sorulara "EVET" diyorsanız...</h4>
                    <p class="mb-4">Sizin de aramızda yeriniz var! Gönüllü başvuru formumuzu doldurun ve bu güzel yolculuğa katılın.</p>
                    <a href="#volunteer-form" class="btn btn-primary btn-lg">
                        <i class="fas fa-hand-holding-heart me-2"></i>
                        Gönüllü Başvurusu Yap
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Neden Gönüllü Olmalısınız -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Neden Gönüllü Olmalısınız?</h2>
                <p class="section-subtitle">Gönüllülük sadece başkalarına yardım etmek değil, aynı zamanda kişisel gelişiminizi desteklemektir</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-heart fa-3x text-danger"></i>
                    </div>
                    <h5 class="feature-title">Fark Yaratın</h5>
                    <p class="feature-description">İhtiyaç sahiplerinin hayatına dokunarak toplumda gerçek bir değişim yaratın.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h5 class="feature-title">Yeni İnsanlarla Tanışın</h5>
                    <p class="feature-description">Aynı değerleri paylaşan insanlarla tanışın ve kalıcı dostluklar kurun.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-chart-line fa-3x text-success"></i>
                    </div>
                    <h5 class="feature-title">Kendinizi Geliştirin</h5>
                    <p class="feature-description">Yeni beceriler kazanın, deneyim edinın ve kişisel gelişiminizi destekleyin.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gönüllü Alanları -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Gönüllü Alanları</h2>
                <p class="section-subtitle">Yetenekleriniz ve ilgi alanlarınıza uygun gönüllülük fırsatları</p>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($volunteer_areas as $area): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="volunteer-area-card h-100 bg-white rounded shadow-sm p-4">
                        <div class="area-icon mb-3">
                            <i class="<?= $area['icon'] ?> fa-3x text-primary"></i>
                        </div>
                        <h5 class="area-title mb-3"><?= $area['title'] ?></h5>
                        <p class="area-description text-muted mb-3"><?= $area['description'] ?></p>
                        <div class="required-skills">
                            <h6 class="skills-title mb-2">Aranan Özellikler:</h6>
                            <div class="skills-list">
                                <?php foreach ($area['skills'] as $skill): ?>
                                    <span class="badge bg-light text-dark me-1 mb-1"><?= $skill ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Gönüllü Başvuru Formu -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="form-container bg-white rounded shadow p-5">
                    <div class="text-center mb-4">
                        <h2 class="section-title">Gönüllü Başvuru Formu</h2>
                        <p class="section-subtitle">Bizimle birlikte iyiliği yaymaya hazır mısınız?</p>
                    </div>

                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= $success_message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= $error_message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" id="volunteerForm" novalidate>
                        <input type="hidden" name="action" value="volunteer_apply">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Ad Soyad *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Lütfen adınızı ve soyadınızı giriniz.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">E-posta *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Lütfen geçerli bir e-posta adresi giriniz.</div>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Telefon *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                                <div class="invalid-feedback">Lütfen geçerli bir telefon numarası giriniz.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label">Doğum Tarihi *</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                                <div class="invalid-feedback">Lütfen doğum tarihinizi giriniz.</div>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label for="occupation" class="form-label">Meslek</label>
                                <input type="text" class="form-control" id="occupation" name="occupation">
                            </div>
                            <div class="col-md-6">
                                <label for="availability" class="form-label">Müsaitlik Durumu *</label>
                                <select class="form-select" id="availability" name="availability" required>
                                    <option value="">Seçiniz</option>
                                    <option value="weekdays">Hafta içi</option>
                                    <option value="weekends">Hafta sonu</option>
                                    <option value="evenings">Akşam saatleri</option>
                                    <option value="flexible">Esnek</option>
                                </select>
                                <div class="invalid-feedback">Lütfen müsaitlik durumunuzu seçiniz.</div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="skills" class="form-label">Yetenekler ve Beceriler</label>
                            <input type="text" class="form-control" id="skills" name="skills" 
                                   placeholder="Örn: İlk yardım, fotoğrafçılık, öğretmenlik...">
                            <div class="form-text">Sahip olduğunuz özel yetenekleri ve becerileri yazınız.</div>
                        </div>

                        <div class="mt-3">
                            <label for="experience" class="form-label">Gönüllülük Deneyimi</label>
                            <textarea class="form-control" id="experience" name="experience" rows="3" 
                                      placeholder="Daha önce katıldığınız gönüllü çalışmalar..."></textarea>
                        </div>

                        <div class="mt-3">
                            <label for="motivation" class="form-label">Neden Gönüllü Olmak İstiyorsunuz? *</label>
                            <textarea class="form-control" id="motivation" name="motivation" rows="4" 
                                      placeholder="Motivasyonunuzu paylaşın..." required></textarea>
                            <div class="invalid-feedback">Lütfen motivasyonunuzu paylaşınız.</div>
                        </div>

                        <div class="mt-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="consent" required>
                                <label class="form-check-label" for="consent">
                                    <a href="/documents" target="_blank">Gönüllü Çalışma Şartları</a>'nı okudum ve kabul ediyorum. *
                                </label>
                                <div class="invalid-feedback">Lütfen gönüllü çalışma şartlarını kabul ediniz.</div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>
                                Başvuru Gönder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Süreç Bilgileri -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Başvuru Süreci</h2>
                <p class="section-subtitle">Gönüllü başvurunuzdan sonra neler olacağını öğrenin</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="process-step text-center">
                    <div class="step-number">1</div>
                    <div class="step-icon mb-3">
                        <i class="fas fa-file-alt fa-2x text-primary"></i>
                    </div>
                    <h5 class="step-title">Başvuru</h5>
                    <p class="step-description">Formu doldurun ve başvurunuzu gönderin</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step text-center">
                    <div class="step-number">2</div>
                    <div class="step-icon mb-3">
                        <i class="fas fa-search fa-2x text-primary"></i>
                    </div>
                    <h5 class="step-title">Değerlendirme</h5>
                    <p class="step-description">Başvurunuz 3-5 iş günü içinde değerlendirilir</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step text-center">
                    <div class="step-number">3</div>
                    <div class="step-icon mb-3">
                        <i class="fas fa-phone fa-2x text-primary"></i>
                    </div>
                    <h5 class="step-title">Görüşme</h5>
                    <p class="step-description">Kısa bir telefon görüşmesi yapılır</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step text-center">
                    <div class="step-number">4</div>
                    <div class="step-icon mb-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <h5 class="step-title">Katılım</h5>
                    <p class="step-description">Ekibimize katılıp fark yaratmaya başlayın</p>
                </div>
            </div>
        </div>
    </div>
</section>
