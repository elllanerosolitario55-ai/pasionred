# 🚀 PASOS DE DEPLOYMENT - EJECUTAR EN VPS

## 📍 UBICACIÓN
Estos comandos deben ejecutarse en el VPS vía SSH conectado como root o usuario con privilegios sudo.

---

## ✅ PASO 1: CREAR BASE DE DATOS MYSQL

### Opción A: Desde Terminal (Recomendado)
```bash
# Conectar a MySQL
mysql -u root -p
# Ingresar password de root de MySQL cuando lo solicite
```

```sql
-- Crear base de datos
CREATE DATABASE pasiones_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Crear usuario
CREATE USER 'pasiones_user'@'localhost' IDENTIFIED BY 'TuPasswordSeguro123!';

-- Otorgar privilegios
GRANT ALL PRIVILEGES ON pasiones_platform.* TO 'pasiones_user'@'localhost';

-- Aplicar cambios
FLUSH PRIVILEGES;

-- Verificar
SHOW DATABASES;

-- Salir
EXIT;
```

### Opción B: Desde phpMyAdmin
1. Abrir CyberPanel en navegador
2. Click en "phpMyAdmin"
3. Click en "Databases" → "Create database"
4. Nombre: `pasiones_platform`
5. Collation: `utf8mb4_unicode_ci`
6. Click "Create"
7. Ir a "Users" → "Add user account"
   - Username: `pasiones_user`
   - Password: (generar seguro)
   - Host: `localhost`
   - Grant all privileges on database: `pasiones_platform`

---

## ✅ PASO 2: CONFIGURAR VARIABLES DE ENTORNO

```bash
# Ir al directorio de la aplicación
cd /home/redsocial.novapasion.com/app

# Editar archivo .env
nano .env
```

### Actualizar estas variables:

```env
# 1. DATABASE (ACTUALIZAR con tus credenciales)
DATABASE_URL="mysql://pasiones_user:TuPasswordSeguro123!@localhost:3306/pasiones_platform"

# 2. NEXTAUTH_SECRET (GENERAR NUEVO)
# Ejecutar en otra terminal: openssl rand -base64 32
# Copiar el resultado aquí:
NEXTAUTH_SECRET="EL_RESULTADO_DEL_COMANDO_OPENSSL"

# 3. STRIPE (obtener de https://dashboard.stripe.com/test/apikeys)
NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY="pk_test_TU_KEY_AQUI"
STRIPE_SECRET_KEY="sk_test_TU_KEY_AQUI"
STRIPE_WEBHOOK_SECRET="whsec_TU_KEY_AQUI"

# 4. PAYPAL (obtener de https://developer.paypal.com)
NEXT_PUBLIC_PAYPAL_CLIENT_ID="TU_CLIENT_ID_AQUI"
PAYPAL_SECRET="TU_SECRET_AQUI"

# 5. CLOUDINARY (obtener de https://cloudinary.com/console)
NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME="TU_CLOUD_NAME"
CLOUDINARY_API_KEY="TU_API_KEY"
CLOUDINARY_API_SECRET="TU_API_SECRET"
```

**Guardar archivo:** `Ctrl + O`, `Enter`, `Ctrl + X`

### Generar NEXTAUTH_SECRET:
```bash
openssl rand -base64 32
# Copiar el resultado al .env
```

---

## ✅ PASO 3: INSTALAR DEPENDENCIAS

```bash
# Asegurarse de estar en el directorio correcto
cd /home/redsocial.novapasion.com/app

# Opción A: Con npm
npm install

# Opción B: Con bun (si está instalado)
bun install
```

**Tiempo estimado:** 3-5 minutos

---

## ✅ PASO 4: CONFIGURAR PRISMA

### 4.1 Generar Prisma Client
```bash
npx prisma generate
```

### 4.2 Ejecutar Migraciones
```bash
npx prisma migrate deploy
```

Si hay errores de migración, ejecutar:
```bash
npx prisma migrate dev --name init
```

### 4.3 (Opcional) Seed de datos de prueba
```bash
npx prisma db seed
```

### 4.4 Verificar tablas creadas
```bash
mysql -u pasiones_user -p pasiones_platform

SHOW TABLES;
EXIT;
```

Deberías ver tablas como: `User`, `Professional`, `Transaction`, `Message`, etc.

---

## ✅ PASO 5: BUILD DE PRODUCCIÓN

```bash
cd /home/redsocial.novapasion.com/app

# Build
npm run build
```

**Tiempo estimado:** 5-10 minutos

**Nota:** Si hay errores de memoria, ejecutar:
```bash
export NODE_OPTIONS="--max-old-space-size=4096"
npm run build
```

---

## ✅ PASO 6: CONFIGURAR PM2

### 6.1 Instalar PM2 globalmente (si no está instalado)
```bash
npm install -g pm2
```

### 6.2 Crear directorio de logs
```bash
mkdir -p /home/redsocial.novapasion.com/logs
```

### 6.3 Iniciar aplicación con PM2
```bash
cd /home/redsocial.novapasion.com/app

pm2 start ecosystem.config.js
```

### 6.4 Guardar configuración PM2
```bash
pm2 save
```

### 6.5 Configurar PM2 para auto-start al reiniciar servidor
```bash
pm2 startup
# Copiar y ejecutar el comando que aparece
```

### 6.6 Verificar que está corriendo
```bash
pm2 status
pm2 logs pasiones-platform --lines 50
```

---

## ✅ PASO 7: CONFIGURAR PROXY REVERSO LITESPEED

### Opción A: Desde CyberPanel (Recomendado)

1. Abrir navegador: `https://TU_IP_VPS:8090`
2. Login a CyberPanel
3. Click en "Websites" → "List Websites"
4. Click en `redsocial.novapasion.com`
5. Click en "Rewrite Rules"
6. Agregar estas reglas:

```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
RewriteCond %{REQUEST_URI} !^/\.well-known/
RewriteRule ^(.*)$ http://localhost:3000/$1 [P,L]
```

7. Click "Save"

### Opción B: Editar .htaccess manualmente

```bash
nano /home/redsocial.novapasion.com/public_html/.htaccess
```

Pegar el contenido del archivo `htaccess-litespeed.txt`

**Guardar:** `Ctrl + O`, `Enter`, `Ctrl + X`

### Reiniciar LiteSpeed
```bash
sudo systemctl restart lsws
```

---

## ✅ PASO 8: CONFIGURAR SSL (Let's Encrypt)

### Desde CyberPanel:

1. Websites → List Websites
2. Click en `redsocial.novapasion.com`
3. Click en "Issue SSL"
4. Seleccionar "Let's Encrypt"
5. Click "Issue Now"
6. Esperar 1-2 minutos

### Verificar SSL:
```bash
curl -I https://redsocial.novapasion.com
# Debe mostrar: HTTP/2 200 o similar
```

---

## ✅ PASO 9: CONFIGURAR FIREWALL

```bash
# Abrir puerto 3000 (interno para Node.js)
sudo firewall-cmd --permanent --add-port=3000/tcp

# Reload firewall
sudo firewall-cmd --reload

# Verificar
sudo firewall-cmd --list-ports
```

---

## ✅ PASO 10: VERIFICACIÓN FINAL

### 1. Verificar app corriendo localmente
```bash
curl http://localhost:3000
# Debe devolver HTML de la página
```

### 2. Verificar dominio
```bash
curl https://redsocial.novapasion.com
# Debe devolver HTML de la página
```

### 3. Verificar en navegador
Abrir: `https://redsocial.novapasion.com`

### 4. Ver logs en tiempo real
```bash
pm2 logs pasiones-platform
```

### 5. Verificar PM2
```bash
pm2 status
pm2 monit
```

### 6. Verificar base de datos
```bash
cd /home/redsocial.novapasion.com/app
npx prisma studio
# Abrir en navegador el puerto que indica
```

---

## 🎉 ¡DEPLOYMENT COMPLETADO!

Tu aplicación debería estar accesible en:
- **URL:** https://redsocial.novapasion.com
- **Puerto interno:** 3000
- **Proceso:** PM2 (auto-restart habilitado)
- **SSL:** Let's Encrypt (renovación automática)

---

## 🔧 COMANDOS ÚTILES POST-DEPLOYMENT

### Ver logs
```bash
pm2 logs pasiones-platform
pm2 logs pasiones-platform --err  # Solo errores
tail -f /home/redsocial.novapasion.com/logs/pm2-out.log
```

### Reiniciar aplicación
```bash
pm2 restart pasiones-platform
```

### Detener aplicación
```bash
pm2 stop pasiones-platform
```

### Actualizar código desde Git
```bash
cd /home/redsocial.novapasion.com/app
git pull origin main
npm install
npm run build
pm2 restart pasiones-platform
```

### Monitor en tiempo real
```bash
pm2 monit
```

---

## 🐛 TROUBLESHOOTING

### App no responde
```bash
pm2 restart pasiones-platform
pm2 logs pasiones-platform --lines 100
```

### Error 502 Bad Gateway
```bash
# Verificar que app está corriendo
pm2 status

# Verificar puerto 3000
curl http://localhost:3000

# Reiniciar LiteSpeed
sudo systemctl restart lsws
```

### Error de base de datos
```bash
# Verificar conexión
mysql -u pasiones_user -p pasiones_platform

# Regenerar Prisma
npx prisma generate
npx prisma migrate deploy
```

### Memoria insuficiente
```bash
# Ver uso de memoria
free -h
pm2 monit

# Ajustar en ecosystem.config.js:
# max_memory_restart: '1G'
```

---

## 📊 TESTING DE FUNCIONALIDADES

### 1. Registro de usuario
- Ir a: https://redsocial.novapasion.com/register
- Crear cuenta
- Verificar email (si está configurado)

### 2. Login
- Ir a: https://redsocial.novapasion.com/login
- Login con las credenciales

### 3. Crear perfil profesional
- Dashboard → Convertirse en profesional
- Completar formulario
- Subir imágenes (verificar Cloudinary)

### 4. Probar pagos
- Ir a membresías
- Intentar comprar membresía
- Verificar Stripe test mode

### 5. Chat
- Abrir chat con otro usuario
- Enviar mensajes
- Verificar tiempo real

---

## 🎯 PRÓXIMOS PASOS

1. [ ] Crear usuario administrador
2. [ ] Crear categorías profesionales
3. [ ] Crear países y provincias
4. [ ] Poblar con datos de prueba
5. [ ] Testing exhaustivo
6. [ ] Configurar backups automáticos
7. [ ] Monitoreo y analytics
8. [ ] Optimización de performance

---

**¡Éxito con tu deployment!** 🚀
