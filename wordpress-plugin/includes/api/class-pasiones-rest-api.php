<?php
/**
 * API REST de Pasiones Platform
 * Para integración con aplicación Next.js (Opción 3)
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_REST_API {

    public static function init() {
        add_action('rest_api_init', array(__CLASS__, 'register_routes'));
    }

    /**
     * Registrar rutas de la API
     */
    public static function register_routes() {
        $namespace = 'pasiones/v1';

        // Profesionales
        register_rest_route($namespace, '/professionals', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_professionals'),
            'permission_callback' => '__return_true',
        ));

        // Categorías
        register_rest_route($namespace, '/categories', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_categories'),
            'permission_callback' => '__return_true',
        ));

        // Países
        register_rest_route($namespace, '/countries', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_countries'),
            'permission_callback' => '__return_true',
        ));

        // Provincias
        register_rest_route($namespace, '/provinces/(?P<country>[\w-]+)', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_provinces'),
            'permission_callback' => '__return_true',
        ));

        // Membresías
        register_rest_route($namespace, '/memberships', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_memberships'),
            'permission_callback' => '__return_true',
        ));

        // Autenticación (SSO)
        register_rest_route($namespace, '/auth/token', array(
            'methods' => 'POST',
            'callback' => array(__CLASS__, 'generate_auth_token'),
            'permission_callback' => '__return_true',
        ));
    }

    /**
     * Obtener profesionales
     */
    public static function get_professionals($request) {
        $params = $request->get_params();

        $args = array(
            'post_type' => 'pasiones_professional',
            'posts_per_page' => isset($params['per_page']) ? intval($params['per_page']) : 12,
            'paged' => isset($params['page']) ? intval($params['page']) : 1,
        );

        $query = new WP_Query($args);

        $professionals = array();

        while ($query->have_posts()) {
            $query->the_post();

            $professionals[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'content' => get_the_content(),
                'excerpt' => get_the_excerpt(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
                'categories' => wp_get_post_terms(get_the_ID(), 'pasiones_category', array('fields' => 'names')),
                'country' => wp_get_post_terms(get_the_ID(), 'pasiones_country', array('fields' => 'names')),
                'province' => wp_get_post_terms(get_the_ID(), 'pasiones_province', array('fields' => 'names')),
                'rating' => Pasiones_Reviews::get_average_rating(get_the_ID()),
            );
        }

        wp_reset_postdata();

        return rest_ensure_response($professionals);
    }

    /**
     * Obtener categorías
     */
    public static function get_categories($request) {
        $categories = get_terms(array(
            'taxonomy' => 'pasiones_category',
            'hide_empty' => false,
        ));

        return rest_ensure_response($categories);
    }

    /**
     * Obtener países
     */
    public static function get_countries($request) {
        $countries = get_terms(array(
            'taxonomy' => 'pasiones_country',
            'hide_empty' => false,
        ));

        return rest_ensure_response($countries);
    }

    /**
     * Obtener provincias
     */
    public static function get_provinces($request) {
        $country_slug = $request['country'];
        $provinces = Pasiones_Countries::get_provinces($country_slug);

        return rest_ensure_response($provinces);
    }

    /**
     * Obtener membresías
     */
    public static function get_memberships($request) {
        $memberships = Pasiones_Memberships::get_membership_config();

        return rest_ensure_response($memberships);
    }

    /**
     * Generar token de autenticación (para SSO)
     */
    public static function generate_auth_token($request) {
        $params = $request->get_params();

        $username = sanitize_text_field($params['username']);
        $password = $params['password'];

        $user = wp_authenticate($username, $password);

        if (is_wp_error($user)) {
            return new WP_Error('authentication_failed', 'Autenticación fallida', array('status' => 401));
        }

        // Generar JWT token (requiere plugin JWT Authentication for WP REST API)
        $token = array(
            'user_id' => $user->ID,
            'user_email' => $user->user_email,
            'exp' => time() + (DAY_IN_SECONDS * 7), // 7 días
        );

        return rest_ensure_response(array(
            'token' => base64_encode(json_encode($token)),
            'user' => array(
                'id' => $user->ID,
                'email' => $user->user_email,
                'display_name' => $user->display_name,
            ),
        ));
    }
}
