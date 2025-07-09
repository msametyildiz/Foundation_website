<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer Test - Basit</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .test-info {
            background: #f8f9fa;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            border: 2px solid #dee2e6;
        }
        
        .test-info h2 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .info-item {
            margin: 10px 0;
            padding: 10px;
            background: white;
            border-radius: 5px;
            font-family: monospace;
        }
        
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
    </style>
</head>
<body>
    <div class="test-info">
        <h2>Footer Test Sayfası</h2>
        
        <?php
        // Hata raporlamayı aç
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        echo '<div class="info-item">';
        echo '<strong>PHP Versiyonu:</strong> ' . phpversion();
        echo '</div>';
        
        echo '<div class="info-item">';
        echo '<strong>Sunucu:</strong> ' . ($_SERVER['SERVER_NAME'] ?? 'Tanımsız');
        echo '</div>';
        
        // Config dosyasını kontrol et
        echo '<div class="info-item">';
        if (file_exists('config/database.php')) {
            echo '<span class="success">✓ config/database.php mevcut</span>';
            require_once 'config/database.php';
            
            // Database constants kontrolü
            $db_constants = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];
            echo '<ul>';
            foreach ($db_constants as $const) {
                if (defined($const)) {
                    echo '<li class="success">✓ ' . $const . ' tanımlı</li>';
                } else {
                    echo '<li class="error">✗ ' . $const . ' tanımlı değil</li>';
                }
            }
            echo '</ul>';
        } else {
            echo '<span class="error">✗ config/database.php bulunamadı</span>';
        }
        echo '</div>';
        
        // Footer dosyalarını kontrol et
        echo '<div class="info-item">';
        $footer_files = [
            'includes/footer.php',
            'includes/functions.php',
            'includes/logo-base64-helper.php'
        ];
        
        echo '<strong>Footer Dosyaları:</strong>';
        echo '<ul>';
        foreach ($footer_files as $file) {
            if (file_exists($file)) {
                echo '<li class="success">✓ ' . $file . ' mevcut</li>';
            } else {
                echo '<li class="error">✗ ' . $file . ' bulunamadı</li>';
            }
        }
        echo '</ul>';
        echo '</div>';
        
        // CSS/JS dosyalarını kontrol et
        echo '<div class="info-item">';
        $asset_files = [
            'assets/css/style.css',
            'assets/css/custom.css',
            'assets/js/main.js',
            'assets/js/navbar-modern.js'
        ];
        
        echo '<strong>Asset Dosyaları:</strong>';
        echo '<ul>';
        foreach ($asset_files as $file) {
            if (file_exists($file)) {
                echo '<li class="success">✓ ' . $file . ' mevcut</li>';
            } else {
                echo '<li class="error">✗ ' . $file . ' bulunamadı</li>';
            }
        }
        echo '</ul>';
        echo '</div>';
        ?>
    </div>
    
    <div style="flex: 1;">
        <!-- Boş alan - footer'ı aşağıda tutmak için -->
    </div>
    
    <?php
    // Footer'ı include et
    echo '<div class="test-info">';
    echo '<h3>Footer Include Ediliyor...</h3>';
    
    try {
        // Buffer kullan
        ob_start();
        $include_error = null;
        
        // Error handler
        set_error_handler(function($errno, $errstr, $errfile, $errline) use (&$include_error) {
            $include_error = "Error [$errno]: $errstr in $errfile on line $errline";
            return true;
        });
        
        include 'includes/footer.php';
        
        restore_error_handler();
        $footer_content = ob_get_clean();
        
        if ($include_error) {
            echo '<div class="error">Footer Include Hatası: ' . htmlspecialchars($include_error) . '</div>';
        } else {
            echo '<div class="success">✓ Footer başarıyla include edildi</div>';
            echo '<div class="info-item">Footer HTML uzunluğu: ' . strlen($footer_content) . ' karakter</div>';
        }
        
        // Footer içeriğini göster
        echo $footer_content;
        
    } catch (Exception $e) {
        echo '<div class="error">Exception: ' . $e->getMessage() . '</div>';
    }
    echo '</div>';
    ?>
    
    <script>
        // Footer elemanlarını kontrol et
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== Footer Element Check ===');
            
            const elements = {
                'footer-modern': 'Footer container',
                'footer-brand': 'Logo ve marka bölümü',
                'footer-links': 'Footer linkleri',
                'footer-social': 'Sosyal medya linkleri',
                'footer-contact-section': 'İletişim bölümü'
            };
            
            let foundCount = 0;
            for (const [className, description] of Object.entries(elements)) {
                const element = document.querySelector('.' + className);
                if (element) {
                    console.log('✓ ' + description + ' (' + className + ') bulundu');
                    foundCount++;
                } else {
                    console.error('✗ ' + description + ' (' + className + ') bulunamadı!');
                }
            }
            
            console.log('Toplam bulunan: ' + foundCount + '/' + Object.keys(elements).length);
            
            // CSS kontrolü
            const styles = window.getComputedStyle(document.body);
            console.log('Body font-family:', styles.fontFamily);
        });
    </script>
</body>
</html> 