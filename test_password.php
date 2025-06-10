<?php
// Generate password hash for admin login
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";

// Test verification
echo "Verification: " . (password_verify($password, $hash) ? 'Success' : 'Failed') . "\n";
?>
