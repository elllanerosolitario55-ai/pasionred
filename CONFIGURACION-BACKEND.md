# 🚀 Guía de Configuración del Backend

Esta guía te ayudará a configurar Prisma, NextAuth, Socket.io y las pasarelas de pago.

---

## 📋 Requisitos Previos

- Node.js 18+
- PostgreSQL instalado y corriendo
- Cuenta de Stripe (modo test)
- Cuenta de PayPal Developer (sandbox)

---

## 1️⃣ Configurar Base de Datos

### Instalar PostgreSQL

**macOS:**
```bash
brew install postgresql@15
brew services start postgresql
```

**Ubuntu:**
```bash
sudo apt update
sudo apt install postgresql postgresql-contrib
sudo systemctl start postgresql
```

**Windows:**
Descargar desde https://www.postgresql.org/download/windows/

### Crear Base de Datos

```bash
# Conectar a PostgreSQL
psql postgres

# Crear base de datos
CREATE DATABASE pasiones;

# Crear usuario
CREATE USER pasiones_user WITH PASSWORD 'tu_password';

# Dar permisos
GRANT ALL PRIVILEGES ON DATABASE pasiones TO pasiones_user;

# Salir
\q
```

### Configurar Variables de Entorno

Copia `.env.example` a `.env.local`:

```bash
cp .env.example .env.local
```

Edita `.env.local`:

```env
# Database
DATABASE_URL="postgresql://pasiones_user:tu_password@localhost:5432/pasiones"

# NextAuth
NEXTAUTH_URL="http://localhost:3000"
NEXTAUTH_SECRET="<genera con: openssl rand -base64 32>"

# Stripe (obtener de https://dashboard.stripe.com/test/apikeys)
STRIPE_PUBLISHABLE_KEY="pk_test_..."
STRIPE_SECRET_KEY="sk_test_..."

# PayPal (obtener de https://developer.paypal.com)
PAYPAL_CLIENT_ID="..."
PAYPAL_SECRET="..."
PAYPAL_MODE="sandbox"

# App
NEXT_PUBLIC_APP_URL="http://localhost:3000"
NEXT_PUBLIC_SOCKET_URL="http://localhost:3001"
```

---

## 2️⃣ Configurar Prisma

### Generar Cliente de Prisma

```bash
bunx prisma generate
```

### Crear Migración Inicial

```bash
bunx prisma migrate dev --name init
```

Esto creará:
- Todas las tablas en la base de datos
- El cliente de Prisma

### Ejecutar Seed (datos de prueba)

```bash
bunx prisma db seed
```

Esto creará:
- ✅ 3 categorías (Coaches, Psicólogos, Médicos)
- ✅ 2 países con provincias (España, México)
- ✅ 4 usuarios de prueba
- ✅ 2 profesionales con membresías
- ✅ Posts, reviews, créditos

**Credenciales de prueba:**
```
Admin:         admin@pasiones.com / password123
Profesional 1: maria@pasiones.com / password123
Profesional 2: juan@pasiones.com / password123
Cliente:       cliente@pasiones.com / password123
```

### Ver Base de Datos (Prisma Studio)

```bash
bunx prisma studio
```

Abre http://localhost:5555 para ver y editar datos.

---

## 3️⃣ Configurar Stripe

### Crear Cuenta de Prueba

1. Ve a https://dashboard.stripe.com/register
2. Crea tu cuenta
3. Activa el modo TEST (toggle en la esquina superior derecha)

### Obtener API Keys

1. Ve a https://dashboard.stripe.com/test/apikeys
2. Copia:
   - **Publishable key**: `pk_test_...`
   - **Secret key**: `sk_test_...` (click en "Reveal")
3. Pégalos en `.env.local`

### Crear Productos de Membresía

Ejecuta el script para crear productos en Stripe:

```bash
# Crear archivo temporal
cat > create-stripe-products.ts << 'EOF'
import { createMembershipProducts } from './src/lib/stripe';

createMembershipProducts().then(() => {
  console.log('✅ Productos creados en Stripe');
  process.exit(0);
});
EOF

# Ejecutar
bunx tsx create-stripe-products.ts

# Eliminar archivo
rm create-stripe-products.ts
```

Esto creará 3 productos en Stripe:
- Membresía Bronce (20€/mes)
- Membresía Plata (45€/mes)
- Membresía Oro (65€/mes)

**Guardar los Price IDs** que se muestran en consola.

### Probar Pagos (Tarjetas de prueba)

Stripe proporciona tarjetas de prueba:

```
Tarjeta exitosa:
4242 4242 4242 4242
Fecha: Cualquier fecha futura
CVC: Cualquier 3 dígitos
ZIP: Cualquier 5 dígitos

Tarjeta que requiere autenticación:
4000 0025 0000 3155

Tarjeta que falla:
4000 0000 0000 0002
```

---

## 4️⃣ Configurar PayPal

### Crear Cuenta de Desarrollador

1. Ve a https://developer.paypal.com
2. Inicia sesión o crea cuenta
3. Ve a **Dashboard**

### Crear App de Sandbox

1. Ve a **Apps & Credentials**
2. Selecciona **Sandbox**
3. Click en **Create App**
4. Nombre: "Pasiones Platform"
5. Copia las credenciales:
   - **Client ID**: `AZ...`
   - **Secret**: `EL...`
6. Pégalas en `.env.local`

### Crear Cuentas de Prueba

1. Ve a **Sandbox → Accounts**
2. Verás 2 cuentas pre-creadas:
   - Business (vendedor)
   - Personal (comprador)
3. Usa estas credenciales para probar pagos

### Probar Pagos

1. Usa la cuenta **Personal** para simular compras
2. Usuario: `sb-xxxxx@personal.example.com`
3. Password: (ver en detalles de cuenta)

---

## 5️⃣ Configurar Socket.io (Chat en Tiempo Real)

### Crear Servidor Socket.io

El servidor de Socket.io ya está configurado en `src/lib/socket/server.ts`

### Iniciar Servidor

Agrega a `package.json`:

```json
{
  "scripts": {
    "dev": "next dev -H 0.0.0.0",
    "socket": "tsx src/lib/socket/standalone-server.ts",
    "dev:full": "concurrently \"bun run dev\" \"bun run socket\""
  }
}
```

Crea el servidor standalone:

```bash
cat > src/lib/socket/standalone-server.ts << 'EOF'
import { createServer } from 'http';
import { initializeSocketIO } from './server';

const PORT = process.env.SOCKET_PORT || 3001;

const httpServer = createServer();
initializeSocketIO(httpServer);

httpServer.listen(PORT, () => {
  console.log(`🔌 Socket.io corriendo en http://localhost:${PORT}`);
});
EOF
```

### Instalar Concurrently

```bash
bun add -d concurrently tsx
```

### Iniciar Todo

```bash
# Opción 1: Por separado
bun run dev       # Terminal 1
bun run socket    # Terminal 2

# Opción 2: Todo junto
bun run dev:full
```

---

## 6️⃣ Configurar NextAuth

NextAuth ya está configurado con:
- ✅ Credentials provider (email/password)
- ✅ Google OAuth (opcional)
- ✅ Facebook OAuth (opcional)
- ✅ Prisma adapter
- ✅ JWT strategy

### Configurar Google OAuth (Opcional)

1. Ve a https://console.cloud.google.com
2. Crea un proyecto
3. Habilita Google+ API
4. Crea credenciales OAuth 2.0:
   - Authorized redirect URIs: `http://localhost:3000/api/auth/callback/google`
5. Copia Client ID y Client Secret
6. Agrega a `.env.local`:

```env
GOOGLE_CLIENT_ID="..."
GOOGLE_CLIENT_SECRET="..."
```

### Configurar Facebook OAuth (Opcional)

1. Ve a https://developers.facebook.com
2. Crea una app
3. Agrega Facebook Login
4. Redirect URI: `http://localhost:3000/api/auth/callback/facebook`
5. Agrega a `.env.local`:

```env
FACEBOOK_CLIENT_ID="..."
FACEBOOK_CLIENT_SECRET="..."
```

---

## 7️⃣ Probar Todo

### Iniciar Aplicación

```bash
bun run dev:full
```

### Verificar Servicios

✅ **Next.js**: http://localhost:3000
✅ **Socket.io**: http://localhost:3001
✅ **Prisma Studio**: http://localhost:5555 (si lo iniciaste)

### Probar Funcionalidades

1. **Registro/Login**:
   - Ve a http://localhost:3000
   - Click en "Registrarse"
   - Crea una cuenta
   - O usa: `maria@pasiones.com / password123`

2. **Pagos con Stripe**:
   - Ve a Membresías
   - Selecciona un plan
   - Usa tarjeta: `4242 4242 4242 4242`

3. **Pagos con PayPal**:
   - Ve a Membresías
   - Selecciona PayPal
   - Usa cuenta sandbox

4. **Chat en Tiempo Real**:
   - Inicia sesión con 2 usuarios diferentes
   - Ve a Chat
   - Envía mensajes
   - Verifica que llegan en tiempo real

5. **Videochat WebRTC**:
   - Click en "Conectar" con un profesional
   - Permite acceso a cámara/micrófono
   - Verifica que se ve el video local

---

## 8️⃣ Comandos Útiles

```bash
# Base de datos
bunx prisma migrate dev         # Crear migración
bunx prisma migrate reset       # Reset completo
bunx prisma db push            # Push cambios sin migración
bunx prisma studio             # Interfaz visual

# Desarrollo
bun run dev                    # Solo Next.js
bun run socket                 # Solo Socket.io
bun run dev:full              # Ambos

# Producción
bun run build                  # Build Next.js
bun run start                  # Start producción

# Stripe
bunx stripe listen --forward-to localhost:3000/api/webhooks/stripe
```

---

## 9️⃣ Troubleshooting

### Error: "Can't reach database server"
```bash
# Verifica que PostgreSQL esté corriendo
pg_isready

# Reinicia PostgreSQL
brew services restart postgresql  # macOS
sudo systemctl restart postgresql  # Linux
```

### Error: "NEXTAUTH_SECRET not set"
```bash
# Genera un secret
openssl rand -base64 32

# Agrega a .env.local
NEXTAUTH_SECRET="el-secret-generado"
```

### Error: "Socket.io not connecting"
```bash
# Verifica que el servidor Socket.io esté corriendo
# Verifica la URL en .env.local
NEXT_PUBLIC_SOCKET_URL="http://localhost:3001"
```

### Error de Prisma "Migration failed"
```bash
# Reset completo
bunx prisma migrate reset

# Recrear
bunx prisma migrate dev --name init
bunx prisma db seed
```

---

## 🎉 ¡Listo!

Tu backend está completamente configurado con:
- ✅ Base de datos PostgreSQL con Prisma
- ✅ Autenticación con NextAuth
- ✅ Pagos con Stripe y PayPal
- ✅ Chat en tiempo real con Socket.io
- ✅ Datos de prueba
- ✅ Widgets de Elementor para WordPress

**Siguiente paso**: Integrar el frontend con el backend y empezar a desarrollar features! 🚀

---

**¿Problemas?** Consulta la documentación:
- Prisma: https://www.prisma.io/docs
- NextAuth: https://next-auth.js.org
- Stripe: https://stripe.com/docs
- PayPal: https://developer.paypal.com/docs
- Socket.io: https://socket.io/docs
