<?php
require_once '../config/database.php';

// İletişim formu işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'contact') {
    if (validateCSRFToken($_POST['csrf_token'])) {
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $phone = sanitizeInput($_POST['phone']);
        $subject = sanitizeInput($_POST['subject']);
        $message = sanitizeInput($_POST['message']);
        
        if (validateEmail($email)) {
            try {
                $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, created_at, status) VALUES (?, ?, ?, ?, ?, NOW(), 'unread')");
                $stmt->execute([$name, $email, $phone, $subject, $message]);
                
                $success_message = "Mesajınız başarıyla gönderilmiştir. En kısa sürede size dönüş yapacağız.";
            } catch (PDOException $e) {
                $error_message = "Mesaj gönderilirken bir hata oluştu. Lütfen tekrar deneyin.";
            }
        } else {
            $error_message = "Lütfen geçerli bir email adresi giriniz.";
        }
    } else {
        $error_message = "Güvenlik doğrulaması başarısız. Lütfen tekrar deneyin.";
    }
}

// İletişim bilgileri
$contact_info = [
    'address' => 'Merkez Mahallesi, Yardım Sokak No: 15/A, 34000 İstanbul',
    'phone' => '+90 (212) 555-0123',
    'emergency' => '+90 (555) 123-4567',
    'fax' => '+90 (212) 555-0124',
    'email' => 'info@necatdernegi.org',
    'working_hours' => [
        'weekdays' => 'Pazartesi - Cuma: 09:00 - 17:00',
        'saturday' => 'Cumartesi: 09:00 - 14:00',
        'sunday' => 'Pazar: Kapalı'
    ]
];

// Sosyal medya hesapları
$social_media = [
    ['platform' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'url' => 'https://facebook.com/necatdernegi', 'color' => '#1877f2'],
    ['platform' => 'Instagram', 'icon' => 'fab fa-instagram', 'url' => 'https://instagram.com/necatdernegi', 'color' => '#e4405f'],
    ['platform' => 'Twitter', 'icon' => 'fab fa-twitter', 'url' => 'https://twitter.com/necatdernegi', 'color' => '#1da1f2'],
    ['platform' => 'YouTube', 'icon' => 'fab fa-youtube', 'url' => 'https://youtube.com/necatdernegi', 'color' => '#ff0000'],
    ['platform' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'url' => 'https://linkedin.com/company/necatdernegi', 'color' => '#0077b5']
];
?>

<div class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-3">İletişim</h1>
                <p class="lead mb-0">Bize ulaşın, size yardımcı olmaktan mutluluk duyarız.</p>
            </div>
            <div class="col-lg-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent justify-content-lg-end">
                        <li class="breadcrumb-item"><a href="/" class="text-white">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">İletişim</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- İletişim Kartları -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="contact-card text-center p-4 bg-white rounded shadow-sm h-100">
                    <div class="contact-icon mb-3">
                        <i class="fas fa-map-marker-alt fa-3x text-primary"></i>
                    </div>
                    <h5 class="contact-title">Adresimiz</h5>
                    <p class="contact-info"><?= $contact_info['address'] ?></p>
                    <a href="https://maps.google.com/?q=<?= urlencode($contact_info['address']) ?>" 
                       target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-directions me-1"></i> Yol Tarifi
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="contact-card text-center p-4 bg-white rounded shadow-sm h-100">
                    <div class="contact-icon mb-3">
                        <i class="fas fa-phone fa-3x text-success"></i>
                    </div>
                    <h5 class="contact-title">Telefon</h5>
                    <p class="contact-info">
                        <strong>Genel:</strong> <?= $contact_info['phone'] ?><br>
                        <strong>Acil:</strong> <?= $contact_info['emergency'] ?>
                    </p>
                    <a href="tel:<?= str_replace([' ', '(', ')', '-'], '', $contact_info['phone']) ?>" 
                       class="btn btn-outline-success btn-sm">
                        <i class="fas fa-phone me-1"></i> Ara
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="contact-card text-center p-4 bg-white rounded shadow-sm h-100">
                    <div class="contact-icon mb-3">
                        <i class="fas fa-envelope fa-3x text-info"></i>
                    </div>
                    <h5 class="contact-title">E-posta</h5>
                    <p class="contact-info"><?= $contact_info['email'] ?></p>
                    <a href="mailto:<?= $contact_info['email'] ?>" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-envelope me-1"></i> Mail Gönder
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="contact-card text-center p-4 bg-white rounded shadow-sm h-100">
                    <div class="contact-icon mb-3">
                        <i class="fas fa-clock fa-3x text-warning"></i>
                    </div>
                    <h5 class="contact-title">Çalışma Saatleri</h5>
                    <p class="contact-info">
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
                <div class="contact-form">
                    <h2 class="section-title mb-4">Bize Mesaj Gönderin</h2>
                    
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
                                    <a href="/documents" target="_blank">Kişisel Verilerin Korunması</a> hakkındaki bilgilendirilmeyi okudum ve kabul ediyorum. *
                                </label>
                                <div class="invalid-feedback">Lütfen gizlilik politikasını kabul ediniz.</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>
                                Mesaj Gönder
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Harita ve Ek Bilgiler -->
            <div class="col-lg-6">
                <div class="map-section">
                    <h2 class="section-title mb-4">Konumumuz</h2>
                    
                    <!-- Google Harita -->
                    <div class="map-container mb-4">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193595.9147730845!2d28.68271917253096!3d41.00543077985568!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14caa7040068086b%3A0xe1ccfe98bc01b0d0!2zxLBzdGFuYnVs!5e0!3m2!1str!2str!4v1703856000000!5m2!1str!2str" 
                                width="100%" height="300" style="border:0;" allowfullscreen="" 
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                    <!-- Ulaşım Bilgileri -->
                    <div class="transport-info bg-light p-4 rounded">
                        <h5 class="mb-3">
                            <i class="fas fa-route me-2 text-primary"></i>
                            Ulaşım Bilgileri
                        </h5>
                        <div class="transport-options">
                            <div class="transport-option mb-2">
                                <i class="fas fa-subway text-success me-2"></i>
                                <strong>Metro:</strong> Vezneciler istasyonundan 5 dakika yürüyüş
                            </div>
                            <div class="transport-option mb-2">
                                <i class="fas fa-bus text-info me-2"></i>
                                <strong>Otobüs:</strong> 28, 61C, 399A hatları (Beyazıt durağı)
                            </div>
                            <div class="transport-option mb-2">
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
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title mb-4">Sosyal Medyada Takip Edin</h2>
                <p class="section-subtitle mb-5">Haberlerımızı kaçırmamak için sosyal medya hesaplarımızı takip edin</p>
                
                <div class="social-media-links">
                    <?php foreach ($social_media as $social): ?>
                        <a href="<?= $social['url'] ?>" target="_blank" 
                           class="social-link btn btn-outline-primary btn-lg mx-2 mb-3"
                           style="border-color: <?= $social['color'] ?>; color: <?= $social['color'] ?>;"
                           onmouseover="this.style.backgroundColor='<?= $social['color'] ?>'; this.style.color='white';"
                           onmouseout="this.style.backgroundColor='transparent'; this.style.color='<?= $social['color'] ?>';">
                            <i class="<?= $social['icon'] ?> me-2"></i>
                            <?= $social['platform'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hızlı İletişim -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="quick-contact-card text-center p-4 bg-gradient-primary text-white rounded">
                    <div class="quick-icon mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x"></i>
                    </div>
                    <h5 class="quick-title">Acil Durum</h5>
                    <p class="quick-description">7/24 acil yardım hattımız</p>
                    <a href="tel:<?= str_replace([' ', '(', ')', '-'], '', $contact_info['emergency']) ?>" 
                       class="btn btn-light btn-lg">
                        <i class="fas fa-phone me-2"></i>
                        <?= $contact_info['emergency'] ?>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="quick-contact-card text-center p-4 bg-gradient-success text-white rounded">
                    <div class="quick-icon mb-3">
                        <i class="fab fa-whatsapp fa-3x"></i>
                    </div>
                    <h5 class="quick-title">WhatsApp</h5>
                    <p class="quick-description">Hızlı mesajlaşma için</p>
                    <a href="https://wa.me/905551234567" target="_blank" class="btn btn-light btn-lg">
                        <i class="fab fa-whatsapp me-2"></i>
                        Mesaj Gönder
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mx-auto">
                <div class="quick-contact-card text-center p-4 bg-gradient-info text-white rounded">
                    <div class="quick-icon mb-3">
                        <i class="fas fa-calendar-check fa-3x"></i>
                    </div>
                    <h5 class="quick-title">Randevu</h5>
                    <p class="quick-description">Ziyaret için randevu alın</p>
                    <button class="btn btn-light btn-lg" onclick="showAppointmentModal()">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Randevu Al
                    </button>
                </div>
            </div>
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
});
</script>
