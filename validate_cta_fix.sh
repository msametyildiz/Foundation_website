#!/bin/bash

echo "=== CTA SECTION FIX VALIDATION ==="
echo "Date: $(date)"
echo ""

echo "1. Checking CSS utility classes..."
if grep -q "\.bg-gradient-primary {" /home/muhammed-samet-yildiz/necat_dernegi_site/assets/css/style.css; then
    echo "✅ bg-gradient-primary utility class found"
else
    echo "❌ bg-gradient-primary utility class missing"
fi

if grep -q "\.bg-gradient-secondary {" /home/muhammed-samet-yildiz/necat_dernegi_site/assets/css/style.css; then
    echo "✅ bg-gradient-secondary utility class found"
else
    echo "❌ bg-gradient-secondary utility class missing"
fi

if grep -q "\.bg-gradient-success {" /home/muhammed-samet-yildiz/necat_dernegi_site/assets/css/style.css; then
    echo "✅ bg-gradient-success utility class found"
else
    echo "❌ bg-gradient-success utility class missing"
fi

if grep -q "\.bg-gradient-info {" /home/muhammed-samet-yildiz/necat_dernegi_site/assets/css/style.css; then
    echo "✅ bg-gradient-info utility class found"
else
    echo "❌ bg-gradient-info utility class missing"
fi

echo ""
echo "2. Checking gradient variable definitions..."
if grep -q "--gradient-primary:" /home/muhammed-samet-yildiz/necat_dernegi_site/assets/css/style.css; then
    echo "✅ --gradient-primary variable found"
else
    echo "❌ --gradient-primary variable missing"
fi

if grep -q "--gradient-secondary:" /home/muhammed-samet-yildiz/necat_dernegi_site/assets/css/style.css; then
    echo "✅ --gradient-secondary variable found"
else
    echo "❌ --gradient-secondary variable missing"
fi

if grep -q "--gradient-cta:" /home/muhammed-samet-yildiz/necat_dernegi_site/assets/css/style.css; then
    echo "✅ --gradient-cta variable found"
else
    echo "❌ --gradient-cta variable missing"
fi

echo ""
echo "3. Checking usage in HTML files..."
bg_gradient_usage=$(find /home/muhammed-samet-yildiz/necat_dernegi_site -name "*.php" -o -name "*.html" | xargs grep -l "bg-gradient" | wc -l)
echo "✅ Found bg-gradient classes used in $bg_gradient_usage file(s)"

echo ""
echo "4. Checking CTA section specifically..."
if grep -q "bg-gradient-primary" /home/muhammed-samet-yildiz/necat_dernegi_site/pages/home.php; then
    echo "✅ CTA section uses bg-gradient-primary class"
else
    echo "❌ CTA section missing bg-gradient-primary class"
fi

echo ""
echo "5. Testing server accessibility..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 | grep -q "200"; then
    echo "✅ Server is accessible at localhost:8000"
else
    echo "❌ Server not accessible"
fi

echo ""
echo "=== VALIDATION COMPLETE ==="
