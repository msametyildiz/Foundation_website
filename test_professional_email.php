<?php
require_once 'config/database.php';
require_once 'includes/EmailService.php';

echo "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Profesyonel Email Şablonları Test</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; min-height: 100vh; }
        .card { box-shadow: 0 15px 35px rgba(0,0,0,0.1); border: none; border-radius: 15px; }
        .card-header { background: linear-gradient(135deg, #4ea674 0%, #48bb78 100%); border-radius: 15px 15px 0 0 !important; }
        .btn-test { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; }
        .btn-test:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .alert { border-radius: 10px; border: none; }
        .test-section { margin-bottom: 30px; padding: 25px; background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
    <div class='container'>
        <div class='card'>
            <div class='card-header text-white text-center py-4'>
                <h2 class='mb-0'><i class='fas fa-envelope-open-text me-3'></i>Profesyonel Email Şablonları Test</h2>
                <p class='mb-0 mt-2 opacity-75'>Yeni geliştirilen modern e-posta şablonlarını test edin</p>
            </div>
            <div class='card-body p-4'>";

try {
    $emailService = new EmailService($pdo);
    
    echo "<div class='alert alert-info'>
            <i class='fas fa-info-circle me-2'></i>
            <strong>Test Ortamı:</strong> E-postalar samet.saray.06@gmail.com adresine gönderilecektir.
          </div>";
    
    // Test Data
    $testVolunteerData = [
        'first_name' => 'Ahmet',
        'last_name' => 'Yılmaz',
        'name' => 'Ahmet Yılmaz',
        'email' => 'test@example.com',
        'phone' => '0555 123 4567',
        'age' => 28,
        'profession' => 'Yazılım Geliştirici',
        'availability' => 'flexible',
        'interests' => 'Teknoloji, Eğitim, Sosyal Sorumluluk, Çevre',
        'experience' => 'Daha önce çeşitli STK\'larda gönüllü olarak çalıştım. Özellikle eğitim alanında 2 yıl deneyimim var. Çocuklara programlama ve matematik dersleri verdim. Ayrıca sosyal medya yönetimi ve web tasarımı konularında da katkıda bulundum.',
        'motivation' => 'Topluma daha fazla fayda sağlamak ve teknoloji alanındaki bilgilerimi paylaşarak genç nesillerin gelişimine katkıda bulunmak istiyorum. Özellikle dezavantajlı grupların teknolojiye erişimini artırmak benim için çok önemli.'
    ];
    
    echo "<div class='test-section'>
            <h4><i class='fas fa-user-plus text-success me-2'></i>Gönüllü E-posta Şablonları</h4>
            <p class='text-muted'>Hem yöneticiye giden bildirim hem de başvuru sahibine giden otomatik yanıt e-postalarını test edin.</p>
            
            <div class='row'>
                <div class='col-md-6 mb-3'>
                    <div class='d-grid'>
                        <button onclick='testAdminEmail()' class='btn btn-test btn-lg text-white'>
                            <i class='fas fa-user-shield me-2'></i>Yönetici Bildirimi Gönder
                        </button>
                    </div>
                </div>
                <div class='col-md-6 mb-3'>
                    <div class='d-grid'>
                        <button onclick='testAutoReply()' class='btn btn-success btn-lg'>
                            <i class='fas fa-reply me-2'></i>Otomatik Yanıt Gönder
                        </button>
                    </div>
                </div>
            </div>
            
            <div class='row mt-3'>
                <div class='col-12'>
                    <div class='d-grid'>
                        <button onclick='testBothEmails()' class='btn btn-warning btn-lg'>
                            <i class='fas fa-envelope-square me-2'></i>Her İki E-postayı Test Et
                        </button>
                    </div>
                </div>
            </div>
          </div>";
    
    echo "<div class='test-section'>
            <h4><i class='fas fa-eye text-primary me-2'></i>Şablon Önizleme</h4>
            <p class='text-muted'>E-posta şablonlarının görünümünü önceden inceleyin.</p>
            
            <div class='row'>
                <div class='col-md-6 mb-3'>
                    <div class='d-grid'>
                        <button onclick='previewAdminTemplate()' class='btn btn-outline-primary btn-lg'>
                            <i class='fas fa-search me-2'></i>Yönetici Şablonu Önizle
                        </button>
                    </div>
                </div>
                <div class='col-md-6 mb-3'>
                    <div class='d-grid'>
                        <button onclick='previewAutoReplyTemplate()' class='btn btn-outline-success btn-lg'>
                            <i class='fas fa-search me-2'></i>Otomatik Yanıt Önizle
                        </button>
                    </div>
                </div>
            </div>
          </div>";

    echo "<div id='testResults' class='mt-4'></div>";
    echo "<div id='previewContainer' class='mt-4'></div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>
            <h6><i class='fas fa-exclamation-triangle me-2'></i>Hata</h6>
            <p class='mb-0'>" . $e->getMessage() . "</p>
          </div>";
}

echo "</div>
        </div>
        
        <div class='text-center mt-4'>
            <a href='test_volunteer_system.php' class='btn btn-outline-light btn-lg me-3'>
                <i class='fas fa-arrow-left me-2'></i>Ana Test Sayfası
            </a>
            <a href='pages/volunteer.php' class='btn btn-outline-light btn-lg'>
                <i class='fas fa-hand-holding-heart me-2'></i>Gönüllü Formu
            </a>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
    <script>
    function showResults(html, type = 'info') {
        const alertClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : 'alert-info';
        document.getElementById('testResults').innerHTML = 
            '<div class=\"alert ' + alertClass + ' alert-dismissible fade show\">' + html + 
            '<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button></div>';
    }
    
    function testAdminEmail() {
        showResults('<i class=\"fas fa-spinner fa-spin me-2\"></i>Yönetici bildirim e-postası gönderiliyor...');
        
        fetch('ajax/forms.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'volunteer',
                name: 'Ahmet Yılmaz',
                email: 'test@example.com',
                phone: '0555 123 4567',
                age: '28',
                profession: 'Yazılım Geliştirici',
                availability: 'flexible',
                interests: 'Teknoloji, Eğitim, Sosyal Sorumluluk',
                experience: 'Daha önce çeşitli STK\\'larda gönüllü olarak çalıştım.',
                message: 'Topluma daha fazla fayda sağlamak istiyorum.'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showResults('<i class=\"fas fa-check-circle me-2\"></i><strong>Başarılı!</strong> Yönetici bildirim e-postası gönderildi. Başvuru da veritabanına kaydedildi.', 'success');
            } else {
                showResults('<i class=\"fas fa-exclamation-triangle me-2\"></i><strong>Hata!</strong> ' + data.message, 'error');
            }
        })
        .catch(error => {
            showResults('<i class=\"fas fa-exclamation-triangle me-2\"></i><strong>AJAX Hatası!</strong> ' + error, 'error');
        });
    }
    
    function testAutoReply() {
        showResults('<i class=\"fas fa-spinner fa-spin me-2\"></i>Otomatik yanıt e-postası test ediliyor...');
        
        // Direct email service test
        fetch('test_direct_auto_reply.php', {
            method: 'POST'
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes('success')) {
                showResults('<i class=\"fas fa-check-circle me-2\"></i><strong>Başarılı!</strong> Otomatik yanıt e-postası gönderildi.', 'success');
            } else {
                showResults('<i class=\"fas fa-exclamation-triangle me-2\"></i><strong>Hata!</strong> ' + data, 'error');
            }
        })
        .catch(error => {
            showResults('<i class=\"fas fa-exclamation-triangle me-2\"></i><strong>AJAX Hatası!</strong> ' + error, 'error');
        });
    }
    
    function testBothEmails() {
        showResults('<i class=\"fas fa-spinner fa-spin me-2\"></i>Her iki e-posta da gönderiliyor...');
        
        // Test complete volunteer application flow
        testAdminEmail();
        setTimeout(() => {
            testAutoReply();
        }, 2000);
    }
    
    function previewAdminTemplate() {
        showResults('<i class=\"fas fa-spinner fa-spin me-2\"></i>Yönetici şablonu yükleniyor...');
        
        fetch('preview_email_template.php?type=admin', {
            method: 'GET'
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('previewContainer').innerHTML = data;
            showResults('<i class=\"fas fa-check-circle me-2\"></i>Yönetici şablonu önizlemesi yüklendi.', 'success');
        })
        .catch(error => {
            showResults('<i class=\"fas fa-exclamation-triangle me-2\"></i><strong>Önizleme Hatası!</strong> ' + error, 'error');
        });
    }
    
    function previewAutoReplyTemplate() {
        showResults('<i class=\"fas fa-spinner fa-spin me-2\"></i>Otomatik yanıt şablonu yükleniyor...');
        
        fetch('preview_email_template.php?type=auto_reply', {
            method: 'GET'
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('previewContainer').innerHTML = data;
            showResults('<i class=\"fas fa-check-circle me-2\"></i>Otomatik yanıt şablonu önizlemesi yüklendi.', 'success');
        })
        .catch(error => {
            showResults('<i class=\"fas fa-exclamation-triangle me-2\"></i><strong>Önizleme Hatası!</strong> ' + error, 'error');
        });
    }
    </script>
</body>
</html>";
