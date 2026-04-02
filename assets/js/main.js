/**
 * Emre Sigorta - Main JavaScript
 * Bootstrap 5 + AOS Animations
 */

document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // 1. AOS - Animate On Scroll Initialization
    // ==========================================
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 80,
        delay: 0
    });

    // ==========================================
    // 2. Navbar Scroll Effect
    // ==========================================
    const navbar = document.getElementById('mainNavbar');
    const backToTop = document.getElementById('backToTop');

    function handleScroll() {
        const scrollY = window.scrollY;
        
        // Navbar shadow on scroll
        if (navbar) {
            if (scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }

        // Back to top button
        if (backToTop) {
            if (scrollY > 400) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        }
    }

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll(); // Initial check

    // Back to top click
    if (backToTop) {
        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ==========================================
    // 3. Counter Animation
    // ==========================================
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        
        counters.forEach(counter => {
            if (counter.dataset.animated) return;
            
            const rect = counter.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
            
            if (isVisible) {
                counter.dataset.animated = 'true';
                const target = parseInt(counter.dataset.target) || 0;
                const suffix = counter.dataset.suffix || '';
                const prefix = counter.dataset.prefix || '';
                const duration = 2000;
                const stepTime = 20;
                const steps = duration / stepTime;
                const increment = target / steps;
                let current = 0;

                const timer = setInterval(function () {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = prefix + Math.floor(current).toLocaleString('tr-TR') + suffix;
                }, stepTime);
            }
        });
    }

    window.addEventListener('scroll', animateCounters, { passive: true });
    animateCounters(); // Initial check

    // ==========================================
    // 4. Mega Menu - Desktop hover behavior
    // ==========================================
    const megaDropdown = document.querySelector('.mega-dropdown');
    if (megaDropdown && window.innerWidth >= 992) {
        let timeout;
        
        megaDropdown.addEventListener('mouseenter', function () {
            clearTimeout(timeout);
            const toggle = this.querySelector('.dropdown-toggle');
            const menu = this.querySelector('.dropdown-menu');
            if (toggle && menu) {
                toggle.classList.add('show');
                toggle.setAttribute('aria-expanded', 'true');
                menu.classList.add('show');
            }
        });
        
        megaDropdown.addEventListener('mouseleave', function () {
            const el = this;
            timeout = setTimeout(function () {
                const toggle = el.querySelector('.dropdown-toggle');
                const menu = el.querySelector('.dropdown-menu');
                if (toggle && menu) {
                    toggle.classList.remove('show');
                    toggle.setAttribute('aria-expanded', 'false');
                    menu.classList.remove('show');
                }
            }, 200);
        });
    }

    // ==========================================
    // 5. Close offcanvas on link click
    // ==========================================
    const offcanvasEl = document.getElementById('mobileMenu');
    if (offcanvasEl) {
        const offcanvasLinks = offcanvasEl.querySelectorAll('a:not(.accordion-button):not([data-bs-toggle])');
        offcanvasLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                if (offcanvas) offcanvas.hide();
            });
        });
    }

    // ==========================================
    // 6. Quote Form Tabs (if on homepage)
    // ==========================================
    const quoteTabs = document.querySelectorAll('.quote-tabs .nav-link');
    const quoteForms = document.querySelectorAll('.quote-form-content');

    quoteTabs.forEach(function (tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            const target = this.dataset.target;

            // Update active tab
            quoteTabs.forEach(function (t) { t.classList.remove('active'); });
            this.classList.add('active');

            // Show target form
            quoteForms.forEach(function (form) {
                form.style.display = form.id === target ? 'block' : 'none';
            });
        });
    });

    // ==========================================
    // 7. Form Validation
    // ==========================================
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // ==========================================
    // 8. Form Phone Masking
    // ==========================================
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(function (input) {
        input.addEventListener('input', function () {
            let val = this.value.replace(/\D/g, '');
            if (val.length > 11) val = val.substring(0, 11);
            
            if (val.length >= 4 && val.length < 7) {
                this.value = val.substring(0, 4) + ' ' + val.substring(4);
            } else if (val.length >= 7 && val.length < 9) {
                this.value = val.substring(0, 4) + ' ' + val.substring(4, 7) + ' ' + val.substring(7);
            } else if (val.length >= 9) {
                this.value = val.substring(0, 4) + ' ' + val.substring(4, 7) + ' ' + val.substring(7, 9) + ' ' + val.substring(9);
            } else {
                this.value = val;
            }
        });
    });

    // ==========================================
    // 9. Smooth scroll for anchor links
    // ==========================================
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#' || targetId === '#!') return;
            
            const targetEl = document.querySelector(targetId);
            if (targetEl) {
                e.preventDefault();
                const navHeight = navbar ? navbar.offsetHeight : 0;
                const top = targetEl.getBoundingClientRect().top + window.scrollY - navHeight - 20;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }
        });
    });

    // ==========================================
    // 10. Tooltip initialization
    // ==========================================
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (tooltipTriggerList.length) {
        tooltipTriggerList.forEach(function (el) {
            new bootstrap.Tooltip(el);
        });
    }

    // ==========================================
    // 10b. Legacy FAQ accordion (faq-item/faq-question)
    // ==========================================
    document.querySelectorAll('.faq-question').forEach(function (question) {
        question.addEventListener('click', function () {
            const item = this.closest('.faq-item');
            const isActive = item.classList.contains('active');
            
            // Close all siblings
            const parent = item.parentElement;
            if (parent) {
                parent.querySelectorAll('.faq-item.active').forEach(function (activeItem) {
                    activeItem.classList.remove('active');
                });
            }
            
            // Toggle current
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });

    // ==========================================
    // 11. Image lazy loading fallback
    // ==========================================
    if ('loading' in HTMLImageElement.prototype) {
        // Native lazy loading supported
        document.querySelectorAll('img[data-src]').forEach(function (img) {
            img.src = img.dataset.src;
        });
    } else {
        // Fallback with IntersectionObserver
        const imgObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    imgObserver.unobserve(img);
                }
            });
        });
        document.querySelectorAll('img[data-src]').forEach(function (img) {
            imgObserver.observe(img);
        });
    }

    // ==========================================
    // 12. Typing Effect for Hero (optional)
    // ==========================================
    const typingEl = document.querySelector('.typing-text');
    if (typingEl) {
        const words = JSON.parse(typingEl.dataset.words || '[]');
        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let delay = 100;

        function typeEffect() {
            const currentWord = words[wordIndex] || '';
            
            if (isDeleting) {
                typingEl.textContent = currentWord.substring(0, charIndex - 1);
                charIndex--;
                delay = 50;
            } else {
                typingEl.textContent = currentWord.substring(0, charIndex + 1);
                charIndex++;
                delay = 100;
            }

            if (!isDeleting && charIndex === currentWord.length) {
                delay = 2000;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                delay = 300;
            }

            setTimeout(typeEffect, delay);
        }

        if (words.length > 0) {
            setTimeout(typeEffect, 1000);
        }
    }

});
