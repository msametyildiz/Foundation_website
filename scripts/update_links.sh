#!/bin/bash
# Script to update all index.php?page= links to cleaner URLs

echo "Updating links in pages directory..."
find ./pages -type f -name "*.php" -exec sed -i 's/href="index\.php?page=\([^"]*\)"/href="<?= site_url(\x27\1\x27) ?>"/g' {} \;
find ./pages -type f -name "*.php" -exec sed -i 's/href="\.\/index\.php?page=\([^"]*\)"/href="<?= site_url(\x27\1\x27) ?>"/g' {} \;
find ./pages -type f -name "*.php" -exec sed -i 's/href="\/index\.php?page=\([^"]*\)"/href="<?= site_url(\x27\1\x27) ?>"/g' {} \;

echo "Updating links in admin pages..."
find ./admin/pages -type f -name "*.php" -exec sed -i 's/href="\.\.\/index\.php?page=\([^"]*\)"/href="<?= site_url(\x27\1\x27) ?>"/g' {} \;

echo "Updating links in ajax scripts..."
find ./ajax -type f -name "*.php" -exec sed -i 's/header("Location: \.\.\/index\.php?page=\([^"]*\)")/header("Location: " . site_url(\x27\1\x27))/g' {} \;

echo "Updating links in scripts directory..."
find ./scripts -type f -name "*.php" -exec sed -i 's/href="index\.php?page=\([^"]*\)"/href="<?= site_url(\x27\1\x27) ?>"/g' {} \;
find ./scripts -type f -name "*.php" -exec sed -i 's/href="\.\/index\.php?page=\([^"]*\)"/href="<?= site_url(\x27\1\x27) ?>"/g' {} \;
find ./scripts -type f -name "*.php" -exec sed -i 's/href="\/index\.php?page=\([^"]*\)"/href="<?= site_url(\x27\1\x27) ?>"/g' {} \;

echo "Updating JS redirects..."
find . -type f -name "*.js" -exec sed -i 's/window\.location\.href = "index\.php?page=\([^"]*\)"/window.location.href = "\1"/g' {} \;
find . -type f -name "*.js" -exec sed -i 's/window\.location\.href = "\.\/index\.php?page=\([^"]*\)"/window.location.href = "\1"/g' {} \;
find . -type f -name "*.js" -exec sed -i 's/window\.location\.href = "\/index\.php?page=\([^"]*\)"/window.location.href = "\/\1"/g' {} \;

echo "Updating location redirects in PHP files..."
find . -type f -name "*.php" -exec sed -i 's/header("Location: index\.php?page=\([^"]*\)")/header("Location: " . site_url(\x27\1\x27))/g' {} \;
find . -type f -name "*.php" -exec sed -i 's/header("Location: \.\/index\.php?page=\([^"]*\)")/header("Location: " . site_url(\x27\1\x27))/g' {} \;
find . -type f -name "*.php" -exec sed -i 's/header("Location: \/index\.php?page=\([^"]*\)")/header("Location: " . site_url(\x27\1\x27))/g' {} \;

echo "Links updated successfully!" 