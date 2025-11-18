<?php
/**
 * Custom Post Types de Pasiones Platform
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Post_Types {

    /**
     * Inicializar
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_post_types'));
    }

    /**
     * Registrar Custom Post Types
     */
    public static function register_post_types() {

        // Profesionales
        register_post_type('pasiones_professional', array(
            'labels' => array(
                'name' => __('Profesionales', 'pasiones-platform'),
                'singular_name' => __('Profesional', 'pasiones-platform'),
                'add_new' => __('Añadir Profesional', 'pasiones-platform'),
                'add_new_item' => __('Añadir Nuevo Profesional', 'pasiones-platform'),
                'edit_item' => __('Editar Profesional', 'pasiones-platform'),
                'new_item' => __('Nuevo Profesional', 'pasiones-platform'),
                'view_item' => __('Ver Profesional', 'pasiones-platform'),
                'search_items' => __('Buscar Profesionales', 'pasiones-platform'),
                'not_found' => __('No se encontraron profesionales', 'pasiones-platform'),
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'author', 'comments'),
            'menu_icon' => 'dashicons-businessperson',
            'rewrite' => array('slug' => 'profesional'),
            'capability_type' => 'post',
            'show_in_menu' => 'pasiones-platform',
        ));

        // Posts/Publicaciones
        register_post_type('pasiones_post', array(
            'labels' => array(
                'name' => __('Publicaciones', 'pasiones-platform'),
                'singular_name' => __('Publicación', 'pasiones-platform'),
                'add_new' => __('Nueva Publicación', 'pasiones-platform'),
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'author', 'comments'),
            'menu_icon' => 'dashicons-admin-post',
            'rewrite' => array('slug' => 'publicacion'),
            'show_in_menu' => 'pasiones-platform',
        ));

        // Videos
        register_post_type('pasiones_video', array(
            'labels' => array(
                'name' => __('Videos', 'pasiones-platform'),
                'singular_name' => __('Video', 'pasiones-platform'),
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'author'),
            'menu_icon' => 'dashicons-video-alt3',
            'rewrite' => array('slug' => 'video'),
            'show_in_menu' => 'pasiones-platform',
        ));

        // Streams
        register_post_type('pasiones_stream', array(
            'labels' => array(
                'name' => __('Streams', 'pasiones-platform'),
                'singular_name' => __('Stream', 'pasiones-platform'),
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'author'),
            'menu_icon' => 'dashicons-video-alt2',
            'rewrite' => array('slug' => 'stream'),
            'show_in_menu' => 'pasiones-platform',
        ));
    }
}
