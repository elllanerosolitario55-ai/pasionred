# 🚀 Configuración de Upload de Archivos y Pagos

## 📋 Resumen

Esta guía te ayudará a configurar:
1. **Cloudinary** - Para upload de imágenes, videos y archivos
2. **Stripe** - Para pagos con tarjeta
3. **PayPal** - Para pagos con PayPal

---

## 📁 1. Cloudinary - Upload de Archivos

### ¿Por qué Cloudinary?

✅ **Ventajas:**
- Transformaciones automáticas de imágenes
- Optimización y compresión automática
- CDN global incluido
- Almacenamiento gratuito hasta 25GB
- Fácil integración
- Soporte para imágenes, videos y audio

### Paso 1: Crear Cuenta en Cloudinary

1. Ve a [cloudinary.com](https://cloudinary.com)
2. Haz clic en **Sign Up Free**
3. Completa el registro con tu email
4. Verifica tu email

### Paso 2: Obtener Credenciales

1. Inicia sesión en Cloudinary
2. Ve a **Dashboard**
3. Copia las siguientes credenciales:
   ```
   Cloud Name: xxxxxxxxxxx
   API Key: 123456789012345
   API Secret: xxxxxxxxxxxxxxxxxxxx
   ```

### Paso 3: Configurar Variables de Entorno

En tu archivo `.env.local`:

```env
NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME="tu-cloud-name"
CLOUDINARY_API_KEY="123456789012345"
CLOUDINARY_API_SECRET="xxxxxxxxxxxxxxxxxxxx"
```

### Paso 4: Estructura de Carpetas en Cloudinary

El sistema creará automáticamente estas carpetas:

```
pasiones/
├── avatars/          # Fotos de perfil
├── covers/           # Imágenes de portada
├── posts/            # Imágenes de publicaciones
├── videos/           # Videos
├── audio/            # Archivos de audio
└── temp/             # Archivos temporales
```

### Paso 5: Configuración Recomendada

En Cloudinary Dashboard:

1. **Settings → Upload**
   - Max file size: 10MB (imágenes), 100MB (videos)
   - Allowed formats: jpg, png, gif, webp, mp4, mov

2. **Settings → Security**
   - Enable signed URLs
   - Set upload presets (opcional)

---

## 💳 2. Stripe - Pagos con Tarjeta

### ¿Por qué Stripe?

✅ **Ventajas:**
- Aceptación global
- Interfaz moderna y fácil
- Pagos recurrentes (suscripciones)
- Seguridad PCI compliant
- Testing fácil con modo sandbox
- Comisiones: 1.5% + 0.25€ por transacción

### Paso 1: Crear Cuenta en Stripe

1. Ve a [stripe.com](https://stripe.com)
2. Haz clic en **Sign up**
3. Completa el registro
4. Verifica tu email

### Paso 2: Obtener API Keys (Modo Test)

1. Ve a **Developers → API keys**
2. Copia las claves de TEST:
   ```
   Publishable key: pk_test_xxxxxxxxxxxxx
   Secret key: sk_test_xxxxxxxxxxxxx
   ```

### Paso 3: Configurar Variables de Entorno

En tu archivo `.env.local`:

```env
NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY="pk_test_xxxxxxxxxxxxx"
STRIPE_SECRET_KEY="sk_test_xxxxxxxxxxxxx"
STRIPE_WEBHOOK_SECRET="whsec_xxxxxxxxxxxxx" # (opcional, para webhooks)
```

### Paso 4: Crear Productos (Membresías)

En Stripe Dashboard:

1. **Products → Add product**

**Membresía Bronce:**
```
Name: Membresía Bronce
Price: 20€/mes
ID: price_xxxxxxxxxxxxx
```

**Membresía Plata:**
```
Name: Membresía Plata
Price: 45€/mes
ID: price_xxxxxxxxxxxxx
```

**Membresía Oro:**
```
Name: Membresía Oro
Price: 65€/mes
ID: price_xxxxxxxxxxxxx
```

### Paso 5: Tarjetas de Prueba

Para testing en modo sandbox:

```
✅ Pago exitoso:
Número: 4242 4242 4242 4242
Fecha: Cualquier fecha futura
CVV: Cualquier 3 dígitos
ZIP: Cualquier código

✅ Pago con 3D Secure:
Número: 4000 0025 0000 3155

❌ Pago rechazado:
Número: 4000 0000 0000 0002

❌ Tarjeta expirada:
Número: 4000 0000 0000 0069
```

### Paso 6: Webhooks (Opcional pero Recomendado)

Para recibir notificaciones de pagos:

1. **Developers → Webhooks**
2. Add endpoint: `https://tu-dominio.com/api/webhooks/stripe`
3. Select events:
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
   - `customer.subscription.created`
   - `customer.subscription.deleted`
4. Copia el webhook secret

---

## 💰 3. PayPal - Pagos con PayPal

### ¿Por qué PayPal?

✅ **Ventajas:**
- Muy popular en España y Latinoamérica
- No requiere compartir datos bancarios
- Protección al comprador
- Fácil para usuarios sin tarjeta
- Comisiones: 3.4% + 0.35€ por transacción

### Paso 1: Crear Cuenta de Developer

1. Ve a [developer.paypal.com](https://developer.paypal.com)
2. Inicia sesión con tu cuenta PayPal (o crea una)
3. Ve a **Dashboard**

### Paso 2: Crear App en Sandbox

1. **Apps & Credentials → Create App**
2. Nombre: "Pasiones Platform"
3. Tipo: Merchant
4. Sandbox: Sí

### Paso 3: Obtener Credenciales

En tu app recién creada:

```
Client ID: xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
Secret: xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### Paso 4: Configurar Variables de Entorno

En tu archivo `.env.local`:

```env
NEXT_PUBLIC_PAYPAL_CLIENT_ID="xxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
PAYPAL_SECRET="xxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
PAYPAL_MODE="sandbox"
```

### Paso 5: Cuentas de Prueba

PayPal crea automáticamente cuentas de prueba:

1. **Sandbox → Accounts**
2. Verás 2 cuentas:
   - **Business (Merchant)**: Para recibir pagos
   - **Personal (Buyer)**: Para hacer pagos de prueba

**Ejemplo de cuenta buyer:**
```
Email: sb-buyer123@personal.example.com
Password: xxxxxxxxx
```

### Paso 6: Modo Producción (Cuando estés listo)

1. Completa el onboarding de PayPal
2. Verifica tu identidad
3. Ve a **Apps & Credentials → Live**
4. Copia las credenciales LIVE
5. Cambia en `.env.local`:
   ```env
   PAYPAL_MODE="live"
   ```

---

## 🧪 Testing del Sistema Completo

### 1. Test de Upload de Archivos

```typescript
// Probar en: /panel o cualquier página con FileUpload

1. Seleccionar una imagen (max 10MB)
2. Verificar preview
3. Click en "Subir"
4. Verificar URL de Cloudinary en consola
5. Abrir URL en nueva pestaña
```

**Resultado esperado:**
- ✅ Preview se muestra correctamente
- ✅ Barra de progreso funciona
- ✅ URL de Cloudinary se genera
- ✅ Imagen se ve en Cloudinary Dashboard

### 2. Test de Pago con Stripe

```typescript
// Probar en: /membresias

1. Click en "Obtener Membresía Bronce"
2. Seleccionar método: Tarjeta
3. Ingresar: 4242 4242 4242 4242
4. Fecha: 12/25, CVV: 123
5. Click en "Pagar 20.00€"
```

**Resultado esperado:**
- ✅ Modal de pago se abre
- ✅ Formulario Stripe se carga
- ✅ Pago se procesa
- ✅ Success message se muestra
- ✅ Transacción aparece en Stripe Dashboard

### 3. Test de Pago con PayPal

```typescript
// Probar en: /membresias

1. Click en "Obtener Membresía Plata"
2. Seleccionar método: PayPal
3. Click en botón PayPal
4. Iniciar sesión con cuenta sandbox
5. Aprobar pago
```

**Resultado esperado:**
- ✅ Modal de pago se abre
- ✅ Botones PayPal se cargan
- ✅ Popup de PayPal se abre
- ✅ Pago se completa
- ✅ Success message se muestra

---

## 🔒 Seguridad

### Variables de Entorno

⚠️ **NUNCA subas a Git:**
- `.env.local`
- `.env.production`

✅ **Sí puedes subir:**
- `.env.example` (sin valores reales)

### Verificaciones de Seguridad

```typescript
// En producción, SIEMPRE verificar:

1. Payment Intent ID en servidor
2. Monto correcto
3. Usuario autenticado
4. Firma de webhook (Stripe)
5. Orden ID válida (PayPal)
```

---

## 📊 Monitoreo y Analytics

### Cloudinary

Dashboard muestra:
- Espacio usado / Total
- Transformaciones realizadas
- Ancho de banda
- Uploads por mes

### Stripe

Dashboard muestra:
- Volumen de transacciones
- Tasa de éxito/rechazo
- Ingresos netos
- Próximos pagos recurrentes

### PayPal

Dashboard muestra:
- Transacciones completadas
- Balance disponible
- Retiros pendientes

---

## 🚨 Troubleshooting

### Error: "Cloudinary credentials not found"

**Solución:**
```bash
# Verificar que .env.local tiene:
NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME="..."
CLOUDINARY_API_KEY="..."
CLOUDINARY_API_SECRET="..."

# Reiniciar servidor:
bun run dev
```

### Error: "Stripe publishable key not set"

**Solución:**
```bash
# Verificar que tiene el prefijo NEXT_PUBLIC:
NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY="pk_test_..."

# NO funciona:
STRIPE_PUBLISHABLE_KEY="pk_test_..."
```

### Error: "PayPal script failed to load"

**Solución:**
1. Verificar conexión a internet
2. Verificar que NEXT_PUBLIC_PAYPAL_CLIENT_ID está configurado
3. Limpiar cache del navegador
4. Verificar que no hay adblockers bloqueando PayPal

### Error: "File too large"

**Solución:**
```typescript
// Ajustar límites en cloudinary.ts:

export const MAX_FILE_SIZES = {
  image: 10 * 1024 * 1024,  // 10MB
  video: 100 * 1024 * 1024, // 100MB
  audio: 20 * 1024 * 1024,  // 20MB
};
```

---

## 📱 Uso en Producción

### Checklist Pre-Producción

- [ ] Cambiar Stripe a modo LIVE
- [ ] Cambiar PayPal a modo LIVE
- [ ] Configurar dominio en Cloudinary
- [ ] Setup webhooks de Stripe
- [ ] Setup webhooks de PayPal
- [ ] Configurar políticas de upload en Cloudinary
- [ ] Revisar comisiones y precios
- [ ] Probar con tarjeta real (monto pequeño)
- [ ] Configurar emails de confirmación
- [ ] Documentar proceso de retiros

---

## 💡 Tips y Mejores Prácticas

### Cloudinary

✅ **Hacer:**
- Usar transformaciones automáticas
- Comprimir imágenes antes de mostrar
- Lazy load de imágenes
- Usar formato WebP cuando sea posible

❌ **Evitar:**
- Subir archivos muy grandes sin compresión
- Almacenar archivos temporales indefinidamente
- Usar URLs sin transformaciones

### Stripe

✅ **Hacer:**
- Siempre verificar monto en servidor
- Usar Payment Intents (no Charges)
- Implementar webhooks
- Guardar Customer ID para pagos futuros

❌ **Evitar:**
- Procesar pagos solo en cliente
- Ignorar webhooks
- Hardcodear montos en frontend

### PayPal

✅ **Hacer:**
- Verificar orden en servidor antes de capturar
- Manejar cancelaciones
- Implementar timeout de 3 horas
- Guardar Order ID en BD

❌ **Evitar:**
- Confiar solo en respuesta del cliente
- No implementar onCancel
- Procesar pagos sin verificación

---

## 📞 Soporte

### Cloudinary
- Docs: https://cloudinary.com/documentation
- Support: https://support.cloudinary.com

### Stripe
- Docs: https://stripe.com/docs
- Support: https://support.stripe.com

### PayPal
- Docs: https://developer.paypal.com/docs
- Support: https://developer.paypal.com/support

---

**✅ SISTEMA COMPLETAMENTE CONFIGURADO**

*Última actualización: Noviembre 2025*
*Versión: 1.0*
