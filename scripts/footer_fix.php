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
    }

    .footer-social {
        display: flex !important;
        gap: 1rem !important;
        flex-wrap: wrap !important;
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
            
            // Footer içindeki ana bölümleri kontrol et
            const footerSections = footer.querySelectorAll('.footer-section');
            if (footerSections.length === 0) {
                console.error('Footer bölümleri bulunamadı, HTML yapısı beklenen ile uyuşmuyor olabilir.');
                
                // Temel footer içeriğini ekle (acil durum çözümü)
                const footerContainer = footer.querySelector('.footer-container') || footer;
                if (footerContainer && footerContainer.innerHTML.trim() === '') {
                    footerContainer.innerHTML = `
                        <div class="row footer-main">
                            <div class="col-lg-4 footer-brand">
                                <div class="footer-logo">
                                    <div class="footer-logo-icon">
                                        <i class="fas fa-hands-helping"></i>
                                    </div>
                                    <div class="footer-brand-text">
                                        <h3>Necat Derneği</h3>
                                        <div class="footer-brand-tagline">Elinizi İyilik İçin Uzatın</div>
                                    </div>
                                </div>
                                <p class="footer-description">
                                    Derneğimiz, yoksulluk, afet, hastalık gibi zorluklarla karşılaşan kişilere ve ailelere destek olmak için çalışmaktadır.
                                </p>
                            </div>
                        </div>
                        <div class="footer-copyright">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6 text-center text-md-start">
                                        <p>&copy; ${new Date().getFullYear()} Necat Derneği - Tüm Hakları Saklıdır</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            } else {
                console.log('Footer bölümleri bulundu, görünürlükleri düzeltiliyor...');
                
                // Tüm bölümlerin görünürlüğünü düzelt
                footerSections.forEach(section => {
                    section.style.display = 'block';
                    section.style.visibility = 'visible';
                    section.style.opacity = '1';
                });
            }
            
            // Copyright bölümünü kontrol et
            const copyright = footer.querySelector('.footer-copyright');
            if (!copyright || copyright.innerHTML.trim() === '') {
                const footerContainer = footer.querySelector('.footer-container') || footer;
                const newCopyright = document.createElement('div');
                newCopyright.className = 'footer-copyright';
                newCopyright.innerHTML = `
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 text-center text-md-start">
                                <p>&copy; ${new Date().getFullYear()} Necat Derneği - Tüm Hakları Saklıdır</p>
                            </div>
                        </div>
                    </div>
                `;
                footerContainer.appendChild(newCopyright);
            }
        } else {
            console.error('Footer bulunamadı. HTML yapısı beklenen ile uyuşmuyor olabilir.');
        }
    });
    </script>
    <?php
    $js = ob_get_clean();
    echo $js;
}

// Sayfa sonuna ekle
function add_footer_fix_to_page() {
    // CSS düzeltmelerini ekle
    output_footer_fix_css();
    
    // JavaScript düzeltmelerini ekle
    output_footer_fix_js();
}

// Otomatik olarak düzeltmeleri ekle
add_footer_fix_to_page();
?>
