#!/bin/bash

# ========================================
# PASIONES PLATFORM - DEPLOYMENT CHECKER
# ========================================

set +e  # Don't exit on error

echo "🔍 Verificando requisitos para deployment..."
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

ERRORS=0
WARNINGS=0

# Check 1: Node.js
echo -n "Verificando Node.js... "
if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v)
    echo -e "${GREEN}✓ Instalado ($NODE_VERSION)${NC}"
else
    echo -e "${RED}✗ No instalado${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 2: npm
echo -n "Verificando npm... "
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm -v)
    echo -e "${GREEN}✓ Instalado ($NPM_VERSION)${NC}"
else
    echo -e "${RED}✗ No instalado${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 3: PM2
echo -n "Verificando PM2... "
if command -v pm2 &> /dev/null; then
    PM2_VERSION=$(pm2 -v)
    echo -e "${GREEN}✓ Instalado ($PM2_VERSION)${NC}"
else
    echo -e "${YELLOW}⚠ No instalado (se instalará automáticamente)${NC}"
    WARNINGS=$((WARNINGS + 1))
fi

# Check 4: MySQL
echo -n "Verificando MySQL... "
if command -v mysql &> /dev/null; then
    MYSQL_VERSION=$(mysql --version | awk '{print $3}')
    echo -e "${GREEN}✓ Instalado ($MYSQL_VERSION)${NC}"
else
    echo -e "${RED}✗ No instalado${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 5: package.json
echo -n "Verificando package.json... "
if [ -f "package.json" ]; then
    echo -e "${GREEN}✓ Encontrado${NC}"
else
    echo -e "${RED}✗ No encontrado${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 6: .env file
echo -n "Verificando archivo .env... "
if [ -f ".env" ]; then
    echo -e "${GREEN}✓ Encontrado${NC}"

    # Check important variables
    if grep -q "DATABASE_URL=.*mysql://" .env; then
        echo -e "  ${GREEN}✓ DATABASE_URL configurado${NC}"
    else
        echo -e "  ${YELLOW}⚠ DATABASE_URL no configurado para MySQL${NC}"
        WARNINGS=$((WARNINGS + 1))
    fi

    if grep -q "NEXTAUTH_SECRET=" .env && ! grep -q "NEXTAUTH_SECRET=\"CAMBIAR" .env; then
        echo -e "  ${GREEN}✓ NEXTAUTH_SECRET configurado${NC}"
    else
        echo -e "  ${YELLOW}⚠ NEXTAUTH_SECRET no configurado${NC}"
        WARNINGS=$((WARNINGS + 1))
    fi

    if grep -q "NEXT_PUBLIC_APP_URL=" .env; then
        echo -e "  ${GREEN}✓ NEXT_PUBLIC_APP_URL configurado${NC}"
    else
        echo -e "  ${YELLOW}⚠ NEXT_PUBLIC_APP_URL no configurado${NC}"
        WARNINGS=$((WARNINGS + 1))
    fi
else
    echo -e "${RED}✗ No encontrado${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 7: Prisma schema
echo -n "Verificando Prisma schema... "
if [ -f "prisma/schema.prisma" ]; then
    echo -e "${GREEN}✓ Encontrado${NC}"

    if grep -q 'provider = "mysql"' prisma/schema.prisma; then
        echo -e "  ${GREEN}✓ Configurado para MySQL${NC}"
    else
        echo -e "  ${YELLOW}⚠ No configurado para MySQL${NC}"
        WARNINGS=$((WARNINGS + 1))
    fi
else
    echo -e "${RED}✗ No encontrado${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 8: ecosystem.config.js
echo -n "Verificando ecosystem.config.js... "
if [ -f "ecosystem.config.js" ]; then
    echo -e "${GREEN}✓ Encontrado${NC}"
else
    echo -e "${YELLOW}⚠ No encontrado (se creará automáticamente)${NC}"
    WARNINGS=$((WARNINGS + 1))
fi

# Check 9: Directory permissions
echo -n "Verificando permisos de directorio... "
if [ -w "." ]; then
    echo -e "${GREEN}✓ Permisos correctos${NC}"
else
    echo -e "${RED}✗ Sin permisos de escritura${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 10: Disk space
echo -n "Verificando espacio en disco... "
AVAILABLE_SPACE=$(df -h . | awk 'NR==2 {print $4}')
echo -e "${GREEN}✓ Disponible: $AVAILABLE_SPACE${NC}"

echo ""
echo "=========================================="

if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    echo -e "${GREEN}✨ ¡Todo listo para deployment!${NC}"
    echo ""
    echo "Ejecuta el script de deployment:"
    echo "  chmod +x deploy-vps.sh"
    echo "  ./deploy-vps.sh"
elif [ $ERRORS -eq 0 ]; then
    echo -e "${YELLOW}⚠ Hay $WARNINGS advertencias pero puedes continuar${NC}"
    echo ""
    echo "Revisa las advertencias arriba y ejecuta:"
    echo "  chmod +x deploy-vps.sh"
    echo "  ./deploy-vps.sh"
else
    echo -e "${RED}✗ Hay $ERRORS errores que deben corregirse${NC}"
    echo -e "${YELLOW}⚠ Advertencias: $WARNINGS${NC}"
    echo ""
    echo "Corrígelos antes de continuar con el deployment."
fi

echo "=========================================="
echo ""

exit 0
