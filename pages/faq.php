<?php
require_once '../config/database.php';

// SSS kategorileri ve soruları
$faq_categories = [
    'general' => [
        'title' => 'Genel Sorular',
        'icon' => 'fas fa-info-circle',
        'questions' => [
            [
                'question' => 'Necat Derneği ne zaman ve neden kuruldu?',
                'answer' => 'Necat Derneği, 2015 yılında ihtiyaç sahiplerine yardım etmek ve toplumsal dayanışmayı güçlendirmek amacıyla kurulmuştur. Derneğimiz, "Necat" kelimesinin anlamı olan "kurtuluş" ilkesiyle hareket ederek, zor durumda olan insanlara umut ve destek olmayı misyon edinmiştir.'
            ],
            [
                'question' => 'Derneğinizin faaliyet alanları nelerdir?',
                'answer' => 'Acil yardım, eğitim desteği, sağlık hizmetleri, gıda yardımı, barınma desteği, sosyal projeler ve toplumsal farkındalık çalışmaları başlıca faaliyet alanlarımızdır. Ayrıca afet anlarında hızlı müdahale ekiplerimizle sahada bulunuruz.'
            ],
            [
                'question' => 'Derneğiniz hangi bölgelerde faaliyet gösteriyor?',
                'answer' => 'Öncelikli olarak Türkiye genelinde faaliyet gösteriyoruz. Ayrıca, gerektiğinde uluslararası acil yardım çalışmalarına da katılım sağlıyoruz. Yerel ihtiyaçları karşılamanın yanı sıra küresel dayanışmanın da önemli olduğuna inanıyoruz.'
            ],
            [
                'question' => 'Derneğinizin yasal statüsü nedir?',
                'answer' => 'Necat Derneği, Türkiye Cumhuriyeti İçişleri Bakanlığı\'na bağlı olarak faaliyet gösteren, resmi izinleri bulunan bir sivil toplum kuruluşudur. Tüm mali işlemlerimiz şeffaf bir şekilde yürütülür ve düzenli olarak denetlenir.'
            ]
        ]
    ],
    'donation' => [
        'title' => 'Bağış ve Yardım',
        'icon' => 'fas fa-heart',
        'questions' => [
            [
                'question' => 'Nasıl bağış yapabilirim?',
                'answer' => 'Bağış yapmak için web sitemizin "Bağış Yap" bölümünü kullanabilir, IBAN numaralarımıza doğrudan transfer yapabilir veya dernek merkezimizi ziyaret edebilirsiniz. Ayrıca telefon ile de bağış kabul ediyoruz.'
            ],
            [
                'question' => 'Bağışımın hangi amaçla kullanıldığını nasıl öğrenebilirim?',
                'answer' => 'Tüm bağışlar için detaylı raporlama sistemi kullanıyoruz. Bağışçılarımıza düzenli olarak faaliyet raporları gönderiliyor ve web sitemizde şeffaflık ilkesi gereği tüm harcamalar paylaşılıyor. Ayrıca istediğiniz zaman dernek merkezimizi ziyaret ederek bilgi alabilirsiniz.'
            ],
            [
                'question' => 'Ayni bağış kabul ediyor musunuz?',
                'answer' => 'Evet, temiz ve kullanılabilir durumda olan giysi, gıda, eğitim malzemeleri, ev eşyaları ve diğer ihtiyaç malzemelerini kabul ediyoruz. Ayni bağış öncesi lütfen bizimle iletişime geçerek hangi malzemelere ihtiyaç olduğunu öğreniniz.'
            ],
            [
                'question' => 'Vergi indirimi alabilir miyim?',
                'answer' => 'Evet, derneğimiz vergi muafiyeti bulunan bir kuruluş olduğu için bağışlarınız için vergi indirimi alabilirsiniz. Bağış makbuzunuzu saklamanız ve beyanname döneminde kullanmanız gerekmektedir.'
            ],
            [
                'question' => 'Kurban ve fidye bağışı kabul ediyor musunuz?',
                'answer' => 'Evet, kurban ve fidye bağışlarınızı kabul ediyoruz. Bu konuda özel organizasyonlar düzenleyerek ihtiyaç sahiplerine ulaştırıyoruz. Ramazan ve Kurban Bayramı dönemlerinde özel kampanyalarımız bulunmaktadır.'
            ]
        ]
    ],
    'volunteer' => [
        'title' => 'Gönüllülük',
        'icon' => 'fas fa-hands-helping',
        'questions' => [
            [
                'question' => 'Gönüllü olmak için hangi şartları sağlamalıyım?',
                'answer' => '18 yaşını doldurmuş olmak, dernek değerlerimizi benimsemek ve düzenli katılım sağlayabilmek temel şartlarımızdır. Özel beceri gerektiren alanlarda ilgili deneyim ve sertifikalar aranabilir.'
            ],
            [
                'question' => 'Gönüllü çalışmalar için herhangi bir ücret ödeniyor mu?',
                'answer' => 'Gönüllü çalışmalarımız tamamen karşılıksız olarak yapılmaktadır. Ancak, uzun süreli saha çalışmalarında temel ihtiyaçlar (yemek, konaklama, ulaşım) dernek tarafından karşılanabilir.'
            ],
            [
                'question' => 'Hangi alanlarda gönüllü olabilirim?',
                'answer' => 'Acil yardım, eğitim desteği, sağlık hizmetleri, teknoloji, medya-iletişim, proje yönetimi, etkinlik organizasyonu ve idari işler gibi birçok alanda gönüllü olabilirsiniz. Yeteneklerinize uygun alanlarda değerlendirilirsiniz.'
            ],
            [
                'question' => 'Gönüllü eğitimi veriliyor mu?',
                'answer' => 'Evet, tüm gönüllülerimize temel eğitim programı uygulanır. Ayrıca özel alanlarda çalışacak gönüllüler için ilgili konularda ek eğitimler düzenlenir. İlk yardım, kriz yönetimi gibi temel eğitimler zorunludur.'
            ]
        ]
    ],
    'projects' => [
        'title' => 'Projeler',
        'icon' => 'fas fa-project-diagram',
        'questions' => [
            [
                'question' => 'Projelere nasıl destek olabilirim?',
                'answer' => 'Projelerimize maddi bağış, ayni yardım, gönüllü katılım veya sosyal medyada paylaşım yaparak destek olabilirsiniz. Her proje için farklı destek türleri gerekebilir.'
            ],
            [
                'question' => 'Proje önerisinde bulunabilir miyim?',
                'answer' => 'Elbette! Toplumsal fayda sağlayacak proje önerilerinizi değerlendiriyoruz. Öneri formumuzu doldurarak veya doğrudan iletişime geçerek önerilerinizi paylaşabilirsiniz.'
            ],
            [
                'question' => 'Projeler nasıl seçilir ve planlanır?',
                'answer' => 'Projeler, toplumsal ihtiyaç analizi, kaynak durumu, etki değerlendirmesi ve sürdürülebilirlik kriterleri göz önünde bulundurularak seçilir. Uzman ekibimiz tarafından detaylı planlama yapılır.'
            ],
            [
                'question' => 'Proje ilerlemesini nasıl takip edebilirim?',
                'answer' => 'Web sitemizin projeler bölümünde güncel ilerleme raporları paylaşılır. Ayrıca sosyal medya hesaplarımızdan ve bülten e-postalarımızdan da bilgi alabilirsiniz.'
            ]
        ]
    ],
    'contact' => [
        'title' => 'İletişim ve Ulaşım',
        'icon' => 'fas fa-phone',
        'questions' => [
            [
                'question' => 'Dernek merkezinizi ziyaret edebilir miyim?',
                'answer' => 'Evet, çalışma saatleri içinde dernek merkezimizi ziyaret edebilirsiniz. Önceden randevu almanızı tavsiye ederiz. Çalışma saatlerimiz: Pazartesi-Cuma 09:00-17:00, Cumartesi 09:00-14:00.'
            ],
            [
                'question' => 'Acil durumlarda nasıl ulaşabilirim?',
                'answer' => 'Acil durumlar için 7/24 hizmet veren acil yardım hattımız bulunmaktadır. Bu numara sadece gerçek acil durumlar için kullanılmalıdır. Genel sorularınız için normal iletişim kanallarını tercih ediniz.'
            ],
            [
                'question' => 'Sosyal medya hesaplarınız hangileri?',
                'answer' => 'Instagram, Facebook, Twitter ve YouTube hesaplarımız bulunmaktadır. Tüm platformlarda @necatdernegi kullanıcı adıyla bizi takip edebilirsiniz.'
            ],
            [
                'question' => 'E-posta ile gönderdiğim mesajlara ne kadar sürede yanıt alırım?',
                'answer' => 'Normal koşullarda 24-48 saat içinde e-postalarınıza yanıt vermeye çalışıyoruz. Yoğun dönemlerde bu süre 72 saate kadar uzayabilir. Acil durumlar için telefon iletişimini tercih ediniz.'
            ]
        ]
    ]
];
?>

<div class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-3">Sıkça Sorulan Sorular</h1>
                <p class="lead mb-0">Merak ettiğiniz soruların yanıtlarını burada bulabilirsiniz.</p>
            </div>
            <div class="col-lg-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent justify-content-lg-end">
                        <li class="breadcrumb-item"><a href="/" class="text-white">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">SSS</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Arama Bölümü -->
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

<!-- Kategori Navigasyonu -->
<section class="py-4 bg-white sticky-top border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="category-nav">
                    <div class="nav nav-pills justify-content-center flex-wrap" id="faq-category-nav">
                        <?php foreach ($faq_categories as $key => $category): ?>
                            <button class="nav-link <?= $key === 'general' ? 'active' : '' ?>" 
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
            <div class="faq-category <?= $categoryKey !== 'general' ? 'd-none' : '' ?>" 
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

<!-- Yardım İhtiyacı -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="help-section p-5 bg-white rounded shadow-sm">
                    <div class="help-icon mb-4">
                        <i class="fas fa-question-circle fa-4x text-primary"></i>
                    </div>
                    <h3 class="help-title mb-3">Aradığınız Cevabı Bulamadınız mı?</h3>
                    <p class="help-description mb-4">
                        Sorularınız için bizimle iletişime geçmekten çekinmeyin. 
                        Size yardımcı olmak için buradayız.
                    </p>
                    <div class="help-actions">
                        <a href="/contact" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-envelope me-2"></i>
                            İletişime Geç
                        </a>
                        <a href="tel:+90555123456" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-phone me-2"></i>
                            Ara
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popüler Konular -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Popüler Konular</h2>
                <p class="section-subtitle">En çok merak edilen konular</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="popular-topic bg-white rounded shadow-sm p-4 h-100">
                    <div class="topic-icon mb-3">
                        <i class="fas fa-heart fa-2x text-danger"></i>
                    </div>
                    <h5 class="topic-title mb-2">Bağış Yapma</h5>
                    <p class="topic-description text-muted mb-3">Bağış yapma yöntemleri ve süreci hakkında bilgiler</p>
                    <a href="#" class="btn btn-outline-primary btn-sm" data-category="donation">
                        Detayları Gör <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="popular-topic bg-white rounded shadow-sm p-4 h-100">
                    <div class="topic-icon mb-3">
                        <i class="fas fa-hands-helping fa-2x text-success"></i>
                    </div>
                    <h5 class="topic-title mb-2">Gönüllü Olmak</h5>
                    <p class="topic-description text-muted mb-3">Gönüllülük başvurusu ve süreç bilgileri</p>
                    <a href="#" class="btn btn-outline-primary btn-sm" data-category="volunteer">
                        Detayları Gör <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="popular-topic bg-white rounded shadow-sm p-4 h-100">
                    <div class="topic-icon mb-3">
                        <i class="fas fa-project-diagram fa-2x text-info"></i>
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
</section>

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
