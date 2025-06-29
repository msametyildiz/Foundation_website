</main>

<?php
// Footer için site ayarlarını çek
try {
    if (!isset($site_settings)) {
        $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM settings");
        $stmt->execute();
        $site_settings = [];
        while ($row = $stmt->fetch()) {
            $site_settings[$row['setting_key']] = $row['setting_value'];
        }
    }
} catch (PDOException $e) {
    $site_settings = [
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
}
?>
    <style>
        /* Modern Footer Styling */
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
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
            --radius-full: 9999px;
        }

        /* Modern Footer */
        .footer-modern {
            position: relative;
            background: #f8f9fa;
            color: var(--gray-700);
            overflow: hidden;
            margin-top: auto;
            border-top: 1px solid #e9ecef;
            padding: 4rem 0 2rem 0;
        }

        .footer-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(78, 166, 116, 0.02) 0%, rgba(211, 217, 43, 0.02) 100%);
            z-index: 1;
        }

        .footer-container {
            position: relative;
            z-index: 2;
            max-width: 1400px;
            margin: 0 auto;
            padding: 3rem 2rem 0;
        }

        /* Main Footer Content */
        .footer-main {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 3rem;
            margin-bottom: 3rem;
            padding-top: 3rem;
        }

        /* Brand Section */
        .footer-brand {
            max-width: 350px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .footer-logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.75rem;
            box-shadow: 0 8px 25px rgba(78, 166, 116, 0.3);
        }

        .footer-brand-text h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.25rem 0;
            color: var(--gray-900);
        }

        .footer-brand-tagline {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .footer-description {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--gray-600);
            margin-bottom: 2rem;
        }

        /* Social Links */
        .footer-social {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            width: 48px;
            height: 48px;
            background: rgba(78, 166, 116, 0.1);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition-base);
            border: 1px solid rgba(78, 166, 116, 0.2);
            position: relative;
            overflow: hidden;
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
        }

        .social-link:hover::before {
            opacity: 1;
        }

        .social-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(78, 166, 116, 0.3);
            color: var(--white);
        }

        .social-link i {
            position: relative;
            z-index: 1;
            font-size: 1.125rem;
        }

        /* Footer Sections */
        .footer-section h4 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--gray-900);
            position: relative;
        }

        .footer-section h4::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 0;
            width: 30px;
            height: 2px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: var(--gray-600);
            text-decoration: none;
            font-size: 0.95rem;
            transition: var(--transition-base);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
            border-radius: var(--radius-lg);
            position: relative;
        }

        .footer-links a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 0;
            height: 1px;
            background: var(--primary-color);
            transition: var(--transition-base);
            transform: translateY(-50%);
        }

        .footer-links a:hover {
            color: var(--primary-color);
            padding-left: 1rem;
        }

        .footer-links a:hover::before {
            width: 20px;
        }

        .footer-links a i {
            font-size: 0.875rem;
            opacity: 0.7;
            color: var(--gray-500);
            transition: var(--transition-base);
        }

        .footer-links a:hover i {
            color: var(--primary-color);
            opacity: 1;
        }

        /* Contact Section */
        .footer-contact-section .contact-info {
            background: transparent;
        }

        .footer-contact-section .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding: 0.5rem 0;
            border-radius: var(--radius-lg);
            transition: var(--transition-base);
        }

        .footer-contact-section .contact-item:hover {
            background: rgba(78, 166, 116, 0.03);
        }

        .footer-contact-section .contact-item i {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .footer-contact-section .contact-item span,
        .footer-contact-section .contact-item .contact-label {
            font-size: 0.95rem;
            color: var(--gray-600);
            line-height: 1.4;
        }

        .footer-contact {
            background: rgba(78, 166, 116, 0.03);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            border: 1px solid rgba(78, 166, 116, 0.1);
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: var(--radius-lg);
            transition: var(--transition-base);
        }

        .contact-item:hover {
            background: rgba(78, 166, 116, 0.03);
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .contact-text {
            font-size: 0.95rem;
            color: var(--gray-600);
            line-height: 1.4;
        }

        /* Footer Bottom */
        .footer-bottom {
            border-top: 1px solid var(--gray-200);
            padding: 2rem 0;
            text-align: center;
            margin: 0;
        }

        .footer-copyright {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        /* Scroll to Top Button */
        .scroll-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition-base);
            box-shadow: 0 4px 15px rgba(78, 166, 116, 0.3);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
        }

        .scroll-to-top.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .scroll-to-top:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(78, 166, 116, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .footer-main {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .footer-container {
                padding: 0 1rem;
            }

            .footer-main {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-bottom {
                text-align: center;
            }

            .footer-social {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .footer-logo-icon {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .footer-brand-text h3 {
                font-size: 1.25rem;
            }

            .scroll-to-top {
                bottom: 1rem;
                right: 1rem;
                width: 45px;
                height: 45px;
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
                        <div class="footer-logo-icon">
                            <i class="fas fa-heart"></i>
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
                        <li><a href="index.php"><i class="fas fa-home"></i> Ana Sayfa</a></li>
                        <li><a href="index.php?page=about"><i class="fas fa-info-circle"></i> Hakkımızda</a></li>
                        <li><a href="index.php?page=projects"><i class="fas fa-project-diagram"></i> Projelerimiz</a></li>
                        <li><a href="index.php?page=volunteer"><i class="fas fa-hands-helping"></i> Gönüllü Ol</a></li>
                    </ul>
                </div>

                <!-- Daha Fazla -->
                <div class="footer-section">
                    <h4>Daha Fazla</h4>
                    <ul class="footer-links">
                        <li><a href="index.php?page=faq"><i class="fas fa-question-circle"></i> SSS</a></li>
                        <li><a href="index.php?page=contact"><i class="fas fa-envelope"></i> İletişim</a></li>
                        <li><a href="index.php?page=donate"><i class="fas fa-heart"></i> Bağış Yap</a></li>
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
        // Modern Footer Functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Update current year
            document.getElementById('currentYear').textContent = new Date().getFullYear();

            // Scroll to top functionality
            const scrollToTopBtn = document.getElementById('scrollToTop');
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
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

            // Enhanced social link interactions
            const socialLinks = document.querySelectorAll('.social-link');
            socialLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px) scale(1.05)';
                });
                
                link.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                });
            });

            // Footer links animation
            const footerLinks = document.querySelectorAll('.footer-links a');
            footerLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Click animation
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Enhanced accessibility
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
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
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

            // Add staggered animation delays
            footerSections.forEach((section, index) => {
                section.style.transitionDelay = `${index * 0.1}s`;
            });
        });

        // Utility notification function
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 2rem;
                right: 2rem;
                background: ${type === 'success' ? '#D3D92B' : '#ef4444'};
                color: white;
                padding: 1rem 2rem;
                border-radius: 0.75rem;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                z-index: 9999;
                font-weight: 500;
                transform: translateX(100%);
                transition: transform 0.3s ease;
            `;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
