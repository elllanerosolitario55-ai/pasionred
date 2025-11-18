<?php
/**
 * Vista: Dashboard de Administración
 */

if (!defined('ABSPATH')) {
    exit;
}

$stats = Pasiones_Dashboard::get_stats();
?>

<div class="wrap pasiones-admin">
    <h1><?php _e('Dashboard - Pasiones Platform', 'pasiones-platform'); ?></h1>

    <!-- Stats Cards -->
    <div class="pasiones-stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <span class="dashicons dashicons-businessperson"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['total_professionals']); ?></h3>
                <p><?php _e('Profesionales', 'pasiones-platform'); ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <span class="dashicons dashicons-groups"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['total_users']); ?></h3>
                <p><?php _e('Usuarios Totales', 'pasiones-platform'); ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <span class="dashicons dashicons-star-filled"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['active_memberships']); ?></h3>
                <p><?php _e('Membresías Activas', 'pasiones-platform'); ?></p>
            </div>
        </div>

        <div class="stat-card highlight">
            <div class="stat-icon">
                <span class="dashicons dashicons-money-alt"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['monthly_revenue'], 2); ?> €</h3>
                <p><?php _e('Ingresos del Mes', 'pasiones-platform'); ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <span class="dashicons dashicons-video-alt2"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['active_sessions']); ?></h3>
                <p><?php _e('Sesiones Activas', 'pasiones-platform'); ?></p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="pasiones-quick-actions">
        <h2><?php _e('Acciones Rápidas', 'pasiones-platform'); ?></h2>
        <div class="actions-grid">
            <a href="<?php echo admin_url('admin.php?page=pasiones-memberships'); ?>" class="action-card">
                <span class="dashicons dashicons-admin-users"></span>
                <span><?php _e('Gestionar Membresías', 'pasiones-platform'); ?></span>
            </a>
            <a href="<?php echo admin_url('admin.php?page=pasiones-transactions'); ?>" class="action-card">
                <span class="dashicons dashicons-money"></span>
                <span><?php _e('Ver Transacciones', 'pasiones-platform'); ?></span>
            </a>
            <a href="<?php echo admin_url('admin.php?page=pasiones-reviews'); ?>" class="action-card">
                <span class="dashicons dashicons-star-filled"></span>
                <span><?php _e('Moderar Reviews', 'pasiones-platform'); ?></span>
            </a>
            <a href="<?php echo admin_url('admin.php?page=pasiones-settings'); ?>" class="action-card">
                <span class="dashicons dashicons-admin-settings"></span>
                <span><?php _e('Configuración', 'pasiones-platform'); ?></span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="pasiones-recent-activity">
        <h2><?php _e('Actividad Reciente', 'pasiones-platform'); ?></h2>

        <?php
        global $wpdb;
        $transactions_table = $wpdb->prefix . 'pasiones_transactions';
        $recent_transactions = $wpdb->get_results(
            "SELECT * FROM $transactions_table ORDER BY created_at DESC LIMIT 10"
        );
        ?>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Fecha', 'pasiones-platform'); ?></th>
                    <th><?php _e('Usuario', 'pasiones-platform'); ?></th>
                    <th><?php _e('Tipo', 'pasiones-platform'); ?></th>
                    <th><?php _e('Monto', 'pasiones-platform'); ?></th>
                    <th><?php _e('Estado', 'pasiones-platform'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recent_transactions) : ?>
                    <?php foreach ($recent_transactions as $transaction) : ?>
                        <tr>
                            <td><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($transaction->created_at)); ?></td>
                            <td>
                                <?php
                                $user = get_userdata($transaction->user_id);
                                echo $user ? $user->display_name : __('Usuario eliminado', 'pasiones-platform');
                                ?>
                            </td>
                            <td><?php echo esc_html($transaction->type); ?></td>
                            <td><?php echo number_format($transaction->amount, 2); ?> <?php echo esc_html($transaction->currency); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo esc_attr($transaction->status); ?>">
                                    <?php echo esc_html(ucfirst($transaction->status)); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5"><?php _e('No hay transacciones recientes', 'pasiones-platform'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.pasiones-admin {
    margin: 20px 20px 0 0;
}

.pasiones-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-card.highlight {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.stat-icon {
    font-size: 48px;
    opacity: 0.8;
}

.stat-card.highlight .stat-icon {
    opacity: 1;
}

.stat-content h3 {
    font-size: 32px;
    margin: 0;
    font-weight: 700;
}

.stat-content p {
    margin: 5px 0 0 0;
    opacity: 0.8;
}

.pasiones-quick-actions {
    margin: 30px 0;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.action-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s;
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.action-card .dashicons {
    font-size: 24px;
    color: #10b981;
}

.pasiones-recent-activity {
    margin: 30px 0;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-completed {
    background: #dcfce7;
    color: #166534;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-failed {
    background: #fee2e2;
    color: #991b1b;
}
</style>
