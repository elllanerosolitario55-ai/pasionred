<?php
/**
 * Sistema de Pagos de Pasiones Platform
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Payments {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_ajax_create_payment', array($this, 'ajax_create_payment'));
        add_action('pasiones_process_session_payment', array($this, 'process_session_payment'), 10, 4);
    }

    /**
     * Crear transacción
     */
    public static function create_transaction($user_id, $professional_id, $amount, $type, $payment_method, $payment_id = null) {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_transactions';

        $result = $wpdb->insert(
            $table,
            array(
                'user_id' => $user_id,
                'professional_id' => $professional_id,
                'amount' => $amount,
                'currency' => get_option('pasiones_currency', 'EUR'),
                'type' => $type,
                'payment_method' => $payment_method,
                'payment_id' => $payment_id,
                'status' => 'completed',
            )
        );

        return $result ? $wpdb->insert_id : false;
    }

    /**
     * Procesar pago de sesión
     */
    public function process_session_payment($session_id, $user_id, $professional_id, $total_cost) {
        // Deducir créditos del usuario
        Pasiones_Credits::deduct_credits($user_id, $total_cost, 'session_payment', 'Pago de sesión #' . $session_id, $session_id);

        // Calcular comisión del admin
        $commission_rate = get_option('pasiones_admin_commission', 20) / 100;
        $commission = $total_cost * $commission_rate;
        $professional_amount = $total_cost - $commission;

        // Añadir créditos al profesional
        Pasiones_Credits::add_credits($professional_id, $professional_amount, 'session_income', 'Ingreso de sesión #' . $session_id, $session_id);

        // Crear transacción
        self::create_transaction($user_id, $professional_id, $total_cost, 'session', 'credits');

        // Notificar al profesional
        Pasiones_Notifications::create_notification(
            $professional_id,
            'payment_received',
            __('Pago recibido', 'pasiones-platform'),
            sprintf(__('Has recibido %s€ por una sesión', 'pasiones-platform'), $professional_amount)
        );
    }

    /**
     * AJAX: Crear pago
     */
    public function ajax_create_payment() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $user_id = get_current_user_id();
        $amount = floatval($_POST['amount']);
        $type = sanitize_text_field($_POST['type']);
        $payment_method = sanitize_text_field($_POST['payment_method']);

        // Aquí se integraría con Stripe/PayPal

        wp_send_json_success();
    }
}
