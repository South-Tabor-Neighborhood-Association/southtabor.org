/**
 * Advanced Accordion Block - Modern Dashboard JavaScript
 * Version: 1.0.0
 */

(function($) {
    'use strict';

    // Dashboard App
    const AABDashboard = {
        /**
         * Initialize the dashboard
         */
        init: function() {
            this.bindEvents();
            this.initTabs();
            this.initAnimations();
            this.initVideoModal();
            this.initRatingPrompt();
            this.initExtensionHandlers();
            this.initTooltips();
        },

        /**
         * Initialize tab functionality (Custom implementation for split nav/panel structure)
         */
        initTabs: function() {
            const self = this;
            const $tabNav = $('#aab-main-tabs');
            const $tabLinks = $tabNav.find('.aab-tabs-list a');
            const $tabPanels = $('.aab-tabs-content .aab-tab-panel');

            if (!$tabNav.length || !$tabLinks.length) {
                return;
            }

            // Initialize: Show first tab, hide others
            $tabPanels.hide().first().show().addClass('active');
            $tabLinks.first().parent().addClass('ui-tabs-active ui-state-active active');

            // Tab click handler
            $tabLinks.on('click', function(e) {
                e.preventDefault();
                const targetId = $(this).attr('href');

                // Update tab nav states
                $tabLinks.parent().removeClass('ui-tabs-active ui-state-active active');
                $(this).parent().addClass('ui-tabs-active ui-state-active active');

                // Animate panel transition
                $tabPanels.filter('.active').fadeOut(100, function() {
                    $(this).removeClass('active');
                    $(targetId).fadeIn(200).addClass('active fade-in');

                    // Re-trigger animations for elements inside the panel
                    $(targetId).find('.aab-animate-fade-in, .aab-animate-slide-in').each(function(index) {
                        $(this).css('animation', 'none');
                        $(this)[0].offsetHeight; // Trigger reflow
                        $(this).css('animation', '');
                    });
                });
            });

            // Keyboard navigation for tabs
            $tabLinks.on('keydown', function(e) {
                const currentIndex = $tabLinks.index(this);
                let newIndex;

                switch(e.key) {
                    case 'ArrowLeft':
                    case 'ArrowUp':
                        e.preventDefault();
                        newIndex = currentIndex === 0 ? $tabLinks.length - 1 : currentIndex - 1;
                        $tabLinks.eq(newIndex).focus().click();
                        break;
                    case 'ArrowRight':
                    case 'ArrowDown':
                        e.preventDefault();
                        newIndex = currentIndex === $tabLinks.length - 1 ? 0 : currentIndex + 1;
                        $tabLinks.eq(newIndex).focus().click();
                        break;
                    case 'Home':
                        e.preventDefault();
                        $tabLinks.first().focus().click();
                        break;
                    case 'End':
                        e.preventDefault();
                        $tabLinks.last().focus().click();
                        break;
                }
            });
        },

        /**
         * Switch to a specific tab programmatically
         */
        switchTab: function(tabId) {
            const $tabLinks = $('#aab-main-tabs .aab-tabs-list a');
            const $tabPanels = $('.aab-tabs-content .aab-tab-panel');
            const $targetLink = $tabLinks.filter('[href="' + tabId + '"]');

            if ($targetLink.length) {
                // Update tab nav states
                $tabLinks.parent().removeClass('ui-tabs-active ui-state-active active');
                $targetLink.parent().addClass('ui-tabs-active ui-state-active active');

                // Update tab panels
                $tabPanels.removeClass('active fade-in').hide();
                $(tabId).addClass('active fade-in').show();
            }
        },

        /**
         * Bind all event handlers
         */
        bindEvents: function() {

            // Card hover effects
            $('.aab-setting-item, .aab-module-item, .aab-feature-card').on('mouseenter', function() {
                $(this).addClass('aab-hover');
            }).on('mouseleave', function() {
                $(this).removeClass('aab-hover');
            });

            // Keyboard accessibility for cards
            $('.aab-setting-item, .aab-module-item').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const link = $(this).find('a').first();
                    if (link.length) {
                        window.location.href = link.attr('href');
                    }
                }
            });
        },

        /**
         * Initialize scroll-based animations
         */
        initAnimations: function() {
            // Intersection Observer for fade-in animations
            if ('IntersectionObserver' in window) {
                const animatedElements = document.querySelectorAll('.aab-animate-on-scroll');

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('aab-animate-fade-in');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });

                animatedElements.forEach(el => observer.observe(el));
            }

            // Staggered animation for grid items
            $('.aab-settings-grid .aab-setting-item, .aab-modules-grid .aab-module-item, .aab-features-grid .aab-feature-card').each(function(index) {
                $(this).css('animation-delay', (index * 0.05) + 's');
            });
        },

        /**
         * Initialize video modal functionality
         */
        initVideoModal: function() {
            const self = this;

            // Open video modal
            $('.aab-hero-video-overlay, .aab-play-btn ,.aab-open-video').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                self.openVideoModal();
            });

            // Close video modal
            $('.aab-video-modal-close, .aab-video-modal').on('click', function(e) {
                if (e.target === this) {
                    self.closeVideoModal();
                }
            });

            // Close on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    self.closeVideoModal();
                }
            });
        },

        /**
         * Open the video modal
         */
        openVideoModal: function() {
            const modal = $('#aab-video-modal');
            const iframe = modal.find('iframe');
            const videoSrc = iframe.data('src');

            if (videoSrc) {
                iframe.attr('src', videoSrc + '?autoplay=1');
            }

            modal.addClass('active');
            $('body').css('overflow', 'hidden');

            // Focus trap
            modal.find('.aab-video-modal-close').focus();
        },

        /**
         * Close the video modal
         */
        closeVideoModal: function() {
            const modal = $('#aab-video-modal');
            const iframe = modal.find('iframe');

            iframe.attr('src', '');
            modal.removeClass('active');
            $('body').css('overflow', '');
        },

        /**
         * Initialize rating prompt with delayed visibility
         */
        initRatingPrompt: function() {
            const ratingCard = $('.aab-rating-card');

            if (!ratingCard.length) return;

            // Check if user has dismissed the rating prompt
            const dismissed = localStorage.getItem('aab_rating_dismissed');
            const installDate = localStorage.getItem('aab_install_date');
            const today = new Date().getTime();

            // Set install date if not set
            if (!installDate) {
                localStorage.setItem('aab_install_date', today);
            }

            // Show rating prompt after 7 days of usage
            const sevenDays = 7 * 24 * 60 * 60 * 1000;
            const shouldShow = !dismissed && installDate && (today - parseInt(installDate)) > sevenDays;

            if (!shouldShow && installDate) {
                // Still show but with lower prominence
                ratingCard.addClass('aab-rating-subtle');
            }

            // Dismiss rating prompt
            $('.aab-rating-dismiss').on('click', function(e) {
                e.preventDefault();
                localStorage.setItem('aab_rating_dismissed', 'true');
                ratingCard.fadeOut(300);
            });
        },

        /**
         * Initialize extension install/activate handlers
         */
        initExtensionHandlers: function() {
            const self = this;

            // Install plugin
            $(document).on('click', '.aab-ext-btn-install', function(e) {
                e.preventDefault();
                const btn = $(this);
                const slug = btn.data('slug');

                if (!slug) return;

                self.installPlugin(btn, slug);
            });

            // Activate plugin
            $(document).on('click', '.aab-ext-btn-activate', function(e) {
                e.preventDefault();
                const btn = $(this);
                const plugin = btn.data('plugin');

                if (!plugin) return;

                self.activatePlugin(btn, plugin);
            });
        },

        /**
         * Install a plugin via AJAX
         */
        installPlugin: function(btn, slug) {
            const originalText = btn.text();

            btn.text('Installing...').addClass('aab-ext-btn-loading').prop('disabled', true);

            $.ajax({
                url: aabDashboard.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aab_install_plugin',
                    slug: slug,
                    nonce: aabDashboard.nonce
                },
                success: function(response) {
                    if (response.success) {
                        btn.text('Activate')
                           .removeClass('aab-ext-btn-loading aab-ext-btn-install')
                           .addClass('aab-ext-btn-activate')
                           .data('plugin', response.data.plugin_file)
                           .prop('disabled', false);
                    } else {
                        btn.text(originalText).removeClass('aab-ext-btn-loading').prop('disabled', false);
                        alert(response.data.message || 'Installation failed. Please try again.');
                    }
                },
                error: function() {
                    btn.text(originalText).removeClass('aab-ext-btn-loading').prop('disabled', false);
                    alert('Installation failed. Please try again.');
                }
            });
        },

        /**
         * Activate a plugin via AJAX
         */
        activatePlugin: function(btn, plugin) {
            const originalText = btn.text();

            btn.text('Activating...').addClass('aab-ext-btn-loading').prop('disabled', true);

            $.ajax({
                url: aabDashboard.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aab_activate_plugin',
                    plugin: plugin,
                    nonce: aabDashboard.nonce
                },
                success: function(response) {
                    if (response.success) {
                        btn.text('Active')
                           .removeClass('aab-ext-btn-loading aab-ext-btn-activate')
                           .addClass('aab-ext-btn-active')
                           .prop('disabled', true);

                        // Reload to update the page state
                        location.reload();
                    } else {
                        btn.text(originalText).removeClass('aab-ext-btn-loading').prop('disabled', false);
                        alert(response.data.message || 'Activation failed. Please try again.');
                    }
                },
                error: function() {
                    btn.text(originalText).removeClass('aab-ext-btn-loading').prop('disabled', false);
                    alert('Activation failed. Please try again.');
                }
            });
        },

        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            // Simple tooltip implementation
            $('.aab-tooltip').each(function() {
                const tooltip = $(this);
                const text = tooltip.attr('title');

                if (text) {
                    tooltip.attr('data-tooltip', text).removeAttr('title');
                }
            });
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        AABDashboard.init();
    });

    // Expose to global scope if needed
    window.AABDashboard = AABDashboard;

})(jQuery);

