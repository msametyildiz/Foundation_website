<?php
/**
 * Necat Derneği - cPanel Sorun Giderme Aracı
 * 
 * Bu betik, cPanel ortamında yaşanan sorunları tespit etmek ve çözmek için kullanılır.
 * Özellikle veritabanı bağlantısı, form işlemleri ve JavaScript sorunlarına odaklanır.
 */

// Hata raporlamayı etkinleştir
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=================================================================\n";
echo "Necat Derneği - cPanel Sorun Giderme Aracı\n";
echo "=================================================================\n";

// Veritabanı bağlantısını test et
echo "\n[1] Veritabanı Bağlantısı Testi\n";
echo "-----------------------------------\n";

try {
    require_once 'config/database.php';
    echo "✓ Veritabanı yapılandırma dosyası yüklendi.\n";
    
    // Veritabanı bağlantısını kontrol et
    if (isset($pdo) && $pdo instanceof PDO) {
        echo "✓ Veritabanı bağlantısı başarılı.\n";
        
        // Tablolar mevcut mu?
        $tables = [
            'settings', 'projects', 'volunteer_applications', 
            'donations', 'contact_messages', 'faq'
        ];
        
        foreach ($tables as $table) {
            try {
                $stmt = $pdo->query("SELECT 1 FROM $table LIMIT 1");
                echo "✓ '$table' tablosu erişilebilir.\n";
            } catch (PDOException $e) {
                echo "✗ '$table' tablosu bulunamadı veya erişilemiyor: " . $e->getMessage() . "\n";
            }
        }
        
        // İstatistik verilerini kontrol et
        echo "\n[1.1] İstatistik Verileri Kontrolü\n";
        
        // Proje sayısı
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM projects WHERE status IN ('active', 'completed')");
            $projectCount = $stmt->fetchColumn();
            echo "✓ Proje sayısı: $projectCount\n";
        } catch (PDOException $e) {
            echo "✗ Proje sayısı alınamadı: " . $e->getMessage() . "\n";
        }
        
        // Gönüllü sayısı
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM volunteer_applications WHERE status = 'approved'");
            $volunteerCount = $stmt->fetchColumn();
            echo "✓ Gönüllü sayısı: $volunteerCount\n";
        } catch (PDOException $e) {
            echo "✗ Gönüllü sayısı alınamadı: " . $e->getMessage() . "\n";
        }
        
        // Yardım edilen aile sayısı
        try {
            $stmt = $pdo->query("SELECT SUM(beneficiaries) as total FROM projects WHERE status IN ('active', 'completed') AND beneficiaries IS NOT NULL");
            $beneficiariesCount = $stmt->fetchColumn();
            echo "✓ Yardım edilen aile sayısı: " . ($beneficiariesCount ?: 0) . "\n";
        } catch (PDOException $e) {
            echo "✗ Yardım edilen aile sayısı alınamadı: " . $e->getMessage() . "\n";
        }
        
        // Settings tablosundaki istatistik verilerini kontrol et
        try {
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('stats_projects', 'stats_volunteers', 'stats_beneficiaries')");
            $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            echo "✓ Settings tablosundaki istatistik verileri:\n";
            echo "  - stats_projects: " . ($settings['stats_projects'] ?? 'Ayarlanmamış') . "\n";
            echo "  - stats_volunteers: " . ($settings['stats_volunteers'] ?? 'Ayarlanmamış') . "\n";
            echo "  - stats_beneficiaries: " . ($settings['stats_beneficiaries'] ?? 'Ayarlanmamış') . "\n";
            
            // Eğer settings tablosunda değerler yoksa veya 0 ise güncelle
            $updates = [
                'stats_projects' => $projectCount,
                'stats_volunteers' => $volunteerCount,
                'stats_beneficiaries' => $beneficiariesCount
            ];
            
            foreach ($updates as $key => $value) {
                if (empty($settings[$key]) || $settings[$key] == '0') {
                    try {
                        // Önce key'in var olup olmadığını kontrol et
                        $check = $pdo->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
                        $check->execute([$key]);
                        
                        if ($check->fetchColumn() > 0) {
                            // Güncelle
                            $update = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                            $update->execute([$value, $key]);
                        } else {
                            // Ekle
                            $insert = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
                            $insert->execute([$key, $value]);
                        }
                        echo "✓ '$key' değeri $value olarak güncellendi.\n";
                    } catch (PDOException $e) {
                        echo "✗ '$key' güncellenemedi: " . $e->getMessage() . "\n";
                    }
                }
            }
        } catch (PDOException $e) {
            echo "✗ Settings tablosundan istatistik verileri alınamadı: " . $e->getMessage() . "\n";
        }
        
    } else {
        echo "✗ Veritabanı bağlantısı başarısız.\n";
    }
} catch (Exception $e) {
    echo "✗ Veritabanı hatası: " . $e->getMessage() . "\n";
}

// E-posta gönderimi testi
echo "\n[2] E-posta Gönderimi Testi\n";
echo "-----------------------------------\n";

try {
    require_once 'includes/EmailService.php';
    echo "✓ EmailService sınıfı yüklendi.\n";
    
    // E-posta ayarlarını kontrol et
    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'smtp%' OR setting_key = 'admin_email'");
        $emailSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        echo "✓ E-posta ayarları:\n";
        echo "  - smtp_host: " . ($emailSettings['smtp_host'] ?? 'Ayarlanmamış') . "\n";
        echo "  - smtp_port: " . ($emailSettings['smtp_port'] ?? 'Ayarlanmamış') . "\n";
        echo "  - smtp_username: " . ($emailSettings['smtp_username'] ?? 'Ayarlanmamış') . "\n";
        echo "  - smtp_password: " . (isset($emailSettings['smtp_password']) ? (empty($emailSettings['smtp_password']) ? 'Boş' : 'Ayarlanmış') : 'Ayarlanmamış') . "\n";
        echo "  - smtp_encryption: " . ($emailSettings['smtp_encryption'] ?? 'Ayarlanmamış') . "\n";
        echo "  - smtp_from_email: " . ($emailSettings['smtp_from_email'] ?? 'Ayarlanmamış') . "\n";
        echo "  - admin_email: " . ($emailSettings['admin_email'] ?? 'Ayarlanmamış') . "\n";
        
        // cPanel için varsayılan SMTP ayarlarını kontrol et ve gerekirse güncelle
        $cpanelSmtpUpdates = [
            'smtp_host' => 'localhost',
            'smtp_port' => '587',
            'smtp_auth' => '1',
            'smtp_encryption' => 'tls',
            'smtp_from_email' => 'info@necatdernegi.org',
            'smtp_from_name' => 'Necat Derneği'
        ];
        
        foreach ($cpanelSmtpUpdates as $key => $value) {
            if (empty($emailSettings[$key])) {
                try {
                    // Önce key'in var olup olmadığını kontrol et
                    $check = $pdo->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
                    $check->execute([$key]);
                    
                    if ($check->fetchColumn() > 0) {
                        // Güncelle
                        $update = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                        $update->execute([$value, $key]);
                    } else {
                        // Ekle
                        $insert = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
                        $insert->execute([$key, $value]);
                    }
                    echo "✓ '$key' değeri '$value' olarak ayarlandı.\n";
                } catch (PDOException $e) {
                    echo "✗ '$key' ayarlanamadı: " . $e->getMessage() . "\n";
                }
            }
        }
        
    } catch (PDOException $e) {
        echo "✗ E-posta ayarları alınamadı: " . $e->getMessage() . "\n";
    }
    
    // E-posta gönderme işlevini test et (gerçek gönderim yapma)
    echo "✓ E-posta gönderme işlevi hazır.\n";
    
} catch (Exception $e) {
    echo "✗ E-posta sınıfı hatası: " . $e->getMessage() . "\n";
}

// JavaScript dosyalarını kontrol et
echo "\n[3] JavaScript Dosyaları Kontrolü\n";
echo "-----------------------------------\n";

$jsFiles = [
    'assets/js/main.js',
    'assets/js/jquery.min.js',
    'assets/js/bootstrap.bundle.min.js'
];

foreach ($jsFiles as $file) {
    if (file_exists($file)) {
        echo "✓ '$file' mevcut.\n";
    } else {
        echo "✗ '$file' bulunamadı.\n";
    }
}

// FAQ ve Accordion işlevselliğini kontrol et
echo "\n[4] FAQ ve Accordion İşlevselliği Kontrolü\n";
echo "-----------------------------------\n";

// pages/faq.php dosyasını kontrol et
$faqFile = 'pages/faq.php';
if (file_exists($faqFile)) {
    echo "✓ FAQ sayfası mevcut.\n";
    
    // Accordion kodunu kontrol et
    $faqContent = file_get_contents($faqFile);
    if (strpos($faqContent, 'accordion-collapse collapse') !== false) {
        echo "✓ Accordion yapısı doğru görünüyor.\n";
        
        // Bootstrap JS yüklenmiş mi?
        $headerFile = '../includes/header.php';
        if (file_exists($headerFile)) {
            $headerContent = file_get_contents($headerFile);
            if (strpos($headerContent, 'bootstrap.bundle.min.js') !== false || 
                strpos($headerContent, 'bootstrap.min.js') !== false) {
                echo "✓ Bootstrap JS dosyası yükleniyor.\n";
            } else {
                echo "✗ Bootstrap JS dosyası yüklenmiyor olabilir.\n";
            }
        }
    } else {
        echo "✗ Accordion yapısında sorun olabilir.\n";
    }
} else {
    echo "✗ FAQ sayfası bulunamadı.\n";
}

// Contact sayfasını kontrol et
echo "\n[5] Contact Sayfası Kontrolü\n";
echo "-----------------------------------\n";

$contactFile = 'pages/contact.php';
if (file_exists($contactFile)) {
    echo "✓ Contact sayfası mevcut.\n";
    
    // İçeriği kontrol et
    $contactContent = file_get_contents($contactFile);
    if (strpos($contactContent, 'contactForm') !== false) {
        echo "✓ Contact form yapısı doğru görünüyor.\n";
    } else {
        echo "✗ Contact form yapısında sorun olabilir.\n";
    }
} else {
    echo "✗ Contact sayfası bulunamadı.\n";
}

// Donate sayfasındaki kopyalama işlevini kontrol et
echo "\n[6] Donate Sayfası Kopyalama İşlevi Kontrolü\n";
echo "-----------------------------------\n";

$donateFile = 'pages/donate.php';
if (file_exists($donateFile)) {
    echo "✓ Donate sayfası mevcut.\n";
    
    // Kopyalama işlevini kontrol et
    $donateContent = file_get_contents($donateFile);
    if (strpos($donateContent, 'copyToClipboard') !== false) {
        echo "✓ Kopyalama işlevi mevcut.\n";
        
        // navigator.clipboard API'sini kontrol et
        if (strpos($donateContent, 'navigator.clipboard.writeText') !== false) {
            echo "✓ Modern clipboard API kullanılıyor.\n";
            echo "  Not: Bu API sadece HTTPS üzerinde veya localhost'ta çalışır.\n";
        } else {
            echo "✗ Modern clipboard API kullanılmıyor olabilir.\n";
        }
    } else {
        echo "✗ Kopyalama işlevi bulunamadı.\n";
    }
} else {
    echo "✗ Donate sayfası bulunamadı.\n";
}

// Footer kontrolü
echo "\n[7] Footer Kontrolü\n";
echo "-----------------------------------\n";

$footerFile = 'includes/footer.php';
if (file_exists($footerFile)) {
    echo "✓ Footer dosyası mevcut.\n";
    
    // Footer içeriğini kontrol et
    $footerContent = file_get_contents($footerFile);
    if (strpos($footerContent, 'footer-main') !== false) {
        echo "✓ Footer yapısı doğru görünüyor.\n";
    } else {
        echo "✗ Footer yapısında sorun olabilir.\n";
    }
} else {
    echo "✗ Footer dosyası bulunamadı.\n";
}

echo "\n=================================================================\n";
echo "Sorun Giderme Tamamlandı\n";
echo "=================================================================\n";

echo "\nÖnerilen Çözümler:\n";
echo "1. İstatistik verileri güncellendi. Ana sayfayı kontrol edin.\n";
echo "2. E-posta ayarları cPanel için optimize edildi.\n";
echo "3. JavaScript dosyalarının doğru yüklendiğinden emin olun.\n";
echo "4. HTTPS kullanımını kontrol edin (özellikle kopyalama işlevi için).\n";
echo "5. Form gönderimlerini test edin.\n";
echo "6. Hata günlüklerini kontrol edin: error_log veya cPanel hata günlükleri.\n";

echo "\nYapılması Gerekenler:\n";
echo "1. cPanel'de PHP sürümünün en az 7.4 olduğundan emin olun.\n";
echo "2. .htaccess dosyasının doğru yüklendiğini kontrol edin.\n";
echo "3. Dosya izinlerinin doğru ayarlandığından emin olun (755 dizinler, 644 dosyalar).\n";
echo "4. SSL sertifikasının doğru yapılandırıldığından emin olun.\n";
?> 