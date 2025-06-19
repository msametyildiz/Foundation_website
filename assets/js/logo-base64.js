/**
 * Base64 Logo Integration for Necat Derneği
 * This script handles the integration of base64 encoded logo across the website
 */

class LogoManager {
    constructor() {
        this.logoBase64 = null;
        this.logoDataUrl = null;
        this.isLoaded = false;
        this.callbacks = [];
        
        // Initialize logo loading
        this.init();
    }
    
    async init() {
        try {
            await this.loadBase64Logo();
            this.replaceLogoElements();
            this.executeCallbacks();
        } catch (error) {
            console.error('Logo initialization failed:', error);
        }
    }
    
    async loadBase64Logo() {
        try {
            // Fetch the base64 data from the text file
            const response = await fetch('logo_base64.txt');
            if (!response.ok) {
                throw new Error('Failed to load logo base64 data');
            }
            
            this.logoBase64 = await response.text();
            
            // Create data URL (check if it already has the data: prefix)
            if (this.logoBase64.startsWith('data:')) {
                this.logoDataUrl = this.logoBase64;
            } else {
                this.logoDataUrl = `data:image/png;base64,${this.logoBase64}`;
            }
            
            this.isLoaded = true;
            console.log('Base64 logo loaded successfully');
            
        } catch (error) {
            console.error('Failed to load base64 logo:', error);
            // Fallback to regular logo file
            this.logoDataUrl = 'assets/images/logo.png';
        }
    }
    
    replaceLogoElements() {
        // Find all logo images and replace with base64
        const logoSelectors = [
            'img[src*="logo.png"]',
            'img[src*="logo2.png"]',
            'img[alt*="logo"]',
            'img[alt*="Logo"]',
            '#logoImage',
            '.logo-image'
        ];
        
        logoSelectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(img => {
                this.setLogoSource(img);
            });
        });
        
        // Special handling for elements that might be added dynamically
        this.observeForNewLogos();
    }
    
    setLogoSource(imgElement) {
        if (!imgElement || !this.logoDataUrl) return;
        
        // Store original src as fallback
        const originalSrc = imgElement.src;
        
        // Set base64 source
        imgElement.src = this.logoDataUrl;
        
        // Handle load error - fallback to original
        imgElement.onerror = () => {
            console.warn('Base64 logo failed to load, falling back to original');
            imgElement.src = originalSrc;
        };
        
        // Add loaded class for styling
        imgElement.onload = () => {
            imgElement.classList.add('logo-loaded');
        };
    }
    
    observeForNewLogos() {
        // Observer to handle dynamically added logo elements
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Check if the added node is a logo
                        if (this.isLogoElement(node)) {
                            this.setLogoSource(node);
                        }
                        
                        // Check for logo elements within the added node
                        const logoElements = node.querySelectorAll && node.querySelectorAll('img[src*="logo"]');
                        if (logoElements) {
                            logoElements.forEach(img => this.setLogoSource(img));
                        }
                    }
                });
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    isLogoElement(element) {
        if (element.tagName !== 'IMG') return false;
        
        const src = element.src || '';
        const alt = element.alt || '';
        const className = element.className || '';
        const id = element.id || '';
        
        return src.includes('logo') || 
               alt.toLowerCase().includes('logo') || 
               className.includes('logo') || 
               id.includes('logo');
    }
    
    // Public methods
    onReady(callback) {
        if (this.isLoaded) {
            callback(this.logoDataUrl);
        } else {
            this.callbacks.push(callback);
        }
    }
    
    executeCallbacks() {
        this.callbacks.forEach(callback => {
            try {
                callback(this.logoDataUrl);
            } catch (error) {
                console.error('Logo callback error:', error);
            }
        });
        this.callbacks = [];
    }
    
    getLogoDataUrl() {
        return this.logoDataUrl;
    }
    
    // Method to get base64 for email templates
    getLogoForEmail() {
        return this.logoDataUrl;
    }
    
    // Method to create logo element with base64
    createLogoElement(options = {}) {
        const img = document.createElement('img');
        img.src = this.logoDataUrl;
        img.alt = options.alt || 'Necat Derneği Logo';
        img.style.height = options.height || 'auto';
        img.style.width = options.width || 'auto';
        
        if (options.className) {
            img.className = options.className;
        }
        
        return img;
    }
}

// Initialize logo manager when DOM is ready
let logoManager;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        logoManager = new LogoManager();
    });
} else {
    logoManager = new LogoManager();
}

// Export for use in other scripts
window.LogoManager = LogoManager;
window.logoManager = logoManager;
