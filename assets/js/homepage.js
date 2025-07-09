// Modern Homepage JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('a[href^="#"]');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
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

    // Navbar scroll effect - Yeni navbar sınıfını kullan
    const navbar = document.querySelector('.navbar-modern');
    let lastScrollTop = 0;
    
    // Eğer navbar bulunursa işlem yap (null kontrolü)
    if (navbar) {
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            lastScrollTop = scrollTop;
        });
    }

    // Animated counter for stats
    const animateCounter = (element, target, duration = 2000) => {
        let start = 0;
        const increment = target / (duration / 16); // 60 FPS
        
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                element.textContent = target.toLocaleString('tr-TR');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(start).toLocaleString('tr-TR');
            }
        }, 16);
    };

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && entry.target) {
                // Add animation classes
                if (entry.target.classList && entry.target.classList.contains('stat-number')) {
                    const target = parseInt(entry.target.getAttribute('data-target'));
                    if (target) {
                        animateCounter(entry.target, target);
                    }
                }
                
                // Animate cards
                if (entry.target.classList && 
                   (entry.target.classList.contains('feature-card') || 
                    entry.target.classList.contains('project-card') ||
                    entry.target.classList.contains('testimonial-card'))) {
                    entry.target.style.animation = 'fadeInUp 0.8s ease forwards';
                }
                
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.stat-number, .feature-card, .project-card, .testimonial-card').forEach(el => {
        if (el) {
            observer.observe(el);
        }
    });

    // Hero scroll indicator - Enhanced responsive functionality
    const scrollIndicator = document.querySelector('.hero-scroll-indicator');
    if (scrollIndicator) {
        // Responsive text adjustment
        function updateScrollIndicatorText() {
            const span = scrollIndicator.querySelector('span');
            if (span) {
                if (window.innerWidth <= 767) {
                    span.style.display = 'none'; // Hide text on mobile
                } else {
                    span.style.display = 'block';
                    if (window.innerWidth <= 991) {
                        span.textContent = 'Devam Et'; // Shorter text for tablets
                    } else {
                        span.textContent = 'Keşfetmeye devam et'; // Full text for desktop
                    }
                }
            }
        }
        
        // Update text on load and resize
        updateScrollIndicatorText();
        window.addEventListener('resize', updateScrollIndicatorText);
        
        // Smooth scroll functionality
        scrollIndicator.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Find the next section after hero
            const possibleTargets = [
                document.querySelector('#about-preview'),
                document.querySelector('.about-preview'),
                document.querySelector('.features-section'),
                document.querySelector('.projects-section'),
                document.querySelector('.stats-section')
            ].filter(Boolean); // Remove null values
            
            const nextSection = possibleTargets[0];
            
            if (nextSection) {
                // Responsive header height calculation
                const headerHeight = window.innerWidth <= 767 ? 60 : 80;
                const targetPosition = nextSection.offsetTop - headerHeight;
                
                // Enhanced smooth scroll with easing
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // Add visual feedback
                this.style.transform = 'translateX(-50%) scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'translateX(-50%) scale(1)';
                }, 150);
            }
        });
        
        // Enhanced accessibility and visual feedback
        scrollIndicator.style.cursor = 'pointer';
        scrollIndicator.setAttribute('role', 'button');
        scrollIndicator.setAttribute('tabindex', '0');
        scrollIndicator.setAttribute('aria-label', 'Bir sonraki bölüme git');
        
        // Enhanced keyboard support
        scrollIndicator.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        
        // Auto-hide scroll indicator on scroll
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset > 100;
            if (scrolled) {
                scrollIndicator.style.opacity = '0.5';
                scrollIndicator.style.pointerEvents = 'none';
            } else {
                scrollIndicator.style.opacity = '1';
                scrollIndicator.style.pointerEvents = 'auto';
            }
            
            // Clear timeout and reset visibility after scroll stops
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                if (!scrolled) {
                    scrollIndicator.style.opacity = '1';
                    scrollIndicator.style.pointerEvents = 'auto';
                }
            }, 150);
        });
    }

    // Button hover effects
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        if (btn) {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        }
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroSection = document.querySelector('.hero-modern');
        
        if (heroSection) {
            const rate = scrolled * -0.5;
            heroSection.style.transform = `translateY(${rate}px)`;
        }
    });

    // Enhanced loading animation for hero image
    const heroImage = document.querySelector('.hero-main-image');
    if (heroImage) {
        // Check if image is already loaded
        if (heroImage.complete && heroImage.naturalHeight !== 0) {
            // Image is already loaded
            heroImage.style.opacity = '1';
            heroImage.style.transform = 'scale(1)';
            heroImage.classList.add('hero-loaded');
        } else {
            // Add loading state
            heroImage.style.opacity = '0';
            heroImage.style.transform = 'scale(0.95)';
            heroImage.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            
            // Enhanced load handler
            heroImage.addEventListener('load', function() {
                this.style.opacity = '1';
                this.style.transform = 'scale(1)';
                
                // Add subtle entrance animation
                setTimeout(() => {
                    this.classList.add('hero-loaded');
                }, 100);
            });
            
            // Error handling
            heroImage.addEventListener('error', function() {
                this.style.opacity = '0.7';
                this.style.filter = 'grayscale(100%)';
                console.warn('Hero image failed to load');
            });
        }
    }

    // Loading animation for other images
    const images = document.querySelectorAll('img:not(.hero-main-image)');
    images.forEach(img => {
        if (img) { // Null kontrolü ekle
            img.addEventListener('load', function() {
                this.style.opacity = '1';
                this.style.transform = 'scale(1)';
            });
            
            // Set initial styles
            img.style.opacity = '0';
            img.style.transform = 'scale(0.95)';
            img.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        }
    });

    // Form enhancements (if any forms exist)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        if (form) { // Null kontrolü ekle
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"], input[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gönderiliyor...';
                    submitBtn.disabled = true;
                }
            });
        }
    });

    // Progress bar animations
    const progressBars = document.querySelectorAll('.progress-fill');
    const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && entry.target) { // Null kontrolü ekle
                const progress = entry.target;
                const width = progress.style.width;
                progress.style.width = '0%';
                progress.style.transition = 'width 2s ease-in-out';
                
                setTimeout(() => {
                    if (progress) { // setTimeout içinde null kontrolü ekle
                        progress.style.width = width;
                    }
                }, 100);
                
                progressObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    progressBars.forEach(bar => {
        if (bar) { // Null kontrolü ekle
            progressObserver.observe(bar);
        }
    });

    // Add loading class removal
    window.addEventListener('load', function() {
        if (document.body) {
            document.body.classList.add('loaded');
            
            // Start animations
            const heroContent = document.querySelector('.hero-content');
            if (heroContent) {
                heroContent.style.animation = 'fadeInUp 1s ease forwards';
            }
            
            const floatingCards = document.querySelectorAll('.floating-card');
            floatingCards.forEach((card, index) => {
                if (card) {
                    card.style.animationDelay = `${index * 0.2}s`;
                    card.style.animation = 'fadeInRight 0.8s ease forwards';
                }
            });
        }
    });
});

// Enhanced floating cards animation
function initializeFloatingCardsAnimation() {
    const floatingCards = document.querySelectorAll('.floating-card');
    
    floatingCards.forEach((card, index) => {
        // Staggered entrance animation
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px) scale(0.95)';
        card.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0) scale(1)';
        }, 800 + (index * 200));
        
        // Enhanced hover interactions
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.03)';
            this.style.boxShadow = '0 25px 50px rgba(0, 0, 0, 0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.1)';
        });
    });
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    initializeFloatingCardsAnimation();
});

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Performance optimization
const debouncedScroll = debounce(() => {
    // Scroll performance optimizations can be added here
}, 10);

window.addEventListener('scroll', debouncedScroll);
