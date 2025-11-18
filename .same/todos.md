# PASIONES Platform - TODO List

## 🎯 ENFOQUE ACTUAL: VPS Deployment (AlmaLinux + CyberPanel + MySQL) 🚀

### ✅ COMPLETADO: Preparación para Deployment

**Cambios Realizados:**
- [x] Schema de Prisma modificado de PostgreSQL a MySQL
- [x] Archivo .env creado con configuración de producción
- [x] DEPLOYMENT-GUIDE.md actualizado con pasos específicos para VPS
- [x] Script de deployment automatizado (deploy-vps.sh) creado
- [x] Código completo subido a GitHub
- [x] Repositorio clonado en VPS en `/home/redsocial.novapasion.com/app`

---

## 📋 TAREAS DE DEPLOYMENT EN PROGRESO

### 🔧 PASO 1: Configuración de Base de Datos MySQL (PENDIENTE)
- [ ] Crear base de datos `pasiones_platform` desde phpMyAdmin
- [ ] Crear usuario MySQL `pasiones_user` con password seguro
- [ ] Otorgar privilegios ALL PRIVILEGES al usuario
- [ ] Actualizar DATABASE_URL en archivo .env

**Comandos:**
```bash
# Opción A: Desde phpMyAdmin (acceder desde CyberPanel)
# Opción B: Desde terminal
mysql -u root -p
CREATE DATABASE pasiones_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'pasiones_user'@'localhost' IDENTIFIED BY 'PASSWORD_SEGURO';
GRANT ALL PRIVILEGES ON pasiones_platform.* TO 'pasiones_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

### ⚙️ PASO 2: Configurar Variables de Entorno (PENDIENTE)

**Archivo:** `/home/redsocial.novapasion.com/app/.env`

- [ ] Actualizar DATABASE_URL con credenciales MySQL reales
- [ ] Generar NEXTAUTH_SECRET: `openssl rand -base64 32`
- [ ] Configurar Stripe (https://dashboard.stripe.com):
  - [ ] NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY
  - [ ] STRIPE_SECRET_KEY
  - [ ] STRIPE_WEBHOOK_SECRET
- [ ] Configurar PayPal (https://developer.paypal.com):
  - [ ] NEXT_PUBLIC_PAYPAL_CLIENT_ID
  - [ ] PAYPAL_SECRET
- [ ] Configurar Cloudinary (https://cloudinary.com):
  - [ ] NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME
  - [ ] CLOUDINARY_API_KEY
  - [ ] CLOUDINARY_API_SECRET

**Comando para generar secret:**
```bash
openssl rand -base64 32
```

---

### 📦 PASO 3: Instalar Dependencias (PENDIENTE)
- [ ] Ejecutar `npm install` o `bun install`
- [ ] Verificar que no hay errores de dependencias

**Comandos:**
```bash
cd /home/redsocial.novapasion.com/app
npm install
# o
bun install
```

---

### 🗄️ PASO 4: Prisma y Migraciones (PENDIENTE)
- [ ] Generar Prisma Client: `npx prisma generate`
- [ ] Ejecutar migraciones: `npx prisma migrate deploy`
- [ ] (Opcional) Seed inicial: `npx prisma db seed`
- [ ] Verificar tablas creadas en phpMyAdmin

**Comandos:**
```bash
npx prisma generate
npx prisma migrate deploy
npx prisma db seed  # Opcional
```

---

### 🏗️ PASO 5: Build de Producción (PENDIENTE)
- [ ] Ejecutar build: `npm run build`
- [ ] Verificar que el build se completa sin errores
- [ ] Verificar carpeta `.next` generada

**Comando:**
```bash
npm run build
```

---

### 🚀 PASO 6: PM2 Setup (PENDIENTE)
- [ ] Crear directorio de logs
- [ ] Iniciar app con PM2
- [ ] Guardar configuración PM2
- [ ] Configurar PM2 startup
- [ ] Verificar que app está corriendo

**Comandos:**
```bash
mkdir -p /home/redsocial.novapasion.com/logs
pm2 start ecosystem.config.js
pm2 save
pm2 startup
pm2 status
```

---

### 🔧 PASO 7: Configurar LiteSpeed Proxy (PENDIENTE)
- [ ] Acceder a CyberPanel (https://IP:8090)
- [ ] Ir a Websites → List Websites
- [ ] Seleccionar `redsocial.novapasion.com`
- [ ] Configurar Rewrite Rules o editar .htaccess
- [ ] Agregar regla de proxy a localhost:3000

**Regla de proxy:**
```apache
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/\.well-known/
RewriteRule ^(.*)$ http://localhost:3000/$1 [P,L]
```

---

### 🔒 PASO 8: Configurar SSL (PENDIENTE)
- [ ] Ir a CyberPanel → Websites
- [ ] Seleccionar dominio
- [ ] Click en "Issue SSL"
- [ ] Seleccionar Let's Encrypt
- [ ] Verificar SSL activo

---

### 🔥 PASO 9: Firewall y Seguridad (PENDIENTE)
- [ ] Abrir puerto 3000 (interno): `firewall-cmd --add-port=3000/tcp`
- [ ] Reload firewall
- [ ] Verificar puertos abiertos

**Comandos:**
```bash
sudo firewall-cmd --permanent --add-port=3000/tcp
sudo firewall-cmd --reload
sudo firewall-cmd --list-ports
```

---

### ✅ PASO 10: Verificación Final (PENDIENTE)
- [ ] Verificar app corriendo: `pm2 status`
- [ ] Verificar localhost: `curl http://localhost:3000`
- [ ] Verificar dominio: `curl https://redsocial.novapasion.com`
- [ ] Verificar SSL válido
- [ ] Ver logs: `pm2 logs pasiones-platform`
- [ ] Probar registro de usuario
- [ ] Probar login
- [ ] Probar creación de profesional

---

## 🛠️ SCRIPT DE DEPLOYMENT AUTOMATIZADO

**Uso del script:**
```bash
cd /home/redsocial.novapasion.com/app
chmod +x deploy-vps.sh
./deploy-vps.sh
```

El script ejecuta automáticamente:
1. Verificación de directorio
2. Verificación de .env
3. Instalación de dependencias
4. Generación de Prisma Client
5. Migraciones (con confirmación)
6. Build de producción
7. Creación de logs directory
8. Configuración de PM2
9. Inicio/reinicio de app
10. Verificación final

---

## 📊 ESTADO GENERAL DEL PROYECTO

### WordPress Implementation: 99% ✅
- **Plugin:** 98% ✅
- **Theme:** 100% ✅
- **Documentación:** 100% ✅

### Next.js App: 95% ✅
- **Código:** 100% ✅
- **Deployment:** 10% 🔄 (En progreso)

### VPS Deployment: 10% 🔄
- **Preparación:** 100% ✅
- **Configuración:** 0% ⏳
- **Build:** 0% ⏳
- **Deploy:** 0% ⏳
- **Testing:** 0% ⏳

---

## 🎯 PRIORIDADES INMEDIATAS

1. **URGENTE:** Configurar base de datos MySQL
2. **URGENTE:** Actualizar variables de entorno (.env)
3. **ALTA:** Instalar dependencias
4. **ALTA:** Ejecutar migraciones Prisma
5. **ALTA:** Build y deploy con PM2

---

## 📝 NOTAS IMPORTANTES

### Credenciales Necesarias:
- ✅ MySQL: usuario, password, base de datos
- ⏳ Stripe: API keys (test o live)
- ⏳ PayPal: Client ID y Secret
- ⏳ Cloudinary: Cloud name, API key, API secret
- ✅ NEXTAUTH_SECRET: generar con openssl

### Verificaciones Post-Deployment:
- [ ] App accesible en https://redsocial.novapasion.com
- [ ] SSL válido y funcionando
- [ ] Base de datos conectada
- [ ] Registro de usuarios funcional
- [ ] Login funcional
- [ ] Upload de imágenes (Cloudinary)
- [ ] Pagos con Stripe
- [ ] Chat en tiempo real
- [ ] Videochat WebRTC
- [ ] Notificaciones

---

## 🔄 COMANDOS ÚTILES DE MANTENIMIENTO

### PM2:
```bash
pm2 status                    # Ver estado
pm2 logs pasiones-platform    # Ver logs
pm2 restart pasiones-platform # Reiniciar
pm2 stop pasiones-platform    # Detener
pm2 monit                     # Monitor en tiempo real
```

### Actualizar código:
```bash
cd /home/redsocial.novapasion.com/app
git pull origin main
npm install
npm run build
pm2 restart pasiones-platform
```

### Base de datos:
```bash
npx prisma studio              # Ver DB en navegador
npx prisma migrate deploy      # Deploy migraciones
npx prisma generate            # Regenerar client
```

---

## 🎉 PRÓXIMOS PASOS (POST-DEPLOYMENT)

1. [ ] Crear usuarios administradores
2. [ ] Crear categorías profesionales
3. [ ] Crear países y provincias
4. [ ] Crear profesionales de prueba
5. [ ] Testing exhaustivo de todas las features
6. [ ] Configurar backups automáticos de DB
7. [ ] Configurar monitoreo (PM2 Plus opcional)
8. [ ] Optimización de performance
9. [ ] SEO y Analytics
10. [ ] Marketing y lanzamiento

---

*Última actualización: Noviembre 2025*
*Estado: Deployment en progreso - Configuración de DB pendiente*
*Próximo paso: Crear base de datos MySQL y configurar .env*
