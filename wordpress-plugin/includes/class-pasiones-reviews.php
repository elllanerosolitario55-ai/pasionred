<?php
/**
 * Sistema de Reviews y Valoraciones
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Reviews {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_ajax_submit_review', array($this, 'ajax_submit_review'));
    }

    /**
     * Crear review
     */
    public static function create_review($professional_id, $user_id, $rating, $comment = '', $session_id = null) {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_reviews';

        return $wpdb->insert(
            $table,
            array(
                'professional_id' => $professional_id,
                'user_id' => $user_id,
                'rating' => $rating,
                'comment' => $comment,
                'session_id' => $session_id,
                'status' => 'approved',
            )
        );
    }

    /**
     * Obtener promedio de rating
     */
    public static function get_average_rating($professional_id) {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_reviews';

        $average = $wpdb->get_var($wpdb->prepare(
            "SELECT AVG(rating) FROM $table WHERE professional_id = %d AND status = 'approved'",
            $professional_id
        ));

        return $average ? round($average, 1) : 0;
    }

    /**
     * AJAX: Enviar review
     */
    public function ajax_submit_review() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $user_id = get_current_user_id();
        $professional_id = intval($_POST['professional_id']);
        $rating = intval($_POST['rating']);
        $comment = sanitize_textarea_field($_POST['comment']);

        if (self::create_review($professional_id, $user_id, $rating, $comment)) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }
}
