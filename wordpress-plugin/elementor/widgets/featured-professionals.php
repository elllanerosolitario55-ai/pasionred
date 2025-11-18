<?php
/**
 * Widget Elementor: Profesionales Destacados
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Elementor_Widget_Featured_Professionals extends \Elementor\Widget_Base {

    public function get_name() {
        return 'pasiones_featured_professionals';
    }

    public function get_title() {
        return __('Profesionales Destacados', 'pasiones-platform');
    }

    public function get_icon() {
        return 'eicon-star';
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
            'title',
            [
                'label' => __('Título', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Profesionales Destacados', 'pasiones-platform'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtítulo', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Los mejores profesionales de nuestra plataforma', 'pasiones-platform'),
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Número de Profesionales', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 20,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'grid' => __('Grid', 'pasiones-platform'),
                    'slider' => __('Slider', 'pasiones-platform'),
                ],
                'default' => 'grid',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Columnas', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'default' => '3',
                'condition' => [
                    'layout' => 'grid',
                ],
            ]
        );

        $this->end_controls_section();

        // Style
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Estilo', 'pasiones-platform'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __('Color Primario', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#10b981',
            ]
        );

        $this->add_control(
            'show_badges',
            [
                'label' => __('Mostrar Badges', 'pasiones-platform'),
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

        $args = array(
            'post_type' => 'pasiones_professional',
            'posts_per_page' => $settings['posts_per_page'],
            'meta_key' => '_pasiones_featured',
            'meta_value' => '1',
        );

        $query = new WP_Query($args);

        // Ordenar resultados por prioridad de membresía
        if ($query->have_posts()) {
            $posts = $query->posts;

            // Prioridad de membresías: ORO > PLATA > BRONCE > GRATIS
            $membership_priority = array(
                'gold' => 1,
                'silver' => 2,
                'bronze' => 3,
                'free' => 4,
            );

            usort($posts, function($a, $b) use ($membership_priority) {
                $membership_a = get_user_meta(get_post_field('post_author', $a->ID), 'pasiones_membership', true) ?: 'free';
                $membership_b = get_user_meta(get_post_field('post_author', $b->ID), 'pasiones_membership', true) ?: 'free';

                $priority_a = isset($membership_priority[$membership_a]) ? $membership_priority[$membership_a] : 999;
                $priority_b = isset($membership_priority[$membership_b]) ? $membership_priority[$membership_b] : 999;

                if ($priority_a != $priority_b) {
                    return $priority_a - $priority_b;
                }

                // Si tienen la misma membresía, ordenar por rating
                $rating_a = Pasiones_Reviews::get_average_rating($a->ID);
                $rating_b = Pasiones_Reviews::get_average_rating($b->ID);

                return $rating_b - $rating_a;
            });

            $query->posts = $posts;
        }

        $columns = $settings['columns'];
        $layout = $settings['layout'];
        ?>

        <div class="pasiones-featured-section">
            <?php if ($settings['title'] || $settings['subtitle']) : ?>
            <div class="section-header">
                <?php if ($settings['title']) : ?>
                    <h2 class="section-title"><?php echo esc_html($settings['title']); ?></h2>
                <?php endif; ?>
                <?php if ($settings['subtitle']) : ?>
                    <p class="section-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="featured-<?php echo esc_attr($layout); ?> columns-<?php echo esc_attr($columns); ?>">
                <?php
                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        $professional_id = get_the_ID();
                        $rating = Pasiones_Reviews::get_average_rating($professional_id);
                        $membership = get_user_meta(get_post_field('post_author', $professional_id), 'pasiones_membership', true);
                        $is_online = get_post_meta($professional_id, '_pasiones_online', true);
                        $is_verified = get_post_meta($professional_id, '_pasiones_verified', true);
                        ?>

                        <div class="featured-card">
                            <div class="card-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large'); ?>
                                <?php else : ?>
                                    <div class="placeholder-image"></div>
                                <?php endif; ?>

                                <?php if ($settings['show_badges'] === 'yes') : ?>
                                    <div class="card-badges">
                                        <?php if ($is_online) : ?>
                                            <span class="badge badge-online">
                                                <span class="pulse"></span>
                                                <?php _e('En línea', 'pasiones-platform'); ?>
                                            </span>
                                        <?php endif; ?>

                                        <?php if ($membership && $membership !== 'free') : ?>
                                            <span class="badge badge-membership badge-<?php echo esc_attr($membership); ?>">
                                                <?php echo esc_html(ucfirst($membership)); ?>
                                            </span>
                                        <?php endif; ?>

                                        <?php if ($is_verified) : ?>
                                            <span class="badge badge-verified">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <?php _e('Verificado', 'pasiones-platform'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-content">
                                <h3 class="card-title"><?php the_title(); ?></h3>

                                <div class="card-rating">
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <span class="star <?php echo $i <= $rating ? 'filled' : ''; ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="rating-number"><?php echo number_format($rating, 1); ?></span>
                                </div>

                                <p class="card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>

                                <div class="card-footer">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                        <?php _e('Ver Perfil', 'pasiones-platform'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p><?php _e('No hay profesionales destacados.', 'pasiones-platform'); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <style>
        .pasiones-featured-section {
            padding: 40px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 36px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 12px 0;
        }

        .section-subtitle {
            font-size: 18px;
            color: #64748b;
            margin: 0;
        }

        .featured-grid {
            display: grid;
            gap: 30px;
        }

        .featured-grid.columns-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .featured-grid.columns-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .featured-grid.columns-4 {
            grid-template-columns: repeat(4, 1fr);
        }

        .featured-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }

        .featured-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .card-image {
            position: relative;
            height: 280px;
            overflow: hidden;
        }

        .card-image img,
        .placeholder-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .placeholder-image {
            background: linear-gradient(135deg, <?php echo esc_attr($settings['primary_color']); ?> 0%, #059669 100%);
        }

        .card-badges {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .badge-online {
            background: #10b981;
        }

        .pulse {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: white;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .badge-membership.badge-bronze {
            background: #cd7f32;
        }

        .badge-membership.badge-silver {
            background: #c0c0c0;
            color: #333;
        }

        .badge-membership.badge-gold {
            background: #ffd700;
            color: #333;
        }

        .badge-verified {
            background: #3b82f6;
        }

        .card-content {
            padding: 24px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 12px 0;
        }

        .card-rating {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .stars {
            display: flex;
            gap: 2px;
        }

        .star {
            color: #e2e8f0;
            font-size: 18px;
        }

        .star.filled {
            color: #fbbf24;
        }

        .rating-number {
            font-weight: 600;
            color: #1e293b;
        }

        .card-excerpt {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }

        .card-footer {
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            text-align: center;
        }

        .btn-primary {
            background: <?php echo esc_attr($settings['primary_color']); ?>;
            color: white;
        }

        .btn-primary:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        @media (max-width: 1024px) {
            .featured-grid.columns-3,
            .featured-grid.columns-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .featured-grid {
                grid-template-columns: 1fr !important;
            }

            .section-title {
                font-size: 28px;
            }
        }
        </style>
        <?php
    }
}
