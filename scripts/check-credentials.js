#!/usr/bin/env node

/**
 * Script para verificar que todas las credenciales están configuradas
 * Uso: bun run scripts/check-credentials.js
 */

const fs = require('fs');
const path = require('path');

console.log('\n🔍 Verificando Credenciales de PASIONES Platform...\n');

// Cargar .env.local si existe
const envPath = path.join(process.cwd(), '.env.local');
const envExamplePath = path.join(process.cwd(), '.env.example');

if (!fs.existsSync(envPath)) {
  console.log('❌ Archivo .env.local NO encontrado');
  console.log('✅ Solución: Copia .env.example a .env.local');
  console.log('   cp .env.example .env.local\n');
  process.exit(1);
}

console.log('✅ Archivo .env.local encontrado\n');

// Leer variables
const envContent = fs.readFileSync(envPath, 'utf-8');

function checkVar(name, description, required = true) {
  const regex = new RegExp(`${name}="?([^"\\n]+)"?`);
  const match = envContent.match(regex);

  const exists = match && match[1] && match[1] !== '...' && match[1].length > 10;

  const status = exists ? '✅' : (required ? '❌' : '⚠️');
  const value = exists ? '(configurado)' : (required ? '(FALTA)' : '(opcional)');

  console.log(`${status} ${name.padEnd(40)} ${value}`);

  return exists;
}

console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n');

console.log('📊 BASE DE DATOS:');
const hasDB = checkVar('DATABASE_URL', 'PostgreSQL connection string', true);

console.log('\n🔐 AUTENTICACIÓN:');
const hasNextAuthURL = checkVar('NEXTAUTH_URL', 'NextAuth URL', true);
const hasNextAuthSecret = checkVar('NEXTAUTH_SECRET', 'NextAuth Secret', true);

console.log('\n📁 CLOUDINARY (Upload):');
const hasCloudName = checkVar('NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME', 'Cloud Name', true);
const hasCloudKey = checkVar('CLOUDINARY_API_KEY', 'API Key', true);
const hasCloudSecret = checkVar('CLOUDINARY_API_SECRET', 'API Secret', true);

console.log('\n💳 STRIPE (Pagos):');
const hasStripePub = checkVar('NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY', 'Publishable Key', true);
const hasStripeSecret = checkVar('STRIPE_SECRET_KEY', 'Secret Key', true);
checkVar('STRIPE_WEBHOOK_SECRET', 'Webhook Secret', false);

console.log('\n💰 PAYPAL (Pagos):');
const hasPayPalClient = checkVar('NEXT_PUBLIC_PAYPAL_CLIENT_ID', 'Client ID', true);
const hasPayPalSecret = checkVar('PAYPAL_SECRET', 'Secret', true);
checkVar('PAYPAL_MODE', 'Mode (sandbox/live)', false);

console.log('\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n');

// Resumen
const allRequired = hasDB && hasNextAuthURL && hasNextAuthSecret &&
                   hasCloudName && hasCloudKey && hasCloudSecret &&
                   hasStripePub && hasStripeSecret &&
                   hasPayPalClient && hasPayPalSecret;

if (allRequired) {
  console.log('🎉 ¡TODAS las credenciales están configuradas!\n');
  console.log('✅ Puedes ejecutar:');
  console.log('   bun prisma migrate deploy  # Crear tablas en BD');
  console.log('   bun prisma db seed         # Poblar con datos de prueba');
  console.log('   bun run dev                # Iniciar app\n');
} else {
  console.log('⚠️  Faltan algunas credenciales obligatorias\n');
  console.log('📖 Lee la guía completa:');
  console.log('   .same/setup-credentials.md\n');
  console.log('💡 O sigue el Quick Start:');
  console.log('   1. Base de Datos: https://neon.tech (2 min)');
  console.log('   2. Cloudinary: https://cloudinary.com (3 min)');
  console.log('   3. Stripe: https://stripe.com (5 min)');
  console.log('   4. PayPal: https://developer.paypal.com (5 min)\n');
}

console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n');

process.exit(allRequired ? 0 : 1);
