<?php
/**
 * Performance Optimization and Caching System
 * Necat Derneği Website
 */

class PerformanceOptimizer {
    private $cache_dir;
    private $cache_enabled;
    private $cache_ttl;
    
    public function __construct($cache_dir = 'cache', $ttl = 3600) {
        $this->cache_dir = $cache_dir;
        $this->cache_ttl = $ttl;
        $this->cache_enabled = true;
        
        // Create cache directory if it doesn't exist
        if (!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0755, true);
        }
    }
    
    /**
     * Get cached content
     */
    public function get($key) {
        if (!$this->cache_enabled) {
            return false;
        }
        
        $cache_file = $this->getCacheFile($key);
        
        if (!file_exists($cache_file)) {
            return false;
        }
        
        $cache_data = unserialize(file_get_contents($cache_file));
        
        // Check if cache has expired
        if (time() > $cache_data['expires']) {
            unlink($cache_file);
            return false;
        }
        
        return $cache_data['content'];
    }
    
    /**
     * Set cache content
     */
    public function set($key, $content, $ttl = null) {
        if (!$this->cache_enabled) {
            return false;
        }
        
        $ttl = $ttl ?? $this->cache_ttl;
        $cache_file = $this->getCacheFile($key);
        
        $cache_data = [
            'content' => $content,
            'expires' => time() + $ttl,
            'created' => time()
        ];
        
        return file_put_contents($cache_file, serialize($cache_data)) !== false;
    }
    
    /**
     * Delete cache entry
     */
    public function delete($key) {
        $cache_file = $this->getCacheFile($key);
        if (file_exists($cache_file)) {
            return unlink($cache_file);
        }
        return true;
    }
    
    /**
     * Clear all cache
     */
    public function clear() {
        $files = glob($this->cache_dir . '/*.cache');
        foreach ($files as $file) {
            unlink($file);
        }
        return true;
    }
    
    /**
     * Get cache file path
     */
    private function getCacheFile($key) {
        return $this->cache_dir . '/' . md5($key) . '.cache';
    }
    
    /**
     * Cache database query results
     */
    public function cacheQuery($key, $callback, $ttl = null) {
        $cached = $this->get($key);
        
        if ($cached !== false) {
            return $cached;
        }
        
        $result = $callback();
        $this->set($key, $result, $ttl);
        
        return $result;
    }
    
    /**
     * Minify HTML output
     */
    public static function minifyHTML($html) {
        // Remove HTML comments (except conditional comments)
        $html = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);
        
        // Remove whitespace
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/>\s+</', '><', $html);
        
        return trim($html);
    }
    
    /**
     * Compress CSS
     */
    public static function minifyCSS($css) {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove whitespace
        $css = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $css);
        $css = str_replace([': ', ' {', '{ ', ' }', '; '], [':', '{', '{', '}', ';'], $css);
        
        return trim($css);
    }
    
    /**
     * Compress JavaScript
     */
    public static function minifyJS($js) {
        // Remove comments
        $js = preg_replace('!/\*.*?\*/!s', '', $js);
        $js = preg_replace('/\n\s*\/\/.*/', '', $js);
        
        // Remove whitespace
        $js = preg_replace('/\s+/', ' ', $js);
        $js = str_replace([' = ', ' + ', ' - ', ' * ', ' / ', ' == ', ' != ', ' >= ', ' <= '], 
                         ['=', '+', '-', '*', '/', '==', '!=', '>=', '<='], $js);
        
        return trim($js);
    }
    
    /**
     * Generate optimized image
     */
    public static function optimizeImage($source, $destination, $quality = 85, $max_width = 1200) {
        if (!extension_loaded('gd')) {
            return false;
        }
        
        $image_info = getimagesize($source);
        if (!$image_info) {
            return false;
        }
        
        list($width, $height, $type) = $image_info;
        
        // Calculate new dimensions
        if ($width > $max_width) {
            $new_width = $max_width;
            $new_height = ($height * $max_width) / $width;
        } else {
            $new_width = $width;
            $new_height = $height;
        }
        
        // Create image resource
        switch ($type) {
            case IMAGETYPE_JPEG:
                $source_image = imagecreatefromjpeg($source);
                break;
            case IMAGETYPE_PNG:
                $source_image = imagecreatefrompng($source);
                break;
            case IMAGETYPE_GIF:
                $source_image = imagecreatefromgif($source);
                break;
            default:
                return false;
        }
        
        // Create new image
        $new_image = imagecreatetruecolor($new_width, $new_height);
        
        // Preserve transparency for PNG and GIF
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
            $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
            imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
        }
        
        // Resize image
        imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, 
                          $new_width, $new_height, $width, $height);
        
        // Save optimized image
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($new_image, $destination, $quality);
                break;
            case IMAGETYPE_PNG:
                imagepng($new_image, $destination, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($new_image, $destination);
                break;
        }
        
        // Clean up
        imagedestroy($source_image);
        imagedestroy($new_image);
        
        return true;
    }
    
    /**
     * Get cache statistics
     */
    public function getStats() {
        $files = glob($this->cache_dir . '/*.cache');
        $total_size = 0;
        $expired_count = 0;
        $active_count = 0;
        
        foreach ($files as $file) {
            $total_size += filesize($file);
            $cache_data = unserialize(file_get_contents($file));
            
            if (time() > $cache_data['expires']) {
                $expired_count++;
            } else {
                $active_count++;
            }
        }
        
        return [
            'total_files' => count($files),
            'active_files' => $active_count,
            'expired_files' => $expired_count,
            'total_size' => $total_size,
            'total_size_mb' => round($total_size / 1024 / 1024, 2)
        ];
    }
    
    /**
     * Clean expired cache files
     */
    public function cleanup() {
        $files = glob($this->cache_dir . '/*.cache');
        $cleaned = 0;
        
        foreach ($files as $file) {
            $cache_data = unserialize(file_get_contents($file));
            
            if (time() > $cache_data['expires']) {
                unlink($file);
                $cleaned++;
            }
        }
        
        return $cleaned;
    }
    
    /**
     * Enable output compression
     */
    public static function enableCompression() {
        if (!ob_get_level() && extension_loaded('zlib')) {
            ob_start('ob_gzhandler');
        }
    }
    
    /**
     * Set browser cache headers
     */
    public static function setBrowserCache($seconds = 3600) {
        header('Cache-Control: public, max-age=' . $seconds);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime(__FILE__)) . ' GMT');
    }
    
    /**
     * Generate critical CSS for above-the-fold content
     */
    public static function generateCriticalCSS($html, $css) {
        // Simple critical CSS extraction
        // In production, use tools like penthouse or critical
        
        $critical_selectors = [
            'body', 'html', 'header', '.header', '#header',
            '.navbar', '.nav', '.menu', '.hero', '.banner',
            'h1', 'h2', '.title', '.container', '.wrapper'
        ];
        
        $critical_css = '';
        
        foreach ($critical_selectors as $selector) {
            $pattern = '/(' . preg_quote($selector, '/') . '\s*\{[^}]+\})/i';
            if (preg_match($pattern, $css, $matches)) {
                $critical_css .= $matches[1] . "\n";
            }
        }
        
        return self::minifyCSS($critical_css);
    }
}

// Usage example for performance monitoring
class PerformanceMonitor {
    private static $start_time;
    private static $start_memory;
    private static $queries = 0;
    
    public static function start() {
        self::$start_time = microtime(true);
        self::$start_memory = memory_get_usage();
    }
    
    public static function incrementQuery() {
        self::$queries++;
    }
    
    public static function getStats() {
        return [
            'execution_time' => round((microtime(true) - self::$start_time) * 1000, 2) . 'ms',
            'memory_usage' => round((memory_get_usage() - self::$start_memory) / 1024, 2) . 'KB',
            'peak_memory' => round(memory_get_peak_usage() / 1024 / 1024, 2) . 'MB',
            'queries' => self::$queries
        ];
    }
}

// Global instance
$performance_optimizer = new PerformanceOptimizer();
?>
