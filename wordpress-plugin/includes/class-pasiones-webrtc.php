<?php
/**
 * Sistema WebRTC para Videochat de Pasiones Platform
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_WebRTC {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_ajax_start_videochat', array($this, 'ajax_start_videochat'));
        add_action('wp_ajax_end_videochat', array($this, 'ajax_end_videochat'));
        add_action('wp_ajax_get_webrtc_config', array($this, 'ajax_get_webrtc_config'));
    }

    /**
     * Iniciar sesión de videochat
     */
    public function start_session($professional_id, $user_id = null, $session_type = 'videochat') {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_sessions';

        $result = $wpdb->insert(
            $table,
            array(
                'professional_id' => $professional_id,
                'user_id' => $user_id,
                'session_type' => $session_type,
                'status' => 'active',
                'start_time' => current_time('mysql'),
                'is_private' => $user_id ? 1 : 0,
            )
        );

        if ($result) {
            return $wpdb->insert_id;
        }

        return false;
    }

    /**
     * Finalizar sesión
     */
    public function end_session($session_id) {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_sessions';

        $session = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d",
            $session_id
        ));

        if (!$session) {
            return false;
        }

        $duration = strtotime(current_time('mysql')) - strtotime($session->start_time);
        $duration_minutes = ceil($duration / 60);

        // Calcular costo
        $cost_per_minute = get_user_meta($session->professional_id, 'pasiones_cost_per_minute', true);
        if (!$cost_per_minute) {
            $cost_per_minute = get_option('pasiones_default_cost_per_minute', 2.5);
        }

        $total_cost = $duration_minutes * $cost_per_minute;

        $wpdb->update(
            $table,
            array(
                'status' => 'completed',
                'end_time' => current_time('mysql'),
                'duration' => $duration_minutes,
                'total_cost' => $total_cost,
            ),
            array('id' => $session_id)
        );

        // Procesar pago si hay usuario
        if ($session->user_id) {
            do_action('pasiones_process_session_payment', $session_id, $session->user_id, $session->professional_id, $total_cost);
        }

        return true;
    }

    /**
     * Obtener configuración WebRTC
     */
    public function get_webrtc_config() {
        return array(
            'iceServers' => array(
                array('urls' => 'stun:stun.l.google.com:19302'),
                array('urls' => 'stun:stun1.l.google.com:19302'),
            ),
        );
    }

    /**
     * AJAX: Iniciar videochat
     */
    public function ajax_start_videochat() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $professional_id = intval($_POST['professional_id']);
        $user_id = get_current_user_id();

        $session_id = $this->start_session($professional_id, $user_id);

        if ($session_id) {
            wp_send_json_success(array(
                'session_id' => $session_id,
                'webrtc_config' => $this->get_webrtc_config(),
            ));
        } else {
            wp_send_json_error(array('message' => __('Error al iniciar videochat', 'pasiones-platform')));
        }
    }

    /**
     * AJAX: Finalizar videochat
     */
    public function ajax_end_videochat() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $session_id = intval($_POST['session_id']);

        if ($this->end_session($session_id)) {
            wp_send_json_success(array('message' => __('Sesión finalizada', 'pasiones-platform')));
        } else {
            wp_send_json_error(array('message' => __('Error al finalizar sesión', 'pasiones-platform')));
        }
    }

    /**
     * AJAX: Obtener configuración WebRTC
     */
    public function ajax_get_webrtc_config() {
        wp_send_json_success($this->get_webrtc_config());
    }
}
