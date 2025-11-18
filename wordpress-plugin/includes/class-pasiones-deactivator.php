<?php
/**
 * Desactivador del plugin Pasiones Platform
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Deactivator {

    /**
     * Desactivar el plugin
     */
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();

        // Limpiar tareas programadas
        wp_clear_scheduled_hook('pasiones_daily_cleanup');
        wp_clear_scheduled_hook('pasiones_check_memberships');
        wp_clear_scheduled_hook('pasiones_process_payouts');
    }
}
