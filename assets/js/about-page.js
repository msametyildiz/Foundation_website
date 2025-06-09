/**
 * About Page Enhanced Interactions
 * Professional animations and user experience enhancements
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeAboutPageAnimations();
    initializeCounterAnimations();
    initializeTestimonialEffects();
    initializeScrollAnimations();
});

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
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Easing function (ease-out cubic)
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const currentValue = Math.floor(startValue + (target - startValue) * easeOut);
        
        element.textContent = currentValue;
        
        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target;
            
            // Add completion effect
            element.style.animation = 'pulse 0.5s ease-in-out';
            setTimeout(() => {
                element.style.animation = '';
            }, 500);
        }
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
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
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
        animation: slideInUp 0.6s ease forwards;
    }
    
    .read-more-btn:hover {
        text-decoration: underline !important;
    }
`;
document.head.appendChild(style);
