<?php
/**
 * Widget Elementor: Tarjetas de Membresía
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Elementor_Widget_Membership_Cards extends \Elementor\Widget_Base {

    public function get_name() {
        return 'pasiones_membership_cards';
    }

    public function get_title() {
        return __('Tarjetas de Membresía', 'pasiones-platform');
    }

    public function get_icon() {
        return 'eicon-price-table';
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
            'show_free',
            [
                'label' => __('Mostrar Gratis', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sí', 'pasiones-platform'),
                'label_off' => __('No', 'pasiones-platform'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'highlight_plan',
            [
                'label' => __('Plan Destacado', 'pasiones-platform'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __('Ninguno', 'pasiones-platform'),
                    'bronze' => __('Bronce', 'pasiones-platform'),
                    'silver' => __('Plata', 'pasiones-platform'),
                    'gold' => __('Oro', 'pasiones-platform'),
                ],
                'default' => 'gold',
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

        $memberships = Pasiones_Memberships::get_membership_config();

        ?>
        <div class="pasiones-membership-cards">
            <?php if ($settings['show_free'] === 'yes') : ?>
                <div class="membership-card free">
                    <div class="card-header">
                        <h3><?php echo esc_html($memberships['free']['name']); ?></h3>
                        <div class="price">
                            <span class="amount">0€</span>
                            <span class="period">/mes</span>
                        </div>
                    </div>
                    <div class="card-features">
                        <ul>
                            <li>✓ <?php echo $memberships['free']['features']['posts_limit']; ?> publicaciones/mes</li>
                            <li>✗ Sin imágenes de pago</li>
                            <li>✗ Sin videochat</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="btn btn-outline">Comenzar Gratis</a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="membership-card bronze <?php echo $settings['highlight_plan'] === 'bronze' ? 'highlighted' : ''; ?>">
                <div class="card-header">
                    <h3><?php echo esc_html($memberships['bronze']['name']); ?></h3>
                    <div class="price">
                        <span class="amount"><?php echo number_format($memberships['bronze']['price'], 0); ?>€</span>
                        <span class="period">/mes</span>
                    </div>
                </div>
                <div class="card-features">
                    <ul>
                        <li>✓ <?php echo $memberships['bronze']['features']['posts_limit']; ?> publicaciones/mes</li>
                        <li>✓ Imágenes de pago</li>
                        <li>✓ Videochat</li>
                        <li>✓ Reviews</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn btn-primary">Elegir Bronce</a>
                </div>
            </div>

            <div class="membership-card silver <?php echo $settings['highlight_plan'] === 'silver' ? 'highlighted' : ''; ?>">
                <div class="card-header">
                    <h3><?php echo esc_html($memberships['silver']['name']); ?></h3>
                    <div class="price">
                        <span class="amount"><?php echo number_format($memberships['silver']['price'], 0); ?>€</span>
                        <span class="period">/mes</span>
                    </div>
                </div>
                <div class="card-features">
                    <ul>
                        <li>✓ <?php echo $memberships['silver']['features']['posts_limit']; ?> publicaciones/mes</li>
                        <li>✓ Todo lo de Bronce</li>
                        <li>✓ Streaming en vivo</li>
                        <li>✓ Alta visibilidad</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn btn-primary">Elegir Plata</a>
                </div>
            </div>

            <div class="membership-card gold <?php echo $settings['highlight_plan'] === 'gold' ? 'highlighted' : ''; ?>">
                <?php if ($settings['highlight_plan'] === 'gold') : ?>
                    <div class="popular-badge">Más Popular</div>
                <?php endif; ?>
                <div class="card-header">
                    <h3><?php echo esc_html($memberships['gold']['name']); ?></h3>
                    <div class="price">
                        <span class="amount"><?php echo number_format($memberships['gold']['price'], 0); ?>€</span>
                        <span class="period">/mes</span>
                    </div>
                </div>
                <div class="card-features">
                    <ul>
                        <li>✓ <strong>Publicaciones ilimitadas</strong></li>
                        <li>✓ Todo lo de Plata</li>
                        <li>✓ Perfil destacado</li>
                        <li>✓ Soporte prioritario</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn btn-primary">Elegir Oro</a>
                </div>
            </div>
        </div>

        <style>
        .pasiones-membership-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }

        .membership-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }

        .membership-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .membership-card.highlighted {
            transform: scale(1.05);
            border: 2px solid <?php echo esc_attr($settings['primary_color']); ?>;
        }

        .popular-badge {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: <?php echo esc_attr($settings['primary_color']); ?>;
            color: white;
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .card-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .card-header h3 {
            margin: 0 0 20px 0;
            font-size: 24px;
        }

        .price {
            font-size: 14px;
            color: #64748b;
        }

        .price .amount {
            font-size: 48px;
            font-weight: bold;
            color: #1e293b;
            display: block;
        }

        .card-features ul {
            list-style: none;
            padding: 0;
            margin: 0 0 30px 0;
        }

        .card-features li {
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-footer {
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background: <?php echo esc_attr($settings['primary_color']); ?>;
            color: white;
        }

        .btn-primary:hover {
            background: #059669;
        }

        .btn-outline {
            border: 2px solid #e2e8f0;
            color: #64748b;
        }

        .btn-outline:hover {
            border-color: <?php echo esc_attr($settings['primary_color']); ?>;
            color: <?php echo esc_attr($settings['primary_color']); ?>;
        }

        @media (max-width: 768px) {
            .pasiones-membership-cards {
                grid-template-columns: 1fr;
            }
            .membership-card.highlighted {
                transform: scale(1);
            }
        }
        </style>
        <?php
    }
}
