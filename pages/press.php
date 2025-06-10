<?php
require_once '../config/database.php';

// Basın açıklamaları ve haberler
$stmt = $pdo->prepare("SELECT * FROM news WHERE status = 'published' AND type IN ('press', 'news') ORDER BY created_at DESC LIMIT 20");
$stmt->execute();
$press_news = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Basın kiti bilgileri
$press_kit = [
    'logo_files' => [
        ['name' => 'Logo (PNG - Yüksek Çözünürlük)', 'file' => 'necat_logo_high.png', 'size' => '2.4 MB'],
        ['name' => 'Logo (JPG - Web)', 'file' => 'necat_logo_web.jpg', 'size' => '450 KB'],
        ['name' => 'Logo (SVG - Vektör)', 'file' => 'necat_logo_vector.svg', 'size' => '120 KB'],
        ['name' => 'Logo (Şeffaf Arka Plan)', 'file' => 'necat_logo_transparent.png', 'size' => '1.8 MB']
    ],
    'documents' => [
        ['name' => 'Kurum Bilgi Formu', 'file' => 'kurum_bilgi_formu.pdf', 'size' => '850 KB'],
        ['name' => 'Faaliyet Raporu 2023', 'file' => 'faaliyet_raporu_2023.pdf', 'size' => '3.2 MB'],
        ['name' => 'Mali Rapor 2023', 'file' => 'mali_rapor_2023.pdf', 'size' => '1.1 MB'],
        ['name' => 'Proje Katalogu', 'file' => 'proje_katalogu.pdf', 'size' => '5.8 MB']
    ],
    'photos' => [
        ['name' => 'Kurumsal Fotoğraflar (Zip)', 'file' => 'kurumsal_fotograflar.zip', 'size' => '25.3 MB'],
        ['name' => 'Proje Fotoğrafları (Zip)', 'file' => 'proje_fotograflari.zip', 'size' => '42.1 MB'],
        ['name' => 'Etkinlik Fotoğrafları (Zip)', 'file' => 'etkinlik_fotograflari.zip', 'size' => '18.7 MB']
    ]
];

// İstatistikler
$stmt = $db->prepare("SELECT COUNT(*) as total_news FROM news WHERE status = 'published'");
$stmt->execute();
$news_stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Medya iletişim bilgileri
$media_contacts = [
    [
        'name' => 'Ayşe Demir',
        'position' => 'Basın Sözcüsü',
        'phone' => '+90 (212) 555-0125',
        'email' => 'basin@necatdernegi.org',
        'photo' => 'ayse_demir.jpg'
    ],
    [
        'name' => 'Mehmet Kaya',
        'position' => 'İletişim Koordinatörü',
        'phone' => '+90 (212) 555-0126',
        'email' => 'iletisim@necatdernegi.org',
        'photo' => 'mehmet_kaya.jpg'
    ]
];
?>

<div class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-3">Basın ve Medya</h1>
                <p class="lead mb-0">Basın mensupları için haberler, belgeler ve iletişim bilgileri</p>
            </div>
            <div class="col-lg-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent justify-content-lg-end">
                        <li class="breadcrumb-item"><a href="/" class="text-white">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Basın</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Hızlı Bilgiler -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="quick-info-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="info-icon mb-3">
                        <i class="fas fa-newspaper fa-3x text-primary"></i>
                    </div>
                    <h3 class="info-number"><?= $news_stats['total_news'] ?></h3>
                    <p class="info-label mb-0">Basın Açıklaması</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="quick-info-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="info-icon mb-3">
                        <i class="fas fa-download fa-3x text-success"></i>
                    </div>
                    <h3 class="info-number"><?= count($press_kit['logo_files']) + count($press_kit['documents']) + count($press_kit['photos']) ?></h3>
                    <p class="info-label mb-0">İndirilebilir Dosya</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="quick-info-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="info-icon mb-3">
                        <i class="fas fa-users fa-3x text-info"></i>
                    </div>
                    <h3 class="info-number"><?= count($media_contacts) ?></h3>
                    <p class="info-label mb-0">Medya İletişim Sorumlusu</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="quick-info-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="info-icon mb-3">
                        <i class="fas fa-clock fa-3x text-warning"></i>
                    </div>
                    <h3 class="info-number">24/7</h3>
                    <p class="info-label mb-0">Medya Destek</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Basın Açıklamaları ve Haberler -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Son Basın Açıklamaları</h2>
                <p class="section-subtitle">Güncel haberlerimiz ve basın açıklamalarımız</p>
            </div>
        </div>

        <?php if (!empty($press_news)): ?>
            <div class="row g-4">
                <?php foreach (array_slice($press_news, 0, 6) as $news): ?>
                    <div class="col-lg-4 col-md-6">
                        <article class="news-card h-100 bg-white rounded shadow-sm">
                            <div class="news-image">
                                <img src="<?= !empty($news['image']) ? '../uploads/news/' . $news['image'] : '../assets/images/default-news.jpg' ?>" 
                                     alt="<?= htmlspecialchars($news['title']) ?>" class="img-fluid">
                                <div class="news-category">
                                    <span class="badge bg-primary"><?= $news['type'] === 'press' ? 'Basın' : 'Haber' ?></span>
                                </div>
                                <div class="news-date">
                                    <span class="badge bg-dark"><?= date('d.m.Y', strtotime($news['created_at'])) ?></span>
                                </div>
                            </div>
                            <div class="news-content p-4">
                                <h5 class="news-title mb-3">
                                    <a href="/news/<?= $news['id'] ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($news['title']) ?>
                                    </a>
                                </h5>
                                <p class="news-excerpt text-muted mb-3">
                                    <?= htmlspecialchars(substr($news['content'], 0, 120)) ?>...
                                </p>
                                <div class="news-actions d-flex justify-content-between align-items-center">
                                    <a href="/news/<?= $news['id'] ?>" class="btn btn-outline-primary btn-sm">
                                        Devamını Oku
                                    </a>
                                    <div class="share-buttons">
                                        <button class="btn btn-sm btn-outline-secondary" onclick="shareNews(<?= $news['id'] ?>)">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-5">
                <a href="/news" class="btn btn-primary btn-lg">
                    <i class="fas fa-newspaper me-2"></i>
                    Tüm Haberleri Görüntüle
                </a>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-newspaper fa-5x text-muted mb-4"></i>
                <h4 class="text-muted">Henüz basın açıklaması bulunmuyor</h4>
                <p class="text-muted">Yakında güncel haberlerimizi paylaşacağız.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Basın Kiti -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Basın Kiti</h2>
                <p class="section-subtitle">Logo dosyaları, kurumsal belgeler ve fotoğraflar</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Logo Dosyaları -->
            <div class="col-lg-4">
                <div class="press-kit-section bg-white rounded shadow-sm p-4 h-100">
                    <div class="section-header mb-4">
                        <div class="section-icon text-center mb-3">
                            <i class="fas fa-image fa-3x text-primary"></i>
                        </div>
                        <h4 class="section-title text-center">Logo Dosyaları</h4>
                    </div>
                    <div class="file-list">
                        <?php foreach ($press_kit['logo_files'] as $file): ?>
                            <div class="file-item d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                                <div class="file-info">
                                    <h6 class="file-name mb-1"><?= $file['name'] ?></h6>
                                    <small class="file-size text-muted"><?= $file['size'] ?></small>
                                </div>
                                <a href="/downloads/press/<?= $file['file'] ?>" class="btn btn-sm btn-primary" download>
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Belgeler -->
            <div class="col-lg-4">
                <div class="press-kit-section bg-white rounded shadow-sm p-4 h-100">
                    <div class="section-header mb-4">
                        <div class="section-icon text-center mb-3">
                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                        </div>
                        <h4 class="section-title text-center">Belgeler</h4>
                    </div>
                    <div class="file-list">
                        <?php foreach ($press_kit['documents'] as $file): ?>
                            <div class="file-item d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                                <div class="file-info">
                                    <h6 class="file-name mb-1"><?= $file['name'] ?></h6>
                                    <small class="file-size text-muted"><?= $file['size'] ?></small>
                                </div>
                                <a href="/downloads/press/<?= $file['file'] ?>" class="btn btn-sm btn-danger" download>
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Fotoğraflar -->
            <div class="col-lg-4">
                <div class="press-kit-section bg-white rounded shadow-sm p-4 h-100">
                    <div class="section-header mb-4">
                        <div class="section-icon text-center mb-3">
                            <i class="fas fa-images fa-3x text-success"></i>
                        </div>
                        <h4 class="section-title text-center">Fotoğraflar</h4>
                    </div>
                    <div class="file-list">
                        <?php foreach ($press_kit['photos'] as $file): ?>
                            <div class="file-item d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                                <div class="file-info">
                                    <h6 class="file-name mb-1"><?= $file['name'] ?></h6>
                                    <small class="file-size text-muted"><?= $file['size'] ?></small>
                                </div>
                                <a href="/downloads/press/<?= $file['file'] ?>" class="btn btn-sm btn-success" download>
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="/downloads/press/basin_kiti_tum_dosyalar.zip" class="btn btn-lg btn-primary" download>
                <i class="fas fa-download me-2"></i>
                Tüm Basın Kiti Dosyalarını İndir
            </a>
        </div>
    </div>
</section>

<!-- Medya İletişim -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Medya İletişim</h2>
                <p class="section-subtitle">Basın mensupları için özel iletişim kanalları</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($media_contacts as $contact): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="media-contact-card text-center bg-white rounded shadow-sm p-4 h-100">
                        <div class="contact-photo mb-3">
                            <img src="../assets/images/team/<?= $contact['photo'] ?>" 
                                 alt="<?= htmlspecialchars($contact['name']) ?>" 
                                 class="rounded-circle" width="100" height="100" style="object-fit: cover;">
                        </div>
                        <h5 class="contact-name"><?= htmlspecialchars($contact['name']) ?></h5>
                        <p class="contact-position text-primary mb-3"><?= htmlspecialchars($contact['position']) ?></p>
                        <div class="contact-info">
                            <p class="contact-phone mb-2">
                                <i class="fas fa-phone text-success me-2"></i>
                                <a href="tel:<?= str_replace([' ', '(', ')', '-'], '', $contact['phone']) ?>" class="text-decoration-none">
                                    <?= $contact['phone'] ?>
                                </a>
                            </p>
                            <p class="contact-email mb-0">
                                <i class="fas fa-envelope text-info me-2"></i>
                                <a href="mailto:<?= $contact['email'] ?>" class="text-decoration-none">
                                    <?= $contact['email'] ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Röportaj Talebi -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="interview-request bg-white rounded shadow p-5">
                    <div class="text-center mb-4">
                        <h2 class="section-title">Röportaj Talebi</h2>
                        <p class="section-subtitle">Basın mensupları için röportaj ve görüşme talep formu</p>
                    </div>

                    <form id="interviewRequestForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="journalist_name" class="form-label">Gazeteci Adı *</label>
                                <input type="text" class="form-control" id="journalist_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="media_organization" class="form-label">Medya Kuruluşu *</label>
                                <input type="text" class="form-control" id="media_organization" required>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label for="journalist_email" class="form-label">E-posta *</label>
                                <input type="email" class="form-control" id="journalist_email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="journalist_phone" class="form-label">Telefon *</label>
                                <input type="tel" class="form-control" id="journalist_phone" required>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label for="interview_type" class="form-label">Röportaj Türü *</label>
                                <select class="form-select" id="interview_type" required>
                                    <option value="">Seçiniz</option>
                                    <option value="phone">Telefon Röportajı</option>
                                    <option value="video">Video Röportaj</option>
                                    <option value="written">Yazılı Röportaj</option>
                                    <option value="facility_visit">Tesis Ziyareti</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="preferred_date" class="form-label">Tercih Edilen Tarih</label>
                                <input type="date" class="form-control" id="preferred_date">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="interview_topic" class="form-label">Röportaj Konusu *</label>
                            <input type="text" class="form-control" id="interview_topic" 
                                   placeholder="Hangi konu hakkında röportaj yapmak istiyorsunuz?" required>
                        </div>

                        <div class="mt-3">
                            <label for="questions" class="form-label">Sorular veya Detaylar</label>
                            <textarea class="form-control" id="questions" rows="4" 
                                      placeholder="Sormak istediğiniz sorular veya röportaj hakkında detaylar..."></textarea>
                        </div>

                        <div class="mt-3">
                            <label for="deadline" class="form-label">Yayın Tarihi/Son Tarih</label>
                            <input type="date" class="form-control" id="deadline">
                            <div class="form-text">Haberin yayınlanacağı tarih veya röportaj için son tarih</div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>
                                Röportaj Talebi Gönder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sosyal Medya Takip -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title mb-4">Sosyal Medyada Takip Edin</h2>
                <p class="section-subtitle mb-5">Güncel haberlerimizi kaçırmamak için bizi takip edin</p>
                
                <div class="social-follow-buttons">
                    <a href="https://twitter.com/necatdernegi" target="_blank" class="btn btn-outline-primary btn-lg mx-2 mb-3">
                        <i class="fab fa-twitter me-2"></i> Twitter'da Takip Et
                    </a>
                    <a href="https://instagram.com/necatdernegi" target="_blank" class="btn btn-outline-primary btn-lg mx-2 mb-3">
                        <i class="fab fa-instagram me-2"></i> Instagram'da Takip Et
                    </a>
                    <a href="https://youtube.com/necatdernegi" target="_blank" class="btn btn-outline-primary btn-lg mx-2 mb-3">
                        <i class="fab fa-youtube me-2"></i> YouTube'da Abone Ol
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Röportaj talebi formu
document.getElementById('interviewRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/ajax/interview_request.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Röportaj talebiniz alınmıştır. En kısa sürede size dönüş yapılacaktır.');
            this.reset();
        } else {
            alert('Talep gönderilirken bir hata oluştu. Lütfen tekrar deneyin.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu.');
    });
});

// Haber paylaşma
function shareNews(newsId) {
    const url = window.location.origin + '/news/' + newsId;
    
    if (navigator.share) {
        navigator.share({
            title: 'Necat Derneği Haberi',
            url: url
        });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            alert('Haber linki kopyalandı!');
        });
    }
}

// Tarih input'larını sınırla
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('preferred_date').setAttribute('min', today);
    document.getElementById('deadline').setAttribute('min', today);
});
</script>
