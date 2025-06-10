#!/bin/bash

# Necat Derneği Website - Final Deployment Script
# This script prepares the application for production deployment

echo "🚀 Necat Derneği Website - Final Deployment Script"
echo "================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Step 1: Check environment
echo ""
echo "Step 1: Environment Check"
echo "========================"

# Check if we're in the right directory
if [ ! -f "composer.json" ]; then
    print_error "composer.json not found. Please run this script from the project root directory."
    exit 1
fi

print_status "Found composer.json"

# Check PHP version
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
print_info "PHP Version: $PHP_VERSION"

# Check MySQL connection
if mysql -u necat_user -p'necat_password123' -e "USE necat_dernegi; SELECT 1;" &> /dev/null; then
    print_status "Database connection successful"
else
    print_error "Database connection failed"
    exit 1
fi

# Step 2: Set proper file permissions
echo ""
echo "Step 2: Setting File Permissions"
echo "==============================="

# Set directory permissions
find . -type d -exec chmod 755 {} \;
print_status "Set directory permissions to 755"

# Set file permissions
find . -type f -exec chmod 644 {} \;
print_status "Set file permissions to 644"

# Make specific directories writable
chmod -R 777 uploads/
chmod -R 777 backups/
print_status "Set uploads and backups directories to 777"

# Make admin scripts executable if needed
chmod 644 admin/*.php
chmod 644 ajax/*.php
print_status "Set admin and ajax file permissions"

# Step 3: Security hardening
echo ""
echo "Step 3: Security Hardening"
echo "========================="

# Create .htaccess for uploads directory
cat > uploads/.htaccess << EOF
# Prevent direct access to uploaded files
Options -Indexes
<Files "*.php">
    Order Deny,Allow
    Deny from All
</Files>
EOF
print_status "Created uploads/.htaccess for security"

# Create .htaccess for backups directory
cat > backups/.htaccess << EOF
# Prevent access to backup files
Order Deny,Allow
Deny from All
EOF
print_status "Created backups/.htaccess for security"

# Create main .htaccess if it doesn't exist
if [ ! -f ".htaccess" ]; then
cat > .htaccess << EOF
# Main .htaccess for Necat Derneği Website

# Security Headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; img-src 'self' data:; font-src 'self' https://cdnjs.cloudflare.com;"

# Hide sensitive files
<Files "*.sql">
    Order Deny,Allow
    Deny from All
</Files>

<Files "composer.*">
    Order Deny,Allow
    Deny from All
</Files>

<Files "*.md">
    Order Deny,Allow
    Deny from All
</Files>

# Prevent access to config files
<Files "database.php">
    Order Deny,Allow
    Deny from All
</Files>

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Enable browser caching
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType text/javascript "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>

# URL Rewriting for clean URLs
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([^/]+)/?$ index.php?page=$1 [L,QSA]
EOF
print_status "Created main .htaccess file"
fi

# Step 4: Update configuration for production
echo ""
echo "Step 4: Production Configuration"
echo "==============================="

print_warning "Remember to update the following for production:"
echo "  - config/database.php: Update database credentials"
echo "  - config/database.php: Update SITE_URL to your domain"
echo "  - config/database.php: Update SECRET_KEY"
echo "  - config/database.php: Update email settings"

# Step 5: Final security scan
echo ""
echo "Step 5: Final Security Scan"
echo "=========================="

# Check for potential security issues
SECURITY_ISSUES=0

# Check for test files that should be removed in production
if [ -f "test_setup.php" ]; then
    print_warning "Remove test_setup.php before deployment"
    SECURITY_ISSUES=$((SECURITY_ISSUES+1))
fi

if [ -f "comprehensive_test.php" ]; then
    print_warning "Remove comprehensive_test.php before deployment"
    SECURITY_ISSUES=$((SECURITY_ISSUES+1))
fi

if [ -f "test_password.php" ]; then
    print_warning "Remove test_password.php before deployment"
    SECURITY_ISSUES=$((SECURITY_ISSUES+1))
fi

# Check config file
if grep -q "your_secret_key_here" config/database.php; then
    print_warning "Update SECRET_KEY in config/database.php"
    SECURITY_ISSUES=$((SECURITY_ISSUES+1))
fi

if grep -q "localhost:8080" config/database.php; then
    print_warning "Update SITE_URL in config/database.php for production"
    SECURITY_ISSUES=$((SECURITY_ISSUES+1))
fi

# Step 6: Create deployment checklist
echo ""
echo "Step 6: Creating Deployment Checklist"
echo "====================================="

cat > DEPLOYMENT_CHECKLIST.md << EOF
# Necat Derneği Website - Deployment Checklist

## Pre-Deployment Steps

### 1. Database Configuration
- [ ] Update database credentials in \`config/database.php\`
- [ ] Create production database
- [ ] Import database structure from \`database/necat_dernegi.sql\`
- [ ] Import admin logs table from \`sql/admin_logs.sql\`
- [ ] Create admin user account
- [ ] Test database connection

### 2. File Configuration
- [ ] Update \`SITE_URL\` in \`config/database.php\`
- [ ] Generate new \`SECRET_KEY\` in \`config/database.php\`
- [ ] Configure email settings for production
- [ ] Remove test files (test_setup.php, comprehensive_test.php, etc.)

### 3. Security Setup
- [ ] SSL certificate installed and configured
- [ ] Update security headers in .htaccess
- [ ] Set proper file permissions (directories: 755, files: 644)
- [ ] Make uploads and backups directories writable (777)
- [ ] Verify .htaccess files in uploads/ and backups/ directories

### 4. Email Configuration
- [ ] Configure SMTP settings in EmailService
- [ ] Test email functionality
- [ ] Verify contact form emails are working
- [ ] Check donation confirmation emails

### 5. Performance Optimization
- [ ] Enable caching in production
- [ ] Optimize images in uploads/images/
- [ ] Configure server-level caching (if available)
- [ ] Test page load speeds

### 6. Final Testing
- [ ] Test admin login functionality
- [ ] Test all admin pages and features
- [ ] Test donation system
- [ ] Test volunteer application form
- [ ] Test contact form
- [ ] Verify all AJAX functionality
- [ ] Test security features

### 7. Backup System
- [ ] Set up automated database backups
- [ ] Test backup and restore procedures
- [ ] Configure log rotation for admin_logs table

## Post-Deployment Steps

### 1. Monitoring
- [ ] Set up error logging and monitoring
- [ ] Configure security scan automation
- [ ] Monitor admin logs for suspicious activity

### 2. Content Management
- [ ] Add initial content (projects, news, team members)
- [ ] Upload organization documents
- [ ] Configure donation types and IBAN accounts

### 3. SEO and Analytics
- [ ] Submit sitemap to search engines
- [ ] Set up Google Analytics (if desired)
- [ ] Verify meta tags and SEO elements

## Admin Login Credentials
- Username: admin
- Password: admin123 (CHANGE THIS!)

## Important URLs
- Admin Panel: /admin/
- Security Dashboard: /admin/?page=security
- Database Backup: Available through admin panel

## Support Information
- PHP Version Required: 8.1+
- MySQL Version Required: 8.0+
- Required PHP Extensions: pdo, pdo_mysql, curl, mbstring, xml, zip, gd, openssl, json, fileinfo
EOF

print_status "Created DEPLOYMENT_CHECKLIST.md"

# Step 7: Create maintenance script
echo ""
echo "Step 7: Creating Maintenance Script"
echo "=================================="

cat > maintenance.php << EOF
<?php
// maintenance.php - System maintenance script
// Run this script periodically to maintain the system

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>System Maintenance</h1>\n";

require_once 'config/database.php';
require_once 'includes/AdminLogger.php';
require_once 'includes/PerformanceOptimizer.php';

// 1. Clean old logs (older than 6 months)
try {
    \$stmt = \$pdo->prepare("DELETE FROM admin_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH)");
    \$stmt->execute();
    \$deleted = \$stmt->rowCount();
    echo "✅ Cleaned \$deleted old log entries<br>\n";
} catch (Exception \$e) {
    echo "❌ Log cleanup failed: " . \$e->getMessage() . "<br>\n";
}

// 2. Clear old cache files
try {
    \$optimizer = new PerformanceOptimizer();
    \$optimizer->clearOldCache();
    echo "✅ Cleared old cache files<br>\n";
} catch (Exception \$e) {
    echo "❌ Cache cleanup failed: " . \$e->getMessage() . "<br>\n";
}

// 3. Clean temporary files
\$temp_files = glob('*.tmp');
foreach (\$temp_files as \$file) {
    if (unlink(\$file)) {
        echo "✅ Deleted temporary file: \$file<br>\n";
    }
}

// 4. Database optimization
try {
    \$tables = ['admin_logs', 'donations', 'contact_messages', 'volunteer_applications'];
    foreach (\$tables as \$table) {
        \$pdo->exec("OPTIMIZE TABLE \$table");
        echo "✅ Optimized table: \$table<br>\n";
    }
} catch (Exception \$e) {
    echo "❌ Database optimization failed: " . \$e->getMessage() . "<br>\n";
}

echo "<p><strong>Maintenance completed at:</strong> " . date('Y-m-d H:i:s') . "</p>\n";
?>
EOF

print_status "Created maintenance.php script"

# Final summary
echo ""
echo "🎉 Deployment Preparation Complete!"
echo "=================================="

print_info "Summary of actions completed:"
echo "  ✅ Environment checked"
echo "  ✅ File permissions set"
echo "  ✅ Security hardening applied"
echo "  ✅ .htaccess files created"
echo "  ✅ Deployment checklist created"
echo "  ✅ Maintenance script created"

if [ $SECURITY_ISSUES -gt 0 ]; then
    print_warning "Found $SECURITY_ISSUES security issues that need attention"
    echo "  Please review the warnings above before deployment"
fi

echo ""
print_info "Next steps:"
echo "  1. Review DEPLOYMENT_CHECKLIST.md"
echo "  2. Update configuration files for production"
echo "  3. Remove test files"
echo "  4. Upload to production server"
echo "  5. Test all functionality"

echo ""
print_info "The application is now ready for production deployment!"
