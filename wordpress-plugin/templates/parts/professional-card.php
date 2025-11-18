<?php
/**
 * Template Part: Professional Card
 */

if (!defined('ABSPATH')) {
    exit;
}

$professional_id = get_the_ID();
$rating = Pasiones_Reviews::get_average_rating($professional_id);
$membership = get_user_meta(get_post_field('post_author', $professional_id), 'pasiones_membership', true);
$is_online = get_post_meta($professional_id, '_pasiones_online', true);
$cost_per_minute = get_post_meta($professional_id, '_pasiones_cost_per_minute', true);
?>

<div class="professional-card" data-professional-id="<?php echo esc_attr($professional_id); ?>">
    <div class="card-header" style="background-image: url('<?php echo get_the_post_thumbnail_url($professional_id, 'large'); ?>');">
        <?php if ($is_online) : ?>
            <span class="badge badge-online"><?php _e('En línea', 'pasiones-platform'); ?></span>
        <?php endif; ?>

        <?php if ($membership) : ?>
            <span class="badge badge-membership badge-<?php echo esc_attr($membership); ?>">
                <?php echo esc_html(ucfirst($membership)); ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="card-body">
        <h3><?php the_title(); ?></h3>

        <div class="rating">
            <span class="stars">
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo $i <= $rating ? '⭐' : '☆';
                }
                ?>
            </span>
            <span class="rating-number"><?php echo number_format($rating, 1); ?></span>
        </div>

        <div class="categories">
            <?php
            $categories = get_the_terms($professional_id, 'pasiones_category');
            if ($categories) {
                foreach ($categories as $category) {
                    echo '<span class="category-tag">' . esc_html($category->name) . '</span>';
                }
            }
            ?>
        </div>

        <p class="excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>

        <div class="card-footer">
            <div class="price">
                <strong><?php echo number_format($cost_per_minute, 2); ?> €</strong>/min
            </div>
            <div class="actions">
                <a href="<?php the_permalink(); ?>" class="btn btn-outline"><?php _e('Ver Perfil', 'pasiones-platform'); ?></a>
                <button class="btn btn-primary start-videochat" data-professional-id="<?php echo esc_attr($professional_id); ?>">
                    <?php _e('Conectar', 'pasiones-platform'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.professional-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.professional-card:hover {
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

.card-header {
    height: 180px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.badge {
    position: absolute;
    top: 12px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: white;
}

.badge-online {
    left: 12px;
    background: #10b981;
}

.badge-membership {
    right: 12px;
}

.badge-bronze { background: #cd7f32; }
.badge-silver { background: #c0c0c0; color: #333; }
.badge-gold { background: #ffd700; color: #333; }

.card-body {
    padding: 20px;
}

.card-body h3 {
    margin: 0 0 10px 0;
    font-size: 1.25rem;
}

.rating {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.categories {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.category-tag {
    background: #f1f5f9;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
}

.excerpt {
    color: #64748b;
    font-size: 14px;
    margin: 12px 0;
}

.card-footer {
    border-top: 1px solid #e2e8f0;
    padding-top: 16px;
}

.price {
    font-size: 14px;
    margin-bottom: 12px;
}

.actions {
    display: flex;
    gap: 8px;
}

.btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    text-decoration: none;
    display: inline-block;
}

.btn-outline {
    background: white;
    border: 1px solid #e2e8f0;
    color: #334155;
}

.btn-primary {
    background: #10b981;
    color: white;
}
</style>
