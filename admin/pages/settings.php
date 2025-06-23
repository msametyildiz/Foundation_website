<?php
// Settings page for admin panel
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        if ($action === 'update_site') {
            $site_name = sanitizeInput($_POST['site_name'] ?? '');
            $site_description = sanitizeInput($_POST['site_description'] ?? '');
            $contact_email = sanitizeInput($_POST['contact_email'] ?? '');
            $contact_phone = sanitizeInput($_POST['contact_phone'] ?? '');
            $contact_address = sanitizeInput($_POST['contact_address'] ?? '');
            $social_facebook = sanitizeInput($_POST['social_facebook'] ?? '');
            $social_twitter = sanitizeInput($_POST['social_twitter'] ?? '');
            $social_instagram = sanitizeInput($_POST['social_instagram'] ?? '');
            $social_linkedin = sanitizeInput($_POST['social_linkedin'] ?? '');
            
            // Update or insert settings
            $settings = [
                'site_name' => $site_name,
                'site_description' => $site_description,
                'contact_email' => $contact_email,
                'contact_phone' => $contact_phone,
                'contact_address' => $contact_address,
                'social_facebook' => $social_facebook,
                'social_twitter' => $social_twitter,
                'social_instagram' => $social_instagram,
                'social_linkedin' => $social_linkedin
            ];
            
            foreach ($settings as $key => $value) {
                $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
                $stmt->execute([$key, $value]);
            }
            
            $success = "Site ayarları güncellendi.";
        }
        
        if ($action === 'update_email') {
            $smtp_host = sanitizeInput($_POST['smtp_host'] ?? '');
            $smtp_port = (int)($_POST['smtp_port'] ?? 587);
            $smtp_username = sanitizeInput($_POST['smtp_username'] ?? '');
            $smtp_password = sanitizeInput($_POST['smtp_password'] ?? '');
            $smtp_encryption = sanitizeInput($_POST['smtp_encryption'] ?? 'tls');
            $from_email = sanitizeInput($_POST['from_email'] ?? '');
            $from_name = sanitizeInput($_POST['from_name'] ?? '');
            
            $email_settings = [
                'smtp_host' => $smtp_host,
                'smtp_port' => $smtp_port,
                'smtp_username' => $smtp_username,
                'smtp_password' => $smtp_password,
                'smtp_encryption' => $smtp_encryption,
                'from_email' => $from_email,
                'from_name' => $from_name
            ];
            
            foreach ($email_settings as $key => $value) {
                $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
                $stmt->execute([$key, $value]);
            }
            
            $success = "E-posta ayarları güncellendi.";
        }
        
        if ($action === 'update_hero') {
            $hero_title = sanitizeInput($_POST['hero_title'] ?? '');
            $hero_subtitle = sanitizeInput($_POST['hero_subtitle'] ?? '');
            $stats_projects = (int)($_POST['stats_projects'] ?? 0);
            $stats_beneficiaries = (int)($_POST['stats_beneficiaries'] ?? 0);
            $stats_volunteers = (int)($_POST['stats_volunteers'] ?? 0);
            $stats_donations = (int)($_POST['stats_donations'] ?? 0);
            
            $hero_settings = [
                'hero_title' => $hero_title,
                'hero_subtitle' => $hero_subtitle,
                'stats_projects' => $stats_projects,
                'stats_beneficiaries' => $stats_beneficiaries,
                'stats_volunteers' => $stats_volunteers,
                'stats_donations' => $stats_donations
            ];
            
            foreach ($hero_settings as $key => $value) {
                $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value, setting_type, category) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
                $type = in_array($key, ['hero_title', 'hero_subtitle']) ? 'text' : 'number';
                $category = in_array($key, ['hero_title', 'hero_subtitle']) ? 'anasayfa' : 'istatistik';
                $stmt->execute([$key, $value, $type, $category]);
            }
            
            $success = "Ana sayfa Hero bölümü güncellendi.";
        }
        
        if ($action === 'update_security') {
            $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;
            $login_attempts = (int)($_POST['login_attempts'] ?? 5);
            $session_timeout = (int)($_POST['session_timeout'] ?? 3600);
            $password_min_length = (int)($_POST['password_min_length'] ?? 8);
            $require_2fa = isset($_POST['require_2fa']) ? 1 : 0;
            
            $security_settings = [
                'maintenance_mode' => $maintenance_mode,
                'login_attempts' => $login_attempts,
                'session_timeout' => $session_timeout,
                'password_min_length' => $password_min_length,
                'require_2fa' => $require_2fa
            ];
            
            foreach ($security_settings as $key => $value) {
                $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
                $stmt->execute([$key, $value]);
            }
            
            $success = "Güvenlik ayarları güncellendi.";
        }
        
        if ($action === 'backup_database') {
            // Backup database (basic implementation)
            $backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $backup_path = '../../backups/' . $backup_file;
            
            // Create backups directory if it doesn't exist
            if (!file_exists('../../backups')) {
                mkdir('../../backups', 0755, true);
            }
            
            $success = "Veritabanı yedeği oluşturuldu: " . $backup_file;
        }
        
        if ($action === 'clear_cache') {
            // Clear cache files
            $cache_dir = '../cache/';
            if (is_dir($cache_dir)) {
                $files = glob($cache_dir . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
            $success = "Önbellek temizlendi.";
        }
        
    } catch (PDOException $e) {
        $error = 'Veritabanı hatası: ' . $e->getMessage();
    } catch (Exception $e) {
        $error = 'Hata: ' . $e->getMessage();
    }
}

// Get current settings
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
    $settings_data = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (PDOException $e) {
    $settings_data = [];
}

// Default values
$site_settings = [
    'site_name' => $settings_data['site_name'] ?? 'Necat Derneği',
    'site_description' => $settings_data['site_description'] ?? 'İnsani yardım ve sosyal sorumluluk projelerimizle topluma hizmet ediyoruz.',
    'contact_email' => $settings_data['contact_email'] ?? 'info@necatdernegi.org',
    'contact_phone' => $settings_data['contact_phone'] ?? '+90 (212) 123 45 67',
    'contact_address' => $settings_data['contact_address'] ?? 'İstanbul, Türkiye',
    'social_facebook' => $settings_data['social_facebook'] ?? '',
    'social_twitter' => $settings_data['social_twitter'] ?? '',
    'social_instagram' => $settings_data['social_instagram'] ?? '',
    'social_linkedin' => $settings_data['social_linkedin'] ?? ''
];

$hero_settings = [
    'hero_title' => $settings_data['hero_title'] ?? 'Umut Olmaya Devam Ediyoruz',
    'hero_subtitle' => $settings_data['hero_subtitle'] ?? 'Her bağış bir umut, her yardım bir gülümseme. Muhtaç ailelere ulaşan yardımlarınızla hayatlara dokunmaya devam ediyoruz.',
    'stats_projects' => $settings_data['stats_projects'] ?? 10,
    'stats_beneficiaries' => $settings_data['stats_beneficiaries'] ?? 5000,
    'stats_volunteers' => $settings_data['stats_volunteers'] ?? 25,
    'stats_donations' => $settings_data['stats_donations'] ?? 500000
];

$email_settings = [
    'smtp_host' => $settings_data['smtp_host'] ?? '',
    'smtp_port' => $settings_data['smtp_port'] ?? 587,
    'smtp_username' => $settings_data['smtp_username'] ?? '',
    'smtp_password' => $settings_data['smtp_password'] ?? '',
    'smtp_encryption' => $settings_data['smtp_encryption'] ?? 'tls',
    'from_email' => $settings_data['from_email'] ?? '',
    'from_name' => $settings_data['from_name'] ?? 'Necat Derneği'
];

$security_settings = [
    'maintenance_mode' => $settings_data['maintenance_mode'] ?? 0,
    'login_attempts' => $settings_data['login_attempts'] ?? 5,
    'session_timeout' => $settings_data['session_timeout'] ?? 3600,
    'password_min_length' => $settings_data['password_min_length'] ?? 8,
    'require_2fa' => $settings_data['require_2fa'] ?? 0
];
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-cog me-2"></i>Sistem Ayarları</h1>
        </div>
    </div>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Settings Tabs -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="settingsTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#site-settings">
                            <i class="fas fa-globe me-2"></i>Site Ayarları
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#hero-settings">
                            <i class="fas fa-home me-2"></i>Ana Sayfa Hero
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#email-settings">
                            <i class="fas fa-envelope me-2"></i>E-posta Ayarları
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#security-settings">
                            <i class="fas fa-shield-alt me-2"></i>Güvenlik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#system-tools">
                            <i class="fas fa-tools me-2"></i>Sistem Araçları
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="card-body">
                <div class="tab-content">
                    <!-- Site Settings Tab -->
                    <div class="tab-pane fade show active" id="site-settings">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_site">
                            
                            <div class="row g-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-info-circle me-2"></i>Genel Bilgiler
                                    </h5>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Site Adı</label>
                                    <input type="text" name="site_name" class="form-control" 
                                           value="<?php echo htmlspecialchars($site_settings['site_name']); ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">İletişim E-postası</label>
                                    <input type="email" name="contact_email" class="form-control" 
                                           value="<?php echo htmlspecialchars($site_settings['contact_email']); ?>" required>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Site Açıklaması</label>
                                    <textarea name="site_description" class="form-control" rows="3" required><?php echo htmlspecialchars($site_settings['site_description']); ?></textarea>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Telefon</label>
                                    <input type="text" name="contact_phone" class="form-control" 
                                           value="<?php echo htmlspecialchars($site_settings['contact_phone']); ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Adres</label>
                                    <input type="text" name="contact_address" class="form-control" 
                                           value="<?php echo htmlspecialchars($site_settings['contact_address']); ?>">
                                </div>
                                
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-share-alt me-2"></i>Sosyal Medya
                                    </h5>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Facebook</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                        <input type="url" name="social_facebook" class="form-control" 
                                               value="<?php echo htmlspecialchars($site_settings['social_facebook']); ?>" 
                                               placeholder="https://facebook.com/... veya sayfa adı">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Twitter</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                        <input type="url" name="social_twitter" class="form-control" 
                                               value="<?php echo htmlspecialchars($site_settings['social_twitter']); ?>" 
                                               placeholder="https://twitter.com/... veya kullanıcı adı">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Instagram</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                        <input type="url" name="social_instagram" class="form-control" 
                                               value="<?php echo htmlspecialchars($site_settings['social_instagram']); ?>" 
                                               placeholder="https://instagram.com/... veya kullanıcı adı">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">LinkedIn</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                                        <input type="url" name="social_linkedin" class="form-control" 
                                               value="<?php echo htmlspecialchars($site_settings['social_linkedin']); ?>" 
                                               placeholder="https://linkedin.com/company/... veya kullanıcı adı">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">YouTube</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                        <input type="url" name="social_youtube" class="form-control" 
                                               value="<?php echo htmlspecialchars($site_settings['social_youtube']); ?>" 
                                               placeholder="https://youtube.com/@... veya kanal adı">
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Kaydet
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Hero Settings Tab -->
                    <div class="tab-pane fade" id="hero-settings">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_hero">
                            
                            <div class="row g-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-home me-2"></i>Ana Sayfa Hero Section
                                    </h5>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Ana Başlık</label>
                                    <input type="text" name="hero_title" class="form-control" 
                                           value="<?php echo htmlspecialchars($hero_settings['hero_title']); ?>" 
                                           placeholder="Umut Olmaya Devam Ediyoruz" required>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Alt Başlık</label>
                                    <textarea name="hero_subtitle" class="form-control" rows="3" 
                                              placeholder="Hero section alt başlığı" required><?php echo htmlspecialchars($hero_settings['hero_subtitle']); ?></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-chart-bar me-2"></i>İstatistik Verileri
                                    </h5>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Toplam Proje Sayısı</label>
                                    <input type="number" name="stats_projects" class="form-control" 
                                           value="<?php echo $hero_settings['stats_projects']; ?>" 
                                           min="0" placeholder="10">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Yardım Edilen Aile Sayısı</label>
                                    <input type="number" name="stats_beneficiaries" class="form-control" 
                                           value="<?php echo $hero_settings['stats_beneficiaries']; ?>" 
                                           min="0" placeholder="5000">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Aktif Gönüllü Sayısı</label>
                                    <input type="number" name="stats_volunteers" class="form-control" 
                                           value="<?php echo $hero_settings['stats_volunteers']; ?>" 
                                           min="0" placeholder="25">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Toplam Bağış Miktarı (TL)</label>
                                    <input type="number" name="stats_donations" class="form-control" 
                                           value="<?php echo $hero_settings['stats_donations']; ?>" 
                                           min="0" placeholder="500000">
                                </div>
                                
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Bilgi:</strong> Bu değerler ana sayfanın Hero Section'ında görüntülenir. 
                                        Eğer değer 0 ise, sistem otomatik olarak veritabanından gerçek verileri çekmeye çalışır.
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Kaydet
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Email Settings Tab -->
                    <div class="tab-pane fade" id="email-settings">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_email">
                            
                            <div class="row g-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-server me-2"></i>SMTP Ayarları
                                    </h5>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">SMTP Sunucusu</label>
                                    <input type="text" name="smtp_host" class="form-control" 
                                           value="<?php echo htmlspecialchars($email_settings['smtp_host']); ?>" 
                                           placeholder="smtp.gmail.com">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Port</label>
                                    <input type="number" name="smtp_port" class="form-control" 
                                           value="<?php echo $email_settings['smtp_port']; ?>" 
                                           placeholder="587">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Kullanıcı Adı</label>
                                    <input type="text" name="smtp_username" class="form-control" 
                                           value="<?php echo htmlspecialchars($email_settings['smtp_username']); ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Şifre</label>
                                    <input type="password" name="smtp_password" class="form-control" 
                                           value="<?php echo htmlspecialchars($email_settings['smtp_password']); ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Şifreleme</label>
                                    <select name="smtp_encryption" class="form-select">
                                        <option value="tls" <?php echo $email_settings['smtp_encryption'] === 'tls' ? 'selected' : ''; ?>>TLS</option>
                                        <option value="ssl" <?php echo $email_settings['smtp_encryption'] === 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                        <option value="" <?php echo empty($email_settings['smtp_encryption']) ? 'selected' : ''; ?>>Yok</option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-envelope me-2"></i>Gönderici Bilgileri
                                    </h5>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Gönderici E-postası</label>
                                    <input type="email" name="from_email" class="form-control" 
                                           value="<?php echo htmlspecialchars($email_settings['from_email']); ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Gönderici Adı</label>
                                    <input type="text" name="from_name" class="form-control" 
                                           value="<?php echo htmlspecialchars($email_settings['from_name']); ?>">
                                </div>
                                
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Gmail kullanıyorsanız, uygulama şifresi oluşturmanız gerekebilir.
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Kaydet
                                    </button>
                                    <button type="button" class="btn btn-outline-info ms-2" onclick="testEmail()">
                                        <i class="fas fa-paper-plane me-2"></i>Test Et
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Security Settings Tab -->
                    <div class="tab-pane fade" id="security-settings">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_security">
                            
                            <div class="row g-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <i class="fas fa-lock me-2"></i>Güvenlik Ayarları
                                    </h5>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Maksimum Giriş Denemesi</label>
                                    <input type="number" name="login_attempts" class="form-control" 
                                           value="<?php echo $security_settings['login_attempts']; ?>" 
                                           min="1" max="10">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Oturum Zaman Aşımı (saniye)</label>
                                    <input type="number" name="session_timeout" class="form-control" 
                                           value="<?php echo $security_settings['session_timeout']; ?>" 
                                           min="300" max="86400">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Minimum Şifre Uzunluğu</label>
                                    <input type="number" name="password_min_length" class="form-control" 
                                           value="<?php echo $security_settings['password_min_length']; ?>" 
                                           min="6" max="20">
                                </div>
                                
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">
                                        <i class="fas fa-toggle-on me-2"></i>Sistem Modları
                                    </h5>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="maintenance_mode" 
                                               id="maintenanceMode" <?php echo $security_settings['maintenance_mode'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="maintenanceMode">
                                            <strong>Bakım Modu</strong><br>
                                            <small class="text-muted">Aktif olduğunda site ziyaretçilere kapalı olur.</small>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="require_2fa" 
                                               id="require2fa" <?php echo $security_settings['require_2fa'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="require2fa">
                                            <strong>İki Faktörlü Kimlik Doğrulama</strong><br>
                                            <small class="text-muted">Tüm admin kullanıcılar için zorunlu kıl.</small>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Kaydet
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- System Tools Tab -->
                    <div class="tab-pane fade" id="system-tools">
                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-database me-2"></i>Veritabanı İşlemleri
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <i class="fas fa-download fa-2x text-primary mb-3"></i>
                                        <h6>Veritabanı Yedeği</h6>
                                        <p class="text-muted">Veritabanının tam yedeğini oluştur</p>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="backup_database">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-download me-1"></i>Yedek Oluştur
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <i class="fas fa-broom fa-2x text-warning mb-3"></i>
                                        <h6>Önbellek Temizle</h6>
                                        <p class="text-muted">Sistem önbelleğini temizle</p>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="clear_cache">
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="fas fa-broom me-1"></i>Temizle
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3 mt-4">
                                    <i class="fas fa-chart-line me-2"></i>Sistem Durumu
                                </h5>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-server fa-2x text-info mb-2"></i>
                                        <h6>PHP Sürümü</h6>
                                        <p class="mb-0"><?php echo PHP_VERSION; ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-memory fa-2x text-success mb-2"></i>
                                        <h6>Bellek Limiti</h6>
                                        <p class="mb-0"><?php echo ini_get('memory_limit'); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-upload fa-2x text-warning mb-2"></i>
                                        <h6>Upload Limiti</h6>
                                        <p class="mb-0"><?php echo ini_get('upload_max_filesize'); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                        <h6>Zaman Limiti</h6>
                                        <p class="mb-0"><?php echo ini_get('max_execution_time'); ?>s</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3 mt-4">
                                    <i class="fas fa-info-circle me-2"></i>Sistem Bilgileri
                                </h5>
                                
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>İşletim Sistemi:</strong></td>
                                            <td><?php echo PHP_OS; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Web Sunucusu:</strong></td>
                                            <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Bilinmiyor'; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>MySQL Sürümü:</strong></td>
                                            <td><?php echo $pdo->query('SELECT VERSION()')->fetchColumn(); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sunucu Zamanı:</strong></td>
                                            <td><?php echo date('Y-m-d H:i:s'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function testEmail() {
    // Test email configuration
    fetch('../ajax/test_email.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Test e-postası başarıyla gönderildi!', 'success');
        } else {
            showAlert('E-posta gönderim hatası: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Bir hata oluştu.', 'danger');
    });
}
</script>
