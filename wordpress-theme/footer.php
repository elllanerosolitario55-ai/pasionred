</main><!-- #primary -->

    <?php
    // Check if using Elementor footer
    if (function_exists('elementor_theme_do_location') && elementor_theme_do_location('footer')) {
        wp_footer();
        echo '</div><!-- #page -->';
        echo '</body>';
        echo '</html>';
        return;
    }
    ?>

    <footer id="colophon" class="site-footer">
        <div class="container">
            <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
                <div class="footer-widgets">
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar('footer-1'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_active_sidebar('footer-2')) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar('footer-2'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_active_sidebar('footer-3')) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar('footer-3'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_active_sidebar('footer-4')) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar('footer-4'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="footer-bottom">
                <p>
                    &copy; <?php echo date('Y'); ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>.
                    <?php esc_html_e('Todos los derechos reservados.', 'pasiones-theme'); ?>
                </p>

                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class' => 'footer-menu',
                    'container' => 'nav',
                    'container_class' => 'footer-navigation',
                    'depth' => 1,
                    'fallback_cb' => '__return_false',
                ));
                ?>
            </div>
        </div>
    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
