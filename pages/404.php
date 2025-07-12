<?php
// 404 Error Page
http_response_code(404);
?>

<div class="error-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="error-content">
                    <div class="error-code">
                        <h1>404</h1>
                        <div class="error-icon">
                            <i class="fas fa-heart-broken"></i>
                        </div>
                    </div>
                    
                    <div class="error-message">
                        <h2>Sayfa Bulunamadı</h2>
                        <p class="lead">Aradığınız sayfa mevcut değil veya taşınmış olabilir.</p>
                        <p>Lütfen web sitemizdeki diğer sayfalara göz atın.</p>
                    </div>
                    
                    <div class="error-actions">
                        <a href="index.php" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-home me-2"></i>Ana Sayfa
                        </a>
                        <a href="index.php?page=contact" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-envelope me-2"></i>İletişim
                        </a>
                    </div>
                    
                    <div class="error-search mt-4 mb-4">
                        <form action="index.php" method="get" class="search-form">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Ne aramıştınız?">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="quick-links mt-5">
                        <h4>Popüler Sayfalar</h4>
                        <div class="row">
                            <div class="col-md-3 col-6 mb-3">
                                <a href="index.php?page=about" class="quick-link">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Hakkımızda</span>
                                </a>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <a href="index.php?page=donate" class="quick-link">
                                    <i class="fas fa-hand-holding-heart"></i>
                                    <span>Bağış Yap</span>
                                </a>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <a href="index.php?page=volunteer" class="quick-link">
                                    <i class="fas fa-users"></i>
                                    <span>Gönüllü Ol</span>
                                </a>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <a href="index.php?page=projects" class="quick-link">
                                    <i class="fas fa-project-diagram"></i>
                                    <span>Projeler</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    min-height: 80vh;
    display: flex;
    align-items: center;
    padding: 4rem 0;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.error-content {
    padding: 2rem;
}

.error-code {
    position: relative;
    margin-bottom: 2rem;
}

.error-code h1 {
    font-size: 8rem;
    font-weight: 900;
    color: #e74c3c;
    margin: 0;
    text-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.error-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0.1;
}

.error-icon i {
    font-size: 6rem;
    color: #e74c3c;
}

.error-message h2 {
    color: #2c3e50;
    margin-bottom: 1rem;
}

.error-message p {
    color: #7f8c8d;
    margin-bottom: 1rem;
}

.search-form {
    max-width: 500px;
    margin: 0 auto;
}

.quick-links {
    margin-top: 3rem;
}

.quick-link {
    display: block;
    padding: 1.5rem;
    background: white;
    border-radius: 15px;
    text-decoration: none;
    color: #2c3e50;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    text-align: center;
}

.quick-link:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    color: #3498db;
    text-decoration: none;
}

.quick-link i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

.quick-link span {
    font-weight: 600;
}

@media (max-width: 768px) {
    .error-code h1 {
        font-size: 6rem;
    }
    
    .error-icon i {
        font-size: 4rem;
    }
    
    .error-actions .btn {
        margin-bottom: 1rem;
        width: 100%;
    }
}
</style>

<script>
// Add some animation to the 404 number
document.addEventListener('DOMContentLoaded', function() {
    const errorCode = document.querySelector('.error-code h1');
    if (errorCode) {
        errorCode.style.opacity = '0';
        errorCode.style.transform = 'scale(0.5)';
        
        setTimeout(() => {
            errorCode.style.transition = 'all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            errorCode.style.opacity = '1';
            errorCode.style.transform = 'scale(1)';
        }, 200);
    }
    
    // Animate quick links
    const quickLinks = document.querySelectorAll('.quick-link');
    quickLinks.forEach((link, index) => {
        link.style.opacity = '0';
        link.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            link.style.transition = 'all 0.6s ease';
            link.style.opacity = '1';
            link.style.transform = 'translateY(0)';
        }, 600 + (index * 100));
    });
});
</script>
