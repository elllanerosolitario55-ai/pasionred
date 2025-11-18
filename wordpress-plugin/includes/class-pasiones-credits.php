<?php
/**
 * Sistema de Créditos PASIONES
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Credits {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_ajax_buy_credits', array($this, 'ajax_buy_credits'));
        add_action('wp_ajax_get_balance', array($this, 'ajax_get_balance'));
    }

    /**
     * Obtener balance de créditos
     */
    public static function get_balance($user_id) {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_credits';

        $balance = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(amount) FROM $table WHERE user_id = %d",
            $user_id
        ));

        return $balance ? floatval($balance) : 0;
    }

    /**
     * Añadir créditos
     */
    public static function add_credits($user_id, $amount, $type, $description = '', $reference_id = null) {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_credits';

        return $wpdb->insert(
            $table,
            array(
                'user_id' => $user_id,
                'amount' => $amount,
                'type' => $type,
                'description' => $description,
                'reference_id' => $reference_id,
            )
        );
    }

    /**
     * Deducir créditos
     */
    public static function deduct_credits($user_id, $amount, $type, $description = '', $reference_id = null) {
        $balance = self::get_balance($user_id);

        if ($balance < $amount) {
            return false; // Saldo insuficiente
        }

        return self::add_credits($user_id, -$amount, $type, $description, $reference_id);
    }

    /**
     * AJAX: Comprar créditos
     */
    public function ajax_buy_credits() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $user_id = get_current_user_id();
        $amount = floatval($_POST['amount']);

        // Aquí se integraría con el sistema de pagos

        wp_send_json_success(array(
            'balance' => self::get_balance($user_id),
        ));
    }

    /**
     * AJAX: Obtener balance
     */
    public function ajax_get_balance() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $user_id = get_current_user_id();

        wp_send_json_success(array(
            'balance' => self::get_balance($user_id),
        ));
    }
}
