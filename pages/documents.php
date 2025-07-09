<?php
require_once '../config/database.php';

// Belge kategorileri ve dosyaları
$document_categories = [
    'legal' => [
        'title' => 'Yasal Belgeler',
        'icon' => 'fas fa-gavel',
        'description' => 'Derneğimizin yasal statüsü ve resmi belgeleri',
        'documents' => [
            [
                'title' => 'Dernek Kuruluş Belgesi',
                'description' => 'Necat Derneği\'nin resmi kuruluş belgesi',
                'file' => 'kurulis_belgesi.pdf',
                'size' => '1.2 MB',
                'date' => '2015-03-15',
                'downloads' => 245
            ],
            [
                'title' => 'Dernek Tüzüğü',
                'description' => 'Derneğimizin çalışma prensiplerini belirleyen tüzük',
                'file' => 'dernek_tuzugu.pdf',
                'size' => '850 KB',
                'date' => '2023-01-10',
                'downloads' => 189
            ],
            [
                'title' => 'Vergi Muafiyet Belgesi',
                'description' => 'Bağışlar için vergi muafiyeti belgesi',
                'file' => 'vergi_muafiyet_belgesi.pdf',
                'size' => '650 KB',
                'date' => '2023-01-05',
                'downloads' => 156
            ],
            [
                'title' => 'Faaliyet İzin Belgesi',
                'description' => 'İçişleri Bakanlığı faaliyet izin belgesi',
                'file' => 'faaliyet_izin_belgesi.pdf',
                'size' => '720 KB',
                'date' => '2023-01-01',
                'downloads' => 134
            ]
        ]
    ],
    'financial' => [
        'title' => 'Mali Belgeler',
        'icon' => 'fas fa-chart-pie',
        'description' => 'Mali raporlar ve şeffaflık belgeleri',
        'documents' => [
            [
                'title' => 'Faaliyet Raporu 2023',
                'description' => '2023 yılı faaliyet raporu ve değerlendirmeler',
                'file' => 'faaliyet_raporu_2023.pdf',
                'size' => '3.2 MB',
                'date' => '2024-01-15',
                'downloads' => 678
            ],
            [
                'title' => 'Mali Rapor 2023',
                'description' => '2023 yılı gelir-gider ve bilanço raporu',
                'file' => 'mali_rapor_2023.pdf',
                'size' => '1.8 MB',
                'date' => '2024-01-15',
                'downloads' => 543
            ],
            [
                'title' => 'Bağış Kullanım Raporu 2023',
                'description' => 'Toplanan bağışların kullanım detayları',
                'file' => 'bagis_kullanim_raporu_2023.pdf',
                'size' => '2.1 MB',
                'date' => '2024-01-20',
                'downloads' => 432
            ],
            [
                'title' => 'Denetim Raporu 2023',
                'description' => 'Bağımsız denetim kuruluşu raporu',
                'file' => 'denetim_raporu_2023.pdf',
                'size' => '1.5 MB',
                'date' => '2024-01-25',
                'downloads' => 298
            ]
        ]
    ],
    'projects' => [
        'title' => 'Proje Belgeleri',
        'icon' => 'fas fa-project-diagram',
        'description' => 'Proje raporları ve sonuç belgeleri',
        'documents' => [
            [
                'title' => 'Proje Katalogu 2023',
                'description' => 'Gerçekleştirilen tüm projelerin katalogu',
                'file' => 'proje_katalogu_2023.pdf',
                'size' => '5.8 MB',
                'date' => '2024-01-10',
                'downloads' => 387
            ],
            [
                'title' => 'Eğitim Projesi Sonuç Raporu',
                'description' => 'Çocuklar için eğitim projesi sonuçları',
                'file' => 'egitim_projesi_raporu.pdf',
                'size' => '2.3 MB',
                'date' => '2023-12-15',
                'downloads' => 234
            ],
            [
                'title' => 'Acil Yardım Projesi Raporu',
                'description' => 'Afet bölgelerinde gerçekleştirilen yardımlar',
                'file' => 'acil_yardim_raporu.pdf',
                'size' => '3.1 MB',
                'date' => '2023-11-20',
                'downloads' => 456
            ],
            [
                'title' => 'Sağlık Projesi Değerlendirmesi',
                'description' => 'Sağlık hizmetleri projesi değerlendirme raporu',
                'file' => 'saglik_projesi_raporu.pdf',
                'size' => '1.9 MB',
                'date' => '2023-10-30',
                'downloads' => 187
            ]
        ]
    ],
    'policies' => [
        'title' => 'Politikalar ve Prosedürler',
        'icon' => 'fas fa-file-contract',
        'description' => 'Kurumsal politikalar ve iş prosedürleri',
        'documents' => [
            [
                'title' => 'Kişisel Verilerin Korunması Politikası',
                'description' => 'KVKK uyum ve kişisel veri koruma politikası',
                'file' => 'kvkk_politikasi.pdf',
                'size' => '980 KB',
                'date' => '2023-05-15',
                'downloads' => 567
            ],
            [
                'title' => 'Gönüllü Çalışma Prosedürleri',
                'description' => 'Gönüllüler için çalışma kuralları ve prosedürler',
                'file' => 'gonullu_prosedürleri.pdf',
                'size' => '760 KB',
                'date' => '2023-03-20',
                'downloads' => 345
            ],
            [
                'title' => 'Bağış Kabul ve Kullanım Politikası',
                'description' => 'Bağışların kabul edilmesi ve kullanılması kuralları',
                'file' => 'bagis_politikasi.pdf',
                'size' => '680 KB',
                'date' => '2023-02-10',
                'downloads' => 289
            ],
            [
                'title' => 'Şeffaflık ve Hesap Verebilirlik Politikası',
                'description' => 'Kurumsal şeffaflık ve hesap verebilirlik ilkeleri',
                'file' => 'seffaflik_politikasi.pdf',
                'size' => '590 KB',
                'date' => '2023-01-30',
                'downloads' => 234
            ]
        ]
    ]
];

// Toplam belge sayısı ve indirme istatistikleri
$total_documents = 0;
$total_downloads = 0;
foreach ($document_categories as $category) {
    $total_documents += count($category['documents']);
    foreach ($category['documents'] as $doc) {
        $total_downloads += $doc['downloads'];
    }
}
?>

<div class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-3">Belgeler</h1>
                <p class="lead mb-0">Şeffaflık ilkemiz gereği tüm belgelerimiz sizlerle paylaşılmaktadır.</p>
            </div>
            <div class="col-lg-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent justify-content-lg-end">
                        <li class="breadcrumb-item"><a href="/" class="text-white">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Belgeler</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Belge İstatistikleri -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-file-alt fa-3x text-primary"></i>
                    </div>
                    <h3 class="stats-number"><?= $total_documents ?></h3>
                    <p class="stats-label mb-0">Toplam Belge</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-download fa-3x text-success"></i>
                    </div>
                    <h3 class="stats-number"><?= number_format($total_downloads) ?></h3>
                    <p class="stats-label mb-0">Toplam İndirme</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-folder fa-3x text-info"></i>
                    </div>
                    <h3 class="stats-number"><?= count($document_categories) ?></h3>
                    <p class="stats-label mb-0">Kategori</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="stats-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-warning"></i>
                    </div>
                    <h3 class="stats-number">100%</h3>
                    <p class="stats-label mb-0">Şeffaflık</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Arama Bölümü -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="search-box">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" id="documentSearch" 
                               placeholder="Belge ara...">
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
<section class="py-4 bg-light sticky-top">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="category-nav">
                    <div class="nav nav-pills justify-content-center flex-wrap" id="document-category-nav">
                        <?php foreach ($document_categories as $key => $category): ?>
                            <button class="nav-link <?= $key === 'legal' ? 'active' : '' ?>" 
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

<!-- Belge Kategorileri -->
<section class="py-5">
    <div class="container">
        <?php foreach ($document_categories as $categoryKey => $category): ?>
            <div class="document-category <?= $categoryKey !== 'legal' ? 'd-none' : '' ?>" 
                 id="category-<?= $categoryKey ?>">
                <div class="row mb-5">
                    <div class="col-lg-8 mx-auto text-center">
                        <h2 class="section-title">
                            <i class="<?= $category['icon'] ?> me-3 text-primary"></i>
                            <?= $category['title'] ?>
                        </h2>
                        <p class="section-subtitle"><?= $category['description'] ?></p>
                    </div>
                </div>

                <div class="row g-4">
                    <?php foreach ($category['documents'] as $document): ?>
                        <div class="col-lg-6">
                            <div class="document-card bg-white rounded shadow-sm p-4 h-100">
                                <div class="d-flex align-items-start">
                                    <div class="document-icon me-3">
                                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                    </div>
                                    <div class="document-info flex-grow-1">
                                        <h5 class="document-title mb-2"><?= htmlspecialchars($document['title']) ?></h5>
                                        <p class="document-description text-muted mb-3"><?= htmlspecialchars($document['description']) ?></p>
                                        
                                        <div class="document-meta">
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-file-archive me-1"></i>
                                                        <?= $document['size'] ?>
                                                    </small>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        <?= date('d.m.Y', strtotime($document['date'])) ?>
                                                    </small>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-download me-1"></i>
                                                        <?= number_format($document['downloads']) ?> indirme
                                                    </small>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-success">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        Doğrulanmış
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="document-actions">
                                            <a href="/downloads/documents/<?= $document['file'] ?>" 
                                               class="btn btn-primary btn-sm me-2" 
                                               download
                                               onclick="trackDownload('<?= $categoryKey ?>', '<?= $document['file'] ?>')">
                                                <i class="fas fa-download me-1"></i>
                                                İndir
                                            </a>
                                            <button class="btn btn-outline-secondary btn-sm" 
                                                    onclick="previewDocument('<?= $document['file'] ?>')">
                                                <i class="fas fa-eye me-1"></i>
                                                Önizle
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Şeffaflık Taahhüdü -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="transparency-commitment p-5 bg-white rounded shadow-sm">
                    <div class="commitment-icon mb-4">
                        <i class="fas fa-certificate fa-4x text-primary"></i>
                    </div>
                    <h3 class="commitment-title mb-3">Şeffaflık Taahhüdümüz</h3>
                    <p class="commitment-description mb-4">
                        Necat Derneği olarak, tüm faaliyetlerimizde şeffaflık ilkesini benimser, 
                        hesap verebilirlik sorumluluğumuzu yerine getiririz. Belgelerimiz düzenli 
                        olarak güncellenir ve kamuoyuyla paylaşılır.
                    </p>
                    <div class="commitment-features">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <i class="fas fa-check-circle text-success mb-2"></i>
                                    <small class="d-block">Düzenli Güncelleme</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <i class="fas fa-shield-alt text-success mb-2"></i>
                                    <small class="d-block">Güvenli Arşivleme</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <i class="fas fa-users text-success mb-2"></i>
                                    <small class="d-block">Halka Açık</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Belge Önizleme Modal -->
<div class="modal fade" id="documentPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Belge Önizleme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="documentPreviewContent" style="height: 70vh;">
                    <!-- PDF önizleme buraya yüklenecek -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <a href="#" id="downloadFromPreview" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i> İndir
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kategori navigasyonu
    const categoryButtons = document.querySelectorAll('[data-category]');
    const categoryContents = document.querySelectorAll('.document-category');
    
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
    const searchInput = document.getElementById('documentSearch');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch(this.value.trim());
        }, 300);
    });
});

function performSearch(query) {
    const documentCards = document.querySelectorAll('.document-card');
    const categories = document.querySelectorAll('.document-category');
    
    if (query.length < 2) {
        // Arama iptal - tüm belgeleri göster ve ilk kategoriyi aktif yap
        categories.forEach(cat => cat.classList.add('d-none'));
        document.getElementById('category-legal').classList.remove('d-none');
        return;
    }
    
    let hasResults = false;
    
    categories.forEach(category => {
        category.classList.remove('d-none');
        const cards = category.querySelectorAll('.document-card');
        let categoryHasResults = false;
        
        cards.forEach(card => {
            const title = card.querySelector('.document-title').textContent.toLowerCase();
            const description = card.querySelector('.document-description').textContent.toLowerCase();
            
            if (title.includes(query.toLowerCase()) || description.includes(query.toLowerCase())) {
                card.style.display = 'block';
                categoryHasResults = true;
                hasResults = true;
            } else {
                card.style.display = 'none';
            }
        });
        
        if (!categoryHasResults) {
            category.classList.add('d-none');
        }
    });
    
    if (!hasResults) {
        // Sonuç bulunamadı mesajı göster
        console.log('Arama sonucu bulunamadı');
    }
}

function previewDocument(fileName) {
    const modal = new bootstrap.Modal(document.getElementById('documentPreviewModal'));
    const previewContent = document.getElementById('documentPreviewContent');
    const downloadLink = document.getElementById('downloadFromPreview');
    
    // PDF önizleme için iframe kullan
    previewContent.innerHTML = `
        <iframe src="/downloads/documents/${fileName}" 
                width="100%" height="100%" 
                style="border: none;">
        </iframe>
    `;
    
    downloadLink.href = `/downloads/documents/${fileName}`;
    downloadLink.setAttribute('download', fileName);
    
    modal.show();
}

function trackDownload(category, fileName) {
    // İndirme istatistiklerini takip et
    fetch('/ajax/track_download.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            category: category,
            file: fileName,
            type: 'document'
        })
    })
    .catch(error => {
        console.error('Download tracking error:', error);
    });
}
</script>
