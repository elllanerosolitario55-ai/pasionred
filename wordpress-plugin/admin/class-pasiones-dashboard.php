<?php
/**
 * Dashboard y Estadísticas
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Dashboard {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}

    /**
     * Obtener estadísticas generales
     */
    public static function get_stats() {
        global $wpdb;

        $stats = array();

        // Total de profesionales
        $stats['total_professionals'] = wp_count_posts('pasiones_professional')->publish;

        // Total de usuarios
        $stats['total_users'] = count_users()['total_users'];

        // Membresías activas
        $memberships_table = $wpdb->prefix . 'pasiones_memberships';
        $stats['active_memberships'] = $wpdb->get_var(
            "SELECT COUNT(*) FROM $memberships_table WHERE status = 'active'"
        );

        // Ingresos del mes
        $transactions_table = $wpdb->prefix . 'pasiones_transactions';
        $stats['monthly_revenue'] = $wpdb->get_var(
            "SELECT SUM(amount) FROM $transactions_table
             WHERE status = 'completed'
             AND MONTH(created_at) = MONTH(CURRENT_DATE())
             AND YEAR(created_at) = YEAR(CURRENT_DATE())"
        );

        // Sesiones activas
        $sessions_table = $wpdb->prefix . 'pasiones_sessions';
        $stats['active_sessions'] = $wpdb->get_var(
            "SELECT COUNT(*) FROM $sessions_table WHERE status = 'active'"
        );

        return $stats;
    }
}
