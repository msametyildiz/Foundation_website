<?php
// Get logo as base64 for email templates
$logoPath = 'assets/images/logo.png';
if (file_exists($logoPath)) {
    $logoData = base64_encode(file_get_contents($logoPath));
    $logoMimeType = 'image/png';
    $logoBase64 = 'data:' . $logoMimeType . ';base64,' . $logoData;
    
    echo "Logo Base64 (first 100 chars): " . substr($logoBase64, 0, 100) . "...\n";
    echo "Full length: " . strlen($logoBase64) . " characters\n";
    echo "Logo file size: " . filesize($logoPath) . " bytes\n";
    
    // Save to a file for easy copying
    file_put_contents('logo_base64.txt', $logoBase64);
    echo "Full base64 saved to logo_base64.txt\n";
} else {
    echo "Logo file not found: $logoPath\n";
}
?>
