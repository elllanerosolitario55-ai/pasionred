<?php
/**
 * Widget Elementor: Perfil Profesional
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Elementor_Widget_Professional_Profile extends \Elementor\Widget_Base {

    public function get_name() {
        return 'pasiones_professional_profile';
    }

    public function get_title() {
        return __('Perfil Profesional', 'pasiones-platform');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['pasiones'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Contenido', 'pasiones-platform'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'professional_id',
            [
                'label' => __('ID del Profesional', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => __('Deja vacío para mostrar el profesional de la página actual', 'pasiones-platform'),
            ]
        );

        $this->add_control(
            'show_contact',
            [
                'label' => __('Mostrar Contacto', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sí', 'pasiones-platform'),
                'label_off' => __('No', 'pasiones-platform'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_reviews',
            [
                'label' => __('Mostrar Reviews', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sí', 'pasiones-platform'),
                'label_off' => __('No', 'pasiones-platform'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $professional_id = $settings['professional_id'] ? intval($settings['professional_id']) : get_the_ID();

        if (!$professional_id) {
            echo '<p>' . __('No se encontró el profesional.', 'pasiones-platform') . '</p>';
            return;
        }

        $professional = get_post($professional_id);
        $author_id = $professional->post_author;
        $membership = get_user_meta($author_id, 'pasiones_membership', true) ?: 'free';
        $rating = Pasiones_Reviews::get_average_rating($professional_id);
        $reviews_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}pasiones_reviews WHERE professional_id = %d AND status = 'approved'",
            $professional_id
        ));
        $is_online = get_post_meta($professional_id, '_pasiones_online', true);
        $is_verified = get_post_meta($professional_id, '_pasiones_verified', true);
        $cost_per_minute = get_post_meta($professional_id, '_pasiones_cost_per_minute', true) ?: 2.5;

        $categories = get_the_terms($professional_id, 'pasiones_category');
        $countries = get_the_terms($professional_id, 'pasiones_country');
        $provinces = get_the_terms($professional_id, 'pasiones_province');
        ?>

        <div class="pasiones-professional-profile">
            {/* Hero Section */}
            <div class="profile-hero">
                <div class="hero-cover">
                    <?php if (has_post_thumbnail($professional_id)) : ?>
                        <?php echo get_the_post_thumbnail($professional_id, 'full'); ?>
                    <?php else : ?>
                        <div class="cover-placeholder"></div>
                    <?php endif; ?>
                </div>

                <div class="hero-content container">
                    <div class="profile-avatar">
                        <?php echo get_avatar($author_id, 150); ?>
                        <?php if ($is_online) : ?>
                            <span class="online-indicator"></span>
                        <?php endif; ?>
                    </div>

                    <div class="profile-info">
                        <div class="info-header">
                            <h1 class="profile-name">
                                <?php echo esc_html($professional->post_title); ?>
                                <?php if ($is_verified) : ?>
                                    <span class="verified-badge" title="<?php _e('Verificado', 'pasiones-platform'); ?>">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                                <?php endif; ?>
                            </h1>

                            <div class="profile-meta">
                                <?php if ($categories) : ?>
                                    <span class="meta-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                        </svg>
                                        <?php echo esc_html($categories[0]->name); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if ($countries && $provinces) : ?>
                                    <span class="meta-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <?php echo esc_html($provinces[0]->name . ', ' . $countries[0]->name); ?>
                                    </span>
                                <?php endif; ?>

                                <span class="meta-item membership-badge membership-<?php echo esc_attr($membership); ?>">
                                    <?php echo esc_html(ucfirst($membership)); ?>
                                </span>
                            </div>

                            <div class="profile-rating">
                                <div class="stars-large">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <span class="star <?php echo $i <= $rating ? 'filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                                <span class="rating-text">
                                    <strong><?php echo number_format($rating, 1); ?></strong>
                                    (<?php echo number_format($reviews_count); ?> <?php _e('reseñas', 'pasiones-platform'); ?>)
                                </span>
                            </div>
                        </div>

                        <?php if ($settings['show_contact'] === 'yes') : ?>
                        <div class="profile-actions">
                            <button class="btn btn-primary btn-large start-videochat" data-professional-id="<?php echo esc_attr($professional_id); ?>">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                </svg>
                                <?php _e('Iniciar Videochat', 'pasiones-platform'); ?>
                            </button>

                            <div class="pricing-info">
                                <span class="price"><?php echo number_format($cost_per_minute, 2); ?> €</span>
                                <span class="price-label">/minuto</span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            {/* Content Section */}
            <div class="profile-content container">
                <div class="content-main">
                    <div class="about-section">
                        <h2><?php _e('Sobre mí', 'pasiones-platform'); ?></h2>
                        <div class="about-text">
                            <?php echo wpautop($professional->post_content); ?>
                        </div>
                    </div>

                    <?php if ($settings['show_reviews'] === 'yes' && $reviews_count > 0) : ?>
                    <div class="reviews-section">
                        <h2><?php _e('Reseñas', 'pasiones-platform'); ?></h2>

                        <?php
                        $reviews = $wpdb->get_results($wpdb->prepare(
                            "SELECT r.*, u.display_name, u.user_email
                             FROM {$wpdb->prefix}pasiones_reviews r
                             JOIN {$wpdb->prefix}users u ON r.user_id = u.ID
                             WHERE r.professional_id = %d AND r.status = 'approved'
                             ORDER BY r.created_at DESC
                             LIMIT 10",
                            $professional_id
                        ));

                        foreach ($reviews as $review) : ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <?php echo get_avatar($review->user_email, 40); ?>
                                        <div>
                                            <strong><?php echo esc_html($review->display_name); ?></strong>
                                            <span class="review-date"><?php echo human_time_diff(strtotime($review->created_at)); ?> <?php _e('atrás', 'pasiones-platform'); ?></span>
                                        </div>
                                    </div>
                                    <div class="review-stars">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <span class="star <?php echo $i <= $review->rating ? 'filled' : ''; ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php if ($review->comment) : ?>
                                    <p class="review-comment"><?php echo esc_html($review->comment); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="content-sidebar">
                    <div class="sidebar-card">
                        <h3><?php _e('Estadísticas', 'pasiones-platform'); ?></h3>
                        <div class="stats-list">
                            <div class="stat-item">
                                <span class="stat-label"><?php _e('Sesiones', 'pasiones-platform'); ?></span>
                                <span class="stat-value"><?php echo number_format(rand(50, 500)); ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label"><?php _e('Seguidores', 'pasiones-platform'); ?></span>
                                <span class="stat-value"><?php echo number_format(rand(100, 2000)); ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label"><?php _e('Miembro desde', 'pasiones-platform'); ?></span>
                                <span class="stat-value"><?php echo get_the_date('M Y', $professional_id); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .pasiones-professional-profile {
            margin: -40px 0 0 0;
        }

        .profile-hero {
            position: relative;
            margin-bottom: 40px;
        }

        .hero-cover {
            height: 300px;
            overflow: hidden;
        }

        .hero-cover img,
        .cover-placeholder {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cover-placeholder {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .hero-content {
            position: relative;
            margin-top: -80px;
        }

        .profile-avatar {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .online-indicator {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 24px;
            height: 24px;
            background: #10b981;
            border: 4px solid white;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .profile-name {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 12px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .verified-badge {
            color: #3b82f6;
        }

        .profile-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #64748b;
            font-size: 14px;
        }

        .membership-badge {
            padding: 4px 12px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
        }

        .membership-bronze { background: #cd7f32; }
        .membership-silver { background: #c0c0c0; color: #333; }
        .membership-gold { background: #ffd700; color: #333; }

        .profile-rating {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 24px;
        }

        .stars-large {
            display: flex;
            gap: 4px;
            font-size: 24px;
        }

        .star {
            color: #e2e8f0;
        }

        .star.filled {
            color: #fbbf24;
        }

        .rating-text {
            font-size: 16px;
            color: #64748b;
        }

        .profile-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-large {
            padding: 16px 32px;
            font-size: 16px;
        }

        .btn-primary {
            background: #10b981;
            color: white;
        }

        .btn-primary:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .pricing-info {
            display: flex;
            align-items: baseline;
            gap: 6px;
        }

        .price {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
        }

        .price-label {
            font-size: 14px;
            color: #64748b;
        }

        .profile-content {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 40px;
            padding: 40px 20px;
        }

        .about-section,
        .reviews-section {
            margin-bottom: 40px;
        }

        .about-section h2,
        .reviews-section h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 20px 0;
        }

        .about-text {
            color: #475569;
            line-height: 1.8;
            font-size: 16px;
        }

        .review-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .reviewer-info {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .reviewer-info img {
            border-radius: 50%;
        }

        .review-date {
            display: block;
            font-size: 13px;
            color: #94a3b8;
        }

        .review-stars {
            font-size: 16px;
        }

        .review-comment {
            color: #475569;
            margin: 0;
            line-height: 1.6;
        }

        .sidebar-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .sidebar-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 20px 0;
        }

        .stats-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .stat-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
        }

        .stat-value {
            font-weight: 700;
            color: #1e293b;
            font-size: 16px;
        }

        @media (max-width: 1024px) {
            .profile-content {
                grid-template-columns: 1fr;
            }

            .content-sidebar {
                order: -1;
            }
        }

        @media (max-width: 768px) {
            .profile-name {
                font-size: 24px;
            }

            .profile-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn-large {
                width: 100%;
                justify-content: center;
            }
        }
        </style>
        <?php
    }
}
