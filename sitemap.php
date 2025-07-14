<?php
// Sitemap.php - Dinamik site haritası oluşturur
header('Content-Type: application/xml; charset=utf-8');

// Sayfalar ve öncelikleri
$pages = [
    'home' => ['priority' => '1.00', 'changefreq' => 'daily'],
    'about' => ['priority' => '0.80', 'changefreq' => 'weekly'],
    'projects' => ['priority' => '0.80', 'changefreq' => 'weekly'],
    'donate' => ['priority' => '0.80', 'changefreq' => 'weekly'],
    'volunteer' => ['priority' => '0.80', 'changefreq' => 'weekly'],
    'contact' => ['priority' => '0.70', 'changefreq' => 'monthly'],
    'faq' => ['priority' => '0.70', 'changefreq' => 'monthly'],
];

// Protokol ve domain
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];

// XML başlangıcı
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// Ana sayfa
echo '  <url>' . PHP_EOL;
echo '    <loc>' . $protocol . $domain . '/</loc>' . PHP_EOL;
echo '    <lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL;
echo '    <changefreq>daily</changefreq>' . PHP_EOL;
echo '    <priority>1.00</priority>' . PHP_EOL;
echo '  </url>' . PHP_EOL;

// Diğer sayfalar
foreach ($pages as $page => $details) {
    if ($page === 'home') continue; // Ana sayfayı zaten ekledik
    
    echo '  <url>' . PHP_EOL;
    echo '    <loc>' . $protocol . $domain . '/index.php?page=' . $page . '</loc>' . PHP_EOL;
    echo '    <lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL;
    echo '    <changefreq>' . $details['changefreq'] . '</changefreq>' . PHP_EOL;
    echo '    <priority>' . $details['priority'] . '</priority>' . PHP_EOL;
    echo '  </url>' . PHP_EOL;
}

// XML kapanışı
echo '</urlset>';
?> 