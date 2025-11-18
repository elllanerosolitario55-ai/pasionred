# 🚀 GUÍA DE DEPLOYMENT - PASIONES Platform Next.js

## 📦 OPCIÓN 1: VPS Hostinger (RECOMENDADO)

### REQUISITOS PREVIOS:
- VPS con Ubuntu 20.04+ o CentOS 8+
- Acceso SSH root o sudo
- Dominio apuntando al VPS
- Mínimo 2GB RAM

---

## 🔧 PASO 1: CONECTAR AL VPS POR SSH

```bash
ssh root@TU_IP_VPS
# O con usuario:
ssh usuario@TU_IP_VPS
```

---

## 🔧 PASO 2: INSTALAR NODE.JS 18+

```bash
# Actualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar Node.js 18 LTS
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Verificar instalación
node --version  # Debe ser v18.x.x o superior
npm --version
```

---

## 🔧 PASO 3: INSTALAR BUN (Opcional pero Recomendado)

```bash
curl -fsSL https://bun.sh/install | bash

# Agregar a PATH
echo 'export PATH="$HOME/.bun/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc

# Verificar
bun --version
```

---

## 🔧 PASO 4: INSTALAR PM2 (Process Manager)

```bash
sudo npm install -g pm2
pm2 --version
```

---

## 🔧 PASO 5: CLONAR EL PROYECTO DESDE GITHUB

```bash
# Navegar a directorio web
cd /var/www

# Clonar repositorio
git clone https://github.com/elllanerosolitario55-ai/pasionred.git
cd pasionred

# O si ya descargaste el ZIP, súbelo por SFTP a /var/www/pasionred
```

---

## 🔧 PASO 6: CONFIGURAR VARIABLES DE ENTORNO

```bash
# Copiar ejemplo
cp .env.example .env.local

# Editar variables
nano .env.local
```

**Configura estas variables:**

```env
# Base de Datos (Usa Neon - Gratis)
DATABASE_URL="postgresql://usuario:password@host.neon.tech/pasiones?sslmode=require"

# NextAuth
NEXTAUTH_URL="https://TU_DOMINIO.com"
NEXTAUTH_SECRET="genera-un-secret-aleatorio"

# Cloudinary (opcional)
NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME="tu-cloud-name"
CLOUDINARY_API_KEY="..."
CLOUDINARY_API_SECRET="..."

# Stripe (opcional por ahora)
NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY="pk_test_..."
STRIPE_SECRET_KEY="sk_test_..."

# PayPal (opcional por ahora)
NEXT_PUBLIC_PAYPAL_CLIENT_ID="..."
PAYPAL_SECRET="..."
PAYPAL_MODE="sandbox"
```

**Guardar:** Ctrl+O, Enter, Ctrl+X

---

## 🔧 PASO 7: INSTALAR DEPENDENCIAS

```bash
# Con Bun (recomendado - más rápido)
bun install

# O con npm
npm install
```

---

## 🔧 PASO 8: CONFIGURAR BASE DE DATOS (Neon)

**Opción A - Usar Neon (Gratis, en la nube):**

1. Ve a https://neon.tech
2. Crea cuenta gratis
3. Crea proyecto "pasiones-platform"
4. Copia la connection string
5. Pégala en DATABASE_URL del .env.local

**Ejecutar migraciones:**

```bash
# Generar Prisma Client
bun prisma generate

# O con npm
npx prisma generate

# Ejecutar migraciones
bun prisma migrate deploy

# Poblar con datos de prueba
bun prisma db seed
```

---

## 🔧 PASO 9: BUILD DE PRODUCCIÓN

```bash
# Build con Bun
bun run build

# O con npm
npm run build
```

**Esto creará la carpeta `.next` con la aplicación optimizada.**

---

## 🔧 PASO 10: INICIAR CON PM2

```bash
# Crear archivo ecosystem para PM2
cat > ecosystem.config.js << 'EOFPM2'
module.exports = {
  apps: [{
    name: 'pasiones-platform',
    script: 'node_modules/next/dist/bin/next',
    args: 'start',
    instances: 2,
    exec_mode: 'cluster',
    env: {
      NODE_ENV: 'production',
      PORT: 3000
    }
  }]
}
EOFPM2

# Iniciar con PM2
pm2 start ecosystem.config.js

# Guardar configuración
pm2 save

# Autostart en reboot
pm2 startup
```

**Verificar que está corriendo:**

```bash
pm2 status
pm2 logs pasiones-platform
```

---

## 🔧 PASO 11: INSTALAR Y CONFIGURAR NGINX

```bash
# Instalar Nginx
sudo apt install nginx -y

# Crear configuración del sitio
sudo nano /etc/nginx/sites-available/pasiones
```

**Pega esta configuración:**

```nginx
server {
    listen 80;
    server_name TU_DOMINIO.com www.TU_DOMINIO.com;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

**Guardar y activar:**

```bash
# Crear symlink
sudo ln -s /etc/nginx/sites-available/pasiones /etc/nginx/sites-enabled/

# Probar configuración
sudo nginx -t

# Reiniciar Nginx
sudo systemctl restart nginx
```

---

## 🔧 PASO 12: CONFIGURAR SSL (HTTPS)

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtener certificado SSL
sudo certbot --nginx -d TU_DOMINIO.com -d www.TU_DOMINIO.com

# Sigue las instrucciones en pantalla
# Elige opción 2: Redirect HTTP to HTTPS
```

**Certbot configurará automáticamente HTTPS y renovación.**

---

## 🔧 PASO 13: CONFIGURAR FIREWALL

```bash
# Permitir HTTP, HTTPS y SSH
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

---

## ✅ VERIFICACIÓN FINAL

```bash
# Ver estado de servicios
pm2 status
sudo systemctl status nginx

# Ver logs
pm2 logs pasiones-platform

# Probar la app
curl http://localhost:3000
```

**Accede desde el navegador:**
```
https://TU_DOMINIO.com
```

---

## 🔄 COMANDOS ÚTILES

```bash
# Reiniciar app
pm2 restart pasiones-platform

# Ver logs en tiempo real
pm2 logs pasiones-platform --lines 100

# Detener app
pm2 stop pasiones-platform

# Actualizar código
cd /var/www/pasionred
git pull origin main
bun install
bun run build
pm2 restart pasiones-platform

# Ver uso de recursos
pm2 monit
```

---

## 🐛 TROUBLESHOOTING

### Error: "Cannot connect to database"
```bash
# Verificar DATABASE_URL
cat .env.local | grep DATABASE_URL

# Probar conexión
bun prisma studio
```

### Error: "Port 3000 already in use"
```bash
# Ver qué usa el puerto
sudo lsof -i :3000

# Matar proceso
sudo kill -9 PID
```

### Error: "Permission denied"
```bash
# Dar permisos
sudo chown -R $USER:$USER /var/www/pasionred
```

---

## 📊 MONITOREO

```bash
# Instalar herramientas de monitoreo
pm2 install pm2-logrotate

# Ver uso de recursos
htop

# Ver logs del sistema
sudo journalctl -u nginx -f
```

---

## 🎉 ¡DEPLOYMENT COMPLETADO!

Tu aplicación Next.js está corriendo en producción.

**URLs importantes:**
- App: https://TU_DOMINIO.com
- Prisma Studio: `bun prisma studio` (solo en SSH)

**Próximos pasos:**
1. Configurar DNS correctamente
2. Agregar profesionales de ejemplo
3. Configurar Stripe/PayPal en modo live
4. Setup de backups automáticos
5. Monitoreo con Sentry o similar

