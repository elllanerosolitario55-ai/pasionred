<?php
/**
 * Custom template tags for this theme
 *
 * @package Pasiones_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Displays membership badge
 */
function pasiones_membership_badge($membership_type) {
    $membership_icons = array(
        'GOLD' => '👑',
        'SILVER' => '🥈',
        'BRONZE' => '🥉',
        'FREE' => '⚪',
    );

    $membership_colors = array(
        'GOLD' => 'gold',
        'SILVER' => 'silver',
        'BRONZE' => 'bronze',
        'FREE' => 'free',
    );

    $icon = isset($membership_icons[$membership_type]) ? $membership_icons[$membership_type] : '';
    $color = isset($membership_colors[$membership_type]) ? $membership_colors[$membership_type] : 'free';

    echo '<span class="membership-badge badge-' . esc_attr($color) . '">';
    echo $icon . ' ' . esc_html($membership_type);
    echo '</span>';
}

/**
 * Display star rating
 */
function pasiones_star_rating($rating, $show_value = true) {
    $rating = (float) $rating;
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;

    echo '<div class="star-rating">';

    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $full_stars) {
            echo '<span class="star star-full">⭐</span>';
        } elseif ($i == $full_stars + 1 && $half_star) {
            echo '<span class="star star-half">⭐</span>';
        } else {
            echo '<span class="star star-empty">☆</span>';
        }
    }

    if ($show_value) {
        echo '<span class="rating-value">' . number_format($rating, 1) . '</span>';
    }

    echo '</div>';
}

/**
 * Display online status badge
 */
function pasiones_online_badge($is_online) {
    if ($is_online) {
        echo '<span class="status-badge online">';
        echo '<span class="pulse-dot"></span> ';
        echo esc_html__('En Línea', 'pasiones-theme');
        echo '</span>';
    } else {
        echo '<span class="status-badge offline">';
        echo esc_html__('Desconectado', 'pasiones-theme');
        echo '</span>';
    }
}

/**
 * Display verified badge
 */
function pasiones_verified_badge() {
    echo '<span class="verified-badge" title="' . esc_attr__('Verificado', 'pasiones-theme') . '">';
    echo '<svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">';
    echo '<path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zm-1 14.414l-4.707-4.707 1.414-1.414L9 11.586l5.293-5.293 1.414 1.414L9 14.414z"/>';
    echo '</svg>';
    echo '</span>';
}

/**
 * Get professional avatar
 */
function pasiones_get_avatar($professional_id, $size = 200) {
    $avatar_url = get_post_meta($professional_id, '_avatar_url', true);
    $user_id = get_post_meta($professional_id, '_user_id', true);

    if ($avatar_url) {
        return '<img src="' . esc_url($avatar_url) . '" alt="' . esc_attr(get_the_title($professional_id)) . '" class="professional-avatar" width="' . esc_attr($size) . '" height="' . esc_attr($size) . '">';
    } elseif ($user_id) {
        return get_avatar($user_id, $size, '', '', array('class' => 'professional-avatar'));
    }

    return '<div class="professional-avatar-placeholder" style="width: ' . $size . 'px; height: ' . $size . 'px;"></div>';
}

/**
 * Format price
 */
function pasiones_format_price($price, $currency = '€') {
    return number_format($price, 2) . $currency;
}

/**
 * Get membership features
 */
function pasiones_get_membership_features($membership_type) {
    $features = array(
        'FREE' => array(
            __('5 publicaciones/mes', 'pasiones-theme'),
            __('Perfil básico', 'pasiones-theme'),
        ),
        'BRONZE' => array(
            __('50 publicaciones/mes', 'pasiones-theme'),
            __('Imágenes de pago', 'pasiones-theme'),
            __('Videos', 'pasiones-theme'),
            __('Videochat', 'pasiones-theme'),
        ),
        'SILVER' => array(
            __('200 publicaciones/mes', 'pasiones-theme'),
            __('Todo lo de Bronce', 'pasiones-theme'),
            __('Streaming en vivo', 'pasiones-theme'),
            __('Alta visibilidad', 'pasiones-theme'),
        ),
        'GOLD' => array(
            __('Publicaciones ilimitadas', 'pasiones-theme'),
            __('Todo lo de Plata', 'pasiones-theme'),
            __('Perfil destacado', 'pasiones-theme'),
            __('Visibilidad premium', 'pasiones-theme'),
            __('Soporte prioritario', 'pasiones-theme'),
        ),
    );

    return isset($features[$membership_type]) ? $features[$membership_type] : array();
}

/**
 * Get membership price
 */
function pasiones_get_membership_price($membership_type) {
    $prices = array(
        'FREE' => 0,
        'BRONZE' => 20,
        'SILVER' => 45,
        'GOLD' => 65,
    );

    return isset($prices[$membership_type]) ? $prices[$membership_type] : 0;
}

/**
 * Display breadcrumbs
 */
function pasiones_breadcrumbs() {
    if (is_front_page()) {
        return;
    }

    echo '<nav class="breadcrumbs" aria-label="Breadcrumb">';
    echo '<ul>';

    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Inicio', 'pasiones-theme') . '</a></li>';

    if (is_category() || is_single()) {
        $category = get_the_category();
        if ($category) {
            echo '<li><a href="' . esc_url(get_category_link($category[0]->term_id)) . '">' . esc_html($category[0]->name) . '</a></li>';
        }
    }

    if (is_single()) {
        echo '<li><span>' . esc_html(get_the_title()) . '</span></li>';
    } elseif (is_page()) {
        echo '<li><span>' . esc_html(get_the_title()) . '</span></li>';
    } elseif (is_search()) {
        echo '<li><span>' . esc_html__('Resultados de búsqueda', 'pasiones-theme') . '</span></li>';
    } elseif (is_404()) {
        echo '<li><span>' . esc_html__('Página no encontrada', 'pasiones-theme') . '</span></li>';
    }

    echo '</ul>';
    echo '</nav>';
}

/**
 * Get post reading time
 */
function pasiones_reading_time() {
    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 words per minute

    return sprintf(
        _n('%s minuto de lectura', '%s minutos de lectura', $reading_time, 'pasiones-theme'),
        number_format_i18n($reading_time)
    );
}

/**
 * Social share buttons
 */
function pasiones_social_share() {
    $url = urlencode(get_permalink());
    $title = urlencode(get_the_title());

    echo '<div class="social-share">';
    echo '<span class="share-label">' . esc_html__('Compartir:', 'pasiones-theme') . '</span>';

    // Facebook
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '" target="_blank" rel="noopener" class="share-btn share-facebook" aria-label="Share on Facebook">';
    echo '<svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>';
    echo '</a>';

    // Twitter
    echo '<a href="https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title . '" target="_blank" rel="noopener" class="share-btn share-twitter" aria-label="Share on Twitter">';
    echo '<svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>';
    echo '</a>';

    // LinkedIn
    echo '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title . '" target="_blank" rel="noopener" class="share-btn share-linkedin" aria-label="Share on LinkedIn">';
    echo '<svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>';
    echo '</a>';

    // WhatsApp
    echo '<a href="https://api.whatsapp.com/send?text=' . $title . '%20' . $url . '" target="_blank" rel="noopener" class="share-btn share-whatsapp" aria-label="Share on WhatsApp">';
    echo '<svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>';
    echo '</a>';

    echo '</div>';
}
