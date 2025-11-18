<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site-container">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'pasiones-theme'); ?></a>

    <?php
    // Check if using Elementor header
    if (function_exists('elementor_theme_do_location') && elementor_theme_do_location('header')) {
        return;
    }
    ?>

    <header id="masthead" class="site-header">
        <div class="header-inner container">
            <div class="site-branding">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
                        <span class="logo-icon">❤️</span>
                        <span class="logo-text"><?php bloginfo('name'); ?></span>
                    </a>
                    <?php
                }
                ?>
            </div>

            <nav id="site-navigation" class="site-navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'main-menu',
                    'container' => false,
                    'fallback_cb' => '__return_false',
                    'walker' => new Pasiones_Walker_Nav_Menu(),
                ));
                ?>

                <div class="header-actions">
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(admin_url('profile.php')); ?>" class="btn btn-outline">
                            <?php esc_html_e('Mi Perfil', 'pasiones-theme'); ?>
                        </a>
                        <a href="<?php echo wp_logout_url(home_url()); ?>" class="btn btn-secondary">
                            <?php esc_html_e('Salir', 'pasiones-theme'); ?>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo wp_login_url(); ?>" class="btn btn-outline">
                            <?php esc_html_e('Iniciar Sesión', 'pasiones-theme'); ?>
                        </a>
                        <a href="<?php echo wp_registration_url(); ?>" class="btn btn-primary">
                            <?php esc_html_e('Registrarse', 'pasiones-theme'); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <button class="mobile-menu-toggle" aria-label="Toggle Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </nav>
        </div>
    </header>

    <main id="primary" class="site-content">
