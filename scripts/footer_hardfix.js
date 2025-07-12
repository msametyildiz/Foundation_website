/**
 * Necat Derneği - Acil Footer Düzeltme Dosyası
 * 
 * Bu JS dosyası, tüm diğer çözümler başarısız olursa doğrudan HTML'ye eklenebilir.
 * Bu durumda <script src="/scripts/footer_hardfix.js"></script> şeklinde eklenmesi yeterlidir.
 */

// Footer düzeltme ana işlevi
function fixNestedFooter() {
    console.log('Footer acil düzeltme betiği çalışıyor...');
    
    // Footer'ı bul
    const footer = document.querySelector('.footer-modern');
    if (footer) {
        console.log('Footer bulundu, düzeltmeler uygulanıyor...');
        
        // Footer içeriğini tamamen değiştir
        footer.innerHTML = `
            <div class="footer-container">
                <div class="footer-main">
                    <!-- BÖLÜM 1: MARKA -->
                    <div class="footer-brand">
                        <div class="footer-logo">
                            <div class="footer-logo-icon">
                                <img src="/assets/images/logo.png" alt="Necat Derneği Logo" style="width: 60px; height: auto;">
                            </div>
                            <div class="footer-brand-text">
                                <h3>Necat Derneği</h3>
                                <div class="footer-brand-tagline">Elinizi İyilik İçin Uzatın</div>
                            </div>
                        </div>
                        <p class="footer-description">
                            Derneğimiz, yoksulluk, afet, hastalık gibi zorluklarla karşılaşan kişilere ve ailelere destek olmak için çalışmaktadır.
                        </p>
                        <div class="footer-social">
                            <a href="#" class="social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- BÖLÜM 2: HIZLI BAĞLANTILAR -->
                    <div class="footer-section">
                        <h4>Hızlı Bağlantılar</h4>
                        <ul class="footer-links">
                            <li><a href="/"><i class="fas fa-home"></i> Ana Sayfa</a></li>
                            <li><a href="/index.php?page=about"><i class="fas fa-info-circle"></i> Hakkımızda</a></li>
                            <li><a href="/index.php?page=projects"><i class="fas fa-project-diagram"></i> Projelerimiz</a></li>
                            <li><a href="/index.php?page=volunteer"><i class="fas fa-hands-helping"></i> Gönüllü Ol</a></li>
                        </ul>
                    </div>
                    
                    <!-- BÖLÜM 3: DAHA FAZLA -->
                    <div class="footer-section">
                        <h4>Daha Fazla</h4>
                        <ul class="footer-links">
                            <li><a href="/index.php?page=faq"><i class="fas fa-question-circle"></i> SSS</a></li>
                            <li><a href="/index.php?page=contact"><i class="fas fa-envelope"></i> İletişim</a></li>
                            <li><a href="/index.php?page=donate"><i class="fas fa-heart"></i> Bağış Yap</a></li>
                        </ul>
                    </div>
                    
                    <!-- BÖLÜM 4: İLETİŞİM -->
                    <div class="footer-section">
                        <h4>Bize Ulaşın</h4>
                        <ul class="footer-contact-info">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Fevzipaşa Mahallesi Rüzgarlı Caddesi Plevne Sokak No:14/1 Ulus Altındağ Ankara</span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <span>+90 312 311 65 25</span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span>info@necatdernegi.org</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- TELİF HAKKI -->
                <div class="footer-copyright">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <p>&copy; ${new Date().getFullYear()} Necat Derneği - Tüm Hakları Saklıdır</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Footer stillerini ayarla
        const footerStyles = `
            .footer-modern {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                min-height: 400px !important;
                flex-direction: column !important;
                position: relative !important;
                margin-top: auto !important;
                border-top: 1px solid rgba(78, 166, 116, 0.1) !important;
                overflow: visible !important;
                width: 100% !important;
            }
            
            .footer-container {
                position: relative !important;
                z-index: 2 !important;
                max-width: 1400px !important;
                margin: 0 auto !important;
                padding: 4rem 2rem 2rem !important;
                flex: 1 !important;
                display: flex !important;
                flex-direction: column !important;
                width: 100% !important;
            }
            
            .footer-main {
                display: grid !important;
                gap: 4rem !important;
                margin-bottom: 3rem !important;
                flex: 1 !important;
                grid-template-columns: 2.2fr 1fr 1fr 1.5fr !important;
            }
            
            .footer-brand, .footer-section {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                position: relative !important;
            }
            
            .footer-copyright {
                padding: 2rem 0 !important;
                border-top: 1px solid rgba(78, 166, 116, 0.1) !important;
                margin-top: 2rem !important;
                text-align: center !important;
                color: #6b7280 !important;
                font-size: 0.95rem !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            .footer-links {
                display: flex !important;
                flex-direction: column !important;
                gap: 0.5rem !important;
                list-style: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            
            .footer-links li {
                margin: 0 !important;
                display: block !important;
            }
            
            .footer-links a {
                color: #6b7280 !important;
                text-decoration: none !important;
                font-size: 1rem !important;
                font-weight: 500 !important;
                transition: all 0.3s !important;
                display: flex !important;
                align-items: center !important;
                gap: 0.75rem !important;
                padding: 0.875rem 1.25rem !important;
                border-radius: 0.5rem !important;
                position: relative !important;
                background: transparent !important;
                border: 1px solid transparent !important;
            }
            
            .footer-links a:hover {
                color: white !important;
                transform: translateX(8px) !important;
                background: linear-gradient(135deg, #4EA674 0%, #3d8560 100%) !important;
                border-color: #4EA674 !important;
            }
            
            .footer-social {
                display: flex !important;
                gap: 1rem !important;
                flex-wrap: wrap !important;
            }
            
            .social-link {
                width: 52px !important;
                height: 52px !important;
                background: transparent !important;
                border-radius: 1rem !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                color: #4EA674 !important;
                text-decoration: none !important;
                transition: all 0.3s !important;
                border: 1px solid rgba(78, 166, 116, 0.15) !important;
                position: relative !important;
                overflow: hidden !important;
                font-size: 1.25rem !important;
            }
            
            .social-link:hover {
                transform: translateY(-4px) scale(1.08) !important;
                box-shadow: 0 12px 30px rgba(78, 166, 116, 0.25), 0 6px 15px rgba(0, 0, 0, 0.1) !important;
                color: white !important;
                border-color: transparent !important;
                background: linear-gradient(135deg, #4EA674 0%, #3d8560 100%) !important;
            }
            
            .footer-description {
                font-size: 1.05rem !important;
                line-height: 1.7 !important;
                color: #6b7280 !important;
                margin-bottom: 2.5rem !important;
                max-width: 380px !important;
                font-weight: 400 !important;
            }
            
            .footer-brand-tagline {
                font-size: 0.95rem !important;
                color: #6b7280 !important;
                font-weight: 500 !important;
                letter-spacing: 0.5px !important;
                text-transform: uppercase !important;
                opacity: 0.8 !important;
            }
            
            .footer-logo {
                display: flex !important;
                align-items: center !important;
                gap: 1.25rem !important;
                margin-bottom: 2rem !important;
                position: relative !important;
            }
            
            .footer-section h4 {
                font-size: 1.25rem !important;
                font-weight: 700 !important;
                margin-bottom: 2rem !important;
                color: #1f2937 !important;
                position: relative !important;
                padding-bottom: 0.75rem !important;
                display: block !important;
                visibility: visible !important;
            }
            
            .footer-contact-info {
                list-style: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            
            .footer-contact-info li {
                display: flex !important;
                gap: 1rem !important;
                margin-bottom: 1rem !important;
                color: #6b7280 !important;
            }
            
            .footer-contact-info i {
                color: #4EA674 !important;
                font-size: 1.2rem !important;
                min-width: 24px !important;
            }
            
            /* Responsive düzeltmeler */
            @media (max-width: 1200px) {
                .footer-main {
                    grid-template-columns: 2fr 1fr 1fr !important;
                }
            }
            
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
                
                .footer-container {
                    padding: 3rem 1.5rem 2rem !important;
                }
            }
            
            @media (max-width: 576px) {
                .footer-container {
                    padding: 2.5rem 1.25rem 1.5rem !important;
                }
                
                .footer-section h4 {
                    margin-bottom: 1.5rem !important;
                    font-size: 1.2rem !important;
                }
                
                .footer-links a {
                    padding: 0.75rem 1rem !important;
                    font-size: 0.95rem !important;
                }
                
                .footer-description {
                    font-size: 0.95rem !important;
                    margin-bottom: 2rem !important;
                }
                
                .footer-logo {
                    margin-bottom: 1.5rem !important;
                }
                
                .footer-copyright {
                    font-size: 0.9rem !important;
                }
            }
        `;
        
        // Stil elementini oluştur ve ekle
        const styleElement = document.createElement('style');
        styleElement.textContent = footerStyles;
        document.head.appendChild(styleElement);
        
        console.log('Footer düzeltmeleri tamamlandı.');
    } else {
        console.log('Footer bulunamadı, düzeltme yapılamadı.');
    }
}

// Sayfa yüklendiğinde düzeltmeyi çalıştır
document.addEventListener('DOMContentLoaded', function() {
    // Sayfa tamamen yüklendikten sonra düzeltmeyi çalıştır
    setTimeout(fixNestedFooter, 500);
});

// Yedek olarak window.onload'a da ekle
window.onload = function() {
    // Eğer DOMContentLoaded çalışmazsa, bu şekilde de dene
    setTimeout(fixNestedFooter, 1000);
};

// Acil durum için doğrudan çağır
setTimeout(fixNestedFooter, 2000);

// Sayfa içeriği değişirse tekrar düzeltme yap (SPA uyumluluğu için)
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
            // DOM değişikliği algılandı, footer'ı kontrol et
            const footer = document.querySelector('.footer-modern');
            if (footer && !footer.getAttribute('data-fixed')) {
                setTimeout(fixNestedFooter, 100);
            }
        }
    });
});

// Gözlemlenecek hedef ve yapılandırma
observer.observe(document.body, {
    childList: true,
    subtree: true
});

// Sayfadan ayrılırken gözlemciyi temizle
window.addEventListener('beforeunload', function() {
    observer.disconnect();
}); 