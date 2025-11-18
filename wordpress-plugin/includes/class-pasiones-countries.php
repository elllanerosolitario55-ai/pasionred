<?php
/**
 * Sistema de Países y Provincias
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Countries {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_ajax_get_provinces', array($this, 'ajax_get_provinces'));
        add_action('wp_ajax_nopriv_get_provinces', array($this, 'ajax_get_provinces'));
    }

    /**
     * Obtener provincias de un país
     */
    public static function get_provinces($country_slug) {
        $country = get_term_by('slug', $country_slug, 'pasiones_country');

        if (!$country) {
            return array();
        }

        return get_terms(array(
            'taxonomy' => 'pasiones_province',
            'parent' => $country->term_id,
            'hide_empty' => false,
        ));
    }

    /**
     * AJAX: Obtener provincias
     */
    public function ajax_get_provinces() {
        $country = sanitize_text_field($_GET['country']);

        $provinces = self::get_provinces($country);

        wp_send_json_success($provinces);
    }
}
