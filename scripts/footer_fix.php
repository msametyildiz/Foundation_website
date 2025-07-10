<?php
/**
 * Necat Derneği - Footer Düzeltme Betiği
 * 
 * Bu dosya, cPanel ortamında footer'ın düzgün görüntülenmesini sağlamak için 
 * acil düzeltmeler içerir. Include olarak kullanılabilir.
 */

// Doğrudan erişimi engelle
if (!defined('INCLUDED')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

// Footer düzeltme CSS'i
function output_footer_fix_css() {
    ob_start();
    ?>
    <style type="text/css">
    /* Footer temel düzeltmeler */
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

    .social-link:hover::before {
        opacity: 1 !important;
    }

    .social-link i {
        position: relative !important;
        z-index: 2 !important;
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
    }
    </style>
    <?php
    $css = ob_get_clean();
    echo $css;
}

// Footer düzeltme JavaScript'i
function output_footer_fix_js() {
    ob_start();
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Footer acil düzeltme betiği çalışıyor...');
        
        // Footer'ı bul
        const footer = document.querySelector('.footer-modern');
        if (footer) {
            console.log('Footer bulundu, düzeltmeler uygulanıyor...');
            
            // Tamamen yeni footer HTML içeriğini enjekte et
            const completeFooterHTML = `
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
                                <li><a href="/index.php"><i class="fas fa-home"></i> Ana Sayfa</a></li>
                                <li><a href="/index.php?page=about"><i class="fas fa-info-circle"></i> Hakkımızda</a></li>
                                <li><a href="/index.php?page=projects"><i class="fas fa-project-diagram"></i> Projelerimiz</a></li>
                                <li><a href="/index.php?page=volunteer"><i class="fas fa-hands-helping"></i> Gönüllü Ol</a></li>
                            </ul>
                        </div>
                        
                        <!-- BÖLÜM 3: DAHA FAZLA -->
                        <div class="footer-section">
                            <h4>Daha Fazla</h4>
                            <ul class="footer-links">
                                <li><a href="/index.php?page=sss"><i class="fas fa-question-circle"></i> SSS</a></li>
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
            
            // Footer'ın içeriğini tamamen değiştir
            footer.innerHTML = completeFooterHTML;
            
            // Stilleri uygula
            footer.style.display = 'flex';
            footer.style.visibility = 'visible';
            footer.style.opacity = '1';
            footer.style.minHeight = '400px';
            footer.style.flexDirection = 'column';
            footer.style.position = 'relative';
            footer.style.marginTop = 'auto';
            footer.style.borderTop = '1px solid rgba(78, 166, 116, 0.1)';
            
            console.log('Footer içeriği tamamen yenilendi ve stiller uygulandı.');
        } else {
            console.error('Footer bulunamadı. HTML yapısı beklenen ile uyuşmuyor olabilir.');
            
            // Footer bulunamazsa, sayfanın sonuna yeni bir footer ekle
            const body = document.querySelector('body');
            if (body) {
                const newFooter = document.createElement('footer');
                newFooter.className = 'footer-modern';
                newFooter.innerHTML = `
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
                                    <li><a href="/index.php"><i class="fas fa-home"></i> Ana Sayfa</a></li>
                                    <li><a href="/index.php?page=about"><i class="fas fa-info-circle"></i> Hakkımızda</a></li>
                                    <li><a href="/index.php?page=projects"><i class="fas fa-project-diagram"></i> Projelerimiz</a></li>
                                    <li><a href="/index.php?page=volunteer"><i class="fas fa-hands-helping"></i> Gönüllü Ol</a></li>
                                </ul>
                            </div>
                            
                            <!-- BÖLÜM 3: DAHA FAZLA -->
                            <div class="footer-section">
                                <h4>Daha Fazla</h4>
                                <ul class="footer-links">
                                    <li><a href="/index.php?page=sss"><i class="fas fa-question-circle"></i> SSS</a></li>
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
                
                // Yeni footer'ı sayfanın sonuna ekle
                body.appendChild(newFooter);
                
                // Stilleri uygula
                newFooter.style.display = 'flex';
                newFooter.style.visibility = 'visible';
                newFooter.style.opacity = '1';
                newFooter.style.minHeight = '400px';
                newFooter.style.flexDirection = 'column';
                newFooter.style.position = 'relative';
                newFooter.style.marginTop = 'auto';
                newFooter.style.borderTop = '1px solid rgba(78, 166, 116, 0.1)';
                
                console.log('Yeni footer oluşturuldu ve sayfaya eklendi.');
            } else {
                console.error('Body elemanı bulunamadı. HTML yapısı beklenen ile uyuşmuyor olabilir.');
            }
        }
    });
    </script>
    <?php
    $js = ob_get_clean();
    echo $js;
}

// Direk PHP ile footer içeriğini değiştir
function inject_footer_html() {
    global $footer_environment;
    
    // Çevre değişkenine bakılmaksızın her zaman çalıştır
    ob_start();
    ?>
    <!-- FOOTER HTML INJECTION - SERVER FIX -->
    <script>
        // Footer HTML işlevini tanımla
        function injectCompleteFooter() {
            // Footer'ı bul
            var footer = document.querySelector('.footer-modern');
            if (!footer) {
                console.error('Footer bulunamadı, sayfa yapısı beklenen gibi değil.');
                return;
            }
            
            // Footer'ın içeriğini değiştir
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
                                <li><a href="/index.php"><i class="fas fa-home"></i> Ana Sayfa</a></li>
                                <li><a href="/index.php?page=about"><i class="fas fa-info-circle"></i> Hakkımızda</a></li>
                                <li><a href="/index.php?page=projects"><i class="fas fa-project-diagram"></i> Projelerimiz</a></li>
                                <li><a href="/index.php?page=volunteer"><i class="fas fa-hands-helping"></i> Gönüllü Ol</a></li>
                            </ul>
                        </div>
                        
                        <!-- BÖLÜM 3: DAHA FAZLA -->
                        <div class="footer-section">
                            <h4>Daha Fazla</h4>
                            <ul class="footer-links">
                                <li><a href="/index.php?page=sss"><i class="fas fa-question-circle"></i> SSS</a></li>
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
            
            // Stiller
            var style = document.createElement('style');
            style.textContent = `
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
                }
                .footer-main {
                    display: grid !important;
                    grid-template-columns: 2.2fr 1fr 1fr 1.5fr !important;
                    gap: 4rem !important;
                    margin-bottom: 3rem !important;
                    flex: 1 !important;
                }
                .social-link {
                    background: transparent !important;
                }
                .social-link:hover {
                    background: linear-gradient(135deg, #4EA674 0%, #3d8560 100%) !important;
                }
                .footer-copyright {
                    text-align: center !important;
                }
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
                }
            `;
            
            // Stili head'e ekle
            document.head.appendChild(style);
            
            console.log('Footer içeriği otomatik olarak yüklendi ve düzeltildi.');
        }
        
        // Sayfa yüklendikten sonra çalıştır
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', injectCompleteFooter);
        } else {
            injectCompleteFooter();
        }
        
        // Yedek mekanizma - timeout ile tekrar dene
        setTimeout(injectCompleteFooter, 500);
        setTimeout(injectCompleteFooter, 2000);
    </script>
    <?php
    $html = ob_get_clean();
    echo $html;
}

// Sayfa sonuna ekle
function add_footer_fix_to_page() {
    // CSS düzeltmelerini ekle
    output_footer_fix_css();
    
    // JavaScript düzeltmelerini ekle
    output_footer_fix_js();
    
    // HTML içeriği doğrudan değiştir
    inject_footer_html();
}

// Otomatik olarak düzeltmeleri ekle
add_footer_fix_to_page();
?>
