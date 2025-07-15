// Ana JavaScript dosyası
document.addEventListener('DOMContentLoaded', function() {
    
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

    // AJAX Form Submissions
    const ajaxForms = document.querySelectorAll('.ajax-form');
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            handleAjaxForm(this);
        });
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const preview = document.getElementById(this.dataset.preview);
                if (preview && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    });

    // Donation amount buttons
    const donationButtons = document.querySelectorAll('.donation-amount-btn');
    const customAmountInput = document.getElementById('custom-amount');
    
    donationButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            donationButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            // Set the amount
            const amount = this.dataset.amount;
            if (customAmountInput) {
                customAmountInput.value = amount;
            }
        });
    });

    // Custom amount input
    if (customAmountInput) {
        customAmountInput.addEventListener('input', function() {
            donationButtons.forEach(btn => btn.classList.remove('active'));
        });
    }

    // Contact form submission
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Form validation
            if (!contactForm.checkValidity()) {
                e.stopPropagation();
                contactForm.classList.add('was-validated');
                showAlert('danger', 'Lütfen tüm zorunlu alanları doğru şekilde doldurun.');
                return;
            }
            
            // Show loading state
            submitBtn.innerHTML = '<span class="loading"></span> Gönderiliyor...';
            submitBtn.disabled = true;
            
            fetch('ajax/forms.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Extract name for personalized message
                    const contactName = formData.get('name') || 'Değerli ziyaretçimiz';
                    const personalizedMessage = `Sayın ${contactName}, mesajınız başarıyla iletildi! Kısa süre içinde tarafınıza dönüş yapılacaktır. Bizimle iletişime geçtiğiniz için teşekkür ederiz.`;
                    showAlert('success', personalizedMessage);
                    contactForm.reset();
                    contactForm.classList.remove('was-validated');
                } else {
                    showAlert('danger', data.message || 'Bir hata oluştu!');
                }
            })
            .catch(error => {
                showAlert('danger', 'Bir hata oluştu!');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Volunteer form submission
    const volunteerForm = document.getElementById('volunteerForm');
    if (volunteerForm) {
        volunteerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Custom validation for volunteer form
            const messageField = document.getElementById('message');
            if (messageField && messageField.value.length < 50) {
                messageField.setCustomValidity('Lütfen motivasyonunuzu en az 50 karakter olacak şekilde detaylı bir şekilde paylaşın.');
            } else if (messageField) {
                messageField.setCustomValidity('');
            }
            
            if (!volunteerForm.checkValidity()) {
                e.stopPropagation();
                volunteerForm.classList.add('was-validated');
                showAlert('danger', 'Lütfen tüm zorunlu alanları doğru şekilde doldurun.');
                return;
            }
            
            submitBtn.innerHTML = '<span class="loading"></span> Gönderiliyor...';
            submitBtn.disabled = true;
            
            fetch('ajax/forms.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Extract name for personalized message
                    const volunteerName = formData.get('name') || 'Değerli gönüllümüz';
                    const personalizedMessage = `Sayın ${volunteerName}, gönüllü başvurunuz başarıyla alındı! Başvurunuz değerlendirilecek ve en kısa sürede size dönüş yapacağız. Bu anlamlı yolculuğa katılmak istediğiniz için teşekkür ederiz.`;
                    showAlert('success', personalizedMessage);
                    volunteerForm.reset();
                    volunteerForm.classList.remove('was-validated');
                    
                    // Reset character counter
                    const charCount = document.getElementById('charCount');
                    if (charCount) {
                        charCount.textContent = '0/50';
                        charCount.className = 'badge bg-secondary';
                    }
                } else {
                    showAlert('danger', data.message || 'Bir hata oluştu!');
                }
            })
            .catch(error => {
                showAlert('danger', 'Bir hata oluştu!');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Donation form submission
    const donationForm = document.getElementById('donation-form');
    if (donationForm) {
        donationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Prevent multiple submissions
            if (this.dataset.submitting === 'true') {
                return;
            }
            
            const formData = new FormData(this);
            formData.append('action', 'donation');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Mark form as submitting
            this.dataset.submitting = 'true';
            submitBtn.innerHTML = '<span class="loading"></span> Yükleniyor...';
            submitBtn.disabled = true;
            
            fetch('ajax/forms.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Form verilerinden ad soyad bilgisini al
                    const donorName = document.getElementById('donor_name')?.value || 'Değerli Bağışçımız';
                    const personalizedMessage = `Sayın ${donorName}, bağışınız başarıyla kaydedilmiştir. İhtiyaç sahiplerine yapmış olduğunuz bu değerli bağış için içtenlikle teşekkür ederiz. Dekont bilgileriniz güvenle alınmış olup, değerlendirme sürecinden sonra tarafınızla iletişime geçilecektir.`;
                    
                    showAlert('success', personalizedMessage);
                    donationForm.reset();
                    // Bootstrap validasyon class'larını temizle
                    donationForm.classList.remove('was-validated');
                    donationForm.querySelectorAll('.is-valid, .is-invalid').forEach(function(el) {
                        el.classList.remove('is-valid', 'is-invalid');
                    });
                } else {
                    showAlert('danger', data.message || 'Bir hata oluştu!');
                }
            })
            .catch(error => {
                showAlert('danger', 'Bir hata oluştu!');
            })
            .finally(() => {
                // Reset form submission state
                this.dataset.submitting = 'false';
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Show alert function - Popup Style
    function showAlert(type, message) {
        // Overlay oluştur
        const overlay = document.createElement('div');
        overlay.className = 'alert-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        
        // Alert popup oluştur
        const alertPopup = document.createElement('div');
        alertPopup.className = `alert-popup alert-popup-${type}`;
        
        // Type'a göre icon ve renk belirleme
        const alertConfig = {
            'success': { icon: 'fas fa-check-circle', color: '#4ea674', bgColor: '#f0fdf4' },
            'danger': { icon: 'fas fa-exclamation-triangle', color: '#dc3545', bgColor: '#fef2f2' },
            'warning': { icon: 'fas fa-exclamation-circle', color: '#f59e0b', bgColor: '#fffbeb' },
            'info': { icon: 'fas fa-info-circle', color: '#3b82f6', bgColor: '#eff6ff' }
        };
        
        const config = alertConfig[type] || alertConfig.info;
        
        alertPopup.style.cssText = `
            background: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            transform: scale(0.8) translateY(-20px);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 2px solid ${config.color};
            position: relative;
        `;
        
        alertPopup.innerHTML = `
            <div style="
                width: 60px;
                height: 60px;
                background: ${config.bgColor};
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem auto;
                border: 3px solid ${config.color};
            ">
                <i class="${config.icon}" style="font-size: 24px; color: ${config.color};"></i>
            </div>
            <div style="
                font-size: 16px;
                line-height: 1.6;
                color: #374151;
                margin-bottom: 1.5rem;
                max-height: 200px;
                overflow-y: auto;
            ">${message}</div>
            <button type="button" class="alert-close-btn" style="
                background: ${config.color};
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                font-size: 14px;
            ">
                Tamam
            </button>
        `;
        
        overlay.appendChild(alertPopup);
        document.body.appendChild(overlay);
        
        // Animasyon başlat
        requestAnimationFrame(() => {
            overlay.style.opacity = '1';
            alertPopup.style.transform = 'scale(1) translateY(0)';
        });
        
        // Kapatma fonksiyonu
        const closeAlert = () => {
            overlay.style.opacity = '0';
            alertPopup.style.transform = 'scale(0.8) translateY(-20px)';
            setTimeout(() => {
                if (overlay.parentNode) {
                    overlay.remove();
                }
            }, 300);
        };
        
        // Event listeners
        const closeBtn = alertPopup.querySelector('.alert-close-btn');
        closeBtn.addEventListener('click', closeAlert);
        
        // Hover effect for button
        closeBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = `0 4px 15px ${config.color}40`;
        });
        
        closeBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
        
        // Overlay'e tıklayınca kapat
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                closeAlert();
            }
        });
        
        // ESC tuşu ile kapat
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                closeAlert();
                document.removeEventListener('keydown', handleEscape);
            }
        };
        document.addEventListener('keydown', handleEscape);
        
        // 7 saniye sonra otomatik kapat
        setTimeout(closeAlert, 7000);
    }

    // Create alert container if it doesn't exist (eski fonksiyon - artık kullanılmıyor)
    function createAlertContainer() {
        const container = document.createElement('div');
        container.id = 'alert-container';
        container.className = 'position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }

    // Navbar scroll effect - DISABLED: Using modern navbar
    // let lastScrollTop = 0;
    // const navbar = document.querySelector('.navbar');
    
    // window.addEventListener('scroll', function() {
    //     let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
    //     if (scrollTop > lastScrollTop && scrollTop > 100) {
    //         // Scrolling down
    //         navbar.classList.add('navbar-hidden');
    //     } else {
    //         // Scrolling up
    //         navbar.classList.remove('navbar-hidden');
    //     }
        
    //     // Add shadow when scrolled
    //     if (scrollTop > 0) {
    //         navbar.classList.add('navbar-scrolled');
    //     } else {
    //         navbar.classList.remove('navbar-scrolled');
    //     }
        
    //     lastScrollTop = scrollTop;
    // });

    // Animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe all cards and sections
    document.querySelectorAll('.card, .hero-section, .stats-section').forEach(el => {
        observer.observe(el);
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Modern Homepage Features
    initializeHomepage();
    
    // Stats Counter Animation
    initializeStatsCounter();
    
    // Smooth Navbar on Scroll - DISABLED: Using modern navbar
    // initializeNavbarScroll();
    
    // Floating Cards Animation
    initializeFloatingCards();
    
    // Enhanced scrolling effects and parallax
    initializeParallaxEffects();
    
    // Enhanced smooth scrolling
    enhancedSmoothScroll();
    
    // Progress bars animation
    initializeProgressBars();
    
    // Hero scroll indicator
    const scrollIndicator = document.querySelector('.hero-scroll-indicator');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', function() {
            // Look for the first section after hero
            const nextSection = document.querySelector('#about-preview, .about-preview, .features-section, .projects-section');
            if (nextSection) {
                const offset = 80; // Account for fixed navbar
                const targetPosition = nextSection.offsetTop - offset;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    }
    
    // Enhanced form handling with better UX
    enhanceFormUX();
    
    // Advanced animations with Intersection Observer
    initializeAdvancedAnimations();
    
    // Optimized scroll listener
    window.addEventListener('scroll', optimizedScrollHandler, { passive: true });
    
    // Page transition effect
    document.body.classList.add('page-transition');
    setTimeout(() => {
        document.body.classList.add('loaded');
    }, 100);
});

// Homepage initialization
function initializeHomepage() {
    // Add fade-in animations to sections
    const sections = document.querySelectorAll('.hero-modern, .features-modern, .about-preview, .projects-modern, .stats-modern, .testimonials-modern, .cta-modern');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fadeInUp');
            }
        });
    }, observerOptions);
    
    sections.forEach(section => {
        observer.observe(section);
    });
}

// Animated stats counter
function initializeStatsCounter() {
    const statNumbers = document.querySelectorAll('.stat-number[data-target]');
    
    const animateCounter = (element) => {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const step = target / (duration / 16); // 60fps
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString('tr-TR');
        }, 16);
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    });
    
    statNumbers.forEach(stat => observer.observe(stat));
}

// Navbar scroll effects - DISABLED: Using modern navbar
// function initializeNavbarScroll() {
//     const navbar = document.querySelector('.navbar');
//     if (!navbar) return;
    
//     let lastScrollTop = 0;
    
//     window.addEventListener('scroll', () => {
//         const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
//         // Add/remove scrolled class
//         if (scrollTop > 50) {
//             navbar.classList.add('scrolled');
//         } else {
//             navbar.classList.remove('scrolled');
//         }
        
//         // Hide/show navbar on scroll
//         if (scrollTop > lastScrollTop && scrollTop > 100) {
//             navbar.style.transform = 'translateY(-100%)';
//         } else {
//             navbar.style.transform = 'translateY(0)';
//         }
        
//         lastScrollTop = scrollTop;
//     });
// }

// Floating cards animation
function initializeFloatingCards() {
    const cards = document.querySelectorAll('.floating-card');
    
    cards.forEach((card, index) => {
        // Add staggered animation delay
        card.style.animationDelay = `${index * 0.5}s`;
        
        // Add hover effects
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-15px)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
}

// Progress bar animation
function animateProgressBars() {
    const progressBars = document.querySelectorAll('.progress-fill');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const width = entry.target.style.width || '0%';
                entry.target.style.width = '0%';
                setTimeout(() => {
                    entry.target.style.width = width;
                }, 100);
                observer.unobserve(entry.target);
            }
        });
    });
    
    progressBars.forEach(bar => observer.observe(bar));
}

// Initialize progress bars when DOM is loaded
document.addEventListener('DOMContentLoaded', animateProgressBars);

// Utility functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
    }).format(amount);
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success toast message
        const toast = document.createElement('div');
        toast.className = 'toast-message';
        toast.textContent = 'Kopyalandı!';
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4ea674;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '1';
        }, 100);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 2000);
    }, function() {
        // Show error toast message
        const toast = document.createElement('div');
        toast.className = 'toast-message';
        toast.textContent = 'Kopyalama başarısız!';
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '1';
        }, 100);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 2000);
    });
}

// Add CSS for scroll effects
const style = document.createElement('style');
style.textContent = `
    .navbar-hidden {
        transform: translateY(-100%);
        transition: transform 0.3s ease-in-out;
    }
    
    .navbar-scrolled {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s ease-in-out;
    }
    
    .animate-in {
        animation: fadeInUp 0.6s ease-out;
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
`;
document.head.appendChild(style);

// AJAX Form Handling Functions
function handleAjaxForm(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    const formData = new FormData(form);
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Gönderiliyor...';
    
    // Clear previous alerts
    const alerts = form.querySelectorAll('.alert');
    alerts.forEach(alert => alert.remove());
    
    fetch('/ajax/forms.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success', form);
            form.reset();
            
            // Special handling for donation form
            if (data.reference) {
                showDonationSuccess(data.reference);
            }
        } else {
            showAlert(data.message, 'danger', form);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Bir hata oluştu. Lütfen tekrar deneyin.', 'danger', form);
    })
    .finally(() => {
        // Restore button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

function showAlert(message, type, container = null) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-${getAlertIcon(type)} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    if (container) {
        container.insertAdjacentHTML('afterbegin', alertHtml);
    } else {
        document.querySelector('.container, .container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
    }
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }
    }, 5000);
}

function getAlertIcon(type) {
    const icons = {
        'success': 'check-circle',
        'danger': 'exclamation-triangle',
        'warning': 'exclamation-circle',
        'info': 'info-circle'
    };
    return icons[type] || 'info-circle';
}

function showDonationSuccess(reference) {
    const modal = new bootstrap.Modal(document.getElementById('donationSuccessModal'));
    document.getElementById('referenceNumber').textContent = reference;
    modal.show();
}

// Notification system
function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${getAlertIcon(type)} me-2"></i>
        ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Auto-hide
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, duration);
}

// Add notification styles
const notificationStyle = document.createElement('style');
notificationStyle.textContent = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 400px;
        display: flex;
        align-items: center;
        border-left: 4px solid;
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification-success { border-left-color: #28a745; }
    .notification-danger { border-left-color: #dc3545; }
    .notification-warning { border-left-color: #ffc107; }
    .notification-info { border-left-color: #17a2b8; }
    
    .notification .btn-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        margin-left: auto;
        opacity: 0.5;
    }
    
    .notification .btn-close:hover {
        opacity: 1;
    }
`;
document.head.appendChild(notificationStyle);

// Enhanced scrolling effects and parallax
function initializeParallaxEffects() {
    const parallaxElements = document.querySelectorAll('.hero-modern, .stats-modern');
    
    if (window.innerWidth > 768) { // Only on desktop
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            
            parallaxElements.forEach(element => {
                element.style.transform = `translateY(${rate}px)`;
            });
        });
    }
}

// Enhanced smooth scrolling
function enhancedSmoothScroll() {
    // Update existing smooth scroll with better easing
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const offset = 80; // Account for fixed navbar
                const targetPosition = target.offsetTop - offset;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Hero scroll indicator
function initializeScrollIndicator() {
    const indicator = document.querySelector('.hero-scroll-indicator');
    if (indicator) {
        indicator.addEventListener('click', function(e) {
            e.preventDefault();
            // Try multiple possible next sections in order of preference
            const possibleSections = [
                '#about-preview',
                '.about-preview',
                '.features-section', 
                '.projects-section',
                '.stats-section'
            ];
            
            let targetSection = null;
            for (const selector of possibleSections) {
                targetSection = document.querySelector(selector);
                if (targetSection) break;
            }
            
            if (targetSection) {
                const offset = 80; // Account for navbar height
                const targetPosition = targetSection.offsetTop - offset;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            } else {
                // Fallback: scroll down by viewport height
                window.scrollTo({
                    top: window.innerHeight,
                    behavior: 'smooth'
                });
            }
        });
        
        // Add cursor pointer style
        indicator.style.cursor = 'pointer';
    }
}

// Enhanced form handling with better UX
function enhanceFormUX() {
    // Add loading states to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gönderiliyor...';
                submitBtn.disabled = true;
            }
        });
    });
}

// Advanced animations with Intersection Observer
function initializeAdvancedAnimations() {
    const animatedElements = document.querySelectorAll('.feature-card, .project-card, .testimonial-card');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100); // Staggered animation
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        observer.observe(element);
    });
}

// Performance optimized scroll handler
let scrollTimeout;
function optimizedScrollHandler() {
    if (scrollTimeout) {
        clearTimeout(scrollTimeout);
    }
    
    scrollTimeout = setTimeout(() => {
        // Scroll-dependent operations here
        updateActiveNavLink();
        checkElementsInView();
    }, 16); // ~60fps
}

function updateActiveNavLink() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    let currentSection = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop - 100;
        const sectionHeight = section.offsetHeight;
        if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
            currentSection = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${currentSection}`) {
            link.classList.add('active');
        }
    });
}

function checkElementsInView() {
    // Additional view-based animations can be added here
}

// Duplicate functions removed - these are already defined earlier in the file

// Progress bar animations
function initializeProgressBars() {
    const progressBars = document.querySelectorAll('.progress-bar[data-progress]');
    
    const animateProgress = (bar) => {
        const targetWidth = bar.getAttribute('data-progress');
        let currentWidth = 0;
        const increment = targetWidth / 100;
        
        const timer = setInterval(() => {
            currentWidth += increment;
            if (currentWidth >= targetWidth) {
                currentWidth = targetWidth;
                clearInterval(timer);
            }
            bar.style.width = currentWidth + '%';
        }, 20);
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateProgress(entry.target);
                observer.unobserve(entry.target);
            }
        });
    });
    
    progressBars.forEach(bar => observer.observe(bar));
}
