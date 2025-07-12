<?php
/**
 * Professional Header Component for Necat Derneği
 * Features: Modern design, performance optimization, accessibility compliance
 * Author: Necat Derneği Development Team
 * Version: 2.0
 */

// Security check - sadece uyarı ver, çıkış yapma
if (!defined('SITE_NAME')) {
    // Fallback değerler tanımla
    define('SITE_NAME', 'Necat Derneği');
    define('SITE_URL', '');
    define('SITE_EMAIL', 'info@necatdernegi.org');
}

// Include logo helper with error handling
try {
    require_once __DIR__ . '/logo-base64-helper.php';
} catch (Exception $e) {
    error_log('Logo helper not found: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="tr" class="no-js">
<head>
    <!-- ===== META INFORMATION ===== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- ===== SEO OPTIMIZATION ===== -->
    <title><?php echo clean_output($page_info['title'] ?? 'Necat Derneği'); ?></title>
    <meta name="description" content="<?php echo clean_output($page_info['description'] ?? ''); ?>">
    <meta name="keywords" content="<?php echo clean_output($page_info['keywords'] ?? ''); ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="author" content="Necat Derneği">
    <meta name="generator" content="Necat Derneği CMS v2.0">
    
    <!-- ===== BRANDING & THEME ===== -->
    <meta name="theme-color" content="#4EA674">
    <meta name="msapplication-TileColor" content="#4EA674">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Necat Derneği">
    
    <!-- ===== FAVICON ===== -->
    <link rel="icon" href="assets/images/favicon.ico" sizes="any">
    <link rel="icon" href="assets/images/favicon-16.png" type="image/png" sizes="16x16">
    <link rel="icon" href="assets/images/favicon-32.png" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="assets/images/favicon-192.png">
    <link rel="manifest" href="site.webmanifest">
    <meta name="msapplication-TileImage" content="assets/images/favicon-192.png">
    
    <!-- ===== PERFORMANCE OPTIMIZATION ===== -->
    <?php if(isset($_GET['page']) && $_GET['page'] == 'home' || !isset($_GET['page'])): ?>
    <link rel="preload" as="image" href="uploads/images/hero/hero-image.jpg" importance="high">
    <?php endif; ?>
    
    <!-- DNS Prefetch for Performance -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- ===== CRITICAL CSS ===== -->
    <style>
        /* Critical CSS for above-the-fold content */
        :root {
            --primary: #4EA674;
            --primary-dark: #3d8560;
            --primary-light: #6bb896;
            --secondary: #D3D92B;
            --white: #ffffff;
            --gray-50: #fafafa;
            --gray-100: #f5f5f5;
            --gray-700: #374151;
            --gray-900: #111827;
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        html { scroll-behavior: smooth; }
        * { box-sizing: border-box; }
        
        body {
            margin: 0;
            padding-top: 80px;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: var(--gray-700);
            background: var(--gray-50);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        body.loaded { opacity: 1; }
        
        /* Navbar Base Styles */
        .navbar-professional {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            height: 80px;
            transition: var(--transition);
        }
        
        .navbar-professional.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-lg);
        }
    </style>
    
    <!-- ===== EXTERNAL RESOURCES ===== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Critical CSS loaded first to prevent jumping */
        body {
            margin: 0;
            padding-top: 90px !important;
            opacity: 0;
            transition: opacity 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body.loaded {
            opacity: 1;
        }

        .navbar-modern {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 10000 !important;
            height: 90px !important;
            will-change: transform;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        :root {
            --primary-color: #4EA674;
            --primary-dark: #3d8560;
            --primary-light: #6bb896;
            --primary-gradient: linear-gradient(135deg, #4EA674 0%, #3d8560 100%);
            --secondary-color: #D3D92B;
            --accent-color: #F2E529;
            --white: #ffffff;
            --gray-50: #fafafa;
            --gray-100: #f5f5f5;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #525252;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --transition-fast: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-smooth: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
            --radius-full: 9999px;
            --backdrop-blur: blur(24px);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-feature-settings: "cv02", "cv03", "cv04", "cv11";
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Ultra Modern Professional Navbar */
        .navbar-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition-base);
            padding: 0;
            box-shadow: 0 4px 32px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
            position: relative;
        }

        .navbar-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(78, 166, 116, 0.02), rgba(211, 217, 43, 0.01));
            pointer-events: none;
            z-index: -1;
        }

        .navbar-modern.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            border-bottom: 1px solid rgba(78, 166, 116, 0.08);
        }

        .navbar-modern.scrolled::before {
            background: linear-gradient(135deg, rgba(78, 166, 116, 0.03), rgba(211, 217, 43, 0.015));
        }

        .navbar-modern.hidden {
            transform: translateY(-100%);
        }

        .navbar-container {
            max-width: 1420px;
            margin: 0 auto;
            padding: 0 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 90px;
            position: relative;
        }

        /* Simplified and Professional Brand Section */
        .navbar-brand-modern {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            transition: var(--transition-base);
            position: relative;
            z-index: 2;
            padding: 0.5rem 0;
        }

        .navbar-brand-modern:hover {
            transform: none; /* More stable */
        }

        .logo-container {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-md);
            transition: var(--transition-base);
            position: relative;
            background-color: transparent;
            box-shadow: none;
        }

        .navbar-brand-modern:hover .logo-container {
             transform: none;
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            border-radius: var(--radius-md);
            transition: var(--transition-fast);
            position: relative;
            z-index: 1;
            opacity: 1 !important;
        }

        .logo-container:hover img {
            transform: none;
        }

        /* Logo states */
        .logo-container.loading {
            background: var(--gray-100);
            border-color: var(--gray-200);
        }

        .logo-container.loading::after {
            content: '';
            width: 28px;
            height: 28px;
            border: 3px solid var(--gray-300);
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            animation: logoSpin 1s linear infinite;
            position: absolute;
            z-index: 2;
        }

        @keyframes logoSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .logo-container img.loaded {
            opacity: 1 !important;
        }

        /* Enhanced logo fallback */
        .logo-fallback {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 700;
            position: relative;
            z-index: 1;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: none; /* Simpler, no animation */
        }

        @keyframes heartPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Simplified brand text */
        .brand-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            line-height: 1.2;
            position: relative;
        }

        .brand-name {
            font-family: 'Poppins', sans-serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
            background: none;
            -webkit-background-clip: unset;
            -webkit-text-fill-color: unset;
            background-clip: unset;
            letter-spacing: -0.02em;
        }

        .brand-tagline {
            font-size: 0.8rem;
            color: var(--gray-500);
            font-weight: 400;
            letter-spacing: normal;
            text-transform: none;
            opacity: 1;
            position: relative;
        }

        .brand-tagline::before {
            display: none;
        }

        .navbar-brand-modern:hover .brand-tagline::before {
            width: 0;
        }

        /* Ultra Modern Navigation Menu */
        .navbar-nav-modern {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
            margin: 0;
            padding: 0;
            position: relative;
        }

        .nav-item-modern {
            position: relative;
        }

        .nav-link-modern {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: var(--radius-xl);
            transition: var(--transition-base);
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            letter-spacing: 0.01em;
        }

        .nav-link-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--primary-gradient);
            opacity: 0;
            transition: var(--transition-base);
            border-radius: var(--radius-xl);
            transform: scale(0.8);
        }

        .nav-link-modern::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: var(--primary-gradient);
            transition: var(--transition-base);
            border-radius: var(--radius-full);
        }

        .nav-link-modern:hover::before {
            opacity: 0.08;
            transform: scale(1);
        }

        .nav-link-modern:hover::after,
        .nav-link-modern.active::after {
            width: 80%;
        }

        .nav-link-modern:hover {
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .nav-link-modern.active {
            color: var(--primary-color);
            font-weight: 600;
            background: rgba(78, 166, 116, 0.05);
        }

        .nav-link-modern.active::before {
            opacity: 0.1;
            transform: scale(1);
        }

        .nav-link-modern span {
            position: relative;
            z-index: 1;
            transition: var(--transition-fast);
        }

        .nav-link-modern:hover span {
            transform: scale(1.02);
        }

        /* Enhanced Dropdown Menu */
        .dropdown-modern {
            position: relative;
        }

        .dropdown-toggle-modern::after {
            content: '';
            margin-left: 0.6rem;
            border: solid var(--gray-600);
            border-width: 0 2px 2px 0;
            display: inline-block;
            padding: 3px;
            transform: rotate(45deg);
            transition: var(--transition-base);
        }

        .dropdown-modern:hover .dropdown-toggle-modern::after {
            transform: rotate(225deg);
            border-color: var(--primary-color);
        }

        .dropdown-menu-modern {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            min-width: 260px;
            background: var(--glass-bg);
            backdrop-filter: var(--backdrop-blur);
            -webkit-backdrop-filter: var(--backdrop-blur);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-2xl);
            border: 1px solid var(--glass-border);
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-16px) scale(0.95);
            transition: var(--transition-base);
            z-index: 10001;
        }

        .dropdown-modern:hover .dropdown-menu-modern {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .dropdown-item-modern {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.25rem;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: var(--radius-lg);
            transition: var(--transition-base);
            font-size: 0.9rem;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .dropdown-item-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--primary-gradient);
            opacity: 0;
            transition: var(--transition-fast);
            border-radius: var(--radius-lg);
        }

        .dropdown-item-modern:hover::before {
            opacity: 0.1;
        }

        .dropdown-item-modern:hover {
            color: var(--primary-color);
            transform: translateX(8px);
            box-shadow: var(--shadow-md);
        }

        .dropdown-item-modern i {
            margin-right: 0.75rem;
            font-size: 0.875rem;
            color: var(--primary-color);
            transition: var(--transition-fast);
        }

        .dropdown-item-modern:hover i {
            transform: scale(1.1);
        }

        /* Enhanced CTA Button */
        .btn-cta-modern {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.875rem 2rem;
            background: var(--primary-gradient);
            color: white;
            text-decoration: none;
            border-radius: var(--radius-full);
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition-base);
            box-shadow: 0 4px 20px rgba(78, 166, 116, 0.35);
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
            letter-spacing: 0.01em;
            white-space: nowrap;
        }

        .btn-cta-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            opacity: 0;
            transition: var(--transition-base);
        }

        .btn-cta-modern::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: var(--transition-smooth);
        }

        .btn-cta-modern:hover::before {
            opacity: 1;
        }

        .btn-cta-modern:hover::after {
            width: 100px;
            height: 100px;
        }

        .btn-cta-modern:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 30px rgba(78, 166, 116, 0.5);
            color: white;
        }

        .btn-cta-modern:active {
            transform: translateY(-1px) scale(0.98);
        }

        .btn-cta-modern span,
        .btn-cta-modern i {
            position: relative;
            z-index: 1;
            transition: var(--transition-fast);
        }

        .btn-cta-modern:hover span {
            transform: scale(1.05);
        }

        .btn-cta-modern i {
            animation: heartbeat 2.5s ease-in-out infinite;
            font-size: 1rem;
        }

        @keyframes heartbeat {
            0%, 70%, 100% { transform: scale(1); }
            35% { transform: scale(1.15); }
        }

        /* Ultra Modern Mobile Toggle */
        .mobile-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: rgba(78, 166, 116, 0.08);
            border: 2px solid rgba(78, 166, 116, 0.15);
            padding: 0.875rem;
            cursor: pointer;
            border-radius: var(--radius-xl);
            transition: var(--transition-base);
            position: relative;
            overflow: hidden;
            width: 52px;
            height: 52px;
            justify-content: center;
            align-items: center;
            box-shadow: var(--shadow-md);
            z-index: 10002;
        }

        .mobile-toggle::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--primary-gradient);
            opacity: 0;
            transition: var(--transition-base);
            border-radius: var(--radius-lg);
            transform: scale(0.8);
        }

        .mobile-toggle:hover::before {
            opacity: 0.12;
            transform: scale(1);
        }

        .mobile-toggle:hover {
            background: rgba(78, 166, 116, 0.12);
            border-color: var(--primary-color);
            transform: scale(1.05);
            box-shadow: var(--shadow-lg);
        }

        .mobile-toggle span {
            width: 24px;
            height: 3px;
            background: var(--gray-700);
            border-radius: var(--radius-full);
            transition: var(--transition-base);
            position: relative;
            z-index: 1;
            transform-origin: center;
        }

        .mobile-toggle:hover span {
            background: var(--primary-color);
        }

        .mobile-toggle.active {
            background: rgba(78, 166, 116, 0.15);
            border-color: var(--primary-color);
            box-shadow: var(--shadow-xl);
        }

        .mobile-toggle.active span {
            background: var(--primary-color);
        }

        .mobile-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-toggle.active span:nth-child(2) {
            opacity: 0;
            transform: translateX(-24px) scale(0);
        }

        .mobile-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(8px, -8px);
        }

        /* Ultra Modern Mobile Menu */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 90px;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            border-bottom: 1px solid rgba(78, 166, 116, 0.1);
            padding: 2.5rem;
            transform: translateY(-100%);
            transition: var(--transition-smooth);
            z-index: 10001;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15), 0 10px 25px rgba(0, 0, 0, 0.08);
            max-height: calc(100vh - 90px);
            overflow-y: auto;
            border-top: 4px solid var(--primary-color);
            visibility: hidden;
            opacity: 0;
        }

        .mobile-menu::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(78, 166, 116, 0.02), rgba(255, 255, 255, 0.05));
            pointer-events: none;
        }

        .mobile-menu.active {
            display: block;
            transform: translateY(0);
            visibility: visible;
            opacity: 1;
            animation: slideDown 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        @keyframes slideDown {
            0% {
                transform: translateY(-100%);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .mobile-menu-header {
            text-align: center;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid rgba(78, 166, 116, 0.1);
            position: relative;
        }

        .mobile-menu-header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: var(--radius-full);
        }

        .mobile-menu-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .mobile-menu-title i {
            font-size: 1.2rem;
            animation: pulse 2s infinite;
        }

        .mobile-menu-subtitle {
            font-size: 0.85rem;
            color: var(--gray-500);
            font-weight: 500;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }

        .mobile-nav {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 500px;
            margin: 0 auto;
        }

        .mobile-nav .nav-link-modern {
            padding: 1.5rem 2rem;
            border-radius: var(--radius-2xl);
            border: 2px solid rgba(78, 166, 116, 0.08);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
            margin-bottom: 0.75rem;
            text-align: left;
            font-weight: 600;
            font-size: 1.05rem;
            transition: var(--transition-base);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05), 0 2px 6px rgba(0, 0, 0, 0.03);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: var(--gray-700);
        }

        .mobile-nav .nav-link-modern i {
            color: var(--primary-color);
            opacity: 0.9;
            transition: var(--transition-base);
            font-size: 1.25rem;
            margin-right: 1.25rem;
            width: 28px;
            text-align: center;
            background: linear-gradient(135deg, rgba(78, 166, 116, 0.1), rgba(78, 166, 116, 0.05));
            width: 48px;
            height: 48px;
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            box-shadow: 0 2px 8px rgba(78, 166, 116, 0.15);
        }

        .mobile-nav .nav-link-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(78, 166, 116, 0.1), transparent);
            transition: left 0.7s ease;
        }

        .mobile-nav .nav-link-modern:hover::before {
            left: 100%;
        }

        .mobile-nav .nav-link-modern::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 0;
            background: var(--primary-gradient);
            transition: var(--transition-base);
            border-radius: 0 var(--radius-xl) var(--radius-xl) 0;
        }

        .mobile-nav .nav-link-modern:hover::after,
        .mobile-nav .nav-link-modern.active::after {
            width: 6px;
        }

        .mobile-nav .nav-link-modern:hover {
            background: linear-gradient(135deg, rgba(78, 166, 116, 0.08), rgba(78, 166, 116, 0.05));
            border-color: var(--primary-color);
            transform: translateY(-6px) translateX(12px);
            box-shadow: 0 12px 30px rgba(78, 166, 116, 0.2), 0 6px 15px rgba(0, 0, 0, 0.1);
            color: var(--primary-color);
        }

        .mobile-nav .nav-link-modern:hover i {
            opacity: 1;
            transform: scale(1.2) rotate(5deg);
            color: var(--primary-color);
            background: linear-gradient(135deg, rgba(78, 166, 116, 0.15), rgba(78, 166, 116, 0.1));
            box-shadow: 0 4px 12px rgba(78, 166, 116, 0.25);
        }

        .mobile-nav .nav-link-modern.active {
            background: linear-gradient(135deg, rgba(78, 166, 116, 0.12), rgba(78, 166, 116, 0.08));
            border-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 700;
            transform: translateX(16px);
            box-shadow: 0 8px 25px rgba(78, 166, 116, 0.25), 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .mobile-nav .nav-link-modern.active i {
            opacity: 1;
            color: var(--primary-color);
            transform: scale(1.15);
            background: linear-gradient(135deg, rgba(78, 166, 116, 0.2), rgba(78, 166, 116, 0.15));
            box-shadow: 0 4px 15px rgba(78, 166, 116, 0.3);
        }

        .mobile-nav .btn-cta-modern {
            margin-top: 2rem;
            justify-content: center;
            padding: 1.5rem 3rem;
            border-radius: var(--radius-2xl);
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 12px 35px rgba(78, 166, 116, 0.4), 0 6px 20px rgba(0, 0, 0, 0.1);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .mobile-nav .btn-cta-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
            opacity: 0;
            transition: var(--transition-base);
        }

        .mobile-nav .btn-cta-modern:hover::before {
            opacity: 1;
        }

        .mobile-nav .btn-cta-modern:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 20px 45px rgba(78, 166, 116, 0.5), 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .mobile-dropdown {
            margin-top: 0.75rem;
            padding-left: 1.5rem;
            border-left: 3px solid var(--primary-color);
            background: rgba(78, 166, 116, 0.03);
            border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        /* Ultra Responsive Design */
        @media (max-width: 1200px) {
            .navbar-container {
                padding: 0 1.5rem;
                max-width: 100%;
            }

            .nav-link-modern {
                padding: 0.8rem 1.25rem;
                font-size: 0.9rem;
            }

            .btn-cta-modern {
                padding: 0.8rem 1.75rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 992px) {
            .navbar-nav-modern {
                display: none;
            }

            .mobile-toggle {
                display: flex;
            }

            .navbar-container {
                padding: 0 1.25rem;
            }

            .brand-text {
                display: flex; /* Changed from block to flex */
                align-items: baseline; /* Align items */
                gap: 0.5rem; /* Add gap between name and tagline */
            }

            .brand-name {
                font-size: 1.3rem;
            }

            .brand-tagline {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 80px !important;
            }

            .navbar-modern {
                height: 80px !important;
            }

            .navbar-container {
                padding: 0 1.25rem;
                min-height: 80px;
            }

            .brand-name {
                font-size: 1.25rem;
            }

            .brand-tagline {
                font-size: 0.72rem;
            }

            .mobile-toggle {
                width: 50px;
                height: 50px;
                padding: 0.8rem;
            }

            .mobile-toggle span {
                width: 22px;
                height: 2.5px;
            }

            .mobile-menu {
                top: 80px;
                padding: 2rem;
                max-height: calc(100vh - 80px);
                overflow-y: auto;
            }

            .mobile-nav .nav-link-modern {
                padding: 1.25rem 1.5rem;
                font-size: 1.02rem;
                border-radius: var(--radius-xl);
                margin-bottom: 0.6rem;
            }

            .mobile-nav .btn-cta-modern {
                padding: 1.25rem 2.25rem;
                margin-top: 1.5rem;
                justify-content: center;
                border-radius: var(--radius-xl);
                font-size: 1.05rem;
            }
        }

        @media (max-width: 576px) {
            body {
                padding-top: 70px !important;
            }

            .navbar-modern {
                height: 70px !important;
            }

            .navbar-container {
                padding: 0 0.875rem;
                min-height: 70px;
            }

            .brand-name {
                font-size: 1.1rem;
            }

            .brand-tagline {
                font-size: 0.65rem;
            }

            .mobile-toggle {
                width: 46px;
                height: 46px;
                padding: 0.75rem;
            }

            .mobile-toggle span {
                width: 20px;
                height: 2.5px;
            }

            .mobile-menu {
                top: 70px;
                padding: 1.25rem;
                max-height: calc(100vh - 70px);
            }

            .mobile-menu-header {
                padding-bottom: 1.25rem;
                margin-bottom: 1.25rem;
            }

            .mobile-menu-title {
                font-size: 1.1rem;
            }

            .mobile-nav .nav-link-modern {
                padding: 1rem 1.125rem;
                font-size: 0.95rem;
                text-align: left;
            }

            .mobile-nav .btn-cta-modern {
                padding: 1rem 1.75rem;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            .navbar-container {
                padding: 0 0.75rem;
            }

            .brand-text {
                display: flex;
                flex-direction: column;
                justify-content: center;
                margin-left: 10px;
            }

            .brand-name {
                font-size: 1rem;
                line-height: 1.2;
            }

            .brand-tagline {
                font-size: 0.65rem;
                line-height: 1;
            }

            .mobile-toggle {
                width: 44px;
                height: 44px;
            }

            .mobile-menu {
                padding: 1rem;
            }

            .mobile-nav .nav-link-modern {
                padding: 0.875rem 1rem;
                font-size: 0.9rem;
            }
        }

        /* Tablet landscape optimizations */
        @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
            .navbar-container {
                padding: 0 1.5rem;
            }

            .mobile-menu {
                max-height: calc(100vh - 85px);
                overflow-y: auto;
                padding: 1.5rem 2rem;
            }

            .mobile-nav {
                max-width: 600px;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
            }

            .mobile-nav .btn-cta-modern {
                grid-column: 1 / -1;
                margin-top: 1rem;
            }
        }

        /* Accessibility & Performance */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* Focus States for Accessibility */
        .nav-link-modern:focus,
        .btn-cta-modern:focus,
        .mobile-toggle:focus {
            outline: 3px solid var(--primary-color);
            outline-offset: 3px;
            box-shadow: 0 0 0 6px rgba(78, 166, 116, 0.2);
        }

        .nav-link-modern:focus-visible,
        .btn-cta-modern:focus-visible,
        .mobile-toggle:focus-visible {
            outline: 3px solid var(--primary-color);
            outline-offset: 3px;
        }

        /* Touch device optimizations */
        @media (pointer: coarse) {
            .nav-link-modern {
                padding: 1rem 1.5rem;
                min-height: 48px;
            }

            .mobile-toggle {
                min-width: 48px;
                min-height: 48px;
                padding: 1rem;
            }

            .mobile-nav .nav-link-modern {
                min-height: 52px;
                padding: 1.25rem 1.5rem;
            }

            .btn-cta-modern {
                min-height: 48px;
                padding: 1rem 2rem;
            }
        }

        /* High DPI displays */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .logo-container img {
                image-rendering: -webkit-optimize-contrast;
                image-rendering: crisp-edges;
            }
        }

        /* Dark mode support preparation */
        @media (prefers-color-scheme: dark) {
            :root {
                --glass-bg: rgba(15, 15, 15, 0.95);
                --glass-border: rgba(255, 255, 255, 0.1);
            }
        }

        /* Print styles */
        @media print {
            .navbar-modern, .mobile-menu {
                display: none !important;
            }

            body {
                padding-top: 0 !important;
            }
        }

        /* Landscape phone specific */
        @media screen and (max-height: 500px) and (orientation: landscape) {
            .mobile-menu {
                max-height: calc(100vh - 60px);
                overflow-y: auto;
                padding: 0.75rem 1rem;
            }

            .mobile-nav .nav-link-modern {
                padding: 0.75rem 1rem;
                margin-bottom: 0.25rem;
            }

            .mobile-nav .btn-cta-modern {
                margin-top: 0.5rem;
                padding: 0.75rem 1.5rem;
            }

            .mobile-menu-header {
                padding-bottom: 0.75rem;
                margin-bottom: 0.75rem;
            }
        }

        /* Modern loading states */
        .navbar-modern.loading {
            pointer-events: none;
        }

        .navbar-modern.loading .navbar-container {
            opacity: 0.7;
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }
        }

        /* Enhanced backdrop filter fallback */
        @supports not (backdrop-filter: blur(20px)) {
            .navbar-modern {
                background: rgba(255, 255, 255, 0.98);
            }

            .mobile-menu {
                background: rgba(255, 255, 255, 0.98);
            }
        }
    </style>

    <link rel="stylesheet" href="assets/css/style.css">

    <!-- JavaScript Libraries - Temel kütüphaneleri yükle, main.js footer'da yükleniyor -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/bootstrap.bundle.min.js" defer></script>
    <script src="assets/js/logo-base64.js" defer></script>
    
    <!-- cPanel Compatibility Script -->
    <script src="scripts/cpanel_compatibility.js" defer></script>
    
    <!-- Sayfa bazlı script yüklemeleri footer'da yapılıyor -->
</head>
<body>
    <nav class="navbar-modern" id="navbar" role="navigation" aria-label="Ana Gezinti">
        <div class="navbar-container">
            <a href="<?= site_url() ?>" class="navbar-brand-modern">
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
                <div class="brand-text">
                    <h1 class="brand-name">Necat Derneği</h1>
                    <span class="brand-tagline">Elinizi İyilik İçin Uzatın</span>
                </div>
            </a>

            <ul class="navbar-nav-modern">
                <li class="nav-item-modern">
                    <a href="<?= site_url() ?>" class="nav-link-modern <?php echo is_active_page('home'); ?>">
                        <span>Ana Sayfa</span>
                    </a>
                </li>
                <li class="nav-item-modern">
                    <a href="<?= site_url('about') ?>" class="nav-link-modern <?php echo is_active_page('about'); ?>">
                        <span>Hakkımızda</span>
                    </a>
                </li>
                <li class="nav-item-modern">
                    <a href="<?= site_url('projects') ?>" class="nav-link-modern <?php echo is_active_page('projects'); ?>">
                        <span>Projelerimiz</span>
                    </a>
                </li>
                <li class="nav-item-modern">
                    <a href="<?= site_url('volunteer') ?>" class="nav-link-modern <?php echo is_active_page('volunteer'); ?>">
                        <span>Gönüllü Ol</span>
                    </a>
                </li>
                <li class="nav-item-modern">
                    <a href="<?= site_url('faq') ?>" class="nav-link-modern <?php echo is_active_page('faq'); ?>">
                        <span>SSS</span>
                    </a>
                </li>
                <li class="nav-item-modern">
                    <a href="<?= site_url('contact') ?>" class="nav-link-modern <?php echo is_active_page('contact'); ?>">
                        <span>İletişim</span>
                    </a>
                </li>
                </li>
                <li class="nav-item-modern">
                    <a href="<?= site_url('donate') ?>" class="btn-cta-modern <?php echo is_active_page('donate'); ?>">
                        <i class="fas fa-heart"></i>
                        <span>Bağış Yap</span>
                    </a>
                </li>
            </ul>

            <button class="mobile-toggle" id="mobileToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-menu-header">
                <div class="mobile-menu-title">
                    <i class="fas fa-compass me-2"></i>
                    <span>Necat Derneği</span>
                </div>
            </div>
            <nav class="mobile-nav">
                <a href="<?= site_url() ?>" class="nav-link-modern <?php echo is_active_page('home'); ?>">
                    <i class="fas fa-home"></i>
                    <span>Ana Sayfa</span>
                </a>
                <a href="<?= site_url('about') ?>" class="nav-link-modern <?php echo is_active_page('about'); ?>">
                    <i class="fas fa-info-circle"></i>
                    <span>Hakkımızda</span>
                </a>
                <a href="<?= site_url('projects') ?>" class="nav-link-modern <?php echo is_active_page('projects'); ?>">
                    <i class="fas fa-project-diagram"></i>
                    <span>Projelerimiz</span>
                </a>
                <a href="<?= site_url('volunteer') ?>" class="nav-link-modern <?php echo is_active_page('volunteer'); ?>">
                    <i class="fas fa-hands-helping"></i>
                    <span>Gönüllü Ol</span>
                </a>
                <a href="<?= site_url('faq') ?>" class="nav-link-modern <?php echo is_active_page('faq'); ?>">
                    <i class="fas fa-question-circle"></i>
                    <span>Sık Sorulan Sorular</span>
                </a>
                <a href="<?= site_url('contact') ?>" class="nav-link-modern <?php echo is_active_page('contact'); ?>">
                    <i class="fas fa-envelope"></i>
                    <span>İletişim</span>
                </a>

                <a href="<?= site_url('donate') ?>" class="btn-cta-modern <?php echo is_active_page('donate'); ?>">
                    <i class="fas fa-heart"></i>
                    <span>Bağış Yap</span>
                </a>
            </nav>
        </div>
    </nav>

    <script>
        // Ultra Professional Enhanced Navigation System
        document.addEventListener('DOMContentLoaded', function() {
            // Professional page loading effect with enhanced animations
            document.body.classList.add('loaded');

            // Enhanced logo loading with sophisticated state management
            const logoContainer = document.getElementById('logoContainer');
            const logoImage = document.getElementById('logoImage');
            const logoFallback = document.getElementById('logoFallback');

            if (logoImage) {
                logoContainer.classList.add('loading');

                // Professional logo loading handler
                logoImage.addEventListener('load', function() {
                    console.log('Professional logo loaded successfully');
                    setTimeout(() => {
                        logoContainer.classList.remove('loading');
                        logoImage.classList.add('loaded');
                        logoImage.style.opacity = '1';
                        logoImage.style.display = 'block';
                        if (logoFallback) logoFallback.style.display = 'none';
                    }, 200);
                });

                logoImage.addEventListener('error', function() {
                    console.log('Logo failed, showing professional fallback');
                    logoContainer.classList.remove('loading');
                    logoImage.style.display = 'none';
                    if (logoFallback) {
                        logoFallback.style.display = 'flex';
                    }
                });

                // Check if logo is already loaded
                if (logoImage.complete && logoImage.naturalWidth > 0) {
                    logoImage.dispatchEvent(new Event('load'));
                } else if (logoImage.complete) {
                    logoImage.dispatchEvent(new Event('error'));
                }
            }

            // Ultra professional navbar scroll behavior
            let lastScrollTop = 0;
            let scrollTicking = false;
            const navbar = document.getElementById('navbar');
            const mobileMenu = document.getElementById('mobileMenu');

            function handleProfessionalScroll() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                // Professional glass morphism effect
                if (scrollTop > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }

                // Intelligent navbar hide/show (only when mobile menu is closed)
                if (!mobileMenu || !mobileMenu.classList.contains('active')) {
                    if (scrollTop > lastScrollTop && scrollTop > 150) {
                        navbar.classList.add('hidden');
                    } else if (scrollTop < lastScrollTop || scrollTop <= 100) {
                        navbar.classList.remove('hidden');
                    }
                } else if (mobileMenu && mobileMenu.classList.contains('active')) {
                    // Mobil menü açıkken navbar'ın her zaman görünür olmasını sağla
                    navbar.classList.remove('hidden');
                }

                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                scrollTicking = false;
            }

            // Performance-optimized scroll listener
            window.addEventListener('scroll', function() {
                if (!scrollTicking) {
                    requestAnimationFrame(handleProfessionalScroll);
                    scrollTicking = true;
                }
            }, { passive: true });

            // Ultra Professional Mobile Menu System
            const mobileToggle = document.getElementById('mobileToggle');
            let isMenuOpen = false;

            if (mobileToggle && mobileMenu) {

                // Professional menu toggle with advanced state management
                function toggleProfessionalMenu(forceClose = false) {
                    console.log('Toggle function called, forceClose:', forceClose, 'isMenuOpen:', isMenuOpen);

                    if (forceClose || isMenuOpen) {
                        // Professional close animation
                        console.log('Closing menu');
                        mobileToggle.classList.remove('active');
                        mobileMenu.classList.remove('active');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                        // Menü kapandığında navbar'ın görünürlüğünü scroll pozisyonuna göre ayarla
                        handleProfessionalScroll();
                        isMenuOpen = false;

                        // Enhanced focus management
                        setTimeout(() => mobileToggle.focus(), 300);
                    } else {
                        // Professional open animation
                        console.log('Opening menu');
                        mobileToggle.classList.add('active');
                        mobileMenu.classList.add('active');
                        document.body.style.overflow = 'hidden';
                        document.body.style.paddingRight = getScrollbarWidth() + 'px';
                        navbar.classList.remove('hidden'); // Menü açıkken navbar'ı her zaman göster
                        isMenuOpen = true;

                        // Enhanced focus management for accessibility
                        const firstMenuItem = mobileMenu.querySelector('.nav-link-modern');
                        if (firstMenuItem) {
                            setTimeout(() => firstMenuItem.focus(), 400);
                        }
                    }
                }

                // Professional toggle event handler
                mobileToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Mobile toggle clicked, current state:', isMenuOpen);
                    toggleProfessionalMenu();
                });

                // Professional menu item click handling
                const mobileNavLinks = mobileMenu.querySelectorAll('.nav-link-modern');
                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        console.log('Menu item clicked:', this.textContent.trim());
                        // Professional visual feedback
                        this.style.transform = 'scale(0.98)';
                        setTimeout(() => {
                            this.style.transform = '';
                            toggleProfessionalMenu(true);
                        }, 150);
                    });
                });

                // Professional outside click handling
                document.addEventListener('click', function(e) {
                    if (!mobileToggle.contains(e.target) &&
                        !mobileMenu.contains(e.target) &&
                        isMenuOpen) {
                        toggleProfessionalMenu(true);
                    }
                });

                // Professional keyboard navigation
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && isMenuOpen) {
                        toggleProfessionalMenu(true);
                    }

                    // Professional tab navigation within mobile menu
                    if (isMenuOpen && e.key === 'Tab') {
                        const focusableElements = mobileMenu.querySelectorAll(
                            'a[href], button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
                        );
                        const firstElement = focusableElements[0];
                        const lastElement = focusableElements[focusableElements.length - 1];

                        if (e.shiftKey && document.activeElement === firstElement) {
                            e.preventDefault();
                            lastElement.focus();
                        } else if (!e.shiftKey && document.activeElement === lastElement) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                });

                // Professional responsive handling
                let resizeTimeout;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(() => {
                        if (window.innerWidth > 992 && isMenuOpen) {
                            toggleProfessionalMenu(true);
                        }
                    }, 100);
                });

                // Professional touch gesture support
                let touchStartY = 0;
                mobileMenu.addEventListener('touchstart', function(e) {
                    touchStartY = e.changedTouches[0].screenY;
                }, { passive: true });

                /* Aşağı kaydırma ile menü kapanmasını engelliyoruz
                mobileMenu.addEventListener('touchend', function(e) {
                    const touchEndY = e.changedTouches[0].screenY;
                    if (touchStartY - touchEndY > 80) {
                        toggleProfessionalMenu(true);
                    }
                }, { passive: true });
                */
            }

            // Professional smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href === '#') return;

                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        const offset = 100;
                        const targetPosition = target.offsetTop - offset;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });

                        history.pushState(null, null, href);
                    }
                });
            });

            // Professional active navigation highlighting
            function updateActiveNavigation() {
                const sections = document.querySelectorAll('section[id], main[id], div[id]');
                const navLinks = document.querySelectorAll('.nav-link-modern');

                let currentSection = '';
                const scrollPosition = window.scrollY + 120;

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;

                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        currentSection = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    const href = link.getAttribute('href');
                    if (href === `#${currentSection}` ||
                        (currentSection === '' && href === 'index.php')) {
                        link.classList.add('active');
                    }
                });
            }

            // Professional scroll-based active highlighting
            let activeNavTimeout;
            window.addEventListener('scroll', function() {
                clearTimeout(activeNavTimeout);
                activeNavTimeout = setTimeout(updateActiveNavigation, 50);
            }, { passive: true });

            // Professional hover effect preloading
            const enhanceHoverEffects = () => {
                const interactiveElements = document.querySelectorAll('.nav-link-modern, .btn-cta-modern, .mobile-toggle');
                interactiveElements.forEach(element => {
                    element.addEventListener('mouseenter', function() {
                        this.style.willChange = 'transform, box-shadow, background';
                    });
                    element.addEventListener('mouseleave', function() {
                        this.style.willChange = 'auto';
                    });
                });
            };

            // Initialize professional enhancements
            setTimeout(enhanceHoverEffects, 500);

            // Professional scrollbar width calculation
            function getScrollbarWidth() {
                const outer = document.createElement('div');
                outer.style.cssText = 'visibility:hidden;overflow:scroll;position:absolute;top:-9999px;width:100px;height:100px';
                document.body.appendChild(outer);

                const inner = document.createElement('div');
                inner.style.width = '100%';
                outer.appendChild(inner);

                const scrollbarWidth = outer.offsetWidth - inner.offsetWidth;
                document.body.removeChild(outer);

                return scrollbarWidth;
            }

            // Professional Intersection Observer for performance
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in-view');
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '-80px 0px'
                });

                // Observe navigation items for enhanced animations
                document.querySelectorAll('.nav-link-modern, .mobile-nav .nav-link-modern').forEach(item => {
                    observer.observe(item);
                });
            }

            // Professional network-aware optimizations
            if ('connection' in navigator) {
                const connection = navigator.connection;
                if (connection.effectiveType === 'slow-2g' || connection.effectiveType === '2g') {
                    document.documentElement.classList.add('slow-connection');
                }
            }

            // Professional accessibility enhancements
            const enhanceAccessibility = () => {
                // Add aria labels for better screen reader support
                if (mobileToggle) mobileToggle.setAttribute('aria-label', 'Navigasyon menüsünü aç/kapat');
                if (mobileMenu) mobileMenu.setAttribute('aria-label', 'Mobil navigasyon menüsü');

                // Add role attributes
                const desktopNav = document.querySelector('.navbar-nav-modern');
                const mobileNav = document.querySelector('.mobile-nav');
                if (desktopNav) desktopNav.setAttribute('role', 'navigation');
                if (mobileNav) mobileNav.setAttribute('role', 'navigation');
            };

            enhanceAccessibility();

            // Professional initial state setup
            setTimeout(() => {
                handleProfessionalScroll();
                updateActiveNavigation();
            }, 100);
        });

        // Professional FOUC prevention
        document.documentElement.style.visibility = 'visible';

        // Professional performance monitoring
        if (typeof performance !== 'undefined' && performance.mark) {
            performance.mark('navigation-loaded');
        }
    </script>