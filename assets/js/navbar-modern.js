// Modern Navbar Functionality - Enhanced for Performance, A11y, and UX
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('navbar');
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    let lastScrollTop = 0;
    let ticking = false;

    if (!navbar) return;

    // --- Performance & UX: Get navbar height from CSS variables ---
    const getNavbarHeight = () => {
        const navbarStyle = getComputedStyle(document.documentElement);
        const heightVar = navbar.classList.contains('scrolled') ? '--navbar-height-scrolled' : '--navbar-height';
        return parseFloat(navbarStyle.getPropertyValue(heightVar).replace('px', ''));
    };

    // --- Scroll Effects (Optimized with rAF) ---
    const handleScroll = () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        // Add scrolled class
        if (scrollTop > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

        // Hide/show navbar on scroll (only if mobile menu is not open)
        if (mobileMenu && !mobileMenu.classList.contains('active')) {
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                navbar.classList.add('hidden');
            } else {
                navbar.classList.remove('hidden');
            }
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        ticking = false;
    };

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(handleScroll);
            ticking = true;
        }
    }, { passive: true });

    // --- Mobile Menu (Enhanced for A11y and UX) ---
    if (mobileToggle && mobileMenu) {
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
        const mobileMenuClose = document.getElementById('mobileMenuClose');
        const focusableElementsString = 'a[href]:not([disabled]), button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled])';
        let focusableElements;

        const openMobileMenu = () => {
            mobileToggle.classList.add('active');
            mobileToggle.setAttribute('aria-expanded', 'true');
            mobileMenu.classList.add('active');
            if (mobileMenuOverlay) mobileMenuOverlay.classList.add('active');

            const scrollbarWidth = getScrollbarWidth();
            document.body.style.overflow = 'hidden';
            document.body.style.paddingRight = `${scrollbarWidth}px`;
            navbar.classList.remove('hidden'); // Keep navbar visible

            // A11y: Trap focus
            focusableElements = Array.from(mobileMenu.querySelectorAll(focusableElementsString));
            if(focusableElements.length > 0) focusableElements[0].focus();
            mobileMenu.addEventListener('keydown', trapFocus);
        };

        const closeMobileMenu = () => {
            mobileToggle.classList.remove('active');
            mobileToggle.setAttribute('aria-expanded', 'false');
            mobileMenu.classList.remove('active');
            if (mobileMenuOverlay) mobileMenuOverlay.classList.remove('active');

            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            // A11y: Return focus to the toggle button
            mobileToggle.focus();
            mobileMenu.removeEventListener('keydown', trapFocus);
        };

        mobileToggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (mobileMenu.classList.contains('active')) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });

        if (mobileMenuClose) mobileMenuClose.addEventListener('click', closeMobileMenu);
        if (mobileMenuOverlay) mobileMenuOverlay.addEventListener('click', closeMobileMenu);

        const mobileNavLinks = mobileMenu.querySelectorAll('.nav-link-modern');
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (mobileMenu.classList.contains('active')) {
                    setTimeout(closeMobileMenu, 150); // Delay for feedback
                }
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                closeMobileMenu();
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 992 && mobileMenu.classList.contains('active')) {
                closeMobileMenu();
            }
        }, { passive: true });

        const trapFocus = (e) => {
            const isTabPressed = e.key === 'Tab';
            if (!isTabPressed) return;

            const firstFocusableElement = focusableElements[0];
            const lastFocusableElement = focusableElements[focusableElements.length - 1];

            if (e.shiftKey) { // Shift + Tab
                if (document.activeElement === firstFocusableElement) {
                    lastFocusableElement.focus();
                    e.preventDefault();
                }
            } else { // Tab
                if (document.activeElement === lastFocusableElement) {
                    firstFocusableElement.focus();
                    e.preventDefault();
                }
            }
        };
    }

    // --- Utility: Get Scrollbar Width ---
    function getScrollbarWidth() {
        const outer = document.createElement('div');
        outer.style.visibility = 'hidden';
        outer.style.overflow = 'scroll';
        document.body.appendChild(outer);
        const inner = document.createElement('div');
        outer.appendChild(inner);
        const scrollbarWidth = (outer.offsetWidth - inner.offsetWidth);
        outer.parentNode.removeChild(outer);
        return scrollbarWidth;
    }

    // --- Smooth Scrolling for Anchor Links (Enhanced) ---
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId.length <= 1) return;
            
            try {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    const navbarHeight = getNavbarHeight();
                    const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            } catch (error) {
                console.error("Could not scroll to element with selector: ", targetId);
            }
        });
    });

    // --- Logo Loading Enhancement ---
    const logoImage = document.getElementById('logoImage');
    if (logoImage) {
        logoImage.addEventListener('load', function() { this.classList.add('loaded'); });
        logoImage.addEventListener('error', function() { this.classList.add('error'); });
    }

    // --- Active Link Highlighting ---
    const navLinks = document.querySelectorAll('.nav-link-modern, .dropdown-item-modern');
    const currentPage = window.location.href;
    navLinks.forEach(link => {
        if (link.href === currentPage) {
            link.classList.add('active');
            // If it's in a dropdown, open the dropdown
            const dropdown = link.closest('.nav-item-modern.dropdown');
            if(dropdown) {
                dropdown.querySelector('.nav-link-modern').classList.add('active');
            }
        }
    });
});
