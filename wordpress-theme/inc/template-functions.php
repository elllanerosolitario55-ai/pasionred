<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Pasiones_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function pasiones_body_classes_extend($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}
add_filter('body_class', 'pasiones_body_classes_extend');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function pasiones_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'pasiones_pingback_header');

/**
 * Add SVG definitions to footer
 */
function pasiones_include_svg_icons() {
    $svg_icons = PASIONES_THEME_DIR . '/assets/images/svg-icons.svg';

    if (file_exists($svg_icons)) {
        require_once $svg_icons;
    }
}
add_action('wp_footer', 'pasiones_include_svg_icons', 9999);

/**
 * Display SVG icons
 */
function pasiones_get_svg($icon, $size = 24) {
    $svg = '<svg class="icon icon-' . esc_attr($icon) . '" width="' . esc_attr($size) . '" height="' . esc_attr($size) . '" aria-hidden="true">';
    $svg .= ' <use href="#icon-' . esc_html($icon) . '"></use>';
    $svg .= '</svg>';

    return $svg;
}

/**
 * Remove default WordPress gallery styles
 */
add_filter('use_default_gallery_style', '__return_false');

/**
 * Custom excerpt more
 */
function pasiones_custom_excerpt_more($more) {
    if (!is_single()) {
        $more = sprintf(
            '<div class="read-more-wrapper"><a class="read-more-link" href="%1$s">%2$s</a></div>',
            esc_url(get_permalink(get_the_ID())),
            __('Leer más', 'pasiones-theme')
        );
    }
    return $more;
}
add_filter('excerpt_more', 'pasiones_custom_excerpt_more');

/**
 * Filter the except length
 */
function pasiones_custom_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'pasiones_custom_excerpt_length', 999);

/**
 * Add responsive container to embeds
 */
function pasiones_embed_html($html) {
    return '<div class="video-container">' . $html . '</div>';
}
add_filter('embed_oembed_html', 'pasiones_embed_html', 10, 3);
add_filter('video_embed_html', 'pasiones_embed_html');

/**
 * Modify tag cloud widget
 */
function pasiones_widget_tag_cloud_args($args) {
    $args['largest'] = 18;
    $args['smallest'] = 14;
    $args['unit'] = 'px';
    $args['number'] = 20;
    return $args;
}
add_filter('widget_tag_cloud_args', 'pasiones_widget_tag_cloud_args');

/**
 * Custom logo classes
 */
function pasiones_custom_logo($html) {
    $html = str_replace('custom-logo-link', 'custom-logo-link site-logo', $html);
    $html = str_replace('custom-logo', 'custom-logo img-fluid', $html);
    return $html;
}
add_filter('get_custom_logo', 'pasiones_custom_logo');

/**
 * Add custom image sizes to media library
 */
function pasiones_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'pasiones-large' => __('Pasiones Large', 'pasiones-theme'),
        'pasiones-medium' => __('Pasiones Medium', 'pasiones-theme'),
        'pasiones-small' => __('Pasiones Small', 'pasiones-theme'),
        'pasiones-avatar' => __('Pasiones Avatar', 'pasiones-theme'),
    ));
}
add_filter('image_size_names_choose', 'pasiones_custom_image_sizes');

/**
 * Disable emoji scripts
 */
function pasiones_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'pasiones_disable_emojis');

/**
 * Remove query strings from static resources
 */
function pasiones_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'pasiones_remove_query_strings', 15, 1);
add_filter('style_loader_src', 'pasiones_remove_query_strings', 15, 1);

/**
 * Move scripts to footer
 */
function pasiones_move_scripts_to_footer() {
    remove_action('wp_head', 'wp_print_scripts');
    remove_action('wp_head', 'wp_print_head_scripts', 9);
    remove_action('wp_head', 'wp_enqueue_scripts', 1);
}
add_action('wp_enqueue_scripts', 'pasiones_move_scripts_to_footer');

/**
 * Add async/defer to scripts
 */
function pasiones_async_scripts($tag, $handle, $src) {
    $async_scripts = array(
        'pasiones-scripts',
    );

    if (in_array($handle, $async_scripts)) {
        return '<script src="' . $src . '" defer></script>' . "\n";
    }

    return $tag;
}
add_filter('script_loader_tag', 'pasiones_async_scripts', 10, 3);

/**
 * Preload fonts
 */
function pasiones_preload_fonts() {
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php
}
add_action('wp_head', 'pasiones_preload_fonts', 1);
