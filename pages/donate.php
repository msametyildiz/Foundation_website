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

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold text-shadow mb-4">Bağış Yapın</h1>
                <p class="lead mb-4">
                    Yardım elinizi uzatın, birlikte umut olalım. Her bağışınız bir aileye 
                    umut, bir çocuğa gelecek demektir.
                </p>
                <div class="d-flex">
                    <div class="me-4">
                        <i class="fas fa-shield-alt fa-2x text-light mb-2"></i>
                        <p class="small">Güvenli</p>
                    </div>
                    <div class="me-4">
                        <i class="fas fa-eye fa-2x text-light mb-2"></i>
                        <p class="small">Şeffaf</p>
                    </div>
                    <div>
                        <i class="fas fa-heart fa-2x text-light mb-2"></i>
                        <p class="small">Samimi</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="assets/images/donation-hero.jpg" alt="Bağış Yap" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Alert Container -->
<div id="alert-container" class="container mt-3"></div>

<!-- Bağış Formu -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Bağış Formu -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-heart"></i> Dekont Yükleme Formu</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            Bağış işleminizi tamamladıktan sonra dekontunuzu aşağıdaki form ile yükleyebilirsiniz.
                        </p>
                        
                        <form id="donation-form" class="needs-validation" novalidate enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="donor_name" class="form-label">Ad Soyad *</label>
                                    <input type="text" class="form-control" id="donor_name" name="donor_name" required>
                                    <div class="invalid-feedback">
                                        Lütfen adınızı ve soyadınızı giriniz.
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="donor_email" class="form-label">E-posta</label>
                                    <input type="email" class="form-control" id="donor_email" name="donor_email">
                                    <div class="small text-muted">İsteğe bağlı - Bilgilendirme için</div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="donor_phone" class="form-label">Telefon</label>
                                    <input type="tel" class="form-control" id="donor_phone" name="donor_phone">
                                    <div class="small text-muted">İsteğe bağlı</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="donation_type" class="form-label">Bağış Türü *</label>
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
                                <label for="amount" class="form-label">Bağış Miktarı (TL)</label>
                                <input type="number" class="form-control" id="amount" name="amount" min="1" step="0.01">
                                <div class="small text-muted">İsteğe bağlı - Dekonttan tespit edilebilir</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="receipt_file" class="form-label">Dekont Dosyası *</label>
                                <input type="file" class="form-control" id="receipt_file" name="receipt_file" 
                                       accept=".jpg,.jpeg,.png,.gif,.pdf" required>
                                <div class="small text-muted">
                                    Kabul edilen formatlar: JPG, PNG, GIF, PDF - Maksimum dosya boyutu: 5MB
                                </div>
                                <div class="invalid-feedback">
                                    Lütfen dekont dosyasını seçiniz.
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Mesajınız</label>
                                <textarea class="form-control" id="message" name="message" rows="3" 
                                         placeholder="İsteğe bağlı olarak bir mesaj bırakabilirsiniz"></textarea>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="privacy_consent" required>
                                <label class="form-check-label" for="privacy_consent">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Kişisel verilerin işlenmesi</a> 
                                    hakkında bilgilendirmeyi okudum ve kabul ediyorum. *
                                </label>
                                <div class="invalid-feedback">
                                    Lütfen kişisel verilerin işlenmesi metnini kabul ediniz.
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-upload"></i> Dekontu Yükle
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Hesap Bilgileri -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-university"></i> Hesap Bilgileri</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($iban_accounts as $account): ?>
                            <div class="account-info mb-4 p-3 border rounded">
                                <h6 class="text-primary"><?php echo clean_output($account['currency']); ?> Hesabı</h6>
                                <p class="mb-2">
                                    <strong>Banka:</strong> <?php echo clean_output($account['bank_name']); ?>
                                </p>
                                <p class="mb-2">
                                    <strong>Hesap Adı:</strong> <?php echo clean_output($account['account_name']); ?>
                                </p>
                                <?php if (!empty($account['account_number'])): ?>
                                    <p class="mb-2">
                                        <strong>Hesap No:</strong> 
                                        <span class="font-monospace"><?php echo clean_output($account['account_number']); ?></span>
                                        <button class="btn btn-sm btn-outline-secondary ms-1" 
                                                onclick="copyToClipboard('<?php echo clean_output($account['account_number']); ?>')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </p>
                                <?php endif; ?>
                                <p class="mb-2">
                                    <strong>IBAN:</strong> 
                                    <span class="font-monospace text-primary"><?php echo clean_output($account['iban']); ?></span>
                                    <button class="btn btn-sm btn-outline-primary ms-1" 
                                            onclick="copyToClipboard('<?php echo clean_output($account['iban']); ?>')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </p>
                                <?php if (!empty($account['swift'])): ?>
                                    <p class="mb-0">
                                        <strong>SWIFT:</strong> 
                                        <span class="font-monospace"><?php echo clean_output($account['swift']); ?></span>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Bağış Önerileri -->
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Önerilen Miktarlar</h5>
                    </div>
                    <div class="card-body">
                        <div class="donation-amounts">
                            <button type="button" class="btn btn-outline-primary donation-amount-btn mb-2" data-amount="50">
                                50 ₺
                                <small class="d-block">Bir günlük yemek</small>
                            </button>
                            <button type="button" class="btn btn-outline-primary donation-amount-btn mb-2" data-amount="100">
                                100 ₺
                                <small class="d-block">Kırtasiye paketi</small>
                            </button>
                            <button type="button" class="btn btn-outline-primary donation-amount-btn mb-2" data-amount="250">
                                250 ₺
                                <small class="d-block">Haftalık gıda</small>
                            </button>
                            <button type="button" class="btn btn-outline-primary donation-amount-btn mb-2" data-amount="500">
                                500 ₺
                                <small class="d-block">Aylık destek</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SSS -->
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="text-center mb-4">Bağış Hakkında Sıkça Sorulan Sorular</h3>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="donationFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#faq1">
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

<!-- Kişisel Veri İşleme Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kişisel Verilerin İşlenmesi Hakkında Bilgilendirme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>
                    6698 sayılı Kişisel Verilerin Korunması Kanunu kapsamında, 
                    kişisel verileriniz aşağıdaki amaçlarla işlenmektedir:
                </p>
                <ul>
                    <li>Bağış işlemlerinizin takibi ve raporlanması</li>
                    <li>Yasal yükümlülüklerin yerine getirilmesi</li>
                    <li>İletişim ve bilgilendirme faaliyetleri</li>
                </ul>
                <p>
                    Kişisel verileriniz, yukarıda belirtilen amaçlar doğrultusunda, 
                    gerekli güvenlik tedbirleri alınarak işlenmekte ve korunmaktadır.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>
