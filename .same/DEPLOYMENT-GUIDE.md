# 🚀 GUÍA DE DEPLOYMENT - VPS Hostinger (AlmaLinux + CyberPanel)

## 📋 INFORMACIÓN DEL SERVIDOR

- **OS:** AlmaLinux 9.5
- **Panel:** CyberPanel
- **Web Server:** LiteSpeed
- **Node.js:** v22.x
- **Database:** MySQL (con phpMyAdmin)
- **Dominio:** redsocial.novapasion.com
- **App Directory:** `/home/redsocial.novapasion.com/app`

---

## ✅ PASO 1: PREPARACIÓN INICIAL

### 1.1 Verificar Node.js y npm
```bash
node -v    # Debe mostrar v22.x
npm -v     # Debe estar instalado
```

### 1.2 Instalar Bun (Opcional pero recomendado)
```bash
curl -fsSL https://bun.sh/install | bash
source ~/.bashrc
bun -v
```

### 1.3 Instalar PM2 globalmente
```bash
npm install -g pm2
```

---

## 📦 PASO 2: CONFIGURAR BASE DE DATOS MYSQL

### 2.1 Crear Base de Datos desde phpMyAdmin

1. Acceder a phpMyAdmin desde CyberPanel
2. Crear nueva base de datos: `pasiones_platform`
3. Crear usuario MySQL:
   - Usuario: `pasiones_user`
   - Password: (generar seguro)
   - Host: `localhost`
   - Privilegios: ALL PRIVILEGES

### 2.2 Alternativa: Crear desde terminal
```bash
mysql -u root -p

CREATE DATABASE pasiones_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'pasiones_user'@'localhost' IDENTIFIED BY 'TU_PASSWORD_SEGURO';
GRANT ALL PRIVILEGES ON pasiones_platform.* TO 'pasiones_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## ⚙️ PASO 3: CONFIGURAR VARIABLES DE ENTORNO

### 3.1 Editar archivo .env
```bash
cd /home/redsocial.novapasion.com/app
nano .env
```

### 3.2 Configurar .env (IMPORTANTE)
```env
# Database - ACTUALIZAR CON TUS DATOS
DATABASE_URL="mysql://pasiones_user:TU_PASSWORD@localhost:3306/pasiones_platform"

# NextAuth - GENERAR SECRET NUEVO
NEXTAUTH_URL="https://redsocial.novapasion.com"
NEXTAUTH_SECRET="EJECUTAR: openssl rand -base64 32"

# Stripe (obtener de https://dashboard.stripe.com)
NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY="pk_test_..."
STRIPE_SECRET_KEY="sk_test_..."
STRIPE_WEBHOOK_SECRET="whsec_..."

# PayPal (obtener de https://developer.paypal.com)
NEXT_PUBLIC_PAYPAL_CLIENT_ID="..."
PAYPAL_SECRET="..."
PAYPAL_MODE="sandbox"

# Cloudinary (obtener de https://cloudinary.com)
NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME="..."
CLOUDINARY_API_KEY="..."
CLOUDINARY_API_SECRET="..."

# App Config
NEXT_PUBLIC_APP_URL="https://redsocial.novapasion.com"
NODE_ENV="production"
```

### 3.3 Generar NEXTAUTH_SECRET
```bash
openssl rand -base64 32
# Copiar el resultado al .env
```

---

## 📥 PASO 4: INSTALAR DEPENDENCIAS

```bash
cd /home/redsocial.novapasion.com/app

# Opción A: Con npm
npm install

# Opción B: Con bun (más rápido)
bun install
```

---

## 🗄️ PASO 5: CONFIGURAR PRISMA Y BASE DE DATOS

### 5.1 Generar Prisma Client
```bash
npx prisma generate
```

### 5.2 Ejecutar Migraciones
```bash
npx prisma migrate deploy
```

### 5.3 (Opcional) Seed de datos iniciales
```bash
npx prisma db seed
```

### 5.4 Verificar conexión a DB
```bash
npx prisma studio
# Abrir en navegador para verificar
```

---

## 🏗️ PASO 6: BUILD DE LA APLICACIÓN

```bash
cd /home/redsocial.novapasion.com/app

# Build de producción
npm run build

# O con bun
bun run build
```

**Nota:** El build puede tardar 5-10 minutos.

---

## 🚀 PASO 7: EJECUTAR CON PM2

### 7.1 Crear archivo de configuración PM2
```bash
nano ecosystem.config.js
```

### 7.2 Contenido de ecosystem.config.js
```javascript
module.exports = {
  apps: [{
    name: 'pasiones-platform',
    script: 'npm',
    args: 'start',
    cwd: '/home/redsocial.novapasion.com/app',
    instances: 1,
    autorestart: true,
    watch: false,
    max_memory_restart: '1G',
    env: {
      NODE_ENV: 'production',
      PORT: 3000
    },
    error_file: '/home/redsocial.novapasion.com/logs/pm2-error.log',
    out_file: '/home/redsocial.novapasion.com/logs/pm2-out.log',
    log_date_format: 'YYYY-MM-DD HH:mm:ss Z'
  }]
}
```

### 7.3 Crear directorio de logs
```bash
mkdir -p /home/redsocial.novapasion.com/logs
```

### 7.4 Iniciar aplicación con PM2
```bash
pm2 start ecosystem.config.js
pm2 save
pm2 startup
```

### 7.5 Verificar que está corriendo
```bash
pm2 status
pm2 logs pasiones-platform
```

---

## 🔧 PASO 8: CONFIGURAR LITESPEED REVERSE PROXY

### 8.1 Acceder a CyberPanel
1. Ir a: `https://TU_IP:8090`
2. Login con credenciales de CyberPanel

### 8.2 Configurar Proxy en CyberPanel
1. Websites → List Websites
2. Seleccionar `redsocial.novapasion.com`
3. Click en "Manage"
4. Ir a "Rewrite Rules"

### 8.3 Agregar regla de proxy
```apache
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/\.well-known/
RewriteRule ^(.*)$ http://localhost:3000/$1 [P,L]
```

### 8.4 Alternativa: Editar .htaccess
```bash
nano /home/redsocial.novapasion.com/public_html/.htaccess
```

```apache
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteCond %{HTTPS} off
  RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

  RewriteCond %{REQUEST_URI} !^/\.well-known/
  RewriteRule ^(.*)$ http://localhost:3000/$1 [P,L]
</IfModule>
```

---

## 🔒 PASO 9: CONFIGURAR SSL

### 9.1 Instalar SSL desde CyberPanel
1. Websites → List Websites
2. Seleccionar `redsocial.novapasion.com`
3. Click en "Issue SSL"
4. Seleccionar "Let's Encrypt"
5. Click en "Issue Now"

### 9.2 Verificar SSL
```bash
curl -I https://redsocial.novapasion.com
```

---

## 🔥 PASO 10: CONFIGURAR FIREWALL

### 10.1 Abrir puerto 3000 (interno)
```bash
sudo firewall-cmd --permanent --add-port=3000/tcp
sudo firewall-cmd --reload
```

### 10.2 Verificar puertos abiertos
```bash
sudo firewall-cmd --list-ports
```

---

## ✅ VERIFICACIÓN FINAL

### 1. Verificar app corriendo
```bash
pm2 status
curl http://localhost:3000
```

### 2. Verificar dominio
```bash
curl https://redsocial.novapasion.com
```

### 3. Verificar logs
```bash
pm2 logs pasiones-platform
tail -f /home/redsocial.novapasion.com/logs/pm2-out.log
```

### 4. Verificar conexión a DB
```bash
cd /home/redsocial.novapasion.com/app
npx prisma studio
```

---

## 🔄 COMANDOS ÚTILES

### PM2
```bash
pm2 restart pasiones-platform   # Reiniciar app
pm2 stop pasiones-platform      # Detener app
pm2 logs pasiones-platform      # Ver logs en tiempo real
pm2 monit                       # Monitor de recursos
pm2 save                        # Guardar configuración
```

### Build y Deploy
```bash
# Actualizar código desde Git
cd /home/redsocial.novapasion.com/app
git pull origin main

# Reinstalar dependencias
npm install

# Regenerar Prisma
npx prisma generate

# Rebuild
npm run build

# Reiniciar PM2
pm2 restart pasiones-platform
```

### Base de Datos
```bash
# Ver DB en navegador
npx prisma studio

# Crear nueva migración
npx prisma migrate dev --name nombre_migracion

# Deploy migraciones
npx prisma migrate deploy

# Reset DB (¡CUIDADO!)
npx prisma migrate reset
```

---

## 🐛 TROUBLESHOOTING

### App no inicia
```bash
# Ver logs de PM2
pm2 logs pasiones-platform --lines 100

# Ver errores
pm2 logs pasiones-platform --err

# Reiniciar app
pm2 restart pasiones-platform
```

### Error de base de datos
```bash
# Verificar conexión MySQL
mysql -u pasiones_user -p pasiones_platform

# Regenerar Prisma
npx prisma generate
npx prisma migrate deploy
```

### Error 502 Bad Gateway
```bash
# Verificar que app está corriendo
pm2 status
curl http://localhost:3000

# Verificar proxy en .htaccess
nano /home/redsocial.novapasion.com/public_html/.htaccess

# Restart LiteSpeed
sudo systemctl restart lsws
```

### Memoria insuficiente
```bash
# Monitorear memoria
pm2 monit

# Ajustar límite de memoria en ecosystem.config.js
max_memory_restart: '1G'
```

---

## 📊 MONITOREO

### 1. PM2 Monitor
```bash
pm2 monit
```

### 2. Logs en tiempo real
```bash
pm2 logs pasiones-platform --lines 200
```

### 3. Verificar performance
```bash
pm2 show pasiones-platform
```

---

## 🎉 ¡DEPLOYMENT COMPLETADO!

Tu aplicación debería estar corriendo en:
- **URL:** https://redsocial.novapasion.com
- **Puerto interno:** 3000
- **Proceso:** PM2 (auto-restart)
- **SSL:** Let's Encrypt
- **DB:** MySQL local

---

## 📞 PRÓXIMOS PASOS

1. ✅ Configurar servicios de terceros (Stripe, PayPal, Cloudinary)
2. ✅ Crear usuarios administradores
3. ✅ Crear categorías y profesionales de prueba
4. ✅ Configurar backups automáticos de DB
5. ✅ Configurar monitoreo (opcional: PM2 Plus)
6. ✅ Testing exhaustivo de todas las funcionalidades

---

*Última actualización: Noviembre 2025*
*Versión: 2.2.0*
