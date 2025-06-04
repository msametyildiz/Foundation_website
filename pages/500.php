<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

http_response_code(500);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Sunucu Hatası | Necat Derneği</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .error-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            padding: 60px;
            text-align: center;
        }
        .error-number {
            font-size: 120px;
            font-weight: 900;
            color: #dc3545;
            line-height: 1;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        .error-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 30px;
            animation: buzz 1.5s infinite;
        }
        .error-title {
            font-size: 36px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }
        .error-description {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        .btn-home {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.6);
            color: white;
        }
        .btn-retry {
            background: transparent;
            border: 2px solid #ff6b6b;
            padding: 13px 28px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            color: #ff6b6b;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin-left: 15px;
        }
        .btn-retry:hover {
            background: #ff6b6b;
            color: white;
        }
        .quick-links {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }
        .quick-links a {
            color: #ff6b6b;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .quick-links a:hover {
            color: #ee5a52;
        }
        .tech-info {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            font-size: 14px;
            color: #666;
            text-align: left;
        }
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        @keyframes buzz {
            0%, 100% {
                transform: translateX(0);
            }
            10%, 30%, 50%, 70%, 90% {
                transform: translateX(-2px);
            }
            20%, 40%, 60%, 80% {
                transform: translateX(2px);
            }
        }
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="error-container">
                    <div class="error-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    
                    <div class="error-number">500</div>
                    
                    <h1 class="error-title">Sunucu Hatası</h1>
                    
                    <p class="error-description">
                        Üzgünüz, sunucuda beklenmeyen bir hata oluştu. Teknik ekibimiz bu sorunu çözmek için çalışıyor. 
                        Lütfen birkaç dakika sonra tekrar deneyin.
                    </p>
                    
                    <div class="mb-4">
                        <a href="/" class="btn-home">
                            <i class="fas fa-home me-2"></i>Ana Sayfaya Dön
                        </a>
                        <a href="javascript:location.reload()" class="btn-retry">
                            <i class="fas fa-redo me-2"></i>Tekrar Dene
                        </a>
                    </div>
                    
                    <div class="tech-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Teknik Bilgi</h6>
                        <p class="mb-2"><strong>Hata Kodu:</strong> HTTP 500 Internal Server Error</p>
                        <p class="mb-2"><strong>Zaman:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                        <p class="mb-0"><strong>Sunucu:</strong> <?php echo $_SERVER['SERVER_NAME'] ?? 'Bilinmiyor'; ?></p>
                    </div>
                    
                    <div class="quick-links">
                        <p class="mb-3"><strong>Yardıma mı ihtiyacınız var?</strong></p>
                        <a href="/pages/contact.php">
                            <i class="fas fa-envelope me-1"></i>Bize Ulaşın
                        </a>
                        <a href="/pages/faq.php">
                            <i class="fas fa-question-circle me-1"></i>SSS
                        </a>
                        <a href="tel:+902121234567">
                            <i class="fas fa-phone me-1"></i>Telefon: (212) 123 45 67
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
