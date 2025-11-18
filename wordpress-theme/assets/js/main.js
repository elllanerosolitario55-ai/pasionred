/**
 * Main JavaScript for Pasiones Theme
 */

(function($) {
    'use strict';

    /**
     * Mobile menu toggle
     */
    function initMobileMenu() {
        const toggle = document.querySelector('.mobile-menu-toggle');
        const menu = document.querySelector('.site-navigation');

        if (!toggle || !menu) return;

        toggle.addEventListener('click', function() {
            this.classList.toggle('active');
            menu.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                toggle.classList.remove('active');
                menu.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        });
    }

    /**
     * Sticky header
     */
    function initStickyHeader() {
        const header = document.querySelector('.site-header');
        if (!header) return;

        let lastScroll = 0;

        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;

            if (currentScroll <= 0) {
                header.classList.remove('scroll-up');
                header.classList.remove('scroll-down');
                return;
            }

            if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
                // Scroll Down
                header.classList.remove('scroll-up');
                header.classList.add('scroll-down');
            } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
                // Scroll Up
                header.classList.remove('scroll-down');
                header.classList.add('scroll-up');
            }

            lastScroll = currentScroll;
        });
    }

    /**
     * Smooth scroll for anchor links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                if (href === '#') {
                    e.preventDefault();
                    return;
                }

                const target = document.querySelector(href);

                if (target) {
                    e.preventDefault();
                    const headerHeight = document.querySelector('.site-header').offsetHeight;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Filter professionals
     */
    function initProfessionalFilters() {
        const filterForm = document.querySelector('.filter-form');

        if (!filterForm) return;

        // Auto-submit on select change
        filterForm.querySelectorAll('select, input[type="checkbox"]').forEach(input => {
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    }

    /**
     * Lazy load images
     */
    function initLazyLoad() {
        if ('loading' in HTMLImageElement.prototype) {
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
            });
        } else {
            // Fallback for browsers that don't support lazy loading
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
            document.body.appendChild(script);
        }
    }

    /**
     * Rating stars interaction
     */
    function initRatingStars() {
        const ratingContainers = document.querySelectorAll('.rating-interactive');

        ratingContainers.forEach(container => {
            const stars = container.querySelectorAll('.star');
            const input = container.querySelector('input[type="hidden"]');

            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    const rating = index + 1;

                    // Update hidden input
                    if (input) {
                        input.value = rating;
                    }

                    // Update visual stars
                    stars.forEach((s, i) => {
                        if (i < rating) {
                            s.classList.add('active');
                            s.textContent = '⭐';
                        } else {
                            s.classList.remove('active');
                            s.textContent = '☆';
                        }
                    });
                });

                star.addEventListener('mouseenter', function() {
                    const rating = index + 1;
                    stars.forEach((s, i) => {
                        if (i < rating) {
                            s.textContent = '⭐';
                        } else {
                            s.textContent = '☆';
                        }
                    });
                });
            });

            container.addEventListener('mouseleave', function() {
                const currentRating = parseInt(input ? input.value : 0);
                stars.forEach((s, i) => {
                    if (i < currentRating) {
                        s.textContent = '⭐';
                    } else {
                        s.textContent = '☆';
                    }
                });
            });
        });
    }

    /**
     * Favorite toggle
     */
    function initFavoriteToggle() {
        const favoriteButtons = document.querySelectorAll('.favorite-toggle');

        favoriteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const professionalId = this.dataset.professionalId;

                if (!professionalId) return;

                // Toggle visual state immediately
                this.classList.toggle('active');

                // Send AJAX request
                $.ajax({
                    url: pasionesAjax.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'pasiones_toggle_favorite',
                        professional_id: professionalId,
                        nonce: pasionesAjax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Favorite toggled successfully');
                        }
                    },
                    error: function(error) {
                        console.error('Error toggling favorite:', error);
                        // Revert visual state on error
                        button.classList.toggle('active');
                    }
                });
            });
        });
    }

    /**
     * Membership selection
     */
    function initMembershipSelection() {
        const membershipButtons = document.querySelectorAll('[data-membership]');

        membershipButtons.forEach(button => {
            button.addEventListener('click', function() {
                const membership = this.dataset.membership;

                if (membership === 'FREE') {
                    window.location.href = '/registro';
                } else {
                    // Open payment modal or redirect to checkout
                    window.location.href = '/checkout?membership=' + membership;
                }
            });
        });
    }

    /**
     * Back to top button
     */
    function initBackToTop() {
        const button = document.createElement('button');
        button.className = 'back-to-top';
        button.innerHTML = '↑';
        button.setAttribute('aria-label', 'Back to top');
        document.body.appendChild(button);

        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                button.classList.add('visible');
            } else {
                button.classList.remove('visible');
            }
        });

        button.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    /**
     * Initialize tooltips
     */
    function initTooltips() {
        const tooltips = document.querySelectorAll('[data-tooltip]');

        tooltips.forEach(element => {
            const tooltipText = element.dataset.tooltip;

            element.addEventListener('mouseenter', function() {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip';
                tooltip.textContent = tooltipText;
                document.body.appendChild(tooltip);

                const rect = element.getBoundingClientRect();
                tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
                tooltip.style.left = (rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)) + 'px';

                element.tooltipElement = tooltip;
            });

            element.addEventListener('mouseleave', function() {
                if (element.tooltipElement) {
                    element.tooltipElement.remove();
                    delete element.tooltipElement;
                }
            });
        });
    }

    /**
     * Initialize all functions on DOM ready
     */
    $(document).ready(function() {
        initMobileMenu();
        initStickyHeader();
        initSmoothScroll();
        initProfessionalFilters();
        initLazyLoad();
        initRatingStars();
        initFavoriteToggle();
        initMembershipSelection();
        initBackToTop();
        initTooltips();
    });

    /**
     * Initialize on window load (for images, etc.)
     */
    $(window).on('load', function() {
        // Hide loading screen if present
        const loader = document.querySelector('.page-loader');
        if (loader) {
            loader.classList.add('fade-out');
            setTimeout(() => loader.remove(), 300);
        }
    });

})(jQuery);
