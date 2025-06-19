<?php
/**
 * Base64 Logo Helper for Necat Derneği
 * This file provides PHP functions to work with base64 encoded logo
 */

class LogoBase64Helper {
    private static $logoBase64 = null;
    private static $logoDataUrl = null;
    private static $logoPath = 'logo_base64.txt';
    
    /**
     * Load base64 logo data from file
     */
    private static function loadLogoData() {
        if (self::$logoBase64 === null) {
            $logoFile = __DIR__ . '/../' . self::$logoPath;
            
            if (file_exists($logoFile)) {
                self::$logoBase64 = file_get_contents($logoFile);
                
                // Check if it already has data: prefix
                if (strpos(self::$logoBase64, 'data:') === 0) {
                    self::$logoDataUrl = trim(self::$logoBase64);
                } else {
                    self::$logoDataUrl = 'data:image/png;base64,' . trim(self::$logoBase64);
                }
            } else {
                // Fallback to regular file
                self::$logoDataUrl = 'assets/images/logo.png';
            }
        }
    }
    
    /**
     * Get logo as base64 data URL
     * @return string
     */
    public static function getLogoDataUrl() {
        self::loadLogoData();
        return self::$logoDataUrl;
    }
    
    /**
     * Get logo as raw base64 string (without data: prefix)
     * @return string
     */
    public static function getLogoBase64() {
        self::loadLogoData();
        if (strpos(self::$logoDataUrl, 'data:') === 0) {
            return substr(self::$logoDataUrl, strpos(self::$logoDataUrl, ',') + 1);
        }
        return self::$logoBase64;
    }
    
    /**
     * Generate logo img tag with base64 source
     * @param array $attributes
     * @return string
     */
    public static function getLogoImg($attributes = []) {
        $src = self::getLogoDataUrl();
        $alt = isset($attributes['alt']) ? $attributes['alt'] : 'Necat Derneği Logo';
        $class = isset($attributes['class']) ? $attributes['class'] : '';
        $style = isset($attributes['style']) ? $attributes['style'] : '';
        $height = isset($attributes['height']) ? $attributes['height'] : '';
        $width = isset($attributes['width']) ? $attributes['width'] : '';
        
        $imgTag = '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($alt) . '"';
        
        if ($class) {
            $imgTag .= ' class="' . htmlspecialchars($class) . '"';
        }
        
        if ($style) {
            $imgTag .= ' style="' . htmlspecialchars($style) . '"';
        }
        
        if ($height) {
            $imgTag .= ' height="' . htmlspecialchars($height) . '"';
        }
        
        if ($width) {
            $imgTag .= ' width="' . htmlspecialchars($width) . '"';
        }
        
        $imgTag .= '>';
        
        return $imgTag;
    }
    
    /**
     * Get logo for email templates (with proper encoding)
     * @param array $attributes
     * @return string
     */
    public static function getLogoForEmail($attributes = []) {
        $attributes = array_merge([
            'style' => 'height: 50px; display: block; margin: 0 auto;',
            'alt' => 'Necat Derneği Logo'
        ], $attributes);
        
        return self::getLogoImg($attributes);
    }
    
    /**
     * Get logo for navbar/header
     * @param array $attributes
     * @return string  
     */
    public static function getLogoForNavbar($attributes = []) {
        $attributes = array_merge([
            'style' => 'height: 40px; width: auto;',
            'alt' => 'Necat Derneği Logo',
            'class' => 'navbar-logo'
        ], $attributes);
        
        return self::getLogoImg($attributes);
    }
    
    /**
     * Check if base64 logo is available
     * @return bool
     */
    public static function isLogoAvailable() {
        self::loadLogoData();
        return self::$logoDataUrl !== null && !empty(self::$logoBase64);
    }
    
    /**
     * Get logo file size in bytes
     * @return int
     */
    public static function getLogoSize() {
        self::loadLogoData();
        return strlen(self::$logoBase64);
    }
    
    /**
     * Get logo info for debugging
     * @return array
     */
    public static function getLogoInfo() {
        self::loadLogoData();
        return [
            'available' => self::isLogoAvailable(),
            'size' => self::getLogoSize(),
            'data_url_length' => strlen(self::$logoDataUrl),
            'has_data_prefix' => strpos(self::$logoDataUrl, 'data:') === 0,
            'file_path' => __DIR__ . '/../' . self::$logoPath,
            'file_exists' => file_exists(__DIR__ . '/../' . self::$logoPath)
        ];
    }
}

// Convenience functions for global use
if (!function_exists('get_logo_base64')) {
    function get_logo_base64() {
        return LogoBase64Helper::getLogoDataUrl();
    }
}

if (!function_exists('logo_img')) {
    function logo_img($attributes = []) {
        return LogoBase64Helper::getLogoImg($attributes);
    }
}

if (!function_exists('logo_for_email')) {
    function logo_for_email($attributes = []) {
        return LogoBase64Helper::getLogoForEmail($attributes);
    }
}

if (!function_exists('logo_for_navbar')) {
    function logo_for_navbar($attributes = []) {
        return LogoBase64Helper::getLogoForNavbar($attributes);
    }
}
?>
