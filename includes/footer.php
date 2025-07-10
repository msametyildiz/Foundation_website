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

// Varsayılan değerler tanımla
$default_settings = [
    'site_title' => 'Necat Derneği',
    'site_description' => 'Yardım eli uzatan, umut dağıtan bir toplum için birlikte çalışıyoruz.',
    'contact_email' => 'info@necatdernegi.org',
    'contact_phone' => '+90 312 444 56 78',
    'contact_address' => 'Kızılay Mahallesi, Atatürk Bulvarı No: 125/7, Çankaya/ANKARA',
    'social_instagram' => '#',
    'social_twitter' => '#',
    'social_linkedin' => '#',
    'social_youtube' => '#'
];

// Site ayarlarını varsayılan değerlerle başlat
$site_settings = $default_settings;

// Veritabanı bağlantısını kontrol et
$footer_pdo = null;

try {
    // Config dosyasını dahil et (eğer dahil edilmemişse)
    if (!defined('DB_HOST')) {
        $config_file = dirname(__DIR__) . '/config/database.php';
        if (file_exists($config_file)) {
            require_once $config_file;
        } else {
            throw new Exception('Config file not found');
        }
    }
    
    // Global $pdo değişkenini kullan (varsa)
    if (isset($pdo) && $pdo instanceof PDO) {
        $footer_pdo = $pdo;
    } else {
        // Yeni bağlantı oluştur
        $footer_pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }
    
    // Settings tablosundan verileri çek
    $stmt = $footer_pdo->prepare("SELECT setting_key, setting_value FROM settings");
    $stmt->execute();
    
    // Veritabanındaki değerleri varsayılan değerlerle birleştir
    while ($row = $stmt->fetch()) {
        if (!empty($row['setting_value'])) {
            $site_settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    
} catch (PDOException $e) {
    // Veritabanı hatası durumunda varsayılan değerler kullanılacak
    error_log("Footer database error: " . $e->getMessage());
} catch (Exception $e) {
    // Genel hata durumunda varsayılan değerler kullanılacak
    error_log("Footer general error: " . $e->getMessage());
}
?>
    <style>
        /* Ultra Professional Footer - Full Responsive Design */
        :root {
            --primary-color: #4EA674;
            --primary-dark: #3d8560;
            --primary-light: #6bb896;
            --secondary-color: #D3D92B;
            --secondary-dark: #bac325;
            --accent-color: #F2E529;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition-fast: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-smooth: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
            --radius-full: 9999px;
            --footer-gradient: linear-gradient(135deg, #f8f9fa 0%, #ffffff 25%, #f3f4f6 75%, #e5e7eb 100%);
        }

        /* Ultra Professional Footer Design */
        .footer-modern {
            position: relative;
            background: var(--footer-gradient);
            color: var(--gray-700);
            overflow: hidden;
            margin-top: auto;
            border-top: 1px solid rgba(78, 166, 116, 0.1);
            padding: 0;
            min-height: 400px;
            display: flex;
            flex-direction: column;
        }

        .footer-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(78, 166, 116, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(211, 217, 43, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(78, 166, 116, 0.02) 0%, transparent 50%);
            z-index: 1;
        }

        .footer-modern::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--primary-color) 100%);
            z-index: 3;
        }

        .footer-container {
            position: relative;
            z-index: 2;
            max-width: 1400px;
            margin: 0 auto;
            padding: 4rem 2rem 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Ultra Modern Grid Layout */
        .footer-main {
            display: grid;
            grid-template-columns: 2.2fr 1fr 1fr 1.5fr;
            gap: 4rem;
            margin-bottom: 3rem;
            flex: 1;
        }

        /* Ultra Professional Brand Section */
        .footer-brand {
            max-width: 400px;
            position: relative;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 2rem;
            position: relative;
        }

        .footer-logo-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: 
                0 8px 25px rgba(78, 166, 116, 0.25),
                0 4px 10px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            transition: var(--transition-smooth);
        }

        .footer-logo-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            transition: transform 0.6s ease;
        }

        .footer-logo:hover .footer-logo-icon {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 
                0 12px 35px rgba(78, 166, 116, 0.35),
                0 6px 15px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.25);
        }

        .footer-logo:hover .footer-logo-icon::before {
            transform: rotate(45deg) translate(100%, 100%);
        }

        .footer-brand-text h3 {
            font-family: 'Poppins', 'Inter', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            color: var(--gray-900);
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .footer-brand-tagline {
            font-size: 0.95rem;
            color: var(--gray-600);
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            opacity: 0.8;
        }

        .footer-description {
            font-size: 1.05rem;
            line-height: 1.7;
            color: var(--gray-600);
            margin-bottom: 2.5rem;
            max-width: 380px;
            font-weight: 400;
        }

        /* Ultra Professional Social Links */
        .footer-social {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .social-link {
            width: 52px;
            height: 52px;
            background: rgba(78, 166, 116, 0.08);
            backdrop-filter: blur(20px);
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition-smooth);
            border: 1px solid rgba(78, 166, 116, 0.15);
            position: relative;
            overflow: hidden;
            font-size: 1.25rem;
        }

        .social-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: var(--transition-base);
            border-radius: var(--radius-xl);
        }

        .social-link::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            border-radius: 50%;
            transition: var(--transition-smooth);
            transform: translate(-50%, -50%);
        }

        .social-link:hover::before {
            opacity: 1;
        }

        .social-link:hover::after {
            width: 100px;
            height: 100px;
        }

        .social-link:hover {
            transform: translateY(-4px) scale(1.08);
            box-shadow: 
                0 12px 30px rgba(78, 166, 116, 0.25),
                0 6px 15px rgba(0, 0, 0, 0.1);
            color: var(--white);
            border-color: transparent;
        }

        .social-link i {
            position: relative;
            z-index: 2;
            transition: var(--transition-base);
        }

        /* Ultra Professional Footer Sections */
        .footer-section {
            position: relative;
        }

        .footer-section h4 {
            font-family: 'Poppins', 'Inter', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: var(--gray-900);
            position: relative;
            padding-bottom: 0.75rem;
        }

        .footer-section h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: var(--radius-full);
            box-shadow: 0 2px 8px rgba(78, 166, 116, 0.3);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .footer-links li {
            margin: 0;
        }

        .footer-links a {
            color: var(--gray-600);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: var(--transition-smooth);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            border-radius: var(--radius-lg);
            position: relative;
            background: transparent;
            border: 1px solid transparent;
            overflow: hidden;
        }

        .footer-links a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 0;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transition: var(--transition-smooth);
            border-radius: var(--radius-lg);
        }

        .footer-links a::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(78, 166, 116, 0.03);
            opacity: 0;
            transition: var(--transition-base);
            border-radius: var(--radius-lg);
        }

        .footer-links a:hover {
            color: var(--white);
            transform: translateX(8px);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-color: var(--primary-color);
            box-shadow: 
                0 8px 25px rgba(78, 166, 116, 0.25),
                0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .footer-links a:hover::before {
            width: 4px;
        }

        .footer-links a:hover::after {
            opacity: 1;
        }

        .footer-links a i {
            font-size: 1rem;
            opacity: 0.8;
            color: var(--gray-500);
            transition: var(--transition-base);
            position: relative;
            z-index: 1;
            min-width: 20px;
        }

        .footer-links a:hover i {
            color: var(--white);
            opacity: 1;
            transform: scale(1.1);
        }

        .footer-links a span {
            position: relative;
            z-index: 1;
        }

        /* Ultra Professional Contact Section */
        .footer-contact-section .contact-info {
            background: transparent;
            padding: 0;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1.25rem;
            border-radius: var(--radius-xl);
            transition: var(--transition-smooth);
            background: rgba(78, 166, 116, 0.03);
            border: 1px solid rgba(78, 166, 116, 0.08);
            position: relative;
            overflow: hidden;
        }

        .contact-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(78, 166, 116, 0.05) 0%, rgba(211, 217, 43, 0.02) 100%);
            opacity: 0;
            transition: var(--transition-base);
        }

        .contact-item:hover {
            background: rgba(78, 166, 116, 0.06);
            border-color: rgba(78, 166, 116, 0.15);
            transform: translateY(-2px);
            box-shadow: 
                0 8px 25px rgba(78, 166, 116, 0.15),
                0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .contact-item:hover::before {
            opacity: 1;
        }

        .contact-item i {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.125rem;
            flex-shrink: 0;
            margin-top: 2px;
            position: relative;
            z-index: 1;
        }

        .contact-item span,
        .contact-item .contact-label {
            font-size: 1rem;
            color: var(--gray-600);
            line-height: 1.5;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        /* Footer Contact Enhancement */
        .footer-contact {
            background: rgba(78, 166, 116, 0.03);
            backdrop-filter: blur(20px);
            border-radius: var(--radius-2xl);
            padding: 2rem;
            border: 1px solid rgba(78, 166, 116, 0.1);
            margin-top: 1rem;
        }

        /* Ultra Professional Footer Bottom */
        .footer-bottom {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(78, 166, 116, 0.1);
            padding: 2rem 0;
            margin-top: auto;
            text-align: center;
            position: relative;
        }

        .footer-bottom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, var(--primary-color) 50%, transparent 100%);
        }

        .footer-copyright {
            font-size: 0.95rem;
            color: var(--gray-600);
            font-weight: 500;
            letter-spacing: 0.3px;
            margin: 0;
        }

        /* Ultra Professional Scroll to Top */
        .scroll-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            cursor: pointer;
            box-shadow: 
                0 8px 25px rgba(78, 166, 116, 0.3),
                0 4px 10px rgba(0, 0, 0, 0.1);
            transition: var(--transition-smooth);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px) scale(0.8);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .scroll-to-top::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
            border-radius: var(--radius-xl);
            opacity: 0;
            transition: var(--transition-base);
        }

        .scroll-to-top.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .scroll-to-top:hover {
            transform: translateY(-4px) scale(1.1);
            box-shadow: 
                0 12px 35px rgba(78, 166, 116, 0.4),
                0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .scroll-to-top:hover::before {
            opacity: 1;
        }

        .scroll-to-top:active {
            transform: translateY(-2px) scale(1.05);
        }

        /* ULTRA RESPONSIVE DESIGN - All Device Sizes */
        
        /* Büyük Ekranlı Masaüstü Bilgisayarlar: 1367px ve üzeri */
        @media (min-width: 1367px) {
            .footer-container {
                max-width: 1600px;
                padding: 3rem 4rem 0;
            }

            .footer-main {
                grid-template-columns: 2.5fr 1fr 1fr 1.5fr;
                gap: 4rem;
            }

            .footer-brand {
                max-width: 400px;
            }

            .footer-logo-icon {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }

            .footer-brand-text h3 {
                font-size: 1.75rem;
            }

            .footer-description {
                font-size: 1.1rem;
            }

            .social-link {
                width: 52px;
                height: 52px;
            }

            .social-link i {
                font-size: 1.25rem;
            }
        }

        /* Dizüstü Bilgisayarlar: 1200px – 1366px */
        @media (min-width: 1200px) and (max-width: 1366px) {
            .footer-container {
                max-width: 1400px;
                padding: 3rem 3rem 0;
            }

            .footer-main {
                grid-template-columns: 2.2fr 1fr 1fr 1.4fr;
                gap: 3.5rem;
            }

            .footer-brand {
                max-width: 380px;
            }

            .footer-logo-icon {
                width: 65px;
                height: 65px;
                font-size: 1.85rem;
            }

            .footer-brand-text h3 {
                font-size: 1.6rem;
            }
        }

        /* Orta Büyüklükte Tabletler: 992px – 1199px */
        @media (min-width: 992px) and (max-width: 1199px) {
            .footer-container {
                padding: 2.5rem 2rem 0;
            }

            .footer-main {
                grid-template-columns: 2fr 1fr 1fr 1.3fr;
                gap: 2.5rem;
            }

            .footer-brand {
                max-width: 320px;
            }

            .footer-description {
                font-size: 0.95rem;
            }

            .footer-section h4 {
                font-size: 1.1rem;
            }

            .footer-links a {
                font-size: 0.9rem;
            }

            .contact-item span {
                font-size: 0.9rem;
            }
        }

        /* Küçük Tabletler ve Büyük Telefonlar (Landscape): 768px – 991px */
        @media (min-width: 768px) and (max-width: 991px) {
            .footer-container {
                padding: 2rem 1.5rem 0;
            }

            .footer-main {
                grid-template-columns: 1fr 1fr;
                gap: 2.5rem;
            }

            .footer-brand {
                max-width: 100%;
                grid-column: 1 / -1;
                text-align: center;
                margin-bottom: 1rem;
            }

            .footer-social {
                justify-content: center;
                margin-bottom: 2rem;
            }

            .footer-section {
                text-align: center;
            }

            .footer-links {
                text-align: center;
            }

            .footer-contact-section .contact-info {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .contact-item {
                justify-content: center;
                max-width: 250px;
            }
        }

        /* Büyük Telefonlar (Portrait) ve Küçük Tabletler: 481px – 767px */
        @media (min-width: 481px) and (max-width: 767px) {
            .footer-modern {
                padding: 3rem 0 1.5rem 0;
            }

            .footer-container {
                padding: 0 1.5rem;
            }

            .footer-main {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
            }

            .footer-brand {
                max-width: 100%;
                margin-bottom: 2rem;
            }

            .footer-logo {
                justify-content: center;
                margin-bottom: 1rem;
            }

            .footer-description {
                text-align: center;
                margin-bottom: 1.5rem;
            }

            .footer-social {
                justify-content: center;
                gap: 0.75rem;
            }

            .social-link {
                width: 44px;
                height: 44px;
            }

            .social-link i {
                font-size: 1rem;
            }

            .footer-section h4 {
                text-align: center;
                position: relative;
                display: inline-block;
            }

            .footer-section h4::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .footer-links {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.5rem 1rem;
            }

            .footer-links li {
                margin-bottom: 0.5rem;
            }

            .footer-links a {
                padding: 0.5rem 1rem;
                border-radius: 25px;
                background: rgba(78, 166, 116, 0.05);
                border: 1px solid rgba(78, 166, 116, 0.1);
                transition: all 0.3s ease;
            }

            .footer-links a:hover {
                background: rgba(78, 166, 116, 0.1);
                padding: 0.5rem 1rem;
            }

            .footer-contact-section .contact-info {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.75rem;
            }

            .contact-item {
                justify-content: center;
                max-width: 280px;
                width: 100%;
            }

            .scroll-to-top {
                bottom: 1.5rem;
                right: 1.5rem;
                width: 48px;
                height: 48px;
            }
        }

        /* Orta Büyüklükte Telefonlar: 321px – 480px */
        @media (min-width: 321px) and (max-width: 480px) {
            .footer-modern {
                padding: 2.5rem 0 1.5rem 0;
            }

            .footer-container {
                padding: 0 1rem;
            }

            .footer-main {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                text-align: center;
            }

            .footer-brand {
                margin-bottom: 1.5rem;
            }

            .footer-logo-icon {
                width: 55px;
                height: 55px;
                font-size: 1.6rem;
            }

            .footer-brand-text h3 {
                font-size: 1.4rem;
            }

            .footer-brand-tagline {
                font-size: 0.8rem;
            }

            .footer-description {
                font-size: 0.95rem;
                line-height: 1.5;
            }

            .footer-social {
                gap: 0.5rem;
                margin-bottom: 1.5rem;
            }

            .social-link {
                width: 42px;
                height: 42px;
            }

            .footer-section h4 {
                font-size: 1.05rem;
                margin-bottom: 1rem;
            }

            .footer-links {
                display: grid;
                grid-template-columns: 1fr;
                gap: 0.25rem;
                max-width: 250px;
                margin: 0 auto;
            }

            .footer-links a {
                padding: 0.75rem 1rem;
                margin: 0;
                border-radius: 12px;
                background: rgba(78, 166, 116, 0.03);
                border: 1px solid rgba(78, 166, 116, 0.08);
                font-size: 0.9rem;
            }

            .footer-links a:hover {
                padding: 0.75rem 1rem;
                background: rgba(78, 166, 116, 0.08);
            }

            .contact-item {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
                max-width: 240px;
                padding: 1rem;
                border-radius: 12px;
                background: rgba(78, 166, 116, 0.03);
            }

            .contact-item i {
                align-self: center;
                margin-bottom: 0.25rem;
            }

            .footer-bottom {
                padding: 1.5rem 0;
                margin-top: 2rem;
            }

            .footer-copyright {
                font-size: 0.8rem;
                line-height: 1.4;
            }

            .scroll-to-top {
                bottom: 1rem;
                right: 1rem;
                width: 45px;
                height: 45px;
            }
        }

        /* Küçük Telefonlar: 320px ve alt */
        @media (max-width: 320px) {
            .footer-modern {
                padding: 2rem 0 1rem 0;
            }

            .footer-container {
                padding: 0 0.75rem;
            }

            .footer-main {
                gap: 1.25rem;
            }

            .footer-brand {
                margin-bottom: 1.25rem;
            }

            .footer-logo {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .footer-logo-icon {
                width: 50px;
                height: 50px;
                font-size: 1.4rem;
            }

            .footer-brand-text h3 {
                font-size: 1.25rem;
            }

            .footer-brand-tagline {
                font-size: 0.75rem;
            }

            .footer-description {
                font-size: 0.9rem;
                padding: 0 0.5rem;
            }

            .footer-social {
                gap: 0.4rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .social-link {
                width: 38px;
                height: 38px;
            }

            .social-link i {
                font-size: 0.9rem;
            }

            .footer-section h4 {
                font-size: 1rem;
                margin-bottom: 0.75rem;
            }

            .footer-links {
                max-width: 200px;
            }

            .footer-links a {
                padding: 0.65rem 0.75rem;
                font-size: 0.85rem;
            }

            .contact-item {
                max-width: 200px;
                padding: 0.75rem;
                font-size: 0.85rem;
            }

            .contact-item i {
                font-size: 0.9rem;
            }

            .footer-bottom {
                padding: 1.25rem 0;
            }

            .footer-copyright {
                font-size: 0.75rem;
                padding: 0 0.5rem;
            }

            .scroll-to-top {
                bottom: 0.75rem;
                right: 0.75rem;
                width: 40px;
                height: 40px;
                font-size: 0.9rem;
            }
        }

        /* Extra optimization for very small screens */
        @media (max-width: 280px) {
            .footer-container {
                padding: 0 0.5rem;
            }

            .footer-main {
                gap: 1rem;
            }

            .footer-brand-text h3 {
                font-size: 1.1rem;
            }

            .footer-description {
                font-size: 0.85rem;
            }

            .footer-links {
                max-width: 180px;
            }

            .contact-item {
                max-width: 180px;
                padding: 0.5rem;
            }
        }

        /* High DPI / Retina Display Optimizations */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .footer-logo-icon {
                border: 0.5px solid rgba(255, 255, 255, 0.1);
            }

            .social-link {
                border: 0.5px solid rgba(78, 166, 116, 0.15);
            }
        }

        /* Touch device optimizations */
        @media (pointer: coarse) {
            .social-link,
            .footer-links a,
            .scroll-to-top {
                min-height: 44px;
                min-width: 44px;
            }

            .footer-links a {
                padding: 0.75rem 1rem;
            }
        }

        /* Landscape orientation for mobile devices */
        @media (max-width: 967px) and (orientation: landscape) {
            .footer-main {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1.5rem;
            }

            .footer-brand {
                grid-column: 1 / -1;
                text-align: center;
                max-width: 100%;
            }

            .footer-description {
                max-width: 600px;
                margin: 0 auto 1.5rem auto;
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Focus States */
        .social-link:focus,
        .footer-links a:focus,
        .scroll-to-top:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
    </style>

    <!-- Modern Footer -->
    <footer class="footer-modern">
        <div class="footer-container">
            <!-- Main Footer Content -->
            <div class="footer-main">
                <!-- Brand Section -->
                <div class="footer-brand">
                    <div class="footer-logo">
                         <div class="logo-container" id="logoContainer">
                    <?php
                    if (LogoBase64Helper::isLogoAvailable()) {
                        echo LogoBase64Helper::getLogoForNavbar([
                            'id' => 'logoImage',
                            'style' => 'height: 40px; width: auto;',
                            'onload' => "console.log('Base64 logo loaded successfully'); this.classList.add('loaded');",
                            'onerror' => "console.log('Base64 logo failed, showing fallback'); this.style.display='none'; document.getElementById('logoFallback').style.display='flex';"
                        ]);
                    } else {
                        // Fallback to regular logo file
                        echo '<img src="assets/images/logo.png?v=' . time() . '" alt="Necat Derneği Logo" id="logoImage" style="height: 40px; width: auto;" onload="console.log(\'Logo loaded successfully\'); this.classList.add(\'loaded\');" onerror="this.style.display=\'none\'; document.getElementById(\'logoFallback\').style.display=\'flex\';">';
                    }
                    ?>
                    <div class="logo-fallback" id="logoFallback" style="display: none;">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
                        <div class="footer-brand-text">
                            <h3>Necat Derneği</h3>
                        </div>
                    </div>
                    <p class="footer-description">
                        <?= htmlspecialchars($site_settings['site_description'] ?? 'Yardım & Dayanışma ruhuyla elinizi iyilik için uzatın. Topluma faydalı projelerle daha iyi bir gelecek inşa ediyoruz.') ?>
                    </p>
                    <div class="footer-social">
                        <?php
                        // Sosyal medya linklerini formatla
                        $social_links = [
                            'Instagram' => ['key' => 'social_instagram', 'icon' => 'fab fa-instagram', 'base_url' => 'https://instagram.com/', 'label' => 'Instagram'],
                            'Twitter' => ['key' => 'social_twitter', 'icon' => 'fab fa-twitter', 'base_url' => 'https://twitter.com/', 'label' => 'Twitter'],
                            'LinkedIn' => ['key' => 'social_linkedin', 'icon' => 'fab fa-linkedin-in', 'base_url' => 'https://linkedin.com/in/', 'label' => 'LinkedIn'],
                            'YouTube' => ['key' => 'social_youtube', 'icon' => 'fab fa-youtube', 'base_url' => 'https://youtube.com/@', 'label' => 'YouTube']
                        ];
                        
                        foreach ($social_links as $platform => $data) {
                            $url = $site_settings[$data['key']] ?? '';
                            
                            if (!empty($url) && $url !== '#') {
                                // URL formatını düzenle
                                if (!str_starts_with($url, 'http') && !empty($data['base_url'])) {
                                    $url = $data['base_url'] . ltrim($url, '@');
                                }
                                
                                echo '<a href="' . htmlspecialchars($url) . '" class="social-link" aria-label="' . $data['label'] . '" target="_blank">';
                                echo '<i class="' . $data['icon'] . '"></i>';
                                echo '</a>';
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- Hızlı Bağlantılar -->
                <div class="footer-section">
                    <h4>Hızlı Bağlantılar</h4>
                    <ul class="footer-links">
                        <li><a href="<?= site_url() ?>"><i class="fas fa-home"></i> Ana Sayfa</a></li>
                        <li><a href="<?= site_url('about') ?>"><i class="fas fa-info-circle"></i> Hakkımızda</a></li>
                        <li><a href="<?= site_url('projects') ?>"><i class="fas fa-project-diagram"></i> Projelerimiz</a></li>
                        <li><a href="<?= site_url('volunteer') ?>"><i class="fas fa-hands-helping"></i> Gönüllü Ol</a></li>
                    </ul>
                </div>

                <!-- Daha Fazla -->
                <div class="footer-section">
                    <h4>Daha Fazla</h4>
                    <ul class="footer-links">
                        <li><a href="<?= site_url('faq') ?>"><i class="fas fa-question-circle"></i> SSS</a></li>
                        <li><a href="<?= site_url('contact') ?>"><i class="fas fa-envelope"></i> İletişim</a></li>
                        <li><a href="<?= site_url('donate') ?>"><i class="fas fa-heart"></i> Bağış Yap</a></li>
                    </ul>
                </div>

                <!-- Bize Ulaşın -->
                <div class="footer-section footer-contact-section">
                    <h4>Bize Ulaşın</h4>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= htmlspecialchars($site_settings['contact_address'] ?? 'Kızılay Mahallesi, Atatürk Bulvarı No: 125/7, Çankaya/ANKARA') ?></span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span><?= htmlspecialchars($site_settings['contact_phone'] ?? '+90 312 444 56 78') ?></span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span><?= htmlspecialchars($site_settings['contact_email'] ?? 'info@necatdernegi.org') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-copyright">
                    &copy; <span id="currentYear">2024</span> Necat Derneği. Tüm hakları saklıdır.
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTop" aria-label="Sayfa başına dön">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Modern Navbar JS -->
    <script src="assets/js/navbar-modern.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    
    <?php
    // Page-specific JavaScript files
    $current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
    $page_js_files = [
        'home' => 'homepage.js',
        'about' => 'about-page.js',
        // Add more page-specific JS files as needed
    ];
    
    if (isset($page_js_files[$current_page])) {
        $js_file = "assets/js/" . $page_js_files[$current_page];
        if (file_exists($js_file)) {
            echo "<script src=\"{$js_file}\"></script>\n    ";
        }
    }
    ?>

    <script>
        // Modern Footer Functionality with Enhanced Responsive Features
        document.addEventListener('DOMContentLoaded', function() {
            // Update current year
            document.getElementById('currentYear').textContent = new Date().getFullYear();

            // Responsive scroll to top functionality
            const scrollToTopBtn = document.getElementById('scrollToTop');
            
            // Adjust scroll threshold based on screen size
            function getScrollThreshold() {
                if (window.innerWidth <= 480) return 200;
                if (window.innerWidth <= 768) return 250;
                return 300;
            }
            
            window.addEventListener('scroll', function() {
                const threshold = getScrollThreshold();
                if (window.pageYOffset > threshold) {
                    scrollToTopBtn.classList.add('visible');
                } else {
                    scrollToTopBtn.classList.remove('visible');
                }
            });

            scrollToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Enhanced responsive social link interactions
            const socialLinks = document.querySelectorAll('.social-link');
            socialLinks.forEach(link => {
                // Touch device optimization
                if ('ontouchstart' in window) {
                    link.addEventListener('touchstart', function() {
                        this.style.transform = 'translateY(-2px) scale(1.02)';
                    });
                    
                    link.addEventListener('touchend', function() {
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);
                    });
                } else {
                    link.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-3px) scale(1.05)';
                    });
                    
                    link.addEventListener('mouseleave', function() {
                        this.style.transform = '';
                    });
                }
            });

            // Responsive footer links behavior
            const footerLinks = document.querySelectorAll('.footer-links a');
            footerLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Enhanced click animation for different screen sizes
                    const scale = window.innerWidth <= 480 ? '0.97' : '0.95';
                    this.style.transform = `scale(${scale})`;
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            // Responsive contact item interactions
            const contactItems = document.querySelectorAll('.contact-item');
            contactItems.forEach(item => {
                if (window.innerWidth <= 768) {
                    item.addEventListener('click', function() {
                        // Add subtle feedback on mobile
                        this.style.background = 'rgba(78, 166, 116, 0.08)';
                        setTimeout(() => {
                            this.style.background = '';
                        }, 200);
                    });
                }
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        // Adjust scroll offset for smaller screens
                        const offset = window.innerWidth <= 768 ? 80 : 100;
                        const targetPosition = target.offsetTop - offset;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Enhanced accessibility with responsive considerations
            const focusableElements = document.querySelectorAll('a, button, input, select, textarea');
            focusableElements.forEach(element => {
                element.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        if (this.tagName !== 'INPUT' && this.tagName !== 'TEXTAREA') {
                            e.preventDefault();
                            this.click();
                        }
                    }
                });
            });

            // Performance optimization with Intersection Observer
            const observerOptions = {
                threshold: window.innerWidth <= 768 ? 0.05 : 0.1,
                rootMargin: window.innerWidth <= 480 ? '0px 0px -30px 0px' : '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe footer sections for entrance animation
            const footerSections = document.querySelectorAll('.footer-section, .footer-brand');
            footerSections.forEach(section => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                section.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                observer.observe(section);
            });

            // Add staggered animation delays based on screen size
            footerSections.forEach((section, index) => {
                const delay = window.innerWidth <= 480 ? index * 0.05 : index * 0.1;
                section.style.transitionDelay = `${delay}s`;
            });

            // Responsive window resize handler
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    // Recalculate responsive behaviors on resize
                    const newThreshold = getScrollThreshold();
                    
                    // Update scroll to top visibility
                    if (window.pageYOffset > newThreshold) {
                        scrollToTopBtn.classList.add('visible');
                    } else {
                        scrollToTopBtn.classList.remove('visible');
                    }
                    
                    // Reset any transforms that might be stuck
                    socialLinks.forEach(link => {
                        link.style.transform = '';
                    });
                    
                    footerLinks.forEach(link => {
                        link.style.transform = '';
                    });
                }, 250);
            });

            // Optimize for touch devices
            if ('ontouchstart' in window) {
                // Add touch-friendly hover states
                document.body.classList.add('touch-device');
                
                // Improve touch targets for small screens
                if (window.innerWidth <= 480) {
                    const touchTargets = document.querySelectorAll('.social-link, .footer-links a, .scroll-to-top');
                    touchTargets.forEach(target => {
                        const currentStyle = window.getComputedStyle(target);
                        const currentHeight = parseInt(currentStyle.height);
                        const currentWidth = parseInt(currentStyle.width);
                        
                        if (currentHeight < 44 || currentWidth < 44) {
                            target.style.minHeight = '44px';
                            target.style.minWidth = '44px';
                        }
                    });
                }
            }

            // Add CSS for touch devices
            const touchCSS = document.createElement('style');
            touchCSS.textContent = `
                .touch-device .social-link:active,
                .touch-device .footer-links a:active,
                .touch-device .scroll-to-top:active {
                    transform: scale(0.95);
                    transition: transform 0.1s ease;
                }
                
                .touch-device .contact-item:active {
                    background: rgba(78, 166, 116, 0.08) !important;
                }
            `;
            document.head.appendChild(touchCSS);

            // Performance monitoring for slower devices
            const startTime = performance.now();
            requestAnimationFrame(() => {
                const endTime = performance.now();
                const frameDuration = endTime - startTime;
                
                // If frame takes too long, reduce animations
                if (frameDuration > 16.67) { // ~60fps threshold
                    document.body.classList.add('reduce-motion');
                    
                    const reduceMotionCSS = document.createElement('style');
                    reduceMotionCSS.textContent = `
                        .reduce-motion * {
                            animation-duration: 0.5s !important;
                            transition-duration: 0.3s !important;
                        }
                    `;
                    document.head.appendChild(reduceMotionCSS);
                }
            });
        });

        // Enhanced utility notification function with responsive positioning
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            const isSmallScreen = window.innerWidth <= 480;
            
            notification.style.cssText = `
                position: fixed;
                top: ${isSmallScreen ? '1rem' : '2rem'};
                right: ${isSmallScreen ? '1rem' : '2rem'};
                left: ${isSmallScreen ? '1rem' : 'auto'};
                background: ${type === 'success' ? '#D3D92B' : '#ef4444'};
                color: white;
                padding: ${isSmallScreen ? '0.75rem 1rem' : '1rem 2rem'};
                border-radius: 0.75rem;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                z-index: 9999;
                font-weight: 500;
                font-size: ${isSmallScreen ? '0.875rem' : '1rem'};
                transform: translateX(${isSmallScreen ? '0' : '100%'});
                opacity: ${isSmallScreen ? '0' : '1'};
                transition: all 0.3s ease;
                max-width: ${isSmallScreen ? '280px' : '400px'};
                word-wrap: break-word;
            `;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (isSmallScreen) {
                    notification.style.opacity = '1';
                    notification.style.transform = 'translateY(0)';
                } else {
                    notification.style.transform = 'translateX(0)';
                }
            }, 100);
            
            setTimeout(() => {
                if (isSmallScreen) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-20px)';
                } else {
                    notification.style.transform = 'translateX(100%)';
                }
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
