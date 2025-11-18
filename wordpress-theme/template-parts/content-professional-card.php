<?php
/**
 * Template part for displaying professional card
 *
 * @package Pasiones_Theme
 */

$professional_id = get_the_ID();
$membership = get_post_meta($professional_id, '_membership_type', true);
$cost_per_minute = get_post_meta($professional_id, '_cost_per_minute', true);
$rating = get_post_meta($professional_id, '_rating', true);
$reviews_count = get_post_meta($professional_id, '_reviews_count', true);
$is_online = get_post_meta($professional_id, '_is_online', true);
$is_verified = get_post_meta($professional_id, '_is_verified', true);
$avatar = get_post_meta($professional_id, '_avatar_url', true);
$category = get_the_terms($professional_id, 'professional_category');
$province = get_the_terms($professional_id, 'province');
?>

<article id="professional-<?php the_ID(); ?>" <?php post_class('professional-card card'); ?>>
    <div class="card-image professional-card-image">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('pasiones-medium'); ?>
            </a>
        <?php else : ?>
            <a href="<?php the_permalink(); ?>">
                <div class="placeholder-image" style="background: linear-gradient(135deg, #ec4899, #3b82f6); height: 100%;"></div>
            </a>
        <?php endif; ?>

        <!-- Badges -->
        <div class="card-badges">
            <?php if ($is_online) : ?>
                <span class="badge badge-online">
                    <span class="pulse-dot"></span> EN VIVO
                </span>
            <?php endif; ?>

            <?php if ($membership) : ?>
                <span class="badge badge-<?php echo esc_attr(strtolower($membership)); ?>">
                    <?php echo esc_html($membership); ?>
                </span>
            <?php endif; ?>

            <?php if ($is_verified) : ?>
                <span class="badge badge-verified" title="Verificado">✓</span>
            <?php endif; ?>
        </div>

        <?php if ($province) : ?>
            <span class="card-location">
                📍 <?php echo esc_html($province[0]->name); ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="card-content professional-card-content">
        <h3 class="card-title professional-name">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>

        <?php if ($category) : ?>
            <p class="professional-category">
                <?php echo esc_html($category[0]->name); ?>
            </p>
        <?php endif; ?>

        <?php if ($rating) : ?>
            <div class="professional-rating-small">
                <span class="stars">
                    <?php
                    $full_stars = floor($rating);
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $full_stars ? '⭐' : '☆';
                    }
                    ?>
                </span>
                <span class="rating-value"><?php echo number_format($rating, 1); ?></span>
                <?php if ($reviews_count) : ?>
                    <span class="reviews-count">(<?php echo esc_html($reviews_count); ?>)</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (has_excerpt()) : ?>
            <p class="card-description">
                <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
            </p>
        <?php endif; ?>

        <div class="card-footer">
            <?php if ($cost_per_minute) : ?>
                <span class="price-tag">
                    <?php echo esc_html($cost_per_minute); ?>€/min
                </span>
            <?php endif; ?>

            <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-small">
                <?php echo $is_online ? '🎥 Conectar' : '👤 Ver Perfil'; ?>
            </a>
        </div>
    </div>
</article>
