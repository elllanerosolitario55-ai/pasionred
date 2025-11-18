<?php
/**
 * Activador del plugin Pasiones Platform
 * Crea tablas de base de datos y configura opciones iniciales
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Activator {

    /**
     * Activar el plugin
     */
    public static function activate() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Tabla de membresías de usuarios
        $table_memberships = $wpdb->prefix . 'pasiones_memberships';
        $sql_memberships = "CREATE TABLE $table_memberships (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            membership_type varchar(50) NOT NULL DEFAULT 'free',
            start_date datetime NOT NULL,
            end_date datetime DEFAULT NULL,
            status varchar(20) NOT NULL DEFAULT 'active',
            auto_renew tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY membership_type (membership_type),
            KEY status (status)
        ) $charset_collate;";
        dbDelta($sql_memberships);

        // Tabla de créditos/monedas PASIONES
        $table_credits = $wpdb->prefix . 'pasiones_credits';
        $sql_credits = "CREATE TABLE $table_credits (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            amount decimal(10,2) NOT NULL DEFAULT 0.00,
            type varchar(50) NOT NULL,
            description text,
            reference_id bigint(20) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY type (type)
        ) $charset_collate;";
        dbDelta($sql_credits);

        // Tabla de transacciones de pago
        $table_transactions = $wpdb->prefix . 'pasiones_transactions';
        $sql_transactions = "CREATE TABLE $table_transactions (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            professional_id bigint(20) DEFAULT NULL,
            amount decimal(10,2) NOT NULL,
            currency varchar(10) NOT NULL DEFAULT 'EUR',
            type varchar(50) NOT NULL,
            payment_method varchar(50) NOT NULL,
            payment_id varchar(255) DEFAULT NULL,
            status varchar(20) NOT NULL DEFAULT 'pending',
            metadata text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY professional_id (professional_id),
            KEY status (status),
            KEY payment_id (payment_id)
        ) $charset_collate;";
        dbDelta($sql_transactions);

        // Tabla de sesiones de videochat/streaming
        $table_sessions = $wpdb->prefix . 'pasiones_sessions';
        $sql_sessions = "CREATE TABLE $table_sessions (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            professional_id bigint(20) NOT NULL,
            user_id bigint(20) DEFAULT NULL,
            session_type varchar(50) NOT NULL,
            status varchar(20) NOT NULL DEFAULT 'waiting',
            start_time datetime DEFAULT NULL,
            end_time datetime DEFAULT NULL,
            duration int(11) DEFAULT 0,
            cost_per_minute decimal(10,2) DEFAULT 0.00,
            total_cost decimal(10,2) DEFAULT 0.00,
            is_private tinyint(1) DEFAULT 1,
            viewers_count int(11) DEFAULT 0,
            metadata text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY professional_id (professional_id),
            KEY user_id (user_id),
            KEY status (status),
            KEY session_type (session_type)
        ) $charset_collate;";
        dbDelta($sql_sessions);

        // Tabla de reviews/valoraciones
        $table_reviews = $wpdb->prefix . 'pasiones_reviews';
        $sql_reviews = "CREATE TABLE $table_reviews (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            professional_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            rating int(1) NOT NULL,
            comment text,
            session_id bigint(20) DEFAULT NULL,
            status varchar(20) NOT NULL DEFAULT 'approved',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY professional_id (professional_id),
            KEY user_id (user_id),
            KEY rating (rating)
        ) $charset_collate;";
        dbDelta($sql_reviews);

        // Tabla de notificaciones
        $table_notifications = $wpdb->prefix . 'pasiones_notifications';
        $sql_notifications = "CREATE TABLE $table_notifications (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            type varchar(50) NOT NULL,
            title varchar(255) NOT NULL,
            message text NOT NULL,
            link varchar(255) DEFAULT NULL,
            is_read tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY is_read (is_read)
        ) $charset_collate;";
        dbDelta($sql_notifications);

        // Tabla de horarios de disponibilidad
        $table_availability = $wpdb->prefix . 'pasiones_availability';
        $sql_availability = "CREATE TABLE $table_availability (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            professional_id bigint(20) NOT NULL,
            day_of_week int(1) NOT NULL,
            start_time time NOT NULL,
            end_time time NOT NULL,
            is_available tinyint(1) DEFAULT 1,
            PRIMARY KEY  (id),
            KEY professional_id (professional_id)
        ) $charset_collate;";
        dbDelta($sql_availability);

        // Configuración inicial
        self::set_default_options();

        // Crear roles personalizados
        self::create_custom_roles();

        // Crear páginas necesarias
        self::create_pages();

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Configurar opciones por defecto
     */
    private static function set_default_options() {
        $default_options = array(
            // General
            'pasiones_currency' => 'EUR',
            'pasiones_currency_symbol' => '€',
            'pasiones_currency_position' => 'before',
            'pasiones_site_name' => get_bloginfo('name'),

            // Membresías
            'pasiones_membership_free_enabled' => 1,
            'pasiones_membership_bronze_enabled' => 1,
            'pasiones_membership_bronze_price' => 20,
            'pasiones_membership_silver_enabled' => 1,
            'pasiones_membership_silver_price' => 45,
            'pasiones_membership_gold_enabled' => 1,
            'pasiones_membership_gold_price' => 65,

            // Límites por membresía
            'pasiones_free_posts_limit' => 5,
            'pasiones_bronze_posts_limit' => 50,
            'pasiones_silver_posts_limit' => 200,
            'pasiones_gold_posts_limit' => -1, // ilimitado

            // Comisiones
            'pasiones_admin_commission' => 20, // 20%
            'pasiones_min_withdrawal' => 50,

            // Videochat/Streaming
            'pasiones_videochat_enabled' => 1,
            'pasiones_streaming_enabled' => 1,
            'pasiones_default_cost_per_minute' => 2.5,

            // Créditos PASIONES
            'pasiones_credits_enabled' => 1,
            'pasiones_credits_name' => 'PASIONES',
            'pasiones_credits_exchange_rate' => 1, // 1 crédito = 1 EUR

            // Pagos
            'pasiones_stripe_enabled' => 0,
            'pasiones_paypal_enabled' => 0,

            // SEO
            'pasiones_seo_enabled' => 1,
            'pasiones_sitemap_enabled' => 1,

            // Seguridad
            'pasiones_recaptcha_enabled' => 0,
            'pasiones_email_verification' => 1,
            'pasiones_id_verification' => 1,

            // Notificaciones
            'pasiones_email_notifications' => 1,
            'pasiones_push_notifications' => 1,

            // Contenido
            'pasiones_auto_approve_posts' => 0,
            'pasiones_max_file_size' => 50, // MB
            'pasiones_allowed_video_formats' => 'mp4,mov',
            'pasiones_allowed_image_formats' => 'jpg,jpeg,png,gif',
            'pasiones_allowed_audio_formats' => 'mp3',

            // Storage
            'pasiones_storage_type' => 'local',

            // GDPR
            'pasiones_gdpr_enabled' => 1,
            'pasiones_cookie_banner' => 1,
        );

        foreach ($default_options as $key => $value) {
            if (get_option($key) === false) {
                add_option($key, $value);
            }
        }
    }

    /**
     * Crear roles personalizados
     */
    private static function create_custom_roles() {
        // Rol de Profesional
        add_role('pasiones_professional', __('Profesional', 'pasiones-platform'), array(
            'read' => true,
            'edit_posts' => true,
            'upload_files' => true,
            'edit_published_posts' => true,
            'delete_published_posts' => true,
            'pasiones_manage_profile' => true,
            'pasiones_videochat' => true,
            'pasiones_streaming' => true,
            'pasiones_manage_schedule' => true,
        ));

        // Agregar capacidades al administrador
        $admin = get_role('administrator');
        if ($admin) {
            $admin->add_cap('pasiones_manage_all');
            $admin->add_cap('pasiones_manage_memberships');
            $admin->add_cap('pasiones_manage_payments');
            $admin->add_cap('pasiones_manage_reviews');
            $admin->add_cap('pasiones_view_statistics');
        }
    }

    /**
     * Crear páginas necesarias
     */
    private static function create_pages() {
        $pages = array(
            'pasiones_home' => array(
                'title' => __('Inicio', 'pasiones-platform'),
                'content' => '[pasiones_home]',
            ),
            'pasiones_professionals' => array(
                'title' => __('Profesionales', 'pasiones-platform'),
                'content' => '[pasiones_professionals]',
            ),
            'pasiones_categories' => array(
                'title' => __('Categorías', 'pasiones-platform'),
                'content' => '[pasiones_categories]',
            ),
            'pasiones_countries' => array(
                'title' => __('Países', 'pasiones-platform'),
                'content' => '[pasiones_countries]',
            ),
            'pasiones_profile' => array(
                'title' => __('Mi Perfil', 'pasiones-platform'),
                'content' => '[pasiones_profile]',
            ),
            'pasiones_dashboard' => array(
                'title' => __('Panel de Control', 'pasiones-platform'),
                'content' => '[pasiones_dashboard]',
            ),
            'pasiones_memberships' => array(
                'title' => __('Membresías', 'pasiones-platform'),
                'content' => '[pasiones_memberships]',
            ),
        );

        foreach ($pages as $option_key => $page_data) {
            $page_id = get_option($option_key);

            if (!$page_id || !get_post($page_id)) {
                $page_id = wp_insert_post(array(
                    'post_title' => $page_data['title'],
                    'post_content' => $page_data['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'comment_status' => 'closed',
                ));

                update_option($option_key, $page_id);
            }
        }
    }
}
