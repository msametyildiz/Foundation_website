<?php
// Define INCLUDED constant for footer_fix.php
define('INCLUDED', true);

// Use footer_environment variable if available
$environment = isset($footer_environment) ? $footer_environment : (isset($environment) ? $environment : 'development');

// Always include footer fix script (both in development and production)
$footer_fix = dirname(__DIR__) . '/scripts/footer_fix.php';
if (file_exists($footer_fix)) {
    include_once $footer_fix;
}

// Functions.php'yi include et (URL helper fonksiyonları için)
if (!function_exists('site_url')) {
    require_once __DIR__ . '/functions.php';
}

// LogoBase64Helper'ı include et
if (!class_exists('LogoBase64Helper')) {
    require_once __DIR__ . '/logo-base64-helper.php';
}

// İletişim bilgileri için veritabanı bağlantısı
try {
    // Veritabanından iletişim bilgilerini almaya çalış
    $contact_info = array(
        'address' => 'Fevzipaşa Mahallesi Rüzgarlı Caddesi Plevne Sokak No:14/1 Ulus Altındağ Ankara',
        'phone' => '+90 312 311 65 25',
        'email' => 'info@necatdernegi.org',
    );
    
    // Eğer veritabanı bağlantısı varsa ve ilgili fonksiyon tanımlıysa
    if (isset($pdo) && function_exists('get_contact_info')) {
        $db_contact_info = get_contact_info();
        if ($db_contact_info && is_array($db_contact_info)) {
            $contact_info = array_merge($contact_info, $db_contact_info);
        }
    }
} catch (Exception $e) {
    // Hata durumunda varsayılan değerleri kullan
    error_log('Footer: Veritabanından iletişim bilgileri alınamadı: ' . $e->getMessage());
}

// Footer için HTML çıktısı başlatılıyor
?>
    <footer class="footer-modern">
        <div class="footer-container">
            <div class="footer-main">
            <!-- 1. Marka Bölümü -->
                <div class="footer-brand">
                    <div class="footer-logo">
                    <div class="footer-logo-icon">
                        <?php if (class_exists('LogoBase64Helper') && method_exists('LogoBase64Helper', 'getBase64Logo')): ?>
                            <img src="<?php echo LogoBase64Helper::getBase64Logo(); ?>" alt="Necat Derneği Logo" class="footer-logo-img">
                        <?php else: ?>
                            <img src="<?php echo site_url('assets/images/logo.png'); ?>" alt="Necat Derneği Logo" class="footer-logo-img">
                        <?php endif; ?>
                    </div>
                        <div class="footer-brand-text">
                            <h3>Necat Derneği</h3>
                            <div class="footer-brand-tagline">Elinizi İyilik İçin Uzatın</div>
                        </div>
                    </div>
                    <p class="footer-description">
                    Derneğimiz, yoksulluk, afet, hastalık gibi zorluklarla karşılaşan kişilere ve ailelere destek olmak için çalışmaktadır.
                    </p>
                    <div class="footer-social">
                    <a href="https://instagram.com" class="social-link" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://twitter.com" class="social-link" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://linkedin.com" class="social-link" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://youtube.com" class="social-link" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    </div>
                </div>

            <!-- 2. Hızlı Bağlantılar -->
                <div class="footer-section">
                    <h4>Hızlı Bağlantılar</h4>
                    <ul class="footer-links">
                    <li><a href="<?php echo site_url(); ?>"><i class="fas fa-home"></i> Ana Sayfa</a></li>
                    <li><a href="<?php echo site_url('about'); ?>"><i class="fas fa-info-circle"></i> Hakkımızda</a></li>
                    <li><a href="<?php echo site_url('projects'); ?>"><i class="fas fa-project-diagram"></i> Projelerimiz</a></li>
                    <li><a href="<?php echo site_url('volunteer'); ?>"><i class="fas fa-hands-helping"></i> Gönüllü Ol</a></li>
                    </ul>
                </div>

            <!-- 3. Daha Fazla -->
                <div class="footer-section">
                    <h4>Daha Fazla</h4>
                    <ul class="footer-links">
                    <li><a href="<?php echo site_url('faq'); ?>"><i class="fas fa-question-circle"></i> SSS</a></li>
                    <li><a href="<?php echo site_url('contact'); ?>"><i class="fas fa-envelope"></i> İletişim</a></li>
                    <li><a href="<?php echo site_url('donate'); ?>"><i class="fas fa-heart"></i> Bağış Yap</a></li>
                    </ul>
                </div>

            <!-- 4. İletişim -->
            <div class="footer-section">
                    <h4>Bize Ulaşın</h4>
                <ul class="footer-contact-info">
                    <li>
                            <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo $contact_info['address']; ?></span>
                    </li>
                    <li>
                            <i class="fas fa-phone"></i>
                        <span><?php echo $contact_info['phone']; ?></span>
                    </li>
                    <li>
                            <i class="fas fa-envelope"></i>
                        <span><?php echo $contact_info['email']; ?></span>
                    </li>
                </ul>
                </div>
            </div>

        <!-- Telif Hakkı -->
                <div class="footer-copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p>&copy; <?php echo date('Y'); ?> Necat Derneği - Tüm Hakları Saklıdır</p>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </footer>

<!-- Acil durumda kullanılacak bağımsız footer düzeltme JavaScript'i -->
<script src="/scripts/footer_hardfix.js"></script>

<!-- jQuery Mask Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<!-- Telefon Maskesi -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Telefon numarası alanlarına maske uygula
        if (typeof $ !== 'undefined') {
            $('input[type="tel"]').mask('(000) 000-00-00');
        }
    });
</script>

<!-- BODY ve HTML kapanış tag'leri otomatik olarak eklenecektir -->
</body>
</html>
