<?php
/**
 * Configuración de Pasiones Platform
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Settings {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Registrar configuraciones
     */
    public function register_settings() {
        // Configuración general
        register_setting('pasiones_general', 'pasiones_currency');
        register_setting('pasiones_general', 'pasiones_currency_symbol');
        register_setting('pasiones_general', 'pasiones_site_name');

        // Membresías
        register_setting('pasiones_memberships', 'pasiones_membership_bronze_price');
        register_setting('pasiones_memberships', 'pasiones_membership_silver_price');
        register_setting('pasiones_memberships', 'pasiones_membership_gold_price');

        // Pagos
        register_setting('pasiones_payments', 'pasiones_stripe_enabled');
        register_setting('pasiones_payments', 'pasiones_stripe_publishable_key');
        register_setting('pasiones_payments', 'pasiones_stripe_secret_key');
        register_setting('pasiones_payments', 'pasiones_paypal_enabled');
        register_setting('pasiones_payments', 'pasiones_paypal_client_id');
        register_setting('pasiones_payments', 'pasiones_paypal_secret');

        // Comisiones
        register_setting('pasiones_payments', 'pasiones_admin_commission');
        register_setting('pasiones_payments', 'pasiones_min_withdrawal');

        // Videochat/Streaming
        register_setting('pasiones_video', 'pasiones_videochat_enabled');
        register_setting('pasiones_video', 'pasiones_streaming_enabled');
        register_setting('pasiones_video', 'pasiones_default_cost_per_minute');
    }
}
