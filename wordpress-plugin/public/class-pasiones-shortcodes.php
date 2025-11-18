<?php
/**
 * Shortcodes de Pasiones Platform
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Shortcodes {

    public static function init() {
        add_shortcode('pasiones_home', array(__CLASS__, 'home'));
        add_shortcode('pasiones_professionals', array(__CLASS__, 'professionals'));
        add_shortcode('pasiones_categories', array(__CLASS__, 'categories'));
        add_shortcode('pasiones_countries', array(__CLASS__, 'countries'));
        add_shortcode('pasiones_profile', array(__CLASS__, 'profile'));
        add_shortcode('pasiones_dashboard', array(__CLASS__, 'dashboard'));
        add_shortcode('pasiones_memberships', array(__CLASS__, 'memberships'));
        add_shortcode('pasiones_videochat', array(__CLASS__, 'videochat'));
        add_shortcode('pasiones_stream', array(__CLASS__, 'stream'));
    }

    /**
     * Shortcode: Home
     */
    public static function home($atts) {
        ob_start();
        include PASIONES_PLUGIN_DIR . 'templates/home.php';
        return ob_get_clean();
    }

    /**
     * Shortcode: Profesionales
     */
    public static function professionals($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'country' => '',
            'province' => '',
            'per_page' => 12,
        ), $atts);

        ob_start();
        include PASIONES_PLUGIN_DIR . 'templates/professionals.php';
        return ob_get_clean();
    }

    /**
     * Shortcode: Categorías
     */
    public static function categories($atts) {
        ob_start();
        include PASIONES_PLUGIN_DIR . 'templates/categories.php';
        return ob_get_clean();
    }

    /**
     * Shortcode: Países
     */
    public static function countries($atts) {
        ob_start();
        include PASIONES_PLUGIN_DIR . 'templates/countries.php';
        return ob_get_clean();
    }

    /**
     * Shortcode: Perfil
     */
    public static function profile($atts) {
        ob_start();
        include PASIONES_PLUGIN_DIR . 'templates/profile.php';
        return ob_get_clean();
    }

    /**
     * Shortcode: Dashboard
     */
    public static function dashboard($atts) {
        ob_start();
        include PASIONES_PLUGIN_DIR . 'templates/dashboard.php';
        return ob_get_clean();
    }

    /**
     * Shortcode: Membresías
     */
    public static function memberships($atts) {
        ob_start();
        include PASIONES_PLUGIN_DIR . 'templates/memberships.php';
        return ob_get_clean();
    }

    /**
     * Shortcode: Videochat
     */
    public static function videochat($atts) {
        $atts = shortcode_atts(array(
            'professional_id' => 0,
        ), $atts);

        ob_start();
        include PASIONES_PLUGIN_DIR . 'templates/videochat.php';
        return ob_get_clean();
    }

    /**
     * Shortcode: Stream
     */
    public static function stream($atts) {
        $atts = shortcode_atts(array(
            'stream_id' => 0,
        ), $atts);

        ob_start();
        include PASIONES_PLUGIN_DIR . 'templates/stream.php';
        return ob_get_clean();
    }
}
