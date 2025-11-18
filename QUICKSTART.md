# ⚡ Quick Start - PASIONES Platform

## 🎯 Opción 1: Solo Ver la UI (SIN configuración)

```bash
# 1. Instalar dependencias
bun install

# 2. Iniciar servidor
bun run dev

# 3. Abrir en navegador
# http://localhost:3000
```

✅ **Funciona:** Toda la UI, navegación, diseño
❌ **No funciona:** Upload, Pagos, Base de datos

---

## 🚀 Opción 2: Configuración Completa (30-45 min)

### 1️⃣ Clonar y Instalar

```bash
# Clonar
git clone <repo-url>
cd pasiones-platform

# Instalar dependencias
bun install
```

### 2️⃣ Configurar Variables de Entorno

```bash
# Copiar ejemplo
cp .env.example .env.local

# Editar .env.local
# Sigue la guía: .same/setup-credentials.md
```

### 3️⃣ Verificar Configuración

```bash
# Verificar credenciales
bun run check:credentials
```

Deberías ver:
```
✅ DATABASE_URL                    (configurado)
✅ NEXTAUTH_URL                    (configurado)
✅ NEXTAUTH_SECRET                 (configurado)
✅ NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME (configurado)
✅ CLOUDINARY_API_KEY              (configurado)
✅ CLOUDINARY_API_SECRET           (configurado)
✅ NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY (configurado)
✅ STRIPE_SECRET_KEY               (configurado)
✅ NEXT_PUBLIC_PAYPAL_CLIENT_ID    (configurado)
✅ PAYPAL_SECRET                   (configurado)
```

### 4️⃣ Setup Base de Datos

```bash
# Generar Prisma Client
bun prisma generate

# Crear tablas
bun prisma migrate deploy

# Poblar con datos de prueba
bun prisma db seed
```

### 5️⃣ Iniciar Aplicación

```bash
# Desarrollo
bun run dev

# Abrir navegador
# http://localhost:3000
```

---

## 📋 Servicios Necesarios (GRATIS)

### 1. Neon (Base de Datos)
- ✅ GRATIS: PostgreSQL en la nube
- 🔗 https://neon.tech
- ⏱️ Setup: 2 minutos

### 2. Cloudinary (Upload)
- ✅ GRATIS: 25 GB storage + bandwidth
- 🔗 https://cloudinary.com
- ⏱️ Setup: 3 minutos

### 3. Stripe (Pagos - Sandbox)
- ✅ GRATIS: Modo test ilimitado
- 🔗 https://stripe.com
- ⏱️ Setup: 5 minutos

### 4. PayPal (Pagos - Sandbox)
- ✅ GRATIS: Cuentas de prueba
- 🔗 https://developer.paypal.com
- ⏱️ Setup: 5 minutos

**Total:** $0 - Todo en planes gratuitos

---

## 🧪 Testing

### Probar Uploads

1. Ve a cualquier página con FileUpload
2. Arrastra una imagen
3. Debe subir a Cloudinary
4. Verifica en: https://cloudinary.com/console

### Probar Stripe

1. Ve a: http://localhost:3000/membresias
2. Click en "Elegir Bronce"
3. Selecciona "Tarjeta"
4. Usa: `4242 4242 4242 4242`
5. Debe procesar el pago
6. Verifica en: https://dashboard.stripe.com/test/payments

### Probar PayPal

1. Ve a: http://localhost:3000/membresias
2. Click en "Elegir Plata"
3. Selecciona "PayPal"
4. Inicia sesión con cuenta sandbox
5. Debe procesar el pago
6. Verifica en: https://developer.paypal.com/dashboard

---

## 📖 Documentación

- **Setup Completo**: `.same/setup-credentials.md`
- **Upload y Pagos**: `.same/setup-upload-payments.md`
- **README**: `README.md`
- **Features**: `FEATURES-IMPLEMENTADAS.md`

---

## 🐛 Problemas Comunes

### "Prisma Client could not connect"

```bash
# Verificar DATABASE_URL en .env.local
# Debe ser una URL válida de PostgreSQL
```

### "Cloudinary not configured"

```bash
# Verificar que las 3 variables estén configuradas:
NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME="..."
CLOUDINARY_API_KEY="..."
CLOUDINARY_API_SECRET="..."
```

### "Stripe publishable key not set"

```bash
# DEBE tener prefijo NEXT_PUBLIC_
NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY="pk_test_..."
```

---

## ✅ Checklist

- [ ] Dependencias instaladas
- [ ] .env.local configurado
- [ ] Credenciales verificadas
- [ ] Base de datos migrada
- [ ] Datos de prueba cargados
- [ ] Servidor corriendo
- [ ] Upload probado
- [ ] Stripe probado
- [ ] PayPal probado

---

## 🎉 ¡Listo!

Si todos los pasos están completos, tienes:

✅ Aplicación corriendo en http://localhost:3000
✅ Base de datos funcionando
✅ Uploads funcionando
✅ Pagos funcionando (sandbox)

**¡A desarrollar!** 🚀

---

*Tiempo total: 30-45 minutos*
*Costo: $0 (todo gratis)*
