<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

http_response_code(403);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Erişim Engellendi | Necat Derneği</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            animation: bounce 2s infinite;
        }
        .error-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 30px;
            animation: shake 1.5s infinite;
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
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            color: white;
        }
        .quick-links {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }
        .quick-links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .quick-links a:hover {
            color: #764ba2;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        @keyframes shake {
            0%, 100% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(-5px);
            }
            75% {
                transform: translateX(5px);
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
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    
                    <div class="error-number">403</div>
                    
                    <h1 class="error-title">Erişim Engellendi</h1>
                    
                    <p class="error-description">
                        Üzgünüz, bu sayfaya erişim yetkiniz bulunmuyor. Bu içeriği görüntülemek için gerekli izinlere sahip değilsiniz.
                    </p>
                    
                    <a href="/" class="btn-home">
                        <i class="fas fa-home me-2"></i>Ana Sayfaya Dön
                    </a>
                    
                    <div class="quick-links">
                        <p class="mb-3"><strong>Hızlı Bağlantılar:</strong></p>
                        <a href="/pages/about.php">
                            <i class="fas fa-info-circle me-1"></i>Hakkımızda
                        </a>
                        <a href="/pages/contact.php">
                            <i class="fas fa-envelope me-1"></i>İletişim
                        </a>
                        <a href="/pages/projects.php">
                            <i class="fas fa-project-diagram me-1"></i>Projeler
                        </a>
                        <a href="/pages/volunteer.php">
                            <i class="fas fa-hands-helping me-1"></i>Gönüllü Ol
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
