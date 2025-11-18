<?php
/**
 * The template for displaying all pages
 *
 * @package Pasiones_Theme
 */

get_header();
?>

<div class="container">
    <div class="content-wrapper">
        <?php
        while (have_posts()) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php if (has_post_thumbnail() && !pasiones_is_elementor_page()) : ?>
                    <div class="page-featured-image">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>

                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'pasiones-theme'),
                        'after' => '</div>',
                    ));
                    ?>
                </div>

                <?php if (comments_open() || get_comments_number()) : ?>
                    <div class="comments-wrapper">
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>
            </article>

        <?php endwhile; ?>
    </div>
</div>

<?php
get_footer();
