<?php
/**
 * Sistema de Notificaciones
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Notifications {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_ajax_get_notifications', array($this, 'ajax_get_notifications'));
        add_action('wp_ajax_mark_notification_read', array($this, 'ajax_mark_read'));
    }

    /**
     * Crear notificación
     */
    public static function create_notification($user_id, $type, $title, $message, $link = null) {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_notifications';

        $result = $wpdb->insert(
            $table,
            array(
                'user_id' => $user_id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $link,
            )
        );

        if ($result && get_option('pasiones_email_notifications')) {
            // Enviar email
            wp_mail(
                get_userdata($user_id)->user_email,
                $title,
                $message
            );
        }

        return $result;
    }

    /**
     * Obtener notificaciones de usuario
     */
    public static function get_notifications($user_id, $unread_only = false) {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_notifications';

        $where = $wpdb->prepare("user_id = %d", $user_id);

        if ($unread_only) {
            $where .= " AND is_read = 0";
        }

        return $wpdb->get_results(
            "SELECT * FROM $table WHERE $where ORDER BY created_at DESC LIMIT 50"
        );
    }

    /**
     * Marcar como leída
     */
    public static function mark_read($notification_id) {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_notifications';

        return $wpdb->update(
            $table,
            array('is_read' => 1),
            array('id' => $notification_id)
        );
    }

    /**
     * AJAX: Obtener notificaciones
     */
    public function ajax_get_notifications() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $user_id = get_current_user_id();

        $notifications = self::get_notifications($user_id);

        wp_send_json_success($notifications);
    }

    /**
     * AJAX: Marcar como leída
     */
    public function ajax_mark_read() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $notification_id = intval($_POST['notification_id']);

        if (self::mark_read($notification_id)) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }
}
