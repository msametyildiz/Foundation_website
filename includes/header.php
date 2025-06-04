<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo clean_output($page_info['title']); ?></title>
    <meta name="description" content="<?php echo clean_output($page_info['description']); ?>">
    <meta name="keywords" content="<?php echo clean_output($page_info['keywords']); ?>">
    
    <!-- Performance and SEO optimizations -->
    <meta name="robots" content="index, follow">
    <meta name="author" content="Necat Derneği">
    <meta name="theme-color" content="#2563eb">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" as="style">
    <link rel="preload" href="assets/css/style.css" as="style">
    
    <!-- DNS prefetch for external resources -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo clean_output($page_info['title']); ?>">
    <meta property="og:description" content="<?php echo clean_output($page_info['description']); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL; ?>">
    <meta property="og:site_name" content="<?php echo SITE_NAME; ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/logo.png" alt="<?php echo SITE_NAME; ?>" height="50">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo is_active_page('home'); ?>" href="index.php">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo is_active_page('about'); ?>" href="index.php?page=about">Hakkımızda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo is_active_page('projects'); ?>" href="index.php?page=projects">Projelerimiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo is_active_page('volunteer'); ?>" href="index.php?page=volunteer">Gönüllü Ol</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo is_active_page('faq'); ?>" href="index.php?page=faq">SSS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo is_active_page('contact'); ?>" href="index.php?page=contact">İletişim</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Kurumsal
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?php echo is_active_page('press'); ?>" href="index.php?page=press">Basında Biz</a></li>
                            <li><a class="dropdown-item <?php echo is_active_page('documents'); ?>" href="index.php?page=documents">Belgelerimiz</a></li>
                            <li><a class="dropdown-item <?php echo is_active_page('team'); ?>" href="index.php?page=team">Yönetim Kurulu</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2 <?php echo is_active_page('donate'); ?>" href="index.php?page=donate">
                            <i class="fas fa-heart"></i> Bağış Yap
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
