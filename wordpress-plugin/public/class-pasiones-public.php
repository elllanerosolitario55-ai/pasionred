<?php
/**
 * Funcionalidad Pública de Pasiones Platform
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Public {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_filter('template_include', array($this, 'custom_templates'));
        add_action('wp_ajax_search_professionals', array($this, 'ajax_search_professionals'));
        add_action('wp_ajax_nopriv_search_professionals', array($this, 'ajax_search_professionals'));
    }

    /**
     * Cargar templates personalizados
     */
    public function custom_templates($template) {
        if (is_singular('pasiones_professional')) {
            $custom_template = PASIONES_PLUGIN_DIR . 'templates/single-professional.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }

        return $template;
    }

    /**
     * AJAX: Buscar profesionales
     */
    public function ajax_search_professionals() {
        $search = sanitize_text_field($_GET['s']);
        $category = sanitize_text_field($_GET['category']);
        $country = sanitize_text_field($_GET['country']);
        $province = sanitize_text_field($_GET['province']);

        $args = array(
            'post_type' => 'pasiones_professional',
            'posts_per_page' => 12,
            's' => $search,
        );

        if ($category) {
            $args['tax_query'][] = array(
                'taxonomy' => 'pasiones_category',
                'field' => 'slug',
                'terms' => $category,
            );
        }

        if ($country) {
            $args['tax_query'][] = array(
                'taxonomy' => 'pasiones_country',
                'field' => 'slug',
                'terms' => $country,
            );
        }

        if ($province) {
            $args['tax_query'][] = array(
                'taxonomy' => 'pasiones_province',
                'field' => 'slug',
                'terms' => $province,
            );
        }

        $query = new WP_Query($args);

        $professionals = array();

        while ($query->have_posts()) {
            $query->the_post();

            $professionals[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                'permalink' => get_permalink(),
                'rating' => Pasiones_Reviews::get_average_rating(get_the_ID()),
            );
        }

        wp_reset_postdata();

        wp_send_json_success($professionals);
    }
}
