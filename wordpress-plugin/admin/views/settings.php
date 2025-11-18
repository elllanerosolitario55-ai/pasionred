<?php
/**
 * Vista: Configuración de Pasiones Platform
 */

if (!defined('ABSPATH')) {
    exit;
}

// Guardar configuración
if (isset($_POST['pasiones_save_settings'])) {
    check_admin_referer('pasiones_settings');

    // General
    update_option('pasiones_currency', sanitize_text_field($_POST['pasiones_currency']));
    update_option('pasiones_currency_symbol', sanitize_text_field($_POST['pasiones_currency_symbol']));

    // Membresías
    update_option('pasiones_membership_bronze_price', floatval($_POST['pasiones_membership_bronze_price']));
    update_option('pasiones_membership_silver_price', floatval($_POST['pasiones_membership_silver_price']));
    update_option('pasiones_membership_gold_price', floatval($_POST['pasiones_membership_gold_price']));

    // Pagos
    update_option('pasiones_stripe_enabled', isset($_POST['pasiones_stripe_enabled']) ? 1 : 0);
    update_option('pasiones_stripe_publishable_key', sanitize_text_field($_POST['pasiones_stripe_publishable_key']));
    update_option('pasiones_stripe_secret_key', sanitize_text_field($_POST['pasiones_stripe_secret_key']));

    update_option('pasiones_paypal_enabled', isset($_POST['pasiones_paypal_enabled']) ? 1 : 0);
    update_option('pasiones_paypal_client_id', sanitize_text_field($_POST['pasiones_paypal_client_id']));
    update_option('pasiones_paypal_secret', sanitize_text_field($_POST['pasiones_paypal_secret']));
    update_option('pasiones_paypal_mode', sanitize_text_field($_POST['pasiones_paypal_mode']));

    // Comisiones
    update_option('pasiones_admin_commission', floatval($_POST['pasiones_admin_commission']));
    update_option('pasiones_min_withdrawal', floatval($_POST['pasiones_min_withdrawal']));

    // Videochat
    update_option('pasiones_videochat_enabled', isset($_POST['pasiones_videochat_enabled']) ? 1 : 0);
    update_option('pasiones_streaming_enabled', isset($_POST['pasiones_streaming_enabled']) ? 1 : 0);
    update_option('pasiones_default_cost_per_minute', floatval($_POST['pasiones_default_cost_per_minute']));

    echo '<div class="notice notice-success"><p>' . __('Configuración guardada correctamente', 'pasiones-platform') . '</p></div>';
}
?>

<div class="wrap pasiones-admin">
    <h1><?php _e('Configuración - Pasiones Platform', 'pasiones-platform'); ?></h1>

    <form method="post" action="">
        <?php wp_nonce_field('pasiones_settings'); ?>

        <div class="pasiones-settings-tabs">
            <nav class="nav-tab-wrapper">
                <a href="#general" class="nav-tab nav-tab-active"><?php _e('General', 'pasiones-platform'); ?></a>
                <a href="#memberships" class="nav-tab"><?php _e('Membresías', 'pasiones-platform'); ?></a>
                <a href="#payments" class="nav-tab"><?php _e('Pagos', 'pasiones-platform'); ?></a>
                <a href="#videochat" class="nav-tab"><?php _e('Videochat', 'pasiones-platform'); ?></a>
            </nav>

            <!-- Tab: General -->
            <div id="general" class="tab-content active">
                <h2><?php _e('Configuración General', 'pasiones-platform'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="pasiones_currency"><?php _e('Moneda', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <select name="pasiones_currency" id="pasiones_currency">
                                <option value="EUR" <?php selected(get_option('pasiones_currency'), 'EUR'); ?>>EUR (€)</option>
                                <option value="USD" <?php selected(get_option('pasiones_currency'), 'USD'); ?>>USD ($)</option>
                                <option value="GBP" <?php selected(get_option('pasiones_currency'), 'GBP'); ?>>GBP (£)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="pasiones_currency_symbol"><?php _e('Símbolo de Moneda', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="pasiones_currency_symbol" id="pasiones_currency_symbol" value="<?php echo esc_attr(get_option('pasiones_currency_symbol', '€')); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Tab: Memberships -->
            <div id="memberships" class="tab-content">
                <h2><?php _e('Precios de Membresías', 'pasiones-platform'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="pasiones_membership_bronze_price"><?php _e('Bronce (€/mes)', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="number" step="0.01" name="pasiones_membership_bronze_price" id="pasiones_membership_bronze_price" value="<?php echo esc_attr(get_option('pasiones_membership_bronze_price', 20)); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="pasiones_membership_silver_price"><?php _e('Plata (€/mes)', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="number" step="0.01" name="pasiones_membership_silver_price" id="pasiones_membership_silver_price" value="<?php echo esc_attr(get_option('pasiones_membership_silver_price', 45)); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="pasiones_membership_gold_price"><?php _e('Oro (€/mes)', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="number" step="0.01" name="pasiones_membership_gold_price" id="pasiones_membership_gold_price" value="<?php echo esc_attr(get_option('pasiones_membership_gold_price', 65)); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Tab: Payments -->
            <div id="payments" class="tab-content">
                <h2><?php _e('Configuración de Pagos', 'pasiones-platform'); ?></h2>

                <h3><?php _e('Stripe', 'pasiones-platform'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="pasiones_stripe_enabled"><?php _e('Habilitar Stripe', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" name="pasiones_stripe_enabled" id="pasiones_stripe_enabled" value="1" <?php checked(get_option('pasiones_stripe_enabled'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="pasiones_stripe_publishable_key"><?php _e('Publishable Key', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="pasiones_stripe_publishable_key" id="pasiones_stripe_publishable_key" value="<?php echo esc_attr(get_option('pasiones_stripe_publishable_key')); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="pasiones_stripe_secret_key"><?php _e('Secret Key', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="password" name="pasiones_stripe_secret_key" id="pasiones_stripe_secret_key" value="<?php echo esc_attr(get_option('pasiones_stripe_secret_key')); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>

                <h3><?php _e('PayPal', 'pasiones-platform'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="pasiones_paypal_enabled"><?php _e('Habilitar PayPal', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" name="pasiones_paypal_enabled" id="pasiones_paypal_enabled" value="1" <?php checked(get_option('pasiones_paypal_enabled'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="pasiones_paypal_client_id"><?php _e('Client ID', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="pasiones_paypal_client_id" id="pasiones_paypal_client_id" value="<?php echo esc_attr(get_option('pasiones_paypal_client_id')); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="pasiones_paypal_secret"><?php _e('Secret', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="password" name="pasiones_paypal_secret" id="pasiones_paypal_secret" value="<?php echo esc_attr(get_option('pasiones_paypal_secret')); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="pasiones_paypal_mode"><?php _e('Modo', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <select name="pasiones_paypal_mode" id="pasiones_paypal_mode">
                                <option value="sandbox" <?php selected(get_option('pasiones_paypal_mode'), 'sandbox'); ?>>Sandbox</option>
                                <option value="live" <?php selected(get_option('pasiones_paypal_mode'), 'live'); ?>>Live</option>
                            </select>
                        </td>
                    </tr>
                </table>

                <h3><?php _e('Comisiones', 'pasiones-platform'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="pasiones_admin_commission"><?php _e('Comisión del Admin (%)', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="number" step="1" name="pasiones_admin_commission" id="pasiones_admin_commission" value="<?php echo esc_attr(get_option('pasiones_admin_commission', 20)); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="pasiones_min_withdrawal"><?php _e('Retiro Mínimo (€)', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="number" step="0.01" name="pasiones_min_withdrawal" id="pasiones_min_withdrawal" value="<?php echo esc_attr(get_option('pasiones_min_withdrawal', 50)); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Tab: Videochat -->
            <div id="videochat" class="tab-content">
                <h2><?php _e('Configuración de Videochat y Streaming', 'pasiones-platform'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="pasiones_videochat_enabled"><?php _e('Habilitar Videochat', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" name="pasiones_videochat_enabled" id="pasiones_videochat_enabled" value="1" <?php checked(get_option('pasiones_videochat_enabled'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="pasiones_streaming_enabled"><?php _e('Habilitar Streaming', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" name="pasiones_streaming_enabled" id="pasiones_streaming_enabled" value="1" <?php checked(get_option('pasiones_streaming_enabled'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="pasiones_default_cost_per_minute"><?php _e('Costo por Defecto (€/min)', 'pasiones-platform'); ?></label>
                        </th>
                        <td>
                            <input type="number" step="0.01" name="pasiones_default_cost_per_minute" id="pasiones_default_cost_per_minute" value="<?php echo esc_attr(get_option('pasiones_default_cost_per_minute', 2.5)); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <p class="submit">
            <input type="submit" name="pasiones_save_settings" class="button button-primary" value="<?php _e('Guardar Cambios', 'pasiones-platform'); ?>">
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');

        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');

        $('.tab-content').removeClass('active');
        $(target).addClass('active');
    });
});
</script>

<style>
.pasiones-settings-tabs {
    margin-top: 20px;
}

.tab-content {
    display: none;
    background: white;
    padding: 20px;
    border: 1px solid #c3c4c7;
    border-top: 0;
}

.tab-content.active {
    display: block;
}

.tab-content h2 {
    margin-top: 0;
}

.tab-content h3 {
    margin-top: 30px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
}
</style>
