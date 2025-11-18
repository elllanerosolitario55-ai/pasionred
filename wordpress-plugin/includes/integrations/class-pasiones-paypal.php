<?php
/**
 * Integración con PayPal
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_PayPal {

    private static $instance = null;
    private $client_id;
    private $secret;
    private $mode; // sandbox o live

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->client_id = get_option('pasiones_paypal_client_id');
        $this->secret = get_option('pasiones_paypal_secret');
        $this->mode = get_option('pasiones_paypal_mode', 'sandbox');

        add_action('wp_ajax_create_paypal_order', array($this, 'ajax_create_order'));
        add_action('wp_ajax_capture_paypal_order', array($this, 'ajax_capture_order'));
    }

    /**
     * Obtener access token
     */
    private function get_access_token() {
        $base_url = $this->mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $response = wp_remote_post($base_url . '/v1/oauth2/token', array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->secret),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => 'grant_type=client_credentials',
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        return isset($body['access_token']) ? $body['access_token'] : false;
    }

    /**
     * Crear orden
     */
    public function create_order($amount, $currency = 'EUR', $description = '') {
        $access_token = $this->get_access_token();

        if (!$access_token) {
            return new WP_Error('paypal_error', __('Error obteniendo token de PayPal', 'pasiones-platform'));
        }

        $base_url = $this->mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $order_data = array(
            'intent' => 'CAPTURE',
            'purchase_units' => array(
                array(
                    'amount' => array(
                        'currency_code' => $currency,
                        'value' => number_format($amount, 2, '.', ''),
                    ),
                    'description' => $description,
                ),
            ),
        );

        $response = wp_remote_post($base_url . '/v2/checkout/orders', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($order_data),
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    /**
     * Capturar orden
     */
    public function capture_order($order_id) {
        $access_token = $this->get_access_token();

        if (!$access_token) {
            return new WP_Error('paypal_error', __('Error obteniendo token de PayPal', 'pasiones-platform'));
        }

        $base_url = $this->mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $response = wp_remote_post($base_url . '/v2/checkout/orders/' . $order_id . '/capture', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
            ),
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    /**
     * AJAX: Crear orden
     */
    public function ajax_create_order() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $amount = floatval($_POST['amount']);
        $description = sanitize_text_field($_POST['description']);

        $result = $this->create_order($amount, 'EUR', $description);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success($result);
    }

    /**
     * AJAX: Capturar orden
     */
    public function ajax_capture_order() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $order_id = sanitize_text_field($_POST['order_id']);

        $result = $this->capture_order($order_id);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        // Registrar transacción
        if (isset($result['status']) && $result['status'] === 'COMPLETED') {
            $amount = floatval($result['purchase_units'][0]['amount']['value']);

            Pasiones_Payments::create_transaction(
                get_current_user_id(),
                null,
                $amount,
                'purchase',
                'paypal',
                $order_id
            );
        }

        wp_send_json_success($result);
    }
}
