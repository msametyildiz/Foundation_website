<?php
// IBAN hesap bilgilerini çek
try {
    $stmt = $pdo->prepare("SELECT * FROM iban_accounts WHERE is_active = 1 ORDER BY sort_order ASC");
    $stmt->execute();
    $iban_accounts = $stmt->fetchAll();
} catch (PDOException $e) {
    $iban_accounts = [];
}

// Bağış türlerini çek
try {
    $stmt = $pdo->prepare("SELECT * FROM donation_types WHERE is_active = 1 ORDER BY sort_order ASC");
    $stmt->execute();
    $donation_types = $stmt->fetchAll();
} catch (PDOException $e) {
    $donation_types = [];
}
?>

<!-- Simple Hero Section - Matching Projects/About Pages -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-3">Bağış Yapın</h1>
                <p class="lead mb-4">
                    Yardım elinizi uzatın, birlikte umut olalım. Her bağışınız bir aileye 
                    umut, bir çocuğa gelecek demektir.
                </p>
                
                <!-- Güvenlik İkonları -->
                <div class="row text-center mt-4">
                    <div class="col-md-4 col-12 mb-3">
                        <div class="trust-feature">
                            <div class="trust-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h6 class="trust-title">Güvenli</h6>
                            <small class="trust-desc">Güvenli ödeme sistemi</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 mb-3">
                        <div class="trust-feature">
                            <div class="trust-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h6 class="trust-title">Şeffaf</h6>
                            <small class="trust-desc">Tüm süreçler şeffaf</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 mb-3">
                        <div class="trust-feature">
                            <div class="trust-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h6 class="trust-title">Samimi</h6>
                            <small class="trust-desc">Samimi yaklaşım</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Alert Container -->
<div id="alert-container" class="container mt-3"></div>

<!-- Bağış Formu ve Hesap Bilgileri -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Hesap Bilgileri - Sol Taraf -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-university me-2"></i>Hesap Bilgileri
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($iban_accounts as $account): ?>
                            <div class="account-info mb-4 p-3 border rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-money-check-alt text-success me-2"></i>
                                    <h6 class="text-success mb-0"><?php echo clean_output($account['currency']); ?> Hesabı</h6>
                                </div>
                                
                                <div class="account-details">
                                    <p class="mb-2">
                                        <strong>Banka:</strong> 
                                        <span class="text-muted"><?php echo clean_output($account['bank_name']); ?></span>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Hesap Adı:</strong> 
                                        <span class="text-muted"><?php echo clean_output($account['account_name']); ?></span>
                                    </p>
                                    <?php if (!empty($account['account_number'])): ?>
                                        <p class="mb-2">
                                            <strong>Hesap No:</strong>
                                            <div class="d-flex align-items-center mt-1">
                                                <span class="font-monospace text-dark bg-light px-2 py-1 rounded me-2 flex-grow-1">
                                                    <?php echo clean_output($account['account_number']); ?>
                                                </span>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="copyToClipboard('<?php echo clean_output($account['account_number']); ?>')"
                                                        title="Kopyala">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </p>
                                    <?php endif; ?>
                                    <p class="mb-2">
                                        <strong>IBAN:</strong>
                                        <div class="d-flex align-items-center mt-1">
                                            <span class="font-monospace text-success bg-light px-2 py-1 rounded me-2 flex-grow-1">
                                                <?php echo clean_output($account['iban']); ?>
                                            </span>
                                            <button class="btn btn-sm btn-primary" 
                                                    onclick="copyToClipboard('<?php echo clean_output($account['iban']); ?>')"
                                                    title="Kopyala">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            </div>
                                        </p>
                                    <?php if (!empty($account['swift'])): ?>
                                        <p class="mb-0">
                                            <strong>SWIFT:</strong> 
                                            <span class="font-monospace text-muted"><?php echo clean_output($account['swift']); ?></span>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                
            </div>
            
            <!-- Bağış Formu - Sağ Taraf -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-upload me-2"></i>Dekont Yükleme Formu
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Bağış işleminizi tamamladıktan sonra dekontunuzu aşağıdaki form ile yükleyebilirsiniz.
                        </div>
                        
                        <!-- Form submission response messages will appear here -->
                        <div id="donation-form-response"></div>
                        
                        <form id="donation-form" class="needs-validation ajax-form" novalidate enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <input type="hidden" name="action" value="donation">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="donor_name" class="form-label">
                                        <i class="fas fa-user text-success me-1"></i>Ad Soyad *
                                    </label>
                                    <input type="text" class="form-control" id="donor_name" name="donor_name" required>
                                    <div class="invalid-feedback">
                                        Lütfen adınızı ve soyadınızı giriniz.
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="donor_email" class="form-label">
                                        <i class="fas fa-envelope text-success me-1"></i>E-posta
                                    </label>
                                    <input type="email" class="form-control" id="donor_email" name="donor_email">
                                    <div class="form-text">İsteğe bağlı - Bilgilendirme için</div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="donor_phone" class="form-label">
                                        <i class="fas fa-phone text-success me-1"></i>Telefon
                                    </label>
                                    <input type="tel" class="form-control" id="donor_phone" name="donor_phone" placeholder="(5XX) XXX-XX-XX" maxlength="14">
                                    <div class="form-text">İsteğe bağlı</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="donation_type" class="form-label">
                                        <i class="fas fa-tag text-success me-1"></i>Bağış Türü *
                                    </label>
                                    <select class="form-control" id="donation_type" name="donation_type_id" required>
                                        <option value="">Bağış türünü seçiniz</option>
                                        <?php foreach ($donation_types as $type): ?>
                                            <option value="<?php echo $type['id']; ?>">
                                                <?php echo clean_output($type['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Lütfen bağış türünü seçiniz.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="amount" class="form-label">
                                    <i class="fas fa-money-bill-wave text-success me-1"></i>Bağış Miktarı (TL)
                                </label>
                                
                                <!-- Hızlı Bağış Miktarları -->
                                <div class="row mb-3">
                                    <div class="col-6 col-md-3 mb-2">
                                        <button type="button" class="btn donation-amount-btn w-100" data-amount="100">
                                            <i class="fas fa-donate me-1"></i>100 TL
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <button type="button" class="btn donation-amount-btn w-100" data-amount="250">
                                            <i class="fas fa-donate me-1"></i>250 TL
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <button type="button" class="btn donation-amount-btn w-100" data-amount="500">
                                            <i class="fas fa-donate me-1"></i>500 TL
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <button type="button" class="btn donation-amount-btn w-100" data-amount="1000">
                                            <i class="fas fa-donate me-1"></i>1000 TL
                                        </button>
                                    </div>
                                </div>
                                
                                <input type="number" class="form-control" id="amount" name="amount" min="1" step="0.01" placeholder="Bağış miktarını giriniz veya yukarıdan seçiniz">
                                <div class="form-text">İsteğe bağlı - Dekonttan tespit edilebilir</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="receipt_file" class="form-label">
                                    <i class="fas fa-file-upload text-success me-1"></i>Dekont Dosyası *
                                </label>
                                <input type="file" class="form-control" id="receipt_file" name="receipt_file" 
                                       accept=".jpg,.jpeg,.png,.gif,.pdf" required>
                                <div class="form-text">
                                    Kabul edilen formatlar: JPG, PNG, GIF, PDF - Maksimum dosya boyutu: 5MB
                                </div>
                                <div class="invalid-feedback">
                                    Lütfen dekont dosyasını seçiniz.
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">
                                    <i class="fas fa-comment text-success me-1"></i>Mesajınız
                                </label>
                                <textarea class="form-control" id="message" name="message" rows="3" 
                                         placeholder="İsteğe bağlı olarak bir mesaj bırakabilirsiniz"></textarea>
                            </div>
                            
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="privacy_consent" required onclick="handlePrivacyClick(this)">
                                <label class="form-check-label" for="privacy_consent">
                                    <span class="text-success" style="cursor: pointer;" onclick="openKVKKModal()">
                                        Kişisel verilerin işlenmesi
                                    </span> 
                                    hakkında bilgilendirmeyi okudum ve kabul ediyorum. *
                                </label>
                                <div class="invalid-feedback">
                                    Lütfen kişisel verilerin işlenmesi metnini kabul ediniz.
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-upload me-2"></i>Dekontu Yükle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SSS -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary px-3 py-2 mb-3">SSS</span>
            <h3>Bağış Hakkında Sıkça Sorulan Sorular</h3>
            <p class="text-muted">Merak ettikleriniz hakkında bilgi alın</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="donationFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#faq1">
                                <i class="fas fa-shield-alt text-success me-2"></i>
                                Bağışım güvenli mi?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#donationFAQ">
                            <div class="accordion-body">
                                Evet, tüm bağışlar resmi banka hesaplarımız üzerinden yapılır ve dekontlar güvenli 
                                şekilde saklanır. Bağışlarınızın kullanımı düzenli olarak raporlanır.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#faq2">
                                <i class="fas fa-chart-line text-success me-2"></i>
                                Bağışımın kullanımını nasıl takip edebilirim?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#donationFAQ">
                            <div class="accordion-body">
                                Faaliyet raporlarımızı web sitemizden indirebilir, sosyal medya hesaplarımızdan 
                                güncel faaliyetlerimizi takip edebilirsiniz.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#faq3">
                                <i class="fas fa-calendar-alt text-success me-2"></i>
                                Düzenli bağış yapabilir miyim?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#donationFAQ">
                            <div class="accordion-body">
                                Elbette! Aylık düzenli bağış yapmak isterseniz bizimle iletişime geçin. 
                                Size özel bir bağış planı oluşturabiliriz.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Simple CTA - Matching Projects/About Pages -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-3">Her Bağış Bir Umuttur</h2>
        <p class="lead mb-4">
            Sizin desteğinizle daha çok insana ulaşabilir, daha büyük değişimler yaratabilirız.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="<?php echo site_url('projects'); ?>" class="btn btn-light btn-lg">
                <i class="fas fa-project-diagram me-2"></i> Projelerimizi Görün
            </a>
            <a href="<?php echo site_url('volunteer'); ?>" class="btn btn-outline-light btn-lg">
                <i class="fas fa-hands-helping me-2"></i> Gönüllü Olun
            </a>
        </div>
    </div>
</section>

<!-- Kişisel Veri İşleme Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-shield-alt me-2"></i>
                    Bağış Dekont Yükleme Formu Kişisel Verilerin İşlenmesi Hakkında Aydınlatma Metni
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="kvkk-content">
                    <div class="text-center mb-4">
                        <h4 class="text-success">NECAT DERNEĞİ</h4>
                        <h6>BAĞIŞ DEKONT YÜKLEME FORMU KİŞİSEL VERİLERİN İŞLENMESİ HAKKINDA AYDINLATMA METNİ</h6>
                    </div>

                    <p class="lead">
                        Bu Aydınlatma Metni, 6698 sayılı Kişisel Verilerin Korunması Kanunu'nun ("KVKK") 10. maddesi ve 
                        Aydınlatma Yükümlülüğünün Yerine Getirilmesinde Uyulacak Usul ve Esaslar Hakkında Tebliğ uyarınca, 
                        veri sorumlusu sıfatıyla hareket eden Necat Derneği tarafından, Bağış Dekont Yükleme Formu aracılığıyla 
                        toplanan kişisel verilerinizin işlenmesine ilişkin olarak sizleri bilgilendirmek amacıyla hazırlanmıştır.
                    </p>

                    <div class="kvkk-section">
                        <h5><i class="fas fa-building text-success me-2"></i>1. Veri Sorumlusu</h5>
                        <p>Kişisel verileriniz, veri sorumlusu sıfatıyla Necat Derneği tarafından işlenmektedir.</p>
                    </div>

                    <div class="kvkk-section">
                        <h5><i class="fas fa-database text-success me-2"></i>2. İşlenen Kişisel Veri Kategorileri ve Amaçları</h5>
                        <p>
                            Bağış Dekont Yükleme Formu aracılığıyla tarafınızca paylaşılan kişisel verileriniz, Necat Derneği'nin 
                            "Hakk'a Riyayet", "Emanete Sadakat", "Ahde Vefa", "İnsana Hürmet", "Adalet", "Vicdan", "Şeffaflık", 
                            "Sorumluluk" ve "Dürüstlük" gibi temel ilkeleri doğrultusunda, aşağıda belirtilen amaçlar kapsamında işlenecektir:
                        </p>

                        <div class="sub-section">
                            <h6><i class="fas fa-chart-line text-success me-2"></i>Bağış İşlemlerinizin Takibi ve Raporlanması:</h6>
                            <p>
                                Bağış dekontu aracılığıyla sağladığınız ad, soyadı, T.C. kimlik numarası (gerekliyse), 
                                iletişim bilgileriniz (telefon numarası, e-posta adresi), bağış miktarı, bağış tarihi ve 
                                banka dekontu üzerindeki diğer bilgiler, derneğimizin yürüttüğü yetim, yoksul ve kimsesiz 
                                ailelere yönelik yiyecek, yakacak, giyecek, kırtasiye yardımı; öğrencilere burs desteği; 
                                Ramazan iftar organizasyonları; evlilik yardımları; kurban kesimi ve dağıtımı; sağlık yardımları 
                                gibi faaliyetlerin şeffaf ve doğru bir şekilde kayıt altına alınması, bağışın amacına uygun 
                                olarak kullanıldığının takibi ve iç raporlama süreçlerinin yürütülmesi amacıyla işlenmektedir. 
                                Bu işlem, derneğimizin "Şeffaflık" ve "Sorumluluk" ilkeleriyle uyumludur.
                            </p>
                        </div>

                        <div class="sub-section">
                            <h6><i class="fas fa-gavel text-success me-2"></i>Yasal Yükümlülüklerin Yerine Getirilmesi:</h6>
                            <p>
                                Bağışlarınızla ilgili olarak 5072 sayılı Dernek ve Vakıfların Kamu Kurum ve Kuruluşları ile 
                                İlişkilerine Dair Kanun, Vergi Usul Kanunu ve ilgili diğer mevzuat uyarınca derneğimizin tabi 
                                olduğu yasal yükümlülüklerin (örneğin, resmi defter kayıtlarının tutulması, denetimlere ilişkin 
                                bilgi sağlama) yerine getirilmesi amacıyla kişisel verileriniz işlenmektedir. Bu işlem, 
                                derneğimizin "Hukuka Saygı" ilkesinin bir gereğidir.
                            </p>
                        </div>

                        <div class="sub-section">
                            <h6><i class="fas fa-envelope text-success me-2"></i>İletişim ve Bilgilendirme Faaliyetleri:</h6>
                            <p>
                                Bağışınızın alındığına dair teyit, teşekkür mesajları gönderilmesi, derneğimizin faaliyetleri 
                                ve projeleri hakkında bilgilendirme yapılması (örneğin, bağışınızla gerçekleştirilen bir projenin 
                                sonuçları hakkında bilgi verilmesi) ve gelecekteki yardım kampanyaları hakkında duyurular yapılması 
                                amacıyla iletişim bilgileriniz kullanılabilir. Bu, derneğimizin bağışçılarımızla olan ilişkilerinde 
                                "Ahde Vefa" ve "İnsana Hürmet" ilkeleri çerçevesinde şeffaf ve sürekli bir iletişim kurma amacını taşımaktadır.
                            </p>
                        </div>
                    </div>

                    <div class="kvkk-section">
                        <h5><i class="fas fa-share text-success me-2"></i>3. İşlenen Verilerin Kimlere ve Hangi Amaçla Aktarılabileceği</h5>
                        <p>
                            Toplanan kişisel verileriniz, yukarıda belirtilen amaçlar doğrultusunda ve KVKK'nın 8. ve 9. maddelerinde 
                            belirtilen kişisel veri işleme şartları çerçevesinde, Necat Derneği'nin faaliyetlerini etkin bir şekilde 
                            yürütebilmesi için gerekli olan durumlarda aşağıdaki kişi ve kuruluşlara aktarılabilecektir:
                        </p>
                        <ul class="list-styled">
                            <li>Yasal yükümlülüklerimizin yerine getirilmesi amacıyla yetkili kamu kurum ve kuruluşlarına (örneğin, Maliye Bakanlığı, İçişleri Bakanlığı Dernekler Dairesi Başkanlığı).</li>
                            <li>Denetim süreçlerinin yürütülmesi amacıyla bağımsız denetim firmalarına.</li>
                            <li>Dijital altyapı hizmetleri (sunucu, web sitesi barındırma, e-posta hizmetleri vb.) aldığımız yurt içi/yurt dışı hizmet sağlayıcılarına, veri güvenliği tedbirleri alınarak.</li>
                            <li>Derneğimizin iş birliği yaptığı kurum ve kuruluşlara (örneğin, ortak projeler yürütülen diğer sivil toplum kuruluşları, Şanlıurfa'da okul yapımı gibi projelerde iş birliği yapılan yerel yönetimler), yalnızca ilgili projenin veya faaliyetin gerektirdiği ölçüde.</li>
                        </ul>
                    </div>

                    <div class="kvkk-section">
                        <h5><i class="fas fa-clipboard-list text-success me-2"></i>4. Kişisel Veri Toplamanın Yöntemi ve Hukuki Sebebi</h5>
                        <p>
                            Kişisel verileriniz, Bağış Dekont Yükleme Formu aracılığıyla tamamen veya kısmen otomatik yollarla 
                            elektronik ortamda toplanmaktadır.
                        </p>
                        <p>Kişisel verilerinizin işlenmesinin hukuki sebepleri şunlardır:</p>
                        <ul class="list-styled">
                            <li><strong>Kanunlarda Açıkça Öngörülmesi:</strong> Derneğimizin tabi olduğu mevzuattan kaynaklanan yasal yükümlülüklerin yerine getirilmesi (KVKK md. 5/2-a).</li>
                            <li><strong>Veri Sorumlusunun Hukuki Yükümlülüğünü Yerine Getirebilmesi İçin Zorunlu Olması:</strong> Bağış işlemlerinin kayıt altına alınması ve ilgili kurumlara raporlanması gibi derneğimizin hukuki yükümlülüklerini yerine getirmesi (KVKK md. 5/2-ç).</li>
                            <li><strong>İlgili Kişinin Temel Hak ve Özgürlüklerine Zarar Vermemek Kaydıyla, Veri Sorumlusunun Meşru Menfaatleri İçin Veri İşlenmesinin Zorunlu Olması:</strong> Bağışlarınızın takibi, derneğimizin faaliyetlerinin etkinliğini ölçme ve bağışçılarımızla iletişim kurma gibi meşru menfaatlerimiz (KVKK md. 5/2-f).</li>
                        </ul>
                    </div>

                    <div class="kvkk-section">
                        <h5><i class="fas fa-user-shield text-success me-2"></i>5. Kişisel Veri Sahibinin Hakları</h5>
                        <p>
                            KVKK'nın 11. maddesi uyarınca, kişisel veri sahibi olarak Necat Derneği'ne başvurarak 
                            aşağıdaki haklara sahipsiniz:
                        </p>
                        <ul class="list-styled">
                            <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme,</li>
                            <li>Kişisel verileriniz işlenmişse buna ilişkin bilgi talep etme,</li>
                            <li>Kişisel verilerinizin işlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme,</li>
                            <li>Yurt içinde veya yurt dışında kişisel verilerinizin aktarıldığı üçüncü kişileri bilme,</li>
                            <li>Kişisel verilerinizin eksik veya yanlış işlenmiş olması hâlinde bunların düzeltilmesini isteme ve bu kapsamda yapılan işlemin kişisel verilerinizin aktarıldığı üçüncü kişilere bildirilmesini isteme,</li>
                            <li>KVKK ve ilgili diğer kanun hükümlerine uygun olarak işlenmiş olmasına rağmen, işlenmesini gerektiren sebeplerin ortadan kalkması hâlinde kişisel verilerinizin silinmesini veya yok edilmesini isteme ve bu kapsamda yapılan işlemin kişisel verilerinizin aktarıldığı üçüncü kişilere bildirilmesini isteme,</li>
                            <li>İşlenen verilerin münhasıran otomatik sistemler vasıtasıyla analiz edilmesi suretiyle aleyhinize bir sonucun ortaya çıkmasına itiraz etme,</li>
                            <li>Kişisel verilerinizin kanuna aykırı olarak işlenmesi sebebiyle zarara uğramanız hâlinde zararın giderilmesini talep etme.</li>
                        </ul>
                        <p>
                            Yukarıda belirtilen haklarınızı kullanmak için talebinizi, yazılı olarak veya Kişisel Verileri Koruma 
                            Kurulu'nun belirlediği diğer yöntemlerle Necat Derneği'ne iletebilirsiniz. Başvurunuzda kimliğinizi tespit 
                            edici gerekli bilgiler ile Kanun'un 11. maddesinde belirtilen haklardan hangisini kullanmayı talep ettiğinize 
                            dair açıklamalarınızı içeren dilekçenizi, Dernek Merkezi Adresi'ne bizzat elden teslim edebilir, noter kanalıyla 
                            veya "info@necatdernegi.org" e-posta adresi üzerinden gönderebilirsiniz.
                        </p>
                    </div>

                    <div class="kvkk-section consent-section">
                        <h5><i class="fas fa-check-circle text-success me-2"></i>6. Onay Beyanı</h5>
                        <div class="alert alert-primary">
                            <strong>
                                İşbu Aydınlatma Metni'ni okuduğumu, anladığımı ve kişisel verilerimin yukarıda belirtilen amaçlar 
                                doğrultusunda Necat Derneği tarafından işlenmesine ve aktarılmasına rıza gösterdiğimi beyan ederim.
                            </strong>
                        </div>
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-primary" onclick="acceptKVKK()">
                                <i class="fas fa-check me-2"></i>Okudum ve Kabul Ediyorum
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Kapat
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* ========================================
   CONSISTENT DONATE PAGE STYLES
   ======================================== */

/* Hero Section - Simple Design matching other pages */
.hero-section {
    background: linear-gradient(135deg, #f8fafb 0%, #ffffff 100%);
    padding: calc(80px + 4rem) 0 4rem 0;
    position: relative;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(78, 166, 116, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(211, 217, 43, 0.03) 0%, transparent 50%);
    pointer-events: none;
}

/* Trust Features */
.trust-feature {
    text-align: center;
    padding: 1rem;
    transition: all 0.3s ease;
}

.trust-feature:hover {
    transform: translateY(-2px);
}

.trust-icon {
    width: 60px;
    height: 60px;
    background: rgba(78, 166, 116, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: #4ea674;
    font-size: 1.5rem;
    transition: all 0.3s ease;
}

.trust-feature:hover .trust-icon {
    background: #4ea674;
    color: white;
    box-shadow: 0 4px 15px rgba(78, 166, 116, 0.3);
}

.trust-title {
    font-size: 1rem;
    font-weight: 600;
    color: #4ea674;
    margin-bottom: 0.5rem;
}

.trust-desc {
    color: var(--gray-600);
    font-size: 0.9rem;
}

/* Card Headers with consistent colors */
.card-header.bg-primary {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important;
    border: none;
}

.card-header.bg-success {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important;
    border: none;
}

/* Account Info Cards */
.account-info {
    background: #f8f9fa;
    border: 1px solid rgba(78, 166, 116, 0.2) !important;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.account-info:hover {
    background: rgba(78, 166, 116, 0.05);
    border-color: rgba(78, 166, 116, 0.3) !important;
    box-shadow: 0 4px 15px rgba(78, 166, 116, 0.1);
}

.account-info h6 {
    color: #4ea674 !important;
    font-weight: 700;
}

.account-info strong {
    color: #495057;
}

.account-info .font-monospace {
    font-size: 0.9rem;
    letter-spacing: 0.5px;
}

/* Donation Amount Buttons */
.donation-amount-btn {
    border: 2px solid rgba(78, 166, 116, 0.3) !important;
    color: #4ea674 !important;
    background: transparent;
    padding: 1rem;
    border-radius: 12px;
    transition: all 0.3s ease;
    text-align: left;
}

.donation-amount-btn:hover {
    background: #4ea674 !important;
    border-color: #4ea674 !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(78, 166, 116, 0.3);
}

.donation-amount-btn:hover i {
    color: white !important;
}

.donation-amount-btn.active {
    background: #4ea674 !important;
    border-color: #4ea674 !important;
    color: white !important;
}

/* Form Styling */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-label i {
    opacity: 0.8;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #4ea674 !important;
    box-shadow: 0 0 0 0.2rem rgba(78, 166, 116, 0.25) !important;
}

.form-text {
    color: #6c757d;
    font-size: 0.875rem;
}

/* Buttons */
.btn-primary {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important;
    border: none !important;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #3d8760 0%, #2d6446 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(78, 166, 116, 0.3);
}

.btn-outline-primary {
    border: 2px solid #4ea674 !important;
    color: #4ea674 !important;
    background: transparent;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #4ea674 !important;
    border-color: #4ea674 !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(78, 166, 116, 0.3);
}

/* Alert */
.alert-info {
    background: rgba(78, 166, 116, 0.1);
    border: 1px solid rgba(78, 166, 116, 0.2);
    color: #4ea674;
    border-radius: 8px;
}

/* Section badges */
.badge.bg-primary {
    background: #4ea674 !important;
    color: white !important;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 20px;
}

/* Accordion styling */
.accordion-button {
    background: white;
    border: none;
    padding: 1.25rem;
    font-weight: 600;
}

.accordion-button:not(.collapsed) {
    background: rgba(78, 166, 116, 0.1);
    color: #4ea674;
    box-shadow: none;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(78, 166, 116, 0.25);
    border-color: #4ea674;
}

.accordion-button::after {
    color: #4ea674;
}

/* CTA Section */
.py-5.bg-primary.text-white {
    background: linear-gradient(135deg, #4ea674 0%, #3d8760 100%) !important;
    position: relative;
}

.py-5.bg-primary.text-white::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, 
        rgba(78, 166, 116, 0.1) 0%, 
        rgba(61, 135, 96, 0.1) 100%);
    pointer-events: none;
}

.btn-accent {
    background: #ffc107 !important;
    color: var(--gray-900) !important;
    border: 2px solid #ffc107 !important;
    font-weight: 700;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-accent:hover {
    background: #e0a800 !important;
    color: var(--gray-900) !important;
    border-color: #e0a800 !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(242, 229, 41, 0.3);
}

.btn-outline-light {
    border-color: rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
}

/* KVKK Modal Styles */
.modal-xl {
    max-width: 90%;
}

.kvkk-content {
    font-size: 0.95rem;
    line-height: 1.6;
}

.kvkk-content h4 {
    color: #4ea674;
    font-weight: 700;
}

.kvkk-content h6 {
    color: #6c757d;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.kvkk-section {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.kvkk-section:last-child {
    border-bottom: none;
}

.kvkk-section h5 {
    color: #4ea674;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba(78, 166, 116, 0.2);
}

.sub-section {
    margin-left: 1rem;
    margin-bottom: 1.5rem;
    padding-left: 1rem;
    border-left: 3px solid rgba(78, 166, 116, 0.3);
}

.sub-section h6 {
    color: #4ea674;
    font-weight: 600;
    margin-bottom: 0.8rem;
}

.list-styled {
    padding-left: 1.5rem;
}

.list-styled li {
    margin-bottom: 0.8rem;
    color: #495057;
}

.list-styled li strong {
    color: #4ea674;
}

.consent-section .alert {
    background: rgba(78, 166, 116, 0.1);
    border: 1px solid rgba(78, 166, 116, 0.3);
    color: #4ea674;
}

.modal-header .modal-title {
    color: #4ea674;
    font-weight: 600;
}

@media (max-width: 768px) {
    .modal-xl {
        max-width: 95%;
        margin: 0.5rem;
    }
    
    .kvkk-content {
        font-size: 0.9rem;
    }
    
    .sub-section {
        margin-left: 0.5rem;
        padding-left: 0.5rem;
    }
}

/* Responsive Design */
@media (max-width: 992px) {
    .hero-section {
        padding: calc(70px + 3rem) 0 3rem 0;
    }
    
    .display-4 {
        font-size: 2.5rem;
    }
}

/* Custom Checkbox Styling */
.form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    border: 2px solid #dee2e6;
    border-radius: 4px;
    background-color: #fff;
    transition: all 0.3s ease;
}

.form-check-input:checked {
    background-color: #4ea674 !important;
    border-color: #4ea674 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e") !important;
}

.form-check-input:focus {
    border-color: #4ea674 !important;
    box-shadow: 0 0 0 0.25rem rgba(78, 166, 116, 0.25) !important;
}

.form-check-input:hover {
    border-color: #4ea674;
}

.form-check-label {
    color: #495057;
    cursor: pointer;
}

.form-check-label .text-success {
    color: #4ea674 !important;
    font-weight: 600;
    text-decoration: underline;
    transition: all 0.3s ease;
}

.form-check-label .text-success:hover {
    color: #3d8760 !important;
    text-decoration: none;
}

/* Ensure checkbox alignment */
.form-check {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.form-check .form-check-input {
    margin-top: 0.125rem;
    flex-shrink: 0;
}

.form-check .form-check-label {
    flex-grow: 1;
}
</style>

<script>
// Copy to clipboard function - Used for IBAN and account numbers
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Use the global showNotification function if available
        if (typeof showNotification === 'function') {
            showNotification('Kopyalandı!', 'success', 2000);
        } else {
            // Fallback to simple toast
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
        }
    }).catch(function(err) {
        console.error('Kopyalama hatası: ', err);
    });
}

// Custom KVKK alert function
function showKVKKAlert() {
    // Create a simple toast notification in the top-right corner
    const toast = document.createElement('div');
    toast.className = 'kvkk-toast-message';
    toast.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        KVKK metni kabul edildi!
    `;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #4ea674;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        font-weight: 500;
        display: flex;
        align-items: center;
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto close after 3 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(20px)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}

// Show personalized donation success message
function showDonationSuccessAlert(donorName) {
    // Create overlay
    const overlay = document.createElement('div');
    overlay.className = 'donation-alert-overlay';
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
    
    // Create alert popup
    const alertPopup = document.createElement('div');
    alertPopup.className = 'donation-alert-popup';
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
        border: 3px solid #4ea674;
        position: relative;
    `;
    
    alertPopup.innerHTML = `
        <div style="
            width: 80px;
            height: 80px;
            background: rgba(78, 166, 116, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            border: 3px solid #4ea674;
        ">
            <i class="fas fa-check-circle" style="font-size: 40px; color: #4ea674;"></i>
        </div>
        <h4 style="color: #4ea674; margin-bottom: 1rem;">Bağışınız Başarıyla Kaydedildi</h4>
        <p style="
            font-size: 16px;
            line-height: 1.6;
            color: #374151;
            margin-bottom: 1.5rem;
        ">Sayın ${donorName}, bağışınız başarıyla kaydedilmiştir. İhtiyaç sahiplerine yapmış olduğunuz bu değerli bağış için içtenlikle teşekkür ederiz. Dekont bilgileriniz güvenle alınmış olup, değerlendirme sürecinden sonra tarafınızla iletişime geçilecektir.</p>
        <button type="button" class="donation-alert-close-btn" style="
            background: #4ea674;
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
    
    // Animation
    requestAnimationFrame(() => {
        overlay.style.opacity = '1';
        alertPopup.style.transform = 'scale(1) translateY(0)';
    });
    
    // Close function
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
    const closeBtn = alertPopup.querySelector('.donation-alert-close-btn');
    closeBtn.addEventListener('click', closeAlert);
    
    // Hover effect for button
    closeBtn.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 4px 15px rgba(78, 166, 116, 0.4)';
    });
    
    closeBtn.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = 'none';
    });
    
    // Close on overlay click
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            closeAlert();
        }
    });
    
    // Close on ESC key
    const handleEscape = (e) => {
        if (e.key === 'Escape') {
            closeAlert();
            document.removeEventListener('keydown', handleEscape);
        }
    };
    document.addEventListener('keydown', handleEscape);
}

// KVKK Modal functions
function openKVKKModal() {
    const modal = new bootstrap.Modal(document.getElementById('privacyModal'));
    modal.show();
}

function handlePrivacyClick(checkbox) {
    // If checkbox is being checked, open the modal
    if (checkbox.checked) {
        // Uncheck it first since user should read the text before accepting
        checkbox.checked = false;
        openKVKKModal();
    }
}

function acceptKVKK() {
    document.getElementById('privacy_consent').checked = true;
    const modal = bootstrap.Modal.getInstance(document.getElementById('privacyModal'));
    if (modal) {
        modal.hide();
    }
    setTimeout(removeModalBackdrop, 350);
    // Modal kapandıktan sonra karartıyı temizle
    setTimeout(function() {
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
    }, 300);
    
    // Show custom KVKK acceptance alert
    setTimeout(function() {
        showKVKKAlert();
    }, 500);
}

// Modal kapatıldığında da karartıyı temizle
function removeModalBackdrop() {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

// Modal kapatıldığında otomatik temizlik
const privacyModalEl = document.getElementById('privacyModal');
if (privacyModalEl) {
    privacyModalEl.addEventListener('hidden.bs.modal', removeModalBackdrop);
}

// Initialize the donation form when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize donation amount buttons
    const donationButtons = document.querySelectorAll('.donation-amount-btn');
    const amountInput = document.getElementById('amount');
    
    if (donationButtons.length > 0 && amountInput) {
        donationButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                donationButtons.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                // Set amount in form
                const amount = this.getAttribute('data-amount');
                amountInput.value = amount;
            });
        });
        
        // Clear active state when manually entering an amount
        amountInput.addEventListener('input', function() {
            donationButtons.forEach(btn => btn.classList.remove('active'));
        });
    }
    
    // We're using the global AJAX form handling from main.js
    const donationForm = document.getElementById('donation-form');
    if (donationForm) {
        // Make sure the form has the ajax-form class
        if (!donationForm.classList.contains('ajax-form')) {
            donationForm.classList.add('ajax-form');
        }
        
        // Add custom form validation
        donationForm.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
        
        // Handle form submission
        donationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Yükleniyor...';
            submitBtn.disabled = true;
            
            fetch('ajax/forms.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Extract name for personalized message
                    const donorName = formData.get('donor_name') || 'Değerli Bağışçımız';
                    
                    // Show personalized success alert
                    showDonationSuccessAlert(donorName);
                    
                    // Reset the form
                    donationForm.reset();
                    donationForm.classList.remove('was-validated');
                } else {
                    // Show error message
                    const errorMessage = data.message || 'Bir hata oluştu. Lütfen tekrar deneyiniz.';
                    
                    // Use the global showNotification function if available
                    if (typeof showNotification === 'function') {
                        showNotification(errorMessage, 'danger');
                    } else {
                        // Show error message in the form response area
                        const responseDiv = document.getElementById('donation-form-response');
                        if (responseDiv) {
                            responseDiv.innerHTML = `
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    ${errorMessage}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Use the global showNotification function if available
                if (typeof showNotification === 'function') {
                    showNotification('Bir hata oluştu. Lütfen tekrar deneyiniz.', 'danger');
                } else {
                    // Show error message in the form response area
                    const responseDiv = document.getElementById('donation-form-response');
                    if (responseDiv) {
                        responseDiv.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Bir hata oluştu. Lütfen tekrar deneyiniz.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                    }
                }
            })
            .finally(() => {
                // Restore button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});
</script>
