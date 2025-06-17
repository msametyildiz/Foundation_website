<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logo Test - Necat Derneği</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 50px; }
        .test-container { max-width: 800px; margin: 0 auto; }
        .test-section { background: white; padding: 30px; border-radius: 10px; margin: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .hero-badge { background: linear-gradient(135deg, #4ea674, #45a049); color: white; padding: 8px 16px; border-radius: 20px; font-size: 14px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="test-container">
            <div class="test-section">
                <h2>🧪 Logo Integration Test</h2>
                <p>Testing logo replacement in different contexts...</p>
            </div>
            
            <div class="test-section">
                <h3>1. Hero Badge with Logo</h3>
                <span class="hero-badge">
                    <img src="assets/images/logo.png" alt="Necat Derneği" style="height: 20px; margin-right: 8px; vertical-align: middle;">
                    Birlikte Güçlüyüz
                </span>
                <p class="mt-3">This simulates how the logo looks in the home page hero section.</p>
            </div>
            
            <div class="test-section">
                <h3>2. Email Header with Logo</h3>
                <div style="background: #4ea674; color: white; padding: 20px; text-align: center; border-radius: 10px;">
                    <img src="assets/images/logo.png" alt="Necat Derneği Logo" style="height: 50px; margin-bottom: 10px;">
                    <h4 style="margin: 0;">Yeni Gönüllü Başvurusu</h4>
                    <p style="margin: 5px 0 0 0;">Necat Derneği Gönüllü Başvuru Sistemi</p>
                </div>
                <p class="mt-3">This simulates how the logo looks in email notifications.</p>
            </div>
            
            <div class="test-section">
                <h3>3. Logo Files Available</h3>
                <div class="row">
                    <div class="col-md-6">
                        <h5>logo.png</h5>
                        <img src="assets/images/logo.png" alt="Logo 1" style="max-height: 100px; border: 1px solid #ddd; padding: 10px;">
                    </div>
                    <div class="col-md-6">
                        <h5>logo2.png</h5>
                        <img src="assets/images/logo2.png" alt="Logo 2" style="max-height: 100px; border: 1px solid #ddd; padding: 10px;">
                    </div>
                </div>
            </div>
            
            <div class="test-section">
                <h3>4. Navigation Links</h3>
                <div class="d-flex gap-3">
                    <a href="pages/home.php" class="btn btn-primary">View Home Page</a>
                    <a href="test_volunteer_system.php" class="btn btn-success">Test Volunteer System</a>
                    <a href="direct_email_test.php" class="btn btn-info">Test Email</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
