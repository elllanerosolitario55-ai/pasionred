<?php
/**
 * Integración con Elementor
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Elementor {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('elementor/widgets/register', array($this, 'register_widgets'));
        add_action('elementor/elements/categories_registered', array($this, 'add_widget_categories'));
    }

    /**
     * Agregar categoría de widgets
     */
    public function add_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'pasiones',
            array(
                'title' => __('Pasiones Platform', 'pasiones-platform'),
                'icon' => 'fa fa-video',
            )
        );
    }

    /**
     * Registrar widgets
     */
    public function register_widgets($widgets_manager) {
        // Cargar todos los widgets
        require_once PASIONES_PLUGIN_DIR . 'elementor/widgets/professionals-grid.php';
        require_once PASIONES_PLUGIN_DIR . 'elementor/widgets/categories-grid.php';
        require_once PASIONES_PLUGIN_DIR . 'elementor/widgets/featured-professionals.php';
        require_once PASIONES_PLUGIN_DIR . 'elementor/widgets/search-form.php';
        require_once PASIONES_PLUGIN_DIR . 'elementor/widgets/membership-cards.php';
        require_once PASIONES_PLUGIN_DIR . 'elementor/widgets/professional-profile.php';

        // Registrar todos los widgets
        $widgets_manager->register(new \Pasiones_Elementor_Widget_Professionals_Grid());
        $widgets_manager->register(new \Pasiones_Elementor_Widget_Categories_Grid());
        $widgets_manager->register(new \Pasiones_Elementor_Widget_Featured_Professionals());
        $widgets_manager->register(new \Pasiones_Elementor_Widget_Search_Form());
        $widgets_manager->register(new \Pasiones_Elementor_Widget_Membership_Cards());
        $widgets_manager->register(new \Pasiones_Elementor_Widget_Professional_Profile());
    }
}
