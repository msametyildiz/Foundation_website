/**
 * Necat Derneği - cPanel Uyumluluk Düzeltmeleri
 * 
 * Bu dosya, cPanel ortamında yaşanan JavaScript sorunlarını gidermek için
 * uyumluluk düzeltmeleri içerir.
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('cPanel uyumluluk düzeltmeleri yükleniyor...');
    
    // 1. Clipboard API için alternatif çözüm
    fixClipboardAPI();
    
    // 2. Accordion işlevselliği için düzeltme
    fixAccordions();
    
    // 3. Form gönderim sorunları için düzeltme
    fixFormSubmissions();
    
    // 4. Footer görünüm sorunları için düzeltme
    fixFooterDisplay();
    
    console.log('cPanel uyumluluk düzeltmeleri tamamlandı.');
});

/**
 * Clipboard API için alternatif çözüm
 * Modern navigator.clipboard API'si sadece HTTPS üzerinde çalışır
 */
function fixClipboardAPI() {
    // Tüm kopyalama düğmelerini bul
    const copyButtons = document.querySelectorAll('[onclick*="copyToClipboard"]');
    
    if (copyButtons.length > 0) {
        console.log('Kopyalama düğmeleri bulundu:', copyButtons.length);
        
        // Global copyToClipboard fonksiyonunu yeniden tanımla
        window.copyToClipboard = function(text) {
            // Modern API'yi dene
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function() {
                    showCopyToast('Kopyalandı!');
                }).catch(function(err) {
                    console.error('Kopyalama hatası (modern API):', err);
                    fallbackCopyToClipboard(text);
                });
            } else {
                // Alternatif yöntem
                fallbackCopyToClipboard(text);
            }
        };
        
        // Alternatif kopyalama yöntemi
        function fallbackCopyToClipboard(text) {
            try {
                // Geçici textarea oluştur
                const textArea = document.createElement('textarea');
                textArea.value = text;
                
                // Görünmez yap
                textArea.style.position = 'fixed';
                textArea.style.top = '0';
                textArea.style.left = '0';
                textArea.style.width = '2em';
                textArea.style.height = '2em';
                textArea.style.padding = '0';
                textArea.style.border = 'none';
                textArea.style.outline = 'none';
                textArea.style.boxShadow = 'none';
                textArea.style.background = 'transparent';
                
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                
                // Kopyalama işlemi
                const successful = document.execCommand('copy');
                
                if (successful) {
                    showCopyToast('Kopyalandı!');
                } else {
                    console.error('Kopyalama başarısız');
                    showCopyToast('Kopyalama başarısız!');
                }
                
                document.body.removeChild(textArea);
            } catch (err) {
                console.error('Kopyalama hatası (alternatif):', err);
                showCopyToast('Kopyalama başarısız!');
            }
        }
        
        // Kopyalama bildirimi göster
        function showCopyToast(message) {
            // Mevcut toast'u kontrol et
            let toast = document.querySelector('.toast-message');
            
            if (toast) {
                // Varolan toast'u güncelle
                toast.textContent = message;
                toast.style.opacity = '1';
                
                // Zamanlayıcıyı sıfırla
                clearTimeout(toast.dataset.timeout);
                toast.dataset.timeout = setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => {
                        if (toast.parentNode) {
                            document.body.removeChild(toast);
                        }
                    }, 300);
                }, 2000);
            } else {
                // Yeni toast oluştur
                toast = document.createElement('div');
                toast.className = 'toast-message';
                toast.textContent = message;
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
                
                toast.dataset.timeout = setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => {
                        if (toast.parentNode) {
                            document.body.removeChild(toast);
                        }
                    }, 300);
                }, 2000);
            }
        }
    }
}

/**
 * Accordion işlevselliği için düzeltme
 * Bootstrap JS yüklenmediğinde veya doğru çalışmadığında
 */
function fixAccordions() {
    // Bootstrap JS yüklü mü kontrol et
    const isBootstrapLoaded = typeof bootstrap !== 'undefined' && bootstrap.Collapse;
    
    if (!isBootstrapLoaded) {
        console.log('Bootstrap JS bulunamadı, accordion düzeltmesi uygulanıyor...');
        
        // Tüm accordion butonlarını bul
        const accordionButtons = document.querySelectorAll('.accordion-button');
        
        if (accordionButtons.length > 0) {
            accordionButtons.forEach(button => {
                // Mevcut event listener'ları temizle
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);
                
                // Yeni event listener ekle
                newButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Hedef collapse elementini bul
                    const targetId = this.getAttribute('data-bs-target') || 
                                     this.getAttribute('href');
                    
                    if (targetId) {
                        const targetElement = document.querySelector(targetId);
                        
                        if (targetElement) {
                            // Toggle collapsed class
                            this.classList.toggle('collapsed');
                            
                            // Toggle show class
                            targetElement.classList.toggle('show');
                            
                            // Aria attributes güncelle
                            const isExpanded = targetElement.classList.contains('show');
                            this.setAttribute('aria-expanded', isExpanded);
                        }
                    }
                });
            });
        }
    } else {
        console.log('Bootstrap JS yüklü, accordion düzeltmesine gerek yok.');
    }
}

/**
 * Form gönderim sorunları için düzeltme
 */
function fixFormSubmissions() {
    // Contact formu
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        console.log('Contact formu bulundu, düzeltme uygulanıyor...');
        
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Form doğrulama
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }
            
            // Form verilerini topla
            const formData = new FormData(this);
            formData.append('action', 'contact');
            
            // Gönder düğmesini devre dışı bırak
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Gönderiliyor...';
            submitBtn.disabled = true;
            
            // AJAX isteği gönder
            fetch('ajax/forms.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Başarılı mesaj göster
                    showAlert('success', 'Mesajınız başarıyla gönderildi! En kısa sürede size dönüş yapacağız.');
                    this.reset();
                    this.classList.remove('was-validated');
                } else {
                    // Hata mesajı göster
                    showAlert('danger', data.message || 'Mesaj gönderilirken bir hata oluştu.');
                }
            })
            .catch(error => {
                console.error('Form gönderim hatası:', error);
                showAlert('danger', 'Bir hata oluştu! Lütfen daha sonra tekrar deneyin.');
            })
            .finally(() => {
                // Gönder düğmesini tekrar etkinleştir
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Gönüllü formu
    const volunteerForm = document.getElementById('volunteerForm');
    if (volunteerForm) {
        console.log('Gönüllü formu bulundu, düzeltme uygulanıyor...');
        
        volunteerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Form doğrulama
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }
            
            // Form verilerini topla
            const formData = new FormData(this);
            formData.append('action', 'volunteer');
            
            // Gönder düğmesini devre dışı bırak
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Gönderiliyor...';
            submitBtn.disabled = true;
            
            // AJAX isteği gönder
            fetch('ajax/forms.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Başarılı mesaj göster
                    showAlert('success', 'Başvurunuz başarıyla alındı! Başvurunuz incelendikten sonra sizinle iletişime geçeceğiz.');
                    this.reset();
                    this.classList.remove('was-validated');
                } else {
                    // Hata mesajı göster
                    showAlert('danger', data.message || 'Başvuru gönderilirken bir hata oluştu.');
                }
            })
            .catch(error => {
                console.error('Form gönderim hatası:', error);
                showAlert('danger', 'Bir hata oluştu! Lütfen daha sonra tekrar deneyin.');
            })
            .finally(() => {
                // Gönder düğmesini tekrar etkinleştir
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Bağış formu
    const donationForm = document.getElementById('donationForm');
    if (donationForm) {
        console.log('Bağış formu bulundu, düzeltme uygulanıyor...');
        
        donationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Form doğrulama
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }
            
            // Form verilerini topla
            const formData = new FormData(this);
            formData.append('action', 'donation');
            
            // Gönder düğmesini devre dışı bırak
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Gönderiliyor...';
            submitBtn.disabled = true;
            
            // AJAX isteği gönder
            fetch('ajax/forms.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Başarılı mesaj göster
                    showAlert('success', 'Bağış bilgileriniz başarıyla kaydedildi! Teşekkür ederiz.');
                    this.reset();
                    this.classList.remove('was-validated');
                } else {
                    // Hata mesajı göster
                    showAlert('danger', data.message || 'Bağış bilgileri gönderilirken bir hata oluştu.');
                }
            })
            .catch(error => {
                console.error('Form gönderim hatası:', error);
                showAlert('danger', 'Bir hata oluştu! Lütfen daha sonra tekrar deneyin.');
            })
            .finally(() => {
                // Gönder düğmesini tekrar etkinleştir
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Alert mesajı gösterme yardımcı fonksiyonu
    function showAlert(type, message) {
        // Mevcut alert'i kontrol et
        const existingAlert = document.querySelector('.alert-container');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Yeni alert oluştur
        const alertContainer = document.createElement('div');
        alertContainer.className = 'alert-container';
        alertContainer.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: 80%;
            max-width: 500px;
        `;
        
        const alertElement = document.createElement('div');
        alertElement.className = `alert alert-${type} alert-dismissible fade show`;
        alertElement.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        alertContainer.appendChild(alertElement);
        document.body.appendChild(alertContainer);
        
        // 5 saniye sonra otomatik kapat
        setTimeout(() => {
            alertElement.classList.remove('show');
            setTimeout(() => {
                if (alertContainer.parentNode) {
                    document.body.removeChild(alertContainer);
                }
            }, 300);
        }, 5000);
        
        // Kapatma düğmesi işlevselliği
        const closeButton = alertElement.querySelector('.btn-close');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                alertElement.classList.remove('show');
                setTimeout(() => {
                    if (alertContainer.parentNode) {
                        document.body.removeChild(alertContainer);
                    }
                }, 300);
            });
        }
    }
}

/**
 * Footer görünüm sorunları için düzeltme
 */
function fixFooterDisplay() {
    // Contact sayfasındaysak bu düzeltmeyi atla
    if (window.location.href.includes('page=contact')) {
        console.log('Contact sayfasında footer görüntü düzeltmesi atlandı.');
        return;
    }

    const footer = document.querySelector('.footer-modern');
    
    if (footer) {
        console.log('Footer bulundu, görünüm düzeltmesi uygulanıyor...');
        
        // cPanel'de CSS yüklenme sorunlarını çözmek için inline stil uygula
        footer.style.display = 'flex';
        footer.style.visibility = 'visible';
        footer.style.opacity = '1';
        footer.style.minHeight = '400px';
        footer.style.flexDirection = 'column';
        footer.style.position = 'relative';
        footer.style.marginTop = 'auto';
        footer.style.borderTop = '1px solid rgba(78, 166, 116, 0.1)';
        
        // Footer içindeki ana bölümlerin görünürlüğünü sağla
        const footerContainer = footer.querySelector('.footer-container');
        if (footerContainer) {
            footerContainer.style.position = 'relative';
            footerContainer.style.zIndex = '2';
            footerContainer.style.maxWidth = '1400px';
            footerContainer.style.margin = '0 auto';
            footerContainer.style.padding = '4rem 2rem 2rem';
            footerContainer.style.flex = '1';
            footerContainer.style.display = 'flex';
            footerContainer.style.flexDirection = 'column';
            
            // Footer Main Grid düzeltmesi
            const footerMain = footerContainer.querySelector('.footer-main');
            if (footerMain) {
                footerMain.style.display = 'grid';
                footerMain.style.gap = '4rem';
                footerMain.style.marginBottom = '3rem';
                footerMain.style.flex = '1';
                
                // Responsive görünüm düzeltmesi
                if (window.innerWidth > 1200) {
                    footerMain.style.gridTemplateColumns = '2.2fr 1fr 1fr 1.5fr';
                } else if (window.innerWidth > 992) {
                    footerMain.style.gridTemplateColumns = '2fr 1fr 1fr';
                } else if (window.innerWidth > 768) {
                    footerMain.style.gridTemplateColumns = '1fr 1fr';
                } else {
                    footerMain.style.gridTemplateColumns = '1fr';
                }
            }
            
            // Footer Copyright düzeltmesi
            const footerCopyright = footerContainer.querySelector('.footer-copyright');
            if (footerCopyright) {
                footerCopyright.style.padding = '2rem 0';
                footerCopyright.style.borderTop = '1px solid rgba(78, 166, 116, 0.1)';
                footerCopyright.style.marginTop = '2rem';
                footerCopyright.style.textAlign = 'center';
                footerCopyright.style.color = 'var(--gray-600)';
                footerCopyright.style.fontSize = '0.95rem';
            }
        }
        
        // Footer bağlantılarını kontrol et
        const footerLinks = footer.querySelectorAll('.footer-links a');
        footerLinks.forEach(link => {
            // Bağlantı çalışıyor mu kontrol et
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (!href || href === '#') {
                    e.preventDefault();
                    console.warn('Geçersiz bağlantı:', this);
                }
            });
        });
        
        // Sosyal medya bağlantılarını kontrol et
        const socialLinks = footer.querySelectorAll('.footer-social a');
        socialLinks.forEach(link => {
            // Bağlantı çalışıyor mu kontrol et
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (!href || href === '#') {
                    e.preventDefault();
                    console.warn('Geçersiz sosyal medya bağlantısı:', this);
                }
            });
        });
        
        // Tüm alt bölümlerin görünürlüğünü kontrol et ve düzelt
        const footerSections = footer.querySelectorAll('.footer-section');
        footerSections.forEach(section => {
            section.style.display = 'block';
            section.style.visibility = 'visible';
            section.style.opacity = '1';
        });
        
        // cPanel-specific fix için stil ekle
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                @media (max-width: 992px) {
                    .footer-main {
                        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important;
                        gap: 3rem !important;
                    }
                }
                
                @media (max-width: 768px) {
                    .footer-main {
                        grid-template-columns: 1fr !important;
                        gap: 2.5rem !important;
                    }
                }
                
                .footer-brand, .footer-section, .footer-copyright {
                    display: block !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                }
            </style>
        `);
    } else {
        console.error('Footer bulunamadı.');
    }
} 