<?php
/**
 * Template for single professional
 *
 * @package Pasiones_Theme
 */

get_header();
?>

<div class="container">
    <?php
    while (have_posts()) :
        the_post();

        // Get professional meta data
        $professional_id = get_the_ID();
        $user_id = get_post_meta($professional_id, '_user_id', true);
        $category = get_the_terms($professional_id, 'professional_category');
        $country = get_the_terms($professional_id, 'country');
        $province = get_the_terms($professional_id, 'province');
        $membership = get_post_meta($professional_id, '_membership_type', true);
        $cost_per_minute = get_post_meta($professional_id, '_cost_per_minute', true);
        $rating = get_post_meta($professional_id, '_rating', true);
        $is_online = get_post_meta($professional_id, '_is_online', true);
        $is_verified = get_post_meta($professional_id, '_is_verified', true);
        ?>

        <article id="professional-<?php the_ID(); ?>" <?php post_class('professional-single-content'); ?>>
            <!-- Cover Image -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="professional-cover">
                    <?php the_post_thumbnail('full'); ?>
                </div>
            <?php endif; ?>

            <div class="professional-header">
                <div class="professional-avatar">
                    <?php
                    $avatar = get_post_meta($professional_id, '_avatar_url', true);
                    if ($avatar) {
                        echo '<img src="' . esc_url($avatar) . '" alt="' . esc_attr(get_the_title()) . '">';
                    } else {
                        echo get_avatar($user_id, 200);
                    }
                    ?>

                    <?php if ($is_online) : ?>
                        <span class="status-badge online">
                            <span class="pulse"></span> En Línea
                        </span>
                    <?php endif; ?>
                </div>

                <div class="professional-info">
                    <h1 class="professional-name">
                        <?php the_title(); ?>
                        <?php if ($is_verified) : ?>
                            <span class="verified-badge" title="Verificado">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zm-1 14.414l-4.707-4.707 1.414-1.414L9 11.586l5.293-5.293 1.414 1.414L9 14.414z"/>
                                </svg>
                            </span>
                        <?php endif; ?>
                    </h1>

                    <div class="professional-meta">
                        <?php if ($category) : ?>
                            <span class="meta-item category">
                                📋 <?php echo esc_html($category[0]->name); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ($country && $province) : ?>
                            <span class="meta-item location">
                                📍 <?php echo esc_html($province[0]->name); ?>, <?php echo esc_html($country[0]->name); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ($membership) : ?>
                            <span class="meta-item membership membership-<?php echo esc_attr(strtolower($membership)); ?>">
                                <?php
                                $membership_icons = array(
                                    'GOLD' => '👑',
                                    'SILVER' => '🥈',
                                    'BRONZE' => '🥉',
                                    'FREE' => '⚪',
                                );
                                echo isset($membership_icons[$membership]) ? $membership_icons[$membership] . ' ' : '';
                                echo esc_html($membership);
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($rating) : ?>
                        <div class="professional-rating">
                            <?php
                            $stars = round($rating);
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $stars ? '⭐' : '☆';
                            }
                            ?>
                            <span class="rating-value"><?php echo number_format($rating, 1); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="professional-actions">
                        <?php if ($cost_per_minute && $is_online) : ?>
                            <button class="btn btn-primary btn-large" onclick="pasionesStartVideochat(<?php echo esc_js($professional_id); ?>)">
                                🎥 Iniciar Videochat
                                <span class="price"><?php echo esc_html($cost_per_minute); ?>€/min</span>
                            </button>
                        <?php endif; ?>

                        <button class="btn btn-secondary btn-large" onclick="pasionesOpenChat(<?php echo esc_js($professional_id); ?>)">
                            💬 Enviar Mensaje
                        </button>

                        <button class="btn btn-outline" onclick="pasionesToggleFavorite(<?php echo esc_js($professional_id); ?>)">
                            ❤️ Favorito
                        </button>
                    </div>
                </div>
            </div>

            <div class="professional-body">
                <div class="professional-about">
                    <h2>Sobre mí</h2>
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                </div>

                <?php
                // Display availability schedule
                $availability = get_post_meta($professional_id, '_availability_schedule', true);
                if ($availability) :
                    ?>
                    <div class="professional-availability">
                        <h2>Horarios Disponibles</h2>
                        <div class="schedule-grid">
                            <?php
                            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                            $day_names = array(
                                'monday' => 'Lunes',
                                'tuesday' => 'Martes',
                                'wednesday' => 'Miércoles',
                                'thursday' => 'Jueves',
                                'friday' => 'Viernes',
                                'saturday' => 'Sábado',
                                'sunday' => 'Domingo',
                            );

                            foreach ($days as $day) {
                                if (isset($availability[$day]) && !empty($availability[$day]['start'])) {
                                    echo '<div class="schedule-day">';
                                    echo '<strong>' . esc_html($day_names[$day]) . '</strong>';
                                    echo '<span>' . esc_html($availability[$day]['start']) . ' - ' . esc_html($availability[$day]['end']) . '</span>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                // Display reviews
                $reviews = get_post_meta($professional_id, '_reviews', true);
                if ($reviews) :
                    ?>
                    <div class="professional-reviews">
                        <h2>Reseñas (<?php echo count($reviews); ?>)</h2>
                        <div class="reviews-list">
                            <?php foreach ($reviews as $review) : ?>
                                <div class="review-item">
                                    <div class="review-header">
                                        <strong><?php echo esc_html($review['user_name']); ?></strong>
                                        <div class="review-rating">
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $review['rating'] ? '⭐' : '☆';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <p class="review-comment"><?php echo esc_html($review['comment']); ?></p>
                                    <span class="review-date"><?php echo esc_html($review['date']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </article>

    <?php endwhile; ?>
</div>

<script>
function pasionesStartVideochat(professionalId) {
    <?php if (function_exists('pasiones_start_videochat_js')) : ?>
        <?php pasiones_start_videochat_js(); ?>
    <?php else : ?>
        alert('Sistema de videochat en desarrollo');
    <?php endif; ?>
}

function pasionesOpenChat(professionalId) {
    alert('Abriendo chat con profesional ID: ' + professionalId);
}

function pasionesToggleFavorite(professionalId) {
    alert('Agregando a favoritos: ' + professionalId);
}
</script>

<?php
get_footer();
