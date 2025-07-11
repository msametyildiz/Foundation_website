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

<!-- Arama Bölümü - Fully Responsive -->
<section class="search-section py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6 col-xxl-5">
                <div class="search-box-wrapper">
                    <div class="search-box">
                        <div class="input-group input-group-lg">
                            <input type="text" 
                                   class="form-control search-input" 
                                   id="faqSearch" 
                                   placeholder="Hangi konuda yardıma ihtiyacınız var?"
                                   autocomplete="off"
                                   aria-label="Soru arama"
                                   maxlength="100">
                            <button class="btn btn-primary search-btn" 
                                    type="button"
                                    aria-label="Ara">
                                <i class="fas fa-search"></i>
                                <span class="btn-text d-none d-sm-inline ms-2">Ara</span>
                            </button>
                        </div>
                        
                        <!-- Search suggestions dropdown -->
                        <div class="search-suggestions d-none" id="searchSuggestions">
                            <div class="suggestions-content">
                                <!-- Populated by JavaScript -->
                            </div>
                        </div>
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
                        <a href="<?= site_url('volunteer') ?>" class="btn btn-primary">
                            <i class="fas fa-hand-holding-heart me-2"></i>
                            Gönüllü Ol
                        </a>
                        <a href="<?= site_url('donate') ?>" class="btn btn-outline-primary">
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
                        <a href="<?= site_url('contact') ?>" class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>
                            İletişime Geç
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



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

/* Accordion Styling with Enhanced Responsive Behavior */
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
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    word-wrap: break-word;
    line-height: 1.4;
}

/* Responsive accordion button padding and text handling */
@media (max-width: 320px) {
    .accordion-button {
        padding: 0.8rem 1rem !important;
        font-size: 0.85rem !important;
        line-height: 1.3 !important;
    }
}

@media (min-width: 321px) and (max-width: 480px) {
    .accordion-button {
        padding: 0.9rem 1.1rem !important;
        font-size: 0.9rem !important;
        line-height: 1.35 !important;
    }
}

@media (min-width: 481px) and (max-width: 767px) {
    .accordion-button {
        padding: 1rem 1.2rem !important;
        font-size: 0.95rem !important;
        line-height: 1.4 !important;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .accordion-button {
        padding: 1.1rem 1.3rem !important;
        font-size: 1rem !important;
        line-height: 1.4 !important;
    }
}

@media (min-width: 992px) {
    .accordion-button {
        padding: 1.25rem 1.5rem !important;
        font-size: 1.05rem !important;
        line-height: 1.5 !important;
    }
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
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
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

/* Button Icon Colors - Specific Override */
.btn-primary .fas.fa-hand-holding-heart {
    color: #ffffff !important;
}

.btn-outline-primary .fas.fa-heart {
    color: #4ea674 !important;
}

.btn-outline-primary:hover .fas.fa-heart {
    color: #ffffff !important;
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

/* Accordion question numbers - Enhanced Responsive */
.accordion-button .question-number {
    background-color: #4ea674;
    color: #ffffff;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    margin-right: 1rem;
    flex-shrink: 0; /* Prevent shrinking on small screens */
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

/* Responsive question numbers for different screen sizes */
@media (max-width: 320px) {
    .accordion-button .question-number {
        width: 24px !important;
        height: 24px !important;
        font-size: 11px !important;
        margin-right: 0.5rem !important;
        min-width: 24px !important;
        min-height: 24px !important;
    }
}

@media (min-width: 321px) and (max-width: 480px) {
    .accordion-button .question-number {
        width: 26px !important;
        height: 26px !important;
        font-size: 12px !important;
        margin-right: 0.6rem !important;
        min-width: 26px !important;
        min-height: 26px !important;
    }
}

@media (min-width: 481px) and (max-width: 767px) {
    .accordion-button .question-number {
        width: 28px !important;
        height: 28px !important;
        font-size: 13px !important;
        margin-right: 0.75rem !important;
        min-width: 28px !important;
        min-height: 28px !important;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .accordion-button .question-number {
        width: 30px !important;
        height: 30px !important;
        font-size: 14px !important;
        margin-right: 0.8rem !important;
        min-width: 30px !important;
        min-height: 30px !important;
    }
}

@media (min-width: 992px) and (max-width: 1199px) {
    .accordion-button .question-number {
        width: 32px !important;
        height: 32px !important;
        font-size: 14px !important;
        margin-right: 0.9rem !important;
        min-width: 32px !important;
        min-height: 32px !important;
    }
}

@media (min-width: 1200px) {
    .accordion-button .question-number {
        width: 34px !important;
        height: 34px !important;
        font-size: 15px !important;
        margin-right: 1rem !important;
        min-width: 34px !important;
        min-height: 34px !important;
    }
}

/* Enhanced Badge-style question numbers in cards */
.question-card .question-number.badge {
    padding: 0.4rem 0.7rem !important;
    font-size: 0.8rem !important;
    font-weight: 700 !important;
    border-radius: 15px !important;
    display: inline-block !important;
    min-width: auto !important;
    height: auto !important;
    line-height: 1.2 !important;
}

@media (max-width: 320px) {
    .question-card .question-number.badge {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.7rem !important;
        border-radius: 12px !important;
    }
}

@media (min-width: 321px) and (max-width: 480px) {
    .question-card .question-number.badge {
        padding: 0.3rem 0.55rem !important;
        font-size: 0.75rem !important;
        border-radius: 13px !important;
    }
}

@media (min-width: 481px) and (max-width: 767px) {
    .question-card .question-number.badge {
        padding: 0.35rem 0.6rem !important;
        font-size: 0.8rem !important;
        border-radius: 14px !important;
    }
}

@media (min-width: 768px) {
    .question-card .question-number.badge {
        padding: 0.4rem 0.7rem !important;
        font-size: 0.85rem !important;
        border-radius: 15px !important;
    }
}

/* ========================================
   RESPONSIVE SEARCH SECTION STYLES
   ======================================== */

/* Base Search Section Styles */
.search-section {
    padding: 2rem 0;
    background: #ffffff !important;
    position: relative;
    overflow: hidden;
}

.search-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 20%, rgba(78, 166, 116, 0.03) 0%, transparent 40%),
        radial-gradient(circle at 80% 80%, rgba(78, 166, 116, 0.02) 0%, transparent 40%);
    pointer-events: none;
}

.search-box-wrapper {
    position: relative;
    z-index: 2;
}

.search-box {
    position: relative;
}

/* Search Input Group Styling */
.search-box .input-group {
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 3px solid transparent;
    background: linear-gradient(white, white) padding-box, 
                linear-gradient(135deg, #4ea674, #3d8760) border-box;
}

.search-box .input-group:focus-within {
    box-shadow: 0 15px 50px rgba(78, 166, 116, 0.2);
    border-color: transparent;
    transform: translateY(-3px) scale(1.02);
}

.search-input {
    border: none;
    padding: 1.25rem 1.75rem;
    font-size: 1.15rem;
    background: #ffffff !important;
    color: #333 !important;
    transition: all 0.3s ease;
    font-weight: 500;
    letter-spacing: 0.5px;
}

.search-input:focus {
    border: none;
    box-shadow: none;
    background: #ffffff !important;
    color: #333 !important;
    outline: none;
}

.search-input::placeholder {
    color: #777 !important;
    font-weight: 400;
    font-style: italic;
    transition: all 0.3s ease;
}

/* Device-specific placeholder adjustments - Enhanced for maximum readability */
@media (max-width: 320px) {
    .search-input::placeholder {
        font-size: 0.75rem !important;
        color: #555 !important;
        font-weight: 500 !important;
        letter-spacing: 0.3px !important;
    }
}

@media (min-width: 321px) and (max-width: 480px) {
    .search-input::placeholder {
        font-size: 0.8rem !important;
        color: #555 !important;
        font-weight: 500 !important;
        letter-spacing: 0.2px !important;
    }
}

@media (min-width: 481px) and (max-width: 767px) {
    .search-input::placeholder {
        font-size: 0.85rem !important;
        color: #666 !important;
        font-weight: 400 !important;
        letter-spacing: 0.1px !important;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .search-input::placeholder {
        font-size: 0.95rem !important;
        color: #777 !important;
        font-weight: 400 !important;
    }
}

@media (min-width: 992px) and (max-width: 1199px) {
    .search-input::placeholder {
        font-size: 1rem !important;
        color: #777 !important;
        font-weight: 400 !important;
    }
}

@media (min-width: 1200px) and (max-width: 1366px) {
    .search-input::placeholder {
        font-size: 1.05rem !important;
        color: #777 !important;
        font-weight: 400 !important;
    }
}

@media (min-width: 1367px) {
    .search-input::placeholder {
        font-size: 1.1rem !important;
        color: #777 !important;
        font-weight: 400 !important;
    }
}

.search-btn {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%);
    border: none;
    padding: 1.25rem 2rem;
    color: white;
    font-weight: 600;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.search-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.search-btn:hover::before {
    left: 100%;
}

.search-btn:hover {
    background: linear-gradient(135deg, #3d8760 0%, #2d6a50 100%);
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 8px 25px rgba(78, 166, 116, 0.3);
}

.search-btn:active {
    transform: translateY(-1px) scale(1.02);
}

.search-btn .fas {
    color: white !important;
    font-size: 1.1rem;
    transition: transform 0.3s ease;
}

.search-btn:hover .fas {
    transform: scale(1.1) rotate(15deg);
}

/* Search Suggestions */
.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border-radius: 0 0 20px 20px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    max-height: 350px;
    overflow-y: auto;
    border: 2px solid #f0f0f0;
    border-top: none;
    backdrop-filter: blur(10px);
}

.suggestions-content {
    padding: 1.5rem;
}

.suggestion-item {
    padding: 0.8rem 1rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 0.5rem;
    border: 1px solid transparent;
    display: flex;
    align-items: center;
}

.suggestion-item:hover {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%);
    color: white;
    transform: translateX(5px);
    border-color: #4ea674;
}

.suggestion-item i {
    margin-right: 0.75rem;
    transition: all 0.3s ease;
}

.suggestion-item:hover i {
    color: white !important;
    transform: scale(1.1);
}

/* Quick Search Tags */
.quick-search-tags {
    margin-top: 2rem;
}

.quick-search-tags small {
    color: #555;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.quick-search-tag {
    border: 2px solid #e9ecef;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    color: #495057;
    border-radius: 25px;
    padding: 0.6rem 1.2rem;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    margin: 0.3rem;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.quick-search-tag::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%);
    transition: left 0.4s ease;
    z-index: -1;
}

.quick-search-tag:hover::before {
    left: 0;
}

.quick-search-tag:hover {
    color: white;
    border-color: #4ea674;
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(78, 166, 116, 0.3);
}

.quick-search-tag .fas {
    color: #4ea674;
    transition: all 0.3s ease;
    margin-right: 0.5rem;
}

.quick-search-tag:hover .fas {
    color: white !important;
    transform: scale(1.1);
}

/* ========================================
   DEVICE-SPECIFIC RESPONSIVE STYLES
   ======================================== */

/* Küçük Telefonlar: 320px – 480px */
@media (min-width: 320px) and (max-width: 480px) {
    .search-section {
        padding: 1.5rem 0;
    }
    
    .search-box .input-group {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    .search-input {
        padding: 0.9rem 1rem !important;
        font-size: 0.85rem !important;
        min-height: 48px !important;
        /* Ensure placeholder text is fully visible and readable */
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    
    /* Enhanced placeholder readability for very small screens */
    .search-input::placeholder {
        opacity: 1 !important;
        -webkit-text-fill-color: #555 !important;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.3) !important;
        font-weight: 500 !important;
    }
    
    .search-btn {
        padding: 0.8rem 1rem;
        min-width: 60px;
    }
    
    .btn-text {
        display: none !important;
    }
    
    .quick-search-tags {
        margin-top: 1rem;
    }
    
    .quick-search-tags small {
        font-size: 0.8rem;
        margin-bottom: 0.5rem !important;
    }
    
    .quick-search-tag {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
        margin: 0.1rem;
        border-radius: 15px;
    }
    
    .quick-search-tag .fas {
        font-size: 0.7rem;
    }
    
    .d-flex.flex-wrap.gap-2 {
        gap: 0.3rem !important;
    }
    
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
}

/* Orta Büyüklükte Telefonlar: 481px – 767px */
@media (min-width: 481px) and (max-width: 767px) {
    .search-section {
        padding: 1.75rem 0;
    }
    
    .search-box .input-group {
        border-radius: 13px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.09);
    }
    
    .search-input {
        padding: 0.9rem 1.2rem;
        font-size: 1rem;
    }
    
    .search-btn {
        padding: 0.9rem 1.2rem;
        min-width: 70px;
    }
    
    .btn-text {
        display: none !important;
    }
    
    .quick-search-tags {
        margin-top: 1.2rem;
    }
    
    .quick-search-tags small {
        font-size: 0.82rem;
    }
    
    .quick-search-tag {
        font-size: 0.8rem;
        padding: 0.35rem 0.7rem;
        margin: 0.15rem;
        border-radius: 18px;
    }
    
    .quick-search-tag .fas {
        font-size: 0.75rem;
    }
    
    .d-flex.flex-wrap.gap-2 {
        gap: 0.4rem !important;
    }
}

/* Büyük Telefonlar & Küçük Tabletler: 768px – 991px */
@media (min-width: 768px) and (max-width: 991px) {
    .search-section {
        padding: 2rem 0;
    }
    
    .search-box .input-group {
        border-radius: 14px;
    }
    
    .search-input {
        padding: 1rem 1.3rem;
        font-size: 1.05rem;
    }
    
    .search-btn {
        padding: 1rem 1.3rem;
    }
    
    .btn-text {
        display: inline !important;
        font-size: 0.9rem;
    }
    
    .quick-search-tags {
        margin-top: 1.3rem;
    }
    
    .quick-search-tag {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
    }
}

/* Orta Büyüklükte Tabletler: 992px – 1199px */
@media (min-width: 992px) and (max-width: 1199px) {
    .search-section {
        padding: 2.2rem 0;
    }
    
    .search-input {
        padding: 1.1rem 1.4rem;
        font-size: 1.1rem;
    }
    
    .search-btn {
        padding: 1.1rem 1.4rem;
    }
    
    .btn-text {
        display: inline !important;
        font-size: 0.95rem;
    }
    
    .quick-search-tags {
        margin-top: 1.4rem;
    }
    
    .quick-search-tag {
        font-size: 0.9rem;
        padding: 0.45rem 0.9rem;
    }
}

/* Dizüstü Bilgisayarlar: 1200px – 1366px */
@media (min-width: 1200px) and (max-width: 1366px) {
    .search-section {
        padding: 2.5rem 0;
    }
    
    .search-input {
        padding: 1.2rem 1.5rem;
        font-size: 1.15rem;
    }
    
    .search-btn {
        padding: 1.2rem 1.5rem;
    }
    
    .btn-text {
        display: inline !important;
        font-size: 1rem;
    }
    
    .quick-search-tags {
        margin-top: 1.5rem;
    }
    
    .quick-search-tag {
        font-size: 0.95rem;
        padding: 0.5rem 1rem;
    }
}

/* Büyük Ekranlı Masaüstü: 1367px ve üzeri */
@media (min-width: 1367px) {
    .search-section {
        padding: 3rem 0;
    }
    
    .search-box .input-group {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }
    
    .search-input {
        padding: 1.3rem 1.6rem;
        font-size: 1.2rem;
    }
    
    .search-btn {
        padding: 1.3rem 1.6rem;
    }
    
    .btn-text {
        display: inline !important;
        font-size: 1.05rem;
    }
    
    .quick-search-tags {
        margin-top: 1.6rem;
    }
    
    .quick-search-tag {
        font-size: 1rem;
        padding: 0.55rem 1.1rem;
    }
}

/* ========================================
   ADDITIONAL RESPONSIVE ENHANCEMENTS
   ======================================== */

/* Touch Device Optimizations */
@media (hover: none) and (pointer: coarse) {
    .search-btn,
    .quick-search-tag {
        min-height: 44px;
        min-width: 44px;
    }
    
    .search-input {
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    .quick-search-tag:hover {
        transform: none;
    }
    
    .search-box .input-group:focus-within {
        transform: none;
    }
}

/* Landscape Phone Orientation */
@media (max-height: 500px) and (orientation: landscape) {
    .search-section {
        padding: 1rem 0;
    }
    
    .quick-search-tags {
        margin-top: 0.8rem;
    }
    
    .quick-search-tags small {
        margin-bottom: 0.3rem !important;
    }
    
    .quick-search-tag {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        margin: 0.1rem;
    }
}

/* High DPI Displays */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .search-box .input-group {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .search-box .input-group:focus-within {
        box-shadow: 0 6px 20px rgba(78, 166, 116, 0.12);
    }
}

/* Foldable Devices & Very Small Screens */
@media (max-width: 280px) {
    .search-section {
        padding: 1rem 0;
    }
    
    .container {
        padding-left: 5px;
        padding-right: 5px;
    }
    
    .search-input {
        padding: 0.6rem 0.8rem;
        font-size: 0.9rem;
    }
    
    .search-btn {
        padding: 0.6rem 0.8rem;
        min-width: 50px;
    }
    
    .quick-search-tag {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        margin: 0.05rem;
    }
    
    .quick-search-tags small {
        font-size: 0.75rem;
    }
}

/* Ultra-wide Screens */
@media (min-width: 1920px) {
    .search-section .container {
        max-width: 1600px;
    }
    
    .search-section .col-xxl-5 {
        flex: 0 0 auto;
        width: 35%;
    }
}

/* Print Styles */
@media print {
    .search-section {
        display: none;
    }
}

/* Accessibility Improvements */
@media (prefers-reduced-motion: reduce) {
    .search-box .input-group,
    .search-btn,
    .quick-search-tag {
        transition: none;
    }
    
    .search-box .input-group:focus-within {
        transform: none;
    }
    
    .search-btn:hover {
        transform: none;
    }
    
    .quick-search-tag:hover {
        transform: none;
    }
}

/* High Contrast Mode */
@media (prefers-contrast: high) {
    .search-box .input-group {
        border: 2px solid #000;
    }
    
    .search-input {
        border: 1px solid #000;
    }
    
    .search-btn {
        border: 2px solid #000;
    }
    
    .quick-search-tag {
        border: 2px solid #000;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .search-section {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    }
    
    .search-input {
        background: #333;
        color: #fff;
        border-color: #555;
    }
    
    .search-input::placeholder {
        color: #aaa;
    }
    
    .quick-search-tag {
        background: #333;
        color: #fff;
        border-color: #555;
    }
    
    .quick-search-tags small {
        color: #ccc;
    }
}

/* Arama Sonuçları İçeriği Responsive - Enhanced Question Numbers */
        @media (max-width: 320px) {
            #search-results-content .accordion-item {
                margin-bottom: 0.5rem !important;
            }
            
            #search-results-content .accordion-button {
                padding: 0.6rem 0.8rem !important;
                font-size: 0.8rem !important;
            }
            
            #search-results-content .question-number {
                font-size: 0.7rem !important;
                margin-right: 0.4rem !important;
                width: 20px !important;
                height: 20px !important;
                min-width: 20px !important;
                min-height: 20px !important;
            }
            
            #search-results-content .accordion-body {
                padding: 0.8rem !important;
                font-size: 0.8rem !important;
                line-height: 1.4 !important;
            }
        }
        
        @media (min-width: 321px) and (max-width: 480px) {
            #search-results-content .accordion-item {
                margin-bottom: 0.6rem !important;
            }
            
            #search-results-content .accordion-button {
                padding: 0.65rem 0.9rem !important;
                font-size: 0.85rem !important;
            }
            
            #search-results-content .question-number {
                font-size: 0.75rem !important;
                margin-right: 0.45rem !important;
                width: 22px !important;
                height: 22px !important;
                min-width: 22px !important;
                min-height: 22px !important;
            }
            
            #search-results-content .accordion-body {
                padding: 0.9rem !important;
                font-size: 0.82rem !important;
                line-height: 1.45 !important;
            }
        }
        
        @media (min-width: 481px) and (max-width: 767px) {
            #search-results-content .accordion-item {
                margin-bottom: 0.75rem !important;
            }
            
            #search-results-content .accordion-button {
                padding: 0.75rem 1rem !important;
                font-size: 0.9rem !important;
            }
            
            #search-results-content .question-number {
                font-size: 0.8rem !important;
                margin-right: 0.5rem !important;
                width: 24px !important;
                height: 24px !important;
                min-width: 24px !important;
                min-height: 24px !important;
            }
            
            #search-results-content .accordion-body {
                padding: 1rem !important;
                font-size: 0.85rem !important;
                line-height: 1.5 !important;
            }
        }
        
        @media (min-width: 768px) and (max-width: 991px) {
            #search-results-content .accordion-item {
                margin-bottom: 1rem !important;
            }
            
            #search-results-content .accordion-button {
                padding: 1rem 1.25rem !important;
                font-size: 0.95rem !important;
            }
            
            #search-results-content .question-number {
                font-size: 0.85rem !important;
                margin-right: 0.75rem !important;
                width: 26px !important;
                height: 26px !important;
                min-width: 26px !important;
                min-height: 26px !important;
            }
            
            #search-results-content .accordion-body {
                padding: 1.25rem !important;
                font-size: 0.9rem !important;
                line-height: 1.6 !important;
            }
        }
        
        @media (min-width: 992px) and (max-width: 1199px) {
            #search-results-content .accordion-item {
                margin-bottom: 1.2rem !important;
            }
            
            #search-results-content .accordion-button {
                padding: 1.1rem 1.4rem !important;
                font-size: 1rem !important;
            }
            
            #search-results-content .question-number {
                font-size: 0.9rem !important;
                margin-right: 0.8rem !important;
                width: 28px !important;
                height: 28px !important;
                min-width: 28px !important;
                min-height: 28px !important;
            }
            
            #search-results-content .accordion-body {
                padding: 1.4rem !important;
                font-size: 0.95rem !important;
                line-height: 1.65 !important;
            }
        }
        
        @media (min-width: 1200px) {
            #search-results-content .accordion-item {
                margin-bottom: 1.5rem !important;
            }
            
            #search-results-content .accordion-button {
                padding: 1.25rem 1.5rem !important;
                font-size: 1.05rem !important;
            }
            
            #search-results-content .question-number {
                font-size: 1rem !important;
                margin-right: 1rem !important;
                width: 30px !important;
                height: 30px !important;
                min-width: 30px !important;
                min-height: 30px !important;
            }
            
            #search-results-content .accordion-body {
                padding: 1.5rem !important;
                font-size: 1rem !important;
                line-height: 1.7 !important;
            }
        }

/* ========================================
   SEARCH RESULTS PROFESSIONAL STYLING
   ======================================== */

/* Search Results Section */
#search-results {
    padding: 3rem 0;
    background: linear-gradient(135deg, #f8fafb 0%, #ffffff 100%);
    position: relative;
}

#search-results::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 20%, rgba(78, 166, 116, 0.03) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(78, 166, 116, 0.02) 0%, transparent 50%);
    pointer-events: none;
}

#search-results .section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
    position: relative;
    display: inline-block;
}

#search-results .section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%);
    border-radius: 2px;
}

#search-results .section-title i {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-right: 1rem;
}

#search-results .text-muted {
    font-size: 1.1rem;
    color: #6c757d !important;
    font-weight: 500;
    margin-bottom: 2rem;
}

/* Search Results Content */
#search-results-content {
    position: relative;
    z-index: 2;
}

#search-results-content .accordion {
    border: none;
}

#search-results-content .accordion-item {
    border: none;
    margin-bottom: 1.5rem;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    background: white;
}

#search-results-content .accordion-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

#search-results-content .accordion-button {
    background: white;
    border: none;
    padding: 1.5rem 2rem;
    font-weight: 600;
    color: #2c3e50;
    border-radius: 15px;
    position: relative;
    transition: all 0.3s ease;
}

#search-results-content .accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%);
    color: white;
    box-shadow: none;
}

#search-results-content .accordion-button:focus {
    box-shadow: 0 0 0 0.2rem rgba(78, 166, 116, 0.25);
}

#search-results-content .accordion-button::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%234ea674'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    transition: transform 0.3s ease;
}

#search-results-content .accordion-button:not(.collapsed)::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    transform: rotate(180deg);
}

#search-results-content .accordion-body {
    padding: 2rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    font-size: 1rem;
    line-height: 1.7;
    color: #495057;
}

#search-results-content .badge {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important;
    color: white;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    margin-right: 1rem;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

#search-results-content .accordion-button:not(.collapsed) .badge {
    background: rgba(255, 255, 255, 0.2) !important;
    color: white;
}

/* Highlight Effect for Search Terms */
#search-results-content mark {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-weight: 600;
}

/* No Results Styling */
#search-results-content .text-center {
    padding: 4rem 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

#search-results-content .text-center i {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

#search-results-content .text-center h5 {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 1rem;
}

#search-results-content .text-center p {
    color: #6c757d;
    font-size: 1.1rem;
}

/* ========================================
   RESPONSIVE PLACEHOLDER TEXT MANAGEMENT
   ======================================== */
    
    // Keep the full placeholder text but adjust styling for readability
    function updatePlaceholderStyling() {
        const screenWidth = window.innerWidth;
        const searchInput = document.getElementById('faqSearch');
        
        // Always keep the full text
        searchInput.placeholder = "Hangi konuda yardıma ihtiyacınız var?";
        
        // Add dynamic styling adjustments for very small screens
        if (screenWidth <= 320) {
            searchInput.style.setProperty('text-overflow', 'ellipsis');
            searchInput.style.setProperty('white-space', 'nowrap');
            searchInput.style.setProperty('overflow', 'hidden');
        } else if (screenWidth <= 480) {
            searchInput.style.setProperty('text-overflow', 'ellipsis');
            searchInput.style.setProperty('white-space', 'nowrap');
            searchInput.style.setProperty('overflow', 'hidden');
        } else {
            searchInput.style.removeProperty('text-overflow');
            searchInput.style.removeProperty('white-space');
            searchInput.style.removeProperty('overflow');
        }
    }
    
    // Initialize placeholder styling
    updatePlaceholderStyling();
    
    // Update styling on window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            updatePlaceholderStyling();
        }, 100);
    });
    
    // Enhanced placeholder visibility for better contrast
    searchInput.addEventListener('focus', function() {
        this.style.setProperty('--placeholder-opacity', '0.8');
    });
    
    searchInput.addEventListener('blur', function() {
        this.style.setProperty('--placeholder-opacity', '1');
    });
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
    
    // Ana arama kutusu
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
            const firstCategory = document.querySelector('.faq-category:not(#search-results)');
            if (firstCategory) {
                firstCategory.classList.remove('d-none');
            }
            
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            const firstButton = document.querySelector('[data-category]');
            if (firstButton) {
                firstButton.classList.add('active');
            }
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
        
        // Arama sonuçlarına otomatik scroll - geliştirilmiş
        setTimeout(() => {
            const searchResultsHeader = searchResultsSection.querySelector('.section-title');
            if (searchResultsHeader) {
                searchResultsHeader.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
            } else {
                searchResultsSection.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
        }, 150);
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
    
    // ========================================
    // ENHANCED SEARCH FUNCTIONALITY
    // ========================================
    
    // Quick search tags functionality
    const quickSearchTags = document.querySelectorAll('.quick-search-tag');
    quickSearchTags.forEach(tag => {
        tag.addEventListener('click', function() {
            const searchTerm = this.getAttribute('data-search');
            searchInput.value = searchTerm;
            performSearch(searchTerm);
            
            // Add visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // Enhanced search with suggestions
    function showSearchSuggestions(query) {
        const suggestions = document.getElementById('searchSuggestions');
        const suggestionsContent = document.querySelector('.suggestions-content');
        
        if (query.length < 2) {
            suggestions.classList.add('d-none');
            return;
        }
        
        // Enhanced suggestion categories with comprehensive terms
        const suggestionCategories = {
            'Bağış': ['bağış', 'bağışla', 'yardım', 'destek', 'para', 'kredi kartı', 'havale', 'eft', 'donate'],
            'Gönüllülük': ['gönüllü', 'katıl', 'yardım et', 'volunteer', 'çalış', 'katkı', 'gönül'],
            'Projeler': ['proje', 'faaliyet', 'etkinlik', 'kampanya', 'program', 'hizmet', 'initiative'],
            'İletişim': ['iletişim', 'telefon', 'email', 'adres', 'ulaş', 'mesaj', 'sor', 'contact'],
            'Sağlık': ['sağlık', 'tedavi', 'hastane', 'ilaç', 'doktor', 'ameliyat', 'health'],
            'Eğitim': ['eğitim', 'okul', 'ders', 'öğretim', 'kurs', 'öğren', 'education'],
            'Hukuki': ['hukuk', 'yasal', 'kanun', 'hak', 'adalet', 'mahkeme', 'legal'],
            'Acil Yardım': ['acil', 'yardım', 'destek', 'ihtiyaç', 'kriz', 'sosyal', 'emergency']
        };
        
        const matchingSuggestions = [];
        const queryLower = query.toLowerCase();
        
        Object.keys(suggestionCategories).forEach(category => {
            const terms = suggestionCategories[category];
            const hasMatch = terms.some(term => 
                term.toLowerCase().includes(queryLower) || 
                queryLower.includes(term.toLowerCase())
            );
            
            if (hasMatch) {
                matchingSuggestions.push({
                    category: category,
                    icon: getSuggestionIcon(category),
                    description: getSuggestionDescription(category),
                    searchTerm: terms[0] // Use the primary term for search
                });
            }
        });
        
        if (matchingSuggestions.length > 0) {
            let suggestionsHTML = '<div class="suggestion-list">';
            matchingSuggestions.forEach(suggestion => {
                suggestionsHTML += `
                    <div class="suggestion-item" data-suggestion="${suggestion.searchTerm}">
                        <i class="${suggestion.icon}"></i>
                        <div>
                            <strong>${suggestion.category}</strong>
                            <br><small class="text-muted">${suggestion.description}</small>
                        </div>
                    </div>
                `;
            });
            suggestionsHTML += '</div>';
            
            suggestionsContent.innerHTML = suggestionsHTML;
            suggestions.classList.remove('d-none');
            
            // Add click handlers to suggestions
            document.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', function() {
                    const suggestionText = this.getAttribute('data-suggestion');
                    searchInput.value = suggestionText;
                    performSearch(suggestionText);
                    suggestions.classList.add('d-none');
                });
            });
        } else {
            suggestions.classList.add('d-none');
        }
    }
    
    function getSuggestionIcon(category) {
        const icons = {
            'Bağış': 'fas fa-heart',
            'Gönüllülük': 'fas fa-hands-helping',
            'Projeler': 'fas fa-project-diagram',
            'İletişim': 'fas fa-phone',
            'Sağlık': 'fas fa-user-md',
            'Eğitim': 'fas fa-graduation-cap',
            'Hukuki': 'fas fa-balance-scale',
            'Acil Yardım': 'fas fa-hand-holding-heart'
        };
        return icons[category] || 'fas fa-question';
    }
    
    function getSuggestionDescription(category) {
        const descriptions = {
            'Bağış': 'Nasıl bağış yapabilirim?',
            'Gönüllülük': 'Gönüllü olarak nasıl katılabilirim?',
            'Projeler': 'Hangi projelerde yer alıyoruz?',
            'İletişim': 'Bizimle nasıl iletişime geçebilirim?',
            'Sağlık': 'Sağlık hizmetlerimiz nelerdir?',
            'Eğitim': 'Eğitim programlarımız',
            'Hukuki': 'Hukuki destek ve danışmanlık',
            'Acil Yardım': 'Acil durum ve sosyal yardım'
        };
        return descriptions[category] || 'Daha fazla bilgi';
    }
    
    // Enhanced search input handling
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        // Show suggestions
        showSearchSuggestions(query);
        
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        const suggestions = document.getElementById('searchSuggestions');
        const searchBox = document.querySelector('.search-box');
        
        if (!searchBox.contains(e.target)) {
            suggestions.classList.add('d-none');
        }
    });
    
    // Keyboard navigation for suggestions
    searchInput.addEventListener('keydown', function(e) {
        const suggestions = document.getElementById('searchSuggestions');
        const suggestionItems = suggestions.querySelectorAll('.suggestion-item');
        
        if (e.key === 'ArrowDown' && suggestionItems.length > 0) {
            e.preventDefault();
            suggestionItems[0].focus();
        } else if (e.key === 'Escape') {
            suggestions.classList.add('d-none');
        }
    });
    
    // ========================================
    // SEARCH BUTTON AND KEYBOARD SUPPORT
    // ========================================
    
    // Search button click handler
    const searchButton = document.querySelector('.search-btn');
    if (searchButton) {
        searchButton.addEventListener('click', function() {
            const query = searchInput.value.trim();
            if (query.length >= 2) {
                performSearch(query);
                // Hide suggestions
                const suggestions = document.getElementById('searchSuggestions');
                suggestions.classList.add('d-none');
                
                // Add visual feedback
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            }
        });
    }
    
    // Enter key support for search input
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = this.value.trim();
            if (query.length >= 2) {
                performSearch(query);
                // Hide suggestions
                const suggestions = document.getElementById('searchSuggestions');
                suggestions.classList.add('d-none');
            }
        }
    });
    
    // Enhanced search input with loading state
    function showSearchLoading() {
        const searchButton = document.querySelector('.search-btn');
        const originalHTML = searchButton.innerHTML;
        searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        setTimeout(() => {
            searchButton.innerHTML = originalHTML;
        }, 500);
    }
    
    // Add loading state to search
    const originalPerformSearch = performSearch;
    performSearch = function(query) {
        if (query.length >= 2) {
            showSearchLoading();
        }
        return originalPerformSearch(query);
    };
    
    // ========================================
    // ACCESSIBILITY IMPROVEMENTS
    // ========================================
    
    // Enhanced keyboard navigation for search results
    function initializeAccessibilityFeatures() {
        const searchResultsSection = document.getElementById('search-results');
        
        // Skip to search results link
        const skipLink = document.createElement('a');
        skipLink.href = '#search-results';
        skipLink.className = 'sr-only sr-only-focusable btn btn-primary';
        skipLink.textContent = 'Arama sonuçlarına geç';
        skipLink.style.position = 'absolute';
        skipLink.style.top = '10px';
        skipLink.style.left = '10px';
        skipLink.style.zIndex = '9999';
        document.body.insertBefore(skipLink, document.body.firstChild);
        
        // Announce search results to screen readers
        const announcer = document.createElement('div');
        announcer.setAttribute('aria-live', 'polite');
        announcer.setAttribute('aria-atomic', 'true');
        announcer.className = 'sr-only';
        announcer.id = 'search-announcer';
        document.body.appendChild(announcer);
        
        // Update the displaySearchResults function to include announcements
        const originalDisplaySearchResults = window.displaySearchResults || displaySearchResults;
        window.displaySearchResults = function(results, query) {
            originalDisplaySearchResults(results, query);
            
            const announcer = document.getElementById('search-announcer');
            if (announcer) {
                if (results.length === 0) {
                    announcer.textContent = `${query} için hiç sonuç bulunamadı`;
                } else {
                    announcer.textContent = `${query} için ${results.length} sonuç bulundu`;
                }

            }
        };
    }
    
    // Initialize accessibility features
    initializeAccessibilityFeatures();
});
</script>
