<?php
// Veritabanından gönüllü verileri
try {
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

// Gönüllü alanları
$volunteer_areas = [
    [
        'title' => 'Eğitim Destek',
        'description' => 'Çocukların eğitim hayatına destek olmak, ders verme, kırtasiye dağıtımı.',
        'icon' => 'fas fa-graduation-cap',
        'skills' => ['Öğretmenlik', 'Sabır', 'İletişim']
    ],
    [
        'title' => 'Saha Çalışması',
        'description' => 'Yardım dağıtımı, ihtiyaç tespiti ve sahada aktif görev alma.',
        'icon' => 'fas fa-hands-helping',
        'skills' => ['Fiziksel Güç', 'Organizasyon', 'Takım Çalışması']
    ],
    [
        'title' => 'Dijital Destek',
        'description' => 'Sosyal medya yönetimi, içerik üretimi, web tasarımı.',
        'icon' => 'fas fa-laptop',
        'skills' => ['Tasarım', 'İçerik Üretimi', 'Sosyal Medya']
    ],
    [
        'title' => 'Etkinlik Organizasyonu',
        'description' => 'Bağış toplama etkinlikleri, farkındalık kampanyaları düzenleme.',
        'icon' => 'fas fa-calendar-alt',
        'skills' => ['Organizasyon', 'Planlama', 'İletişim']
    ],
    [
        'title' => 'Çeviri ve Dil Desteği',
        'description' => 'Yabancı uyruklu ailelere tercümanlık ve dil desteği.',
        'icon' => 'fas fa-language',
        'skills' => ['Yabancı Dil', 'Kültürel Farkındalık', 'İletişim']
    ],
    [
        'title' => 'Sağlık Destek',
        'description' => 'Sağlık taramalarında yardım, hasta nakli ve destek.',
        'icon' => 'fas fa-heartbeat',
        'skills' => ['İlk Yardım', 'Sağlık Bilgisi', 'Empati']
    ]
];

// Motivasyon soruları
$volunteer_questions = [
    [
        'icon' => 'fas fa-heart',
        'question' => 'Başkalarına yardım etmek sizi mutlu ediyor mu?',
    ],
    [
        'icon' => 'fas fa-clock',
        'question' => 'Boş zamanlarınızı anlamlı işler için ayırmaya istekli misiniz?',
    ],
    [
        'icon' => 'fas fa-users',
        'question' => 'Takım çalışması yapmaktan hoşlanır mısınız?',
    ],
    [
        'icon' => 'fas fa-lightbulb',
        'question' => 'Toplumsal sorunlara çözüm üretmek ister misiniz?',
    ],
    [
        'icon' => 'fas fa-handshake',
        'question' => 'Farklı kültürlerden insanlarla çalışabilir misiniz?',
    ],
    [
        'icon' => 'fas fa-star',
        'question' => 'Kişisel gelişiminize katkıda bulunacak deneyimler arıyor musunuz?',
    ]
];
?>

<!-- Simple Hero Section (matching About page style) -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-3">Gönüllü Ol</h1>
                <p class="lead mb-4">
                    İyiliğin yayılmasında bize katılın. Her el, her kalp değerlidir.
                </p>
                
                
            </div>
        </div>
    </div>
</section>

<!-- Motivasyon Soruları -->
<section class="py-5" style="background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%);">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-10 mx-auto text-center">
                <span class="badge bg-white text-primary px-3 py-2 mb-3" style="color: #4ea674 !important;">Size Soru Sormak İstiyoruz</span>
                <h2 class="text-white mb-4">Kalbinizin Sesini Dinleyin</h2>
                <p class="text-white opacity-75">Bu sorulara samimi cevaplar verin ve gönüllülük serüveninize başlayın</p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php foreach ($volunteer_questions as $index => $question): ?>
            <div class="col-lg-6">
                <div class="card h-100 border-0 shadow-sm" style="backdrop-filter: blur(15px); background: rgba(255, 255, 255, 0.95);">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light" 
                                     style="width: 60px; height: 60px;">
                                    <i class="<?= $question['icon'] ?> fa-lg text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <span class="badge bg-primary mb-2"><?= $index + 1 ?></span>
                                <p class="mb-3 fw-medium"><?= $question['question'] ?></p>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto text-center">
                <div class="card border-0 shadow-lg" style="backdrop-filter: blur(15px); background: rgba(255, 255, 255, 0.95);">
                    <div class="card-body p-4">
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
    </div>
</section>

<!-- Neden Gönüllü Olmalısınız -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary px-3 py-2 mb-3">Neden Gönüllü Olmalısınız?</span>
            <h2>Gönüllülüğün Faydaları</h2>
            <p class="text-muted">Gönüllülük sadece başkalarına yardım etmek değil, aynı zamanda kişisel gelişiminizi desteklemektir</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-4" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-heart fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-semibold mb-3">Fark Yaratın</h5>
                    <p class="text-muted">İhtiyaç sahiplerinin hayatına dokunarak toplumda gerçek bir değişim yaratın.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-4" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-semibold mb-3">Yeni İnsanlarla Tanışın</h5>
                    <p class="text-muted">Aynı değerleri paylaşan insanlarla tanışın ve kalıcı dostluklar kurun.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-4" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-chart-line fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-semibold mb-3">Kendinizi Geliştirin</h5>
                    <p class="text-muted">Yeni beceriler kazanın, deneyim edinın ve kişisel gelişiminizi destekleyin.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gönüllü Alanları -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary px-3 py-2 mb-3">Gönüllü Alanları</span>
            <h2>Hangi Alanda Yer Almak İstersiniz?</h2>
            <p class="text-muted">Yetenekleriniz ve ilgi alanlarınıza uygun gönüllülük fırsatları</p>
        </div>

        <div class="row g-4">
            <?php foreach ($volunteer_areas as $area): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-4" 
                                 style="width: 80px; height: 80px;">
                                <i class="<?= $area['icon'] ?> fa-2x text-primary"></i>
                            </div>
                            <h5 class="fw-semibold mb-3"><?= $area['title'] ?></h5>
                            <p class="text-muted mb-4"><?= $area['description'] ?></p>
                            <div class="mt-auto">
                                <h6 class="fw-semibold mb-2">Aranan Özellikler:</h6>
                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                    <?php foreach ($area['skills'] as $skill): ?>
                                        <span class="badge bg-light text-dark"><?= $skill ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Gönüllü Başvuru Formu -->
<section class="py-5" id="volunteer-form">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <span class="badge bg-primary px-3 py-2 mb-3">Başvuru Formu</span>
                            <h2>Gönüllü Başvuru Formu</h2>
                            <p class="text-muted">Bizimle birlikte iyiliği yaymaya hazır mısınız?</p>
                        </div>

                        <form id="volunteerForm" novalidate>
                            <input type="hidden" name="action" value="volunteer">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Ad Soyad *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback">
                                        Lütfen adınızı ve soyadınızı girin.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">E-posta *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback">
                                        Lütfen geçerli bir e-posta adresi girin.
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Telefon *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required 
                                           placeholder="">
                                    <div class="invalid-feedback">
                                        Lütfen telefon numaranızı girin.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="age" class="form-label">Yaş</label>
                                    <input type="number" class="form-control" id="age" name="age" min="16" max="80" 
                                           placeholder="">
                                    <div class="invalid-feedback">
                                        Yaş 16-80 arasında olmalıdır.
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label for="profession" class="form-label">Meslek</label>
                                    <input type="text" class="form-control" id="profession" name="profession" 
                                           placeholder="Öğretmen, Mühendis, Öğrenci...">
                                </div>
                                <div class="col-md-6">
                                    <label for="availability" class="form-label">Müsaitlik Durumu *</label>
                                    <select class="form-select" id="availability" name="availability" required>
                                        <option value="">Seçiniz</option>
                                        <option value="weekdays">Hafta içi (Pazartesi-Cuma)</option>
                                        <option value="weekends">Hafta sonu (Cumartesi-Pazar)</option>
                                        <option value="evenings">Akşam saatleri (18:00 sonrası)</option>
                                        <option value="flexible">Esnek (Her zaman müsait)</option>
                                        <option value="mornings">Sabah saatleri (09:00-12:00)</option>
                                        <option value="afternoons">Öğleden sonra (13:00-17:00)</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Lütfen müsaitlik durumunuzu belirtin.
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="interests" class="form-label">İlgi Alanları</label>
                                <input type="text" class="form-control" id="interests" name="interests" 
                                       placeholder="Örn: Eğitim, sağlık, organizasyon, teknoloji...">
                                <small class="form-text text-muted">Hangi alanlarda gönüllü olmak istediğinizi belirtin</small>
                            </div>

                            <div class="mt-3">
                                <label for="experience" class="form-label">Gönüllülük Deneyimi</label>
                                <textarea class="form-control" id="experience" name="experience" rows="3" 
                                          placeholder="Daha önce katıldığınız gönüllü çalışmalar, deneyimleriniz..."></textarea>
                                <small class="form-text text-muted">Varsa önceki gönüllülük deneyimlerinizi paylaşın</small>
                            </div>

                            <div class="mt-3">
                                <label for="message" class="form-label">Neden Gönüllü Olmak İstiyorsunuz? *</label>
                                <textarea class="form-control" id="message" name="message" rows="4" 
                                          placeholder="Motivasyonunuzu, hedeflerinizi ve beklentilerinizi paylaşın..." 
                                          minlength="50" required></textarea>
                                <div class="invalid-feedback">
                                    Lütfen motivasyonunuzu en az 50 karakter olacak şekilde detaylı bir şekilde paylaşın.
                                </div>
                                <small class="form-text text-muted">Bu bilgi, size en uygun gönüllülük fırsatını sunmamızda yardımcı olacaktır (En az 50 karakter) 
                                    <span id="charCount" class="badge bg-secondary">0/50</span>
                                </small>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Başvuru Gönder
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Süreç Bilgileri -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary px-3 py-2 mb-3">Başvuru Süreci</span>
            <h2>Başvurunuzdan Sonra Neler Olacak?</h2>
            <p class="text-muted">Gönüllü başvurunuzdan sonra izlenen adımlar</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle text-white mb-3" 
                         style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold; background: #4ea674;">1</div>
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-file-alt fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-semibold">Başvuru</h5>
                    <p class="text-muted">Formu doldurun ve başvurunuzu gönderin</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle text-white mb-3" 
                         style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold; background: #4ea674;">2</div>
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-search fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-semibold">Değerlendirme</h5>
                    <p class="text-muted">Başvurunuz 3-5 iş günü içinde değerlendirilir</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle text-white mb-3" 
                         style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold; background: #4ea674;">3</div>
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-phone fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-semibold">Görüşme</h5>
                    <p class="text-muted">Kısa bir telefon görüşmesi yapılır</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle text-white mb-3" 
                         style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold; background: #4ea674;">3</div>
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-semibold">Katılım</h5>
                    <p class="text-muted">Ekibimize katılıp fark yaratmaya başlayın</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Simple CTA (matching About page style) -->
<section class="py-5 bg-primary text-white ">
    <div class="container text-center">
        <h2 class="mb-3">Siz de Bu Anlamlı Yolculuğa Katılın</h2>
        <p class="lead mb-4">
            Her gönüllü, toplumsal değişimde önemli bir rol oynar. Birlikte daha güçlü olalım.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="#volunteer-form" class="btn btn-light btn-lg">
                <i class="fas fa-hand-holding-heart me-2"></i> Başvuru Yap
            </a>
            <a href="index.php?page=contact" class="btn btn-outline-light btn-lg">
                <i class="fas fa-phone me-2"></i> Bizi Arayın
            </a>
        </div>
    </div>
</section>


<style>
/* ========================================
   VOLUNTEER PAGE CONSISTENT STYLES
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
    color: #4ea674 !important; /* Primary Green for all statistics */
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

/* Clean CTA Section with consistent colors */
.py-5.bg-primary.text-white {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important;
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
        rgba(61, 135, 96, 0.1) 100%);
    pointer-events: none;
}

/* Badges with consistent primary color */
.badge.bg-primary,
.badge.bg-light.text-primary {
    background: #4ea674 !important;
    color: #ffffff !important;
}

.badge.bg-light.text-primary {
    background: rgba(78, 166, 116, 0.1) !important;
    color: #4ea674 !important;
    border: 1px solid rgba(78, 166, 116, 0.2);
}

/* Primary buttons consistent color */
.btn-primary {
    background-color: #4ea674 !important;
    border-color: #4ea674 !important;
    color: #ffffff !important;
}

.btn-primary:hover {
    background-color: #3d8760 !important;
    border-color: #3d8760 !important;
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(78, 166, 116, 0.3);
}

/* Light button on dark background */
.btn-light {
    background: #ffffff !important;
    color: #4ea674 !important;
    border: 2px solid #ffffff !important;
}

.btn-light:hover {
    background: rgba(255, 255, 255, 0.9) !important;
    color: #3d8760 !important;
    transform: translateY(-2px);
}

/* Outline light button */
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

/* Icons consistent color */
.text-primary,
i.text-primary {
    color: #4ea674 !important;
}

/* Success color override for final step */
.text-success,
i.text-success {
    color: #28a745 !important;
}

.bg-success {
    background-color: #28a745 !important;
}

/* Cards and sections */
.card {
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(78, 166, 116, 0.15);
}

/* Form styling */
.form-control:focus {
    border-color: #4ea674;
    box-shadow: 0 0 0 0.2rem rgba(78, 166, 116, 0.25);
}

.form-select:focus {
    border-color: #4ea674;
    box-shadow: 0 0 0 0.2rem rgba(78, 166, 116, 0.25);
}

/* Responsive Design */
@media (max-width: 992px) {
    .hero-section {
        padding: calc(70px + 3rem) 0 3rem 0;
    }
    
    .display-4 {
        font-size: 2.5rem;
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
    
    .stat-number-consistent {
        font-size: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const volunteerForm = document.getElementById('volunteerForm');
    const submitBtn = document.getElementById('submitBtn');
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');

    // Character count functionality
    if (messageTextarea && charCount) {
        messageTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            const minLength = 50;
            
            charCount.textContent = `${currentLength}/${minLength}`;
            
            if (currentLength >= minLength) {
                charCount.className = 'badge bg-success';
            } else {
                charCount.className = 'badge bg-warning';
            }
        });
    }

    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            if (value.length > 0) {
                if (value.startsWith('0')) {
                    // Format: 0555 123 4567
                    value = value.replace(/(\d{4})(\d{3})(\d{2})(\d{2})/, '$1 $2 $3 $4');
                } else if (value.startsWith('90')) {
                    // Format: 90 555 123 4567
                    value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
                }
            }
            e.target.value = value;
        });
    }

    // Age validation
    const ageInput = document.getElementById('age');
    if (ageInput) {
        ageInput.addEventListener('input', function(e) {
            const value = parseInt(e.target.value);
            if (value && (value < 16 || value > 80)) {
                e.target.setCustomValidity('Yaş 16-80 arasında olmalıdır');
            } else {
                e.target.setCustomValidity('');
            }
        });
    }

    // Message field validation for minimum 50 characters
    const messageInput = document.getElementById('message');
    if (messageInput) {
        messageInput.addEventListener('input', function(e) {
            if (e.target.value.length < 50) {
                e.target.setCustomValidity('Lütfen motivasyonunuzu en az 50 karakter olacak şekilde detaylı bir şekilde paylaşın.');
            } else {
                e.target.setCustomValidity('');
            }
        });
    }
});
</script>
