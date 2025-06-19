<?php
require_once 'includes/logo-base64-helper.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base64 Logo Test - Necat Derneği</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            background: #f8f9fa; 
            padding: 50px; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .test-container { 
            max-width: 1000px; 
            margin: 0 auto; 
        }
        .test-section { 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            margin: 20px 0; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
        }
        .hero-badge { 
            background: linear-gradient(135deg, #4ea674, #45a049); 
            color: white; 
            padding: 12px 24px; 
            border-radius: 25px; 
            font-size: 16px; 
            display: inline-flex; 
            align-items: center; 
            gap: 8px;
        }
        .navbar-demo {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .email-demo {
            background: #4ea674;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 15px;
            margin: 20px 0;
        }
        .comparison-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .logo-box {
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
            text-align: center;
            background: white;
        }
        .info-badge {
            background: #e3f2fd;
            color: #1565c0;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
            margin: 5px;
        }
        .success-badge {
            background: #e8f5e8;
            color: #2e7d32;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
            margin: 5px;
        }
        .error-badge {
            background: #ffebee;
            color: #c62828;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="test-container">
            <!-- Header -->
            <div class="test-section">
                <h1 class="text-center mb-4">
                    <i class="fas fa-image text-primary"></i>
                    Base64 Logo Integration Test
                </h1>
                <p class="text-center text-muted">Testing the base64 logo implementation across different contexts</p>
                
                <!-- Logo Status -->
                <div class="text-center mt-4">
                    <?php
                    $logoInfo = LogoBase64Helper::getLogoInfo();
                    if ($logoInfo['available']) {
                        echo '<span class="success-badge"><i class="fas fa-check"></i> Base64 Logo Available</span>';
                        echo '<span class="info-badge">Size: ' . number_format($logoInfo['size']) . ' characters</span>';
                    } else {
                        echo '<span class="error-badge"><i class="fas fa-times"></i> Base64 Logo Not Available</span>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Navbar Demo -->
            <div class="test-section">
                <h3><i class="fas fa-bars"></i> 1. Navbar Logo Test</h3>
                <p>Testing how the base64 logo appears in navigation bar context:</p>
                
                <div class="navbar-demo">
                    <?php echo LogoBase64Helper::getLogoForNavbar(); ?>
                    <div>
                        <strong>Necat Derneği</strong>
                        <small class="text-muted d-block">Birlikte Güçlüyüz</small>
                    </div>
                </div>
            </div>
            
            <!-- Hero Section Demo -->
            <div class="test-section">
                <h3><i class="fas fa-star"></i> 2. Hero Badge Test</h3>
                <p>Testing logo in hero section badge:</p>
                
                <div class="text-center">
                    <span class="hero-badge">
                        <?php echo LogoBase64Helper::getLogoImg(['style' => 'height: 24px; width: auto;']); ?>
                        Birlikte Güçlüyüz
                    </span>
                </div>
            </div>
            
            <!-- Email Template Demo -->
            <div class="test-section">
                <h3><i class="fas fa-envelope"></i> 3. Email Template Test</h3>
                <p>Testing logo for email templates:</p>
                
                <div class="email-demo">
                    <?php echo LogoBase64Helper::getLogoForEmail(); ?>
                    <h4 style="margin: 15px 0 10px 0;">Yeni Gönüllü Başvurusu</h4>
                    <p style="margin: 0;">Necat Derneği Gönüllü Başvuru Sistemi</p>
                </div>
            </div>
            
            <!-- Comparison Test -->
            <div class="test-section">
                <h3><i class="fas fa-balance-scale"></i> 4. Comparison Test</h3>
                <p>Comparing base64 logo with regular file:</p>
                
                <div class="comparison-grid">
                    <div class="logo-box">
                        <h5>Base64 Logo</h5>
                        <?php echo LogoBase64Helper::getLogoImg(['style' => 'max-height: 80px; width: auto;']); ?>
                        <small class="d-block mt-2 text-muted">Embedded in page</small>
                    </div>
                    <div class="logo-box">
                        <h5>Regular File Logo</h5>
                        <img src="assets/images/logo.png" alt="Regular Logo" style="max-height: 80px; width: auto;">
                        <small class="d-block mt-2 text-muted">External file</small>
                    </div>
                </div>
            </div>
            
            <!-- Technical Info -->
            <div class="test-section">
                <h3><i class="fas fa-info-circle"></i> 5. Technical Information</h3>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Logo Status:</h5>
                        <ul class="list-unstyled">
                            <?php
                            $logoInfo = LogoBase64Helper::getLogoInfo();
                            foreach ($logoInfo as $key => $value) {
                                echo '<li><strong>' . ucwords(str_replace('_', ' ', $key)) . ':</strong> ';
                                if (is_bool($value)) {
                                    echo $value ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>';
                                } else {
                                    echo htmlspecialchars($value);
                                }
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>JavaScript Integration:</h5>
                        <button class="btn btn-primary" onclick="testJavaScript()">Test JS Logo Manager</button>
                        <div id="jsResult" class="mt-3"></div>
                    </div>
                </div>
            </div>
            
            <!-- Dynamic Logo Test -->
            <div class="test-section">
                <h3><i class="fas fa-magic"></i> 6. Dynamic Logo Test</h3>
                <p>Testing dynamic logo replacement with JavaScript:</p>
                
                <button class="btn btn-success" onclick="addDynamicLogo()">Add Dynamic Logo</button>
                <button class="btn btn-warning" onclick="replaceLogo()">Replace All Logos</button>
                <div id="dynamicLogoContainer" class="mt-3"></div>
            </div>
        </div>
    </div>
    
    <!-- Include the base64 logo JavaScript -->
    <script src="assets/js/logo-base64.js"></script>
    
    <script>
        function testJavaScript() {
            const result = document.getElementById('jsResult');
            
            if (window.logoManager) {
                window.logoManager.onReady((logoUrl) => {
                    result.innerHTML = `
                        <div class="alert alert-success">
                            <strong>JavaScript Integration Working!</strong><br>
                            Logo URL Length: ${logoUrl.length} characters<br>
                            Logo Type: ${logoUrl.startsWith('data:') ? 'Base64' : 'File'}
                        </div>
                    `;
                });
            } else {
                result.innerHTML = `
                    <div class="alert alert-danger">
                        <strong>JavaScript Integration Failed!</strong><br>
                        LogoManager not found.
                    </div>
                `;
            }
        }
        
        function addDynamicLogo() {
            const container = document.getElementById('dynamicLogoContainer');
            
            if (window.logoManager) {
                const logoElement = window.logoManager.createLogoElement({
                    height: '60px',
                    className: 'border rounded p-2 m-2'
                });
                
                const wrapper = document.createElement('div');
                wrapper.className = 'alert alert-info d-inline-block';
                wrapper.innerHTML = '<strong>Dynamic Logo:</strong> ';
                wrapper.appendChild(logoElement);
                
                container.appendChild(wrapper);
            } else {
                container.innerHTML = '<div class="alert alert-warning">LogoManager not available</div>';
            }
        }
        
        function replaceLogo() {
            if (window.logoManager) {
                // Force replace all logos
                window.logoManager.replaceLogoElements();
                
                const result = document.createElement('div');
                result.className = 'alert alert-success mt-2';
                result.innerHTML = '<i class="fas fa-check"></i> All logos have been replaced with base64 version!';
                document.getElementById('dynamicLogoContainer').appendChild(result);
            }
        }
        
        // Test on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                console.log('Page loaded, testing logo manager...');
                if (window.logoManager) {
                    console.log('✓ LogoManager is available');
                    console.log('✓ Logo URL:', window.logoManager.getLogoDataUrl());
                } else {
                    console.log('✗ LogoManager not found');
                }
            }, 1000);
        });
    </script>
</body>
</html>
