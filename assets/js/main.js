/**
 * Main JavaScript
 * 
 * Handles navigation, animations, scroll effects, and form interactions.
 */

document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
    initScrollEffects();
    initRevealAnimations();
    initSmoothScroll();
    initFormValidation();
    initTypingAnimation();
    initSkillsAnimation();
});

/**
 * Navigation
 */
function initNavigation() {
    const navbar = document.getElementById('navbar');
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    const navOverlay = document.getElementById('navOverlay');
    
    // Navbar scroll effect
    let lastScroll = 0;
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        // Add/remove scrolled class
        if (currentScroll > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        lastScroll = currentScroll;
    }, { passive: true });
    
    // Mobile menu toggle
    if (navToggle && navMenu && navOverlay) {
        navToggle.addEventListener('click', function() {
            const isOpen = navMenu.classList.contains('active');
            
            if (isOpen) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });
        
        // Close menu on overlay click
        navOverlay.addEventListener('click', closeMobileMenu);
        
        // Close menu on link click
        const navLinks = navMenu.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });
        
        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                closeMobileMenu();
            }
        });
    }
    
    function openMobileMenu() {
        navToggle.classList.add('active');
        navMenu.classList.add('active');
        navOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMobileMenu() {
        navToggle.classList.remove('active');
        navMenu.classList.remove('active');
        navOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
}

/**
 * Scroll Effects
 */
function initScrollEffects() {
    const sections = document.querySelectorAll('section');
    
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
            }
        });
    }, observerOptions);
    
    sections.forEach(section => {
        sectionObserver.observe(section);
    });
}

/**
 * Reveal Animations
 */
function initRevealAnimations() {
    const revealElements = document.querySelectorAll('.reveal, .project-card, .section-header');
    
    const revealOptions = {
        root: null,
        rootMargin: '0px 0px -50px 0px',
        threshold: 0.1
    };
    
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Add staggered delay for multiple elements
                const delay = index * 100;
                setTimeout(() => {
                    entry.target.classList.add('active');
                }, delay);
                
                revealObserver.unobserve(entry.target);
            }
        });
    }, revealOptions);
    
    revealElements.forEach(el => {
        revealObserver.observe(el);
    });
}

/**
 * Skills Animation - Animate skill bars when scrolled into view
 */
function initSkillsAnimation() {
    // Home page skill cards
    const skillCards = document.querySelectorAll('.skill-card');
    
    const cardsObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                const card = entry.target;
                const progressBar = card.querySelector('.skill-card-progress');
                
                if (progressBar) {
                    const targetWidth = progressBar.dataset.width;
                    // Staggered animation
                    setTimeout(() => {
                        progressBar.style.width = targetWidth + '%';
                    }, index * 100);
                }
                
                cardsObserver.unobserve(card);
            }
        });
    }, {
        threshold: 0.2
    });
    
    skillCards.forEach(card => {
        cardsObserver.observe(card);
    });
    
    // Skills page skill cards (full list)
    const skillsPageBars = document.querySelectorAll('.skill-card-progress[data-width]');
    
    const pageObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                const bar = entry.target;
                const targetWidth = bar.dataset.width;
                
                setTimeout(() => {
                    bar.style.width = targetWidth + '%';
                }, (index % 4) * 100);
                
                pageObserver.unobserve(bar);
            }
        });
    }, {
        threshold: 0.2
    });
    
    skillsPageBars.forEach(bar => {
        pageObserver.observe(bar);
    });
}

/**
 * Smooth Scroll
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                
                const navHeight = document.getElementById('navbar').offsetHeight;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Form Validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            // Clear previous errors
            form.querySelectorAll('.form-error').forEach(error => error.remove());
            form.querySelectorAll('.form-input.error').forEach(input => {
                input.classList.remove('error');
            });
            
            requiredFields.forEach(field => {
                const value = field.value.trim();
                
                if (!value) {
                    isValid = false;
                    showFieldError(field, 'This field is required');
                } else if (field.type === 'email' && !isValidEmail(value)) {
                    isValid = false;
                    showFieldError(field, 'Please enter a valid email address');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
    
    function showFieldError(field, message) {
        field.classList.add('error');
        
        const errorElement = document.createElement('p');
        errorElement.className = 'form-error';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    }
    
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
}

/**
 * Typing Animation
 */
function initTypingAnimation() {
    const greetingEl = document.getElementById('type-greeting');
    const headlineEl = document.getElementById('type-headline');
    
    if (!greetingEl || !headlineEl) return;
    
    // Text to type
    const greetingText = "Hello, I'm Stanley Obimma";
    const headlineText = "I build web applications for businesses";
    
    // Clear existing content
    greetingEl.textContent = '';
    headlineEl.innerHTML = '';
    
    // Typing configuration
    const typingSpeed = 70;
    const pauseBeforeHeadline = 400;
    
    // Type greeting first
    typeText(greetingEl, greetingText, typingSpeed, () => {
        setTimeout(() => {
            // Build headline with accent
            headlineEl.innerHTML = 'I build <span class="hero-title-accent">web applications</span> for businesses';
            
            // Reveal other elements
            showHeroElements();
        }, pauseBeforeHeadline);
    });
}

/**
 * Type text into an element with animation
 */
function typeText(element, text, speed, callback) {
    let i = 0;
    element.textContent = '';
    
    function type() {
        if (i < text.length) {
            element.textContent += text.charAt(i);
            i++;
            setTimeout(type, speed);
        } else if (callback) {
            callback();
        }
    }
    
    type();
}

/**
 * Show hero description, CTA, and stats with fade in animation
 */
function showHeroElements() {
    const elements = [
        document.querySelector('.hero-description'),
        document.querySelector('.hero-cta'),
        document.querySelector('.hero-stats'),
        document.querySelector('.hero-code')
    ];
    
    elements.forEach((el, index) => {
        if (el) {
            setTimeout(() => {
                el.style.visibility = 'visible';
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'all 0.6s ease';
                
                el.offsetHeight;
                
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 150);
        }
    });
}

/**
 * Utility Functions
 */

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Format number with commas
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// Copy to clipboard
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Copied to clipboard!');
        });
    } else {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast('Copied to clipboard!');
    }
}

// Show toast notification
function showToast(message, type = 'success') {
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: var(--color-bg-card);
        color: var(--color-text-primary);
        padding: 12px 24px;
        border-radius: 8px;
        border: 1px solid var(--color-border);
        font-size: 14px;
        z-index: 9999;
        opacity: 0;
        transition: all 0.3s ease;
    `;
    
    if (type === 'success') {
        toast.style.borderColor = 'var(--color-success)';
    } else if (type === 'error') {
        toast.style.borderColor = 'var(--color-error)';
    }
    
    document.body.appendChild(toast);
    
    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(-50%) translateY(0)';
    });
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(100px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Lazy load images
function lazyLoadImages() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

if ('IntersectionObserver' in window) {
    lazyLoadImages();
}

// Add loading state to buttons
function initButtonStates() {
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.dataset.originalText = submitBtn.textContent;
                submitBtn.textContent = 'Processing...';
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', initButtonStates);
