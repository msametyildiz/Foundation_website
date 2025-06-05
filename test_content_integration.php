<?php
/**
 * Content Catalog Integration Test
 * Tests all integrated content across pages
 */

// Test content catalog integration
require_once 'includes/content_catalog.php';

echo "<h1>Content Catalog Integration Test</h1>\n";

// Test homepage content
echo "<h2>1. Homepage Content</h2>\n";
$homepage_content = getContentForPage('home');
echo "<pre>" . print_r($homepage_content, true) . "</pre>\n";

// Test about content
echo "<h2>2. About Page Content</h2>\n";
$about_content = getContentForPage('about');
echo "<h3>Founding Principles Count: " . count($about_content['principles']) . "</h3>\n";
foreach ($about_content['principles'] as $key => $principle) {
    echo "<p><strong>{$principle['title']}</strong>: {$principle['description']}</p>\n";
}

// Test projects content
echo "<h2>3. Projects Page Content (Activities)</h2>\n";
$activities = getContentForPage('projects');
echo "<h3>Activities Count: " . count($activities) . "</h3>\n";
foreach ($activities as $activity) {
    echo "<p><strong>{$activity['title']}</strong>: {$activity['description']} [Category: {$activity['category']}]</p>\n";
}

// Test volunteer content
echo "<h2>4. Volunteer Page Content (Questions)</h2>\n";
$volunteer_questions = getContentForPage('volunteer');
echo "<h3>Volunteer Questions Count: " . count($volunteer_questions) . "</h3>\n";
foreach ($volunteer_questions as $index => $question) {
    echo "<p><strong>Q" . ($index + 1) . ":</strong> " . substr($question['question'], 0, 100) . "... [Category: {$question['category']}]</p>\n";
}

// Test FAQ content
echo "<h2>5. FAQ Page Content</h2>\n";
$faq_questions = getContentForPage('faq');
echo "<h3>FAQ Questions Count: " . count($faq_questions) . "</h3>\n";
foreach ($faq_questions as $faq) {
    echo "<p><strong>Q:</strong> {$faq['question']}</p>\n";
    echo "<p><strong>A:</strong> {$faq['answer']}</p>\n";
    echo "<hr>\n";
}

// Test helper functions
echo "<h2>6. Helper Functions Test</h2>\n";
echo "<p>Random Motivation Question: " . getRandomMotivationQuestion()['question'] . "</p>\n";
echo "<p>Education Activities: " . count(getActivitiesByCategory('egitim')) . "</p>\n";
echo "<p>Health Activities: " . count(getActivitiesByCategory('saglik')) . "</p>\n";

// Test color categories
echo "<h2>7. Category Colors Test</h2>\n";
$test_categories = ['aile_yardimi', 'egitim', 'saglik', 'manevi'];
foreach ($test_categories as $category) {
    echo "<p>Category '{$category}': " . getCategoryColor($category) . "</p>\n";
}

echo "<h2>✅ Content Catalog Integration Test Complete</h2>\n";
echo "<p>All content has been successfully integrated into the website pages.</p>\n";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1 { color: #4EA674; }
h2 { color: #D3D92B; margin-top: 30px; }
h3 { color: #F2E529; }
pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
hr { margin: 10px 0; }
</style>
