<?php

/**
 * Admin Support Page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/class-remote-notice-client.php';

add_action( 'admin_init', function() {
    if ( class_exists( 'Remote_Notice_Client' ) ) {
        Remote_Notice_Client::init( 'AAB', [
            'api_url'        => 'https://manage.spider-themes.net/wp-json/noticepilot/v1/content/aab',
            'plugin_version' => AAGB_VERSION,
            'is_pro'         => aab_fs()->can_use_premium_code(),
        ]);
    }
});
// Include Documentation Builder page
require_once plugin_dir_path(__FILE__) . 'documentation-builder.php';

add_action('admin_menu', 'aab_plugin_admin_page');
add_action('admin_enqueue_scripts', 'aab_admin_page_assets');

add_action('admin_head', function () {
    if (aab_admin_pages()) {
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }
});

if (! function_exists('aab_admin_pages')) {
    function aab_admin_pages($pages = [])
    {
        // if $pages is string, convert it to an array
        if (is_string($pages)) {
            $pages = [$pages];
        }

        if (empty($pages)) {
            // Default admin pages of Advanced Accordion Block
            $admin_pages = ! empty($_GET['page']) ? in_array(sanitize_text_field($_GET['page']), [
                'aab-documentation-builder',
                'aab-settings',
                'aab-block-usage-table',
                'aab-settings-account',
            ], true) : '';
        } else {
            // Selected admin pages of Advanced Accordion Block
            $admin_pages = ! empty($_GET['page']) ? in_array(sanitize_text_field($_GET['page']), $pages, true) : '';
        }

        if ($admin_pages) {
            return true;
        }
    }
}

// Admin Assets
if (! function_exists('aab_admin_page_assets')) {
    function aab_admin_page_assets(): void
    {
        $page = $_GET['page'] ?? '';

        // Load assets for dashboard pages
        if ('aab-settings' === $page) {
            // Modern Dashboard Styles
            wp_enqueue_style(
                'aab-dashboard-modern-css',
                plugins_url('assets/css/dashboard-modern.css', __FILE__),
                [],
                AAGB_VERSION
            );

            // Modern Dashboard Scripts
            wp_enqueue_script(
                'aab-dashboard-modern-js',
                plugins_url('assets/js/dashboard-modern.js', __FILE__),
                ['jquery'],
                AAGB_VERSION,
                true
            );

            wp_localize_script('aab-dashboard-modern-js', 'aabDashboard', [
                'ajaxurl'   => admin_url('admin-ajax.php'),
                'nonce'     => wp_create_nonce('aab_plugin_action'),
                'isPro'     => aab_fs()->can_use_premium_code(),
                'version'   => AAGB_VERSION,
                'adminUrl'  => admin_url(),
                'pluginUrl' => plugins_url('', __FILE__),
            ]);
        }
        if ('aab-documentation-builder' === $page) {
            wp_enqueue_style('aab-doc-builder-css', plugins_url('assets/css/documentation-builder.css', __FILE__), [], '1.0.0');
            wp_enqueue_script('aab-doc-builder-js', plugins_url('assets/js/documentation-builder.js', __FILE__), ['jquery'], '1.0.0', true);
            wp_localize_script('aab-doc-builder-js', 'aabDocBuilder', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('aab_plugin_action'),
            ]);
        }
    }
}

/**
 * Registers an admin page and a submenu page for the Accordion Block plugin in the WordPress dashboard.
 *
 * @return void
 */
if (! function_exists('aab_plugin_admin_page')) {
    function aab_plugin_admin_page()
    {
        add_menu_page(
            'Accordion Block',  // Page title
            'Accordion Block',  // Menu title
            'manage_options',      // Capability
            'aab-settings',        // Menu slug
            'aab_admin_page_content_callback', // No callback, will redirect instead
            'data:image/svg+xml;base64,' . base64_encode(preg_replace(
                '/\s+/',
                ' ',
                file_get_contents(plugin_dir_path(__FILE__) . 'assets/custom-icon.svg')
            )),
            // Path to your SVG file,
            26
        );

        add_submenu_page(
            'aab-settings',
            'AAB Usage Table',
            'AAB Usage Table',
            'manage_options',
            'aab-block-usage-table',
            'aab_render_block_usage_table',
        );

        // Only show Documentation Builder if EazyDocs is not active
        if (! is_plugin_active('eazydocs/eazydocs.php')) {
            add_submenu_page(
                'aab-settings',
                'Documentation Builder',
                'Documentation Builder',
                'manage_options',
                'aab-documentation-builder',
                'aab_documentation_builder_page',
            );
        }
    }
}
/**
 * Callback function to render the content of the admin page for the Advanced Accordion Block plugin.
 *
 * This function outputs the HTML structure for the admin page, including a welcome section, a quick introduction
 * to the plugin's functionality, an embedded video tutorial from YouTube, key features listed in sections,
 * and support links to assist users.
 *
 * @return void
 */

if (! function_exists('aab_admin_page_content_callback')) {
    function aab_admin_page_content_callback(): void
    {
        // Check user capabilities
        if (! current_user_can('manage_options')) {
            return;
        }

        $is_pro = aab_fs()->can_use_premium_code();
        $current_user = wp_get_current_user();
        $user_display_name = ! empty($current_user->display_name) ? $current_user->display_name : $current_user->user_login;
        $version = defined('AAGB_VERSION') ? AAGB_VERSION : '1.0.0';

        // Get plugin status for extensions
        $extensions = aab_get_extension_plugins_status();

?>
        <div class="aab-dashboard-wrap">
            <!-- Header Navigation -->
            <header class="aab-header" role="banner">
                <div class="aab-header-left">
                    <div class="aab-logo">
                        <div class="aab-logo-icon" aria-hidden="true">
                            <svg width="45" height="45" viewBox="0 0 95 94" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M47.5 9.7948C11.2046 9.7948 9.89899 11.0866 9.89899 47C9.89899 82.9134 11.2046 84.2052 47.5 84.2052C83.7954 84.2052 85.101 82.9134 85.101 47C85.101 11.0866 83.7954 9.7948 47.5 9.7948Z" fill="#5FE4D4" />
                                <path d="M72.7404 58.717H22.4824C21.9663 58.717 21.5474 59.1218 21.5474 59.6205V66.849C21.5474 67.3478 21.9663 67.7526 22.4824 67.7526H72.7404C73.2566 67.7526 73.6755 67.3478 73.6755 66.849V59.6205C73.6755 59.1209 73.2575 58.717 72.7404 58.717Z" fill="white" fill-opacity="0.7" />
                                <path d="M72.7406 58.4448H22.4826C21.8121 58.4448 21.267 58.9716 21.267 59.6194V66.8479C21.267 67.4958 21.8121 68.0226 22.4826 68.0226H72.7406C73.411 68.0226 73.9562 67.4958 73.9562 66.8479V59.6194C73.9562 58.9716 73.411 58.4448 72.7406 58.4448ZM22.4826 67.4804C22.1216 67.4804 21.828 67.1967 21.828 66.8479V59.6194C21.828 59.2706 22.1216 58.9869 22.4826 58.9869H72.7406C73.1015 58.9869 73.3951 59.2706 73.3951 59.6194V66.8479C73.3951 67.1967 73.1015 67.4804 72.7406 67.4804H22.4826Z" fill="black" fill-opacity="0.5" />
                                <path d="M72.7404 26.2486H22.4824C21.9663 26.2486 21.5474 26.6534 21.5474 27.1522V34.3806C21.5474 34.8794 21.9663 35.2842 22.4824 35.2842H72.7404C73.2566 35.2842 73.6755 34.8794 73.6755 34.3806V27.1522C73.6755 26.6534 73.2575 26.2486 72.7404 26.2486Z" fill="white" fill-opacity="0.7" />
                                <path d="M72.7406 25.9764H22.4826C21.8121 25.9764 21.267 26.5032 21.267 27.151V34.3795C21.267 35.0274 21.8121 35.5542 22.4826 35.5542H72.7406C73.411 35.5542 73.9562 35.0274 73.9562 34.3795V27.151C73.9562 26.5032 73.411 25.9764 72.7406 25.9764ZM22.4826 35.012C22.1216 35.012 21.828 34.7283 21.828 34.3795V27.151C21.828 26.8023 22.1216 26.5185 22.4826 26.5185H72.7406C73.1015 26.5185 73.3951 26.8023 73.3951 27.151V34.3795C73.3951 34.7283 73.1015 35.012 72.7406 35.012H22.4826Z" fill="black" fill-opacity="0.5" />
                                <path d="M72.7404 37.8806H22.4824C21.9663 37.8806 21.5474 38.2854 21.5474 38.7841V55.2181C21.5474 55.7169 21.9663 56.1217 22.4824 56.1217H72.7404C73.2566 56.1217 73.6755 55.7169 73.6755 55.2181V38.7841C73.6755 38.2854 73.2575 37.8806 72.7404 37.8806Z" fill="white" fill-opacity="0.7" />
                                <path d="M72.7406 37.6083H22.4826C21.8121 37.6083 21.267 38.1352 21.267 38.783V55.217C21.267 55.8649 21.8121 56.3916 22.4826 56.3916H72.7406C73.411 56.3916 73.9562 55.8649 73.9562 55.217V38.783C73.9562 38.1352 73.411 37.6083 72.7406 37.6083ZM22.4826 55.8494C22.1216 55.8494 21.828 55.5658 21.828 55.217V38.783C21.828 38.4342 22.1216 38.1505 22.4826 38.1505H72.7406C73.1015 38.1505 73.3951 38.4342 73.3951 38.783V55.217C73.3951 55.5658 73.1015 55.8494 72.7406 55.8494H22.4826ZM26.5609 64.0667H23.5574V63.5246H26.5609V64.0667Z" fill="black" fill-opacity="0.5" />
                                <path d="M26.2971 41.0598H23.2936V40.5176H26.2971V41.0598ZM25.3383 65.2435H24.7773V62.3413H25.3383V65.2435ZM26.5613 31.0329H23.5578V30.4908H26.5613V31.0329Z" fill="black" fill-opacity="0.5" />
                                <path d="M25.3402 32.214H24.7791V29.3118H25.3402V32.214Z" fill="black" fill-opacity="0.5" />
                            </svg>
                        </div>
                    </div>
                    <!-- jQuery UI Tabs Navigation in Header -->
                    <nav id="aab-main-tabs" class="aab-nav aab-header-nav aab-tabs-wrapper" role="navigation" aria-label="<?php esc_attr_e('Dashboard navigation', 'advanced-accordion-block'); ?>">
                        <ul class="aab-header-nav-list aab-tabs-list" role="tablist">
                            <li><a href="#aab-tab-welcome" class="aab-nav-item"><?php esc_html_e('Welcome', 'advanced-accordion-block'); ?></a></li>
                            <?php if (! $is_pro) : ?>
                                <li><a href="#aab-tab-free-vs-pro" class="aab-nav-item"><?php esc_html_e('Lite vs Pro', 'advanced-accordion-block'); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <div class="aab-header-right">
                    <?php if (! $is_pro) : ?>
                        <a href="<?php echo esc_url(aab_fs()->get_upgrade_url()); ?>" class="aab-upgrade-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 20h20l-2-8-4 4-4-8-4 8-4-4-2 8zM5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM12 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM19 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                            </svg>
                            <?php esc_html_e('Unlock Pro Features', 'advanced-accordion-block'); ?>
                        </a>
                    <?php endif; ?>
                    <div class="aab-version-badge">
                        <span class="version"><?php echo esc_html($version); ?></span>
                        <span class="tier <?php echo $is_pro ? 'pro' : ''; ?>"><?php echo $is_pro ? esc_html__('PRO', 'advanced-accordion-block') : esc_html__('LITE', 'advanced-accordion-block'); ?></span>
                    </div>
                    <div class="aab-header-icons">
                        <a href="https://helpdesk.spider-themes.net/docs/advance-accordions" target="_blank" rel="noopener" class="aab-header-icon-btn" title="<?php esc_attr_e('Documentation', 'advanced-accordion-block'); ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                            </svg>
                        </a>
                        <a href="https://advanced-accordion-block.spider-themes.net/changelog/" target="_blank" rel="noopener" class="aab-header-icon-btn" title="<?php esc_attr_e('Changelog', 'advanced-accordion-block'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 24 24" xml:space="preserve" class="">
                                <g>
                                    <path d="M13.1 1A10.927 10.927 0 0 0 2.566 9.223l-.732-1.107a1 1 0 1 0-1.668 1.1l2.2 3.334a1.084 1.084 0 0 0 .634.425 1.024 1.024 0 0 0 .756-.145l3.3-2.223a1 1 0 1 0-1.115-1.659L4.44 9.96A8.909 8.909 0 1 1 13.1 21a8.892 8.892 0 0 1-7.281-3.822 1 1 0 1 0-1.64 1.143A10.881 10.881 0 0 0 24 12 10.963 10.963 0 0 0 13.1 1z" fill="currentColor" class=""></path>
                                    <path d="M13 5.95a1 1 0 0 0-1 1V12a1.04 1.04 0 0 0 .293.707l3 3.027a1.013 1.013 0 0 0 1.414.007 1 1 0 0 0 .006-1.414L14 11.589V6.95a1 1 0 0 0-1-1z" fill="currentColor"></path>
                                </g>
                            </svg>
                            <span class="aab-notification-dot" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Tab Panels Container -->
            <div class="aab-tabs-content">
                <!-- Welcome Tab Panel -->
                <div id="aab-tab-welcome" class="aab-tab-panel" role="tabpanel">
                    <!-- Main Container -->
                    <main class="aab-main-container" role="main">
                        <div class="aab-two-column">
                            <!-- Main Content -->
                            <div class="aab-main-content">
                                <!-- Hero Section -->
                                <section class="aab-card aab-animate-fade-in" aria-labelledby="aab-hero-title">
                                    <div class="aab-card-body">
                                        <div class="aab-hero">
                                            <div class="aab-hero-content">
                                                <p class="aab-hero-greeting"><?php printf(esc_html__('Hello %s,', 'advanced-accordion-block'), esc_html($user_display_name)); ?></p>
                                                <h1 id="aab-hero-title" class="aab-hero-title">
                                                    <?php esc_html_e('Welcome to Advanced Accordion', 'advanced-accordion-block'); ?>
                                                    <span class="aab-hero-badge <?php echo $is_pro ? 'pro' : ''; ?>"><?php echo $is_pro ? esc_html__('PRO', 'advanced-accordion-block') : esc_html__('LITE', 'advanced-accordion-block'); ?></span>
                                                </h1>
                                                <p class="aab-hero-description">
                                                    <?php esc_html_e('Advanced Accordion is a powerful Gutenberg block that lets you create beautiful, fully customizable accordions and FAQ sections. Built with native WordPress components for optimal performance.', 'advanced-accordion-block'); ?>
                                                </p>
                                                <div class="aab-hero-actions">
                                                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=page')); ?>" class="aab-btn aab-btn-primary">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                                            <line x1="12" y1="8" x2="12" y2="16" />
                                                            <line x1="8" y1="12" x2="16" y2="12" />
                                                        </svg>
                                                        <?php esc_html_e('Create New Page', 'advanced-accordion-block'); ?>
                                                    </a>
                                                    <a href="#" class="aab-btn aab-btn-secondary aab-open-video">
                                                        <?php esc_html_e('Watch the Quick Starter Guide', 'advanced-accordion-block'); ?>
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polygon points="5 3 19 12 5 21 5 3" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="aab-hero-video">
                                                <div class="aab-hero-video-overlay" role="button" tabindex="0" aria-label="<?php esc_attr_e('Play video tutorial', 'advanced-accordion-block'); ?>">
                                                    <span class="aab-hero-video-badge">
                                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l6-5-6-5v10z" />
                                                        </svg>
                                                        <?php esc_html_e('Quick Start Guide - v', 'advanced-accordion-block'); ?><?php echo esc_html($version); ?>
                                                    </span>
                                                    <h2 class="aab-hero-video-title"><?php esc_html_e('ACCORDION', 'advanced-accordion-block'); ?></h2>
                                                    <p class="aab-hero-video-subtitle"><?php esc_html_e('GETTING', 'advanced-accordion-block'); ?><br /><?php esc_html_e('STARTED', 'advanced-accordion-block'); ?></p>
                                                    <span class="aab-play-btn" aria-hidden="true">
                                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                                            <polygon points="5 3 19 12 5 21 5 3" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- Available Blocks -->
                                <section class="aab-card aab-animate-fade-in aab-animate-delay-1" aria-labelledby="aab-blocks-title">
                                    <div class="aab-card-header">
                                        <h2 id="aab-blocks-title" class="aab-card-title"><?php esc_html_e('Available Blocks', 'advanced-accordion-block'); ?></h2>
                                        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=page')); ?>" class="aab-card-action"><?php esc_html_e('Create New Page', 'advanced-accordion-block'); ?></a>
                                    </div>
                                    <div class="aab-card-body">
                                        <div class="aab-blocks-grid">
                                            <div class="aab-block-card">
                                                <div class="aab-block-icon">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="3" y="3" width="18" height="6" rx="1" />
                                                        <rect x="3" y="11" width="18" height="3" rx="1" opacity="0.5" />
                                                        <rect x="3" y="16" width="18" height="3" rx="1" opacity="0.5" />
                                                    </svg>
                                                </div>
                                                <div class="aab-block-info">
                                                    <h3 class="aab-block-name"><?php esc_html_e('Separate Accordion', 'advanced-accordion-block'); ?></h3>
                                                    <p class="aab-block-desc"><?php esc_html_e('Individual accordion blocks that work independently. Perfect for single FAQs or standalone collapsible content.', 'advanced-accordion-block'); ?></p>
                                                </div>
                                            </div>
                                            <div class="aab-block-card">
                                                <div class="aab-block-icon">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="3" y="2" width="18" height="5" rx="1" />
                                                        <rect x="3" y="9" width="18" height="5" rx="1" />
                                                        <rect x="3" y="16" width="18" height="5" rx="1" />
                                                    </svg>
                                                </div>
                                                <div class="aab-block-info">
                                                    <h3 class="aab-block-name"><?php esc_html_e('Group Accordion', 'advanced-accordion-block'); ?></h3>
                                                    <p class="aab-block-desc"><?php esc_html_e('Container for multiple accordion items with shared settings. Ideal for FAQ sections and structured content.', 'advanced-accordion-block'); ?></p>
                                                </div>
                                            </div>
                                            <div class="aab-block-card">
                                                <div class="aab-block-icon">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="2" y="4" width="6" height="16" rx="1" />
                                                        <rect x="9" y="4" width="6" height="16" rx="1" opacity="0.7" />
                                                        <rect x="16" y="4" width="6" height="16" rx="1" opacity="0.4" />
                                                    </svg>
                                                </div>
                                                <div class="aab-block-info">
                                                    <h3 class="aab-block-name"><?php esc_html_e('Horizontal Accordion', 'advanced-accordion-block'); ?></h3>
                                                    <p class="aab-block-desc"><?php esc_html_e('Side-by-side accordion layout with titles, subtitles, and icons. Great for feature showcases and comparisons.', 'advanced-accordion-block'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- AI FAQ Generator -->
                                <?php
                                $atml_plugin_file = 'antimanual/antimanual.php';
                                $atml_is_active   = is_plugin_active( $atml_plugin_file );
                                $atml_is_installed = file_exists( WP_PLUGIN_DIR . '/' . $atml_plugin_file );
                                ?>
                                <section class="aab-card aab-ai-faq-card aab-animate-fade-in aab-animate-delay-2" aria-labelledby="aab-ai-faq-title">
                                    <div class="aab-card-body">
                                        <div class="aab-ai-faq-content">
                                            <div class="aab-ai-faq-icon" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                </svg>
                                                <span class="aab-ai-faq-sparkle" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                                        <path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8L12 2z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="aab-ai-faq-text">
                                                <div class="aab-ai-faq-header">
                                                    <h2 id="aab-ai-faq-title" class="aab-ai-faq-title"><?php esc_html_e( 'AI FAQ Generator', 'advanced-accordion-block' ); ?></h2>
                                                    <span class="aab-ai-faq-badge"><?php esc_html_e( 'AI-Powered', 'advanced-accordion-block' ); ?></span>
                                                </div>
                                                <p class="aab-ai-faq-desc"><?php esc_html_e( 'Generate comprehensive FAQ sections instantly with AI. Choose topics, set the tone, and let AI create professional Q&A content — all from a guided 4-step wizard.', 'advanced-accordion-block' ); ?></p>
                                                <div class="aab-ai-faq-features">
                                                    <span class="aab-ai-faq-feature">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12" /></svg>
                                                        <?php esc_html_e( 'Quick Topic Presets', 'advanced-accordion-block' ); ?>
                                                    </span>
                                                    <span class="aab-ai-faq-feature">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12" /></svg>
                                                        <?php esc_html_e( 'Multiple Tone Styles', 'advanced-accordion-block' ); ?>
                                                    </span>
                                                    <span class="aab-ai-faq-feature">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12" /></svg>
                                                        <?php esc_html_e( 'Preview & Edit', 'advanced-accordion-block' ); ?>
                                                    </span>
                                                    <span class="aab-ai-faq-feature">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12" /></svg>
                                                        <?php esc_html_e( 'Export & Publish', 'advanced-accordion-block' ); ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="aab-ai-faq-cta">
                                                <?php if ( $atml_is_active ) : ?>
                                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=atml-faq-generator' ) ); ?>" class="aab-btn aab-btn-ai-faq">
                                                        <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                                                            <path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8L12 2z" />
                                                        </svg>
                                                        <?php esc_html_e( 'Generate FAQs', 'advanced-accordion-block' ); ?>
                                                    </a>
                                                <?php elseif ( $atml_is_installed ) : ?>
                                                    <button class="aab-btn aab-btn-ai-faq aab-ext-btn-activate" data-plugin="<?php echo esc_attr( $atml_plugin_file ); ?>">
                                                        <?php esc_html_e( 'Activate Antimanual', 'advanced-accordion-block' ); ?>
                                                    </button>
                                                <?php else : ?>
                                                    <button class="aab-btn aab-btn-ai-faq aab-ext-btn-install" data-slug="antimanual">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                            <polyline points="7 10 12 15 17 10" />
                                                            <line x1="12" y1="15" x2="12" y2="3" />
                                                        </svg>
                                                        <?php esc_html_e( 'Install Antimanual', 'advanced-accordion-block' ); ?>
                                                    </button>
                                                <?php endif; ?>
                                                <a href="https://wordpress.org/plugins/antimanual/" target="_blank" rel="noopener" class="aab-ai-faq-learn-more">
                                                    <?php esc_html_e( 'Learn More', 'advanced-accordion-block' ); ?>
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <line x1="7" y1="17" x2="17" y2="7" />
                                                        <polyline points="7 7 17 7 17 17" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- Pro Modules -->
                                <section class="aab-card aab-animate-fade-in aab-animate-delay-2" aria-labelledby="aab-pro-modules-title">
                                    <div class="aab-card-header">
                                        <h2 id="aab-pro-modules-title" class="aab-card-title"><?php esc_html_e('Pro Features & Modules', 'advanced-accordion-block'); ?></h2>
                                        <?php if (! $is_pro) : ?>
                                            <a href="<?php echo esc_url(aab_fs()->get_upgrade_url()); ?>" class="aab-card-action"><?php esc_html_e('Upgrade Now', 'advanced-accordion-block'); ?></a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="aab-card-body">
                                        <div class="aab-modules-grid">
                                            <?php
                                            $pro_modules = [
                                                ['name' => __('Instant Search', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/demos/#instant-search-with-toggle'],
                                                ['name' => __('Voting Feedback', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/dynamic-featuers/#voting-feedback'],
                                                ['name' => __('Anchor Links', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/demos/#read-more-button'],
                                                ['name' => __('Q&A Style Layout', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/accordion-layouts/#enable-qa-style'],
                                                ['name' => __('Steps Layout', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/demos/#steps-layout'],
                                                ['name' => __('Checklist Layout', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/demos/#checklist-layout-with-show-more'],
                                                ['name' => __('Autoplay Mode', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/demos/#accordion-auto-play'],
                                                ['name' => __('Read More Button', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/demos/#read-more-button'],
                                                ['name' => __('Category Filter Tabs', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/categories-accordion/#filter-tabs-by-category'],
                                                ['name' => __('Featured Icon/Image', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/title-features/#title-with-featured-image'],
                                                ['name' => __('Auto-Numbering', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/demos/#accordion-auto-numbering'],
                                                ['name' => __('Next/Prev Navigation', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/accordion-navigation/'],
                                                ['name' => __('Subheading Support', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/title-features/#subheading-support'],
                                                ['name' => __('Custom Labels', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/demos/#label'],
                                                ['name' => __('Pagination/Show More Button', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/categories-accordion/#show-more-accordion'],
                                                ['name' => __('FAQ Schema Markup', 'advanced-accordion-block'), 'doc_link' => 'https://advanced-accordion-block.spider-themes.net/demos/'],
                                            ];

                                            foreach ($pro_modules as $module) :
                                            ?>
                                                <div class="aab-module-item">
                                                    <div class="aab-module-info">
                                                        <h3 class="aab-module-name"><?php echo esc_html($module['name']); ?></h3>
                                                        <a href="<?php echo esc_url($module['doc_link']); ?>" target="_blank" rel="noopener" class="aab-module-link"><?php esc_html_e('Demo', 'advanced-accordion-block'); ?></a>
                                                    </div>
                                                    <?php if (! $is_pro) : ?>
                                                        <span class="aab-pro-badge"><?php esc_html_e('PRO', 'advanced-accordion-block'); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </section>

                                <!-- Key Features Grid -->
                                <section class="aab-card aab-animate-fade-in aab-animate-delay-3" aria-labelledby="aab-features-title">
                                    <div class="aab-card-header">
                                        <h2 id="aab-features-title" class="aab-card-title"><?php esc_html_e('Key Features', 'advanced-accordion-block'); ?></h2>
                                        <a href="https://advanced-accordion-block.spider-themes.net/features" target="_blank" rel="noopener" class="aab-card-action"><?php esc_html_e('View All Features', 'advanced-accordion-block'); ?></a>
                                    </div>
                                    <div class="aab-card-body">
                                        <div class="aab-features-grid">
                                            <?php
                                            $features = [
                                                [
                                                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>',
                                                    'title' => __('Gutenberg Native', 'advanced-accordion-block'),
                                                    'desc' => __('Built with native Gutenberg components for seamless integration and optimal performance.', 'advanced-accordion-block'),
                                                    'pro' => false
                                                ],
                                                [
                                                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>',
                                                    'title' => __('Unlimited Nesting', 'advanced-accordion-block'),
                                                    'desc' => __('Create complex accordion structures with unlimited nesting levels.', 'advanced-accordion-block'),
                                                    'pro' => false
                                                ],
                                                [
                                                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>',
                                                    'title' => __('SEO Optimized', 'advanced-accordion-block'),
                                                    'desc' => __('Built-in schema markup for FAQ pages to enhance search visibility.', 'advanced-accordion-block'),
                                                    'pro' => true
                                                ],
                                                [
                                                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>',
                                                    'title' => __('5 Icon Pairs', 'advanced-accordion-block'),
                                                    'desc' => __('Choose from multiple icon styles for expand/collapse indicators.', 'advanced-accordion-block'),
                                                    'pro' => false
                                                ],
                                                [
                                                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
                                                    'title' => __('Fully Responsive', 'advanced-accordion-block'),
                                                    'desc' => __('Looks perfect on all devices and screen sizes.', 'advanced-accordion-block'),
                                                    'pro' => false
                                                ],
                                                [
                                                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>',
                                                    'title' => __('Lightweight & Fast', 'advanced-accordion-block'),
                                                    'desc' => __('Optimized code with minimal footprint for maximum speed.', 'advanced-accordion-block'),
                                                    'pro' => false
                                                ],
                                            ];

                                            foreach ($features as $feature) :
                                            ?>
                                                <article class="aab-feature-card">
                                                    <div class="aab-feature-icon" aria-hidden="true">
                                                        <?php echo $feature['icon']; ?>
                                                    </div>
                                                    <h3 class="aab-feature-title">
                                                        <?php echo esc_html($feature['title']); ?>
                                                        <?php if ($feature['pro'] && ! $is_pro) : ?>
                                                            <span class="aab-pro-badge"><?php esc_html_e('PRO', 'advanced-accordion-block'); ?></span>
                                                        <?php endif; ?>
                                                    </h3>
                                                    <p class="aab-feature-desc"><?php echo esc_html($feature['desc']); ?></p>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </section>

                                <!-- Lite vs Pro Comparison -->
                                <?php if (! $is_pro) : ?>
                                    <section class="aab-card aab-animate-fade-in aab-animate-delay-4" aria-labelledby="aab-comparison-title">
                                        <div class="aab-card-header">
                                            <h2 id="aab-comparison-title" class="aab-card-title"><?php esc_html_e('Lite vs Pro Comparison', 'advanced-accordion-block'); ?></h2>
                                            <a href="<?php echo esc_url(aab_fs()->get_upgrade_url()); ?>" class="aab-card-action"><?php esc_html_e('Get Pro', 'advanced-accordion-block'); ?></a>
                                        </div>
                                        <div class="aab-card-body" style="padding: 0;">
                                            <table class="aab-comparison-table" role="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"><?php esc_html_e('Features', 'advanced-accordion-block'); ?></th>
                                                        <th scope="col"><?php esc_html_e('Lite', 'advanced-accordion-block'); ?></th>
                                                        <th scope="col"><?php esc_html_e('Pro', 'advanced-accordion-block'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $comparison_features = [
                                                        ['feature' => __('Separate Accordion Block', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                        ['feature' => __('Group Accordion Block', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                        ['feature' => __('Horizontal Accordion', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                        ['feature' => __('Nested Accordions', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                        ['feature' => __('Custom Icons', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                        ['feature' => __('Animation Effects', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                        ['feature' => __('FAQ Schema Markup', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                        ['feature' => __('Search & Filter', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                        ['feature' => __('Priority Support', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                    ];

                                                    foreach ($comparison_features as $item) :
                                                    ?>
                                                        <tr class="<?php echo ($item['pro'] && ! $item['free']) ? 'aab-pro-highlight' : ''; ?>">
                                                            <td><?php echo esc_html($item['feature']); ?></td>
                                                            <td>
                                                                <?php if ($item['free']) : ?>
                                                                    <span class="aab-check-icon" aria-label="<?php esc_attr_e('Available', 'advanced-accordion-block'); ?>">
                                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                                            <polyline points="20 6 9 17 4 12" />
                                                                        </svg>
                                                                    </span>
                                                                <?php else : ?>
                                                                    <span class="aab-cross-icon" aria-label="<?php esc_attr_e('Not available', 'advanced-accordion-block'); ?>">
                                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                            <line x1="18" y1="6" x2="6" y2="18" />
                                                                            <line x1="6" y1="6" x2="18" y2="18" />
                                                                        </svg>
                                                                    </span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <span class="aab-check-icon" aria-label="<?php esc_attr_e('Available', 'advanced-accordion-block'); ?>">
                                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                                        <polyline points="20 6 9 17 4 12" />
                                                                    </svg>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </section>
                                <?php endif; ?>

                                <!-- Elementor Users Promotion -->
                                <section class="aab-card aab-elementor-card aab-animate-fade-in" aria-labelledby="aab-elementor-title">
                                    <div class="aab-card-body">
                                        <div class="aab-elementor-content">
                                            <div class="aab-elementor-icon">
                                                <svg width="50" height="50" viewBox="0 0 239 227" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_1363_741)">
                                                        <path d="M238.578 53.6445V43.7531V0H194.824H43.7531C19.5871 0 0 19.5871 0 43.7531H210.87C218.413 49.9364 228.06 53.6445 238.578 53.6445Z" fill="url(#paint0_linear_1363_741)" />
                                                        <path d="M0 173.359V183.25V227.003H43.7531H194.824C218.99 227.003 238.578 207.416 238.578 183.25H27.7077C20.1644 177.067 10.5176 173.359 0 173.359Z" fill="url(#paint1_linear_1363_741)" />
                                                        <path d="M194.824 91.625H0C0 115.791 19.5871 135.378 43.7531 135.378H238.578C238.578 111.212 218.99 91.625 194.824 91.625Z" fill="url(#paint2_linear_1363_741)" />
                                                    </g>
                                                    <defs>
                                                        <linearGradient id="paint0_linear_1363_741" x1="0" y1="26.8223" x2="238.578" y2="26.8222" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#7460FF" />
                                                            <stop offset="1" stop-color="#9D70FF" />
                                                        </linearGradient>
                                                        <linearGradient id="paint1_linear_1363_741" x1="0" y1="200.181" x2="238.578" y2="200.181" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#7460FF" />
                                                            <stop offset="1" stop-color="#9D70FF" />
                                                        </linearGradient>
                                                        <linearGradient id="paint2_linear_1363_741" x1="0" y1="113.502" x2="238.578" y2="113.502" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#7460FF" />
                                                            <stop offset="1" stop-color="#9D70FF" />
                                                        </linearGradient>
                                                        <clipPath id="clip0_1363_741">
                                                            <rect width="238.578" height="227.003" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg>


                                            </div>
                                            <div class="aab-elementor-text">
                                                <h2 id="aab-elementor-title" class="aab-elementor-question"><?php esc_html_e('Want to use Accordion in Elementor?', 'advanced-accordion-block'); ?></h2>
                                                <p class="aab-elementor-desc"><?php esc_html_e('Try Spider Elements - our powerful Elementor addon with 20+ widgets including an advanced Accordion widget with all the features you love!', 'advanced-accordion-block'); ?></p>
                                                <div class="aab-elementor-features">
                                                    <span class="aab-elementor-feature">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="20 6 9 17 4 12" />
                                                        </svg>
                                                        <?php esc_html_e('Nested Accordions', 'advanced-accordion-block'); ?>
                                                    </span>
                                                    <span class="aab-elementor-feature">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="20 6 9 17 4 12" />
                                                        </svg>
                                                        <?php esc_html_e('FAQ Schema', 'advanced-accordion-block'); ?>
                                                    </span>
                                                    <span class="aab-elementor-feature">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="20 6 9 17 4 12" />
                                                        </svg>
                                                        <?php esc_html_e('Custom Icons', 'advanced-accordion-block'); ?>
                                                    </span>
                                                    <span class="aab-elementor-feature">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="20 6 9 17 4 12" />
                                                        </svg>
                                                        <?php esc_html_e('20+ Widgets', 'advanced-accordion-block'); ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="aab-elementor-cta">
                                                <button class="aab-btn aab-btn-elementor aab-ext-btn-install" data-slug="spider-elements">
                                                    <?php
                                                    $plugin_file = 'spider-elements/spider-elements.php';

                                                    if (! file_exists(WP_PLUGIN_DIR . '/' . $plugin_file)) :
                                                    ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 515.283 515.283" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                                            <g>
                                                                <path d="M400.775 515.283H114.507c-30.584 0-59.339-11.911-80.968-33.54C11.911 460.117 0 431.361 0 400.775v-28.628c0-15.811 12.816-28.628 28.627-28.628s28.627 12.817 28.627 28.628v28.628c0 15.293 5.956 29.67 16.768 40.483 10.815 10.814 25.192 16.771 40.485 16.771h286.268c15.292 0 29.669-5.957 40.483-16.771 10.814-10.815 16.771-25.192 16.771-40.483v-28.628c0-15.811 12.816-28.628 28.626-28.628s28.628 12.817 28.628 28.628v28.628c0 30.584-11.911 59.338-33.54 80.968-21.629 21.629-50.384 33.54-80.968 33.54zM257.641 400.774a28.538 28.538 0 0 1-19.998-8.142l-.002-.002-.057-.056-.016-.016c-.016-.014-.03-.029-.045-.044l-.029-.029a.892.892 0 0 0-.032-.031l-.062-.062-114.508-114.509c-11.179-11.179-11.179-29.305 0-40.485 11.179-11.179 29.306-11.18 40.485 0l65.638 65.638V28.627C229.014 12.816 241.83 0 257.641 0s28.628 12.816 28.628 28.627v274.408l65.637-65.637c11.178-11.179 29.307-11.179 40.485 0 11.179 11.179 11.179 29.306 0 40.485L277.883 392.39l-.062.062-.032.031-.029.029c-.014.016-.03.03-.044.044l-.017.016a1.479 1.479 0 0 1-.056.056l-.002.002c-.315.307-.634.605-.96.895a28.441 28.441 0 0 1-7.89 4.995l-.028.012c-.011.004-.02.01-.031.013a28.5 28.5 0 0 1-11.091 2.229z" fill="#fff"></path>
                                                            </g>
                                                        </svg>
                                                        <?php esc_html_e('Install Spider Elements', 'advanced-accordion-block'); ?>

                                                    <?php elseif (! is_plugin_active($plugin_file)) : ?>

                                                        <?php esc_html_e('Activate', 'advanced-accordion-block'); ?>

                                                    <?php else : ?>

                                                        <?php esc_html_e('Active', 'advanced-accordion-block'); ?>

                                                    <?php endif; ?>

                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- Pre-built Patterns Section -->
                                <section class="aab-card aab-animate-fade-in" aria-labelledby="aab-patterns-title">
                                    <div class="aab-card-header">
                                        <h2 id="aab-patterns-title" class="aab-card-title">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; margin-right: 8px; color: var(--aab-primary);">
                                                <rect x="3" y="3" width="7" height="7" />
                                                <rect x="14" y="3" width="7" height="7" />
                                                <rect x="14" y="14" width="7" height="7" />
                                                <rect x="3" y="14" width="7" height="7" />
                                            </svg>
                                            <?php esc_html_e('Pre-built Patterns', 'advanced-accordion-block'); ?>
                                        </h2>
                                        <a href="https://advanced-accordion-block.spider-themes.net/prebuild-pattern-design/" class="aab-card-action"><?php esc_html_e('Browse Patterns', 'advanced-accordion-block'); ?></a>
                                    </div>
                                    <div class="aab-card-body">
                                        <p class="aab-patterns-intro"><?php esc_html_e('Get started instantly with 15+ professionally designed accordion patterns. Just insert and customize!', 'advanced-accordion-block'); ?></p>
                                        <div class="aab-patterns-grid">
                                            <?php
                                            $patterns = [
                                                ['name' => __('Simple Accordion', 'advanced-accordion-block'), 'url' => 'https://advanced-accordion-block.spider-themes.net/prebuild-pattern-design/#simple-accordion', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="6" rx="1"/><rect x="3" y="11" width="18" height="3" rx="1" opacity="0.5"/><rect x="3" y="16" width="18" height="3" rx="1" opacity="0.5"/></svg>'],
                                                ['name' => __('Minimal Border', 'advanced-accordion-block'), 'url' => 'https://advanced-accordion-block.spider-themes.net/prebuild-pattern-design/#minimal-border-style', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="5"/><rect x="3" y="10" width="18" height="5"/><rect x="3" y="17" width="18" height="5"/></svg>'],
                                                ['name' => __('Active Background', 'advanced-accordion-block '), 'url' => 'https://advanced-accordion-block.spider-themes.net/prebuild-pattern-design/#active-background-style', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="6" rx="1" fill="currentColor" opacity="0.2"/><rect x="3" y="11" width="18" height="3" rx="1"/><rect x="3" y="16" width="18" height="3" rx="1"/></svg>'],
                                                ['name' => __('Modern Q&A', 'advanced-accordion-block'), 'url' => 'https://advanced-accordion-block.spider-themes.net/prebuild-pattern-design/#modern-q-a-pro', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="6" cy="6" r="3"/><line x1="11" y1="6" x2="21" y2="6"/><circle cx="6" cy="12" r="3"/><line x1="11" y1="12" x2="21" y2="12"/><circle cx="6" cy="18" r="3"/><line x1="11" y1="18" x2="21" y2="18"/></svg>'],
                                                ['name' => __('Steps Layout', 'advanced-accordion-block'), 'url' => 'https://advanced-accordion-block.spider-themes.net/prebuild-pattern-design/#step-with-accordion-subtitle-pro', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="6" cy="6" r="2"/><line x1="6" y1="8" x2="6" y2="16"/><circle cx="6" cy="18" r="2"/><rect x="10" y="4" width="11" height="4" rx="1"/><rect x="10" y="16" width="11" height="4" rx="1"/></svg>'],
                                                ['name' => __('Auto Numbering', 'advanced-accordion-block'), 'url' => 'https://advanced-accordion-block.spider-themes.net/prebuild-pattern-design/#accordion-auto-numbering', 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><text x="4" y="8" font-size="6" fill="currentColor">1</text><rect x="10" y="3" width="11" height="5" rx="1"/><text x="4" y="16" font-size="6" fill="currentColor">2</text><rect x="10" y="11" width="11" height="5" rx="1"/></svg>'],
                                            ];

                                            foreach ($patterns as $pattern) :
                                            ?>
                                                <a class="aab-pattern-item" href="<?php echo esc_url($pattern['url']); ?>" target="_blank" rel="noopener">
                                                    <div class="aab-pattern-icon"><?php echo $pattern['icon']; ?></div>
                                                    <span class="aab-pattern-name"><?php echo esc_html($pattern['name']); ?></span>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </section>

                                <!-- Perfect For Section -->
                                <section class="aab-card aab-animate-fade-in" aria-labelledby="aab-usecases-title">
                                    <div class="aab-card-header">
                                        <h2 id="aab-usecases-title" class="aab-card-title"><?php esc_html_e('Perfect For', 'advanced-accordion-block'); ?></h2>
                                    </div>
                                    <div class="aab-card-body">
                                        <div class="aab-usecases-grid">
                                            <?php
                                            $usecases = [
                                                ['icon' => '📰', 'title' => __('Bloggers', 'advanced-accordion-block'), 'desc' => __('Organize long-form content into scannable sections', 'advanced-accordion-block')],
                                                ['icon' => '🛍️', 'title' => __('E-commerce', 'advanced-accordion-block'), 'desc' => __('Build product FAQs to reduce support inquiries', 'advanced-accordion-block')],
                                                ['icon' => '📚', 'title' => __('Service Providers', 'advanced-accordion-block'), 'desc' => __('Create comprehensive FAQ sections for agencies', 'advanced-accordion-block')],
                                                ['icon' => '🎓', 'title' => __('Education', 'advanced-accordion-block'), 'desc' => __('Interactive step-by-step guides and courses', 'advanced-accordion-block')],
                                                ['icon' => '🏢', 'title' => __('Corporate', 'advanced-accordion-block'), 'desc' => __('Professional Q&A with advanced filtering', 'advanced-accordion-block')],
                                                ['icon' => '📋', 'title' => __('Documentation', 'advanced-accordion-block'), 'desc' => __('Expandable knowledge base articles', 'advanced-accordion-block')],
                                            ];

                                            foreach ($usecases as $usecase) :
                                            ?>
                                                <div class="aab-usecase-item">
                                                    <span class="aab-usecase-icon"><?php echo $usecase['icon']; ?></span>
                                                    <div class="aab-usecase-content">
                                                        <h4 class="aab-usecase-title"><?php echo esc_html($usecase['title']); ?></h4>
                                                        <p class="aab-usecase-desc"><?php echo esc_html($usecase['desc']); ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </section>

                                <!-- Quick Links Section -->
                                <section class="aab-card aab-animate-fade-in" aria-labelledby="aab-quicklinks-title">
                                    <div class="aab-card-header">
                                        <h2 id="aab-quicklinks-title" class="aab-card-title"><?php esc_html_e('Quick Links', 'advanced-accordion-block'); ?></h2>
                                    </div>
                                    <div class="aab-card-body">
                                        <div class="aab-quicklinks-grid">
                                            <a href="https://advanced-accordion-block.spider-themes.net/demos" target="_blank" rel="noopener" class="aab-quicklink-item">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                                </svg>
                                                <span><?php esc_html_e('View Live Demos', 'advanced-accordion-block'); ?></span>
                                            </a>
                                            <a href="https://helpdesk.spider-themes.net/docs/advance-accordions" target="_blank" rel="noopener" class="aab-quicklink-item">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                                                </svg>
                                                <span><?php esc_html_e('Documentation', 'advanced-accordion-block'); ?></span>
                                            </a>
                                            <a href="https://www.youtube.com/watch?v=K40z8KxojxE" target="_blank" rel="noopener" class="aab-quicklink-item">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polygon points="5 3 19 12 5 21 5 3" />
                                                </svg>
                                                <span><?php esc_html_e('Video Tutorials', 'advanced-accordion-block'); ?></span>
                                            </a>
                                            <a href="https://wordpress.org/support/plugin/advanced-accordion-block/" target="_blank" rel="noopener" class="aab-quicklink-item">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                                    <line x1="12" y1="17" x2="12.01" y2="17" />
                                                </svg>
                                                <span><?php esc_html_e('Get Support', 'advanced-accordion-block'); ?></span>
                                            </a>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            <!-- Sidebar -->
                            <aside class="aab-sidebar" role="complementary">
                                <!-- Extend Your Website -->
                                <section class="aab-card aab-animate-slide-in aab-animate-delay-1" aria-labelledby="aab-extend-title">
                                    <div class="aab-card-header">
                                        <h2 id="aab-extend-title" class="aab-card-title"><?php esc_html_e('Extend Your Website', 'advanced-accordion-block'); ?></h2>
                                    </div>
                                    <div class="aab-card-body">
                                        <div class="aab-extension-list">
                                            <?php foreach ($extensions as $extension) : ?>
                                                <div class="aab-extension-item">
                                                    <div class="aab-extension-icon" style="background: <?php echo esc_attr($extension['color']); ?>20; color: <?php echo esc_attr($extension['color']); ?>;">
                                                        <?php echo $extension['icon']; ?>
                                                    </div>
                                                    <div class="aab-extension-info">
                                                        <h3 class="aab-extension-name"><?php echo esc_html($extension['name']); ?></h3>
                                                        <p class="aab-extension-desc"><?php echo esc_html($extension['desc']); ?></p>
                                                    </div>
                                                    <div class="aab-extension-action">
                                                        <?php if ($extension['status'] === 'active') : ?>
                                                            <span class="aab-ext-btn aab-ext-btn-active"><?php esc_html_e('Active', 'advanced-accordion-block'); ?></span>
                                                        <?php elseif ($extension['status'] === 'installed') : ?>
                                                            <button class="aab-ext-btn aab-ext-btn-activate" data-plugin="<?php echo esc_attr($extension['plugin_file']); ?>"><?php esc_html_e('Activate', 'advanced-accordion-block'); ?></button>
                                                        <?php else : ?>
                                                            <button class="aab-ext-btn aab-ext-btn-install" data-slug="<?php echo esc_attr($extension['slug']); ?>"><?php esc_html_e('Install', 'advanced-accordion-block'); ?></button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </section>

                                <!-- VIP Priority Support -->
                                <section class="aab-card aab-vip-card aab-animate-slide-in aab-animate-delay-2" aria-labelledby="aab-vip-title">
                                    <div class="aab-card-body">
                                        <h2 id="aab-vip-title" class="aab-vip-title">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M2 20h20l-2-8-4 4-4-8-4 8-4-4-2 8zM5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM12 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM19 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                                            </svg>
                                            <?php esc_html_e('VIP Priority Support', 'advanced-accordion-block'); ?>
                                        </h2>
                                        <p class="aab-vip-desc"><?php esc_html_e('Faster and exclusive support service designed for VIP assistance and benefits.', 'advanced-accordion-block'); ?></p>
                                        <a href="<?php echo $is_pro ? esc_url('https://spider-themes.net/contact/') : esc_url(aab_fs()->get_upgrade_url()); ?>" class="aab-vip-link">
                                            <?php esc_html_e('Know More', 'advanced-accordion-block'); ?>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="5" y1="12" x2="19" y2="12" />
                                                <polyline points="12 5 19 12 12 19" />
                                            </svg>
                                        </a>
                                    </div>
                                </section>

                                <!-- Join the Community -->
                                <section class="aab-card aab-community-card aab-animate-slide-in aab-animate-delay-3" aria-labelledby="aab-community-title">
                                    <div class="aab-card-body">
                                        <h2 id="aab-community-title" class="aab-community-title"><?php esc_html_e('Join the Community', 'advanced-accordion-block'); ?></h2>
                                        <p class="aab-community-desc"><?php esc_html_e('Got a question about the plugin, want to share your awesome project or just say hi? Join our wonderful community!', 'advanced-accordion-block'); ?></p>
                                        <a href="https://www.linkedin.com/company/spider-themes/" target="_blank" rel="noopener" class="aab-community-link">
                                            <?php esc_html_e('Join Now', 'advanced-accordion-block'); ?>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="5" y1="12" x2="19" y2="12" />
                                                <polyline points="12 5 19 12 12 19" />
                                            </svg>
                                        </a>
                                    </div>
                                </section>

                                <!-- Rate Us -->
                                <section class="aab-card aab-rating-card aab-animate-slide-in aab-animate-delay-4" aria-labelledby="aab-rating-title">
                                    <div class="aab-card-body">
                                        <h2 id="aab-rating-title" class="aab-rating-title"><?php esc_html_e('Rate Us', 'advanced-accordion-block'); ?></h2>
                                        <div class="aab-rating-stars-info">
                                            <div class="aab-rating-stars" aria-label="<?php esc_attr_e('5 out of 5 stars', 'advanced-accordion-block'); ?>">
                                                <?php for ($i = 0; $i < 5; $i++) : ?>
                                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                                    </svg>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <p class="aab-rating-desc"><?php esc_html_e('We love to hear from you, we would appreciate every single review.', 'advanced-accordion-block'); ?></p>
                                        <a href="https://wordpress.org/support/plugin/advanced-accordion-block/reviews/#new-post" target="_blank" rel="noopener" class="aab-rating-link">
                                            <?php esc_html_e('Submit a Review', 'advanced-accordion-block'); ?>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="5" y1="12" x2="19" y2="12" />
                                                <polyline points="12 5 19 12 12 19" />
                                            </svg>
                                        </a>
                                    </div>
                                </section>

                                <!-- What's New -->
                                <section class="aab-card aab-whats-new-card aab-animate-slide-in aab-animate-delay-4" aria-labelledby="aab-whats-new-title">
                                    <div class="aab-card-header">
                                        <h2 id="aab-whats-new-title" class="aab-card-title"><?php esc_html_e("What's New", 'advanced-accordion-block'); ?></h2>
                                    </div>
                                    <div class="aab-card-body">
                                        <div class="aab-changelog-version">
                                            <span class="aab-changelog-version-number">v<?php echo esc_html($version); ?></span>
                                            <span class="aab-changelog-date"><?php echo esc_html(date_i18n('F j, Y')); ?></span>
                                        </div>
                                        <ul class="aab-changelog-list" role="list">
                                            <li class="aab-changelog-item">
                                                <span class="aab-changelog-type new"><?php esc_html_e('New', 'advanced-accordion-block'); ?></span>
                                                <span class="aab-changelog-text"><?php esc_html_e('Modern dashboard design with enhanced UI/UX', 'advanced-accordion-block'); ?></span>
                                            </li>
                                            <li class="aab-changelog-item">
                                                <span class="aab-changelog-type improve"><?php esc_html_e('Improve', 'advanced-accordion-block'); ?></span>
                                                <span class="aab-changelog-text"><?php esc_html_e('Performance optimizations for faster loading', 'advanced-accordion-block'); ?></span>
                                            </li>
                                            <li class="aab-changelog-item">
                                                <span class="aab-changelog-type fix"><?php esc_html_e('Fix', 'advanced-accordion-block'); ?></span>
                                                <span class="aab-changelog-text"><?php esc_html_e('Minor bug fixes and stability improvements', 'advanced-accordion-block'); ?></span>
                                            </li>
                                        </ul>
                                        <a href="https://advanced-accordion-block.spider-themes.net/changelog/" target="_blank" rel="noopener" class="aab-changelog-more">
                                            <?php esc_html_e('View Full Changelog', 'advanced-accordion-block'); ?>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="7" y1="17" x2="17" y2="7" />
                                                <polyline points="7 7 17 7 17 17" />
                                            </svg>
                                        </a>
                                    </div>
                                </section>
                            </aside>
                        </div>
                    </main>
                </div>
                <!-- End Welcome Tab Panel -->

                <?php if (! $is_pro) : ?>
                    <!-- Lite vs Pro Tab Panel -->
                    <div id="aab-tab-free-vs-pro" class="aab-tab-panel" role="tabpanel">
                        <main class="aab-main-container" role="main">
                            <!-- Page Header -->
                            <div class="aab-page-header aab-animate-fade-in">
                                <div class="aab-page-header-content">
                                    <h1 class="aab-page-title"><?php esc_html_e('Lite vs Pro Comparison', 'advanced-accordion-block'); ?></h1>
                                    <p class="aab-page-description"><?php esc_html_e('Compare the features available in Lite and Pro versions. Upgrade to Pro to unlock all advanced features and priority support.', 'advanced-accordion-block'); ?></p>
                                </div>
                                <a href="<?php echo esc_url(aab_fs()->get_upgrade_url()); ?>" class="aab-btn aab-btn-primary aab-btn-lg">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 20h20l-2-8-4 4-4-8-4 8-4-4-2 8zM5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM12 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM19 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                                    </svg>
                                    <?php esc_html_e('Upgrade to Pro', 'advanced-accordion-block'); ?>
                                </a>
                            </div>

                            <!-- Comparison Table -->
                            <section class="aab-card aab-comparison-card aab-animate-fade-in" aria-labelledby="aab-comparison-title">
                                <div class="aab-card-body" style="padding: 0;">
                                    <table class="aab-comparison-table aab-comparison-table-full" role="table">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="aab-feature-col"><?php esc_html_e('Features', 'advanced-accordion-block'); ?></th>
                                                <th scope="col" class="aab-plan-col aab-plan-free">
                                                    <div class="aab-plan-header">
                                                        <span class="aab-plan-name"><?php esc_html_e('Lite', 'advanced-accordion-block'); ?></span>
                                                        <span class="aab-plan-price"><?php esc_html_e('$0', 'advanced-accordion-block'); ?></span>
                                                    </div>
                                                </th>
                                                <th scope="col" class="aab-plan-col aab-plan-pro">
                                                    <div class="aab-plan-header">
                                                        <span class="aab-plan-badge"><?php esc_html_e('RECOMMENDED', 'advanced-accordion-block'); ?></span>
                                                        <span class="aab-plan-name"><?php esc_html_e('Pro', 'advanced-accordion-block'); ?></span>
                                                        <span class="aab-plan-price"><?php esc_html_e('From $39/year', 'advanced-accordion-block'); ?></span>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Block Types Category -->
                                            <tr class="aab-category-row">
                                                <td colspan="3"><strong><?php esc_html_e('Block Types', 'advanced-accordion-block'); ?></strong></td>
                                            </tr>
                                            <?php
                                            $block_features = [
                                                ['feature' => __('Separate Accordion Block', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Group Accordion Block', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Horizontal Accordion Block', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Accordion Toolbar Block', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Global Defaults Block', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                            ];
                                            foreach ($block_features as $item) :
                                                echo aab_render_comparison_row($item);
                                            endforeach;
                                            ?>

                                            <!-- Core Features Category -->
                                            <tr class="aab-category-row">
                                                <td colspan="3"><strong><?php esc_html_e('Core Features', 'advanced-accordion-block'); ?></strong></td>
                                            </tr>
                                            <?php
                                            $core_features = [
                                                ['feature' => __('Unlimited Accordions', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Unlimited Nested Accordions', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Fully Responsive Design', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Keyboard Accessibility (WCAG)', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Multiple Icon Styles (5 Pairs)', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Custom Colors & Backgrounds', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Border & Border Radius Controls', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Margin & Padding Controls', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Pre-built Patterns (15+)', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Active on Page Load', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Linked Accordions', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                            ];
                                            foreach ($core_features as $item) :
                                                echo aab_render_comparison_row($item);
                                            endforeach;
                                            ?>

                                            <!-- Pro Features Category -->
                                            <tr class="aab-category-row">
                                                <td colspan="3"><strong><?php esc_html_e('Pro Features', 'advanced-accordion-block'); ?></strong></td>
                                            </tr>
                                            <?php
                                            $pro_features = [
                                                ['feature' => __('Instant Search Bar', 'advanced-accordion-block'), 'free' => false, 'pro' => true, 'highlight' => true],
                                                ['feature' => __('Voting Feedback System', 'advanced-accordion-block'), 'free' => false, 'pro' => true, 'highlight' => true],
                                                ['feature' => __('Anchor Links / Deep Linking', 'advanced-accordion-block'), 'free' => false, 'pro' => true, 'highlight' => true],
                                                ['feature' => __('Q&A Style Layout', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                ['feature' => __('Steps Layout with Progress', 'advanced-accordion-block'), 'free' => false, 'pro' => true, 'highlight' => true],
                                                ['feature' => __('Checklist Layout', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                ['feature' => __('Autoplay Functionality', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                ['feature' => __('Open All / Close All Button', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                ['feature' => __('Read More Button', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                ['feature' => __('Category Filter Tabs', 'advanced-accordion-block'), 'free' => false, 'pro' => true, 'highlight' => true],
                                                ['feature' => __('Show More Button (Load More)', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                ['feature' => __('Featured Icon/Image Support', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                ['feature' => __('Auto-Numbering with Colors', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                ['feature' => __('Next/Previous Navigation', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                ['feature' => __('Subheading Support', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                ['feature' => __('Custom Labels/Badges', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                ['feature' => __('Custom CSS Field', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                                ['feature' => __('Hover Activation Event', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                            ];
                                            foreach ($pro_features as $item) :
                                                echo aab_render_comparison_row($item);
                                            endforeach;
                                            ?>

                                            <!-- SEO & Schema Category -->
                                            <tr class="aab-category-row">
                                                <td colspan="3"><strong><?php esc_html_e('SEO & Schema', 'advanced-accordion-block'); ?></strong></td>
                                            </tr>
                                            <?php
                                            $seo_features = [
                                                ['feature' => __('FAQ Schema Markup (JSON-LD)', 'advanced-accordion-block'), 'free' => false, 'pro' => true, 'highlight' => true],
                                                ['feature' => __('Google Rich Results Ready', 'advanced-accordion-block'), 'free' => false, 'pro' => true],
                                            ];
                                            foreach ($seo_features as $item) :
                                                echo aab_render_comparison_row($item);
                                            endforeach;
                                            ?>

                                            <!-- Support Category -->
                                            <tr class="aab-category-row">
                                                <td colspan="3"><strong><?php esc_html_e('Support & Updates', 'advanced-accordion-block'); ?></strong></td>
                                            </tr>
                                            <?php
                                            $support_features = [
                                                ['feature' => __('Community Support (WordPress.org)', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('Priority Email Support', 'advanced-accordion-block'), 'free' => false, 'pro' => true, 'highlight' => true],
                                                ['feature' => __('Regular Updates & Bug Fixes', 'advanced-accordion-block'), 'free' => true, 'pro' => true],
                                                ['feature' => __('New Feature Updates', 'advanced-accordion-block'), 'free' => 'limited', 'pro' => true],
                                            ];
                                            foreach ($support_features as $item) :
                                                echo aab_render_comparison_row($item);
                                            endforeach;
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td class="aab-plan-footer">
                                                    <span class="aab-current-plan"><?php esc_html_e('Current Plan', 'advanced-accordion-block'); ?></span>
                                                </td>
                                                <td class="aab-plan-footer">
                                                    <a href="<?php echo esc_url(aab_fs()->get_upgrade_url()); ?>" class="aab-btn aab-btn-primary">
                                                        <?php esc_html_e('Upgrade Now', 'advanced-accordion-block'); ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </section>

                            <!-- Pro Benefits Section -->
                            <section class="aab-card aab-benefits-card aab-animate-fade-in" aria-labelledby="aab-benefits-title">
                                <div class="aab-card-header">
                                    <h2 id="aab-benefits-title" class="aab-card-title"><?php esc_html_e('Why Upgrade to Pro?', 'advanced-accordion-block'); ?></h2>
                                </div>
                                <div class="aab-card-body">
                                    <div class="aab-benefits-grid">
                                        <div class="aab-benefit-item">
                                            <div class="aab-benefit-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="11" cy="11" r="8" />
                                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                                </svg>
                                            </div>
                                            <h3><?php esc_html_e('Instant Search', 'advanced-accordion-block'); ?></h3>
                                            <p><?php esc_html_e('Help users find specific FAQs quickly with a built-in search bar. Perfect for long FAQ lists.', 'advanced-accordion-block'); ?></p>
                                        </div>
                                        <div class="aab-benefit-item">
                                            <div class="aab-benefit-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3" />
                                                </svg>
                                            </div>
                                            <h3><?php esc_html_e('User Feedback', 'advanced-accordion-block'); ?></h3>
                                            <p><?php esc_html_e('Add voting buttons to measure how helpful your FAQs are. Make data-driven improvements.', 'advanced-accordion-block'); ?></p>
                                        </div>
                                        <div class="aab-benefit-item">
                                            <div class="aab-benefit-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <path d="M12 16v-4M12 8h.01" />
                                                </svg>
                                            </div>
                                            <h3><?php esc_html_e('FAQ Schema SEO', 'advanced-accordion-block'); ?></h3>
                                            <p><?php esc_html_e('Automatic structured data markup for Google Rich Results. Rank higher in search.', 'advanced-accordion-block'); ?></p>
                                        </div>
                                        <div class="aab-benefit-item">
                                            <div class="aab-benefit-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M2 20h20l-2-8-4 4-4-8-4 8-4-4-2 8zM5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM12 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM19 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                                                </svg>
                                            </div>
                                            <h3><?php esc_html_e('Priority Support', 'advanced-accordion-block'); ?></h3>
                                            <p><?php esc_html_e('Get faster responses from our expert support team. Your questions answered within 24 hours.', 'advanced-accordion-block'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- CTA Section -->
                            <section class="aab-cta-section aab-animate-fade-in">
                                <div class="aab-cta-content">
                                    <h2><?php esc_html_e('Ready to unlock all Pro features?', 'advanced-accordion-block'); ?></h2>
                                    <p><?php esc_html_e('Join thousands of users who have upgraded to Pro. 30-day money-back guarantee.', 'advanced-accordion-block'); ?></p>
                                    <div class="aab-cta-buttons">
                                        <a href="<?php echo esc_url(aab_fs()->get_upgrade_url()); ?>" class="aab-btn aab-btn-primary aab-btn-lg">
                                            <?php esc_html_e('Upgrade to Pro Now', 'advanced-accordion-block'); ?>
                                        </a>
                                        <a href="https://advanced-accordion-block.spider-themes.net/demos" target="_blank" rel="noopener" class="aab-btn aab-btn-outline aab-btn-lg">
                                            <?php esc_html_e('View Live Demos', 'advanced-accordion-block'); ?>
                                        </a>
                                    </div>
                                </div>
                            </section>
                        </main>
                    </div>
                    <!-- End Lite vs Pro Tab Panel -->
                <?php endif; ?>
            </div>
            <!-- End Tab Panels Container -->

            <!-- Video Modal -->
            <div id="aab-video-modal" class="aab-video-modal" role="dialog" aria-modal="true" aria-labelledby="aab-video-modal-title">
                <div class="aab-video-modal-content">
                    <button class="aab-video-modal-close" aria-label="<?php esc_attr_e('Close video', 'advanced-accordion-block'); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                    <h3 id="aab-video-modal-title" class="aab-sr-only"><?php esc_html_e('Quick Start Video Guide', 'advanced-accordion-block'); ?></h3>
                    <iframe data-src="https://www.youtube.com/embed/K40z8KxojxE" title="<?php esc_attr_e('Advanced Accordion Block Tutorial', 'advanced-accordion-block'); ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
<?php
    }
}

/**
 * Get extension plugins status
 *
 * @return array
 */
if (! function_exists('aab_get_extension_plugins_status')) {
    function aab_get_extension_plugins_status(): array
    {
        $extensions = [
            [
                'name'        => __('EazyDocs', 'advanced-accordion-block'),
                'desc'        => __('Knowledge Base & Documentation Plugin', 'advanced-accordion-block'),
                'slug'        => 'eazydocs',
                'plugin_file' => 'eazydocs/eazydocs.php',
                'color'       => '#0866ff',
                'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 300 300" fill="none"><mask id="mask0_115_239" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="300" height="300"><path d="M300 0H0V300H300V0Z" fill="white"/></mask><g mask="url(#mask0_115_239)"><path d="M55.3436 111.127L6.68738 123.752C3.93738 124.439 2.56236 127.314 3.84361 129.596C28.0624 173.752 38.7811 209.783 55.3436 289.002V111.127Z" fill="url(#paint0_linear_115_239)"/><path d="M52.2184 75.625L12.9997 84.4375C10.2809 85.0312 8.84342 87.6562 9.81217 90C31.5934 141.5 39.8747 184.813 52.2184 261.438V75.625Z" fill="url(#paint1_linear_115_239)"/><path d="M243.875 67.7793C240.344 67.7793 237.5 64.9043 237.5 61.4043V3.7168H73.1562C60.5625 3.7168 50.375 13.9043 50.375 26.498V275.498C50.375 288.092 60.5937 298.279 73.1562 298.279H276.094C288.687 298.279 298.875 288.092 298.875 275.498V67.7793H243.875Z" fill="url(#paint2_linear_115_239)"/><path d="M299.719 67.6562L298.875 66.7812V67.6562H299.719Z" fill="url(#paint3_linear_115_239)"/><path d="M237.655 3V3.71875H238.343L237.655 3Z" fill="url(#paint4_linear_115_239)"/><path d="M244.03 67.6543H298.905V66.7793L238.343 3.7168H237.655V61.2793C237.655 64.7793 240.499 67.6543 244.03 67.6543Z" fill="url(#paint5_linear_115_239)"/><path d="M244.03 67.6543H298.905V66.7793L238.343 3.7168H237.655V61.2793C237.655 64.7793 240.499 67.6543 244.03 67.6543Z" fill="url(#paint6_linear_115_239)"/><path d="M192.938 112.752H95.2813C92.5313 112.752 90.2812 110.502 90.2812 107.752V95.0645C90.2812 92.3145 92.5313 90.0645 95.2813 90.0645H192.938C195.688 90.0645 197.938 92.3145 197.938 95.0645V107.752C197.969 110.502 195.719 112.752 192.938 112.752Z" fill="white"/><path d="M256.656 159.686H95.2813C92.5313 159.686 90.2812 157.436 90.2812 154.686V142.061C90.2812 139.311 92.5313 137.061 95.2813 137.061H256.656C259.406 137.061 261.656 139.311 261.656 142.061V154.748C261.656 157.436 259.438 159.686 256.656 159.686Z" fill="white"/><path d="M256.656 206.656H95.2813C92.5313 206.656 90.2812 204.406 90.2812 201.656V188.969C90.2812 186.219 92.5313 183.969 95.2813 183.969H256.656C259.406 183.969 261.656 186.219 261.656 188.969V201.656C261.656 204.406 259.438 206.656 256.656 206.656Z" fill="white"/><path d="M256.656 253.596H95.2813C92.5313 253.596 90.2812 251.346 90.2812 248.596V235.908C90.2812 233.158 92.5313 230.908 95.2813 230.908H256.656C259.406 230.908 261.656 233.158 261.656 235.908V248.596C261.656 251.346 259.438 253.596 256.656 253.596Z" fill="white"/></g><defs><linearGradient id="paint0_linear_115_239" x1="76.7592" y1="263.622" x2="22.0239" y2="101.184" gradientUnits="userSpaceOnUse"><stop stop-color="#AEDCFF"/><stop offset="0.546" stop-color="#599FFF"/><stop offset="1" stop-color="#1770FF"/></linearGradient><linearGradient id="paint1_linear_115_239" x1="63.2447" y1="207.82" x2="24.7775" y2="70.6762" gradientUnits="userSpaceOnUse"><stop stop-color="#90E0FF"/><stop offset="0.1722" stop-color="#7DD4FF"/><stop offset="0.5209" stop-color="#4CB6FF"/><stop offset="1" stop-color="#0087FF"/></linearGradient><linearGradient id="paint2_linear_115_239" x1="61.4972" y1="293.171" x2="291.177" y2="4.52807" gradientUnits="userSpaceOnUse"><stop stop-color="#33BCFF"/><stop offset="0.5156" stop-color="#22A5FF"/><stop offset="1" stop-color="#0866FF"/></linearGradient><linearGradient id="paint3_linear_115_239" x1="322.45" y1="43.1785" x2="216.272" y2="153.456" gradientUnits="userSpaceOnUse"><stop stop-color="#90E0FF"/><stop offset="0.1722" stop-color="#7DD4FF"/><stop offset="0.5209" stop-color="#4CB6FF"/><stop offset="1" stop-color="#0087FF"/></linearGradient><linearGradient id="paint4_linear_115_239" x1="258.738" y1="-18.1659" x2="152.559" y2="92.1103" gradientUnits="userSpaceOnUse"><stop stop-color="#90E0FF"/><stop offset="0.1722" stop-color="#7DD4FF"/><stop offset="0.5209" stop-color="#4CB6FF"/><stop offset="1" stop-color="#0087FF"/></linearGradient><linearGradient id="paint5_linear_115_239" x1="63.1999" y1="293.406" x2="292.352" y2="5.42486" gradientUnits="userSpaceOnUse"><stop stop-color="#33BCFF"/><stop offset="0.5156" stop-color="#22A5FF"/><stop offset="1" stop-color="#0866FF"/></linearGradient><linearGradient id="paint6_linear_115_239" x1="290.594" y1="12.5049" x2="184.415" y2="122.781" gradientUnits="userSpaceOnUse"><stop stop-color="#90E0FF"/><stop offset="0.1722" stop-color="#7DD4FF"/><stop offset="0.5209" stop-color="#4CB6FF"/><stop offset="1" stop-color="#0087FF"/></linearGradient></defs></svg>',
            ],
            [
                'name'        => __('Forumax', 'advanced-accordion-block'),
                'desc'        => __('The ultimate WordPress forum enhancement plugin.', 'advanced-accordion-block'),
                'slug'        => 'forumax',
                'plugin_file' => 'forumax/forumax.php',
                'color'       => '#6732c1',
                'icon'        => '<svg width="30" height="24" viewBox="0 0 30 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <mask id="mask0_1512_354" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="30" height="24"> <path d="M29.317 0H0V23.823H29.317V0Z" fill="white"/> </mask> <g mask="url(#mask0_1512_354)"> <path d="M21.2714 0H8.04518C7.32268 0 6.73828 0.521501 6.73828 1.1648V7.515C6.73828 8.1583 7.32318 8.6798 8.04518 8.6798H13.6868L14.6583 10.3687L15.4564 8.6798H21.2709C21.9929 8.6798 22.5783 8.1583 22.5783 7.515V1.1648C22.5788 0.521501 21.9934 0 21.2714 0ZM17.8541 6.104H12.1593V5.4365H17.8541V6.104ZM20.5737 4.4207H9.43958V3.7532H20.5737V4.4207ZM20.5737 2.7369H9.43958V2.0694H20.5737V2.7369Z" fill="url(#paint0_linear_1512_354)"/> <path d="M14.6587 11.1582C12.6889 11.1582 11.0908 12.7818 11.0908 14.7865C11.0908 16.7897 12.6889 18.4139 14.6587 18.4139C16.629 18.4139 18.226 16.7903 18.226 14.7865C18.226 12.7818 16.629 11.1582 14.6587 11.1582Z" fill="url(#paint1_linear_1512_354)"/> <path d="M16.7234 18.7568H12.5935C10.741 18.7568 9.23926 20.2845 9.23926 22.1678V23.8234H20.0767V22.1678C20.0772 20.2845 18.576 18.7568 16.7234 18.7568Z" fill="url(#paint2_linear_1512_354)"/> <path d="M26.8379 6.31757V10.6646C26.8379 11.0669 26.4712 11.3944 26.0199 11.3944H23.5949L23.7048 12.6421L22.4873 11.3944H18.4522C18.0006 11.3944 17.6343 11.0669 17.6343 10.6646V9.24707H21.2708C21.6018 9.24707 21.9133 9.16677 22.1827 9.02527L22.1841 9.02477C22.5266 8.88627 22.7958 8.62907 22.9265 8.31267L22.927 8.31117C23.0602 8.07267 23.1357 7.80237 23.1357 7.51527V5.58887H26.0194C26.4712 5.58887 26.8379 5.91537 26.8379 6.31757Z" fill="url(#paint3_linear_1512_354)"/> <path d="M11.682 9.24707V10.6646C11.682 11.0669 11.3158 11.3944 10.864 11.3944H6.82902L5.61162 12.6421L5.72142 11.3944H3.29642C2.84522 11.3944 2.47852 11.0669 2.47852 10.6646V6.31757C2.47852 5.91537 2.84522 5.58887 3.29642 5.58887H6.18002V7.51477C6.18002 7.80177 6.25562 8.07267 6.38922 8.31117L6.38972 8.31217C6.51992 8.62857 6.78882 8.88527 7.13022 9.02377C7.40052 9.16577 7.71302 9.24707 8.04502 9.24707H11.682Z" fill="url(#paint4_linear_1512_354)"/> <path d="M24.7885 13.6727C23.3643 13.7626 22.2045 14.9421 22.116 16.391C22.0127 18.0818 23.3306 19.4856 24.9718 19.4856C25.0245 19.4856 25.0768 19.4842 25.1289 19.4811H28.5433C28.5826 19.4811 28.594 19.4266 28.5571 19.4114C28.1353 19.234 27.8322 18.7529 27.8322 18.1855V16.6314H27.8307L27.8322 16.5764C27.8322 14.9078 26.4517 13.5681 24.7885 13.6727Z" fill="url(#paint5_linear_1512_354)"/> <path d="M26.628 19.7607H23.3154C21.8311 19.7607 20.6265 20.9851 20.6265 22.4956V23.8231H29.3172V22.4956C29.3172 20.9851 28.1127 19.7607 26.628 19.7607Z" fill="url(#paint6_linear_1512_354)"/> <path d="M4.52835 13.6727C5.95255 13.7626 7.11235 14.9421 7.20075 16.391C7.30415 18.0818 5.98625 19.4856 4.34495 19.4856C4.29225 19.4856 4.24015 19.4842 4.18795 19.4811H0.77305C0.73385 19.4811 0.722351 19.4266 0.759151 19.4114C1.18105 19.234 1.48415 18.7529 1.48415 18.1855V16.6314H1.48565L1.48415 16.5764C1.48465 14.9078 2.86515 13.5681 4.52835 13.6727Z" fill="url(#paint7_linear_1512_354)"/> <path d="M2.6893 19.7607H6.0018C7.4861 19.7607 8.6907 20.9851 8.6907 22.4956V23.8231H0V22.4956C0 20.9851 1.2045 19.7607 2.6893 19.7607Z" fill="url(#paint8_linear_1512_354)"/> </g> <defs> <linearGradient id="paint0_linear_1512_354" x1="14.6585" y1="-0.1783" x2="14.6585" y2="24.3282" gradientUnits="userSpaceOnUse"> <stop stop-color="#9E579D"/> <stop offset="1" stop-color="#574B90"/> </linearGradient> <linearGradient id="paint1_linear_1512_354" x1="14.6584" y1="-0.178595" x2="14.6584" y2="24.3279" gradientUnits="userSpaceOnUse"> <stop stop-color="#9E579D"/> <stop offset="1" stop-color="#574B90"/> </linearGradient> <linearGradient id="paint2_linear_1512_354" x1="14.6583" y1="-0.177868" x2="14.6583" y2="24.3287" gradientUnits="userSpaceOnUse"> <stop stop-color="#9E579D"/> <stop offset="1" stop-color="#574B90"/> </linearGradient> <linearGradient id="paint3_linear_1512_354" x1="22.2363" y1="-0.178433" x2="22.2363" y2="24.3281" gradientUnits="userSpaceOnUse"> <stop stop-color="#9E579D"/> <stop offset="1" stop-color="#574B90"/> </linearGradient> <linearGradient id="paint4_linear_1512_354" x1="7.08042" y1="-0.178433" x2="7.08042" y2="24.3281" gradientUnits="userSpaceOnUse"> <stop stop-color="#9E579D"/> <stop offset="1" stop-color="#574B90"/> </linearGradient> <linearGradient id="paint5_linear_1512_354" x1="25.3451" y1="-0.178174" x2="25.3451" y2="24.3283" gradientUnits="userSpaceOnUse"> <stop stop-color="#9E579D"/> <stop offset="1" stop-color="#574B90"/> </linearGradient> <linearGradient id="paint6_linear_1512_354" x1="24.9718" y1="-0.178043" x2="24.9718" y2="24.3284" gradientUnits="userSpaceOnUse"> <stop stop-color="#9E579D"/> <stop offset="1" stop-color="#574B90"/> </linearGradient> <linearGradient id="paint7_linear_1512_354" x1="3.97165" y1="-0.178174" x2="3.97165" y2="24.3283" gradientUnits="userSpaceOnUse"> <stop stop-color="#9E579D"/> <stop offset="1" stop-color="#574B90"/> </linearGradient> <linearGradient id="paint8_linear_1512_354" x1="4.3454" y1="-0.178043" x2="4.3454" y2="24.3284" gradientUnits="userSpaceOnUse"> <stop stop-color="#9E579D"/> <stop offset="1" stop-color="#574B90"/> </linearGradient> </defs> </svg>',
            ],
            [
                'name'        => __('Antimanual', 'advanced-accordion-block'),
                'desc'        => __('AI Extension plugin for WordPress sites', 'advanced-accordion-block'),
                'slug'        => 'antimanual',
                'plugin_file' => 'antimanual/antimanual.php',
                'color'       => '#0079ff',
                'icon'        => '<?xml version="1.0" encoding="UTF-8"?> <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1024 1024"> <!-- Generator: Adobe Illustrator 29.6.1, SVG Export Plug-In . SVG Version: 2.1.1 Build 9) --> <defs> <style> .st0 { fill: url(#linear-gradient2); }

 .st1 { fill: url(#linear-gradient1); }

 .st2 { fill: url(#linear-gradient); } </style> <linearGradient id="linear-gradient" x1="371.5" y1="377.4" x2="862.2" y2="1083.5" gradientUnits="userSpaceOnUse"> <stop offset="0" stop-color="#0cd9f0"/> <stop offset="1" stop-color="#1941d6"/> </linearGradient> <linearGradient id="linear-gradient1" x1="636" y1="242" x2="1041.4" y2="242" gradientUnits="userSpaceOnUse"> <stop offset="0" stop-color="#0cd9f0"/> <stop offset="1" stop-color="#1941d6"/> </linearGradient> <linearGradient id="linear-gradient2" x1="761" y1="146.5" x2="1040.6" y2="146.5" xlink:href="#linear-gradient1"/> </defs> <path class="st2" d="M520,353.5c.6-2.3,4.3-3.3,6.4-3.6,11.6-1.2,40.7-1.3,52.1,0s2.1.3,3.2.8c4.1,1.6,15.4,24.2,18.4,29.6,28.4,51.2,54.9,103.6,83,155l2.4,1.7c2.6,0,9-12.5,10.7-15.4,31.9-55.5,59.6-113.5,90.4-169.6,1.3-1.6,3-1.8,4.9-2.1,12-1.4,40.8-1.2,53.1,0s7.5,1.9,7.5,7.5v340c-.2,83.4-63.6,153.8-146.6,161.5h-424c-82.1-5.9-145.2-74-149.5-155.4v-381.1c7.7-95.7,72.4-154.1,167.5-157.5,86.1-3,174.3,2,260.6.5,31.2,6.4,29,50.8-3.5,53.6h-267.1c-55.3,3.1-97.2,41.7-104.3,96.6-2.6,127.5-.3,255.3-1.2,382.9,3.4,57.9,46.6,102.9,104.5,107.5h397c55.2-4.2,100-46.5,103.6-102.5v-236.5c0,0-88.3,162.3-88.3,162.3-2.9,1.2-24.2,1.2-28.3.7s-3.6-.5-4.9-2.1c-28.4-49.2-52-101.4-80.4-150.6-1.6-2.8-3.4-6.7-6.2-8.3-.2,3.3.9,6.2,1,9.5,2.3,59.6-1.4,120.9-.5,180.6l-2.5,3.5h-104c-1.7-1.1-2.6-2.7-3.5-4.5-8.5-16.9-11.8-40.9-19.7-58.3-.8-1.7-1.4-3.9-3.5-4.5l-119.3.3c-2.6,1.8-16.7,51.8-20.2,59.8s-3.7,7.7-5.3,7.7h-67c-.8,0-1.9-2.3-1.5-3.4,37.9-103,79.1-204.9,117.1-307.9l1.5-1.5,72.9.9,93.5,235v-232.5ZM350.1,538.9c2.1.4,4.2,1,6.4,1.1,17.4,1.2,47.9,1.6,65.1,0s6.9-.7,7.3-2.6l-38.9-115.4-4,8.5c-8.4,33-28.8,69.8-35.5,102.5-.4,2-.8,4-.3,6Z"/> <g> <path class="st1" d="M817,240c.3,1.3-1,1.1-2,1.5-6.1,2.2-12.9,3.4-19.2,5.8-39.6,14.8-60.1,42.3-70.3,82.8-7.4-47.8-43.1-79.2-89.5-88.5,46.9-9.2,80.4-40.5,90.5-87.5,9.4,46.7,43.8,78.2,90.5,86Z"/> <path class="st0" d="M894,146c.3,1.3-1,1.2-2,1.5-9.3,2.8-17.4,4.2-26.4,8.6-22.9,11.4-34.5,32.4-39.1,56.9-5.5-36.3-30.1-59.3-65.5-66.5,35.6-6.8,58.3-31.5,65.5-66.5,6.5,35.6,31.2,61.3,67.5,66Z"/> </g> </svg>',
            ],
            [
                'name'        => __('Spider Elements', 'advanced-accordion-block'),
                'desc'        => __('More widgets for Elementor', 'advanced-accordion-block'),
                'slug'        => 'spider-elements',
                'plugin_file' => 'spider-elements/spider-elements.php',
                'color'       => '#4C4CF1',
                'icon'        => '<svg width="50" height="50" viewBox="0 0 239 227" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_1363_741)"> <path d="M238.578 53.6445V43.7531V0H194.824H43.7531C19.5871 0 0 19.5871 0 43.7531H210.87C218.413 49.9364 228.06 53.6445 238.578 53.6445Z" fill="url(#paint0_linear_1363_741)"/> <path d="M0 173.359V183.25V227.003H43.7531H194.824C218.99 227.003 238.578 207.416 238.578 183.25H27.7077C20.1644 177.067 10.5176 173.359 0 173.359Z" fill="url(#paint1_linear_1363_741)"/> <path d="M194.824 91.625H0C0 115.791 19.5871 135.378 43.7531 135.378H238.578C238.578 111.212 218.99 91.625 194.824 91.625Z" fill="url(#paint2_linear_1363_741)"/> </g> <defs> <linearGradient id="paint0_linear_1363_741" x1="0" y1="26.8223" x2="238.578" y2="26.8222" gradientUnits="userSpaceOnUse"> <stop stop-color="#7460FF"/> <stop offset="1" stop-color="#9D70FF"/> </linearGradient> <linearGradient id="paint1_linear_1363_741" x1="0" y1="200.181" x2="238.578" y2="200.181" gradientUnits="userSpaceOnUse"> <stop stop-color="#7460FF"/> <stop offset="1" stop-color="#9D70FF"/> </linearGradient> <linearGradient id="paint2_linear_1363_741" x1="0" y1="113.502" x2="238.578" y2="113.502" gradientUnits="userSpaceOnUse"> <stop stop-color="#7460FF"/> <stop offset="1" stop-color="#9D70FF"/> </linearGradient> <clipPath id="clip0_1363_741"> <rect width="238.578" height="227.003" fill="white"/> </clipPath> </defs> </svg>',
            ],
        ];

        // Check plugin status
        foreach ($extensions as &$extension) {
            if (is_plugin_active($extension['plugin_file'])) {
                $extension['status'] = 'active';
            } elseif (file_exists(WP_PLUGIN_DIR . '/' . $extension['plugin_file'])) {
                $extension['status'] = 'installed';
            } else {
                $extension['status'] = 'not_installed';
            }
        }

        return $extensions;
    }
}

/**
 * Helper function to render comparison table row
 */
if (! function_exists('aab_render_comparison_row')) {
    function aab_render_comparison_row($item): string
    {
        $highlight_class = isset($item['highlight']) && $item['highlight'] ? 'aab-pro-highlight' : '';

        $free_icon = '';
        if ($item['free'] === true) {
            $free_icon = '<span class="aab-check-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>';
        } elseif ($item['free'] === 'limited') {
            $free_icon = '<span class="aab-limited-icon">Limited</span>';
        } else {
            $free_icon = '<span class="aab-cross-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span>';
        }

        $pro_icon = '<span class="aab-check-icon aab-check-pro"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>';

        return sprintf(
            '<tr class="%s"><td>%s</td><td>%s</td><td>%s</td></tr>',
            esc_attr($highlight_class),
            esc_html($item['feature']),
            $free_icon,
            $pro_icon
        );
    }
}

/**
 * AJAX handler for installing EazyDocs plugin
 */
add_action('wp_ajax_aab_install_plugin', 'aab_install_plugin_ajax');
if (! function_exists('aab_install_plugin_ajax')) {
    function aab_install_plugin_ajax()
    {
        check_ajax_referer('aab_plugin_action', 'nonce');

        if (! current_user_can('install_plugins')) {
            wp_send_json_error(['message' => 'You do not have permission to install plugins.']);
        }

        $slug = isset($_POST['slug']) ? sanitize_text_field($_POST['slug']) : '';

        if (empty($slug)) {
            wp_send_json_error(['message' => 'Plugin slug is required.']);
        }

        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

        $api = plugins_api('plugin_information', [
            'slug'   => $slug,
            'fields' => ['sections' => false],
        ]);

        if (is_wp_error($api)) {
            wp_send_json_error(['message' => 'Failed to get plugin information: ' . $api->get_error_message()]);
        }

        $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
        $result   = $upgrader->install($api->download_link);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => 'Installation failed: ' . $result->get_error_message()]);
        }

        if (! $result) {
            wp_send_json_error(['message' => 'Installation failed. Please try again.']);
        }

        $plugin_file = $upgrader->plugin_info();
        if (! $plugin_file) {
            wp_send_json_error(['message' => 'Plugin installed but could not determine plugin file.']);
        }

        wp_send_json_success([
            'message'     => 'EazyDocs has been installed successfully!',
            'plugin_file' => $plugin_file,
        ]);
    }
}

/**
 * AJAX handler for activating EazyDocs plugin
 */
add_action('wp_ajax_aab_activate_plugin', 'aab_activate_plugin_ajax');
if (! function_exists('aab_activate_plugin_ajax')) {
    function aab_activate_plugin_ajax()
    {
        check_ajax_referer('aab_plugin_action', 'nonce');

        if (! current_user_can('activate_plugins')) {
            wp_send_json_error(['message' => 'You do not have permission to activate plugins.']);
        }

        $plugin = isset($_POST['plugin']) ? sanitize_text_field($_POST['plugin']) : '';

        if (empty($plugin)) {
            wp_send_json_error(['message' => 'Plugin file is required.']);
        }

        $result = activate_plugin($plugin);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => 'Activation failed: ' . $result->get_error_message()]);
        }

        wp_send_json_success([
            'message'      => 'EazyDocs has been activated successfully!',
            'redirect_url' => admin_url('edit.php?post_type=docs'),
        ]);
    }
}

if (! class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}


/**
 * Renders the Block Usage Table in the WordPress admin interface for the Accordion Block plugin.
 *
 * @return void
 */
if (! function_exists('aab_render_block_usage_table')) {
    function aab_render_block_usage_table()
    {
        $table = new AAB_Block_Usage_Table();
        $table->prepare_items();
        echo '<div class="wrap">';
        echo '<h1>Block Usage</h1>';
        echo '<form method="post">';
        $table->search_box('Search Pages/Posts', 'aab-block-usage-search');
        $table->display();
        echo '</form>';
        echo '</div>';
    }
}
/**
 * Extends the WP_List_Table class to manage and display block usage data in a list table format.
 *
 * This class is designed to handle various actions such as preparing data,
 * managing columns, sorting, filtering, pagination, and bulk actions for displaying
 * the usage of specific block types (e.g., 'Separate Accordion', 'Group Accordion')
 * in WordPress posts and pages.
 */

if (! class_exists('AAB_Block_Usage_Table')) {
    class AAB_Block_Usage_Table extends WP_List_Table
    {
        private $blocks
        = [
            'aab/accordion-block '      => 'Separate Accordion',
            'aab/group-accordion '      => 'Group Accordion',
            'aab/horizontal-accordion ' => 'Horizontal Accordion',
        ];

        public function __construct()
        {
            parent::__construct([
                'singular' => 'block_usage',
                'plural'   => 'block_usages',
                'ajax'     => false,
            ]);
        }

        public function get_columns()
        {
            return [
                'cb'          => '<input type="checkbox" />', // Checkbox for bulk actions
                'title'       => __('Title', 'advanced-accordion-block'),
                'author'      => __('Author', 'advanced-accordion-block'),
                'block_names' => __('Block Name', 'advanced-accordion-block'),
                'block_count' => __('Total Block Count', 'advanced-accordion-block'),
                'post_type'   => __('Post Type', 'advanced-accordion-block'), // Add Post Type Column
                'date'        => __('Date', 'advanced-accordion-block'),
            ];
        }


        public function get_sortable_columns()
        {
            return [
                'title'       => ['title', true],
                'author'      => ['author', false],
                'block_count' => ['block_count', false],
                'date'        => ['date', false],
            ];
        }

        public function get_bulk_actions()
        {
            return [
                'delete' => 'Delete',
            ];
        }

        public function column_cb($item)
        {
            return sprintf('<input type="checkbox" name="page[]" value="%d" />', $item['ID']);
        }

        public function column_title($item)
        {
            $actions = [
                'edit'  => sprintf('<a href="%s">Edit</a>', get_edit_post_link($item['ID'])),
                'trash' => sprintf('<a href="%s" class="submitdelete">Trash</a>', get_delete_post_link($item['ID'])),
                'view'  => sprintf('<a href="%s" target="_blank">View</a>', get_permalink($item['ID'])),
            ];

            return sprintf(
                '<strong><a class="row-title" href="%s">%s</a></strong> %s',
                esc_url(get_edit_post_link($item['ID'])),
                esc_html($item['title']),
                $this->row_actions($actions)
            );
        }

        /**
         * Prepares the items for display in a list table.
         *
         * This method applies various tasks such as searching, filtering, sorting,
         * pagination, and setting column headers for the items in the list table.
         *
         * @return void
         */
        public function prepare_items()
        {
            $search      = isset($_POST['s']) ? sanitize_text_field($_POST['s']) : '';
            $date_filter = isset($_POST['m']) ? sanitize_text_field($_POST['m']) : '';

            $data = $this->get_block_usage_data($search, $date_filter);

            // Handle bulk actions
            $this->process_bulk_action();

            // Handle sorting
            $orderby = $_GET['orderby'] ?? 'title';
            $order   = $_GET['order'] ?? 'asc';

            usort($data, function ($a, $b) use ($orderby, $order) {
                $result = strnatcmp($a[$orderby], $b[$orderby]);

                return ($order === 'asc') ? $result : -$result;
            });

            // Pagination setup
            $per_page     = 20;
            $current_page = $this->get_pagenum();
            $total_items  = count($data);

            $data = array_slice($data, ($current_page - 1) * $per_page, $per_page);

            $this->set_pagination_args([
                'total_items' => $total_items,
                'per_page'    => $per_page,
                'total_pages' => ceil($total_items / $per_page),
            ]);

            $columns  = $this->get_columns();
            $hidden   = [];
            $sortable = $this->get_sortable_columns();

            $this->_column_headers = [$columns, $hidden, $sortable];
            $this->items           = $data;
        }

        public function column_default($item, $column_name)
        {
            return $item[$column_name] ?? '';
        }


        private function get_block_usage_data($search = '', $date_filter = '')
        {
            global $wpdb;

            $data         = [];
            $where_clause = '';

            if (! empty($search)) {
                $where_clause .= $wpdb->prepare(
                    "AND post_title LIKE %s",
                    '%' . $wpdb->esc_like($search) . '%'
                );
            }

            if (! empty($date_filter)) {
                $where_clause .= $wpdb->prepare(
                    "AND DATE_FORMAT(post_date, '%%Y%%m') = %s",
                    $date_filter
                );
            }

            $block_search = [];
            foreach (array_keys($this->blocks) as $block_name) {
                $search_string  = '<!-- wp:' . $block_name;
                $block_search[] = $wpdb->prepare('post_content LIKE %s', '%' . $wpdb->esc_like($search_string) . '%');
            }

            if (! empty($block_search)) {
                $where_clause .= ' AND (' . implode(' OR ', $block_search) . ')';
            }

            $posts = $wpdb->get_results( "
            SELECT ID, post_title, post_content, post_author, post_date, post_type
            FROM {$wpdb->posts} 
            WHERE post_status = 'publish' 
            AND (post_type = 'post' OR post_type = 'page')
            $where_clause
        ");

            if ( $posts ) {
                $author_ids = [];
                foreach ( $posts as $post ) {
                    $author_ids[] = $post->post_author;
                }
                $author_ids = array_unique( $author_ids );
                if ( function_exists( 'update_user_cache' ) ) {
                    update_user_cache( $author_ids );
                }
            }

            foreach ( $posts as $post ) {
                $block_summary = [];
                $total_count   = 0;
                foreach ($this->blocks as $block_name => $block_label) {
                    $block_count = substr_count($post->post_content, '<!-- wp:' . $block_name);

                    if ($block_count > 0) {
                        $block_summary[] = sprintf('%s (%d)', esc_html($block_label), $block_count);
                        $total_count     += $block_count;
                    }
                }

                if (! empty($block_summary)) {
                    $data[] = [
                            'ID'          => $post->ID,
                            'title'       => $post->post_title,
                            'author'      => get_the_author_meta( 'display_name', $post->post_author ),
                            'block_names' => implode( ', ', $block_summary ),
                            'block_count' => $total_count,
                            'post_type'   => $post->post_type,
                            'date'        => gmdate( 'Y/m/d', strtotime( $post->post_date ) ),
                    ];
                }
            }

            return $data;
        }

        /**
         * Check if a block type exists
         *
         * @param string $block_name The block name to check.
         *
         * @return bool Whether the block type exists.
         */
        private function process_bulk_action() {
            if ( 'delete' === $this->current_action() ) {
                check_admin_referer( 'bulk-block_usages' );

                // Ensure 'page' is an array before processing
                $post_ids = isset($_POST['page']) && is_array($_POST['page']) ? array_map('intval', $_POST['page']) : [];

                // Ensure no null values are passed
                $post_ids = array_filter($post_ids, function ($id) {
                    return ! is_null($id);
                });

                if ( ! empty( $post_ids ) ) {
                    foreach ( $post_ids as $post_id ) {
                        if ( get_post_status( $post_id ) && current_user_can( 'delete_post', $post_id ) ) {
                            wp_delete_post( $post_id, true );
                        }
                    }
                }
            }
        }

        protected function extra_tablenav($which)
        {
            if ($which === 'top') {
                $this->months_dropdown('page');
                submit_button('Filter', '', 'filter_action', false, ['id' => 'post-query-submit']);
            }
        }
    }
}
