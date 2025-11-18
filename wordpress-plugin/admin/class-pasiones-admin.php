<?php
/**
 * Panel de Administración de Pasiones Platform
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Admin {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    /**
     * Añadir menú de administración
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Pasiones Platform', 'pasiones-platform'),
            __('Pasiones', 'pasiones-platform'),
            'manage_options',
            'pasiones-platform',
            array($this, 'dashboard_page'),
            'dashicons-video-alt2',
            30
        );

        add_submenu_page(
            'pasiones-platform',
            __('Dashboard', 'pasiones-platform'),
            __('Dashboard', 'pasiones-platform'),
            'manage_options',
            'pasiones-platform',
            array($this, 'dashboard_page')
        );

        add_submenu_page(
            'pasiones-platform',
            __('Configuración', 'pasiones-platform'),
            __('Configuración', 'pasiones-platform'),
            'manage_options',
            'pasiones-settings',
            array($this, 'settings_page')
        );

        add_submenu_page(
            'pasiones-platform',
            __('Membresías', 'pasiones-platform'),
            __('Membresías', 'pasiones-platform'),
            'manage_options',
            'pasiones-memberships',
            array($this, 'memberships_page')
        );

        add_submenu_page(
            'pasiones-platform',
            __('Transacciones', 'pasiones-platform'),
            __('Transacciones', 'pasiones-platform'),
            'manage_options',
            'pasiones-transactions',
            array($this, 'transactions_page')
        );

        add_submenu_page(
            'pasiones-platform',
            __('Reviews', 'pasiones-platform'),
            __('Reviews', 'pasiones-platform'),
            'manage_options',
            'pasiones-reviews',
            array($this, 'reviews_page')
        );
    }

    /**
     * Página de Dashboard
     */
    public function dashboard_page() {
        include PASIONES_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    /**
     * Página de Configuración
     */
    public function settings_page() {
        include PASIONES_PLUGIN_DIR . 'admin/views/settings.php';
    }

    /**
     * Página de Membresías
     */
    public function memberships_page() {
        include PASIONES_PLUGIN_DIR . 'admin/views/memberships.php';
    }

    /**
     * Página de Transacciones
     */
    public function transactions_page() {
        include PASIONES_PLUGIN_DIR . 'admin/views/transactions.php';
    }

    /**
     * Página de Reviews
     */
    public function reviews_page() {
        include PASIONES_PLUGIN_DIR . 'admin/views/reviews.php';
    }
}
