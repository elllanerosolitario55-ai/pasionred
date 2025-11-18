<?php
/**
 * Integración con Stripe
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Stripe {

    private static $instance = null;
    private $secret_key;
    private $publishable_key;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->secret_key = get_option('pasiones_stripe_secret_key');
        $this->publishable_key = get_option('pasiones_stripe_publishable_key');

        add_action('wp_ajax_create_stripe_payment_intent', array($this, 'ajax_create_payment_intent'));
        add_action('wp_ajax_create_stripe_subscription', array($this, 'ajax_create_subscription'));
    }

    /**
     * Crear Payment Intent
     */
    public function create_payment_intent($amount, $currency = 'EUR', $metadata = array()) {
        if (!$this->secret_key) {
            return new WP_Error('no_stripe_key', __('Stripe no está configurado', 'pasiones-platform'));
        }

        $endpoint = 'https://api.stripe.com/v1/payment_intents';

        $body = array(
            'amount' => $amount * 100, // Stripe usa centavos
            'currency' => strtolower($currency),
            'metadata' => $metadata,
        );

        $response = wp_remote_post($endpoint, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->secret_key,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => http_build_query($body),
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        return $body;
    }

    /**
     * Crear suscripción
     */
    public function create_subscription($customer_id, $price_id, $metadata = array()) {
        if (!$this->secret_key) {
            return new WP_Error('no_stripe_key', __('Stripe no está configurado', 'pasiones-platform'));
        }

        $endpoint = 'https://api.stripe.com/v1/subscriptions';

        $body = array(
            'customer' => $customer_id,
            'items' => array(
                array('price' => $price_id),
            ),
            'metadata' => $metadata,
        );

        $response = wp_remote_post($endpoint, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->secret_key,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => http_build_query($body),
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    /**
     * AJAX: Crear payment intent
     */
    public function ajax_create_payment_intent() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $amount = floatval($_POST['amount']);
        $type = sanitize_text_field($_POST['type']);

        $result = $this->create_payment_intent($amount, 'EUR', array(
            'type' => $type,
            'user_id' => get_current_user_id(),
        ));

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success($result);
    }

    /**
     * AJAX: Crear suscripción
     */
    public function ajax_create_subscription() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $customer_id = sanitize_text_field($_POST['customer_id']);
        $price_id = sanitize_text_field($_POST['price_id']);

        $result = $this->create_subscription($customer_id, $price_id);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success($result);
    }
}
