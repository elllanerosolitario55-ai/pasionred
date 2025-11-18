<?php
/**
 * Sistema de Membresías de Pasiones Platform
 * Gratis, Bronce (20€), Plata (45€), Oro (65€)
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Memberships {

    private static $instance = null;

    /**
     * Tipos de membresía
     */
    const MEMBERSHIP_FREE = 'free';
    const MEMBERSHIP_BRONZE = 'bronze';
    const MEMBERSHIP_SILVER = 'silver';
    const MEMBERSHIP_GOLD = 'gold';

    /**
     * Singleton
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        add_action('wp_ajax_upgrade_membership', array($this, 'ajax_upgrade_membership'));
        add_action('wp_ajax_cancel_membership', array($this, 'ajax_cancel_membership'));
        add_action('pasiones_check_memberships', array($this, 'check_expired_memberships'));
    }

    /**
     * Obtener configuración de membresías
     */
    public static function get_membership_config() {
        return array(
            self::MEMBERSHIP_FREE => array(
                'name' => __('Gratis', 'pasiones-platform'),
                'price' => 0,
                'features' => array(
                    'posts_limit' => get_option('pasiones_free_posts_limit', 5),
                    'can_post_images' => false,
                    'can_post_videos' => false,
                    'can_videochat' => false,
                    'can_stream' => false,
                    'can_receive_reviews' => false,
                    'can_set_schedule' => false,
                    'visibility' => 'basic',
                ),
            ),
            self::MEMBERSHIP_BRONZE => array(
                'name' => __('Bronce', 'pasiones-platform'),
                'price' => get_option('pasiones_membership_bronze_price', 20),
                'features' => array(
                    'posts_limit' => get_option('pasiones_bronze_posts_limit', 50),
                    'can_post_images' => true,
                    'can_post_videos' => true,
                    'can_videochat' => true,
                    'can_stream' => false,
                    'can_receive_reviews' => true,
                    'can_set_schedule' => true,
                    'visibility' => 'medium',
                    'images_pricing' => 'flexible', // admin puede configurar
                ),
            ),
            self::MEMBERSHIP_SILVER => array(
                'name' => __('Plata', 'pasiones-platform'),
                'price' => get_option('pasiones_membership_silver_price', 45),
                'features' => array(
                    'posts_limit' => get_option('pasiones_silver_posts_limit', 200),
                    'can_post_images' => true,
                    'can_post_videos' => true,
                    'can_videochat' => true,
                    'can_stream' => true,
                    'can_receive_reviews' => true,
                    'can_set_schedule' => true,
                    'visibility' => 'high',
                    'images_pricing' => 'flexible',
                    'videos_pricing' => 'flexible',
                    'videochat_pricing' => 'flexible',
                    'stream_pricing' => 'flexible',
                ),
            ),
            self::MEMBERSHIP_GOLD => array(
                'name' => __('Oro', 'pasiones-platform'),
                'price' => get_option('pasiones_membership_gold_price', 65),
                'features' => array(
                    'posts_limit' => -1, // ilimitado
                    'can_post_images' => true,
                    'can_post_videos' => true,
                    'can_videochat' => true,
                    'can_stream' => true,
                    'can_receive_reviews' => true,
                    'can_set_schedule' => true,
                    'visibility' => 'premium',
                    'images_pricing' => 'flexible',
                    'videos_pricing' => 'flexible',
                    'videochat_pricing' => 'flexible',
                    'stream_pricing' => 'flexible',
                    'featured' => true,
                    'priority_support' => true,
                ),
            ),
        );
    }

    /**
     * Obtener membresía actual de un usuario
     */
    public static function get_user_membership($user_id) {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_memberships';

        $membership = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d AND status = 'active' ORDER BY id DESC LIMIT 1",
            $user_id
        ));

        if (!$membership) {
            // Retornar membresía gratis por defecto
            return (object) array(
                'user_id' => $user_id,
                'membership_type' => self::MEMBERSHIP_FREE,
                'status' => 'active',
            );
        }

        return $membership;
    }

    /**
     * Verificar si un usuario puede realizar una acción
     */
    public static function can_user_do($user_id, $action) {
        $membership = self::get_user_membership($user_id);
        $config = self::get_membership_config();

        if (!isset($config[$membership->membership_type])) {
            return false;
        }

        $features = $config[$membership->membership_type]['features'];

        return isset($features[$action]) ? $features[$action] : false;
    }

    /**
     * Actualizar membresía de usuario
     */
    public static function upgrade_membership($user_id, $membership_type, $transaction_id = null) {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_memberships';

        // Cancelar membresías activas anteriores
        $wpdb->update(
            $table,
            array('status' => 'cancelled', 'updated_at' => current_time('mysql')),
            array('user_id' => $user_id, 'status' => 'active')
        );

        // Crear nueva membresía
        $end_date = date('Y-m-d H:i:s', strtotime('+1 month'));

        $result = $wpdb->insert(
            $table,
            array(
                'user_id' => $user_id,
                'membership_type' => $membership_type,
                'start_date' => current_time('mysql'),
                'end_date' => $end_date,
                'status' => 'active',
                'auto_renew' => 1,
            )
        );

        if ($result) {
            // Enviar notificación
            Pasiones_Notifications::create_notification(
                $user_id,
                'membership_upgraded',
                __('Membresía actualizada', 'pasiones-platform'),
                sprintf(__('Tu membresía ha sido actualizada a %s', 'pasiones-platform'), strtoupper($membership_type))
            );

            do_action('pasiones_membership_upgraded', $user_id, $membership_type);
        }

        return $result;
    }

    /**
     * Cancelar membresía
     */
    public static function cancel_membership($user_id) {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_memberships';

        $result = $wpdb->update(
            $table,
            array(
                'status' => 'cancelled',
                'auto_renew' => 0,
                'updated_at' => current_time('mysql')
            ),
            array('user_id' => $user_id, 'status' => 'active')
        );

        if ($result) {
            Pasiones_Notifications::create_notification(
                $user_id,
                'membership_cancelled',
                __('Membresía cancelada', 'pasiones-platform'),
                __('Tu membresía ha sido cancelada', 'pasiones-platform')
            );
        }

        return $result;
    }

    /**
     * Verificar membresías expiradas (ejecutar diariamente)
     */
    public function check_expired_memberships() {
        global $wpdb;

        $table = $wpdb->prefix . 'pasiones_memberships';

        // Obtener membresías expiradas
        $expired = $wpdb->get_results(
            "SELECT * FROM $table WHERE status = 'active' AND end_date < NOW()"
        );

        foreach ($expired as $membership) {
            if ($membership->auto_renew) {
                // Intentar renovar automáticamente
                do_action('pasiones_auto_renew_membership', $membership);
            } else {
                // Marcar como expirada
                $wpdb->update(
                    $table,
                    array('status' => 'expired'),
                    array('id' => $membership->id)
                );

                // Notificar al usuario
                Pasiones_Notifications::create_notification(
                    $membership->user_id,
                    'membership_expired',
                    __('Membresía expirada', 'pasiones-platform'),
                    __('Tu membresía ha expirado. Renuévala para seguir disfrutando de los beneficios.', 'pasiones-platform')
                );
            }
        }
    }

    /**
     * AJAX: Actualizar membresía
     */
    public function ajax_upgrade_membership() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $user_id = get_current_user_id();
        $membership_type = sanitize_text_field($_POST['membership_type']);

        if (!$user_id) {
            wp_send_json_error(array('message' => __('Debes iniciar sesión', 'pasiones-platform')));
        }

        $config = self::get_membership_config();

        if (!isset($config[$membership_type])) {
            wp_send_json_error(array('message' => __('Tipo de membresía inválido', 'pasiones-platform')));
        }

        // Aquí se integraría con el sistema de pagos
        // Por ahora, solo actualizamos la membresía

        $result = self::upgrade_membership($user_id, $membership_type);

        if ($result) {
            wp_send_json_success(array(
                'message' => __('Membresía actualizada correctamente', 'pasiones-platform'),
                'membership' => self::get_user_membership($user_id),
            ));
        } else {
            wp_send_json_error(array('message' => __('Error al actualizar membresía', 'pasiones-platform')));
        }
    }

    /**
     * AJAX: Cancelar membresía
     */
    public function ajax_cancel_membership() {
        check_ajax_referer('pasiones_nonce', 'nonce');

        $user_id = get_current_user_id();

        if (!$user_id) {
            wp_send_json_error(array('message' => __('Debes iniciar sesión', 'pasiones-platform')));
        }

        $result = self::cancel_membership($user_id);

        if ($result) {
            wp_send_json_success(array('message' => __('Membresía cancelada', 'pasiones-platform')));
        } else {
            wp_send_json_error(array('message' => __('Error al cancelar membresía', 'pasiones-platform')));
        }
    }
}
