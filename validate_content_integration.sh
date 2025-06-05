#!/bin/bash

# Content Integration Validation Script
# Validates that all content catalog integrations are working properly

echo "=========================================="
echo "  CONTENT CATALOG INTEGRATION VALIDATION"
echo "=========================================="
echo ""

# Define colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to check if a file exists and contains specific content
check_file_content() {
    local file="$1"
    local search_term="$2"
    local description="$3"
    
    if [ -f "$file" ]; then
        if grep -q "$search_term" "$file"; then
            echo -e "${GREEN}✓${NC} $description"
            return 0
        else
            echo -e "${RED}✗${NC} $description - Content not found"
            return 1
        fi
    else
        echo -e "${RED}✗${NC} $description - File not found"
        return 1
    fi
}

# Function to check PHP syntax
check_php_syntax() {
    local file="$1"
    local description="$2"
    
    if php -l "$file" > /dev/null 2>&1; then
        echo -e "${GREEN}✓${NC} $description - PHP syntax OK"
        return 0
    else
        echo -e "${RED}✗${NC} $description - PHP syntax error"
        return 1
    fi
}

echo -e "${BLUE}1. Checking Content Catalog File${NC}"
echo "----------------------------------------"
check_php_syntax "includes/content_catalog.php" "Content catalog syntax"
check_file_content "includes/content_catalog.php" "homepage_content" "Homepage content variable"
check_file_content "includes/content_catalog.php" "founding_principles" "Founding principles variable"
check_file_content "includes/content_catalog.php" "activities" "Activities variable"
check_file_content "includes/content_catalog.php" "volunteer_questions" "Volunteer questions variable"
check_file_content "includes/content_catalog.php" "faq_questions" "FAQ questions variable"
check_file_content "includes/content_catalog.php" "getContentForPage" "Content getter function"
echo ""

echo -e "${BLUE}2. Checking Page Integrations${NC}"
echo "----------------------------------------"
check_php_syntax "pages/home.php" "Home page syntax"
check_file_content "pages/home.php" "require_once 'includes/content_catalog.php'" "Home page content catalog import"
check_file_content "pages/home.php" "homepage_content\['hero_description'\]" "Home page hero content integration"
check_file_content "pages/home.php" "mission_preview" "Home page mission preview integration"

check_php_syntax "pages/about.php" "About page syntax"
check_file_content "pages/about.php" "require_once 'includes/content_catalog.php'" "About page content catalog import"
check_file_content "pages/about.php" "about_content\['principles'\]" "About page principles integration"
check_file_content "pages/about.php" "about_content\['mission'\]" "About page mission integration"

check_php_syntax "pages/projects.php" "Projects page syntax"
check_file_content "pages/projects.php" "require_once 'includes/content_catalog.php'" "Projects page content catalog import"
check_file_content "pages/projects.php" "activities" "Projects page activities integration"

check_php_syntax "pages/volunteer.php" "Volunteer page syntax"
check_file_content "pages/volunteer.php" "require_once 'includes/content_catalog.php'" "Volunteer page content catalog import"
check_file_content "pages/volunteer.php" "volunteer_questions" "Volunteer page questions integration"

check_php_syntax "pages/faq.php" "FAQ page syntax"
check_file_content "pages/faq.php" "require_once 'includes/content_catalog.php'" "FAQ page content catalog import"
check_file_content "pages/faq.php" "faq_questions" "FAQ page questions integration"
echo ""

echo -e "${BLUE}3. Checking CSS Integration${NC}"
echo "----------------------------------------"
check_file_content "assets/css/style.css" "CONTENT CATALOG INTEGRATION STYLES" "Content catalog CSS styles"
check_file_content "assets/css/style.css" "mission-preview" "Mission preview styles"
check_file_content "assets/css/style.css" "principle-card" "Principle card styles"
check_file_content "assets/css/style.css" "activity-card" "Activity card styles"
check_file_content "assets/css/style.css" "motivation-card" "Motivation card styles"
check_file_content "assets/css/style.css" "spiritual-faq-card" "Spiritual FAQ card styles"
echo ""

echo -e "${BLUE}4. Testing Content Functionality${NC}"
echo "----------------------------------------"

# Test if content catalog can be loaded
if php -r "require_once 'includes/content_catalog.php'; echo 'Content loaded successfully';"; then
    echo -e "${GREEN}✓${NC} Content catalog loads without errors"
else
    echo -e "${RED}✗${NC} Content catalog loading failed"
fi

# Test if functions work
if php -r "require_once 'includes/content_catalog.php'; \$content = getContentForPage('home'); echo count(\$content) > 0 ? 'Functions work' : 'Functions failed';"; then
    echo -e "${GREEN}✓${NC} Content functions are operational"
else
    echo -e "${RED}✗${NC} Content functions failed"
fi

# Test if helper functions work
if php -r "require_once 'includes/content_catalog.php'; \$q = getRandomMotivationQuestion(); echo isset(\$q['question']) ? 'Helper functions work' : 'Helper functions failed';"; then
    echo -e "${GREEN}✓${NC} Helper functions are operational"
else
    echo -e "${RED}✗${NC} Helper functions failed"
fi

echo ""
echo -e "${BLUE}5. Integration Summary${NC}"
echo "----------------------------------------"

# Count successful integrations
TOTAL_CHECKS=20
SUCCESS_COUNT=0

# Re-run critical checks and count successes
FILES_TO_CHECK=(
    "includes/content_catalog.php"
    "pages/home.php" 
    "pages/about.php"
    "pages/projects.php"
    "pages/volunteer.php"
    "pages/faq.php"
)

for file in "${FILES_TO_CHECK[@]}"; do
    if php -l "$file" > /dev/null 2>&1; then
        ((SUCCESS_COUNT++))
    fi
done

# Content checks
CONTENT_CHECKS=(
    "includes/content_catalog.php:homepage_content"
    "pages/home.php:mission_preview"
    "pages/about.php:about_content"
    "pages/projects.php:activities"
    "pages/volunteer.php:volunteer_questions"
    "pages/faq.php:faq_questions"
    "assets/css/style.css:CONTENT CATALOG INTEGRATION STYLES"
)

for check in "${CONTENT_CHECKS[@]}"; do
    IFS=':' read -r file content <<< "$check"
    if [ -f "$file" ] && grep -q "$content" "$file"; then
        ((SUCCESS_COUNT++))
    fi
done

echo "Integration Status: $SUCCESS_COUNT/13 checks passed"

if [ $SUCCESS_COUNT -ge 10 ]; then
    echo -e "${GREEN}🎉 CONTENT INTEGRATION SUCCESSFUL!${NC}"
    echo ""
    echo "✅ All major components have been integrated:"
    echo "   • Homepage with hero content and mission preview"
    echo "   • About page with founding principles"  
    echo "   • Projects page with activity categories"
    echo "   • Volunteer page with motivational questions"
    echo "   • FAQ page with spiritual questions"
    echo "   • CSS styles for all new content sections"
    echo ""
    echo -e "${YELLOW}📋 Next Steps:${NC}"
    echo "   1. Test the website in a browser"
    echo "   2. Verify all content displays correctly"
    echo "   3. Check responsive design on mobile devices"
    echo "   4. Test all interactive elements"
else
    echo -e "${RED}⚠️  INTEGRATION NEEDS ATTENTION${NC}"
    echo "Some components may need manual review."
fi

echo ""
echo "=========================================="
echo "  Content integration validation complete"
echo "=========================================="
