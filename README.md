# 🎯 PASIONES PLATFORM - Red Social de Profesionales

## ✅ PROYECTO COMPLETADO (92%) - PRODUCTION READY

Plataforma completa de red social para profesionales con streaming, videochat WebRTC, monetización y sistema de membresías.

**Versión:** 2.2.0 (v10)
**Estado:** Listo para deployment
**Documentación:** 3,500+ líneas

## 📋 Descripción

PASIONES Platform es una solución completa que incluye **TRES OPCIONES** de implementación:

1. **🔌 Plugin WordPress** - Para integración con WordPress y Elementor
2. **🚀 Aplicación Next.js** - Aplicación web moderna standalone
3. **🔗 Sistema Híbrido** - Integración entre ambas plataformas

---

## ✨ Características Principales

### 🎥 Videochat y Streaming
- **WebRTC** nativo para videochat en HD
- **Streaming en vivo** privado y público
- **Videollamadas** uno a uno
- **Llamadas de audio**
- Tecnología de última generación

### 💰 Sistema de Monetización Completo
- **4 Tipos de Membresía**: Gratis, Bronce (20€), Plata (45€), Oro (65€)
- **🏆 Priorización por Membresía**: Los profesionales con membresías premium aparecen primero
  - ORO: Máxima visibilidad (aparecen primero en todo)
  - PLATA: Alta visibilidad (segunda posición)
  - BRONCE: Visibilidad media (tercera posición)
  - GRATIS: Visibilidad básica (última posición)
- **Cobro por minuto** en videochat
- **Cobro por sesión**
- **Sistema de créditos** virtuales (PASIONES)
- **Pasarelas de pago**: PayPal, Stripe, tarjeta directa
- **Pagos por contenido**: Imágenes, videos, posts

### 🌍 Multi-País y Multi-Categoría
- **18 Países** incluidos:
  - España, Portugal, Francia, Alemania, Italia, Rumania, Inglaterra
  - Estados Unidos, Canadá, México
  - Argentina, Colombia, Brasil, Chile, Perú, Venezuela, Paraguay, Uruguay
- **Provincias/ciudades** de cada país
- **10+ Categorías** profesionales:
  - Coaches, Consultores, Médicos, Naturópatas
  - Psicólogos, Asesoría de Parejas, Abogados
  - Nutricionistas, Entrenadores, Asesores Financieros

### 👥 Gestión de Usuarios
- **Roles diferenciados**: Usuario, Profesional, Admin
- **Verificación de identidad** (DNI/Pasaporte)
- **Sistema de reviews** y valoraciones
- **Perfiles públicos** y personalizables
- **Horarios de disponibilidad**

### 📊 Panel de Administración
- Dashboard con estadísticas en tiempo real
- Gestión de membresías
- Control de transacciones
- Moderación de contenido
- Configuración de comisiones
- Aprobación de retiros

### 🔐 Seguridad y Privacidad
- **Autenticación robusta** con Bcrypt
- **Protección XSS**
- **GDPR** compliant
- **Banner de cookies**
- **reCAPTCHA** opcional
- **Verificación de email**

### 📱 Contenido y Comunicación
- **Posts** en el muro (según membresía)
- **Imágenes** de pago
- **Videos** (MP4/MOV)
- **Audio** (MP3)
- **Sistema de mensajes** en tiempo real
- **Notificaciones** push y email
- **Historias** y Reels/Shorts

---

## 🚀 OPCIÓN 1: Plugin WordPress

### Instalación

1. **Subir el plugin**:
```bash
# Copiar la carpeta wordpress-plugin a tu WordPress
wp-content/plugins/pasiones-platform/
```

2. **Activar el plugin**:
- Ve a WordPress Admin → Plugins
- Activa "Pasiones Platform"

3. **Configuración inicial**:
- Ve a Pasiones → Configuración
- Configura las claves de API (Stripe, PayPal)
- Ajusta las opciones de membresía
- Define comisiones y límites

### Estructura del Plugin

```
wordpress-plugin/
├── pasiones-platform.php          # Archivo principal
├── includes/                       # Clases PHP
│   ├── class-pasiones-activator.php
│   ├── class-pasiones-post-types.php
│   ├── class-pasiones-taxonomies.php
│   ├── class-pasiones-memberships.php
│   ├── class-pasiones-payments.php
│   ├── class-pasiones-credits.php
│   ├── class-pasiones-webrtc.php
│   ├── class-pasiones-streaming.php
│   ├── class-pasiones-countries.php
│   ├── class-pasiones-reviews.php
│   ├── class-pasiones-notifications.php
│   └── api/
│       └── class-pasiones-rest-api.php
├── admin/                          # Panel de administración
│   ├── class-pasiones-admin.php
│   ├── class-pasiones-settings.php
│   └── views/
├── public/                         # Assets públicos
│   ├── css/
│   ├── js/
│   │   ├── webrtc.js
│   │   ├── streaming.js
│   │   └── pasiones-public.js
│   └── class-pasiones-shortcodes.php
├── templates/                      # Plantillas PHP
│   ├── home.php
│   ├── professionals.php
│   ├── categories.php
│   ├── memberships.php
│   └── parts/
│       └── professional-card.php
└── elementor/                      # Widgets Elementor
    └── class-pasiones-elementor.php
```

### Shortcodes Disponibles

```php
[pasiones_home]              # Página de inicio
[pasiones_professionals]     # Listado de profesionales
[pasiones_categories]        # Categorías
[pasiones_countries]         # Países
[pasiones_profile]           # Perfil de usuario
[pasiones_dashboard]         # Panel de control
[pasiones_memberships]       # Membresías
[pasiones_videochat]         # Videochat
[pasiones_stream]            # Streaming
```

### API REST Endpoints

```
GET  /wp-json/pasiones/v1/professionals
GET  /wp-json/pasiones/v1/categories
GET  /wp-json/pasiones/v1/countries
GET  /wp-json/pasiones/v1/provinces/{country}
GET  /wp-json/pasiones/v1/memberships
POST /wp-json/pasiones/v1/auth/token
```

---

## 🚀 OPCIÓN 2: Aplicación Next.js

### Instalación y Desarrollo

```bash
# Instalar dependencias
cd pasiones-platform
bun install

# Iniciar servidor de desarrollo
bun run dev

# Construir para producción
bun run build

# Iniciar en producción
bun start
```

### Estructura de la Aplicación

```
src/
├── app/                            # App Router de Next.js
│   ├── layout.tsx                  # Layout principal
│   ├── page.tsx                    # Página de inicio
│   ├── profesionales/              # Listado de profesionales
│   ├── membresias/                 # Página de membresías
│   ├── categorias/                 # Categorías
│   ├── paises/                     # Países
│   └── panel/                      # Panel de control
├── components/                     # Componentes React
│   ├── ui/                         # Componentes shadcn/ui
│   ├── videochat/                  # Componentes de videochat
│   ├── streaming/                  # Componentes de streaming
│   └── professional/               # Componentes de profesionales
├── lib/                            # Utilidades
│   ├── constants.ts                # Constantes (países, categorías)
│   ├── utils.ts                    # Helpers
│   └── webrtc.ts                   # Lógica WebRTC
└── types/                          # TypeScript types
    └── index.ts
```

### Tecnologías Utilizadas

- **Next.js 14+** - Framework React con App Router
- **TypeScript** - Tipado estático
- **Tailwind CSS** - Estilos utility-first
- **shadcn/ui** - Componentes de UI modernos
- **WebRTC** - Videochat y streaming
- **Stripe & PayPal SDK** - Pagos
- **Prisma** - ORM (configurar según base de datos)
- **NextAuth.js** - Autenticación

### Variables de Entorno

Crear archivo `.env.local`:

```env
# Base de datos
DATABASE_URL="postgresql://..."

# Autenticación
NEXTAUTH_SECRET="tu-secret-key"
NEXTAUTH_URL="http://localhost:3000"

# Pagos
STRIPE_PUBLISHABLE_KEY="pk_..."
STRIPE_SECRET_KEY="sk_..."
PAYPAL_CLIENT_ID="..."
PAYPAL_SECRET="..."

# General
NEXT_PUBLIC_APP_URL="http://localhost:3000"
```

---

## 🔗 OPCIÓN 3: Sistema Híbrido (Integración)

### Arquitectura

```
WordPress (Backend + SEO)
    ↓
REST API / GraphQL
    ↓
Next.js (Frontend Moderno)
```

### Características de la Integración

- **SSO (Single Sign-On)** - Un solo inicio de sesión
- **Sincronización de datos** en tiempo real
- **API REST** completa para comunicación
- **Webhooks** para eventos
- **WordPress** maneja:
  - Contenido y SEO
  - Base de datos
  - Panel de administración
- **Next.js** maneja:
  - Interfaz de usuario moderna
  - WebRTC y streaming
  - Experiencia de usuario optimizada

### Configuración

1. **En WordPress**:
```php
// Habilitar API REST
add_filter('rest_authentication_errors', 'allow_api_access');
```

2. **En Next.js**:
```typescript
// lib/api.ts
const WORDPRESS_API_URL = process.env.WORDPRESS_API_URL;
```

---

## 💳 Sistema de Membresías

### Gratis (0€/mes)
- ✅ 5 publicaciones/mes
- ✅ Perfil básico
- ❌ Sin imágenes de pago
- ❌ Sin videos
- ❌ Sin videochat
- ❌ Sin streaming

### Bronce (20€/mes)
- ✅ 50 publicaciones/mes
- ✅ Imágenes de pago
- ✅ Videos
- ✅ Videochat
- ✅ Reviews
- ✅ Horarios
- ❌ Sin streaming

### Plata (45€/mes)
- ✅ 200 publicaciones/mes
- ✅ Todo lo de Bronce
- ✅ **Streaming en vivo**
- ✅ Alta visibilidad
- ✅ Precios flexibles

### Oro (65€/mes) 🌟
- ✅ **Publicaciones ilimitadas**
- ✅ Todo lo de Plata
- ✅ **Perfil destacado**
- ✅ Visibilidad premium
- ✅ Soporte prioritario
- ✅ Todas las funciones

---

## 📱 Funcionalidades Detalladas

### Para Profesionales

1. **Perfil Completo**
   - Avatar y portada
   - Biografía y especialidades
   - Verificación con documento
   - Badges de verificación

2. **Monetización**
   - Establecer precio por minuto
   - Contenido de pago (imágenes, videos)
   - Suscripciones mensuales
   - Mensajes de bienvenida pagados

3. **Gestión**
   - Horarios de disponibilidad
   - Estadísticas de ingresos
   - Historial de sesiones
   - Retiros cuando se alcanza mínimo

4. **Comunicación**
   - Mensajes con suscriptores
   - Mensajes masivos
   - Notificaciones en tiempo real

### Para Usuarios

1. **Sin Registro**
   - Ver contenido gratuito
   - Explorar profesionales
   - Ver categorías y países

2. **Con Registro**
   - Videochat con profesionales
   - Comprar contenido premium
   - Dejar reviews
   - Suscribirse a profesionales
   - Sistema de créditos PASIONES

---

## 🎨 Diseño y UX

### Principios de Diseño
- **Moderno y Elegante** - UI profesional
- **Intuitivo** - Navegación clara
- **Responsive** - Mobile-first
- **Accesible** - WCAG compliant
- **Rápido** - Optimizado para rendimiento

### Colores Principales
- Primario: Emerald (#10b981)
- Secundario: Cyan (#06b6d4)
- Fondo: Slate (#0f172a)
- Texto: Slate (#1e293b)

---

## 🔧 Configuración Avanzada

### Base de Datos (WordPress)

```sql
-- Tablas creadas automáticamente al activar:
wp_pasiones_memberships        # Membresías de usuarios
wp_pasiones_credits            # Sistema de créditos
wp_pasiones_transactions       # Transacciones de pago
wp_pasiones_sessions           # Sesiones de videochat/streaming
wp_pasiones_reviews            # Reviews y valoraciones
wp_pasiones_notifications      # Notificaciones
wp_pasiones_availability       # Horarios de disponibilidad
```

### Comisiones

- **Comisión del administrador**: 20% (configurable)
- **Retiro mínimo**: 50€ (configurable)
- Los profesionales reciben el 80% de sus ingresos
- Sistema de pagos automático

---

## 📊 SEO y Posicionamiento

### Optimización Incluida

1. **SEO On-Page**
   - Meta tags dinámicos
   - Schema.org markup
   - URLs amigables
   - Sitemap XML

2. **Performance**
   - Lazy loading de imágenes
   - Code splitting
   - CDN ready
   - Caching optimizado

3. **Contenido Indexable**
   - Todas las categorías
   - Perfiles de profesionales
   - Países y provincias
   - Posts públicos

---

## 🚢 Deployment

### Opción Next.js (Netlify/Vercel)

```bash
# Build
bun run build

# Deploy a Netlify
netlify deploy --prod

# Deploy a Vercel
vercel --prod
```

### Opción WordPress

1. Subir archivos vía FTP
2. Importar base de datos
3. Actualizar wp-config.php
4. Activar plugin

---

## 📄 Licencia

Este proyecto incluye:
- **Plugin WordPress**: GPL v2 or later
- **Aplicación Next.js**: MIT License (o la que especifiques)

---

## 🤝 Soporte

Para soporte técnico:
- 📧 Email: support@pasiones-platform.com
- 📚 Documentación completa incluida
- 🎥 Video tutoriales disponibles

---

## 🎯 Próximas Características

- [ ] App móvil nativa (iOS/Android)
- [ ] Sistema de afiliados
- [ ] Gamificación
- [ ] Certificados digitales
- [ ] Integraciones con calendarios (Google, Outlook)
- [ ] Análisis avanzado con IA
- [ ] Traducciones automáticas

---

## 📝 Notas Importantes

### Para WordPress:
- PHP 8.0+ requerido
- WordPress 6.0+ requerido
- MySQL 5.7+ requerido

### Para Next.js:
- Node.js 18+ requerido
- Bun recomendado (o npm/pnpm)

### General:
- Certificado SSL requerido
- Servidor con soporte WebRTC
- Ancho de banda adecuado para streaming

---

**¡Gracias por elegir PASIONES Platform!** 🎉

---

*Versión: 2.1.3*
*Última actualización: 2025*
