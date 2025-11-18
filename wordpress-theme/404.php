<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Pasiones_Theme
 */

get_header();
?>

<div class="container">
    <div class="error-404-content">
        <div class="error-404-inner">
            <div class="error-404-number">404</div>
            <h1 class="error-404-title"><?php esc_html_e('Página no encontrada', 'pasiones-theme'); ?></h1>
            <p class="error-404-text">
                <?php esc_html_e('Lo sentimos, la página que buscas no existe o ha sido movida.', 'pasiones-theme'); ?>
            </p>

            <div class="error-404-search">
                <h2><?php esc_html_e('¿Buscas algo específico?', 'pasiones-theme'); ?></h2>
                <?php get_search_form(); ?>
            </div>

            <div class="error-404-links">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                    <?php esc_html_e('Volver al Inicio', 'pasiones-theme'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/profesionales')); ?>" class="btn btn-secondary">
                    <?php esc_html_e('Ver Profesionales', 'pasiones-theme'); ?>
                </a>
            </div>

            <?php if (pasiones_theme_plugin_is_active()) : ?>
                <div class="error-404-categories">
                    <h3><?php esc_html_e('O explora nuestras categorías:', 'pasiones-theme'); ?></h3>
                    <?php echo pasiones_get_categories_grid(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
get_footer();
