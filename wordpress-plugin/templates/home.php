<?php
/**
 * Template: Home
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="pasiones-home">
    <!-- Hero Section -->
    <section class="pasiones-hero">
        <div class="container">
            <h1><?php _e('Conecta con Profesionales Expertos en Tiempo Real', 'pasiones-platform'); ?></h1>
            <p><?php _e('Videochat, streaming y consultas con los mejores profesionales', 'pasiones-platform'); ?></p>

            <div class="search-bar">
                <input type="text" id="pasiones-search" placeholder="<?php _e('Buscar profesionales, categorías...', 'pasiones-platform'); ?>">
                <button class="btn-primary"><?php _e('Buscar', 'pasiones-platform'); ?></button>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="pasiones-categories">
        <div class="container">
            <h2><?php _e('Explora por Categoría', 'pasiones-platform'); ?></h2>
            <div class="categories-grid">
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'pasiones_category',
                    'hide_empty' => false,
                ));

                foreach ($categories as $category) : ?>
                    <div class="category-card">
                        <a href="<?php echo get_term_link($category); ?>">
                            <h3><?php echo esc_html($category->name); ?></h3>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Professionals -->
    <section class="pasiones-featured">
        <div class="container">
            <h2><?php _e('Profesionales Destacados', 'pasiones-platform'); ?></h2>
            <div class="professionals-grid">
                <?php
                $args = array(
                    'post_type' => 'pasiones_professional',
                    'posts_per_page' => 6,
                    'meta_query' => array(
                        array(
                            'key' => '_pasiones_featured',
                            'value' => '1',
                        ),
                    ),
                );

                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        include PASIONES_PLUGIN_DIR . 'templates/parts/professional-card.php';
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Online Professionals -->
    <section class="pasiones-online">
        <div class="container">
            <h2><?php _e('Profesionales en Línea', 'pasiones-platform'); ?></h2>
            <div class="professionals-grid">
                <?php
                $args = array(
                    'post_type' => 'pasiones_professional',
                    'posts_per_page' => 6,
                    'meta_query' => array(
                        array(
                            'key' => '_pasiones_online',
                            'value' => '1',
                        ),
                    ),
                );

                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        include PASIONES_PLUGIN_DIR . 'templates/parts/professional-card.php';
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>
</div>

<style>
.pasiones-home {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.pasiones-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: white;
    padding: 80px 20px;
    text-align: center;
}

.pasiones-hero h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.search-bar {
    max-width: 600px;
    margin: 2rem auto;
    display: flex;
    gap: 10px;
}

.search-bar input {
    flex: 1;
    padding: 12px 20px;
    border-radius: 8px;
    border: none;
}

.btn-primary {
    background: #10b981;
    color: white;
    padding: 12px 32px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}

.pasiones-categories,
.pasiones-featured,
.pasiones-online {
    padding: 60px 20px;
}

.categories-grid,
.professionals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 2rem;
}

.category-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>
