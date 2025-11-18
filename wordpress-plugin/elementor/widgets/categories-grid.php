<?php
/**
 * Widget Elementor: Grid de Categorías
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Elementor_Widget_Categories_Grid extends \Elementor\Widget_Base {

    public function get_name() {
        return 'pasiones_categories_grid';
    }

    public function get_title() {
        return __('Grid de Categorías', 'pasiones-platform');
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
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
            'columns',
            [
                'label' => __('Columnas', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ],
                'default' => '3',
            ]
        );

        $this->add_control(
            'show_count',
            [
                'label' => __('Mostrar Contador', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sí', 'pasiones-platform'),
                'label_off' => __('No', 'pasiones-platform'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label' => __('Mostrar Descripción', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sí', 'pasiones-platform'),
                'label_off' => __('No', 'pasiones-platform'),
                'return_value' => 'yes',
                'default' => 'yes',
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
            'card_background',
            [
                'label' => __('Fondo de Tarjeta', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
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

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $categories = get_terms(array(
            'taxonomy' => 'pasiones_category',
            'hide_empty' => false,
        ));

        $columns = $settings['columns'];
        ?>

        <div class="pasiones-categories-grid columns-<?php echo esc_attr($columns); ?>">
            <?php foreach ($categories as $category) :
                $count = $category->count;
                $link = get_term_link($category);
            ?>
                <a href="<?php echo esc_url($link); ?>" class="category-card">
                    <div class="category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>

                    <div class="category-content">
                        <h3 class="category-name"><?php echo esc_html($category->name); ?></h3>

                        <?php if ($settings['show_description'] === 'yes' && $category->description) : ?>
                            <p class="category-description"><?php echo esc_html($category->description); ?></p>
                        <?php endif; ?>

                        <?php if ($settings['show_count'] === 'yes') : ?>
                            <div class="category-count">
                                <span class="count-number"><?php echo number_format($count); ?></span>
                                <span class="count-label"><?php _e('profesionales', 'pasiones-platform'); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="category-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <style>
        .pasiones-categories-grid {
            display: grid;
            gap: 20px;
            margin: 30px 0;
        }

        .pasiones-categories-grid.columns-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .pasiones-categories-grid.columns-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .pasiones-categories-grid.columns-4 {
            grid-template-columns: repeat(4, 1fr);
        }

        .pasiones-categories-grid.columns-5 {
            grid-template-columns: repeat(5, 1fr);
        }

        .category-card {
            background: <?php echo esc_attr($settings['card_background']); ?>;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 20px;
            text-decoration: none;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-color: <?php echo esc_attr($settings['primary_color']); ?>;
        }

        .category-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, <?php echo esc_attr($settings['primary_color']); ?> 0%, #059669 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .category-content {
            flex: 1;
        }

        .category-name {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 8px 0;
        }

        .category-description {
            font-size: 14px;
            color: #64748b;
            margin: 0 0 12px 0;
            line-height: 1.5;
        }

        .category-count {
            display: flex;
            align-items: baseline;
            gap: 6px;
        }

        .count-number {
            font-size: 18px;
            font-weight: 700;
            color: <?php echo esc_attr($settings['primary_color']); ?>;
        }

        .count-label {
            font-size: 13px;
            color: #94a3b8;
        }

        .category-arrow {
            color: <?php echo esc_attr($settings['primary_color']); ?>;
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s;
        }

        .category-card:hover .category-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        @media (max-width: 1024px) {
            .pasiones-categories-grid.columns-4,
            .pasiones-categories-grid.columns-5 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .pasiones-categories-grid {
                grid-template-columns: 1fr !important;
            }

            .category-card {
                padding: 20px;
            }

            .category-icon {
                width: 48px;
                height: 48px;
            }

            .category-icon svg {
                width: 32px;
                height: 32px;
            }
        }
        </style>
        <?php
    }
}
