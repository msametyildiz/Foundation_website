<?php
// Veritabanından iletişim bilgilerini çek
try {
    // Site ayarlarını çek
    $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM site_settings");
    $stmt->execute();
    $site_settings = [];
    while ($row = $stmt->fetch()) {
        $site_settings[$row['setting_key']] = $row['setting_value'];
    }

    // İletişim formu işleme
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'contact') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (!empty($name) && !empty($email) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, created_at, status, ip_address) VALUES (?, ?, ?, ?, ?, NOW(), 'new', ?)");
                $stmt->execute([$name, $email, $phone, $subject, $message, $_SERVER['REMOTE_ADDR'] ?? '']);
                
                $success_message = "Mesajınız başarıyla gönderilmiştir. En kısa sürede size dönüş yapacağız.";
            } catch (PDOException $e) {
                $error_message = "Mesaj gönderilirken bir hata oluştu. Lütfen tekrar deneyin.";
            }
        } else {
            $error_message = "Lütfen tüm zorunlu alanları doldurun ve geçerli bir email adresi giriniz.";
        }
    }

    // İletişim bilgilerini ayarlardan çek
    $contact_info = [
        'address' => $site_settings['contact_address'] ?? 'Adres bilgisi güncelleniyor...',
        'phone' => $site_settings['contact_phone'] ?? '+90 312 444 56 78',
        'emergency' => $site_settings['emergency_phone'] ?? '+90 555 123 4567',
        'fax' => $site_settings['contact_fax'] ?? '',
        'email' => $site_settings['contact_email'] ?? 'info@necatdernegi.org',
        'working_hours' => [
            'weekdays' => 'Pazartesi - Cuma: 09:00 - 18:00',
            'saturday' => 'Cumartesi: 09:00 - 14:00',
            'sunday' => 'Pazar: Kapalı'
        ]
    ];

    // Sosyal medya hesaplarını ayarlardan çek
    $social_media = [
        ['platform' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'url' => $site_settings['facebook_url'] ?? '#', 'color' => '#1877f2'],
        ['platform' => 'Instagram', 'icon' => 'fab fa-instagram', 'url' => $site_settings['instagram_url'] ?? '#', 'color' => '#e4405f'],
        ['platform' => 'Twitter', 'icon' => 'fab fa-twitter', 'url' => $site_settings['twitter_url'] ?? '#', 'color' => '#1da1f2'],
        ['platform' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'url' => $site_settings['linkedin_url'] ?? '#', 'color' => '#0077b5']
    ];

} catch (PDOException $e) {
    $contact_info = [
        'address' => 'Kızılay Mahallesi, Atatürk Bulvarı No: 125/7, Çankaya/ANKARA',
        'phone' => '+90 312 444 56 78',
        'email' => 'info@necatdernegi.org'
    ];
    $social_media = [];
}
?>

<!-- Hero Section - Consistent with Projects/About Pages -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-3">İletişim</h1>
                <p class="lead mb-4">
                    Bize ulaşın, size yardımcı olmaktan mutluluk duyarız. Sorularınız, önerileriniz ve desteğiniz bizim için çok değerli.
                </p>
                
                <!-- Quick Stats -->
                <div class="row text-center mt-4">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent">24/7</h3>
                            <small class="stat-label-muted">Destek Hattı</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent">2</h3>
                            <small class="stat-label-muted">Saat İçinde</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent">500+</h3>
                            <small class="stat-label-muted">Başvuru/Ay</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-simple">
                            <h3 class="stat-number-consistent">6</h3>
                            <small class="stat-label-muted">İletişim Kanalı</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- İletişim Bilgileri Kartları -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary px-3 py-2 mb-3">İletişim Bilgileri</span>
            <h2>Bizimle İletişime Geçin</h2>
            <p class="text-muted">Farklı kanallardan bize ulaşabilirsiniz</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="contact-info-card text-center">
                    <div class="contact-icon-wrapper mb-4">
                        <div class="contact-icon bg-primary">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                    <h5 class="contact-card-title">Adresimiz</h5>
                    <p class="contact-card-text"><?= $contact_info['address'] ?></p>
                    <a href="https://maps.google.com/?q=<?= urlencode($contact_info['address']) ?>" 
                       target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-directions me-1"></i> Yol Tarifi
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="contact-info-card text-center">
                    <div class="contact-icon-wrapper mb-4">
                        <div class="contact-icon bg-primary">
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>
                    <h5 class="contact-card-title">Telefon</h5>
                    <p class="contact-card-text">
                        <strong>Genel:</strong> <?= $contact_info['phone'] ?><br>
                        <strong>Acil:</strong> <?= $contact_info['emergency'] ?>
                    </p>
                    <a href="tel:<?= str_replace([' ', '(', ')', '-'], '', $contact_info['phone']) ?>" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-phone me-1"></i> Ara
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="contact-info-card text-center">
                    <div class="contact-icon-wrapper mb-4">
                        <div class="contact-icon bg-primary">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    <h5 class="contact-card-title">E-posta</h5>
                    <p class="contact-card-text"><?= $contact_info['email'] ?></p>
                    <a href="mailto:<?= $contact_info['email'] ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-envelope me-1"></i> Mail Gönder
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="contact-info-card text-center">
                    <div class="contact-icon-wrapper mb-4">
                        <div class="contact-icon bg-primary">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <h5 class="contact-card-title">Çalışma Saatleri</h5>
                    <p class="contact-card-text">
                        <?= $contact_info['working_hours']['weekdays'] ?><br>
                        <?= $contact_info['working_hours']['saturday'] ?><br>
                        <?= $contact_info['working_hours']['sunday'] ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- İletişim Formu ve Harita -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- İletişim Formu -->
            <div class="col-lg-6">
                <div class="contact-form-wrapper">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary px-3 py-2 mb-3">Mesaj Gönder</span>
                        <h2>Bize Mesaj Gönderin</h2>
                        <p class="text-muted">Sorularınızı, önerilerinizi ve görüşlerinizi bizimle paylaşın</p>
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

                    <div class="contact-form-card">
                        <form method="POST" id="contactForm" novalidate>
                            <input type="hidden" name="action" value="contact">
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
                                    <label for="phone" class="form-label">Telefon</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Konu *</label>
                                    <select class="form-select" id="subject" name="subject" required>
                                        <option value="">Konu seçiniz</option>
                                        <option value="general">Genel Bilgi</option>
                                        <option value="donation">Bağış Konuları</option>
                                        <option value="volunteer">Gönüllülük</option>
                                        <option value="project">Proje Önerisi</option>
                                        <option value="complaint">Şikayet</option>
                                        <option value="media">Basın ve Medya</option>
                                        <option value="partnership">İş Birliği</option>
                                        <option value="other">Diğer</option>
                                    </select>
                                    <div class="invalid-feedback">Lütfen bir konu seçiniz.</div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="message" class="form-label">Mesajınız *</label>
                                <textarea class="form-control" id="message" name="message" rows="5" 
                                          placeholder="Mesajınızı buraya yazınız..." required></textarea>
                                <div class="invalid-feedback">Lütfen mesajınızı yazınız.</div>
                            </div>

                            <div class="mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacy" required>
                                    <label class="form-check-label" for="privacy">
                                        <span onclick="showKVKKModal(); return false;" style="color: #4ea674; text-decoration: none; font-weight: 600; cursor: pointer;">Kişisel Verilerin Korunması</span> hakkındaki bilgilendirilmeyi okudum ve kabul ediyorum. *
                                    </label>
                                    <div class="invalid-feedback">Lütfen gizlilik politikasını kabul ediniz.</div>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Mesaj Gönder
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Harita ve Ek Bilgiler -->
            <div class="col-lg-6">
                <div class="map-section-wrapper">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary px-3 py-2 mb-3">Konum</span>
                        <h2>Ofisimizi Ziyaret Edin</h2>
                        <p class="text-muted">Randevu alarak ofisimize gelebilirsiniz</p>
                    </div>
                    
                    <!-- Google Harita -->
                    <div class="map-container mb-4">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193595.9147730845!2d28.68271917253096!3d41.00543077985568!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14caa7040068086b%3A0xe1ccfe98bc01b0d0!2zxLBzdGFuYnVs!5e0!3m2!1str!2str!4v1703856000000!5m2!1str!2str" 
                                width="100%" height="350" style="border:0; border-radius: 15px;" allowfullscreen="" 
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                    <!-- Ulaşım Bilgileri -->
                    <div class="transport-info-card">
                        <h5 class="mb-3">
                            <i class="fas fa-route me-2 text-success"></i>
                            Ulaşım Bilgileri
                        </h5>
                        <div class="transport-options">
                            <div class="transport-option">
                                <i class="fas fa-subway text-success me-2"></i>
                                <strong>Metro:</strong> Vezneciler istasyonundan 5 dakika yürüyüş
                            </div>
                            <div class="transport-option">
                                <i class="fas fa-bus text-info me-2"></i>
                                <strong>Otobüs:</strong> 28, 61C, 399A hatları (Beyazıt durağı)
                            </div>
                            <div class="transport-option">
                                <i class="fas fa-car text-warning me-2"></i>
                                <strong>Araç:</strong> Eminönü yönünden 10 dakika
                            </div>
                            <div class="transport-option">
                                <i class="fas fa-parking text-secondary me-2"></i>
                                <strong>Park:</strong> Yakın çevrede ücretli park alanları mevcut
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sosyal Medya -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary px-3 py-2 mb-3">Sosyal Medya</span>
            <h2>Bizi Takip Edin</h2>
            <p class="text-muted">Haberlerimizi kaçırmamak ve güncel kalabilmek için sosyal medya hesaplarımızı takip edin</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="social-media-grid">
                    <?php foreach ($social_media as $social): ?>
                        <a href="<?= $social['url'] ?>" target="_blank" class="social-link-card">
                            <div class="social-icon">
                                <i class="<?= $social['icon'] ?>"></i>
                            </div>
                            <span class="social-name"><?= $social['platform'] ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Call to Action - Consistent with Projects/About Pages -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-3">Siz de Bu Anlamlı Yolculuğa Katılın</h2>
        <p class="lead mb-4">
            Sorularınızı sordunuz, artık harekete geçme zamanı. Gönüllü olun veya bağış yaparak destek olun.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="index.php?page=donate" class="btn btn-light btn-lg">
                <i class="fas fa-heart me-2"></i> Bağış Yap
            </a>
            <a href="index.php?page=volunteer" class="btn btn-outline-light btn-lg">
                <i class="fas fa-hands-helping me-2"></i> Gönüllü Ol
            </a>
        </div>
    </div>
</section>

<!-- Randevu Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Randevu Talebi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="appointmentForm">
                    <div class="mb-3">
                        <label for="appointment_name" class="form-label">Ad Soyad</label>
                        <input type="text" class="form-control" id="appointment_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_phone" class="form-label">Telefon</label>
                        <input type="tel" class="form-control" id="appointment_phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_date" class="form-label">Tercih Edilen Tarih</label>
                        <input type="date" class="form-control" id="appointment_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_time" class="form-label">Tercih Edilen Saat</label>
                        <select class="form-select" id="appointment_time" required>
                            <option value="">Saat seçiniz</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_purpose" class="form-label">Ziyaret Amacı</label>
                        <textarea class="form-control" id="appointment_purpose" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" onclick="submitAppointment()">Randevu Talebi Gönder</button>
            </div>
        </div>
    </div>
</div>

<!-- KVKK Modal -->
<div class="modal fade" id="kvkkModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-shield-alt me-2"></i>
                    Necat Derneği Kişisel Verilerin Korunması Kanunu Aydınlatma Metni ve Gönüllülük Esasları Raporu
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="kvkk-content">
                    <p class="lead">Bu rapor, Necat Derneği'nin 6698 sayılı Kişisel Verilerin Korunması Kanunu (KVKK) kapsamındaki aydınlatma yükümlülüklerini yerine getirmek ve gönüllülük faaliyetlerine ilişkin esasları belirlemek amacıyla hazırlanmıştır. Rapor, derneğin misyonu, vizyonu ve faaliyet alanları doğrultusunda, kişisel veri işleme süreçlerini ve gönüllülerin uyması gereken temel prensipleri detaylandırmaktadır.</p>
                    
                    <h4 class="mt-4 mb-3">1. Kişisel Verilerin Korunması Kanunu (KVKK) Kapsamında Aydınlatma Metni</h4>
                    <p>İşbu metin, 6698 sayılı Kişisel Verilerin Korunması Kanunu'nun ("KVKK") 10. maddesi ve Aydınlatma Yükümlülüğünün Yerine Getirilmesinde Uyulacak Usul ve Esaslar Hakkında Tebliğ uyarınca, veri sorumlusu sıfatıyla hareket eden Necat Derneği tarafından kişisel verilerinizin işlenmesine ilişkin olarak aydınlatma yükümlülüğünün yerine getirilmesi amacı ile hazırlanmıştır.</p>
                    
                    <h5 class="mt-4 mb-2">1.1. Hangi Kişisel Veriler İşlenmektedir?</h5>
                    <p>Necat Derneği gönüllülerinin ve derneğin faaliyetlerinden faydalanan kişilerin paylaşmasını talep etmiş olduğu veriler, derneğin yasal yükümlülüklerini yerine getirmesi ve hizmetlerini etkin bir şekilde sunabilmesi için gerekli olan bilgilerden oluşmaktadır. Bu veriler başlıca; Adı, Soyadı veya Unvanı, T.C. kimlik numarası, İmza, Adres bilgisi, E-posta adresi, Telefon numarası, Adli Sicil Kaydı ve Sağlık Raporu bilgileridir. Bu verilerin toplanması, derneğin şeffaflık, dürüstlük ve sorumluluk ilkeleriyle uyumlu olarak, faaliyetlerini güvenli ve yasal çerçevede sürdürmesini sağlamaktadır. Özellikle Adli Sicil Kaydı ve Sağlık Raporu bilgileri, gönüllülerin ve hizmet alanların güvenliğini temin etmek, dernek faaliyetlerinin hassasiyetine uygun hareket edildiğinden emin olmak için kritik öneme sahiptir.</p>
                    
                    <h5 class="mt-4 mb-2">1.2. Kişisel Veriler Neden İşlenir?</h5>
                    <p>Kişisel veriler, Necat Derneği'nin kuruluş ilkeleri, vizyonu ve misyonu doğrultusunda, tüm canlılar arasında dayanışma, saygı ve yardımlaşma prensibiyle hareket ederek, sağlanacak hizmetlerin yasal çerçevede tam ve zamanında sunulabilmesi amacıyla işlenmektedir. Derneğin temel faaliyet alanları arasında; yetim, yoksul ve kimsesiz ailelere yiyecek, yakacak, giyecek ve kırtasiye yardımı; yetim ve yoksul öğrencilere burs desteği; Ramazan ayında iftar organizasyonları; maddi imkansızlık yaşayan yetim ve yoksullara evlilik yardımları; adak, akika ve vacip kurban kesimleri ve dağıtımı; hastalara kan temini ve ilaç, tıbbi malzeme ve sağlık yardımları; yoksul ailelere yönelik sosyal ve manevi etkinlikler bulunmaktadır. Bu faaliyetlerin yanı sıra, gençlerin alkol ve uyuşturucu madde bağımlılığıyla mücadelesi, hayvan sevgisi ve korunması, eğitim kurumlarına katkı ve bağışlar gibi geniş bir yelpazede hizmet verilmektedir.</p>
                    
                    <p>Kişisel verilerin işlenmesi, derneğin gönüllüsü olmanız ve buna bağlı süreçlerin (gönüllü kaydı, faaliyetlere katılım, iletişim, eğitim vb.) yürütülmesi, derneğin hukuki yükümlülüklerini yerine getirmesi ve meşru menfaatlerini koruması amacıyla KVKK'nın 5. ve 6. maddelerinde belirtilen kişisel veri işleme şartları ve amaçları dâhilinde gerçekleştirilmektedir. Bu, derneğin "Hukuka Saygı" ilkesinin bir yansıması olarak, tüm operasyonlarının yasalara uygunluğunu garanti altına almaktadır.</p>
                    
                    <h5 class="mt-4 mb-2">1.3. İşlenen Veriler Kimlere Hangi Amaçla Aktarılabilir?</h5>
                    <p>İşlenen kişisel verileriniz, Necat Derneği'nin "Kardeşlik Seferberliği" vizyonu ve "İnsanların en iyisi, onlara faydası çok olandır" prensibi doğrultusunda, gönüllülük süreçlerinin yürütülmesi ve derneğin amaçlarına ulaşabilmesi için belirli üçüncü kişilere aktarılabilmektedir. Bu aktarımlar, derneğin hizmetlerini tam ve zamanında sunabilmesi için zorunlu olan iş süreçlerinin bir parçasıdır.</p>
                    
                    <p>Verileriniz, Necat Derneği'nin kendisi, bağışçıları ve destekçileri, tedarikçiler (örneğin, lojistik veya etkinlik organizasyon hizmeti sunanlar), iş birliği yapılan kurumlar (örneğin, Şanlıurfa'da okul yapımı gibi projelerde iş birliği yapılan yerel yönetimler veya diğer hayır kurumları), kanunen yetkili kamu kurumları ve özel kişilere aktarılabilir. Ayrıca, kişisel verilerinizin yeterli ve gerekli şekilde korunması ve dijital altyapıların işletilmesi için dijital araçlar, bilgi teknolojileri, sunucu ve sunucu hizmetleri güvenliği ile web sitesi alanında hizmet alınan şirketlere de aktarım yapılabilmektedir. Bu aktarımlar, belirtilen kişisel veri işleme şartları ve amaçları çerçevesinde KVKK'nın 8. ve 9. maddesi kapsamında gerçekleştirilmektedir. Bu veri aktarımı süreçleri, derneğin "Şeffaflık" ve "Sorumluluk" ilkeleriyle uyumlu olarak, veri güvenliğini ve gizliliğini en üst düzeyde tutma taahhüdünü yansıtmaktadır.</p>
                    
                    <h5 class="mt-4 mb-2">1.4. Kişisel Veri Toplamanın Yöntemi ile Hukuki Sebebi</h5>
                    <p>Kişisel verileriniz, Necat Derneği'nin "Elini iyiliğe uzatın" çağrısı ve "Onların bize değil, bizim onlara ihtiyacımız var" anlayışı temelinde, gönüllülük formu veya diğer bir yolla her türlü sözlü, yazılı ya da elektronik ortamda toplanmaktadır. Bu toplama yöntemleri, derneğin faaliyetlerini geniş kitlelere ulaştırma ve ihtiyaç sahiplerine etkin bir şekilde yardım etme kapasitesini artırmaktadır.</p>
                    
                    <p>Toplanan kişisel verileriniz, KVKK'nın 5. ve 6. maddelerinde belirtilen kişisel veri işleme şartları ve amaçları kapsamında işlenmekte ve aktarılmaktadır. Bu hukuki sebepler başlıca şunlardır: kanunlarda açıkça öngörülmesi (örneğin, dernekler mevzuatı gereği tutulması gereken kayıtlar), bir sözleşmenin kurulması veya ifasıyla doğrudan doğruya ilgili olması kaydıyla, sözleşmenin taraflarına ait kişisel verilerin işlenmesinin gerekli olması (gönüllülük taahhütnamesi gibi), Necat Derneği'nin hukuki yükümlülüğünü yerine getirebilmesi için zorunlu olması durumları (örneğin, adli sicil kaydı kontrolü) ve ilgili kişinin açık rızasının bulunması. Bu hukuki dayanaklar, derneğin "Adalet" ve "Hukuka Saygı" prensiplerine bağlılığını göstermektedir.</p>
                    
                    <h5 class="mt-4 mb-2">1.5. Kişisel Veri Sahibinin Hakları</h5>
                    <p>KVKK'nın "İstisnalar" başlıklı 28. maddesinde öngörülen haller saklı kalmak kaydıyla, KVKK'nın 11. maddesi çerçevesinde; kişisel veri sahipleri Necat Derneği'ne başvurarak aşağıdaki haklara sahiptir:</p>
                    <ul>
                        <li>Kişisel verilerinin işlenip işlenmediğini öğrenme</li>
                        <li>Kişisel verileri işlenmişse buna ilişkin bilgi talep etme</li>
                        <li>Kişisel verilerinin işlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme</li>
                        <li>Yurt içinde veya yurt dışında kişisel verilerinin aktarıldığı üçüncü kişileri bilme</li>
                        <li>Kişisel verilerinin eksik veya yanlış işlenmiş olması hâlinde bunların düzeltilmesini isteme ve bu kapsamda yapılan işlemin kişisel verilerin aktarıldığı üçüncü kişilere bildirilmesini isteme</li>
                        <li>KVKK'ya ve ilgili diğer kanun hükümlerine uygun olarak işlenmiş olmasına rağmen, işlenmesini gerektiren sebeplerin ortadan kalkması hâlinde kişisel verilerinin silinmesini veya yok edilmesini isteme ve bu kapsamda yapılan işlemin kişisel verilerin aktarıldığı üçüncü kişilere bildirilmesini isteme</li>
                        <li>İşlenen verilerin münhasıran otomatik sistemler vasıtasıyla analiz edilmesi suretiyle kişinin kendisi aleyhine bir sonucun ortaya çıkmasına itiraz etme</li>
                        <li>Kişisel verilerin kanuna aykırı olarak işlenmesi sebebiyle zarara uğraması hâlinde zararın giderilmesini talep etme</li>
                    </ul>
                    
                    <p>Yukarıda belirtilen haklarınızı kullanmak için talebinizi, yazılı veya Kişisel Verileri Koruma Kurulu'nun belirlediği diğer yöntemlerle Necat Derneği'ne iletebilirsiniz. Yapılacak başvuru kapsamında kimliği tespit edici gerekli bilgiler ile Kanun'un 11. maddesinde belirtilen haklardan kullanmayı talep edilen hakka yönelik açıklamaları içeren talep, Dernek Merkezi Adresi'ne bizzat elden iletilebilir, noter kanalıyla veya "info@necatdernegi.org" e-posta adresi üzerinden gönderilebilir.</p>
                    
                    <p>Başvuruda yer alan talepler, talebin niteliğine göre en kısa sürede ve en geç otuz gün içinde Necat Derneği tarafından ücretsiz olarak sonuçlandırılacaktır. Ancak işlemin, Necat Derneği için ayrıca bir maliyet gerektirmesi halinde, Kişisel Verileri Koruma Kurulu tarafından belirlenen tarifedeki ücret alınacaktır.</p>
                    
                    <h4 class="mt-4 mb-3">2. Gönüllülük Esasları ve Taahhütnamesi</h4>
                    <p>Necat Derneği, gönüllülük faaliyetlerini "Yüreğimizin uzanabildiği her yerde vazifeliyiz" ve "İyilik, yüceltmektir, diriltmektir, arınmaktır, huzurdur. Paha biçilmez bir güzelliktir" anlayışıyla yürütmektedir. Derneğin temel amacı, gönüllülerinin Necat Derneği'nin kuruluş ilkelerine, vizyonuna ve misyonuna uygun şekilde hareket ederek, gönüllülük çalışmalarını gerçekleştirmesidir. Necat Derneği olarak, tüm canlılar arasında dayanışma, saygı ve yardımlaşma ilkesi benimsenmekte ve herkes bu düşünceyle karşılanmaktadır.</p>
                    
                    <h5 class="mt-4 mb-2">2.1. Necat Derneği'nin Kuruluş İlkeleri</h5>
                    <p>Derneğin faaliyetlerinin temelini oluşturan ilkeler: "Hakk'a Riyayet", "Emanete Sadakat", "Ahde Vefa", "İnsana Hürmet", "Adalet", "Vicdan", "Şeffaflık", "Sorumluluk" ve "Dürüstlük" olarak belirlenmiştir. Bu ilkeler, derneğin tüm operasyonlarında ve gönüllü ilişkilerinde rehber niteliğindedir. Dernek, iş yaşamında dürüstlük ve hukuka saygıya büyük önem vermekte, çalışanlarıyla ilişkilerinde eşitlik, güvenli ve sağlıklı bir çalışma ortamı sağlama, kişiliklerine saygı duyma gibi temel prensiplere uymaktadır.</p>
                    
                    <p>Derneğin vizyonu, "Kardeşlik Seferberliği" ruhuyla, mazlumlar için atan bir yürekle hareket eden iyilik gönüllülerinin bir araya gelmesiyle kurulmuştur. Bu vizyon, derneğe ulaşan yardım çağrılarına zamanında ve yeterli bir şekilde ulaşabilecek imkanları seferber ederek en hızlı ve etkin çözümler üretmeyi hedeflemektedir.</p>
                    
                    <h5 class="mt-4 mb-2">2.2. Gönüllülük Taahhütnamesi</h5>
                    <p>Necat Derneği gönüllüsü olarak aşağıdaki taahhütlere uyulması beklenmektedir:</p>
                    <ul>
                        <li>Derneğe uyumlu olarak vizyon ve misyonuna aykırı hareket etmemek</li>
                        <li>Tüm canlıların ihtiyaçlarını herhangi bir ayrım gözetmeksizin karşılayarak, onlara yardımcı olmak</li>
                        <li>Kişileri ötekileştiren, ayrımcılığa yol açabilecek herhangi bir tartışmaya girmemek</li>
                        <li>Necat gönüllüsü olunduğunu belirtilen hiçbir fiziksel veya sosyal platformda siyasi içerikli, din, dil, ırk konusunda ayrıştırıcı veya ötekileştirici paylaşımlar yapmamak</li>
                        <li>Necat Derneği üyeleri, çalışanları ve diğer gönüllülerle uyum içinde çalışıp, ekip çalışmalarına destek vermek</li>
                        <li>Dernek tanıtım toplantılarına ve gönüllü eğitimlerine katılım sağlamak</li>
                        <li>Gönüllü çalışmalarında süreklilik gösterip, zaman planlamasına uymak</li>
                        <li>Necat adını kullanarak kişisel çıkar sağlamamak, hediye ve adına bağış kabul etmemek</li>
                        <li>Gönüllü Bilgi Formu'nda verilen bilgilerin doğru, eksiksiz ve gerçeğe uygun olduğunu beyan etmek</li>
                        <li>Türkiye Cumhuriyeti devleti aleyhinde faaliyette bulunmamak ve yüz kızartıcı bir suçtan ceza almamak</li>
                        <li>Necat Derneği bünyesindeki faaliyetler ile ilgili bilgilerin ve çeşitli duyuruların tarafına ulaştırılmasını kabul etmek</li>
                        <li>Dernek çalışanlarına, gönüllülerine ve ihtiyaç sahiplerine ilişkin kişisel veri, bilgi ya da görsellerin gizliliğine azami düzeyde riayet etmek</li>
                        <li>Gönüllülüğün ifası için zorunlu kişisel verilerin Necat Derneği tarafından işlenmesine izin vermek ve rıza göstermek</li>
                        <li>Necat Derneği nezdindeki her türlü Kişisel Veriye ilişkin olarak, gönüllülük ilişkisi süresince ve sonrasında da Kişisel Verilerin Korunması Kanunu'na uygun davranmak</li>
                    </ul>
                    
                    <h4 class="mt-4 mb-3">3. Sonuç ve Değerlendirme</h4>
                    <p>Necat Derneği için hazırlanan bu KVKK Aydınlatma Metni ve Gönüllülük Esasları/Taahhütnamesi, derneğin yasal yükümlülüklerini yerine getirme ve gönüllülük faaliyetlerini şeffaf, etik ve hukuka uygun bir çerçevede yürütme kararlılığını ortaya koymaktadır. Metinler, derneğin köklü insani yardım misyonu ve "Hakk'a Riyayet", "Adalet", "Dürüstlük" gibi temel ilkeleriyle tam bir uyum içerisindedir.</p>
                    
                    <p>Kişisel verilerin işlenmesi ve korunmasına yönelik detaylı açıklamalar, veri sahiplerinin haklarını tam olarak anlamalarını sağlamakta ve derneğin veri güvenliği konusundaki hassasiyetini pekiştirmektedir. Gönüllülük esasları ise, derneğin "tüm canlılar arasında dayanışma, saygı ve yardımlaşma" prensibini vurgulayarak, gönüllülerden beklenen davranış standartlarını net bir şekilde ortaya koymaktadır.</p>
                    
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>İletişim:</strong> Yukarıda belirtilen haklarınızı kullanmak için talebinizi yazılı veya elektronik yöntemlerle Necat Derneği'ne iletebilirsiniz. Başvurular en geç otuz gün içinde ücretsiz olarak sonuçlandırılacaktır.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-primary" onclick="acceptKVKK()">
                    <i class="fas fa-check me-2"></i>Okudum ve Kabul Ediyorum
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showAppointmentModal() {
    new bootstrap.Modal(document.getElementById('appointmentModal')).show();
}

function submitAppointment() {
    const form = document.getElementById('appointmentForm');
    const formData = new FormData(form);
    
    // Form verilerini kontrol et
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }
    
    // AJAX ile randevu talebi gönder
    fetch('/ajax/appointment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Randevu talebiniz alınmıştır. Size dönüş yapılacaktır.');
            bootstrap.Modal.getInstance(document.getElementById('appointmentModal')).hide();
            form.reset();
        } else {
            alert('Randevu talebi gönderilirken bir hata oluştu.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu.');
    });
}

// Tarih input'unu bugünden sonrası için sınırla
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('appointment_date');
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);
    
    // Privacy checkbox click handler - önce KVKK modalını aç
    const privacyCheckbox = document.getElementById('privacy');
    privacyCheckbox.addEventListener('click', function(e) {
        // Checkbox'ın işaretlenmesini engelle
        e.preventDefault();
        // KVKK modalını aç
        showKVKKModal();
    });
});

function showKVKKModal() {
    new bootstrap.Modal(document.getElementById('kvkkModal')).show();
}

function acceptKVKK() {
    // KVKK onaylandığında checkbox'ı işaretle
    document.getElementById('privacy').checked = true;
    // Modalı kapat
    bootstrap.Modal.getInstance(document.getElementById('kvkkModal')).hide();
}
</script>

<style>
/* ========================================
   CONSISTENT CONTACT PAGE STYLES
   ======================================== */

/* Hero Section - Matching Projects/About */
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

/* Statistics Styling - Consistent with Projects */
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
    color: #4ea674 !important;
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

/* Contact Info Cards */
.contact-info-card {
    background: white;
    padding: 2rem 1.5rem;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(78, 166, 116, 0.1);
    height: 100%;
    position: relative;
    overflow: hidden;
}

.contact-info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.contact-info-card:hover::before {
    transform: scaleX(1);
}

.contact-info-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(78, 166, 116, 0.15);
    border-color: rgba(78, 166, 116, 0.3);
}

.contact-icon-wrapper {
    position: relative;
    display: inline-block;
}

.contact-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    transition: all 0.3s ease;
}

.contact-info-card:hover .contact-icon {
    transform: scale(1.1) rotate(5deg);
}

.contact-icon.bg-primary { background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%); }
.contact-icon.bg-success { background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important; }
.contact-icon.bg-info { background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important; }
.contact-icon.bg-warning { background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important; }

.contact-card-title {
    color: #4ea674;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.25rem;
}

.contact-card-text {
    color: var(--gray-600);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

/* Form Wrapper */
.contact-form-wrapper {
    position: relative;
}

.contact-form-card {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(78, 166, 116, 0.1);
    position: relative;
    overflow: hidden;
}

.contact-form-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%);
}

/* Form Styling */
.form-label {
    color: #4ea674;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    border: 2px solid #e1e5e9;
    border-radius: 10px;
    padding: 12px 16px;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #4ea674;
    box-shadow: 0 0 0 0.2rem rgba(78, 166, 116, 0.25);
    outline: none;
}

/* Map Section */
.map-section-wrapper {
    position: relative;
}

.map-container {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(78, 166, 116, 0.1);
}

.transport-info-card {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(78, 166, 116, 0.1);
}

.transport-option {
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(78, 166, 116, 0.1);
    color: var(--gray-700);
    line-height: 1.6;
}

.transport-option:last-child {
    border-bottom: none;
}

/* Social Media Grid */
.social-media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1.5rem;
    justify-items: center;
}

.social-link-card {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(78, 166, 116, 0.1);
    color: var(--gray-700);
    min-width: 120px;
}

.social-link-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(78, 166, 116, 0.15);
    color: #4ea674;
    text-decoration: none;
}

.social-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    transition: all 0.3s ease;
}

.social-link-card:hover .social-icon {
    transform: scale(1.1) rotate(10deg);
}

.social-name {
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Quick Contact Cards */
.quick-contact-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(78, 166, 116, 0.1);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.quick-contact-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.quick-contact-card:hover::before {
    transform: scaleX(1);
}

.quick-contact-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(78, 166, 116, 0.15);
}

.quick-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    margin: 0 auto 1.5rem;
    transition: all 0.3s ease;
}

.quick-contact-card:hover .quick-icon {
    transform: scale(1.1) rotate(5deg);
}

.quick-icon.emergency {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important;
}

.quick-icon.whatsapp {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important;
}

.quick-icon.appointment {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important;
}

.quick-title {
    color: #4ea674;
    font-weight: 600;
    margin-bottom: 1rem;
}

.quick-description {
    color: var(--gray-600);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

/* Section Badges - Consistent */
.badge.bg-primary.px-3.py-2.mb-3 {
    background-color: #4ea674 !important;
    color: #ffffff !important;
    border: none !important;
}

/* Button Styling - Consistent with Projects */
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
}

/* CTA Section - Consistent */
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

.btn-accent {
    background: #ffc107 !important;
    color: var(--gray-900) !important;
    border: 2px solid #ffc107 !important;
    font-weight: 700;
}

.btn-accent:hover {
    background: #e0a800 !important;
    color: var(--gray-900) !important;
    border-color: #e0a800 !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(242, 229, 41, 0.3);
}

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

/* KVKK Modal Styles */
.kvkk-content {
    font-size: 0.95rem;
    line-height: 1.6;
    color: var(--gray-700);
}

.kvkk-content h4 {
    color: #4ea674;
    font-weight: 600;
    border-bottom: 2px solid rgba(78, 166, 116, 0.2);
    padding-bottom: 0.5rem;
}

.kvkk-content h5 {
    color: #3d8760;
    font-weight: 600;
}

.kvkk-content ul {
    padding-left: 1.5rem;
}

.kvkk-content li {
    margin-bottom: 0.5rem;
}

.modal-header.bg-primary {
    background-color: #4ea674 !important;
}

/* Updated Icon Colors */
.contact-icon.bg-success { 
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important; 
}

.contact-icon.bg-info { 
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important; 
}

.contact-icon.bg-warning { 
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important; 
}

.quick-icon.emergency {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important;
}

.quick-icon.whatsapp {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important;
}

.quick-icon.appointment {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important;
}

.btn-success {
    background-color: #4ea674 !important;
    border-color: #4ea674 !important;
}

.btn-success:hover {
    background-color: #3d8760 !important;
    border-color: #3d8760 !important;
}

.btn-info {
    background-color: #4ea674 !important;
    border-color: #4ea674 !important;
}

.btn-info:hover {
    background-color: #3d8760 !important;
    border-color: #3d8760 !important;
}

/* Transport icons color update */
.transport-option i.text-success {
    color: #4ea674 !important;
}

.transport-option i.text-info {
    color: #4ea674 !important;
}

.transport-option i.text-warning {
    color: #4ea674 !important;
}

.transport-option i.text-secondary {
    color: #4ea674 !important;
}

/* Privacy checkbox custom styling - sayfa rengi referansı kullanarak */
.form-check-input[type="checkbox"] {
    accent-color: #4ea674;
}

.form-check-input[type="checkbox"]:checked {
    background-color: #4ea674 !important;
    border-color: #4ea674 !important;
}

.form-check-input[type="checkbox"]:focus {
    border-color: #4ea674 !important;
    box-shadow: 0 0 0 0.25rem rgba(78, 166, 116, 0.25) !important;
}

.form-check-input[type="checkbox"]:checked:focus {
    background-color: #4ea674 !important;
    border-color: #4ea674 !important;
    box-shadow: 0 0 0 0.25rem rgba(78, 166, 116, 0.25) !important;
}

.form-check-input[type="checkbox"]:indeterminate {
    background-color: #4ea674 !important;
    border-color: #4ea674 !important;
}

/* Privacy link styling */
.form-check-label span {
    color: #4ea674;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
    transition: color 0.3s ease;
}

.form-check-label span:hover {
    color: #3d8760;
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 992px) {
    .hero-section {
        padding: calc(70px + 3rem) 0 3rem 0;
    }
    
    .contact-form-card {
        padding: 2rem 1.5rem;
    }
    
    .quick-contact-card {
        padding: 1.5rem;
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: calc(60px + 2rem) 0 2rem 0;
    }
    
    .display-4 {
        font-size: 2rem;
    }
    
    .stat-number-consistent {
        font-size: 2rem;
    }
    
    .contact-form-card {
        padding: 1.5rem;
    }
    
    .social-media-grid {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
    }
    
    .btn {
        width: 100%;
        max-width: 280px;
    }
}

@media (max-width: 576px) {
    .hero-section {
        padding: calc(50px + 1rem) 0 1rem 0;
    }
    
    .display-4 {
        font-size: 1.75rem;
    }
    
    .stat-number-consistent {
        font-size: 1.5rem;
    }
    
    .contact-info-card,
    .quick-contact-card {
        padding: 1.5rem 1rem;
    }
    
    .contact-icon,
    .quick-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}
</style>
