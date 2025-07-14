<?php
// Veritabanından gerçek verileri çek
try {
    // Settings tablosundan dinamik verileri çek
    $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM settings");
    $stmt->execute();
    $settings = [];
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }

    // About sayfası hero verileri
    $about_hero = [
        'title' => $settings['site_name'] ?? 'Necat Derneği',
        'subtitle' => $settings['about_description'] ?? 'Necat Derneği hakkında...',
        'foundation_year' => $settings['foundation_year'] ?? '1995',
        'mission' => $settings['about_mission'] ?? '',
        'vision' => $settings['about_vision'] ?? '',
        'values' => $settings['about_values'] ?? ''
    ];

    // İstatistikler - önce settings'ten al, yoksa hesapla
    $hero_stats = [
        'experience_years' => date('Y') - (int)($settings['foundation_year'] ?? 1995),
        'total_projects' => (int)($settings['stats_projects'] ?? 0),
        'total_beneficiaries' => (int)($settings['stats_beneficiaries'] ?? 0),
        'total_volunteers' => (int)($settings['stats_volunteers'] ?? 0)
    ];

    // Eğer settings'te istatistik yoksa veritabanından hesapla
    if ($hero_stats['total_projects'] == 0) {
        $stmt = $pdo->prepare("SELECT 
            (SELECT COUNT(*) FROM projects WHERE status IN ('active', 'completed')) as total_projects,
            (SELECT SUM(beneficiaries) FROM projects WHERE status IN ('active', 'completed') AND beneficiaries IS NOT NULL) as total_families,
            (SELECT COUNT(*) FROM volunteer_applications WHERE status = 'approved') as total_volunteers,
            (SELECT COUNT(*) FROM team_members WHERE is_active = 1) as total_team,
            (SELECT SUM(collected_amount) FROM projects WHERE status IN ('active', 'completed')) as total_donations
        ");
        $stmt->execute();
        $db_stats = $stmt->fetch();
        
        if ($db_stats) {
            $hero_stats['total_projects'] = (int)($db_stats['total_projects'] ?? 0);
            $hero_stats['total_beneficiaries'] = (int)($db_stats['total_families'] ?? 0);
            $hero_stats['total_volunteers'] = (int)($db_stats['total_volunteers'] ?? 0);
        }
    }

    // Diğer sayfalar için eski veriler (geriye dönük uyumluluk)
    $stats = ['total_projects' => $hero_stats['total_projects'], 'total_families' => $hero_stats['total_beneficiaries'], 'total_volunteers' => $hero_stats['total_volunteers'], 'total_team' => 0, 'total_donations' => 0];
    
    // Yönetim kurulu üyelerini çek
    $stmt = $pdo->prepare("SELECT * FROM team_members WHERE category = 'yonetim' AND is_active = 1 ORDER BY sort_order ASC");
    $stmt->execute();
    $team_members = $stmt->fetchAll();

    // Başarılı projeleri çek
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE status = 'completed' ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $completed_projects = $stmt->fetchAll();

    // Kuruluş ilkelerini settings'ten al ve işle
    $values_string = $about_hero['values'] ?? '';
    $values_array = array_filter(array_map('trim', explode(',', $values_string)));
    
    // İlkeler için icon ve açıklama eşleştirmesi
    $principles_mapping = [
        'Hakk\'a Riayet' => [
            'icon' => 'fas fa-balance-scale',
            'description' => 'Allah\'ın emirlerine uygun hareket eder, hakkı gözetir ve adaleti savunuruz.'
        ],
        'Emanete Sadakat' => [
            'icon' => 'fas fa-handshake',
            'description' => 'Bize tevdi edilen her sorumluluğu titizlikle yerine getirir, güveni koruruz.'
        ],
        'Ahde Vefa' => [
            'icon' => 'fas fa-heart',
            'description' => 'Verdiğimiz sözlerde dururuz ve taahhütlerimizi eksiksiz yerine getiririz.'
        ],
        'İnsana Hürmet' => [
            'icon' => 'fas fa-users',
            'description' => 'Her insanın onur ve saygınlığını korur, insan haklarına saygı gösteririz.'
        ],
        'Adalet' => [
            'icon' => 'fas fa-gavel',
            'description' => 'Herkesi eşit görür, adil davranır ve hakkaniyeti gözetiriz.'
        ],
        'Vicdan' => [
            'icon' => 'fas fa-heart-pulse',
            'description' => 'Vicdani sorumluluklarımızı yerine getirir, içten ve samimi davranırız.'
        ],
        'Şeffaflık' => [
            'icon' => 'fas fa-eye',
            'description' => 'Tüm faaliyetlerimizi açık ve şeffaf şekilde yürütür, hesap verebilirlik ilkesiyle hareket ederiz.'
        ],
        'Sorumluluk' => [
            'icon' => 'fas fa-clipboard-check',
            'description' => 'Üstlendiğimiz her görevi sorumluluk bilinciyle yerine getirir, hesabını veririz.'
        ],
        'Dürüstlük' => [
            'icon' => 'fas fa-shield-alt',
            'description' => 'Doğruluk ve dürüstlük ilkelerimizle hareket eder, güvenilir oluruz.'
        ]
    ];
    
    // Dinamik ilkeler dizisi oluştur
    $about_content = ['principles' => []];
    foreach ($values_array as $value) {
        $clean_value = trim($value);
        if (isset($principles_mapping[$clean_value])) {
            $about_content['principles'][] = [
                'title' => $clean_value,
                'icon' => $principles_mapping[$clean_value]['icon'],
                'description' => $principles_mapping[$clean_value]['description']
            ];
        }
    }

    // Tarihçe verilerini settings'ten al ve dinamik olarak oluştur
    $history_timeline = [];
    $history_years = ['1995', '1998', '2005', '2010', '2020', '2024'];
    
    foreach ($history_years as $year) {
        $title_key = "history_{$year}_title";
        $desc_key = "history_{$year}_description";
        
        if (isset($settings[$title_key]) && isset($settings[$desc_key])) {
            $history_timeline[] = [
                'year' => $year,
                'title' => $settings[$title_key],
                'description' => $settings[$desc_key]
            ];
        }
    }

    // Faaliyet alanları için projelerden veri çek
    $stmt = $pdo->prepare("SELECT title, short_description, category, beneficiaries, status FROM projects WHERE status IN ('active', 'completed') ORDER BY sort_order ASC");
    $stmt->execute();
    $projects_data = $stmt->fetchAll();

    // Kategori bazında icon eşleştirmesi
    $category_icons = [
        'Sosyal Yardım' => 'fas fa-home',
        'Eğitim' => 'fas fa-graduation-cap',
        'Gıda Yardımı' => 'fas fa-moon',
        'Sosyal Hizmet' => 'fas fa-rings-wedding',
        'Acil Yardım' => 'fas fa-cut',
        'Sağlık' => 'fas fa-heartbeat',
        'Toplum Destek' => 'fas fa-users'
    ];

    // Faaliyet alanlarını dinamik olarak oluştur
    $activities_data = [];
    foreach ($projects_data as $project) {
        $category = $project['category'] ?? 'Genel';
        $icon = $category_icons[$category] ?? 'fas fa-hand-holding-heart';
        $beneficiaries = $project['beneficiaries'] ?? 0;
        $stats = $beneficiaries > 0 ? number_format($beneficiaries) . '+ Kişi' : 'Aktif';
        
        $activities_data[] = [
            'title' => $project['title'],
            'description' => $project['short_description'] ?? 'Proje açıklaması yükleniyor...',
            'icon' => $icon,
            'stats' => $stats
        ];
    }

} catch (PDOException $e) {
    // Fallback veriler
    $current_page = 'about'; // Sayfa içi navigasyon için
    $about_hero = [
        'title' => 'Necat Derneği',
        'subtitle' => 'İhtiyaç sahiplerine maddi ve manevi destek sağlamak amacıyla kurulmuş bir yardım kuruluşudur.',
        'foundation_year' => '1995',
        'mission' => 'İnsani değerler temelinde toplumsal dayanışmayı güçlendirmek.',
        'vision' => 'Toplumsal dayanışmanın en üst seviyede yaşandığı bir toplum inşa etmek.',
        'values' => 'Şeffaflık, Hesap Verebilirlik, Eşitlik, Adalet, Dayanışma'
    ];
    $hero_stats = ['experience_years' => date('Y') - 1995, 'total_projects' => 0, 'total_beneficiaries' => 0, 'total_volunteers' => 0];
    $stats = ['total_projects' => 0, 'total_families' => 0, 'total_volunteers' => 0, 'total_team' => 0, 'total_donations' => 0];
    $team_members = [];
    $completed_projects = [];
    $settings = [];
    
    // Fallback için basit ilkeler
    $about_content = [
        'principles' => [
            [
                'title' => 'Şeffaflık',
                'icon' => 'fas fa-eye',
                'description' => 'Tüm faaliyetlerimizi açık ve şeffaf şekilde yürütür, hesap verebilirlik ilkesiyle hareket ederiz.'
            ],
            [
                'title' => 'Hesap Verebilirlik',
                'icon' => 'fas fa-clipboard-check',
                'description' => 'Yaptığımız her işin hesabını verebilir, sorumluluklarımızı yerine getiririz.'
            ],
            [
                'title' => 'Dayanışma',
                'icon' => 'fas fa-hands-helping',
                'description' => 'Toplumsal birlik ve beraberliği güçlendiren dayanışma ruhuyla çalışırız.'
            ]
        ]
    ];
    
    // Fallback tarihçe verileri
    $history_timeline = [
        [
            'year' => '1995',
            'title' => 'Kuruluş',
            'description' => 'Necat Derneği, sosyal yardımlaşma amacıyla kuruldu.'
        ],
        [
            'year' => '1998',
            'title' => 'İlk Projeler',
            'description' => 'Eğitim burs programı ve gıda yardımı projeleri başlatıldı.'
        ],
        [
            'year' => '2020',
            'title' => 'Pandemi Desteği',
            'description' => 'COVID-19 salgını döneminde acil yardım programları hayata geçirildi.'
        ],
        [
            'year' => '2024',
            'title' => 'Büyüme',
            'description' => 'Sağlık, eğitim ve afet yardımı alanlarında projeler genişletildi.'
        ]
    ];
    
    // Fallback faaliyet alanları
    $activities_data = [
        [
            'title' => 'Gıda ve Giyim Yardımı',
            'description' => 'Yetim, yoksul ve kimsesiz ailelere temel gıda, yakacak ve giyecek yardımları ulaştırıyoruz.',
            'icon' => 'fas fa-home',
            'stats' => '1000+ Kişi'
        ],
        [
            'title' => 'Kırtasiye Yardımı',
            'description' => 'Maddi durumu yetersiz olan öğrencilere okul dönemlerinde defter, kalem, çanta gibi ihtiyaçlar sağlıyoruz.',
            'icon' => 'fas fa-graduation-cap',
            'stats' => '500+ Kişi'
        ],
        [
            'title' => 'Burs Destek Programı',
            'description' => 'Eğitimine devam edebilmesi için yardıma ihtiyacı olan öğrencilere aylık burs desteği veriyoruz.',
            'icon' => 'fas fa-graduation-cap',
            'stats' => '250+ Kişi'
        ]
    ];
}
?>



<?php 
// Sayfa içi navigasyon menüsünü ekle
$current_page = 'about';
include 'includes/page_navigation.php'; 
?>

<!-- Simple Hero Section -->
<section class="hero-section bg-white" id="misyon">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-light text-dark mb-4">Hakkımızda</h1>
                <p class="lead text-muted mb-5">
                    <?= htmlspecialchars($about_hero['subtitle']) ?>
                </p>
                <div class="row g-3 mb-5">
                    <div class="col-md-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent mb-1"><?= $hero_stats['experience_years'] ?>+</h3>
                            <small class="stat-label-muted">Yıl Deneyim</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent mb-1"><?= number_format($hero_stats['total_beneficiaries']) ?>+</h3>
                            <small class="stat-label-muted">Yararlanıcı</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent mb-1"><?= $hero_stats['total_projects'] ?></h3>
                            <small class="stat-label-muted">Proje</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent mb-1"><?= $hero_stats['total_volunteers'] ?>+</h3>
                            <small class="stat-label-muted">Gönüllü</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Simple Mission & Vision -->
<section class="py-5 bg-light" id="vizyon">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="simple-card h-100">
                    <div class="simple-card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-success text-white me-3">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h3 class="h4 mb-0">Misyonumuz</h3>
                        </div>
                        <p class="text-muted">
                            <?= nl2br(htmlspecialchars($about_hero['mission'] ?? 'Misyon bilgisi yükleniyor...')) ?>
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
                            <?= nl2br(htmlspecialchars($about_hero['vision'] ?? 'Vizyon bilgisi yükleniyor...')) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kuruluş İlkelerimiz -->
<section class="py-5" style="background-color: #fafafa;" id="degerlerimiz">
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
                                <i class="<?= $principle['icon'] ?> fa-2x text-success"></i>
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

<!-- Simple Values
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
                        <i class="fas fa-hand-holding-heart fa-lg text-success"></i>
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
                        <i class="fas fa-eye fa-lg text-success"></i>
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
                        <i class="fas fa-shield-alt fa-lg text-success"></i>
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
                        <i class="fas fa-users fa-lg text-success"></i>
                    </div>
                    <h5 class="fw-semibold text-dark mb-3">Dayanışma</h5>
                    <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                        Birlikte daha güçlü olduğumuzun bilinciyle hareket ederiz.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section> -->

<!-- Tarihçe 
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
</section>-->

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
            // Faaliyet alanları verisi artık dinamik olarak projects tablosundan geliyor
            foreach ($activities_data as $index => $activity): 
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm" style="transition: transform 0.3s ease;">
                    <div class="card-body p-4 text-center">
                        <!-- Icon -->
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light" 
                                 style="width: 80px; height: 80px;">
                                <i class="<?= $activity['icon'] ?> fa-2x text-success"></i>
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
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
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

<!-- Simple Statistics 
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="h3 fw-light">Rakamlarla Başarılarımız</h2>
        </div>
        
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-3">
                    <h3 class="text-success mb-1">25</h3>
                    <small class="text-muted">Tamamlanan Proje</small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-3">
                    <h3 class="text-success mb-1">500+</h3>
                    <small class="text-muted">Yardım Ulaştırılan Aile</small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-3">
                    <h3 class="text-success mb-1">120</h3>
                    <small class="text-muted">Aktif Gönüllü</small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-3">
                    <h3 class="text-success mb-1">150K ₺</h3>
                    <small class="text-muted">Toplam Bağış</small>
                </div>
            </div>
        </div>
    </div>
</section>-->

<!-- Simple CTA -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="h3 fw-light mb-4">Birlikte Daha Güçlü Olalım</h2>
                <p class="lead mb-4 opacity-90">
                    Sen de bu anlamlı yolculuğumuzda yer al ve toplumsal değişimin bir parçası ol.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?php echo site_url('volunteer'); ?>" class="btn btn-light btn-lg px-4 py-2">
                        <i class="fas fa-hands-helping me-2"></i> Gönüllü Ol
                    </a>
                    <a href="<?php echo site_url('donate'); ?>" class="btn btn-outline-light btn-lg px-4 py-2">
                        <i class="fas fa-heart me-2"></i> Bağış Yap
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CSS for Timeline -->
<style>
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

/* Statistics Styling with Consistent Logo Color */
.stat-simple {
    text-align: center;
    padding: 1rem 0.5rem;
    transition: all 0.3s ease;
    border-radius: 8px;
}

.stat-simple:hover {
    transform: translateY(-2px);
}

/* Consistent color for all statistics */
.stat-number-consistent {
    color: #4ea674 !important;
    font-weight: 600;
    font-size: 2rem;
}

.stat-label-muted {
    color: #6c757d !important;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

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
    background: var(--success-color);
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
    background: var(--success-color);
    border: 3px solid white;
    box-shadow: 0 0 0 3px var(--success-color);
}

.timeline-date {
    font-weight: bold;
    color: var(--success-color);
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
