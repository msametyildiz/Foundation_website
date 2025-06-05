<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo clean_output($page_info['title']); ?></title>
    <meta name="description" content="<?php echo clean_output($page_info['description']); ?>">
    <meta name="keywords" content="<?php echo clean_output($page_info['keywords']); ?>">
    
    <!-- Performance and SEO optimizations -->
    <meta name="robots" content="index, follow">
    <meta name="author" content="Necat Derneği">
    <meta name="theme-color" content="#4EA674">
    
    <!-- Preload critical images -->
    <?php if(isset($_GET['page']) && $_GET['page'] == 'home' || !isset($_GET['page'])): ?>
    <link rel="preload" as="image" href="uploads/images/hero/hero-image.jpg" importance="high">
    <?php endif; ?>
    
    <!-- DNS prefetch for external resources -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Modern Navbar Styling -->
    <style>
        :root {
            --primary-color: #4EA674;
            --primary-dark: #3d8560;
            --primary-light: #6bb896;
            --secondary-color: #D3D92B;
            --accent-color: #F2E529;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #525252;
            --gray-700: #404040;
            --gray-800: #262626;
            --gray-900: #0D0D0D;
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition-fast: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-full: 9999px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            padding-top: 0;
        }

        /* Modern Navbar */
        .navbar-modern {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition-base);
            padding: 0;
        }

        .navbar-modern.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-xl);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
        }

        .navbar-modern.hidden {
            transform: translateY(-100%);
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 80px;
        }

        /* Logo Section */
        .navbar-brand-modern {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            transition: var(--transition-base);
        }

        .navbar-brand-modern:hover {
            transform: scale(1.02);
        }

        .logo-container {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(78, 166, 116, 0.3);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .brand-name {
            font-family: 'Poppins', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
        }

        .brand-tagline {
            font-size: 0.75rem;
            color: var(--gray-600);
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Navigation Menu */
        .navbar-nav-modern {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-item-modern {
            position: relative;
        }

        .nav-link-modern {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: var(--radius-lg);
            transition: var(--transition-base);
            position: relative;
            overflow: hidden;
        }

        .nav-link-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            opacity: 0;
            transition: var(--transition-base);
            border-radius: var(--radius-lg);
        }

        .nav-link-modern:hover::before,
        .nav-link-modern.active::before {
            opacity: 0.1;
        }

        .nav-link-modern:hover {
            color: var(--primary-color);
            transform: translateY(-1px);
        }

        .nav-link-modern.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .nav-link-modern span {
            position: relative;
            z-index: 1;
        }

        /* Dropdown Menu */
        .dropdown-modern {
            position: relative;
        }

        .dropdown-toggle-modern::after {
            content: '';
            margin-left: 0.5rem;
            border: solid var(--gray-600);
            border-width: 0 2px 2px 0;
            display: inline-block;
            padding: 3px;
            transform: rotate(45deg);
            transition: var(--transition-fast);
        }

        .dropdown-modern:hover .dropdown-toggle-modern::after {
            transform: rotate(225deg);
        }

        .dropdown-menu-modern {
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 240px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.75rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: var(--transition-base);
            z-index: 1001;
        }

        .dropdown-modern:hover .dropdown-menu-modern {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item-modern {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: var(--radius-lg);
            transition: var(--transition-fast);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .dropdown-item-modern:hover {
            background: rgba(78, 166, 116, 0.1);
            color: var(--primary-color);
            transform: translateX(4px);
        }

        /* CTA Button */
        .btn-cta-modern {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            text-decoration: none;
            border-radius: var(--radius-full);
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition-base);
            box-shadow: 0 4px 15px rgba(78, 166, 116, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-cta-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            opacity: 0;
            transition: var(--transition-base);
        }

        .btn-cta-modern:hover::before {
            opacity: 1;
        }

        .btn-cta-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78, 166, 116, 0.4);
            color: white;
        }

        .btn-cta-modern span,
        .btn-cta-modern i {
            position: relative;
            z-index: 1;
        }

        .btn-cta-modern i {
            animation: heartbeat 2s infinite;
        }

        @keyframes heartbeat {
            0%, 50%, 100% { transform: scale(1); }
            25% { transform: scale(1.1); }
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            flex-direction: column;
            gap: 4px;
            background: none;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
            border-radius: var(--radius-lg);
            transition: var(--transition-base);
        }

        .mobile-toggle:hover {
            background: rgba(78, 166, 116, 0.1);
        }

        .mobile-toggle span {
            width: 24px;
            height: 2px;
            background: var(--gray-700);
            border-radius: 2px;
            transition: var(--transition-base);
        }

        .mobile-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Mobile Menu */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 80px;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1.5rem 2rem;
            transform: translateY(-100%);
            transition: var(--transition-base);
            z-index: 999;
        }

        .mobile-menu.active {
            display: block;
            transform: translateY(0);
        }

        .mobile-nav {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .mobile-nav .nav-link-modern {
            padding: 1rem;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
        }

        .mobile-dropdown {
            margin-top: 0.5rem;
            padding-left: 1rem;
            border-left: 2px solid var(--gray-200);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .navbar-nav-modern {
                display: none;
            }

            .mobile-toggle {
                display: flex;
            }

            .navbar-container {
                padding: 0 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .navbar-container {
                padding: 0 1rem;
                min-height: 70px;
            }

            .logo-container {
                width: 45px;
                height: 45px;
                font-size: 1.25rem;
            }

            .brand-name {
                font-size: 1.1rem;
            }

            .brand-tagline {
                font-size: 0.7rem;
            }

            .mobile-menu {
                top: 70px;
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
        .nav-link-modern:focus,
        .btn-cta-modern:focus,
        .mobile-toggle:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
    </style>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo clean_output($page_info['title']); ?>">
    <meta property="og:description" content="<?php echo clean_output($page_info['description']); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL; ?>">
    <meta property="og:site_name" content="<?php echo SITE_NAME; ?>">
</head>
<body>
    <!-- Modern Navbar -->
    <nav class="navbar-modern" id="navbar">
        <div class="navbar-container">
            <!-- Brand -->
            <a href="index.php" class="navbar-brand-modern">
                <div class="logo-container">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="brand-text">
                    <h1 class="brand-name">Necat Derneği</h1>
                    <span class="brand-tagline">Yardım & Dayanışma</span>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <ul class="navbar-nav-modern">
                <li class="nav-item-modern">
                    <a href="index.php" class="nav-link-modern <?php echo is_active_page('home'); ?>">
                        <span>Ana Sayfa</span>
                    </a>
                </li>
                <li class="nav-item-modern">
                    <a href="index.php?page=about" class="nav-link-modern <?php echo is_active_page('about'); ?>">
                        <span>Hakkımızda</span>
                    </a>
                </li>
                <li class="nav-item-modern">
                    <a href="index.php?page=projects" class="nav-link-modern <?php echo is_active_page('projects'); ?>">
                        <span>Projelerimiz</span>
                    </a>
                </li>
                <li class="nav-item-modern">
                    <a href="index.php?page=volunteer" class="nav-link-modern <?php echo is_active_page('volunteer'); ?>">
                        <span>Gönüllü Ol</span>
                    </a>
                </li>
                <li class="nav-item-modern">
                    <a href="index.php?page=faq" class="nav-link-modern <?php echo is_active_page('faq'); ?>">
                        <span>SSS</span>
                    </a>
                </li>
                <li class="nav-item-modern">
                    <a href="index.php?page=contact" class="nav-link-modern <?php echo is_active_page('contact'); ?>">
                        <span>İletişim</span>
                    </a>
                </li>
                <li class="nav-item-modern dropdown-modern">
                    <a href="#" class="nav-link-modern dropdown-toggle-modern">
                        <span>Kurumsal</span>
                    </a>
                    <div class="dropdown-menu-modern">
                        <a href="index.php?page=press" class="dropdown-item-modern <?php echo is_active_page('press'); ?>">
                            <i class="fas fa-newspaper me-2"></i>
                            Basında Biz
                        </a>
                        <a href="index.php?page=documents" class="dropdown-item-modern <?php echo is_active_page('documents'); ?>">
                            <i class="fas fa-file-alt me-2"></i>
                            Belgelerimiz
                        </a>
                        <a href="index.php?page=team" class="dropdown-item-modern <?php echo is_active_page('team'); ?>">
                            <i class="fas fa-users me-2"></i>
                            Yönetim Kurulu
                        </a>
                    </div>
                </li>
                <li class="nav-item-modern">
                    <a href="index.php?page=donate" class="btn-cta-modern <?php echo is_active_page('donate'); ?>">
                        <i class="fas fa-heart"></i>
                        <span>Bağış Yap</span>
                    </a>
                </li>
            </ul>

            <!-- Mobile Toggle -->
            <button class="mobile-toggle" id="mobileToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <nav class="mobile-nav">
                <a href="index.php" class="nav-link-modern <?php echo is_active_page('home'); ?>">Ana Sayfa</a>
                <a href="index.php?page=about" class="nav-link-modern <?php echo is_active_page('about'); ?>">Hakkımızda</a>
                <a href="index.php?page=projects" class="nav-link-modern <?php echo is_active_page('projects'); ?>">Projelerimiz</a>
                <a href="index.php?page=volunteer" class="nav-link-modern <?php echo is_active_page('volunteer'); ?>">Gönüllü Ol</a>
                <a href="index.php?page=faq" class="nav-link-modern <?php echo is_active_page('faq'); ?>">SSS</a>
                <a href="index.php?page=contact" class="nav-link-modern <?php echo is_active_page('contact'); ?>">İletişim</a>
                <div class="mobile-dropdown">
                    <a href="index.php?page=press" class="nav-link-modern <?php echo is_active_page('press'); ?>">
                        <i class="fas fa-newspaper me-2"></i>
                        Basında Biz
                    </a>
                    <a href="index.php?page=documents" class="nav-link-modern <?php echo is_active_page('documents'); ?>">
                        <i class="fas fa-file-alt me-2"></i>
                        Belgelerimiz
                    </a>
                    <a href="index.php?page=team" class="nav-link-modern <?php echo is_active_page('team'); ?>">
                        <i class="fas fa-users me-2"></i>
                        Yönetim Kurulu
                    </a>
                </div>
                <a href="index.php?page=donate" class="btn-cta-modern <?php echo is_active_page('donate'); ?>" style="margin-top: 1rem; justify-self: center; width: fit-content;">
                    <i class="fas fa-heart"></i>
                    <span>Bağış Yap</span>
                </a>
            </nav>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="padding-top: 80px;">
