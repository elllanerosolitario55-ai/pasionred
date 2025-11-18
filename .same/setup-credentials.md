# 🔑 Guía de Configuración de Credenciales

## 📋 Resumen

Esta guía te ayudará a configurar **TODAS** las credenciales necesarias para que la aplicación funcione completamente.

---

## ⚡ Quick Start (5 minutos)

### Opción 1: Solo Testing de UI (SIN servicios externos)

```bash
# La app funcionará con datos mock
bun run dev
```

✅ **Funciona:**
- Navegación por todas las páginas
- UI completa
- Componentes visuales
- Diseño responsive

❌ **NO funciona:**
- Upload de archivos
- Pagos
- Base de datos

---

### Opción 2: Setup Completo (30-45 minutos)

Sigue esta guía paso a paso para configurar:
1. Base de Datos (Neon - GRATIS)
2. Cloudinary (Upload - GRATIS)
3. Stripe (Pagos - GRATIS sandbox)
4. PayPal (Pagos - GRATIS sandbox)

---

## 🗄️ 1. BASE DE DATOS (PostgreSQL con Neon)

### ¿Por qué Neon?
- ✅ Completamente GRATIS
- ✅ PostgreSQL en la nube
- ✅ Setup en 2 minutos
- ✅ No requiere tarjeta de crédito

### Paso 1: Crear Cuenta

1. Ve a [https://neon.tech](https://neon.tech)
2. Click en **"Sign Up"**
3. Regístrate con:
   - Google (recomendado)
   - GitHub
   - Email

### Paso 2: Crear Proyecto

1. Click en **"Create Project"**
2. Configuración:
   ```
   Project name: pasiones-platform
   PostgreSQL version: 16
   Region: EU Central (Frankfurt) o US East
   ```
3. Click **"Create Project"**

### Paso 3: Obtener Connection String

1. En el dashboard, verás:
   ```
   Connection string
   postgresql://username:password@ep-xxx.eu-central-1.aws.neon.tech/pasiones?sslmode=require
   ```

2. **COPIA ESA URL COMPLETA**

### Paso 4: Configurar en .env.local

```bash
# Crear archivo .env.local
cp .env.example .env.local

# Editar y agregar:
DATABASE_URL="postgresql://username:password@ep-xxx.eu-central-1.aws.neon.tech/pasiones?sslmode=require"
```

### Paso 5: Ejecutar Migraciones

```bash
# Instalar Prisma CLI
bun add -d prisma

# Crear tablas en la base de datos
bun prisma migrate deploy

# O para desarrollo:
bun prisma migrate dev --name init

# Verificar que funciona
bun prisma studio
# Se abre en http://localhost:5555
```

### Paso 6: Poblar con Datos de Prueba

```bash
# Ejecutar seed
bun prisma db seed
```

✅ **Listo! Base de datos configurada**

---

## 📁 2. CLOUDINARY (Upload de Archivos)

### ¿Por qué Cloudinary?
- ✅ 25 GB storage GRATIS
- ✅ 25 GB bandwidth/mes GRATIS
- ✅ Transformaciones automáticas
- ✅ CDN global incluido

### Paso 1: Crear Cuenta

1. Ve a [https://cloudinary.com](https://cloudinary.com)
2. Click en **"Sign Up Free"**
3. Completa el registro:
   ```
   Email: tu-email@example.com
   Contraseña: (mínimo 8 caracteres)
   ```
4. Verifica tu email

### Paso 2: Obtener Credenciales

1. Inicia sesión en Cloudinary
2. Ve al **Dashboard** (aparece automáticamente)
3. Verás:
   ```
   Cloud name: dxxxxxxxxx
   API Key: 123456789012345
   API Secret: abcdefghijklmnopqrstuvwxyz
   ```

### Paso 3: Configurar en .env.local

```env
NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME="dxxxxxxxxx"
CLOUDINARY_API_KEY="123456789012345"
CLOUDINARY_API_SECRET="abcdefghijklmnopqrstuvwxyz"
```

### Paso 4: Probar Upload

1. Inicia la app:
   ```bash
   bun run dev
   ```

2. Ve a cualquier página con FileUpload

3. Sube una imagen de prueba

4. Verifica en Cloudinary Dashboard → Media Library

✅ **Listo! Uploads funcionando**

---

## 💳 3. STRIPE (Pagos con Tarjeta)

### ¿Por qué Stripe?
- ✅ Modo TEST completamente gratis
- ✅ Tarjetas de prueba ilimitadas
- ✅ No requiere tarjeta real
- ✅ Dashboard profesional

### Paso 1: Crear Cuenta

1. Ve a [https://stripe.com](https://stripe.com)
2. Click en **"Sign up"**
3. Completa:
   ```
   Email: tu-email@example.com
   Nombre completo
   País
   ```
4. Verifica tu email

### Paso 2: Activar Modo TEST

1. Inicia sesión en Stripe Dashboard
2. Arriba a la derecha verás un switch:
   ```
   [ ] Viewing test data
   ```
3. **ASEGÚRATE QUE ESTÉ ACTIVADO** (toggle azul)

### Paso 3: Obtener API Keys

1. Ve a **Developers** (menú izquierdo)
2. Click en **API keys**
3. Verás DOS claves:

   **Publishable key (Pública):**
   ```
   pk_test_51xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
   ```

   **Secret key (Secreta):**
   ```
   sk_test_51xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
   ```

4. Click en **"Reveal test key"** para ver la secreta

### Paso 4: Configurar en .env.local

```env
NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY="pk_test_51xxxxx..."
STRIPE_SECRET_KEY="sk_test_51xxxxx..."
```

### Paso 5: Probar Pago

1. Inicia la app:
   ```bash
   bun run dev
   ```

2. Ve a [http://localhost:3000/membresias](http://localhost:3000/membresias)

3. Click en **"Elegir Bronce"** (20€)

4. Selecciona **"Tarjeta de Crédito/Débito"**

5. Ingresa datos de prueba:
   ```
   Número: 4242 4242 4242 4242
   Fecha: 12/25 (cualquier fecha futura)
   CVV: 123 (cualquier 3 dígitos)
   ZIP: 12345 (cualquier código)
   ```

6. Click en **"Pagar 20.00€"**

7. ✅ Deberías ver: **"¡Pago Completado!"**

8. Verifica en Stripe Dashboard → Payments

### Tarjetas de Prueba Adicionales

```
✅ Pago exitoso:
   4242 4242 4242 4242

✅ Pago con 3D Secure (autenticación extra):
   4000 0025 0000 3155

❌ Pago rechazado (fondos insuficientes):
   4000 0000 0000 9995

❌ Tarjeta expirada:
   4000 0000 0000 0069

❌ CVC incorrecto:
   4000 0000 0000 0127
```

✅ **Listo! Pagos con Stripe funcionando**

---

## 💰 4. PAYPAL (Pagos con PayPal)

### ¿Por qué PayPal?
- ✅ Muy popular en España/LATAM
- ✅ Sandbox completamente gratis
- ✅ Cuentas de prueba automáticas
- ✅ No requiere PayPal real

### Paso 1: Crear Cuenta de Developer

1. Ve a [https://developer.paypal.com](https://developer.paypal.com)
2. Click en **"Log in to Dashboard"**
3. Si tienes PayPal personal, usa esas credenciales
4. Si no, crea cuenta nueva (GRATIS)

### Paso 2: Crear App en Sandbox

1. Ve a **Apps & Credentials**
2. Asegúrate que esté en modo **"Sandbox"** (arriba)
3. Click en **"Create App"**
4. Configuración:
   ```
   App Name: Pasiones Platform
   App Type: Merchant
   ```
5. Click **"Create App"**

### Paso 3: Obtener Credenciales

En la página de tu app verás:

```
Client ID:
AxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxZ

Secret:
ExxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxZ
```

### Paso 4: Configurar en .env.local

```env
NEXT_PUBLIC_PAYPAL_CLIENT_ID="Axxxxx...Z"
PAYPAL_SECRET="Exxxxx...Z"
PAYPAL_MODE="sandbox"
```

### Paso 5: Obtener Cuentas de Prueba

1. Ve a **Sandbox** → **Accounts** (menú izquierdo)
2. Verás 2 cuentas creadas automáticamente:

   **Business Account (Recibir pagos):**
   ```
   Email: sb-xxxxx@business.example.com
   Password: xxxxxxxxx
   ```

   **Personal Account (Hacer pagos):**
   ```
   Email: sb-xxxxx@personal.example.com
   Password: xxxxxxxxx
   ```

3. **GUARDA ESTAS CREDENCIALES**

### Paso 6: Probar Pago

1. Inicia la app:
   ```bash
   bun run dev
   ```

2. Ve a [http://localhost:3000/membresias](http://localhost:3000/membresias)

3. Click en **"Elegir Plata"** (45€)

4. Selecciona **"PayPal"**

5. Se abrirá popup de PayPal

6. Inicia sesión con la cuenta **PERSONAL** de sandbox:
   ```
   Email: sb-xxxxx@personal.example.com
   Password: xxxxxxxxx
   ```

7. Revisa el pago y click **"Pay Now"**

8. ✅ Deberías ver: **"¡Pago Completado!"**

9. Verifica en PayPal Developer Dashboard → Sandbox → Transactions

✅ **Listo! Pagos con PayPal funcionando**

---

## 🔐 5. NEXTAUTH (Autenticación)

### Paso 1: Generar Secret

```bash
# Generar secret aleatorio
openssl rand -base64 32
```

### Paso 2: Configurar en .env.local

```env
NEXTAUTH_URL="http://localhost:3000"
NEXTAUTH_SECRET="el-secret-que-generaste-arriba"
```

✅ **Listo! Auth configurado**

---

## ✅ CHECKLIST FINAL

### Verificar que TODO está configurado:

```bash
# Tu .env.local debe tener:
DATABASE_URL="postgresql://..."              ✅
NEXTAUTH_URL="http://localhost:3000"        ✅
NEXTAUTH_SECRET="..."                       ✅
NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME="..."     ✅
CLOUDINARY_API_KEY="..."                    ✅
CLOUDINARY_API_SECRET="..."                 ✅
NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY="pk_test_..." ✅
STRIPE_SECRET_KEY="sk_test_..."             ✅
NEXT_PUBLIC_PAYPAL_CLIENT_ID="..."          ✅
PAYPAL_SECRET="..."                         ✅
PAYPAL_MODE="sandbox"                       ✅
```

### Testing Final:

1. **Base de Datos:**
   ```bash
   bun prisma studio
   # Debe abrir http://localhost:5555
   ```

2. **App:**
   ```bash
   bun run dev
   # Debe abrir http://localhost:3000
   ```

3. **Páginas:**
   - ✅ Home: http://localhost:3000
   - ✅ Membresías: http://localhost:3000/membresias
   - ✅ Profesionales: http://localhost:3000/profesionales
   - ✅ Panel: http://localhost:3000/panel

4. **Upload:**
   - Ve a cualquier página con FileUpload
   - Sube una imagen
   - Verifica en Cloudinary

5. **Stripe:**
   - Ve a /membresias
   - Prueba pago con tarjeta de test
   - Verifica en Stripe Dashboard

6. **PayPal:**
   - Ve a /membresias
   - Prueba pago con PayPal sandbox
   - Verifica en PayPal Developer Dashboard

---

## 🐛 Troubleshooting

### Error: "Prisma Client could not connect"

```bash
# Verificar DATABASE_URL
echo $DATABASE_URL

# Regenerar Prisma Client
bun prisma generate

# Ejecutar migraciones
bun prisma migrate deploy
```

### Error: "Cloudinary not configured"

```bash
# Verificar variables
echo $NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME
echo $CLOUDINARY_API_KEY

# Reiniciar servidor
# Ctrl+C y luego:
bun run dev
```

### Error: "Stripe publishable key not set"

```bash
# IMPORTANTE: Debe tener prefijo NEXT_PUBLIC_
# ❌ INCORRECTO:
STRIPE_PUBLISHABLE_KEY="pk_test_..."

# ✅ CORRECTO:
NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY="pk_test_..."

# Reiniciar servidor después de cambiar
```

### Error: "PayPal buttons not loading"

```bash
# Verificar:
1. NEXT_PUBLIC_PAYPAL_CLIENT_ID está configurado
2. No hay AdBlocker activo
3. Conexión a internet estable
4. Limpiar caché del navegador
```

---

## 📞 Soporte

### Documentación Oficial:

- **Neon**: https://neon.tech/docs
- **Cloudinary**: https://cloudinary.com/documentation
- **Stripe**: https://stripe.com/docs
- **PayPal**: https://developer.paypal.com/docs

### Guías del Proyecto:

- `.same/setup-upload-payments.md` - Guía detallada de uploads y pagos
- `.same/todos.md` - Estado del proyecto
- `README.md` - Documentación general

---

## 🎉 ¡Felicidades!

Si llegaste hasta aquí y todo funciona, tienes:

✅ Base de datos en la nube (Neon)
✅ Upload de archivos (Cloudinary)
✅ Pagos con tarjeta (Stripe)
✅ Pagos con PayPal
✅ Autenticación (NextAuth)

**¡Tu entorno de desarrollo está 100% listo!** 🚀

---

*Última actualización: Noviembre 2025*
*Tiempo estimado de setup: 30-45 minutos*
*Costo: $0 (todo en planes gratuitos)*
