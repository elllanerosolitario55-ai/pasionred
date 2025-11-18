<?php
/**
 * Plugin Name: Pasiones Platform - Red Social de Profesionales
 * Plugin URI: https://pasiones-platform.com
 * Description: Plataforma completa de red social para profesionales con streaming, videochat WebRTC, monetización y sistema de membresías (Gratis, Bronce, Plata, Oro)
 * Version: 2.1.3
 * Author: Pasiones Platform
 * Author URI: https://pasiones-platform.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pasiones-platform
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes del plugin
define('PASIONES_VERSION', '2.1.3');
define('PASIONES_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PASIONES_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PASIONES_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Clase principal del plugin Pasiones Platform
 */
class Pasiones_Platform {

    private static $instance = null;

    /**
     * Singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->load_dependencies();
        $this->define_hooks();
        $this->init_modules();
    }

    /**
     * Cargar dependencias
     */
    private function load_dependencies() {
        // Core
        require_once PASIONES_PLUGIN_DIR . 'includes/class-pasiones-activator.php';
        require_once PASIONES_PLUGIN_DIR . 'includes/class-pasiones-deactivator.php';

        // Funcionalidades principales
        require_once PASIONES_PLUGIN_DIR . 'includes/class-pasiones-post-types.php';
        require_once PASIONES_PLUGIN_DIR . 'includes/class-pasiones-taxonomies.php';
        require_once PASIONES_PLUGIN_DIR . 'includes/class-pasiones-memberships.php';
        require_once PASIONES_PLUGIN_DIR . 'includes/class-pasiones-payments.php';
        require_once PASIONES_PLUGIN_DIR . 'includes/class-pasiones-credits.php';
        require_once PASIONES_PLUGIN_DIR . 'includes/class-pasiones-webrtc.php';
        require_once PASIONES_PLUGIN_DIR . 'includes/class-pasiones-streaming.php';
        require_once PASIONES_PLUGIN_DIR . 'includes/class-pasiones-countries.php';
        require_once PASIONES_PLUGIN_DIR . 'includes/class-pasiones-reviews.php';
        require_once PASIONES_PLUGIN_DIR . 'includes/class-pasiones-notifications.php';

        // Admin
        require_once PASIONES_PLUGIN_DIR . 'admin/class-pasiones-admin.php';
        require_once PASIONES_PLUGIN_DIR . 'admin/class-pasiones-settings.php';
        require_once PASIONES_PLUGIN_DIR . 'admin/class-pasiones-dashboard.php';

        // Public
        require_once PASIONES_PLUGIN_DIR . 'public/class-pasiones-public.php';
        require_once PASIONES_PLUGIN_DIR . 'public/class-pasiones-shortcodes.php';

        // Elementor
        if (did_action('elementor/loaded')) {
            require_once PASIONES_PLUGIN_DIR . 'elementor/class-pasiones-elementor.php';
        }

        // API REST
        require_once PASIONES_PLUGIN_DIR . 'includes/api/class-pasiones-rest-api.php';

        // Integraciones
        require_once PASIONES_PLUGIN_DIR . 'includes/integrations/class-pasiones-stripe.php';
        require_once PASIONES_PLUGIN_DIR . 'includes/integrations/class-pasiones-paypal.php';
    }

    /**
     * Definir hooks
     */
    private function define_hooks() {
        // Activación y desactivación
        register_activation_hook(__FILE__, array('Pasiones_Activator', 'activate'));
        register_deactivation_hook(__FILE__, array('Pasiones_Deactivator', 'deactivate'));

        // Inicialización
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));

        // Scripts y estilos
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Inicializar módulos
     */
    private function init_modules() {
        // Inicializar Custom Post Types
        Pasiones_Post_Types::init();

        // Inicializar Taxonomías
        Pasiones_Taxonomies::init();

        // Inicializar sistema de membresías
        Pasiones_Memberships::get_instance();

        // Inicializar sistema de pagos
        Pasiones_Payments::get_instance();

        // Inicializar sistema de créditos
        Pasiones_Credits::get_instance();

        // Inicializar WebRTC
        Pasiones_WebRTC::get_instance();

        // Inicializar Streaming
        Pasiones_Streaming::get_instance();

        // Inicializar países y provincias
        Pasiones_Countries::get_instance();

        // Inicializar reviews
        Pasiones_Reviews::get_instance();

        // Inicializar notificaciones
        Pasiones_Notifications::get_instance();

        // Admin
        if (is_admin()) {
            Pasiones_Admin::get_instance();
            Pasiones_Settings::get_instance();
            Pasiones_Dashboard::get_instance();
        }

        // Public
        Pasiones_Public::get_instance();
        Pasiones_Shortcodes::init();

        // REST API
        Pasiones_REST_API::init();

        // Elementor
        if (did_action('elementor/loaded')) {
            Pasiones_Elementor::get_instance();
        }
    }

    /**
     * Inicialización del plugin
     */
    public function init() {
        // Registrar tipos de post personalizados
        do_action('pasiones_register_post_types');

        // Registrar taxonomías
        do_action('pasiones_register_taxonomies');

        // Inicializar sesiones si es necesario
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * Cargar traducciones
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'pasiones-platform',
            false,
            dirname(PASIONES_PLUGIN_BASENAME) . '/languages/'
        );
    }

    /**
     * Encolar assets públicos
     */
    public function enqueue_public_assets() {
        // CSS
        wp_enqueue_style(
            'pasiones-public',
            PASIONES_PLUGIN_URL . 'public/css/pasiones-public.css',
            array(),
            PASIONES_VERSION
        );

        // JavaScript
        wp_enqueue_script(
            'pasiones-webrtc',
            PASIONES_PLUGIN_URL . 'public/js/webrtc.js',
            array('jquery'),
            PASIONES_VERSION,
            true
        );

        wp_enqueue_script(
            'pasiones-streaming',
            PASIONES_PLUGIN_URL . 'public/js/streaming.js',
            array('jquery', 'pasiones-webrtc'),
            PASIONES_VERSION,
            true
        );

        wp_enqueue_script(
            'pasiones-public',
            PASIONES_PLUGIN_URL . 'public/js/pasiones-public.js',
            array('jquery'),
            PASIONES_VERSION,
            true
        );

        // Localizar script
        wp_localize_script('pasiones-public', 'pasionesData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url('pasiones/v1/'),
            'nonce' => wp_create_nonce('pasiones_nonce'),
            'userId' => get_current_user_id(),
            'currency' => get_option('pasiones_currency', 'EUR'),
            'currencySymbol' => get_option('pasiones_currency_symbol', '€'),
        ));
    }

    /**
     * Encolar assets de admin
     */
    public function enqueue_admin_assets($hook) {
        // CSS
        wp_enqueue_style(
            'pasiones-admin',
            PASIONES_PLUGIN_URL . 'admin/css/pasiones-admin.css',
            array(),
            PASIONES_VERSION
        );

        // JavaScript
        wp_enqueue_script(
            'pasiones-admin',
            PASIONES_PLUGIN_URL . 'admin/js/pasiones-admin.js',
            array('jquery'),
            PASIONES_VERSION,
            true
        );

        wp_localize_script('pasiones-admin', 'pasionesAdminData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pasiones_admin_nonce'),
        ));
    }
}

/**
 * Iniciar el plugin
 */
function pasiones_platform() {
    return Pasiones_Platform::get_instance();
}

// Iniciar el plugin
pasiones_platform();
