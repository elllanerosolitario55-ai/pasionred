<?php
/**
 * Widget Elementor: Formulario de Búsqueda
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Elementor_Widget_Search_Form extends \Elementor\Widget_Base {

    public function get_name() {
        return 'pasiones_search_form';
    }

    public function get_title() {
        return __('Buscador de Profesionales', 'pasiones-platform');
    }

    public function get_icon() {
        return 'eicon-search';
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
            'placeholder',
            [
                'label' => __('Placeholder', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Buscar profesionales, categorías...', 'pasiones-platform'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Texto del Botón', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Buscar', 'pasiones-platform'),
            ]
        );

        $this->add_control(
            'show_filters',
            [
                'label' => __('Mostrar Filtros', 'pasiones-platform'),
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
            'primary_color',
            [
                'label' => __('Color primario', 'pasiones-platform'),
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

        $countries = get_terms(array(
            'taxonomy' => 'pasiones_country',
            'hide_empty' => false,
        ));
        ?>

        <div class="pasiones-search-form">
            <form id="pasiones-search" class="search-form" method="GET" action="<?php echo home_url('/profesionales'); ?>">
                <div class="search-main">
                    <input
                        type="text"
                        name="s"
                        placeholder="<?php echo esc_attr($settings['placeholder']); ?>"
                        class="search-input"
                    >
                    <button type="submit" class="search-button">
                        <?php echo esc_html($settings['button_text']); ?>
                    </button>
                </div>

                <?php if ($settings['show_filters'] === 'yes') : ?>
                <div class="search-filters">
                    <select name="category" class="filter-select">
                        <option value=""><?php _e('Todas las categorías', 'pasiones-platform'); ?></option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo esc_attr($category->slug); ?>">
                                <?php echo esc_html($category->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="country" class="filter-select">
                        <option value=""><?php _e('Todos los países', 'pasiones-platform'); ?></option>
                        <?php foreach ($countries as $country) : ?>
                            <option value="<?php echo esc_attr($country->slug); ?>">
                                <?php echo esc_html($country->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="membership" class="filter-select">
                        <option value=""><?php _e('Todas las membresías', 'pasiones-platform'); ?></option>
                        <option value="bronze"><?php _e('Bronce', 'pasiones-platform'); ?></option>
                        <option value="silver"><?php _e('Plata', 'pasiones-platform'); ?></option>
                        <option value="gold"><?php _e('Oro', 'pasiones-platform'); ?></option>
                    </select>

                    <label class="checkbox-label">
                        <input type="checkbox" name="online_only" value="1">
                        <?php _e('Solo en línea', 'pasiones-platform'); ?>
                    </label>
                </div>
                <?php endif; ?>
            </form>
        </div>

        <style>
        .pasiones-search-form {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .search-main {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-input {
            flex: 1;
            padding: 14px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: <?php echo esc_attr($settings['primary_color']); ?>;
            box-shadow: 0 0 0 3px <?php echo esc_attr($settings['primary_color']); ?>20;
        }

        .search-button {
            padding: 14px 40px;
            background: <?php echo esc_attr($settings['primary_color']); ?>;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .search-button:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        }

        .search-filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .filter-select {
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-select:focus {
            outline: none;
            border-color: <?php echo esc_attr($settings['primary_color']); ?>;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .search-main {
                flex-direction: column;
            }

            .search-filters {
                grid-template-columns: 1fr;
            }
        }
        </style>
        <?php
    }
}
