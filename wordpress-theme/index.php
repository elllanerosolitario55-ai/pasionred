<?php
/**
 * The main template file
 *
 * @package Pasiones_Theme
 */

get_header();
?>

<div class="container">
    <div class="content-area">
        <?php
        if (have_posts()) :

            // Hero section for home
            if (is_home() && !is_paged()) :
                ?>
                <section class="hero-section">
                    <div class="hero-content">
                        <h1 class="hero-title">
                            <?php
                            $hero_title = get_theme_mod('pasiones_hero_title', __('Conecta con Profesionales', 'pasiones-theme'));
                            echo esc_html($hero_title);
                            ?>
                        </h1>
                        <p class="hero-description">
                            <?php
                            $hero_desc = get_theme_mod('pasiones_hero_description', __('Encuentra expertos verificados para ayudarte', 'pasiones-theme'));
                            echo esc_html($hero_desc);
                            ?>
                        </p>
                        <?php
                        if (function_exists('pasiones_search_form')) {
                            pasiones_search_form();
                        }
                        ?>
                    </div>
                </section>
                <?php
            endif;

            ?>
            <div class="posts-grid grid grid-3">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', get_post_type());
                endwhile;
                ?>
            </div>

            <?php
            pasiones_pagination();

        else :
            get_template_part('template-parts/content', 'none');
        endif;
        ?>
    </div>

    <?php if (is_active_sidebar('sidebar-1')) : ?>
        <aside class="sidebar">
            <?php dynamic_sidebar('sidebar-1'); ?>
        </aside>
    <?php endif; ?>
</div>

<?php
get_footer();
