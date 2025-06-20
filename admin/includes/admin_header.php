<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli - Necat Derneği</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h4><i class="fas fa-heart text-danger"></i> Necat Derneği</h4>
                <p class="text-muted">Admin Panel</p>
            </div>
            
            <ul class="list-unstyled components">
                <li class="<?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                    <a href="?page=dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                
                <li class="<?php echo $page === 'donations' ? 'active' : ''; ?>">
                    <a href="?page=donations">
                        <i class="fas fa-hand-holding-heart"></i> Bağışlar
                    </a>
                </li>
                
                <li class="<?php echo $page === 'volunteers' ? 'active' : ''; ?>">
                    <a href="?page=volunteers">
                        <i class="fas fa-users"></i> Gönüllüler
                    </a>
                </li>
                
                <li class="<?php echo $page === 'projects' ? 'active' : ''; ?>">
                    <a href="?page=projects">
                        <i class="fas fa-project-diagram"></i> Projeler
                    </a>
                </li>
                
                <li class="<?php echo $page === 'projects_hero' ? 'active' : ''; ?>">
                    <a href="?page=projects_hero">
                        <i class="fas fa-cog"></i> Projeler Sayfası Ayarları
                    </a>
                </li>
                
                <li class="<?php echo $page === 'news' ? 'active' : ''; ?>">
                    <a href="?page=news">
                        <i class="fas fa-newspaper"></i> Haberler
                    </a>
                </li>
                
                <li class="<?php echo $page === 'messages' ? 'active' : ''; ?>">
                    <a href="?page=messages">
                        <i class="fas fa-envelope"></i> Mesajlar
                    </a>
                </li>
                
                <li class="<?php echo $page === 'contact_cards' ? 'active' : ''; ?>">
                    <a href="?page=contact_cards">
                        <i class="fas fa-address-card"></i> İletişim Kartları
                    </a>
                </li>
                
                <li class="<?php echo $page === 'users' ? 'active' : ''; ?>">
                    <a href="?page=users">
                        <i class="fas fa-user-cog"></i> Kullanıcılar
                    </a>
                </li>
                
                <li class="<?php echo $page === 'file_manager' ? 'active' : ''; ?>">
                    <a href="?page=file_manager">
                        <i class="fas fa-folder-open"></i> Dosya Yöneticisi
                    </a>
                </li>
                
                <li class="<?php echo $page === 'logs' ? 'active' : ''; ?>">
                    <a href="?page=logs">
                        <i class="fas fa-clipboard-list"></i> Admin Logları
                    </a>
                </li>
                
                <li class="<?php echo $page === 'security' ? 'active' : ''; ?>">
                    <a href="?page=security">
                        <i class="fas fa-shield-alt"></i> Güvenlik
                    </a>
                </li>
                
                <li class="<?php echo $page === 'settings' ? 'active' : ''; ?>">
                    <a href="?page=settings">
                        <i class="fas fa-cog"></i> Ayarlar
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Page Content -->
        <div id="content" class="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-outline-primary">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="navbar-nav ms-auto">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" 
                               id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-2"></i>
                                <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="?page=settings">
                                    <i class="fas fa-cog me-2"></i>Ayarlar
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="?page=logout">
                                    <i class="fas fa-sign-out-alt me-2"></i>Çıkış Yap
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content -->
            <div class="container-fluid py-4"><?php
