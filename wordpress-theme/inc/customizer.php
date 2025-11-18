<?php
/**
 * Theme Customizer
 *
 * @package Pasiones_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add postMessage support for site title and description
 */
function pasiones_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector' => '.site-title a',
                'render_callback' => 'pasiones_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector' => '.site-description',
                'render_callback' => 'pasiones_customize_partial_blogdescription',
            )
        );
    }

    // Hero Section
    $wp_customize->add_section('pasiones_hero', array(
        'title' => __('Hero Section', 'pasiones-theme'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('pasiones_hero_title', array(
        'default' => __('Conecta con Profesionales', 'pasiones-theme'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('pasiones_hero_title', array(
        'label' => __('Hero Title', 'pasiones-theme'),
        'section' => 'pasiones_hero',
        'type' => 'text',
    ));

    $wp_customize->add_setting('pasiones_hero_description', array(
        'default' => __('Encuentra expertos verificados para ayudarte', 'pasiones-theme'),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('pasiones_hero_description', array(
        'label' => __('Hero Description', 'pasiones-theme'),
        'section' => 'pasiones_hero',
        'type' => 'textarea',
    ));

    // Colors
    $wp_customize->add_section('pasiones_colors', array(
        'title' => __('Colors', 'pasiones-theme'),
        'priority' => 40,
    ));

    $wp_customize->add_setting('pasiones_primary_color', array(
        'default' => '#ec4899',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'pasiones_primary_color', array(
        'label' => __('Primary Color', 'pasiones-theme'),
        'section' => 'pasiones_colors',
    )));

    $wp_customize->add_setting('pasiones_secondary_color', array(
        'default' => '#3b82f6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'pasiones_secondary_color', array(
        'label' => __('Secondary Color', 'pasiones-theme'),
        'section' => 'pasiones_colors',
    )));

    // Footer
    $wp_customize->add_section('pasiones_footer', array(
        'title' => __('Footer Settings', 'pasiones-theme'),
        'priority' => 50,
    ));

    $wp_customize->add_setting('pasiones_footer_copyright', array(
        'default' => sprintf(__('&copy; %s. Todos los derechos reservados.', 'pasiones-theme'), date('Y')),
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('pasiones_footer_copyright', array(
        'label' => __('Footer Copyright Text', 'pasiones-theme'),
        'section' => 'pasiones_footer',
        'type' => 'textarea',
    ));

    // Social Media
    $wp_customize->add_section('pasiones_social', array(
        'title' => __('Social Media', 'pasiones-theme'),
        'priority' => 60,
    ));

    $social_networks = array(
        'facebook' => 'Facebook',
        'twitter' => 'Twitter',
        'instagram' => 'Instagram',
        'linkedin' => 'LinkedIn',
        'youtube' => 'YouTube',
    );

    foreach ($social_networks as $network => $label) {
        $wp_customize->add_setting("pasiones_social_{$network}", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control("pasiones_social_{$network}", array(
            'label' => $label . ' URL',
            'section' => 'pasiones_social',
            'type' => 'url',
        ));
    }

    // Layout
    $wp_customize->add_section('pasiones_layout', array(
        'title' => __('Layout Options', 'pasiones-theme'),
        'priority' => 70,
    ));

    $wp_customize->add_setting('pasiones_sidebar_position', array(
        'default' => 'right',
        'sanitize_callback' => 'pasiones_sanitize_select',
    ));

    $wp_customize->add_control('pasiones_sidebar_position', array(
        'label' => __('Sidebar Position', 'pasiones-theme'),
        'section' => 'pasiones_layout',
        'type' => 'select',
        'choices' => array(
            'left' => __('Left', 'pasiones-theme'),
            'right' => __('Right', 'pasiones-theme'),
            'none' => __('No Sidebar', 'pasiones-theme'),
        ),
    ));
}
add_action('customize_register', 'pasiones_customize_register');

/**
 * Render the site title for the selective refresh partial.
 */
function pasiones_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 */
function pasiones_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Sanitize select
 */
function pasiones_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function pasiones_customize_preview_js() {
    wp_enqueue_script(
        'pasiones-customizer',
        PASIONES_THEME_URI . '/assets/js/customizer.js',
        array('customize-preview'),
        PASIONES_THEME_VERSION,
        true
    );
}
add_action('customize_preview_init', 'pasiones_customize_preview_js');

/**
 * Output custom CSS for customizer settings
 */
function pasiones_customizer_css() {
    $primary_color = get_theme_mod('pasiones_primary_color', '#ec4899');
    $secondary_color = get_theme_mod('pasiones_secondary_color', '#3b82f6');
    ?>
    <style type="text/css">
        :root {
            --color-primary: <?php echo esc_html($primary_color); ?>;
            --color-secondary: <?php echo esc_html($secondary_color); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'pasiones_customizer_css');
