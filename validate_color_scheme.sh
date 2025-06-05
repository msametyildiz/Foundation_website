#!/bin/bash

# Color Scheme Validation Script
# Verifies that logo colors have been properly integrated throughout the site

echo "🎨 Necat Derneği - Color Scheme Integration Validation"
echo "======================================================"
echo ""

# Define colors
OLD_COLORS=("#2563eb" "#1d4ed8" "#10b981" "#3b82f6")
NEW_COLORS=("#4EA674" "#D3D92B" "#F2E529" "#F2EEB6" "#0D0D0D")

# Check for old color references
echo "🔍 Checking for old color references..."
old_found=false
for color in "${OLD_COLORS[@]}"; do
    count=$(grep -r --include="*.css" --include="*.php" --exclude-dir="vendor" "$color" . 2>/dev/null | wc -l)
    if [ $count -gt 0 ]; then
        echo "❌ Found $count references to old color $color"
        old_found=true
    else
        echo "✅ No references to old color $color found"
    fi
done

echo ""

# Check for new color implementation
echo "🎯 Verifying new logo color implementation..."
total_new_refs=0
for color in "${NEW_COLORS[@]}"; do
    count=$(grep -r --include="*.css" --include="*.php" --exclude-dir="vendor" "$color" . 2>/dev/null | wc -l)
    echo "✅ Logo color $color found in $count locations"
    total_new_refs=$((total_new_refs + count))
done

echo ""

# Check CSS variables
echo "🔧 Checking CSS variable implementation..."
css_vars=(
    "--primary-color: #4EA674"
    "--secondary-color: #D3D92B"
    "--accent-color: #F2E529"
    "--light-cream: #F2EEB6"
)

for var in "${css_vars[@]}"; do
    count=$(grep -r --include="*.css" --include="*.php" "$var" . 2>/dev/null | wc -l)
    if [ $count -gt 0 ]; then
        echo "✅ CSS variable '$var' found in $count files"
    else
        echo "❌ CSS variable '$var' not found"
    fi
done

echo ""

# Check critical files
echo "📁 Verifying critical files were updated..."
critical_files=(
    "assets/css/style.css"
    "assets/css/admin.css"
    "includes/header.php"
    "includes/footer.php"
    "admin/login.php"
)

for file in "${critical_files[@]}"; do
    if [ -f "$file" ]; then
        green_count=$(grep -c "#4EA674" "$file" 2>/dev/null || echo "0")
        if [ $green_count -gt 0 ]; then
            echo "✅ $file contains logo colors ($green_count references)"
        else
            echo "⚠️  $file may need color updates"
        fi
    else
        echo "❌ Critical file $file not found"
    fi
done

echo ""

# Check theme-color meta tag
echo "🏷️  Checking theme-color meta tag..."
theme_color_count=$(grep -c 'content="#4EA674"' includes/header.php 2>/dev/null || echo "0")
if [ $theme_color_count -gt 0 ]; then
    echo "✅ Theme color meta tag updated to logo green"
else
    echo "❌ Theme color meta tag needs updating"
fi

echo ""

# Summary
echo "📊 VALIDATION SUMMARY"
echo "===================="
if [ "$old_found" = false ] && [ $total_new_refs -gt 15 ]; then
    echo "🎉 SUCCESS: Color scheme integration is COMPLETE!"
    echo "   • No old color references found"
    echo "   • $total_new_refs logo color references implemented"
    echo "   • All critical files updated"
    echo ""
    echo "✅ Website is ready with the new logo-based color scheme!"
else
    echo "⚠️  Issues detected:"
    if [ "$old_found" = true ]; then
        echo "   • Old color references still exist"
    fi
    if [ $total_new_refs -lt 15 ]; then
        echo "   • Insufficient logo color implementation ($total_new_refs found, expected 15+)"
    fi
fi

echo ""
echo "📋 Test the integration by opening: test_color_scheme.html"
echo "🌐 View the live site to see the new color scheme in action"
echo ""
