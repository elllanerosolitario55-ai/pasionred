<?php
/**
 * Widget Elementor: Grid de Profesionales
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Elementor_Widget_Professionals_Grid extends \Elementor\Widget_Base {

    public function get_name() {
        return 'pasiones_professionals_grid';
    }

    public function get_title() {
        return __('Profesionales Grid', 'pasiones-platform');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
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
            'posts_per_page',
            [
                'label' => __('Número de profesionales', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 50,
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __('Categoría', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_categories_list(),
                'default' => '',
            ]
        );

        $this->add_control(
            'country',
            [
                'label' => __('País', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_countries_list(),
                'default' => '',
            ]
        );

        $this->add_control(
            'membership',
            [
                'label' => __('Tipo de Membresía', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __('Todas', 'pasiones-platform'),
                    'free' => __('Gratis', 'pasiones-platform'),
                    'bronze' => __('Bronce', 'pasiones-platform'),
                    'silver' => __('Plata', 'pasiones-platform'),
                    'gold' => __('Oro', 'pasiones-platform'),
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'show_online_only',
            [
                'label' => __('Solo en línea', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sí', 'pasiones-platform'),
                'label_off' => __('No', 'pasiones-platform'),
                'return_value' => 'yes',
                'default' => '',
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
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Estilo', 'pasiones-platform'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label' => __('Fondo de tarjeta', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .professional-card' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label' => __('Radio del borde', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .professional-card' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $args = array(
            'post_type' => 'pasiones_professional',
            'posts_per_page' => $settings['posts_per_page'],
            'post_status' => 'publish',
            'orderby' => 'meta_value', // Ordenar por membresía
            'order' => 'ASC',
        );

        $tax_query = array('relation' => 'AND');

        if (!empty($settings['category'])) {
            $tax_query[] = array(
                'taxonomy' => 'pasiones_category',
                'field' => 'slug',
                'terms' => $settings['category'],
            );
        }

        if (!empty($settings['country'])) {
            $tax_query[] = array(
                'taxonomy' => 'pasiones_country',
                'field' => 'slug',
                'terms' => $settings['country'],
            );
        }

        if (count($tax_query) > 1) {
            $args['tax_query'] = $tax_query;
        }

        $meta_query = array('relation' => 'AND');

        if (!empty($settings['membership'])) {
            $meta_query[] = array(
                'key' => '_pasiones_membership',
                'value' => $settings['membership'],
            );
        }

        if ($settings['show_online_only'] === 'yes') {
            $meta_query[] = array(
                'key' => '_pasiones_online',
                'value' => '1',
            );
        }

        if (count($meta_query) > 1) {
            $args['meta_query'] = $meta_query;
        }

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
        ?>

        <div class="pasiones-professionals-grid columns-<?php echo esc_attr($columns); ?>">
            <?php
            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                    include PASIONES_PLUGIN_DIR . 'templates/parts/professional-card.php';
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <p><?php _e('No se encontraron profesionales.', 'pasiones-platform'); ?></p>
                <?php
            endif;
            ?>
        </div>

        <style>
        .pasiones-professionals-grid {
            display: grid;
            gap: 20px;
            margin: 20px 0;
        }
        .pasiones-professionals-grid.columns-2 {
            grid-template-columns: repeat(2, 1fr);
        }
        .pasiones-professionals-grid.columns-3 {
            grid-template-columns: repeat(3, 1fr);
        }
        .pasiones-professionals-grid.columns-4 {
            grid-template-columns: repeat(4, 1fr);
        }
        @media (max-width: 768px) {
            .pasiones-professionals-grid {
                grid-template-columns: 1fr !important;
            }
        }
        </style>

        <?php
    }

    private function get_categories_list() {
        $categories = get_terms(array(
            'taxonomy' => 'pasiones_category',
            'hide_empty' => false,
        ));

        $options = array('' => __('Todas las categorías', 'pasiones-platform'));

        foreach ($categories as $category) {
            $options[$category->slug] = $category->name;
        }

        return $options;
    }

    private function get_countries_list() {
        $countries = get_terms(array(
            'taxonomy' => 'pasiones_country',
            'hide_empty' => false,
        ));

        $options = array('' => __('Todos los países', 'pasiones-platform'));

        foreach ($countries as $country) {
            $options[$country->slug] = $country->name;
        }

        return $options;
    }
}
