<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div style="background: red; height: 200px; color: white; padding: 20px;">
        <h1>Test Content - Bu görünüyor mu?</h1>
        <p>Eğer bu metin görünüyorsa CSS sorunu var</p>
    </div>
    
    <section class="hero-modern" style="background: #4EA674 !important; height: 400px;">
        <div class="container">
            <h1 style="color: white; padding: 50px;">Hero Section Test</h1>
        </div>
    </section>
</body>
</html>
