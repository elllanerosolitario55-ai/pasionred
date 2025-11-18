#!/bin/bash

# ========================================
# PASIONES PLATFORM - VPS DEPLOYMENT SCRIPT
# ========================================

set -e  # Exit on error

echo "🚀 Iniciando deployment de Pasiones Platform..."
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
APP_DIR="/home/redsocial.novapasion.com/app"
LOG_DIR="/home/redsocial.novapasion.com/logs"
APP_NAME="pasiones-platform"

# Step 1: Check if we're in the right directory
echo -e "${YELLOW}[1/10]${NC} Verificando directorio..."
if [ ! -f "package.json" ]; then
    echo -e "${RED}Error: No se encontró package.json. ¿Estás en el directorio correcto?${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Directorio correcto${NC}"
echo ""

# Step 2: Check .env file
echo -e "${YELLOW}[2/10]${NC} Verificando archivo .env..."
if [ ! -f ".env" ]; then
    echo -e "${RED}Error: No se encontró archivo .env${NC}"
    echo "Copiando .env.example a .env..."
    cp .env.example .env
    echo -e "${YELLOW}⚠️  Por favor, edita el archivo .env con tus credenciales antes de continuar${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Archivo .env encontrado${NC}"
echo ""

# Step 3: Install dependencies
echo -e "${YELLOW}[3/10]${NC} Instalando dependencias..."
if command -v bun &> /dev/null; then
    echo "Usando Bun..."
    bun install
else
    echo "Usando npm..."
    npm install
fi
echo -e "${GREEN}✓ Dependencias instaladas${NC}"
echo ""

# Step 4: Generate Prisma Client
echo -e "${YELLOW}[4/10]${NC} Generando Prisma Client..."
npx prisma generate
echo -e "${GREEN}✓ Prisma Client generado${NC}"
echo ""

# Step 5: Run migrations
echo -e "${YELLOW}[5/10]${NC} Ejecutando migraciones de base de datos..."
echo "¿Deseas ejecutar las migraciones ahora? (s/n)"
read -r response
if [[ "$response" =~ ^([sS][iI]|[sS])$ ]]; then
    npx prisma migrate deploy
    echo -e "${GREEN}✓ Migraciones ejecutadas${NC}"
else
    echo -e "${YELLOW}⚠️  Migraciones omitidas. Recuerda ejecutarlas manualmente.${NC}"
fi
echo ""

# Step 6: Build application
echo -e "${YELLOW}[6/10]${NC} Construyendo aplicación..."
npm run build
echo -e "${GREEN}✓ Build completado${NC}"
echo ""

# Step 7: Create logs directory
echo -e "${YELLOW}[7/10]${NC} Creando directorio de logs..."
mkdir -p "$LOG_DIR"
echo -e "${GREEN}✓ Directorio de logs creado${NC}"
echo ""

# Step 8: Create ecosystem file if it doesn't exist
echo -e "${YELLOW}[8/10]${NC} Verificando archivo ecosystem.config.js..."
if [ ! -f "ecosystem.config.js" ]; then
    echo "Creando ecosystem.config.js..."
    cat > ecosystem.config.js << 'EOF'
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
EOF
    echo -e "${GREEN}✓ ecosystem.config.js creado${NC}"
else
    echo -e "${GREEN}✓ ecosystem.config.js ya existe${NC}"
fi
echo ""

# Step 9: PM2 management
echo -e "${YELLOW}[9/10]${NC} Gestionando PM2..."
if pm2 describe "$APP_NAME" > /dev/null 2>&1; then
    echo "Aplicación ya existe en PM2. ¿Deseas reiniciarla? (s/n)"
    read -r restart_response
    if [[ "$restart_response" =~ ^([sS][iI]|[sS])$ ]]; then
        pm2 restart "$APP_NAME"
        echo -e "${GREEN}✓ Aplicación reiniciada${NC}"
    fi
else
    echo "Iniciando aplicación con PM2..."
    pm2 start ecosystem.config.js
    pm2 save
    echo -e "${GREEN}✓ Aplicación iniciada con PM2${NC}"
fi
echo ""

# Step 10: Final verification
echo -e "${YELLOW}[10/10]${NC} Verificación final..."
sleep 3
pm2 status

echo ""
echo -e "${GREEN}================================================${NC}"
echo -e "${GREEN}✨ DEPLOYMENT COMPLETADO ✨${NC}"
echo -e "${GREEN}================================================${NC}"
echo ""
echo "📊 Estado de la aplicación:"
echo "   Nombre: $APP_NAME"
echo "   Directorio: $APP_DIR"
echo "   Logs: $LOG_DIR"
echo ""
echo "🔗 URLs:"
echo "   Producción: https://redsocial.novapasion.com"
echo "   Local: http://localhost:3000"
echo ""
echo "📝 Comandos útiles:"
echo "   Ver logs:       pm2 logs $APP_NAME"
echo "   Reiniciar:      pm2 restart $APP_NAME"
echo "   Detener:        pm2 stop $APP_NAME"
echo "   Estado:         pm2 status"
echo "   Monitor:        pm2 monit"
echo ""
echo -e "${YELLOW}⚠️  IMPORTANTE:${NC}"
echo "   1. Verifica que el archivo .env tenga todas las credenciales correctas"
echo "   2. Configura SSL desde CyberPanel"
echo "   3. Configura el proxy reverso en LiteSpeed"
echo "   4. Abre el puerto 3000 en el firewall si es necesario"
echo ""
echo -e "${GREEN}¡Listo para producción! 🚀${NC}"
