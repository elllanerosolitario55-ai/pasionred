# 🚀 QUICK START - VPS DEPLOYMENT

## 📍 ESTÁS AQUÍ

Tu código ya está en el VPS en:
```
/home/redsocial.novapasion.com/app
```

## ✅ LO QUE YA TIENES

- ✅ AlmaLinux 9.5
- ✅ CyberPanel instalado
- ✅ LiteSpeed Web Server
- ✅ Node.js v22
- ✅ MySQL + phpMyAdmin
- ✅ Dominio: redsocial.novapasion.com
- ✅ Código completo clonado desde GitHub

## 🎯 PRÓXIMOS 3 PASOS (5 MINUTOS)

### 1️⃣ CREAR BASE DE DATOS (2 minutos)

**Opción A: Terminal**
```bash
mysql -u root -p

CREATE DATABASE pasiones_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'pasiones_user'@'localhost' IDENTIFIED BY 'PasswordSeguro123!';
GRANT ALL PRIVILEGES ON pasiones_platform.* TO 'pasiones_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Opción B: phpMyAdmin** (más fácil)
- Acceder desde CyberPanel
- Crear database: `pasiones_platform`
- Crear usuario: `pasiones_user` con password seguro
- Dar privilegios completos

---

### 2️⃣ CONFIGURAR .ENV (2 minutos)

```bash
cd /home/redsocial.novapasion.com/app
nano .env
```

**Editar solo estas 3 líneas:**

```env
# 1. Tu base de datos (cambiar PASSWORD)
DATABASE_URL="mysql://pasiones_user:TU_PASSWORD@localhost:3306/pasiones_platform"

# 2. Generar secret nuevo (ejecutar: openssl rand -base64 32)
NEXTAUTH_SECRET="PEGAR_RESULTADO_AQUI"

# 3. Las demás se pueden dejar por ahora o configurar después
```

**Generar secret:**
```bash
openssl rand -base64 32
```

**Guardar:** `Ctrl+O`, `Enter`, `Ctrl+X`

---

### 3️⃣ EJECUTAR DEPLOYMENT AUTOMÁTICO (1 minuto)

```bash
cd /home/redsocial.novapasion.com/app
chmod +x deploy-vps.sh
./deploy-vps.sh
```

**El script hará automáticamente:**
- ✅ Instalar dependencias
- ✅ Generar Prisma Client
- ✅ Ejecutar migraciones
- ✅ Build de producción
- ✅ Configurar PM2
- ✅ Iniciar aplicación

---

## ✅ VERIFICACIÓN

```bash
# Ver si está corriendo
pm2 status

# Ver logs
pm2 logs pasiones-platform

# Probar localmente
curl http://localhost:3000
```

## 🌐 ACCEDER

Tu app estará en:
- **Local:** http://localhost:3000
- **Dominio:** https://redsocial.novapasion.com (después de configurar SSL)

---

## 📋 CONFIGURACIÓN ADICIONAL (OPCIONAL - 10 MINUTOS)

### 4️⃣ Configurar Proxy Reverso

**Desde CyberPanel:**
1. Websites → redsocial.novapasion.com → Rewrite Rules
2. Pegar:
```apache
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/\.well-known/
RewriteRule ^(.*)$ http://localhost:3000/$1 [P,L]
```

### 5️⃣ Configurar SSL

**Desde CyberPanel:**
1. Websites → redsocial.novapasion.com
2. Issue SSL → Let's Encrypt → Issue Now

### 6️⃣ Abrir Firewall

```bash
sudo firewall-cmd --permanent --add-port=3000/tcp
sudo firewall-cmd --reload
```

---

## 🎉 ¡LISTO!

Ahora tu aplicación está corriendo en producción.

## 📞 ¿NECESITAS AYUDA?

Lee la documentación completa en:
- `.same/DEPLOYMENT-STEPS.md` - Paso a paso detallado
- `.same/DEPLOYMENT-GUIDE.md` - Guía completa
- `.same/todos.md` - Lista de tareas

## 🔧 COMANDOS ÚTILES

```bash
pm2 status                    # Ver estado
pm2 logs pasiones-platform    # Ver logs
pm2 restart pasiones-platform # Reiniciar
pm2 monit                     # Monitor
```

---

## 🚨 IMPORTANTE: CONFIGURAR SERVICIOS

Después del deployment, necesitas configurar en `.env`:

1. **Cloudinary** (uploads de imágenes)
   - Registrarse en: https://cloudinary.com
   - Obtener: Cloud Name, API Key, API Secret

2. **Stripe** (pagos)
   - Dashboard: https://dashboard.stripe.com
   - Obtener: Publishable Key, Secret Key

3. **PayPal** (pagos alternativos)
   - Developer: https://developer.paypal.com
   - Obtener: Client ID, Secret

**Sin estos servicios**, algunas funcionalidades no funcionarán:
- ❌ Upload de imágenes
- ❌ Pagos de membresías
- ⚠️ Pero el resto de la app SÍ funcionará

---

**Tiempo total estimado:** 5-15 minutos
**Dificultad:** Fácil

¡Éxito con tu deployment! 🚀
