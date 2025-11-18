<?php
/**
 * Template for displaying professional archive
 *
 * @package Pasiones_Theme
 */

get_header();
?>

<div class="container">
    <div class="archive-header">
        <h1 class="archive-title">
            <?php
            if (is_tax('professional_category')) {
                single_term_title();
            } elseif (is_tax('country')) {
                echo '📍 ' . single_term_title('', false);
            } elseif (is_tax('province')) {
                echo '🏙️ ' . single_term_title('', false);
            } else {
                esc_html_e('Profesionales', 'pasiones-theme');
            }
            ?>
        </h1>

        <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
    </div>

    <!-- Filters -->
    <div class="professionals-filters">
        <form method="get" class="filter-form">
            <div class="filter-group">
                <label for="filter-category"><?php esc_html_e('Categoría', 'pasiones-theme'); ?></label>
                <select name="category" id="filter-category">
                    <option value=""><?php esc_html_e('Todas', 'pasiones-theme'); ?></option>
                    <?php
                    $categories = get_terms(array('taxonomy' => 'professional_category', 'hide_empty' => true));
                    foreach ($categories as $category) {
                        $selected = isset($_GET['category']) && $_GET['category'] == $category->slug ? 'selected' : '';
                        echo '<option value="' . esc_attr($category->slug) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="filter-country"><?php esc_html_e('País', 'pasiones-theme'); ?></label>
                <select name="country" id="filter-country">
                    <option value=""><?php esc_html_e('Todos', 'pasiones-theme'); ?></option>
                    <?php
                    $countries = get_terms(array('taxonomy' => 'country', 'hide_empty' => true));
                    foreach ($countries as $country) {
                        $selected = isset($_GET['country']) && $_GET['country'] == $country->slug ? 'selected' : '';
                        echo '<option value="' . esc_attr($country->slug) . '" ' . $selected . '>' . esc_html($country->name) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="filter-membership"><?php esc_html_e('Membresía', 'pasiones-theme'); ?></label>
                <select name="membership" id="filter-membership">
                    <option value=""><?php esc_html_e('Todas', 'pasiones-theme'); ?></option>
                    <option value="GOLD" <?php selected(isset($_GET['membership']) && $_GET['membership'] == 'GOLD'); ?>>👑 Oro</option>
                    <option value="SILVER" <?php selected(isset($_GET['membership']) && $_GET['membership'] == 'SILVER'); ?>>🥈 Plata</option>
                    <option value="BRONZE" <?php selected(isset($_GET['membership']) && $_GET['membership'] == 'BRONZE'); ?>>🥉 Bronce</option>
                    <option value="FREE" <?php selected(isset($_GET['membership']) && $_GET['membership'] == 'FREE'); ?>>⚪ Gratis</option>
                </select>
            </div>

            <div class="filter-group">
                <label>
                    <input type="checkbox" name="online_only" value="1" <?php checked(isset($_GET['online_only']) && $_GET['online_only'] == '1'); ?>>
                    <?php esc_html_e('Solo en línea', 'pasiones-theme'); ?>
                </label>
            </div>

            <div class="filter-group">
                <label>
                    <input type="checkbox" name="verified_only" value="1" <?php checked(isset($_GET['verified_only']) && $_GET['verified_only'] == '1'); ?>>
                    <?php esc_html_e('Solo verificados', 'pasiones-theme'); ?>
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                <?php esc_html_e('Filtrar', 'pasiones-theme'); ?>
            </button>
        </form>
    </div>

    <?php
    // Modify query based on filters
    if (isset($_GET['membership']) || isset($_GET['online_only']) || isset($_GET['verified_only'])) {
        global $wp_query;

        $meta_query = array('relation' => 'AND');

        if (isset($_GET['membership']) && !empty($_GET['membership'])) {
            $meta_query[] = array(
                'key' => '_membership_type',
                'value' => sanitize_text_field($_GET['membership']),
                'compare' => '='
            );
        }

        if (isset($_GET['online_only']) && $_GET['online_only'] == '1') {
            $meta_query[] = array(
                'key' => '_is_online',
                'value' => '1',
                'compare' => '='
            );
        }

        if (isset($_GET['verified_only']) && $_GET['verified_only'] == '1') {
            $meta_query[] = array(
                'key' => '_is_verified',
                'value' => '1',
                'compare' => '='
            );
        }

        if (count($meta_query) > 1) {
            $wp_query->set('meta_query', $meta_query);
        }
    }
    ?>

    <?php if (have_posts()) : ?>
        <div class="professionals-grid grid grid-4">
            <?php
            // Sort by membership priority
            $posts_array = array();
            while (have_posts()) {
                the_post();
                $posts_array[] = $post;
            }

            // Sort by membership
            $membership_priority = array('GOLD' => 1, 'SILVER' => 2, 'BRONZE' => 3, 'FREE' => 4);
            usort($posts_array, function($a, $b) use ($membership_priority) {
                $membership_a = get_post_meta($a->ID, '_membership_type', true);
                $membership_b = get_post_meta($b->ID, '_membership_type', true);

                $priority_a = isset($membership_priority[$membership_a]) ? $membership_priority[$membership_a] : 999;
                $priority_b = isset($membership_priority[$membership_b]) ? $membership_priority[$membership_b] : 999;

                if ($priority_a == $priority_b) {
                    $rating_a = (float)get_post_meta($a->ID, '_rating', true);
                    $rating_b = (float)get_post_meta($b->ID, '_rating', true);
                    return $rating_b <=> $rating_a;
                }

                return $priority_a <=> $priority_b;
            });

            // Display sorted professionals
            foreach ($posts_array as $post) {
                setup_postdata($post);
                get_template_part('template-parts/content', 'professional-card');
            }
            wp_reset_postdata();
            ?>
        </div>

        <?php pasiones_pagination(); ?>

    <?php else : ?>
        <div class="no-results">
            <h2><?php esc_html_e('No se encontraron profesionales', 'pasiones-theme'); ?></h2>
            <p><?php esc_html_e('Intenta ajustar los filtros o busca en otra categoría.', 'pasiones-theme'); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php
get_footer();
