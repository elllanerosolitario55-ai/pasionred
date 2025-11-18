<?php
/**
 * Taxonomías personalizadas de Pasiones Platform
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pasiones_Taxonomies {

    /**
     * Inicializar
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_taxonomies'));
    }

    /**
     * Registrar taxonomías
     */
    public static function register_taxonomies() {

        // Categorías de profesionales
        register_taxonomy('pasiones_category', array('pasiones_professional'), array(
            'labels' => array(
                'name' => __('Categorías', 'pasiones-platform'),
                'singular_name' => __('Categoría', 'pasiones-platform'),
                'add_new_item' => __('Añadir Nueva Categoría', 'pasiones-platform'),
            ),
            'hierarchical' => true,
            'public' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'categoria'),
            'show_admin_column' => true,
        ));

        // Países
        register_taxonomy('pasiones_country', array('pasiones_professional'), array(
            'labels' => array(
                'name' => __('Países', 'pasiones-platform'),
                'singular_name' => __('País', 'pasiones-platform'),
            ),
            'hierarchical' => true,
            'public' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'pais'),
            'show_admin_column' => true,
        ));

        // Provincias
        register_taxonomy('pasiones_province', array('pasiones_professional'), array(
            'labels' => array(
                'name' => __('Provincias', 'pasiones-platform'),
                'singular_name' => __('Provincia', 'pasiones-platform'),
            ),
            'hierarchical' => true,
            'public' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'provincia'),
            'show_admin_column' => true,
        ));

        // Crear categorías por defecto
        self::create_default_terms();
    }

    /**
     * Crear términos por defecto
     */
    private static function create_default_terms() {
        // Solo crear si no existen
        if (get_option('pasiones_default_terms_created')) {
            return;
        }

        // Categorías de profesionales
        $categories = array(
            'Coaches' => 'Profesionales del coaching personal y empresarial',
            'Consultores' => 'Consultores de negocio y estrategia',
            'Médicos' => 'Profesionales de la medicina',
            'Naturópatas' => 'Especialistas en medicina natural',
            'Psicólogos' => 'Psicólogos y terapeutas',
            'Asesoría de Parejas' => 'Especialistas en relaciones',
            'Abogados' => 'Profesionales del derecho',
            'Nutricionistas' => 'Especialistas en nutrición',
            'Entrenadores Personales' => 'Entrenadores fitness',
            'Asesores Financieros' => 'Expertos en finanzas',
        );

        foreach ($categories as $cat_name => $cat_desc) {
            if (!term_exists($cat_name, 'pasiones_category')) {
                wp_insert_term($cat_name, 'pasiones_category', array(
                    'description' => $cat_desc,
                    'slug' => sanitize_title($cat_name),
                ));
            }
        }

        // Países con sus provincias
        $countries_data = array(
            'España' => array(
                'Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza', 'Málaga',
                'Murcia', 'Palma de Mallorca', 'Las Palmas', 'Bilbao'
            ),
            'Portugal' => array(
                'Lisboa', 'Oporto', 'Braga', 'Coimbra', 'Faro'
            ),
            'Francia' => array(
                'París', 'Marsella', 'Lyon', 'Toulouse', 'Niza', 'Burdeos'
            ),
            'Alemania' => array(
                'Berlín', 'Hamburgo', 'Múnich', 'Colonia', 'Frankfurt'
            ),
            'Italia' => array(
                'Roma', 'Milán', 'Nápoles', 'Turín', 'Florencia', 'Venecia'
            ),
            'Rumania' => array(
                'Bucarest', 'Cluj-Napoca', 'Timișoara', 'Iași', 'Constanța'
            ),
            'Inglaterra' => array(
                'Londres', 'Manchester', 'Birmingham', 'Liverpool', 'Leeds'
            ),
            'Estados Unidos' => array(
                'Nueva York', 'Los Ángeles', 'Chicago', 'Houston', 'Miami', 'San Francisco'
            ),
            'Canadá' => array(
                'Toronto', 'Montreal', 'Vancouver', 'Calgary', 'Ottawa'
            ),
            'México' => array(
                'Ciudad de México', 'Guadalajara', 'Monterrey', 'Puebla', 'Cancún'
            ),
            'Argentina' => array(
                'Buenos Aires', 'Córdoba', 'Rosario', 'Mendoza', 'La Plata'
            ),
            'Colombia' => array(
                'Bogotá', 'Medellín', 'Cali', 'Barranquilla', 'Cartagena'
            ),
            'Brasil' => array(
                'São Paulo', 'Río de Janeiro', 'Brasilia', 'Salvador', 'Fortaleza'
            ),
            'Chile' => array(
                'Santiago', 'Valparaíso', 'Concepción', 'La Serena', 'Antofagasta'
            ),
            'Perú' => array(
                'Lima', 'Arequipa', 'Cusco', 'Trujillo', 'Piura'
            ),
            'Venezuela' => array(
                'Caracas', 'Maracaibo', 'Valencia', 'Barquisimeto', 'Maracay'
            ),
            'Paraguay' => array(
                'Asunción', 'Ciudad del Este', 'San Lorenzo', 'Luque', 'Capiatá'
            ),
            'Uruguay' => array(
                'Montevideo', 'Salto', 'Paysandú', 'Maldonado', 'Rivera'
            ),
        );

        foreach ($countries_data as $country => $provinces) {
            // Crear país
            $country_term = wp_insert_term($country, 'pasiones_country');

            if (!is_wp_error($country_term)) {
                $country_id = $country_term['term_id'];

                // Crear provincias
                foreach ($provinces as $province) {
                    $province_term = wp_insert_term($province, 'pasiones_province', array(
                        'parent' => $country_id,
                    ));
                }
            }
        }

        update_option('pasiones_default_terms_created', true);
    }
}
