<?php
/**
 * PASIONES Platform Theme Functions
 *
 * @package Pasiones_Theme
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('PASIONES_THEME_VERSION', '1.0.0');
define('PASIONES_THEME_DIR', get_template_directory());
define('PASIONES_THEME_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function pasiones_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1200, 600, true);
    add_image_size('pasiones-large', 1200, 600, true);
    add_image_size('pasiones-medium', 800, 400, true);
    add_image_size('pasiones-small', 400, 300, true);
    add_image_size('pasiones-avatar', 400, 400, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'pasiones-theme'),
        'footer' => __('Footer Menu', 'pasiones-theme'),
        'professional' => __('Professional Menu', 'pasiones-theme'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for custom logo
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 300,
        'flex-height' => true,
        'flex-width' => true,
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for Block Styles
    add_theme_support('wp-block-styles');

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');

    // Add Elementor support
    add_theme_support('elementor');
}
add_action('after_setup_theme', 'pasiones_theme_setup');

/**
 * Set the content width in pixels
 */
function pasiones_content_width() {
    $GLOBALS['content_width'] = apply_filters('pasiones_content_width', 1200);
}
add_action('after_setup_theme', 'pasiones_content_width', 0);

/**
 * Register widget areas
 */
function pasiones_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'pasiones-theme'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here.', 'pasiones-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => __('Footer 1', 'pasiones-theme'),
        'id' => 'footer-1',
        'description' => __('Footer column 1', 'pasiones-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer 2', 'pasiones-theme'),
        'id' => 'footer-2',
        'description' => __('Footer column 2', 'pasiones-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer 3', 'pasiones-theme'),
        'id' => 'footer-3',
        'description' => __('Footer column 3', 'pasiones-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer 4', 'pasiones-theme'),
        'id' => 'footer-4',
        'description' => __('Footer column 4', 'pasiones-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'pasiones_widgets_init');

/**
 * Enqueue scripts and styles
 */
function pasiones_scripts() {
    // Main stylesheet
    wp_enqueue_style(
        'pasiones-style',
        get_stylesheet_uri(),
        array(),
        PASIONES_THEME_VERSION
    );

    // Google Fonts
    wp_enqueue_style(
        'pasiones-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap',
        array(),
        null
    );

    // Main JavaScript
    wp_enqueue_script(
        'pasiones-scripts',
        PASIONES_THEME_URI . '/assets/js/main.js',
        array('jquery'),
        PASIONES_THEME_VERSION,
        true
    );

    // Localize script for AJAX
    wp_localize_script('pasiones-scripts', 'pasionesAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('pasiones-nonce'),
    ));

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'pasiones_scripts');

/**
 * Elementor Support
 */
function pasiones_elementor_support() {
    // Register Elementor locations
    if (function_exists('elementor_theme_do_location')) {
        // Header
        add_action('pasiones_header', function() {
            elementor_theme_do_location('header');
        });

        // Footer
        add_action('pasiones_footer', function() {
            elementor_theme_do_location('footer');
        });
    }
}
add_action('after_setup_theme', 'pasiones_elementor_support');

/**
 * Check if using Elementor
 */
function pasiones_is_elementor_page() {
    return (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->preview->is_preview_mode());
}

/**
 * Add custom body classes
 */
function pasiones_body_classes($classes) {
    // Add class for Elementor pages
    if (pasiones_is_elementor_page()) {
        $classes[] = 'elementor-page';
    }

    // Add class if sidebar is active
    if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'has-sidebar';
    }

    // Add class for professional post type
    if (is_singular('professional')) {
        $classes[] = 'professional-single';
    }

    return $classes;
}
add_filter('body_class', 'pasiones_body_classes');

/**
 * Custom template tags
 */
require PASIONES_THEME_DIR . '/inc/template-tags.php';

/**
 * Functions which enhance the theme
 */
require PASIONES_THEME_DIR . '/inc/template-functions.php';

/**
 * Customizer additions
 */
require PASIONES_THEME_DIR . '/inc/customizer.php';

/**
 * Integration with Pasiones Plugin
 */
if (class_exists('Pasiones_Platform')) {
    require PASIONES_THEME_DIR . '/inc/plugin-integration.php';
}

/**
 * Custom Walker for Navigation Menu
 */
class Pasiones_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu dropdown-menu\">\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if ($args->walker->has_children) {
            $classes[] = 'has-dropdown';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= $indent . '<li' . $class_names . '>';

        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts['class'] = 'nav-link';

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Add SVG support
 */
function pasiones_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'pasiones_mime_types');

/**
 * Excerpt length
 */
function pasiones_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'pasiones_excerpt_length');

/**
 * Excerpt more
 */
function pasiones_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'pasiones_excerpt_more');

/**
 * Custom pagination
 */
function pasiones_pagination() {
    global $wp_query;

    if ($wp_query->max_num_pages <= 1) {
        return;
    }

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $max = $wp_query->max_num_pages;

    echo '<nav class="pagination-wrapper" aria-label="Page navigation">';
    echo '<ul class="pagination">';

    // Previous button
    if ($paged > 1) {
        echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($paged - 1) . '">← Previous</a></li>';
    }

    // Page numbers
    for ($i = 1; $i <= $max; $i++) {
        $active = ($paged == $i) ? ' active' : '';
        echo '<li class="page-item' . $active . '"><a class="page-link" href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';
    }

    // Next button
    if ($paged < $max) {
        echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($paged + 1) . '">Next →</a></li>';
    }

    echo '</ul>';
    echo '</nav>';
}
