<?php
// Veritabanından FAQ verilerini çek
try {
    // FAQ sorularını kategorilere göre çek
    $stmt = $pdo->prepare("SELECT * FROM faq WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
    $stmt->execute();
    $faq_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Kategorilere göre grupla
    $faq_categories = [];
    foreach ($faq_data as $faq) {
        $category = $faq['category'] ?? 'Genel';
        if (!isset($faq_categories[$category])) {
            $faq_categories[$category] = [
                'title' => $category,
                'icon' => $category === 'Bağış' ? 'fas fa-heart' : ($category === 'Gönüllülük' ? 'fas fa-users' : ($category === 'Projeler' ? 'fas fa-project-diagram' : 'fas fa-info-circle')),
                'questions' => []
            ];
        }
        $faq_categories[$category]['questions'][] = $faq;
    }

    // Eğer veritabanında veri yoksa varsayılan kategoriler oluştur
    if (empty($faq_categories)) {
        $faq_categories = [
            'general' => [
                'title' => 'Genel Sorular',
                'icon' => 'fas fa-info-circle',
                'questions' => [
                    [
                        'question' => 'Derneğiniz ne zaman kuruldu?',
                        'answer' => 'Derneğimiz 2020 yılında kurulmuş olup, o tarihten beri kesintisiz olarak faaliyetlerimizi sürdürmekteyiz.'
                    ],
                    [
                        'question' => 'Hangi alanlarda faaliyet gösteriyorsunuz?',
                        'answer' => 'Eğitim, sağlık, sosyal yardım, acil durum müdahalesi ve toplumsal gelişim alanlarında faaliyet göstermekteyiz.'
                    ]
                ]
            ],
            'donation' => [
                'title' => 'Bağış ve Yardım',
                'icon' => 'fas fa-heart',
                'questions' => [
                    [
                        'question' => 'Nasıl bağış yapabilirim?',
                        'answer' => 'Bağış yapmak için web sitemizin "Bağış Yap" bölümünü kullanabilir, IBAN numaralarımıza doğrudan transfer yapabilir veya dernek merkezimizi ziyaret edebilirsiniz.'
                    ],
                    [
                        'question' => 'Bağışımın hangi amaçla kullanıldığını nasıl öğrenebilirim?',
                        'answer' => 'Tüm bağışlar için detaylı raporlama sistemi kullanıyoruz. Bağışçılarımıza düzenli olarak faaliyet raporları gönderiliyor ve web sitemizde şeffaflık ilkesi gereği tüm harcamalar paylaşılıyor.'
                    ]
                ]
            ],
            'volunteer' => [
                'title' => 'Gönüllülük',
                'icon' => 'fas fa-hands-helping',
                'questions' => [
                    [
                        'question' => 'Gönüllü olmak için hangi şartları sağlamalıyım?',
                        'answer' => '18 yaşını doldurmuş olmak, dernek değerlerimizi benimsemek ve düzenli katılım sağlayabilmek temel şartlarımızdır.'
                    ],
                    [
                        'question' => 'Hangi alanlarda gönüllü olabilirim?',
                        'answer' => 'Acil yardım, eğitim desteği, sağlık hizmetleri, teknoloji, medya-iletişim, proje yönetimi gibi birçok alanda gönüllü olabilirsiniz.'
                    ]
                ]
            ]
        ];
    }

} catch (PDOException $e) {
    $faq_categories = [
        'general' => [
            'title' => 'Genel Sorular',
            'icon' => 'fas fa-info-circle',
            'questions' => []
        ]
    ];
}

// Manevi sorular için özel array - simplified
$faq_questions = [
    [
        'icon' => 'fas fa-heart',
        'question' => 'Başkalarına yardım etmek sizi mutlu ediyor mu?',
        'answer' => 'İyilik yapmak, insanın ruhunu besleyen en güzel eylemlerden biridir.',
        'category' => 'empathy'
    ],
    [
        'icon' => 'fas fa-clock',
        'question' => 'Boş zamanlarınızı anlamlı işler için ayırmaya istekli misiniz?',
        'answer' => 'Zamanımız en değerli varlığımızdır ve onu paylaştığımızda çoğalır.',
        'category' => 'time_management'
    ],
    [
        'icon' => 'fas fa-users',
        'question' => 'Takım çalışması yapmaktan hoşlanır mısınız?',
        'answer' => 'Birlikte başarılan işler, tek başına yapılandan çok daha anlamlıdır.',
        'category' => 'teamwork'
    ],
    [
        'icon' => 'fas fa-lightbulb',
        'question' => 'Toplumsal sorunlara çözüm üretmek ister misiniz?',
        'answer' => 'Her sorun aslında bir fırsattır ve çözüm üretmek hepimizin sorumluluğudur.',
        'category' => 'problem_solving'
    ]
];
?>

<!-- Simple Hero Section - Matching About/Projects style -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-3">Sıkça Sorulan Sorular</h1>
                <p class="lead mb-4">
                    Merak ettiğiniz soruların yanıtlarını burada bulabilirsiniz.
                    Size de sorularımız var!
                </p>
                
                <!-- Simple stats like other pages 
                <div class="row text-center mt-4">
                    <div class="col-md-4 col-6 mb-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent"><?= count($faq_categories) ?></h3>
                            <small class="stat-label-muted">Kategori</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mb-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent"><?= array_sum(array_map(function($cat) { return count($cat['questions']); }, $faq_categories)) ?></h3>
                            <small class="stat-label-muted">Soru & Cevap</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mb-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent">24/7</h3>
                            <small class="stat-label-muted">Destek</small>
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
</section>

<!-- Arama Bölümü - Simplified -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="search-box">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" id="faqSearch" 
                               placeholder="Soru ara...">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Manevi Sorular - Simplified Design -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary px-3 py-2 mb-3">Size Sorduğumuz Sorular</span>
            <h2>Kalbinizin Sesini Dinleyin</h2>
            <p class="text-muted">Bu sorulara samimi cevaplar verin</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($faq_questions as $index => $faq): ?>
            <div class="col-lg-6">
                <div class="card h-100 question-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <div class="question-icon me-3">
                                <i class="<?= $faq['icon'] ?> fa-2x text-primary"></i>
                            </div>
                            <div class="question-content">
                                <span class="question-number badge bg-primary mb-2"><?= $index + 1 ?></span>
                                <h5 class="question-text mb-3"><?= $faq['question'] ?></h5>
                                <p class="answer-text text-muted mb-0"><?= $faq['answer'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto text-center">
                <div class="simple-card p-4">
                    <h4 class="text-primary mb-3">Bu sorulara "EVET" diyorsanız...</h4>
                    <p class="mb-4">Bizimle birlikte bu kutlu yolculukta yer alın. Gönüllü olun veya bağış yaparak hayırlı işlerin bir parçası olun.</p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="index.php?page=volunteer" class="btn btn-primary">
                            <i class="fas fa-hand-holding-heart me-2"></i>
                            Gönüllü Ol
                        </a>
                        <a href="index.php?page=donate" class="btn btn-outline-primary">
                            <i class="fas fa-heart me-2"></i>
                            Bağış Yap
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kategori Navigasyonu -->
<section class="py-4 bg-white sticky-top border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="category-nav">
                    <div class="nav nav-pills justify-content-center flex-wrap" id="faq-category-nav">
                        <?php foreach ($faq_categories as $key => $category): ?>
                            <button class="nav-link <?= $key === array_key_first($faq_categories) ? 'active' : '' ?>" 
                                    data-category="<?= $key ?>" type="button">
                                <i class="<?= $category['icon'] ?> me-2"></i>
                                <?= $category['title'] ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- SSS İçeriği -->
<section class="py-5">
    <div class="container">
        <?php foreach ($faq_categories as $categoryKey => $category): ?>
            <div class="faq-category <?= $categoryKey !== array_key_first($faq_categories) ? 'd-none' : '' ?>" 
                 id="category-<?= $categoryKey ?>">
                <div class="row mb-5">
                    <div class="col-lg-8 mx-auto text-center">
                        <h2 class="section-title">
                            <i class="<?= $category['icon'] ?> me-3 text-primary"></i>
                            <?= $category['title'] ?>
                        </h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        <div class="accordion" id="faqAccordion<?= ucfirst($categoryKey) ?>">
                            <?php foreach ($category['questions'] as $index => $qa): ?>
                                <div class="accordion-item faq-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?= $index !== 0 ? 'collapsed' : '' ?>" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#faq<?= $categoryKey ?><?= $index ?>"
                                                aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>">
                                            <span class="question-number me-3"><?= $index + 1 ?></span>
                                            <?= htmlspecialchars($qa['question']) ?>
                                        </button>
                                    </h2>
                                    <div id="faq<?= $categoryKey ?><?= $index ?>" 
                                         class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" 
                                         data-bs-parent="#faqAccordion<?= ucfirst($categoryKey) ?>">
                                        <div class="accordion-body">
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($qa['answer'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Arama Sonuçları -->
        <div class="faq-category d-none" id="search-results">
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="section-title">
                        <i class="fas fa-search me-3 text-primary"></i>
                        Arama Sonuçları
                    </h2>
                    <p class="text-muted" id="search-results-count"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div id="search-results-content"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Yardım İhtiyacı - Simplified -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="simple-card p-5">
                    <div class="help-icon mb-4">
                        <i class="fas fa-question-circle fa-4x text-primary"></i>
                    </div>
                    <h3 class="help-title mb-3">Aradığınız Cevabı Bulamadınız mı?</h3>
                    <p class="help-description mb-4">
                        Sorularınız için bizimle iletişime geçmekten çekinmeyin. 
                        Size yardımcı olmak için buradayız.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="index.php?page=contact" class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>
                            İletişime Geç
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popüler Konular - Simplified
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary px-3 py-2 mb-3">Popüler Konular</span>
            <h2>En Çok Merak Edilen Konular</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="simple-card h-100 p-4 text-center">
                    <div class="topic-icon mb-3">
                        <i class="fas fa-heart fa-2x text-primary"></i>
                    </div>
                    <h5 class="topic-title mb-2">Bağış Yapma</h5>
                    <p class="topic-description text-muted mb-3">Bağış yapma yöntemleri ve süreci hakkında bilgiler</p>
                    <a href="#" class="btn btn-outline-primary btn-sm" data-category="donation">
                        Detayları Gör <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="simple-card h-100 p-4 text-center">
                    <div class="topic-icon mb-3">
                        <i class="fas fa-hands-helping fa-2x text-primary"></i>
                    </div>
                    <h5 class="topic-title mb-2">Gönüllü Olmak</h5>
                    <p class="topic-description text-muted mb-3">Gönüllülük başvurusu ve süreç bilgileri</p>
                    <a href="#" class="btn btn-outline-primary btn-sm" data-category="volunteer">
                        Detayları Gör <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="simple-card h-100 p-4 text-center">
                    <div class="topic-icon mb-3">
                        <i class="fas fa-project-diagram fa-2x text-primary"></i>
                    </div>
                    <h5 class="topic-title mb-2">Projelerimiz</h5>
                    <p class="topic-description text-muted mb-3">Projelerimiz ve nasıl destek olabileceğiniz</p>
                    <a href="#" class="btn btn-outline-primary btn-sm" data-category="projects">
                        Detayları Gör <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section> -->

<style>
/* ========================================
   CONSISTENT FAQ PAGE STYLES
   ======================================== */

/* Hero Section - Simple Design (matching About/Projects pages) */
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

/* Statistics Styling with Consistent Colors */
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

/* Simple Cards - Consistent with other pages */
.simple-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    transition: var(--transition-base);
    border: 1px solid var(--gray-100);
}

.simple-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

/* Question Cards - Simplified */
.question-card {
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.question-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(78, 166, 116, 0.15);
    border-left-color: #4ea674;
}

.question-icon {
    color: #4ea674 !important;
}

.question-number {
    background-color: #4ea674 !important;
    color: #ffffff !important;
    font-weight: 700;
}

.question-text {
    color: #4ea674 !important;
    font-weight: 600;
}

.answer-text {
    color: var(--gray-600) !important;
}

/* Button Consistency - Primary Green */
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

.btn-outline-primary {
    color: #4ea674 !important;
    border-color: #4ea674 !important;
    background: transparent;
}

.btn-outline-primary:hover {
    color: #ffffff !important;
    background-color: #4ea674 !important;
    border-color: #4ea674 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(78, 166, 116, 0.3);
}

/* Badge Consistency */
.badge.bg-primary {
    background-color: #4ea674 !important;
    color: #ffffff !important;
}

/* Category Navigation */
.nav-pills .nav-link {
    border-radius: var(--radius-lg);
    transition: all 0.3s ease;
    color: #6c757d;
    border: 1px solid transparent;
}

.nav-pills .nav-link.active {
    background-color: #4ea674 !important;
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(78, 166, 116, 0.3);
}

.nav-pills .nav-link:hover:not(.active) {
    background-color: rgba(78, 166, 116, 0.1);
    color: #4ea674;
    border-color: rgba(78, 166, 116, 0.2);
}

/* Accordion Styling */
.accordion-item {
    border: 1px solid rgba(78, 166, 116, 0.1);
    margin-bottom: 1rem;
    border-radius: var(--radius-lg) !important;
    overflow: hidden;
}

.accordion-button {
    background-color: rgba(78, 166, 116, 0.05);
    border: none;
    font-weight: 600;
}

.accordion-button:not(.collapsed) {
    background-color: #4ea674;
    color: #ffffff;
    box-shadow: none;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(78, 166, 116, 0.25);
    border-color: #4ea674;
}

.accordion-button::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%234ea674'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
}

.accordion-button:not(.collapsed)::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23ffffff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
}

.question-number {
    background-color: #4ea674;
    color: #ffffff;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
}

/* Topic Icons - Consistent Green */
.topic-icon i,
.help-icon i {
    color: #4ea674 !important;
}

.topic-title {
    color: #4ea674 !important;
    font-weight: 600;
}

.help-title {
    color: #4ea674 !important;
}

/* Section Icons - All Green */
.section-title i {
    color: #4ea674 !important;
}

/* FAQ Item Icons */
.faq-item i,
.accordion-button i {
    color: #4ea674 !important;
}

/* Popular Topics Section Icons */
.popular-topic .topic-icon i {
    color: #4ea674 !important;
}

.popular-topic .topic-title {
    color: #4ea674 !important;
}

/* Help Section Icons */
.help-icon i {
    color: #4ea674 !important;
}

/* All dropdown item icons */
.dropdown-item-modern i {
    color: #4ea674 !important;
}

/* All card titles and important text */
.card-title,
.simple-card h3,
.simple-card h4,
.simple-card h5 {
    color: #4ea674 !important;
}

/* Icon colors in simple cards */
.simple-card i {
    color: #4ea674 !important;
}

/* Question and answer styling enhancement */
.question-content .question-text {
    color: #4ea674 !important;
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.4;
}

.question-content .answer-text {
    color: var(--gray-600) !important;
    font-style: italic;
    line-height: 1.6;
}

/* All icons throughout the page */
i.fas, i.fab, i.far {
    color: #4ea674 !important;
}

/* Override any remaining inconsistent colors */
.text-primary,
i.text-primary {
    color: #4ea674 !important;
}

.bg-primary {
    background-color: #4ea674 !important;
}

/* Navigation category icons */
.nav-pills .nav-link i {
    color: #4ea674 !important;
}

.nav-pills .nav-link.active i {
    color: #ffffff !important;
}

/* Accordion question numbers */
.accordion-button .question-number {
    background-color: #4ea674;
    color: #ffffff;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    margin-right: 1rem;
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
    
    .nav-pills .nav-link {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        margin: 0.25rem;
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
    
    .d-flex.gap-3 {
        flex-direction: column;
    }
    
    .d-flex.gap-3 .btn {
        margin-bottom: 0.5rem;
    }
}

/* Search Box Enhancement */
.search-box .input-group {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-radius: var(--radius-lg);
    overflow: hidden;
}

.search-box .form-control:focus {
    border-color: #4ea674;
    box-shadow: 0 0 0 0.25rem rgba(78, 166, 116, 0.25);
}

.search-box .btn {
    background-color: #4ea674;
    border-color: #4ea674;
}

.search-box .btn:hover {
    background-color: #3d8760;
    border-color: #3d8760;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kategori navigasyonu
    const categoryButtons = document.querySelectorAll('[data-category]');
    const categoryContents = document.querySelectorAll('.faq-category');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetCategory = this.getAttribute('data-category');
            
            // Active state güncelle
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // İçerik göster/gizle
            categoryContents.forEach(content => {
                content.classList.add('d-none');
            });
            
            const targetContent = document.getElementById(`category-${targetCategory}`);
            if (targetContent) {
                targetContent.classList.remove('d-none');
            }
        });
    });
    
    // Arama fonksiyonu
    const searchInput = document.getElementById('faqSearch');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch(this.value.trim());
        }, 300);
    });
    
    function performSearch(query) {
        if (query.length < 2) {
            // Arama iptal - ilk kategoriyi göster
            categoryContents.forEach(content => content.classList.add('d-none'));
            document.getElementById('category-general').classList.remove('d-none');
            
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            document.querySelector('[data-category="general"]').classList.add('active');
            return;
        }
        
        const faqCategories = <?= json_encode($faq_categories) ?>;
        const results = [];
        
        Object.keys(faqCategories).forEach(categoryKey => {
            const category = faqCategories[categoryKey];
            category.questions.forEach((qa, index) => {
                if (qa.question.toLowerCase().includes(query.toLowerCase()) || 
                    qa.answer.toLowerCase().includes(query.toLowerCase())) {
                    results.push({
                        category: category.title,
                        categoryKey: categoryKey,
                        question: qa.question,
                        answer: qa.answer,
                        index: index
                    });
                }
            });
        });
        
        displaySearchResults(results, query);
    }
    
    function displaySearchResults(results, query) {
        const searchResultsSection = document.getElementById('search-results');
        const searchResultsContent = document.getElementById('search-results-content');
        const searchResultsCount = document.getElementById('search-results-count');
        
        // Tüm kategorileri gizle
        categoryContents.forEach(content => content.classList.add('d-none'));
        
        // Active state kaldır
        categoryButtons.forEach(btn => btn.classList.remove('active'));
        
        if (results.length === 0) {
            searchResultsCount.textContent = `"${query}" için sonuç bulunamadı`;
            searchResultsContent.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5>Sonuç Bulunamadı</h5>
                    <p class="text-muted">Farklı kelimeler deneyebilir veya bizimle iletişime geçebilirsiniz.</p>
                </div>
            `;
        } else {
            searchResultsCount.textContent = `"${query}" için ${results.length} sonuç bulundu`;
            
            let html = '<div class="accordion" id="searchResultsAccordion">';
            results.forEach((result, index) => {
                html += `
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#searchResult${index}">
                                <span class="badge bg-primary me-3">${result.category}</span>
                                ${highlightText(result.question, query)}
                            </button>
                        </h2>
                        <div id="searchResult${index}" 
                             class="accordion-collapse collapse" 
                             data-bs-parent="#searchResultsAccordion">
                            <div class="accordion-body">
                                <p class="mb-0">${highlightText(result.answer, query)}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            
            searchResultsContent.innerHTML = html;
        }
        
        searchResultsSection.classList.remove('d-none');
    }
    
    function highlightText(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }
    
    // Popüler konular
    document.querySelectorAll('.popular-topic .btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const category = this.getAttribute('data-category');
            document.querySelector(`[data-category="${category}"]`).click();
        });
    });
});
</script>
