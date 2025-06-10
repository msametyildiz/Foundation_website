/**
 * About Page Enhanced Interactions
 * Professional animations and user experience enhancements for redesigned Activity Areas
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeAboutPageAnimations();
    initializeActivityCardsModern();
    initializeCounterAnimations();
    initializeTestimonialEffects();
    initializeScrollAnimations();
});

/**
 * Initialize modern activity cards interactions
 */
function initializeActivityCardsModern() {
    const activityCards = document.querySelectorAll('.activity-card-modern');
    
    activityCards.forEach((card, index) => {
        // Staggered entrance animation
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 + (index * 100));
        
        // Enhanced hover effects
        card.addEventListener('mouseenter', function() {
            // Add subtle glow effect
            this.style.boxShadow = '0 20px 40px rgba(78, 166, 116, 0.15), 0 0 20px rgba(78, 166, 116, 0.1)';
            
            // Animate the icon
            const icon = this.querySelector('.icon-circle-modern');
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
            }
            
            // Animate the stats badge
            const stats = this.querySelector('.activity-stats');
            if (stats) {
                stats.style.transform = 'scale(1.05)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
            
            const icon = this.querySelector('.icon-circle-modern');
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }
            
            const stats = this.querySelector('.activity-stats');
            if (stats) {
                stats.style.transform = 'scale(1)';
            }
        });
        
        // Click effect with ripple
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.btn-link-modern')) {
                createRippleEffect(this, e);
            }
        });
    });
    
    // Initialize stats container animation
    const statsContainer = document.querySelector('.stats-container');
    if (statsContainer) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.8s ease-out';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });
        
        observer.observe(statsContainer);
    }
}

/**
 * Create ripple effect on card click
 */
function createRippleEffect(element, event) {
    const ripple = document.createElement('div');
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;
    
    ripple.style.cssText = `
        position: absolute;
        width: ${size}px;
        height: ${size}px;
        left: ${x}px;
        top: ${y}px;
        background: rgba(78, 166, 116, 0.3);
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s ease-out;
        pointer-events: none;
        z-index: 1000;
    `;
    
    element.style.position = 'relative';
    element.style.overflow = 'hidden';
    element.appendChild(ripple);
    
    setTimeout(() => {
        ripple.remove();
    }, 600);
}

/**
 * Initialize about page specific animations
 */
function initializeAboutPageAnimations() {
    // Hero section entrance animation
    const heroContent = document.querySelector('.hero-content');
    const heroImage = document.querySelector('.hero-image-wrapper');
    
    if (heroContent) {
        heroContent.style.opacity = '0';
        heroContent.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            heroContent.style.transition = 'all 1s cubic-bezier(0.4, 0, 0.2, 1)';
            heroContent.style.opacity = '1';
            heroContent.style.transform = 'translateY(0)';
        }, 300);
    }
    
    if (heroImage) {
        heroImage.style.opacity = '0';
        heroImage.style.transform = 'translateX(50px) scale(0.9)';
        
        setTimeout(() => {
            heroImage.style.transition = 'all 1.2s cubic-bezier(0.4, 0, 0.2, 1)';
            heroImage.style.opacity = '1';
            heroImage.style.transform = 'translateX(0) scale(1)';
        }, 600);
    }
    
    // Mission and Vision cards staggered animation
    const missionCard = document.querySelector('.mission-card');
    const visionCard = document.querySelector('.vision-card');
    
    [missionCard, visionCard].forEach((card, index) => {
        if (card) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(40px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 900 + (index * 200));
        }
    });
}

/**
 * Enhanced counter animations with easing
 */
function initializeCounterAnimations() {
    const observerOptions = {
        threshold: 0.3,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                entry.target.classList.add('animated');
                animateCounter(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe all stat numbers
    document.querySelectorAll('.stat-number[data-target]').forEach(el => {
        counterObserver.observe(el);
    });
}

/**
 * Animate counter with smooth easing
 */
function animateCounter(element) {
    const target = parseInt(element.getAttribute('data-target'));
    const duration = 2000; // 2 seconds
    const startTime = performance.now();
    const startValue = 0;
    
    function updateCounter(currentTime) {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2500;
        const startTime = performance.now();
        
        function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function for smooth animation
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(target * easeOut);
            
            element.textContent = current.toLocaleString();
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target.toLocaleString();
                // Add completion effect
                element.classList.add('stat-number-animated');
                setTimeout(() => {
                    element.classList.remove('stat-number-animated');
                }, 2000);
            }
        }
        
        requestAnimationFrame(updateCounter);
    }
    
    requestAnimationFrame(updateCounter);
}

/**
 * Testimonial cards interactive effects
 */
function initializeTestimonialEffects() {
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    
    testimonialCards.forEach(card => {
        // Enhanced hover effect
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-12px) scale(1.02)';
            
            // Highlight the rating stars
            const stars = this.querySelectorAll('.testimonial-rating i');
            stars.forEach((star, index) => {
                setTimeout(() => {
                    star.style.transform = 'scale(1.2)';
                    star.style.transition = 'transform 0.2s ease';
                }, index * 50);
            });
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            
            // Reset stars
            const stars = this.querySelectorAll('.testimonial-rating i');
            stars.forEach(star => {
                star.style.transform = 'scale(1)';
            });
        });
        
        // Add read more functionality for long testimonials
        const testimonialText = card.querySelector('.testimonial-text');
        if (testimonialText && testimonialText.textContent.length > 150) {
            const fullText = testimonialText.textContent;
            const shortText = fullText.substring(0, 150) + '...';
            
            testimonialText.textContent = shortText;
            
            const readMoreBtn = document.createElement('span');
            readMoreBtn.className = 'read-more-btn';
            readMoreBtn.textContent = ' Devamını oku';
            readMoreBtn.style.color = 'var(--primary-color)';
            readMoreBtn.style.cursor = 'pointer';
            readMoreBtn.style.fontWeight = '600';
            
            let isExpanded = false;
            
            readMoreBtn.addEventListener('click', function() {
                if (!isExpanded) {
                    testimonialText.textContent = fullText;
                    this.textContent = ' Daha az göster';
                    isExpanded = true;
                } else {
                    testimonialText.textContent = shortText;
                    this.textContent = ' Devamını oku';
                    isExpanded = false;
                }
            });
            
            testimonialText.appendChild(readMoreBtn);
        }
    });
}

/**
 * Scroll-triggered animations
 */
function initializeScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -20px 0px'
    };
    
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                
                // Special handling for different element types
                if (entry.target.classList.contains('stat-card-enhanced')) {
                    // Stagger stat cards animation
                    const cards = document.querySelectorAll('.stat-card-enhanced');
                    cards.forEach((card, index) => {
                        setTimeout(() => {
                            card.style.animation = 'slideInUp 0.6s ease forwards';
                        }, index * 100);
                    });
                }
                
                scrollObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe elements for scroll animations
    const elementsToAnimate = document.querySelectorAll(`
        .testimonial-card,
        .stat-card-enhanced,
        .mission-card,
        .vision-card,
        .cta-content
    `);
    
    elementsToAnimate.forEach(el => {
        scrollObserver.observe(el);
    });
}

/**
 * Floating elements animation
 */
function initializeFloatingElements() {
    const floatingElements = document.querySelectorAll('.floating-element');
    
    floatingElements.forEach((element, index) => {
        const randomDelay = Math.random() * 2;
        const randomDuration = 4 + Math.random() * 2;
        
        element.style.animationDelay = `${randomDelay}s`;
        element.style.animationDuration = `${randomDuration}s`;
        
        // Add hover effect
        element.addEventListener('mouseenter', function() {
            this.style.animationPlayState = 'paused';
            this.style.transform = 'scale(1.2) translateY(-10px)';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.animationPlayState = 'running';
            this.style.transform = '';
        });
    });
}

/**
 * Smooth scroll enhancements
 */
function enhancedSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const navbarHeight = document.querySelector('.navbar')?.offsetHeight || 80;
                const targetPosition = target.offsetTop - navbarHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Parallax effect for background elements
 */
function initializeParallaxEffects() {
    let ticking = false;
    
    function updateParallax() {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;
        
        // Apply parallax to hero background
        const heroBackground = document.querySelector('.about-hero-enhanced::before');
        if (heroBackground) {
            heroBackground.style.transform = `translateY(${rate}px)`;
        }
        
        // Apply parallax to floating elements
        const floatingElements = document.querySelectorAll('.floating-element');
        floatingElements.forEach((element, index) => {
            const speed = 0.2 + (index * 0.1);
            element.style.transform = `translateY(${scrolled * speed}px)`;
        });
        
        ticking = false;
    }
    
    function requestParallaxUpdate() {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', requestParallaxUpdate);
}

// Initialize additional effects
document.addEventListener('DOMContentLoaded', function() {
    initializeFloatingElements();
    enhancedSmoothScroll();
    initializeParallaxEffects();
});

// Add CSS animations through JavaScript
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        from {
            transform: scale(0);
            opacity: 1;
        }
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
    
    .animate-in {
        animation: fadeInUp 0.8s ease-out;
    }
    
    .stat-number-animated {
        animation: pulse 2s ease-in-out infinite;
    }
    
    /* Improved transitions for better performance */
    .activity-card-modern {
        will-change: transform, box-shadow;
    }
    
    .icon-circle-modern {
        will-change: transform;
    }
    
    .stat-number {
        will-change: transform;
    }
    
    .section-badge {
        animation: fadeInDown 0.6s ease-out;
    }
    
    /* Loading states */
    .page-loaded .activity-card-modern {
        animation: slideInUp 0.8s ease-out forwards;
    }
`;
document.head.appendChild(style);

// Enhanced counter animation with better timing
function enhancedCounterAnimation(element) {
    const target = parseInt(element.getAttribute('data-counter'));
    const duration = 2500;
    const startTime = performance.now();
    
    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Easing function for smooth animation
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const current = Math.floor(target * easeOut);
        
        element.textContent = current.toLocaleString();
        
        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target.toLocaleString();
            // Add completion effect
            element.classList.add('stat-number-animated');
            setTimeout(() => {
                element.classList.remove('stat-number-animated');
            }, 2000);
        }
    }
    
    requestAnimationFrame(updateCounter);
}

// Accessibility improvements for activity cards
function enhanceAccessibility() {
    const activityCards = document.querySelectorAll('.activity-card-modern');
    
    activityCards.forEach(card => {
        // Add keyboard navigation
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
        card.setAttribute('aria-label', 'Faaliyet alanı kartı - detayları görüntülemek için tıklayın');
        
        // Keyboard event handling
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        
        // Focus handling
        card.addEventListener('focus', function() {
            this.style.outline = '2px solid var(--primary-color)';
            this.style.outlineOffset = '2px';
        });
        
        card.addEventListener('blur', function() {
            this.style.outline = 'none';
        });
    });
    
    // Enhanced focus management for buttons
    const actionButtons = document.querySelectorAll('.btn-link-modern');
    actionButtons.forEach(button => {
        button.addEventListener('focus', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        button.addEventListener('blur', function() {
            this.style.transform = 'translateX(0)';
        });
    });
}

// Performance optimization
window.addEventListener('load', function() {
    document.body.classList.add('page-loaded');
    enhanceAccessibility();
    
    // Preload any images in activity cards
    const cardImages = document.querySelectorAll('.activity-card-modern img');
    cardImages.forEach(img => {
        if (img.dataset.src) {
            img.src = img.dataset.src;
        }
    });
});

// Debounce utility for performance optimization
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
