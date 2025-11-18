<?php
/**
 * Integration with Pasiones Platform Plugin
 *
 * @package Pasiones_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if plugin is active
 */
function pasiones_theme_plugin_is_active() {
    return class_exists('Pasiones_Platform');
}

/**
 * Display professional search form
 */
function pasiones_search_form() {
    if (!pasiones_theme_plugin_is_active()) {
        return;
    }
    ?>
    <form class="professional-search-form" action="<?php echo esc_url(home_url('/professional')); ?>" method="get">
        <div class="search-input-wrapper">
            <input
                type="search"
                name="s"
                placeholder="<?php esc_attr_e('Buscar profesionales...', 'pasiones-theme'); ?>"
                value="<?php echo get_search_query(); ?>"
                class="search-input"
            />
            <button type="submit" class="search-button">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
    <?php
}

/**
 * Get professional categories for display
 */
function pasiones_get_categories_grid() {
    if (!pasiones_theme_plugin_is_active()) {
        return '';
    }

    $categories = get_terms(array(
        'taxonomy' => 'professional_category',
        'hide_empty' => true,
    ));

    if (empty($categories) || is_wp_error($categories)) {
        return '';
    }

    ob_start();
    ?>
    <div class="categories-grid grid grid-3">
        <?php foreach ($categories as $category) : ?>
            <a href="<?php echo esc_url(get_term_link($category)); ?>" class="category-card card">
                <div class="category-icon">
                    <?php
                    $icon = get_term_meta($category->term_id, 'category_icon', true);
                    echo $icon ? esc_html($icon) : '📋';
                    ?>
                </div>
                <h3 class="category-name"><?php echo esc_html($category->name); ?></h3>
                <p class="category-count">
                    <?php
                    printf(
                        _n('%s profesional', '%s profesionales', $category->count, 'pasiones-theme'),
                        number_format_i18n($category->count)
                    );
                    ?>
                </p>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get countries grid
 */
function pasiones_get_countries_grid() {
    if (!pasiones_theme_plugin_is_active()) {
        return '';
    }

    $countries = get_terms(array(
        'taxonomy' => 'country',
        'hide_empty' => true,
    ));

    if (empty($countries) || is_wp_error($countries)) {
        return '';
    }

    ob_start();
    ?>
    <div class="countries-grid grid grid-4">
        <?php foreach ($countries as $country) : ?>
            <a href="<?php echo esc_url(get_term_link($country)); ?>" class="country-card card">
                <div class="country-flag">
                    <?php
                    $flag = get_term_meta($country->term_id, 'country_flag', true);
                    echo $flag ? esc_html($flag) : '🌍';
                    ?>
                </div>
                <h3 class="country-name"><?php echo esc_html($country->name); ?></h3>
                <p class="country-count">
                    <?php
                    printf(
                        _n('%s profesional', '%s profesionales', $country->count, 'pasiones-theme'),
                        number_format_i18n($country->count)
                    );
                    ?>
                </p>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Display membership cards
 */
function pasiones_membership_cards() {
    if (!pasiones_theme_plugin_is_active()) {
        return;
    }

    $memberships = array(
        array(
            'type' => 'FREE',
            'name' => __('Gratis', 'pasiones-theme'),
            'price' => 0,
            'icon' => '⚪',
            'features' => array(
                __('5 publicaciones/mes', 'pasiones-theme'),
                __('Perfil básico', 'pasiones-theme'),
            ),
        ),
        array(
            'type' => 'BRONZE',
            'name' => __('Bronce', 'pasiones-theme'),
            'price' => 20,
            'icon' => '🥉',
            'features' => array(
                __('50 publicaciones/mes', 'pasiones-theme'),
                __('Imágenes de pago', 'pasiones-theme'),
                __('Videos', 'pasiones-theme'),
                __('Videochat', 'pasiones-theme'),
            ),
        ),
        array(
            'type' => 'SILVER',
            'name' => __('Plata', 'pasiones-theme'),
            'price' => 45,
            'icon' => '🥈',
            'popular' => true,
            'features' => array(
                __('200 publicaciones/mes', 'pasiones-theme'),
                __('Todo lo de Bronce', 'pasiones-theme'),
                __('Streaming en vivo', 'pasiones-theme'),
                __('Alta visibilidad', 'pasiones-theme'),
            ),
        ),
        array(
            'type' => 'GOLD',
            'name' => __('Oro', 'pasiones-theme'),
            'price' => 65,
            'icon' => '👑',
            'recommended' => true,
            'features' => array(
                __('Publicaciones ilimitadas', 'pasiones-theme'),
                __('Todo lo de Plata', 'pasiones-theme'),
                __('Perfil destacado', 'pasiones-theme'),
                __('Visibilidad premium', 'pasiones-theme'),
                __('Soporte prioritario', 'pasiones-theme'),
            ),
        ),
    );

    ob_start();
    ?>
    <div class="memberships-grid grid grid-4">
        <?php foreach ($memberships as $membership) : ?>
            <div class="membership-card card <?php echo isset($membership['popular']) ? 'popular' : ''; ?> <?php echo isset($membership['recommended']) ? 'recommended' : ''; ?>">
                <?php if (isset($membership['popular'])) : ?>
                    <div class="card-badge badge-popular"><?php esc_html_e('Popular', 'pasiones-theme'); ?></div>
                <?php endif; ?>

                <?php if (isset($membership['recommended'])) : ?>
                    <div class="card-badge badge-recommended"><?php esc_html_e('Recomendado', 'pasiones-theme'); ?></div>
                <?php endif; ?>

                <div class="membership-header">
                    <div class="membership-icon"><?php echo esc_html($membership['icon']); ?></div>
                    <h3 class="membership-name"><?php echo esc_html($membership['name']); ?></h3>
                    <div class="membership-price">
                        <span class="price-amount"><?php echo esc_html($membership['price']); ?>€</span>
                        <span class="price-period">/<?php esc_html_e('mes', 'pasiones-theme'); ?></span>
                    </div>
                </div>

                <div class="membership-features">
                    <ul>
                        <?php foreach ($membership['features'] as $feature) : ?>
                            <li>✓ <?php echo esc_html($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="membership-action">
                    <button class="btn btn-primary btn-large" data-membership="<?php echo esc_attr($membership['type']); ?>">
                        <?php echo $membership['price'] == 0 ? esc_html__('Comenzar Gratis', 'pasiones-theme') : esc_html__('Elegir Plan', 'pasiones-theme'); ?>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get online professionals
 */
function pasiones_get_online_professionals($limit = 8) {
    if (!pasiones_theme_plugin_is_active()) {
        return array();
    }

    $args = array(
        'post_type' => 'professional',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => '_is_online',
                'value' => '1',
                'compare' => '=',
            ),
        ),
        'orderby' => 'meta_value_num',
        'meta_key' => '_rating',
        'order' => 'DESC',
    );

    return new WP_Query($args);
}

/**
 * Display online professionals
 */
function pasiones_online_professionals_grid($limit = 8) {
    $query = pasiones_get_online_professionals($limit);

    if (!$query->have_posts()) {
        return '';
    }

    ob_start();
    ?>
    <div class="online-professionals-grid grid grid-4">
        <?php
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', 'professional-card');
        }
        wp_reset_postdata();
        ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get featured professionals (top rated)
 */
function pasiones_get_featured_professionals($limit = 8) {
    if (!pasiones_theme_plugin_is_active()) {
        return array();
    }

    $args = array(
        'post_type' => 'professional',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => '_is_verified',
                'value' => '1',
                'compare' => '=',
            ),
        ),
        'orderby' => 'meta_value_num',
        'meta_key' => '_rating',
        'order' => 'DESC',
    );

    return new WP_Query($args);
}

/**
 * Display stats
 */
function pasiones_display_stats() {
    if (!pasiones_theme_plugin_is_active()) {
        return '';
    }

    // Get counts
    $professionals_count = wp_count_posts('professional');
    $total_professionals = $professionals_count->publish;

    $online_query = new WP_Query(array(
        'post_type' => 'professional',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_is_online',
                'value' => '1',
            ),
        ),
        'fields' => 'ids',
    ));
    $online_count = $online_query->post_count;

    ob_start();
    ?>
    <div class="stats-grid grid grid-3">
        <div class="stat-item">
            <div class="stat-value"><?php echo esc_html($total_professionals); ?>+</div>
            <div class="stat-label"><?php esc_html_e('Profesionales', 'pasiones-theme'); ?></div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo esc_html($online_count); ?></div>
            <div class="stat-label"><?php esc_html_e('En Línea Ahora', 'pasiones-theme'); ?></div>
        </div>
        <div class="stat-item">
            <div class="stat-value">24/7</div>
            <div class="stat-label"><?php esc_html_e('Disponible', 'pasiones-theme'); ?></div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Register shortcodes for easy content building
 */
function pasiones_register_shortcodes() {
    add_shortcode('pasiones_categories', 'pasiones_get_categories_grid');
    add_shortcode('pasiones_countries', 'pasiones_get_countries_grid');
    add_shortcode('pasiones_memberships', 'pasiones_membership_cards');
    add_shortcode('pasiones_online_professionals', 'pasiones_online_professionals_grid');
    add_shortcode('pasiones_stats', 'pasiones_display_stats');
}
add_action('init', 'pasiones_register_shortcodes');
