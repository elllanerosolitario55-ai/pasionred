# 🎨 PASIONES PLATFORM - WordPress Theme

## 📋 DOCUMENTACIÓN COMPLETA

**Versión:** 1.0.0
**Compatible con:** WordPress 6.0+
**Requiere PHP:** 8.0+
**Requiere:** Plugin Pasiones Platform
**100% Compatible con Elementor**

---

## 🎯 RESUMEN

Theme WordPress profesional diseñado específicamente para PASIONES Platform. Incluye soporte completo para Elementor, templates personalizados para todos los Custom Post Types del plugin, y un diseño moderno y responsive.

---

## 📁 ESTRUCTURA DEL THEME

```
wordpress-theme/
├── style.css                    # Stylesheet principal con metadata
├── functions.php                # Funciones del theme
├── header.php                   # Header template
├── footer.php                   # Footer template
├── index.php                    # Template principal
├── page.php                     # Template para páginas
├── single.php                   # Template para posts
├── single-professional.php      # Template para profesionales
├── archive.php                  # Template de archivo
├── archive-professional.php     # Template para listado de profesionales
├── search.php                   # Template de búsqueda
├── 404.php                      # Página de error 404
├── comments.php                 # Template de comentarios
├── sidebar.php                  # Sidebar
├── README.txt                   # Descripción del theme
├── screenshot.png               # Captura de pantalla (1200x900px)
│
├── inc/                         # Archivos de funcionalidad
│   ├── template-tags.php        # Custom template tags
│   ├── template-functions.php   # Funciones de template
│   ├── customizer.php           # Configuración del Customizer
│   └── plugin-integration.php   # Integración con plugin
│
├── template-parts/              # Partes de templates
│   ├── content.php              # Post content
│   ├── content-none.php         # No content found
│   ├── content-page.php         # Page content
│   ├── content-professional-card.php  # Professional card
│   └── content-search.php       # Search results
│
├── page-templates/              # Page templates
│   ├── template-home.php        # Homepage template
│   ├── template-professionals.php    # Professionals page
│   ├── template-categories.php  # Categories page
│   ├── template-countries.php   # Countries page
│   └── template-memberships.php # Memberships page
│
├── assets/                      # Assets del theme
│   ├── css/
│   │   ├── main.css            # CSS adicional
│   │   └── responsive.css      # CSS responsive
│   ├── js/
│   │   ├── main.js            # JavaScript principal
│   │   ├── customizer.js      # Customizer preview JS
│   │   └── elementor.js       # Elementor custom JS
│   └── images/
│       ├── logo.svg           # Logo del theme
│       └── placeholder.png    # Placeholder image
│
└── languages/                   # Archivos de traducción
    └── pasiones-theme.pot      # Translation template
```

---

## ✨ CARACTERÍSTICAS PRINCIPALES

### 1. **Compatibilidad con Elementor**

El theme es 100% compatible con Elementor:

```php
// functions.php
add_theme_support('elementor');

// Soporte para header/footer de Elementor
if (function_exists('elementor_theme_do_location')) {
    elementor_theme_do_location('header');
    elementor_theme_do_location('footer');
}
```

**Features:**
- Header personalizable con Elementor
- Footer personalizable con Elementor
- Full width templates
- Elementor locations support
- Custom CSS/JS support

### 2. **Templates Personalizados**

#### Single Professional Template
`single-professional.php` - Muestra el perfil completo de un profesional:

**Incluye:**
- Cover image
- Avatar con estado online
- Badge de membresía
- Información del profesional
- Categoría y ubicación
- Rating y reviews
- Botones de acción (Videochat, Mensaje, Favorito)
- Horarios de disponibilidad
- Lista de reviews
- Integración con WebRTC

#### Archive Professional Template
`archive-professional.php` - Listado de profesionales con filtros:

**Features:**
- Filtros por categoría
- Filtros por país/provincia
- Filtros por membresía
- Filtro de online only
- Filtro de verificados only
- **Ordenamiento por prioridad de membresía:**
  1. ORO (máxima visibilidad)
  2. PLATA (alta visibilidad)
  3. BRONCE (media visibilidad)
  4. GRATIS (básica visibilidad)
- Grid responsive (4 columnas → 2 → 1)
- Pagination

### 3. **Shortcodes Incluidos**

Todos registrados automáticamente:

```php
[pasiones_categories]          // Grid de categorías
[pasiones_countries]           // Grid de países
[pasiones_memberships]         // Cards de membresías
[pasiones_online_professionals] // Profesionales en línea
[pasiones_stats]              // Estadísticas de la plataforma
```

**Uso en páginas:**
```
[pasiones_memberships]
[pasiones_online_professionals limit="8"]
[pasiones_stats]
```

### 4. **Customizer Settings**

Accede desde `Apariencia → Personalizar`:

**Secciones:**
1. **Hero Section**
   - Hero Title
   - Hero Description

2. **Colors**
   - Primary Color (default: #ec4899)
   - Secondary Color (default: #3b82f6)

3. **Footer Settings**
   - Copyright Text

4. **Social Media**
   - Facebook URL
   - Twitter URL
   - Instagram URL
   - LinkedIn URL
   - YouTube URL

5. **Layout Options**
   - Sidebar Position (Left/Right/None)

### 5. **Widget Areas**

4 áreas de widgets registradas:

```php
// Sidebar
register_sidebar('sidebar-1');

// Footer (4 columnas)
register_sidebar('footer-1');
register_sidebar('footer-2');
register_sidebar('footer-3');
register_sidebar('footer-4');
```

**Uso:**
```php
<?php if (is_active_sidebar('sidebar-1')) : ?>
    <aside class="sidebar">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </aside>
<?php endif; ?>
```

### 6. **Navigation Menus**

3 ubicaciones de menú:

```php
register_nav_menus(array(
    'primary' => 'Primary Menu',     // Header
    'footer' => 'Footer Menu',       // Footer
    'professional' => 'Professional Menu', // Professional dashboard
));
```

**Configurar:**
1. Ir a `Apariencia → Menús`
2. Crear menús
3. Asignar a ubicaciones

### 7. **Image Sizes**

Tamaños personalizados registrados:

```php
add_image_size('pasiones-large', 1200, 600, true);   // Header images
add_image_size('pasiones-medium', 800, 400, true);   // Card images
add_image_size('pasiones-small', 400, 300, true);    // Thumbnails
add_image_size('pasiones-avatar', 400, 400, true);   // Avatars
```

---

## 🎨 DISEÑO Y ESTILOS

### Variables CSS (Custom Properties)

```css
:root {
    /* Colores principales */
    --color-primary: #ec4899;
    --color-primary-dark: #db2777;
    --color-secondary: #3b82f6;
    --color-secondary-dark: #2563eb;
    --color-emerald: #10b981;

    /* Membresías */
    --color-gold: #ffd700;
    --color-silver: #c0c0c0;
    --color-bronze: #cd7f32;

    /* Grises */
    --color-dark: #0f172a;
    --color-gray-900: #1e293b;
    /* ... más grises */

    /* Tipografía */
    --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto;

    /* Espaciado */
    --container-width: 1200px;

    /* Bordes */
    --radius-md: 12px;
    --radius-lg: 16px;

    /* Sombras */
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);

    /* Transiciones */
    --transition-normal: 300ms ease-in-out;
}
```

### Clases Utility

```css
/* Text Alignment */
.text-center { text-align: center; }
.text-right { text-align: right; }

/* Spacing */
.mb-1 { margin-bottom: 0.5rem; }
.mb-2 { margin-bottom: 1rem; }
.mb-3 { margin-bottom: 1.5rem; }

.py-4 { padding-top: 2rem; padding-bottom: 2rem; }

/* Grid */
.grid { display: grid; gap: 30px; }
.grid-2 { grid-template-columns: repeat(2, 1fr); }
.grid-3 { grid-template-columns: repeat(3, 1fr); }
.grid-4 { grid-template-columns: repeat(4, 1fr); }
```

### Componentes

#### Badges
```html
<span class="badge badge-gold">👑 ORO</span>
<span class="badge badge-silver">🥈 PLATA</span>
<span class="badge badge-bronze">🥉 BRONCE</span>
<span class="badge badge-online">En Línea</span>
```

#### Buttons
```html
<button class="btn btn-primary">Primary</button>
<button class="btn btn-secondary">Secondary</button>
<button class="btn btn-outline">Outline</button>
<button class="btn btn-primary btn-large">Large</button>
```

#### Cards
```html
<div class="card">
    <div class="card-image">...</div>
    <div class="card-content">
        <h3 class="card-title">Title</h3>
        <p class="card-description">Description</p>
    </div>
</div>
```

---

## 🔧 FUNCIONES PERSONALIZADAS

### Template Tags

Disponibles en `inc/template-tags.php`:

#### 1. Membership Badge
```php
<?php pasiones_membership_badge('GOLD'); ?>
// Output: <span class="membership-badge badge-gold">👑 GOLD</span>
```

#### 2. Star Rating
```php
<?php pasiones_star_rating(4.5, true); ?>
// Output: ⭐⭐⭐⭐☆ 4.5
```

#### 3. Online Badge
```php
<?php pasiones_online_badge(true); ?>
// Output: <span class="status-badge online">En Línea</span>
```

#### 4. Verified Badge
```php
<?php pasiones_verified_badge(); ?>
// Output: <span class="verified-badge">✓</span>
```

#### 5. Get Avatar
```php
<?php echo pasiones_get_avatar($professional_id, 200); ?>
```

#### 6. Format Price
```php
<?php echo pasiones_format_price(20, '€'); ?>
// Output: 20.00€
```

#### 7. Breadcrumbs
```php
<?php pasiones_breadcrumbs(); ?>
```

#### 8. Social Share
```php
<?php pasiones_social_share(); ?>
// Muestra botones de Facebook, Twitter, LinkedIn, WhatsApp
```

---

## 📱 RESPONSIVE DESIGN

### Breakpoints

```css
/* Mobile First */
/* Base: Mobile (< 768px) */

@media (max-width: 1024px) {
    /* Tablet */
    .grid-3, .grid-4 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    /* Mobile */
    h1 { font-size: 2rem; }

    .grid, .grid-2, .grid-3, .grid-4 {
        grid-template-columns: 1fr;
    }
}
```

### Mobile Menu

JavaScript automático para menú mobile:

```javascript
// assets/js/main.js
function initMobileMenu() {
    const toggle = document.querySelector('.mobile-menu-toggle');
    const menu = document.querySelector('.site-navigation');

    toggle.addEventListener('click', function() {
        this.classList.toggle('active');
        menu.classList.toggle('active');
    });
}
```

---

## 🚀 INSTALACIÓN Y CONFIGURACIÓN

### 1. Instalación

```bash
# Opción A: Via WordPress Admin
1. Ir a Apariencia → Temas
2. Click en "Añadir Nuevo"
3. Subir archivo ZIP del theme
4. Activar

# Opción B: Via FTP
1. Descomprimir pasiones-theme.zip
2. Subir carpeta a /wp-content/themes/
3. Activar desde panel de WordPress
```

### 2. Instalar Plugin Requerido

```
1. Ir a Plugins → Añadir Nuevo
2. Buscar "Pasiones Platform"
3. Instalar y activar
```

### 3. Configuración Inicial

```
1. Apariencia → Personalizar
   - Configurar colores
   - Agregar logo
   - Configurar hero section

2. Apariencia → Menús
   - Crear menú principal
   - Asignar a "Primary Menu"

3. Apariencia → Widgets
   - Agregar widgets al sidebar
   - Configurar footer widgets

4. Configuración → Lectura
   - Establecer página de inicio
```

### 4. Páginas Recomendadas

Crear estas páginas:

```
- Inicio (Homepage)
- Profesionales (Archive de profesionales)
- Categorías (Lista de categorías)
- Países (Lista de países)
- Membresías (Pricing)
- Sobre Nosotros
- Contacto
- Política de Privacidad
- Términos de Servicio
```

---

## 🎨 PERSONALIZACIÓN AVANZADA

### Child Theme

Crear un child theme para personalizaciones:

**1. Crear carpeta:**
```
/wp-content/themes/pasiones-theme-child/
```

**2. Crear style.css:**
```css
/*
Theme Name: Pasiones Child Theme
Template: pasiones-theme
*/

@import url("../pasiones-theme/style.css");

/* Tu CSS personalizado aquí */
```

**3. Crear functions.php:**
```php
<?php
function pasiones_child_enqueue_styles() {
    wp_enqueue_style('pasiones-parent-style',
        get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'pasiones_child_enqueue_styles');
```

### Hooks Disponibles

```php
// Acciones
do_action('pasiones_before_header');
do_action('pasiones_after_header');
do_action('pasiones_before_footer');
do_action('pasiones_after_footer');
do_action('pasiones_before_content');
do_action('pasiones_after_content');

// Filtros
apply_filters('pasiones_excerpt_length', 20);
apply_filters('pasiones_excerpt_more', '...');
apply_filters('pasiones_body_classes', $classes);
```

### Añadir CSS Personalizado

**Opción 1: Customizer**
```
Apariencia → Personalizar → CSS Adicional
```

**Opción 2: functions.php**
```php
function pasiones_custom_css() {
    wp_enqueue_style(
        'pasiones-custom',
        get_stylesheet_directory_uri() . '/custom.css'
    );
}
add_action('wp_enqueue_scripts', 'pasiones_custom_css');
```

---

## 🔌 INTEGRACIÓN CON PLUGIN

### Verificar Plugin Activo

```php
if (pasiones_theme_plugin_is_active()) {
    // El plugin está activo
    // Usar funcionalidades
}
```

### Funciones de Integración

Disponibles en `inc/plugin-integration.php`:

```php
// Obtener profesionales online
$query = pasiones_get_online_professionals(8);

// Obtener profesionales destacados
$query = pasiones_get_featured_professionals(8);

// Mostrar grid de categorías
echo pasiones_get_categories_grid();

// Mostrar grid de países
echo pasiones_get_countries_grid();

// Mostrar cards de membresías
echo pasiones_membership_cards();

// Mostrar estadísticas
echo pasiones_display_stats();
```

---

## 📊 SEO OPTIMIZATION

### Features SEO

1. **Meta Tags Dinámicos**
```php
// Automático con Yoast SEO / Rank Math
```

2. **Schema Markup**
```php
// JSON-LD para profesionales
{
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "Professional Name",
    "jobTitle": "Category",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Province",
        "addressCountry": "Country"
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.5",
        "reviewCount": "24"
    }
}
```

3. **Sitemap XML**
- Compatible con Yoast SEO
- Compatible con Rank Math

4. **URLs Amigables**
```
/professional/maria-garcia/
/professional_category/psicologos/
/country/españa/
/province/madrid/
```

---

## ⚡ OPTIMIZACIÓN DE RENDIMIENTO

### Optimizaciones Incluidas

1. **Lazy Loading de Imágenes**
```php
// Automático en WordPress 5.5+
<img loading="lazy" src="...">
```

2. **Defer JavaScript**
```php
// main.js se carga con defer
<script src="main.js" defer></script>
```

3. **Preload Fonts**
```php
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
```

4. **Remove Query Strings**
```php
// Automático via template-functions.php
```

5. **Disable Emojis**
```php
// Automático via template-functions.php
```

### Plugins Recomendados

```
- WP Rocket (Caché)
- Imagify (Optimización de imágenes)
- WP Super Minify (Minify CSS/JS)
- Autoptimize (Optimización)
```

---

## 🌐 TRADUCCIÓN

### Archivos de Traducción

Ubicación: `languages/pasiones-theme.pot`

### Traducir con Loco Translate

```
1. Instalar plugin Loco Translate
2. Ir a Loco Translate → Themes
3. Seleccionar "Pasiones Theme"
4. Agregar idioma
5. Traducir strings
6. Guardar
```

### Traducir Manualmente

```bash
# 1. Generar .pot
wp i18n make-pot . languages/pasiones-theme.pot

# 2. Crear .po para español
msginit -i languages/pasiones-theme.pot -o languages/es_ES.po -l es_ES

# 3. Traducir con Poedit

# 4. Generar .mo
msgfmt languages/es_ES.po -o languages/es_ES.mo
```

### Strings Traducibles

```php
__('Text', 'pasiones-theme')              // Traducir
_e('Text', 'pasiones-theme')              // Echo traducir
esc_html__('Text', 'pasiones-theme')      // Escape + traducir
esc_html_e('Text', 'pasiones-theme')      // Escape + echo + traducir
_n('Singular', 'Plural', $n, 'pasiones-theme')  // Plurales
```

---

## 📞 SOPORTE Y RECURSOS

### Documentación
- Theme docs: `/path/to/.same/wordpress-theme-docs.md`
- Plugin docs: Ver plugin documentation
- WordPress Codex: https://codex.wordpress.org
- Elementor docs: https://elementor.com/help

### Soporte
- Email: support@pasiones-platform.com
- Documentación: https://docs.pasiones-platform.com
- GitHub Issues: (if applicable)

### Recursos Útiles
- Elementor: https://elementor.com
- Google Fonts: https://fonts.google.com
- Lucide Icons: https://lucide.dev
- WordPress Support: https://wordpress.org/support

---

## ✅ CHECKLIST POST-INSTALACIÓN

```
Theme Instalado:
□ Theme activado
□ Plugin Pasiones Platform instalado
□ Logo subido
□ Favicon configurado
□ Colores personalizados
□ Hero section configurado

Menús:
□ Menú principal creado
□ Menú footer creado
□ Menús asignados a ubicaciones

Páginas:
□ Página de inicio creada
□ Páginas de servicio creadas
□ Página de contacto creada
□ Páginas legales creadas

Widgets:
□ Sidebar configurado
□ Footer widgets configurados

SEO:
□ Plugin SEO instalado (Yoast/Rank Math)
□ Sitemap generado
□ Google Analytics configurado
□ Search Console verificado

Performance:
□ Plugin de caché instalado
□ Imágenes optimizadas
□ CDN configurado (opcional)

Testing:
□ Responsive probado
□ Navegación funcional
□ Formularios funcionan
□ Links verificados
```

---

## 🎉 CONCLUSIÓN

El theme Pasiones Platform está listo para crear una red social profesional completa. Con soporte completo para Elementor, integración perfecta con el plugin Pasiones Platform y diseño moderno, tienes todo lo necesario para lanzar tu plataforma.

**Próximos Pasos:**
1. Personalizar diseño
2. Crear contenido
3. Configurar funcionalidades del plugin
4. Testing exhaustivo
5. Lanzamiento

**¡Buena suerte con tu proyecto!** 🚀

---

*Última actualización: Noviembre 2025*
*Versión del theme: 1.0.0*
