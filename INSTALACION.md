# 📦 Guía de Instalación - PASIONES Platform

Esta guía te ayudará a instalar y configurar PASIONES Platform en tus tres modalidades disponibles.

---

## 🔌 OPCIÓN 1: Plugin WordPress

### Requisitos Previos

- **PHP**: 8.0 o superior
- **WordPress**: 6.0 o superior
- **MySQL**: 5.7 o superior
- **Memoria PHP**: Mínimo 256MB (recomendado 512MB)
- **Certificado SSL**: Requerido para WebRTC

### Instalación Paso a Paso

#### 1. Subir el Plugin

**Método A: Vía WordPress Admin**
```
1. Ve a WordPress Admin → Plugins → Añadir nuevo
2. Haz clic en "Subir plugin"
3. Selecciona el archivo ZIP del plugin
4. Haz clic en "Instalar ahora"
5. Activa el plugin
```

**Método B: Vía FTP**
```bash
# Copiar la carpeta completa al directorio de plugins
wp-content/
└── plugins/
    └── pasiones-platform/
        ├── pasiones-platform.php
        ├── includes/
        ├── admin/
        ├── public/
        └── templates/
```

#### 2. Activación

1. Ve a **Plugins → Plugins instalados**
2. Busca "Pasiones Platform"
3. Haz clic en **"Activar"**

El plugin creará automáticamente:
- ✅ Todas las tablas de base de datos necesarias
- ✅ Custom Post Types (Profesionales, Posts, Videos, Streams)
- ✅ Taxonomías (Categorías, Países, Provincias)
- ✅ Páginas iniciales con shortcodes
- ✅ Roles de usuario personalizados
- ✅ Opciones de configuración por defecto

#### 3. Configuración Inicial

Ve a **Pasiones → Configuración** y completa:

**General**
- Selecciona tu moneda (EUR, USD, GBP)
- Establece el símbolo de moneda

**Membresías**
- Bronce: 20€/mes (por defecto)
- Plata: 45€/mes (por defecto)
- Oro: 65€/mes (por defecto)

**Pagos - Stripe**
```
1. Crea una cuenta en https://stripe.com
2. Ve a Developers → API keys
3. Copia tu Publishable key: pk_test_...
4. Copia tu Secret key: sk_test_...
5. Pégalos en la configuración
6. Marca "Habilitar Stripe"
```

**Pagos - PayPal**
```
1. Crea una cuenta en https://developer.paypal.com
2. Ve a Dashboard → My Apps & Credentials
3. Crea una nueva app
4. Copia Client ID
5. Copia Secret
6. Selecciona modo (Sandbox para pruebas, Live para producción)
7. Marca "Habilitar PayPal"
```

**Comisiones**
- Comisión del admin: 20% (recomendado)
- Retiro mínimo: 50€

**Videochat**
- Marca "Habilitar Videochat"
- Marca "Habilitar Streaming"
- Costo por defecto: 2.50 €/min

#### 4. Crear Páginas

El plugin crea páginas automáticamente con estos shortcodes:

```php
// Página de inicio
[pasiones_home]

// Listado de profesionales
[pasiones_professionals]

// Categorías
[pasiones_categories]

// Países
[pasiones_countries]

// Perfil de usuario
[pasiones_profile]

// Panel de control del profesional
[pasiones_dashboard]

// Membresías
[pasiones_memberships]

// Videochat (profesional específico)
[pasiones_videochat professional_id="123"]

// Stream (stream específico)
[pasiones_stream stream_id="456"]
```

#### 5. Configurar Permalinks

```
1. Ve a Ajustes → Enlaces permanentes
2. Selecciona "Nombre de la entrada"
3. Guarda los cambios
```

#### 6. Integración con Elementor (Opcional)

Si usas Elementor:

```
1. Instala y activa Elementor
2. Los widgets de Pasiones aparecerán automáticamente
3. Busca "Pasiones" en el panel de widgets
4. Arrastra y suelta los widgets en tus páginas
```

Widgets disponibles:
- ✅ Lista de Profesionales
- ✅ Categorías
- ✅ Profesionales Destacados
- ✅ Formulario de Búsqueda
- ✅ Tarjetas de Membresía

---

## 🚀 OPCIÓN 2: Aplicación Next.js

### Requisitos Previos

- **Node.js**: 18 o superior
- **Bun**: Recomendado (o npm/pnpm)
- **Base de datos**: PostgreSQL, MySQL, o MongoDB

### Instalación Paso a Paso

#### 1. Clonar/Descargar el Proyecto

```bash
# Si tienes el código fuente
cd pasiones-platform
```

#### 2. Instalar Dependencias

```bash
# Con Bun (recomendado)
bun install

# O con npm
npm install

# O con pnpm
pnpm install
```

#### 3. Configurar Variables de Entorno

Crea un archivo `.env.local` en la raíz del proyecto:

```env
# Base de Datos
DATABASE_URL="postgresql://usuario:password@localhost:5432/pasiones"

# Autenticación
NEXTAUTH_SECRET="genera-un-secret-aleatorio-aqui"
NEXTAUTH_URL="http://localhost:3000"

# Stripe
STRIPE_PUBLISHABLE_KEY="pk_test_..."
STRIPE_SECRET_KEY="sk_test_..."

# PayPal
PAYPAL_CLIENT_ID="..."
PAYPAL_SECRET="..."
PAYPAL_MODE="sandbox"

# General
NEXT_PUBLIC_APP_URL="http://localhost:3000"
NEXT_PUBLIC_APP_NAME="Pasiones Platform"

# Opcional: Storage
AWS_ACCESS_KEY_ID="..."
AWS_SECRET_ACCESS_KEY="..."
AWS_REGION="eu-west-1"
AWS_BUCKET_NAME="pasiones-uploads"
```

#### 4. Configurar Base de Datos

Si usas Prisma (recomendado):

```bash
# Generar cliente de Prisma
bunx prisma generate

# Ejecutar migraciones
bunx prisma migrate dev

# Seed inicial (opcional)
bunx prisma db seed
```

#### 5. Iniciar en Desarrollo

```bash
# Con Bun
bun run dev

# O con npm
npm run dev
```

La aplicación estará disponible en: `http://localhost:3000`

#### 6. Build para Producción

```bash
# Construir la aplicación
bun run build

# Iniciar en producción
bun start
```

---

## 🔗 OPCIÓN 3: Sistema Híbrido

Combina WordPress (backend) con Next.js (frontend).

### Arquitectura

```
┌─────────────┐         ┌──────────────┐
│  WordPress  │ ◄─────► │   Next.js    │
│  (Backend)  │  API    │  (Frontend)  │
│  + Admin    │  REST   │   WebRTC     │
└─────────────┘         └──────────────┘
```

### Instalación

#### 1. Instalar WordPress Plugin

Sigue los pasos de la **Opción 1**

#### 2. Habilitar API REST en WordPress

Añade a `wp-config.php`:

```php
// Habilitar CORS para Next.js
define('ALLOW_CORS', true);
```

Añade al tema activo `functions.php`:

```php
// Permitir CORS desde Next.js
add_action('rest_api_init', function() {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function($value) {
        header('Access-Control-Allow-Origin: http://localhost:3000');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        return $value;
    });
});
```

#### 3. Configurar Next.js

En `.env.local`:

```env
# URL de WordPress
WORDPRESS_API_URL="https://tu-wordpress.com/wp-json"
WORDPRESS_USERNAME="admin"
WORDPRESS_APP_PASSWORD="tu-app-password"

# Resto de configuración...
```

#### 4. Crear Application Password en WordPress

```
1. Ve a WordPress Admin → Usuarios → Perfil
2. Scroll hasta "Contraseñas de aplicación"
3. Crea una nueva contraseña
4. Copia y usa en WORDPRESS_APP_PASSWORD
```

#### 5. Verificar Conexión

```bash
# Test de conexión API
curl https://tu-wordpress.com/wp-json/pasiones/v1/professionals
```

---

## ✅ Verificación de Instalación

### WordPress Plugin

```php
// Verifica que el plugin esté activo
// Ve a: Pasiones → Dashboard

// Deberías ver:
✅ Profesionales: 0
✅ Usuarios Totales: [número]
✅ Membresías Activas: 0
✅ Ingresos del Mes: 0.00 €
✅ Sesiones Activas: 0
```

### Next.js

```bash
# Prueba estas URLs:
http://localhost:3000/              # Home
http://localhost:3000/profesionales # Profesionales
http://localhost:3000/membresias    # Membresías
http://localhost:3000/categorias    # Categorías
http://localhost:3000/paises        # Países
```

---

## 🔧 Troubleshooting

### WordPress

**Error: "La tabla no existe"**
```bash
# Desactiva y reactiva el plugin
# Esto recreará las tablas
```

**Error: "No se puede activar el plugin"**
```
- Verifica la versión de PHP (mínimo 8.0)
- Aumenta memory_limit en php.ini a 256M
```

**WebRTC no funciona**
```
- Verifica que tengas certificado SSL (https://)
- WebRTC requiere HTTPS para funcionar
```

### Next.js

**Error: "Cannot find module"**
```bash
# Limpia node_modules e instala de nuevo
rm -rf node_modules
bun install
```

**Error de base de datos**
```bash
# Verifica tu DATABASE_URL
# Ejecuta migraciones nuevamente
bunx prisma migrate reset
```

**Puerto 3000 en uso**
```bash
# Usa otro puerto
bun run dev -- -p 3001
```

---

## 📱 Configuración SSL

Para WebRTC necesitas HTTPS:

### Desarrollo Local

**Opción A: mkcert**
```bash
# Instalar mkcert
brew install mkcert  # macOS
# o
choco install mkcert # Windows

# Crear certificados
mkcert -install
mkcert localhost

# Usar en Next.js
# package.json: "dev": "next dev --experimental-https"
```

**Opción B: ngrok**
```bash
# Instalar ngrok
npm install -g ngrok

# Exponer puerto 3000
ngrok http 3000

# Usar la URL https:// que te da ngrok
```

### Producción

- Usa Netlify/Vercel (SSL automático)
- O configura Let's Encrypt en tu servidor

---

## 🎉 Siguiente Paso

Una vez instalado:

1. **Crea categorías** de profesionales
2. **Configura países** y provincias
3. **Crea tu primer profesional** de prueba
4. **Prueba el videochat** (requiere HTTPS)
5. **Configura las membresías** según tus necesidades

---

## 📞 Soporte

¿Problemas con la instalación?

- 📧 Email: support@pasiones-platform.com
- 📚 Documentación: README.md
- 🎥 Video tutoriales: [próximamente]

---

**¡Listo! Tu plataforma PASIONES está instalada y lista para usar! 🚀**
