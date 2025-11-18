<?php
/**
 * Sistema de Streaming de Pasiones Platform
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Streaming {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_ajax_start_stream', array($this, 'ajax_start_stream'));
        add_action('wp_ajax_end_stream', array($this, 'ajax_end_stream'));
        add_action('wp_ajax_nopriv_join_stream', array($this, 'ajax_join_stream'));
    }

    /**
     * Iniciar streaming
     */
    public function start_stream($professional_id, $is_private = false) {
        return Pasiones_WebRTC::get_instance()->start_session(
            $professional_id,
            null,
            'streaming'
        );
    }

    /**
     * AJAX: Iniciar stream
     */
    public function ajax_start_stream() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $user_id = get_current_user_id();
        $is_private = isset($_POST['is_private']) ? (bool)$_POST['is_private'] : false;

        if (!Pasiones_Memberships::can_user_do($user_id, 'can_stream')) {
            wp_send_json_error(array('message' => __('Tu membresía no permite streaming', 'pasiones-platform')));
        }

        $stream_id = $this->start_stream($user_id, $is_private);

        if ($stream_id) {
            wp_send_json_success(array('stream_id' => $stream_id));
        } else {
            wp_send_json_error(array('message' => __('Error al iniciar stream', 'pasiones-platform')));
        }
    }

    /**
     * AJAX: Finalizar stream
     */
    public function ajax_end_stream() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $stream_id = intval($_POST['stream_id']);

        if (Pasiones_WebRTC::get_instance()->end_session($stream_id)) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

    /**
     * AJAX: Unirse a stream
     */
    public function ajax_join_stream() {
        $stream_id = intval($_POST['stream_id']);

        // Incrementar contador de viewers
        global $wpdb;
        $table = $wpdb->prefix . 'pasiones_sessions';

        $wpdb->query($wpdb->prepare(
            "UPDATE $table SET viewers_count = viewers_count + 1 WHERE id = %d",
            $stream_id
        ));

        wp_send_json_success();
    }
}
